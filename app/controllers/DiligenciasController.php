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

        if(!empty($post)) {
            $mandado = array(
                'descricao' => $post['descricao'],
                'numero_protocolo' => $post['numero_protocolo'],
                'id_interessado' => $post['id_interessado'],
                'id_promotoria' => $post['id_promotoria']
            );

            $resposta = Mandado::cadastrar($mandado);

            if($resposta->status) {
                $mandado = $resposta->mandado;

                $this->load('Services\DateConverter');

                $diligencia = array(
                    'prazo_cumprimento' => $this->dateconverter->toMySqlFormat($post['prazo_cumprimento']),
                    'id_mandado' => $resposta->mandado->id,
                    'id_tipo_diligencia' => $post['id_tipo_diligencia']
                );

                $resposta = Diligencia::cadastrar($diligencia);

                if($resposta->status) {
                    $this->load('Helpers\Alert', array(
                        'success',
                        'Diligência cadastrada!',
                        "A diligência <strong>$mandado->numero_protocolo</strong> foi cadastrada com sucesso."
                    ));

                    $evento = array(
                        'evento_ocorrido' => 'Registro',
                        'data' => date('Y-m-d H:i:s'),
                        'id_diligencia' => $resposta->diligencia->id,
                        'id_autor' => $this->auth->getUserId()
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
}