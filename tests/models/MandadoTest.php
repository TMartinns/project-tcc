<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class MandadoTest extends TestCase
{

    public function testCadastrar()
    {
        $this->assertTrue(Mandado::cadastrar(array(
            'descricao' => 'Mandado de averiguação 09/18',
            'numero_protocolo' => '201800001142',
            'id_interessado' => 86,
            'id_promotoria' => 1
        ))->status);
    }
}
