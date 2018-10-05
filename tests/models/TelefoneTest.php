<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class TelefoneTest extends TestCase
{

    public function testCadastrar()
    {
        $this->assertTrue(Telefone::cadastrar(array(
            'ddd' => '64',
            'numero' => '996487145',
            'id_pessoa' => 81
        ))->status);
    }

    public function testEditar()
    {
        $this->assertTrue(Telefone::editar(78, array(
            'ddd' => '65',
            'numero' => '15412456',
            'id_pessoa' => 78
        ))->status);
    }
}
