<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class DiligenciaTest extends TestCase
{

    public function testEditarStatus()
    {
        $this->assertTrue(Diligencia::editarStatus(34, 'E')->status);
    }

    public function testCadastrar()
    {
        $this->assertTrue(Diligencia::cadastrar(array(
            'prazo_cumprimento' => date('Y-m-d'),
            'status' => 'A',
            'id_mandado' => 37,
            'id_tipo_diligencia' => 1
        ))->status);
    }

    public function testGetAllByRemessa()
    {
        $this->assertNotEmpty(Diligencia::getAllByRemessa(113));
    }
}
