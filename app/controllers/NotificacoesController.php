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

    public function indexAction()
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);
    }

    public function vistoAction($idNotificacao = null)
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        if(!empty(filter_var($idNotificacao, FILTER_VALIDATE_INT))) {
            if(!empty(Notificacao::find_by_id($idNotificacao))) {
                Notificacao::visto($idNotificacao);
            }
        }
    }
}