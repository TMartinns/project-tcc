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

        $this->load('Helpers\Menu',
            $this->request,
            $this->configs,
            $this->auth->getUserRole()
        );

        $this->view->setAssets('css', array(
            $this->configs->bower . 'gijgo/dist/combined/css/gijgo.min.css',
            $this->configs->bower . 'DataTables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css'
        ));

        $this->view->setAssets('js', array(
            $this->configs->bower . 'gijgo/dist/combined/js/gijgo.min.js',
            $this->configs->bower . 'gijgo/dist/combined/js/messages/messages.pt-br.min.js',
            $this->configs->js . 'datepicker.config.js',
            $this->configs->bower . 'DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js',
            $this->configs->bower . 'DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js'
        ));
    }

    public function indexAction()
    {
        $this->auth->roleCheck(array('C', 'O'));
    }

    public function cadastrarAction()
    {
        $this->auth->roleCheck(array('C'));

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

    public function visualizarAction($id = null)
    {
        $this->auth->roleCheck(array('C', 'O'));

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $diligencia = Diligencia::find_by_id($id);

            if (!empty($diligencia)) {
                $mandado = Mandado::find_by_id($diligencia->id_mandado);

                $tipoDiligencia = TipoDiligencia::find_by_id($diligencia->id_tipo_diligencia);

                $promotoria = Promotoria::find_by_id($mandado->id_promotoria);

                $interessado = Pessoa::find_by_id($mandado->id_interessado);

                $this->view->setVars(array(
                    'diligencia' => $diligencia,
                    'mandado' => $mandado,
                    'tipoDiligencia' => $tipoDiligencia,
                    'promotoria' => $promotoria,
                    'interessado' => $interessado
                ));
            } else {
                $this->view->setFile('index');
            }
        } else {
            $this->view->setFile('index');
        }
    }

    public function emAndamentoAction($id = null)
    {
        $this->auth->roleCheck(array('C'));

        $this->view->setFile('index');

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {

            $resposta = Diligencia::editarStatus($id, 'A');

            if ($resposta->status) {
                $mandado = Mandado::find_by_id($resposta->diligencia->id_mandado);

                $this->load('Helpers\Alert', array(
                    'warning',
                    'Diligência editada!',
                    "A diligência <strong>$mandado->numero_protocolo</strong> foi marcada como em andamento!"
                ));
            }
        }
    }

    public function emEsperaAction($id = null)
    {
        $this->auth->roleCheck(array('C'));

        $this->view->setFile('index');

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {

            $resposta = Diligencia::editarStatus($id, 'E');

            if ($resposta->status) {
                $mandado = Mandado::find_by_id($resposta->diligencia->id_mandado);

                $this->load('Helpers\Alert', array(
                    'danger',
                    'Diligência editada!',
                    "A diligência <strong>$mandado->numero_protocolo</strong> foi marcada como em espera!"
                ));
            }
        }
    }

    public function cumpridaAction($id = null)
    {
        $this->auth->roleCheck(array('O'));

        $this->view->setFile('index');

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {

            $resposta = Diligencia::editarStatus($id, 'C');

            if ($resposta->status) {
                $mandado = Mandado::find_by_id($resposta->diligencia->id_mandado);

                $this->load('Helpers\Alert', array(
                    'success',
                    'Diligência editada!',
                    "A diligência <strong>$mandado->numero_protocolo</strong> foi marcada como cumprida!"
                ));

                $eventoCumprimento = TipoEvento::find_by_tipo('Cumprimento');

                $evento = array(
                    'data' => date('Y-m-d H:i:s'),
                    'id_diligencia' => $resposta->diligencia->id,
                    'id_autor' => $this->auth->getUserId(),
                    'id_tipo_evento' => $eventoCumprimento->id
                );

                Evento::cadastrar($evento);
            }
        }
    }

    public function enviarAction()
    {
        $this->auth->roleCheck(array('C', 'O'));

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
                $pessoas = Pessoa::getAllByUsuariosFuncaoAndIsAtivo('O', 1);

                $remessas = Remessa::all(array(
                    'order' => 'data desc'
                ));

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

                            if ($this->auth->getUserRole() == 'C') {
                                $diligencia = Diligencia::find_by_id($diligencia);

                                $tipoDiligenciaUrgente = TipoDiligencia::find_by_tipo('Urgente');
                                if ($diligencia->id_tipo_diligencia == $tipoDiligenciaUrgente->id) {

                                    $notificacao = array(
                                        'id_diligencia' => $diligencia->id,
                                        'id_destinatario' => $idDestinatario,
                                        'mensagem' => 'Uma diligência urgente foi emitida, clique para visualizá-la.',
                                        'data' => date('Y-m-d H:i:s')
                                    );

                                    Notificacao::cadastrar($notificacao);

                                    $this->load('Services\Email');

                                    $this->email->setFrom($this->configs->mail->getFrom());

                                    $destinatario = Usuario::find_by_id_pessoa($idDestinatario);

                                    $mensagem = $notificacao['mensagem'];

                                    $href = $this->getRelativeURL('diligencias', false) . DS . 'visualizar' . DS . $notificacao['id_diligencia'];

                                    $this->email->send(
                                        $destinatario->email,
                                        '[ADUV] DILIGÊNCIA URGENTE',
                                        "<a href='$href'>$mensagem</a> <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        Por favor, não responda essa mensagem. <br/>
                                        Atenciosamente, Suporte ADUV."
                                    );
                                }
                            }
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
        $this->auth->roleCheck(array('C', 'O'));

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