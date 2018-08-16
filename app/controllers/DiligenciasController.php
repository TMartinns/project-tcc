<?php

class DiligenciasController extends \HXPHP\System\Controller
{
    public function __construct(\HXPHP\System\Configs\Config $configs = null)
    {
        parent::__construct($configs);

        $this->load(
            'Services\Auth',
            $configs->auth->after_login,
            $configs->auth->after_logout,
            true
        );

        $this->auth->redirectCheck(false);

        $this->auth->roleCheck(array('C', 'O'));

        $this->load('Helpers\Menu',
            $this->request,
            $this->configs,
            $this->auth->getUserRole()
        );

        $this->view->setAssets('css', $this->configs->bower . 'gijgo/dist/combined/css/gijgo.min.css');

        $this->view->setAssets('js', array(
            $this->configs->js . 'autocomplete.config.js',
            $this->configs->js . 'modal.config.js',
            $this->configs->bower . 'gijgo/dist/combined/js/gijgo.min.js',
            $this->configs->bower . 'gijgo/dist/combined/js/messages/messages.pt-br.min.js',
            $this->configs->js . 'datepicker.config.js'
        ));
    }

    public function cadastrarAction()
    {
        $this->view->setFile('index');

        $post = $this->request->post();

        if (!empty($post)) {
            $mandado = array(
                'descricao' => $post['descricao'],
                'numero_protocolo' => $post['numeroProtocolo'],
                'id_interessado' => $post['idInteressado'],
                'id_promotoria' => $post['promotoria']
            );

            $resposta = Mandado::cadastrar($mandado);

            if ($resposta->status) {
                $mandado = $resposta->mandado;

                $this->load('Services\DateConverter');

                $diligencia = array(
                    'prazo_cumprimento' => $this->dateconverter->toMySqlFormat($post['prazoCumprimento']),
                    'id_mandado' => $resposta->mandado->id,
                    'id_tipo_diligencia' => $post['tipoDiligencia']
                );

                $resposta = Diligencia::cadastrar($diligencia);

                if ($resposta->status) {
                    $this->load('Helpers\Alert', array(
                        'success',
                        'Diligência cadastrada!',
                        "A diligência <strong>$mandado->numero_protocolo</strong> foi cadastrada com sucesso."
                    ));

                    $eventoRegistro = TipoEvento::find_by_tipo('Registro');

                    $evento = array(
                        'data' => date('Y-m-d H:i:s'),
                        'id_diligencia' => $resposta->diligencia->id,
                        'id_autor' => $this->auth->getUserId(),
                        'id_tipo_evento' => $eventoRegistro->id
                    );

                    Evento::cadastrar($evento);
                } else {
                    $this->load('Helpers\Alert', array(
                        'danger',
                        'Não foi possível completar o cadastro!',
                        $resposta->errors
                    ));
                }
            } else {
                $this->load('Helpers\Alert', array(
                    'danger',
                    'Não foi possível completar o cadastro!',
                    $resposta->errors
                ));

            }
        }
    }

