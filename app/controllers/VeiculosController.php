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
    }

    public function cadastrarAction()
    {
        $this->view->setFile('index');

        $post = $this->request->post();

        if (!empty($post)) {
            $resposta = Veiculo::cadastrar($post);

            if ($resposta->status == true) {
                if (isset($_FILES['imagem']) && !empty($_FILES['imagem']['tmp_name'])) {
                    $imagem = new upload($_FILES['imagem']);

                    if ($imagem->uploaded) {
                        $nome_imagem = md5(uniqid());
                        $imagem->file_new_name_body = $nome_imagem;
                        $imagem->file_new_name_ext = 'png';

                        $dir_path = ROOT_PATH . DS . 'public' . DS . 'uploads' . DS . 'veiculos' . DS . $resposta->veiculo->id . DS;

                        $imagem->process($dir_path);

                        if ($imagem->processed) {
                            $imagem->clean();

                            $this->load('Helpers\Alert', array(
                                'success',
                                'O cadastro foi completado com sucesso!'
                            ));

                            if (!is_null($resposta->veiculo->imagem)) {
                                unlink($dir_path . $resposta->veiculo->imagem);
                            }

                            $resposta->veiculo->imagem = $nome_imagem . '.png';
                            $resposta->veiculo->save(false);
                        } else {
                            $this->load('Helpers\Alert', array(
                                'danger',
                                'Não foi possível salvar a imagem do veículo!',
                                $imagem->error
                            ));
                        }
                    }
                } else {
                    $this->load('Helpers\Alert', array(
                        'success',
                        'O cadastro foi completado com sucesso!'
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
}