<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class AuxDiligenciasRemessasTest extends TestCase
{

    public function testCadastrar()
    {
        $this->assertTrue(AuxDiligenciasRemessas::cadastrar(array(
            'id_remessa' => 149,
            'id_diligencia' => 67
        )));
    }
}
