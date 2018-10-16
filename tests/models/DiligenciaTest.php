<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class DiligenciaTest extends TestCase
{

    public function testEditarStatus()
    {
        $this->assertObjectHasAttribute('diligencia', Diligencia::editarStatus(51, 'E'));

        $this->assertObjectHasAttribute('status', Diligencia::editarStatus(51, 'E'));

        $this->assertTrue(Diligencia::editarStatus(51, 'E')->status);

        $this->assertNotNull(Diligencia::editarStatus(51, 'E')->diligencia);
    }

    public function testCadastrar()
    {
        $array = array(
            'prazo_cumprimento' => date('Y-m-d'),
            'status' => 'A',
            'id_mandado' => 42,
            'id_tipo_diligencia' => 1
        );

        $this->assertObjectHasAttribute('status', Diligencia::cadastrar($array));

        $this->assertObjectHasAttribute('diligencia', Diligencia::cadastrar($array));

        $this->assertObjectHasAttribute('errors', Diligencia::cadastrar($array));

        $this->assertTrue(Diligencia::cadastrar($array)->status);

        $this->assertNotNull(Diligencia::cadastrar($array)->diligencia);

        $this->assertEmpty(Diligencia::cadastrar($array)->errors);
    }

    public function testGetAllByRemessa()
    {
        $this->assertNotEmpty(Diligencia::getAllByRemessa(123));
    }
}
