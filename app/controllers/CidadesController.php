<?php

class CidadesController extends \HXPHP\System\Controller
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
    }

    public function indexAction()
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);
    }

    public function getCidadesAction($id = null)
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        $resposta = array();

        if(!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $cidades = Cidade::find_all_by_id_uf($id);

            foreach ($cidades as $cidade) {
                $resposta[] = array(
                    'id' => $cidade->id,
                    'nome' => $cidade->nome
                );
            }
        }

        echo json_encode($resposta);
    }
}