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

    public function cadastrarAction()
    {
        $this->view->setFile('index');

        $this->request->setCustomFilters(array(
            'email' => FILTER_VALIDATE_EMAIL
        ));

        $post = $this->request->post();

        if(!empty($post)) {
            $pessoa = array(
                'nome' => $post['nome'],
                'cpf' => $post['cpf']
            );

            $callback = Pessoa::cadastrar($pessoa);

            if($callback->status == true) {
                $senha = $this->configs->random->password();
                $usuario = array(
                    'nome_usuario' => $post['nome_usuario'],
                    'senha' => password_hash($senha, PASSWORD_DEFAULT),
                    'email' => $post['email'],
                    'permissao' => $post['permissao'],
                    'id_pessoa' => $callback->pessoa->id
                );

                $callback = Usuario::cadastrar($usuario);

                if($callback->status == true) {
                    $this->load('Helpers\Alert', array(
                       'success',
                       'O cadastro foi completado com sucesso!'
                    ));

                } else {
                    $pessoa = Pessoa::find_by_id($usuario['id_pessoa']);
                    $pessoa->delete();
                    $this->load('Helpers\Alert', array(
                        'danger',
                        'Não foi possível completar o cadastro em razão dos seguintes erros:',
                        $callback->errors
                    ));
                }
            } else {
                $this->load('Helpers\Alert', array(
                    'danger',
                    'Não foi possível completar o cadastro em razão dos seguintes erros:',
                    $callback->errors
                ));
            }
        }
    }
}