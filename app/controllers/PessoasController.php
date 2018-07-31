<?php

class PessoasController extends \HXPHP\System\Controller
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
    }

    public function cadastrarAction()
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        $post = $this->request->post();

        if(!empty($post)) {
            $pessoa = array(
                'nome' => $post['nome'],
                'cpf' => $post['cpf'],
                'data_nascimento' => $post['data_nascimento']
            );

            $resposta = Pessoa::cadastrar($pessoa);

            if($resposta->status) {
                $pessoa = $resposta->pessoa;

                $telefone = array(
                    'ddd' => $post['ddd'],
                    'numero' => $post['numeroTelefone'],
                    'id_pessoa' => $pessoa->id
                );

                $resposta = Telefone::cadastrar($telefone);

                if($resposta->status) {
                    $telefone = $resposta->telefone;

                    $endereco = array(
                        'logradouro' => $post['logradouro'],
                        'numero' => $post['numeroEndereco'],
                        'complemento' => $post['complemento'],
                        'cep' => $post['cep'],
                        'bairro' => $post['bairro'],
                        'id_cidade' => ($post['cidade'] == 0) ? null : $post['cidade'],
                        'id_pessoa' => $pessoa->id,
                    );

                    $resposta = Endereco::cadastrar($endereco);

                    if($resposta->status) {
                        $resposta = array(
                            'pessoa' => array(
                                'id' => $pessoa->id,
                                'nome' => $pessoa->nome
                            ),
                            'status' => $resposta->status,
                            'errors' => $resposta->errors
                        );

                        echo json_encode($resposta);
                    } else {
                        $pessoa->delete();
                        $telefone->delete();

                        echo json_encode($resposta);
                    }
                } else {
                    $pessoa->delete();

                    echo json_encode($resposta);
                }
            } else {
                echo json_encode($resposta);
            }
        }
    }

    public function getPessoasAction()
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        $nome = $this->request->post('nome');

        $pessoas = Pessoa::find_by_sql("select * from pessoas where nome like '%$nome%'");

        $resposta = array();

        foreach ($pessoas as $pessoa) {
            $resposta[] = array(
                'id' => $pessoa->id,
                'nome' => $pessoa->nome,
                'cpf' => $pessoa->cpf
            );
        }

        echo json_encode($resposta);
    }
}