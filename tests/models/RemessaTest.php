<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class RemessaTest extends TestCase
{

    public function testGetByDiligenciaAndStatus()
    {
        $this->assertNotEmpty(Remessa::getByDiligenciaAndStatus(49, 'R')[0]);
    }

    public function testCadastrar()
    {
        $array = array(
            'data' => date('Y-m-d H:i:s'),
            'status' => 'A',
            'id_remetente' => 83,
            'id_destinatario' => 84
        );

        $this->assertObjectHasAttribute('status', Remessa::cadastrar($array));

        $this->assertObjectHasAttribute('remessa', Remessa::cadastrar($array));

        $this->assertObjectHasAttribute('errors', Remessa::cadastrar($array));

        $this->assertTrue(Remessa::cadastrar($array)->status);

        $this->assertNotNull(Remessa::cadastrar($array)->remessa);

        $this->assertEmpty(Remessa::cadastrar($array)->errors);
    }

    public function testReceber()
    {
        $this->assertTrue(Remessa::receber(123));
    }
}
