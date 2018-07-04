<?php

class IndexController extends \HXPHP\System\Controller
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

        $this->auth->redirectCheck(true);
    }

    public function indexAction()
    {
        $this->view->setHeader('index\header');
        $this->view->setFooter('index\footer');
    }

    public function acessarAction()
    {
        $this->view->setFile('index');

        $post = $this->request->post();
        $nome_usuario = $post['nome_usuario'];
        $usuario = Usuario::find_by_sql("select * from usuarios where nome_usuario = '$nome_usuario'")[0];

        if (password_verify($post['senha'], $usuario->senha)) {
            $this->auth->login($usuario->id_pessoa, $usuario->nome_usuario, $usuario->permissao);
        }
    }
}