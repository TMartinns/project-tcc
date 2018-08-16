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

        $this->auth->roleCheck(array('C'));

        $this->load('Helpers\Menu',
            $this->request,
            $this->configs,
            $this->auth->getUserRole()
        );

        $this->view->setAssets('js', $this->configs->js . 'autocomplete.config.js');
    }

    public function cadastrarAction()
    {
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

    public function desativarAction($id)
    {
        $this->view->setFile('index');

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

    public function ativarAction($id)
    {
        $this->view->setFile('index');

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

    public function getUsuariosAction()
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        $nome = $this->request->post('nome');

        $pessoas = Pessoa::find_by_sql("select * from pessoas where nome like '%$nome%'");

        $resposta = array();

        foreach ($pessoas as $pessoa) {
            $html = "<div class='card'>
            <div class='card-body'>
            <div class='offset-md-4 col-md-4 offset-lg-4 col-lg-4 text-center'>";

            $usuario = Usuario::find_by_id_pessoa($pessoa->id);

            if (!is_null($usuario)) {
                $telefones = Telefone::find_all_by_id_pessoa($pessoa->id);
                $enderecos = Endereco::find_all_by_id_pessoa($pessoa->id);

                $img = new \stdClass();
                $img->title = '';
                if (is_null($usuario->imagem)) {
                    $img->title = 'Icon designed by Eucalyp from Flaticon';
                    $avatar = '';
                    if (is_null($pessoa->genero)) {
                        $genero = array('man-' . mt_rand(0, 34), 'woman-' . mt_rand(0, 12));
                        $avatar = $genero[mt_rand(0, 1)];
                    } else {
                        $avatar = ($pessoa->genero == 'M') ? 'man-' . mt_rand(0, 34) : 'woman-' . mt_rand(0, 12);
                    }
                    $img->src = $this->configs->img . "avatars/usuarios/$avatar";
                } else {
                    $img->src = $this->configs->uploads . "usuarios/$usuario->id_pessoa";
                }

                $usuario->funcao = ($usuario->funcao == 'C') ? 'Coordenador' : 'Oficial de promotoria';

                $html .= "<p>
                    <img class='rounded-circle avatar bg-dark' src='$img->src' width='180' height='180' title='$img->title'>
                    </p>
                    <hr/>
                    <h5 class='card-title'>$pessoa->nome</h5>
                    </div>
                    <div class='row'>
                    <div class='offset-md-2 col-md-4 offset-lg-2 col-lg-4'>                  
                    <p class='card-text'><h6>E-mail</h6>$usuario->email</p>
                    <p class='card-text'><h6>Função</h6>$usuario->funcao</p>
                    <p class='card-text'><h6>CPF</h6>$pessoa->cpf</p>
                    <p class='card-text'><h6>Data de nascimento</h6>$pessoa->data_nascimento</p>                  
                    </div>
                    <div class='col-md-4 col-lg-4'>
                    <p class='card-text'><h6>Telefones</h6>";

                if (!is_null($telefones)) {
                    foreach ($telefones as $key => $telefone) {
                        $hr = ($key != count($telefones) - 1) ? "<hr/>" : '';
                        $html .= "<p class='card-text'> ($telefone->ddd) $telefone->numero</p>$hr";
                    }
                }

                $html .= "</p>
                    <p class='card-text'><h6>Enderecos</h6>";

                if (!is_null($enderecos)) {
                    foreach ($enderecos as $key => $endereco) {
                        $cidade = Cidade::find_by_id($endereco->id_cidade);

                        $uf = Uf::find_by_id($cidade->id_uf);

                        $hr = ($key != count($telefones) - 1) ? "<hr/>" : '';
                        $html .= "<p class='card-text'> 
                            $endereco->logradouro, $endereco->numero, $endereco->complemento, $endereco->bairro
                            <br/>
                            $cidade->nome/$uf->uf
                            <br/>
                            $endereco->cep
                            </p>
                            $hr";
                    }
                }

                $html .= "</p>
                    </div>
                    </div>
                    </div>
                    <div class='card-footer text-center bg-white'>";

                if ($usuario->is_ativo == 1) {
                    $href = $this->getRelativeURL('usuarios', false) . '/desativar/' . $usuario->id_pessoa;
                    $html .= "<a class='btn btn-outline-danger' href='$href'>
                        <span><i class='fas fa-lock'></i></span>
                        Desativar
                        </a>";
                } else {
                    $href = $this->getRelativeURL('usuarios', false) . '/ativar/' . $usuario->id_pessoa;
                    $html .= "<a class='btn btn-outline-success' href='$href'>
                        <span><i class='fas fa-lock-open'></i></span>
                        Ativar
                        </a>";
                }

                $html .= "</div>
                    </div>
                    <br/>";

                $resposta[] = array(
                    'email' => $usuario->email,
                    'nome' => $pessoa->nome,
                    'html' => $html
                );
            }
        }

        echo json_encode($resposta);
    }

    public function getUsuariosAtivosAction()
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        $nome = $this->request->post('nome');

        $usuarios = Usuario::find_by_sql(
            "select * from usuarios 
            inner join pessoas 
            on usuarios.id_pessoa = pessoas.id 
            where pessoas.nome like '%$nome%' and usuarios.is_ativo = 1"
        );

        $resposta = array();
        foreach ($usuarios as $usuario) {
            $usuario->funcao = ($usuario->funcao == 'C') ? 'Coordenador(a)' : 'Oficial de promotoria';

            $resposta[] = array(
                'id' => $usuario->id,
                'nome' => $usuario->nome,
                'funcao' => $usuario->funcao
            );
        }

        echo json_encode($resposta);
    }
}