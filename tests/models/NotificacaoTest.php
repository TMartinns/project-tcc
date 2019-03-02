<?php

use PHPUnit\Framework\TestCase;

class NotificacaoTest extends TestCase
{

    public function testVisto()
    {
        $this->assertTrue(Notificacao::visto(15));
    }

    public function testCadastrar()
    {
        $this->assertTrue(Notificacao::cadastrar(array(
            'id_diligencia' => 67,
            'id_destinatario' => 88,
            'mensagem' => 'Uma diligência urgente foi emitida, clique para visualizá-la.',
            'data' => date('Y-m-d'),
            'id_tipo_notificacao' => 1
        )));
    }
}
