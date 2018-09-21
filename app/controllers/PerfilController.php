<?php

class PerfilController extends \HXPHP\System\Controller
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

        $this->auth->roleCheck(array('C', 'O'));

        $this->load('Helpers\Menu',
            $this->request,
            $this->configs,
            $this->auth->getUserRole()
        );

        $this->view->setAssets('css', array(
            $this->configs->css . 'wrap-custom-file.css',
            $this->configs->bower . 'gijgo/dist/combined/css/gijgo.min.css',
        ));

        $this->view->setAssets('js', array(
            $this->configs->js . 'wrap-custom-file.js',
            $this->configs->bower . 'gijgo/dist/combined/js/gijgo.min.js',
            $this->configs->bower . 'gijgo/dist/combined/js/messages/messages.pt-br.min.js',
            $this->configs->js . 'datepicker.config.js',
            $this->configs->js . 'modal.config.js'
        ));
    }

    public function indexAction()
    {
        $pessoa = Pessoa::find_by_id($this->auth->getUserId());

        $usuario = Usuario::find_by_id_pessoa($pessoa->id);
        $telefone = Telefone::find_by_id_pessoa($pessoa->id);
        $endereco = Endereco::find_by_id_pessoa($pessoa->id);

        $this->view->setVars(array(
            'pessoa' => $pessoa,
            'usuario' => $usuario,
            'telefone' => $telefone,
            'endereco' => $endereco
        ));
    }

    private function imagemUpload($usuario)
    {
        $resposta = new \stdClass();
        $resposta->usuario = $usuario;
        $resposta->status = false;
        $resposta->error = '';

        if (isset($_FILES['imagem']) && !empty($_FILES['imagem']['tmp_name'])) {
            $imagem = new upload($_FILES['imagem'], 'pt_BR');

            if ($imagem->uploaded) {
                $imagem->allowed = array('image/jpg', 'image/png', 'image/jpeg');
                $imagem->file_max_size = '1000000';

                $nomeImagem = md5(uniqid());
                $imagem->file_new_name_body = $nomeImagem;
                $imagem->file_new_name_ext = 'png';
                $imagem->image_resize = true;
                $imagem->image_x = 360;
                $imagem->image_ratio_y = true;

                $dir_path = ROOT_PATH . DS . 'public' . DS . 'uploads' . DS . 'usuarios' . DS . $usuario->id . DS;

                $imagem->process($dir_path);

                if ($imagem->processed) {
                    $imagem->clean();

                    if (!is_null($usuario->imagem)) {
                        unlink($dir_path . $usuario->imagem);
                    }

                    $usuario->imagem = $nomeImagem . '.png';
                    $usuario->save(false);

                    $resposta->usuario = $usuario;
                    $resposta->status = true;

                    return $resposta;
                } else {
                    $resposta->error = $imagem->error;

                    return $resposta;
                }
            }
        } else {
            return $resposta;
        }
    }

    public function editarAction($id = null)
    {
        $this->view->setFile('index');

        $this->request->setCustomFilters(array(
            'email' => FILTER_VALIDATE_EMAIL
        ));

        $post = $this->request->post();

        if (!empty($post) && !empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $this->load('Services\DateConverter');

            $pessoa = array(
                'nome' => $post['nome'],
                'cpf' => $post['cpf'],
                'data_nascimento' => $this->dateconverter->toMySqlFormat($post['dataNascimento'])
            );

            $resposta = Pessoa::editar($id, $pessoa);

            if ($resposta->status) {
                $usuario = array(
                    'email' => $post['email']
                );

                $resposta = Usuario::editar($id, $usuario);

                if($resposta->status) {
                    $usuario = $resposta->usuario;

                    $telefone = array(
                        'ddd' => $post['ddd'],
                        'numero' => $post['numeroTelefone']
                    );

                    $resposta = Telefone::editar($id, $telefone);

                    if($resposta->status) {
                        $endereco = array(
                            'logradouro' => $post['logradouro'],
                            'numero' => $post['numeroEndereco'],
                            'complemento' => $post['complemento'],
                            'cep' => $post['cep'],
                            'bairro' => $post['bairro'],
                            'id_cidade' => ($post['cidade'] == 0) ? '' : $post['cidade']
                        );

                        $resposta = Endereco::editar($id, $endereco);

                        if($resposta->status) {
                            $resposta = $this->imagemUpload($usuario);

                            $alertSuccess = array(
                                'success',
                                'Perfil editado!',
                                "O seu perfil foi alterado com sucesso."
                            );

                            if (!$resposta->status) {
                                if (empty($resposta->error)) {
                                    $this->load('Helpers\Alert', $alertSuccess);
                                } else {
                                    $this->load('Helpers\Alert', array(
                                        'danger',
                                        'Não foi possível salvar a imagem de perfil!',
                                        $resposta->error
                                    ));
                                }
                            } else {
                                $this->load('Helpers\Alert', $alertSuccess);
                            }
                        } else {
                            $this->load('Helpers\Alert', array(
                                'danger',
                                'Não foi possível completar as alterações!',
                                $resposta->errors
                            ));
                        }
                    } else {
                        $this->load('Helpers\Alert', array(
                            'danger',
                            'Não foi possível completar as alterações!',
                            $resposta->errors
                        ));
                    }
                } else {
                    $this->load('Helpers\Alert', array(
                        'danger',
                        'Não foi possível completar as alterações!',
                        $resposta->errors
                    ));
                }
            } else {
                $this->load('Helpers\Alert', array(
                    'danger',
                    'Não foi possível completar as alterações!',
                    $resposta->errors
                ));
            }
        }

        $pessoa = Pessoa::find_by_id($this->auth->getUserId());

        $usuario = Usuario::find_by_id_pessoa($pessoa->id);
        $telefone = Telefone::find_by_id_pessoa($pessoa->id);
        $endereco = Endereco::find_by_id_pessoa($pessoa->id);

        $this->view->setVars(array(
            'pessoa' => $pessoa,
            'usuario' => $usuario,
            'telefone' => $telefone,
            'endereco' => $endereco
        ));
    }
}