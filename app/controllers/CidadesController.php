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

        $this->load('Helpers\Menu',
            $this->request,
            $this->configs,
            $this->auth->getUserRole()
        );
    }

    public function getCidadesAction()
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        $cidades = Cidade::find_all_by_id_uf($this->request->post());

        $resposta = array();

        foreach ($cidades as $cidade) {
            $resposta[] = array(
                'id' => $cidade->id,
                'nome' => $cidade->nome
            );
        }

        echo json_encode($resposta);
    }
}