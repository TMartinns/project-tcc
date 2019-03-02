<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class VeiculoUtilizadoTest extends TestCase
{

    public function testEncerrar()
    {
        $veiculoUtilizado = VeiculoUtilizado::find_by_id(47);

        $this->assertTrue(VeiculoUtilizado::encerrar($veiculoUtilizado, ''));
    }

    public function testCadastrar()
    {
        $this->assertTrue(VeiculoUtilizado::cadastrar(array(
            'data_inicio' => date('Y-m-d'),
            'id_veiculo' => 23,
            'id_oficial' => 84
        )));
    }
}
