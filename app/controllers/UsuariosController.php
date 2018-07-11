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

        $this->load('Helpers\Menu',
            $this->request,
            $this->configs,
            $this->auth->getUserRole()
        );
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

            $resposta = Pessoa::cadastrar($pessoa);

            if($resposta->status == true) {
                $senha = $this->configs->random->password();
                $usuario = array(
                    'nome_usuario' => $post['nome_usuario'],
                    'senha' => password_hash($senha, PASSWORD_DEFAULT),
                    'email' => $post['email'],
                    'funcao' => $post['funcao'],
                    'id_pessoa' => $resposta->pessoa->id
                );

                $resposta = Usuario::cadastrar($usuario);

                if($resposta->status == true) {
                    $this->load('Helpers\Alert', array(
                       'success',
                       'O cadastro foi completado com sucesso!',
                        'Uma mensagem com os dados cadastrais do usuário foi enviada ao e-mail informado.'
                    ));

                    $this->load('Services\Email');

                    $this->email->setFrom($this->configs->mail->getFrom());

                    $nome_usuario = $resposta->usuario->nome_usuario;
                    $this->email->send(
                        $resposta->usuario->email,
                        '[Conta ADUV] Registro de conta completado',
                        "Você foi registrado com sucesso no sistema ADUV! <br/>
                        Esse e-mail foi enviado automaticamente pelo nosso sistema para informá-lo de seus dados cadastrais. <br/>
                        <br/>
                        -------------------------------------<br/>
                        Nome de usuário: $nome_usuario <br/>
                        Senha: $senha <br/>
                        <br/>
                        <br/>
                        <br/>
                        Por favor, não responsa essa mensagem. <br/>
                        Atenciosamente, Suporte ADUV."
                    );
                } else {
                    $pessoa = Pessoa::find_by_id($usuario['id_pessoa']);
                    $pessoa->delete();
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

    public function desativarAction($id)
    {
        $this->view->setFile('index');

        $resposta = Usuario::ativar($id, false);
        $nome_usuario = $resposta->usuario->nome_usuario;
        if($resposta->status) {
            $this->load('Helpers\Alert', array(
                'danger',
                'Usuário desativado!',
                "O usuário <strong>$nome_usuario</strong> foi desativado com sucesso!"
            ));
        }
    }

    public function ativarAction($id)
    {
        $this->view->setFile('index');

        $resposta = Usuario::ativar($id);
        $nome_usuario = $resposta->usuario->nome_usuario;
        if($resposta->status) {
            $this->load('Helpers\Alert', array(
                'success',
                'Usuário ativado!',
                "O usuário <strong>$nome_usuario</strong> foi ativado com sucesso!"
            ));
        }
    }
}