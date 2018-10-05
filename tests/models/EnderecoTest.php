<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class EnderecoTest extends TestCase
{

    public function testEditar()
    {
        $this->assertTrue(Endereco::editar(51, array(
            'logradouro' => 'Rua Garibaldo Leão',
            'numero' => '145',
            'complemento' => 'Q. 20, L. 10-D',
            'cep' => '7145236',
            'bairro' => 'Vila Conceição',
            'id_cidade' => 2300705,
            'id_pessoa' => 51
        ))->status);
    }

    public function testCadastrar()
    {
        $this->assertTrue(Endereco::cadastrar(array(
            'logradouro' => 'Rua Clodoveu Leão',
            'numero' => '270',
            'complemento' => 'Q. 14, L. 15-B',
            'cep' => '75902760',
            'bairro' => 'Vila Borges',
            'id_cidade' => 5218805,
            'id_pessoa' => 81
        ))->status);
    }
}
