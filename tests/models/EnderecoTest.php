<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class EnderecoTest extends TestCase
{

    public function testEditar()
    {
        $array = array(
            'logradouro' => 'Rua das Rosas',
            'numero' => '952',
            'complemento' => 'Q.5, L. 12',
            'cep' => '75912087',
            'bairro' => 'Residencial Atalaia',
            'id_cidade' => 5218805
        );

        $this->assertObjectHasAttribute('endereco', Endereco::editar(83, $array));

        $this->assertObjectHasAttribute('status', Endereco::editar(83, $array));

        $this->assertObjectHasAttribute('errors', Endereco::editar(83, $array));

        $this->assertTrue(Endereco::editar(83, $array)->status);

        $this->assertNotNull(Endereco::editar(83, $array)->endereco);

        $this->assertEmpty(Endereco::editar(83, $array)->errors);
    }

    public function testCadastrar()
    {
        $array = array(
            'logradouro' => 'Rua Clodoveu Leão',
            'numero' => '270',
            'complemento' => 'Q. 14, L. 15-B',
            'cep' => '75902760',
            'bairro' => 'Vila Borges',
            'id_cidade' => 5218805,
            'id_pessoa' => 88
        );

        $this->assertObjectHasAttribute('status', Endereco::cadastrar($array));

        $this->assertObjectHasAttribute('endereco', Endereco::cadastrar($array));

        $this->assertObjectHasAttribute('errors', Endereco::cadastrar($array));

        $this->assertTrue(Endereco::cadastrar($array)->status);

        $this->assertNotNull(Endereco::cadastrar($array)->endereco);

        $this->assertEmpty(Endereco::cadastrar($array)->errors);
    }
}
