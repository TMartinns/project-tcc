<?php

class RelatoriosController extends \HXPHP\System\Controller
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

        $this->auth->roleCheck(array('C'));

        $this->load('Helpers\Menu',
            $this->request,
            $this->configs,
            $this->auth->getUserRole()
        );

        $this->view->setAssets('css', array(
            $this->configs->bower . 'gijgo/dist/combined/css/gijgo.min.css',
            $this->configs->bower . 'DataTables/datatables.min.css'
        ));

        $this->view->setAssets('js', array(
            $this->configs->bower . 'gijgo/dist/combined/js/gijgo.min.js',
            $this->configs->bower . 'gijgo/dist/combined/js/messages/messages.pt-br.min.js',
            $this->configs->js . 'datepicker.config.js',
            $this->configs->bower . 'DataTables/datatables.min.js',
            $this->configs->js . 'relatorio.diligencias.js',
            $this->configs->js . 'relatorio.veiculos.js'
        ));
    }
}