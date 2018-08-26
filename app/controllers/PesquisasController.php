<?php

class PesquisasController extends \HXPHP\System\Controller
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

    public function getResultadoAction()
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        $info = $this->request->post('info');

        $usuarios = array();

        if($this->auth->getUserRole() == 'C') {
            foreach (Usuario::getAllByEmailOrPessoasNome($info, $info) as $usuario) {
                $usuarios[] = array(
                    'texto' => $usuario->nome,
                    'url' => $this->view->getRelativeURL('usuarios', false) . DS . 'visualizar' . DS . $usuario->id
                );
            }
        }

        $diligencias = array();

        foreach(Mandado::all(array(
            'conditions' => "numero_protocolo like '%$info%'"
        )) as $mandado) {
            $diligencia = Diligencia::find_by_id_mandado($mandado->id);

            $diligencias[] = array(
                'texto' => $mandado->numero_protocolo,
                'url' => $this->view->getRelativeURL('diligencias', false) . DS . 'visualizar' . DS . $diligencia->id
            );
        }

        $veiculos = array();

        if($this->auth->getUserRole() == 'C') {
            foreach (Veiculo::all(array(
                'conditions' => "modelo like '%$info%' or renavam like '%$info%' or placa like '%$info%'"
            )) as $veiculo) {
                $veiculos[] = array(
                    'texto' => $veiculo->modelo,
                    'url' => $this->view->getRelativeURL('veiculos', false) . DS . 'visualizar' . DS . $veiculo->id
                );
            }
        }

        echo json_encode(array(
            'usuarios' => $usuarios,
            'diligencias' => $diligencias,
            'veiculos' => $veiculos
        ));
    }
}