    public function enviarAction()
    {
        $this->view->setFile('index');

        $this->request->setCustomFilters(array(
            'diligencias' => array(
                'filter' => FILTER_SANITIZE_NUMBER_INT,
                'flags' => FILTER_FORCE_ARRAY
            )
        ));

        $post = $this->request->post();

        if (!empty($post)) {
            $idDestinatario = (empty($post['idUsuarioEspecifico'])) ? null : $post['idUsuarioEspecifico'];

            if (is_null($idDestinatario) && $post['destinatario'] == 'O') {
                $pessoas = Pessoa::find_by_sql(
                    "SELECT * 
                    FROM pessoas 
                    INNER JOIN usuarios 
                    ON pessoas.id = usuarios.id_pessoa 
                    WHERE usuarios.funcao = 'O' AND usuarios.is_ativo = 1
                    ORDER BY pessoas.nome"
                );

                $remessas = Remessa::find_by_sql("SELECT * FROM remessas ORDER BY data DESC");

                $idDestinatario = $pessoas[0]->id;

                if (!empty($remessas)) {
                    foreach ($remessas as $remessa) {
                        foreach ($pessoas as $key => $pessoa) {
                            if ($remessa->id_destinatario == $pessoa->id) {
                                if (isset($pessoas[$key + 1])) {
                                    $idDestinatario = $pessoas[$key + 1]->id;
                                }

                                break 2;
                            }
                        }
                    }
                }
            }

            $remessa = array(
                'data' => date('Y-m-d H:i:s'),
                'id_remetente' => $this->auth->getUserId(),
                'id_destinatario' => $idDestinatario
            );

            $resposta = Remessa::cadastrar($remessa);

            if ($resposta->status) {
                $diligencias = $post['diligencias'];

                if (!empty($diligencias)) {
                    $this->load('Storage\Session');

                    $ultimasDiligencias = md5(implode($diligencias));

                    if (!$this->session->exists('ultimasDiligenciasEnviadas') || $this->session->get('ultimasDiligenciasEnviadas') != $ultimasDiligencias) {
                        $this->session->set('ultimasDiligenciasEnviadas', $ultimasDiligencias);

                        foreach ($diligencias as $diligencia) {
                            AuxDiligenciasRemessas::cadastrar(array(
                                'id_remessa' => $resposta->remessa->id,
                                'id_diligencia' => $diligencia
                            ));

                            $eventoEnvio = TipoEvento::find_by_tipo('Envio');

                            $evento = array(
                                'data' => date('Y-m-d H:i:s'),
                                'id_diligencia' => $diligencia,
                                'id_autor' => $this->auth->getUserId(),
                                'id_tipo_evento' => $eventoEnvio->id
                            );

                            Evento::cadastrar($evento);
                        }

                        $this->load('Helpers\Alert', array(
                            'success',
                            'Envio efetuado!',
                            "Todas as diligências selecionadas foram enviadas com sucesso."
                        ));
                    } else {
                        $resposta->remessa->delete();
                    }
                } else {
                    $resposta->remessa->delete();

                    $this->load('Helpers\Alert', array(
                        'danger',
                        'Não foi possível completar o envio!',
                        'Você precisa selecionar pelo menos uma diligência.'
                    ));
                }
            } else {
                $this->load('Helpers\Alert', array(
                    'danger',
                    'Não foi possível completar o envio!',
                    $resposta->errors
                ));
            }
        }
    }

    public function receberAction()
    {
        $this->view->setFile('index');

        $this->request->setCustomFilters(array(
            'remessas' => array(
                'filter' => FILTER_SANITIZE_NUMBER_INT,
                'flags' => FILTER_FORCE_ARRAY
            )
        ));

        $post = $this->request->post();

        if (!empty($post)) {
            $remessas = $post['remessas'];

            if (!is_null($remessas)) {
                $this->load('Storage\Session');

                $ultimasRemessas = md5(implode($remessas));

                if (!$this->session->exists('ultimasRemessasRecebidas') || $this->session->get('ultimasRemessasRecebidas') != $ultimasRemessas) {
                    $this->session->set('ultimasRemessasRecebidas', $ultimasRemessas);

                    foreach ($remessas as $remessa) {
                        $diligencias = AuxDiligenciasRemessas::find_all_by_id_remessa($remessa);

                        $eventoRecebimento = TipoEvento::find_by_tipo('Recebimento');

                        foreach ($diligencias as $diligencia) {
                            Evento::cadastrar(array(
                                'data' => date('Y-m-d H:i:s'),
                                'id_diligencia' => $diligencia->id_diligencia,
                                'id_autor' => $this->auth->getUserId(),
                                'id_tipo_evento' => $eventoRecebimento->id
                            ));
                        }

                        Remessa::receber($remessa);
                    }

                    $this->load('Helpers\Alert', array(
                        'success',
                        'Recebimento efetuado!',
                        'Todas as remessas selecionadas foram recebidas com sucesso!'
                    ));
                }
            } else {
                $this->load('Helpers\Alert', array(
                    'danger',
                    'Não foi possível completar o recebimento!',
                    'Você precisa selecionar pelo menos uma remessa.'
                ));
            }
        }
    }
}