<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class RemessaTest extends TestCase
{

    public function testGetByDiligenciaAndStatus()
    {
        $this->assertNotEmpty(Remessa::getByDiligenciaAndStatus(18, 'R'));
    }

    public function testCadastrar()
    {
        $this->assertTrue(Remessa::cadastrar(array(
            'data' => date('Y-m-d H:i:s'),
            'status' => 'A',
            'id_remetente' => 35,
            'id_destinatario' => 14
        ))->status);
    }
}
