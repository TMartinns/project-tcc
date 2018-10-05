<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class MandadoTest extends TestCase
{

    public function testCadastrar()
    {
        $this->assertTrue(Mandado::cadastrar(array(
            'descricao' => 'Ofício 800/2018',
            'numero_protocolo' => '012345678950',
            'id_interessado' => 81,
            'id_promotoria' => 1
        ))->status);
    }
}
