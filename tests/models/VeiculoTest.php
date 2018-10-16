<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class VeiculoTest extends TestCase
{

    public function testEditar()
    {
        $array = array(
            'marca' => 'EFFA',
            'modelo' => 'Plutus 3.2 8V 4x2',
            'cor' => 'Branco',
            'renavam' => '55410329921',
            'ano' => '2011',
            'placa' => 'KEF2546'
        );

        $this->assertObjectHasAttribute('status', Veiculo::editar(23, $array));

        $this->assertObjectHasAttribute('veiculo', Veiculo::editar(23, $array));

        $this->assertObjectHasAttribute('errors', Veiculo::editar(23, $array));

        $this->assertTrue(Veiculo::editar(23, $array)->status);

        $this->assertNotNull(Veiculo::editar(23, $array)->veiculo);

        $this->assertEmpty(Veiculo::editar(23, $array)->errors);
    }

    public function testCadastrar()
    {
        $this->assertTrue(Veiculo::cadastrar(array(
            'marca' => 'BRM',
            'modelo' => 'Buggy/M-8/M-8 Long',
            'cor' => 'Vermelho',
            'renavam' => '11785737494',
            'ano' => '1985',
            'placa' => 'MZS2299'
        ))->status);
    }

    public function testAtivar()
    {
        $this->assertObjectHasAttribute('status', Veiculo::ativar(23));

        $this->assertObjectHasAttribute('veiculo', Veiculo::ativar(23));

        $this->assertTrue(Veiculo::ativar(23)->status);

        $this->assertNotNull(Veiculo::ativar(23)->veiculo);
    }
}
