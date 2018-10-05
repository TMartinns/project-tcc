<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class VeiculoTest extends TestCase
{

    public function testEditar()
    {
        $this->assertTrue(Veiculo::editar(8, array(
            'marca' => 'Volkswagen',
            'modelo' => 'Fox',
            'cor' => 'Azul',
            'renavam' => '14785695232',
            'ano' => '2012',
            'placa' => 'AHXS145'
        ))->status);
    }

    public function testCadastrar()
    {
        $this->assertTrue(Veiculo::cadastrar(array(
            'marca' => 'Ford',
            'modelo' => 'KA',
            'cor' => 'Preto',
            'renavam' => '74541523510',
            'ano' => '2018',
            'placa' => 'ABIH146'
        ))->status);
    }

    public function testAtivar()
    {
        $this->assertTrue(Veiculo::ativar(13)->status);
    }
}
