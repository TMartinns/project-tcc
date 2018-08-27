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

        $this->view->setAssets('js', $this->configs->js . 'autocomplete.config.js');
    }

    public function indexAction()
    {
        $this->auth->roleCheck(array('C'));
    }

    public function cadastrarAction()
    {
        $this->auth->roleCheck(array('C'));

        $this->view->setFile('index');

        $this->request->setCustomFilters(array(
            'email' => FILTER_VALIDATE_EMAIL
        ));

        $post = $this->request->post();

        if (!empty($post)) {
            $pessoa = array(
                'nome' => $post['nome'],
                'cpf' => $post['cpf']
            );

            $resposta = Pessoa::cadastrar($pessoa);

            if ($resposta->status) {
                $this->load('Services\Random');
                $senha = $this->random->password();
                $usuario = array(
                    'email' => $post['email'],
                    'senha' => password_hash($senha, PASSWORD_DEFAULT),
                    'funcao' => $post['funcao'],
                    'id_pessoa' => $resposta->pessoa->id
                );

                $resposta = Usuario::cadastrar($usuario);

                if ($resposta->status) {
                    $this->load('Helpers\Alert', array(
                        'success',
                        'O cadastro foi completado com sucesso!',
                        'Uma mensagem com os dados cadastrais do usuário foi enviada ao e-mail informado.'
                    ));

                    $this->load('Services\Email');

                    $this->email->setFrom($this->configs->mail->getFrom());

                    $this->email->send(
                        $resposta->usuario->email,
                        '[Conta ADUV] Registro de conta completado',
                        "Você foi registrado com sucesso no sistema ADUV! <br/>
                        Esse e-mail foi enviado automaticamente pelo nosso sistema para informá-lo de seus dados cadastrais. <br/>
                        <br/>
                        -------------------------------------<br/>                      
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

    public function visualizarAction($id = null)
    {
        $this->auth->roleCheck(array('C'));

        if(!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $pessoa = Pessoa::find_by_id($id);

            if(!empty($pessoa)) {
                $usuario = Usuario::find_by_id_pessoa($pessoa->id);
                $telefones = Telefone::find_all_by_id_pessoa($pessoa->id);
                $enderecos = Endereco::find_all_by_id_pessoa($pessoa->id);

                $this->view->setVars(array(
                    'pessoa' => $pessoa,
                    'usuario' => $usuario,
                    'telefones' => $telefones,
                    'enderecos' => $enderecos
                ));
            } else {
                $this->view->setFile('index');
            }
        } else {
            $this->view->setFile('index');
        }
    }

    public function desativarAction($id = null)
    {
        $this->auth->roleCheck(array('C'));

        $this->view->setFile('index');

        if(!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $resposta = Usuario::ativar($id, false);

            $pessoa = Pessoa::find_by_id($resposta->usuario->id);

            if ($resposta->status) {
                $this->load('Helpers\Alert', array(
                    'danger',
                    'Usuário desativado!',
                    "O usuário <strong>$pessoa->nome</strong> foi desativado com sucesso."
                ));
            }
        }
    }

    public function ativarAction($id = null)
    {
        $this->auth->roleCheck(array('C'));

        $this->view->setFile('index');

        if(!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $resposta = Usuario::ativar($id);

            $pessoa = Pessoa::find_by_id($resposta->usuario->id);

            if ($resposta->status) {
                $this->load('Helpers\Alert', array(
                    'success',
                    'Usuário ativado!',
                    "O usuário <strong>$pessoa->nome</strong> foi ativado com sucesso."
                ));
            }
        }
    }

    public function getUsuariosAtivosAction()
    {
        $this->auth->roleCheck(array('C', 'O'));

        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        $nome = $this->request->post('nome');

        $resposta = array();

        if(!empty($nome)) {
            $usuarios = Usuario::getAllByIsAtivoAndPessoasNome(1, $nome);

            foreach ($usuarios as $usuario) {
                if($this->auth->getUserRole() == 'O') {
                    if($usuario->funcao == 'O') {
                        continue;
                    }
                }

                $usuario->funcao = ($usuario->funcao == 'C') ? 'Coordenador(a)' : 'Oficial de promotoria';

                $resposta[] = array(
                    'id' => $usuario->id,
                    'nome' => $usuario->nome,
                    'funcao' => $usuario->funcao
                );
            }
        }

        echo json_encode($resposta);
    }

    public function getUsuarioFuncaoAction()
    {
        $this->auth->roleCheck(array('C', 'O'));

        $this->view->setPath('blank', false)
            ->setFile('index')
            ->setTemplate(false);

        echo json_encode($this->auth->getUserRole());
    }
}