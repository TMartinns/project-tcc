<?php

class VeiculosController extends \HXPHP\System\Controller
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

        $this->view->setAssets('css', $this->configs->css . 'wrap-custom-file.css');

        $this->view->setAssets('js', array(
            $this->configs->js . 'modal.config.js',
            $this->configs->js . 'wrap-custom-file.js'
        ));
    }

    private function imagemUpload($veiculo)
    {
        $resposta = new \stdClass();
        $resposta->veiculo = $veiculo;
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

                $dir_path = ROOT_PATH . DS . 'public' . DS . 'uploads' . DS . 'veiculos' . DS . $veiculo->id . DS;

                $imagem->process($dir_path);

                if ($imagem->processed) {
                    $imagem->clean();

                    if (!is_null($veiculo->imagem)) {
                        unlink($dir_path . $veiculo->imagem);
                    }

                    $veiculo->imagem = $nomeImagem . '.png';
                    $veiculo->save(false);

                    $resposta->veiculo = $veiculo;
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

    public function cadastrarAction()
    {
        $this->view->setFile('index');

        $post = $this->request->post();

        if (!empty($post)) {
            $resposta = Veiculo::cadastrar($post);

            if ($resposta->status) {
                $resposta = $this->imagemUpload($resposta->veiculo);

                $modelo = $resposta->veiculo->modelo;

                $alertSuccess = array(
                    'success',
                    'Veículo cadastrado!',
                    "O veículo <strong>$modelo</strong> foi cadastrado com sucesso."
                );

                if (!$resposta->status) {
                    if (empty($resposta->error)) {
                        $this->load('Helpers\Alert', $alertSuccess);
                    } else {
                        $this->load('Helpers\Alert', array(
                            'danger',
                            'Não foi possível salvar a imagem do veículo!',
                            $resposta->error
                        ));
                    }
                } else {
                    $this->load('Helpers\Alert', $alertSuccess);
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

    public function editarAction($id = null)
    {
        $this->view->setFile('index');

        $post = $this->request->post();

        if (!empty($post) && !empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $resposta = Veiculo::editar($id, $post);

            if ($resposta->status) {
                $resposta = $this->imagemUpload($resposta->veiculo);

                $modelo = $resposta->veiculo->modelo;

                $alertSuccess = array(
                    'success',
                    'Veículo editado!',
                    "O veículo <strong>$modelo</strong> foi alterado com sucesso."
                );

                if (!$resposta->status) {
                    if (empty($resposta->error)) {
                        $this->load('Helpers\Alert', $alertSuccess);
                    } else {
                        $this->load('Helpers\Alert', array(
                            'danger',
                            'Não foi possível salvar a imagem do veículo!',
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
        }
    }

    public function registrarUsoVeiculoAction($id = null)
    {

        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            VeiculoUtilizado::cadastrar(array(
                'data_inicio' => date('Y-m-d H:i:s'),
                'id_veiculo' => $id,
                'id_oficial' => $this->auth->getUserId()
            ));
        }
    }

    public function desativarAction($id = null)
    {
        $this->view->setFile('index');

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $resposta = Veiculo::ativar($id, false);

            $modelo = $resposta->veiculo->modelo;

            if ($resposta->status) {
                $this->load('Helpers\Alert', array(
                    'danger',
                    'Veículo desativado!',
                    "O veículo <strong>$modelo</strong> foi desativado com sucesso."
                ));
            }
        }
    }

    public function ativarAction($id = null)
    {
        $this->view->setFile('index');

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $resposta = Veiculo::ativar($id);

            $modelo = $resposta->veiculo->modelo;

            if ($resposta->status) {
                $this->load('Helpers\Alert', array(
                    'success',
                    'Veículo ativado!',
                    "O veículo <strong>$modelo</strong> foi ativado com sucesso."
                ));
            }
        }
    }

    public function getVeiculoAction($id = null)
    {
        $this->view->setPath('blank', false)
            ->setFile('index')
            ->setTemplate(false);

        $resposta = array();

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $veiculo = Veiculo::find_by_id_and_is_ativo($id, 1);

            if (!empty($veiculo)) {
                $resposta = array(
                    'id' => $veiculo->id,
                    'modelo' => $veiculo->modelo,
                    'imagem' => $veiculo->imagem
                );
            }
        }

        echo json_encode($resposta);
    }
}