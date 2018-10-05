<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class UsuarioTest extends TestCase
{

    public function testGetAllByIsAtivoAndPessoasNome()
    {
        $this->assertNotEmpty(Usuario::getAllByIsAtivoAndPessoasNome(1, 'Maria'));
    }

    public function testGetAllByEmailOrPessoasNome()
    {
        $this->assertNotEmpty(Usuario::getAllByEmailOrPessoasNome('thales.martins35@gmail.com', 'João'));
    }

    public function testAtivar()
    {
        $this->assertTrue(Usuario::ativar(35)->status);
    }

    public function testEditar()
    {
        $this->assertTrue(Usuario::editar(35, array(
            'email' => 'joao_mario@yopmail.com'
        ))->status);
    }

    public function testCadastrar()
    {
        $this->assertTrue(Usuario::cadastrar(array(
            'email' => 'maria_galdino@yopmail.com',
            'senha' => password_hash('123', PASSWORD_DEFAULT),
            'funcao' => 'O',
            'id_pessoa' => 82
        ))->status);
    }
}
