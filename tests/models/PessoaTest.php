<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class PessoaTest extends TestCase
{

    public function testEditar()
    {
        $this->assertTrue(Pessoa::editar(36, array(
            'nome' => 'João Mário',
            'cpf' => '70077707715',
            'genero' => 'M',
            'data_nascimento' => '1998-09-21'
        ))->status);
    }

    public function testGetAllByUsuariosFuncaoAndIsAtivo()
    {
        $this->assertNotEmpty(Pessoa::getAllByUsuariosFuncaoAndIsAtivo('C', 1));
    }

    public function testGetPrimeiroNome()
    {
        $this->assertNotEmpty(Pessoa::getPrimeiroNome(14));
    }

    public function testCadastrar()
    {
        $this->assertTrue(Pessoa::cadastrar(array(
            'nome' => 'Maria Galdino',
            'cpf' => '80088884713'
        ))->status);
    }
}
