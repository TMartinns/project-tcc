<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class EventoTest extends TestCase
{

    public function testCadastrar()
    {
        $this->assertTrue(Evento::cadastrar(array(
            'data' => date('Y-m-d'),
            'id_diligencia' => 67,
            'id_autor' => 83,
            'id_tipo_evento' => 4
        )));
    }
}
