<?php

class NotificacoesController extends \HXPHP\System\Controller
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

        $this->auth->roleCheck(array('O'));
    }

    public function vistoAction($id = null)
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        if(!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            if(!empty(Notificacao::find_by_id($id))) {
                Notificacao::visto($id);
            }
        }
    }
}