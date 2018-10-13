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

        $this->load('Helpers\Menu',
            $this->request,
            $this->configs,
            $this->auth->getUserRole()
        );

        $this->view->setAssets('css', array(
            $this->configs->css . 'wrap-custom-file.css',
            $this->configs->bower . 'DataTables/datatables.min.css'
        ));

        $this->view->setAssets('js', array(
            $this->configs->js . 'wrap-custom-file.js',
            $this->configs->bower . 'DataTables/datatables.min.js'
        ));
    }

    public function indexAction()
    {
        $this->auth->roleCheck(array('C'));
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
        $this->auth->roleCheck(array('C'));

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
        $this->auth->roleCheck(array('C'));

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

    public function visualizarAction($id = null)
    {
        $this->auth->roleCheck(array('C', 'O'));

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $veiculo = Veiculo::find_by_id($id);

            if (!empty($veiculo)) {
                $this->view->setVar('veiculo', $veiculo);
            } else {
                $this->redirectTo('veiculos', false, false);
            }
        } else {
            $this->redirectTo('veiculos', false, false);
        }
    }

    public function registrarUsoVeiculoAction($id = null)
    {
        $this->auth->roleCheck(array('O'));

        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $veiculoUtilizado = VeiculoUtilizado::find_by_id_veiculo_and_id_oficial_and_data_termino($id, $this->auth->getUserId(), null);

            if(empty($veiculoUtilizado)) {
                VeiculoUtilizado::cadastrar(array(
                    'data_inicio' => date('Y-m-d H:i:s'),
                    'id_veiculo' => $id,
                    'id_oficial' => $this->auth->getUserId()
                ));
            } else {
                VeiculoUtilizado::encerrar($veiculoUtilizado);
            }
        }
    }

    public function desativarAction($id = null)
    {
        $this->auth->roleCheck(array('C'));

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
        $this->auth->roleCheck(array('C'));

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
        $this->auth->roleCheck(array('O'));

        $this->view->setPath('blank', true)
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

    public function getVeiculosUtilizadosAction($periodo = null)
    {
        $this->auth->roleCheck(array('C'));

        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        $resposta = array();

        $registros = VeiculoUtilizado::all();

        foreach ($registros as $registro) {
            if (!empty($periodo) && filter_var($periodo, FILTER_VALIDATE_INT)) {
                $dataAtual = new DateTime(Date('Y-m-d'));

                if ($periodo == 1) {
                    $dataAtual->modify('-1 month');

                    if ($registro->data_inicio->format('Y-m-d') < $dataAtual->format('Y-m-d'))
                        continue;
                } else if ($periodo == 2) {
                    $dataAtual->modify('-3 month');

                    if ($registro->data_inicio->format('Y-m-d') < $dataAtual->format('Y-m-d'))
                        continue;
                } else if ($periodo == 3) {
                    $dataAtual->modify('-6 month');

                    if ($registro->data_inicio->format('Y-m-d') < $dataAtual->format('Y-m-d'))
                        continue;
                }
            } else {
                $datas = $this->request->post();

                $this->load('Services\DateConverter');

                $datas['dataInicio'] = $this->dateconverter->toMySqlFormat($datas['dataInicio']);
                $datas['dataFim'] = $this->dateconverter->toMySqlFormat($datas['dataFim']);

                if(!empty($datas['dataInicio']) || !empty($datas['dataFim'])) {
                    if($registro->data_inicio->format('Y-m-d') < $datas['dataInicio'] || $registro->data_inicio->format('Y-m-d') > $datas['dataFim'])
                        continue;
                }
            }

            $veiculo = Veiculo::find_by_id($registro->id_veiculo);
            $oficial = Pessoa::find_by_id($registro->id_oficial);

            $resposta[] = array(
                $registro->data_inicio->format('d/m/Y H:i:s'),
                $veiculo->marca . ' ' . $veiculo->modelo . ' - ' . $veiculo->placa,
                $oficial->nome,
                (empty($registro->data_termino)) ? '' : $registro->data_termino->format('d/m/Y H:i:s')
            );
        }

        echo json_encode($resposta);
    }
}