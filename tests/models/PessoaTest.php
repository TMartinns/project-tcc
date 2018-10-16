<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class PessoaTest extends TestCase
{

    public function testEditar()
    {
        $array = array(
            'nome' => 'Mário Otávio Thiago Monteiro',
            'cpf' => '24733223170',
            'genero' => 'M',
            'data_nascimento' => '1988-09-21'
        );

        $this->assertObjectHasAttribute('pessoa', Pessoa::editar(83, $array));

        $this->assertObjectHasAttribute('status', Pessoa::editar(83, $array));

        $this->assertObjectHasAttribute('errors', Pessoa::editar(83, $array));

        $this->assertTrue(Pessoa::editar(83, $array)->status);

        $this->assertNotNull(Pessoa::editar(83, $array)->pessoa);

        $this->assertEmpty(Pessoa::editar(83, $array)->errors);
    }

    public function testGetAllByUsuariosFuncaoAndIsAtivo()
    {
        $this->assertNotEmpty(Pessoa::getAllByUsuariosFuncaoAndIsAtivo('C', 1));
    }

    public function testGetPrimeiroNome()
    {
        $this->assertNotEmpty(Pessoa::getPrimeiroNome(83));
    }

    public function testCadastrar()
    {
        $this->assertTrue(Pessoa::cadastrar(array(
            'nome' => 'Maria Rosa Gonçalves',
            'cpf' => '12580071423'
        ))->status);
    }
}
