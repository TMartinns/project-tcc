<?php

class InicioController extends \HXPHP\System\Controller
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

        $this->load('Helpers\Menu',
            $this->auth->getUserRole()
        );

        $this->auth->redirectCheck(false);
    }

}