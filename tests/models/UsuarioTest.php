<?php

use PHPUnit\Framework\TestCase;

require_once 'config.php';

class UsuarioTest extends TestCase
{

    public function testGetAllByIsAtivoAndPessoasNome()
    {
        $this->assertNotEmpty(Usuario::getAllByIsAtivoAndPessoasNome(1, 'Isaac'));
    }

    public function testGetAllByEmailOrPessoasNome()
    {
        $this->assertNotEmpty(Usuario::getAllByEmailOrPessoasNome('mario.otavio@yopmail.com', 'Rita'));
    }

    public function testAtivar()
    {
        $this->assertObjectHasAttribute('status', Usuario::ativar(83));

        $this->assertObjectHasAttribute('usuario', Usuario::ativar(83));

        $this->assertTrue(Usuario::ativar(83)->status);

        $this->assertNotNull(Usuario::ativar(83)->usuario);
    }

    public function testEditar()
    {
        $array = array(
            'email' => 'mario.otavio@yopmail.com'
        );

        $this->assertObjectHasAttribute('status', Usuario::editar(83, $array));

        $this->assertObjectHasAttribute('usuario', Usuario::editar(83, $array));

        $this->assertObjectHasAttribute('errors', Usuario::editar(83, $array));

        $this->assertTrue(Usuario::editar(83, $array)->status);

        $this->assertNotNull(Usuario::editar(83, $array)->usuario);

        $this->assertEmpty(Usuario::editar(83, $array)->errors);
    }

    public function testCadastrar()
    {
        $this->assertTrue(Usuario::cadastrar(array(
            'email' => 'maria.rosa@yopmail.com',
            'senha' => password_hash('123', PASSWORD_DEFAULT),
            'funcao' => 'O',
            'id_pessoa' => 88
        ))->status);
    }

    public function testAlterarSenha()
    {
        $this->assertObjectHasAttribute('status', Usuario::alterarSenha(83, password_hash('123', PASSWORD_DEFAULT)));

        $this->assertObjectHasAttribute('usuario', Usuario::alterarSenha(83, password_hash('123', PASSWORD_DEFAULT)));

        $this->assertNotNull(Usuario::alterarSenha(83, password_hash('123', PASSWORD_DEFAULT))->usuario);

        $this->assertTrue(Usuario::alterarSenha(83, password_hash('123', PASSWORD_DEFAULT))->status);
    }
}
