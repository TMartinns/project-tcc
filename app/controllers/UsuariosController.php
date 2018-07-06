<?php

class UsuariosController extends \HXPHP\System\Controller
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
    }

    public function indexAction()
    {
        $this->view->setAssets('css',
            $this->configs->bower . 'gijgo/dist/combined/css/gijgo.min.css');
        $this->view->setAssets('js',
            $this->configs->bower . 'gijgo/dist/combined/js/gijgo.min.js');
        $this->view->setAssets('js',
            $this->configs->bower . 'gijgo/dist/combined/js/messages/messages.pt-br.min.js');
        $this->view->setAssets('js',
            $this->configs->js . 'datepicker.config.js');
    }
}