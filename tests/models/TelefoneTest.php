<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class TelefoneTest extends TestCase
{

    public function testCadastrar()
    {
        $array = array(
            'ddd' => '64',
            'numero' => '987473509',
            'id_pessoa' => 88
        );

        $this->assertObjectHasAttribute('status', Telefone::cadastrar($array));

        $this->assertObjectHasAttribute('telefone', Telefone::cadastrar($array));

        $this->assertObjectHasAttribute('errors', Telefone::cadastrar($array));

        $this->assertTrue(Telefone::cadastrar($array)->status);

        $this->assertNotNull(Telefone::cadastrar($array)->telefone);

        $this->assertEmpty(Telefone::cadastrar($array)->errors);
    }

    public function testEditar()
    {
        $array = array(
            'ddd' => '64',
            'numero' => '996202631'
        );

        $this->assertObjectHasAttribute('telefone', Telefone::editar(83, $array));

        $this->assertObjectHasAttribute('status', Telefone::editar(83, $array));

        $this->assertObjectHasAttribute('errors', Telefone::editar(83, $array));

        $this->assertTrue(Telefone::editar(83, $array)->status);

        $this->assertNotNull(Telefone::editar(83, $array)->telefone);

        $this->assertEmpty(Telefone::editar(83, $array)->errors);
    }
}
