<?php

class Usuario extends \HXPHP\System\Model
{
    static $table_name = 'usuarios';

    static $validates_presence_of = array(
        array(
            'email',
            'message' => 'O e-mail é um campo obrigatório.'
        ),
        array(
            'funcao',
            'message' => 'A função é um campo obrigatório.'
        )
    );

    static $validates_uniqueness_of = array(
        array(
            'email',
            'message' => 'Já existe um usuário com esse e-mail cadastrado.'
        )
    );

    public static function cadastrar(array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->usuario = null;
        $resposta->status = false;
        $resposta->errors = array();

        $usuario = self::create($atributos);

        if($usuario->is_valid()) {
            $resposta->usuario = $usuario;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $usuario->errors->get_raw_errors();

        foreach ($errors as $key => $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function editar($id, array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->usuario = null;
        $resposta->status = false;
        $resposta->errors = array();

        if(in_array('', $atributos)) {
            array_push($resposta->errors, 'Todos os campos são obrigatórios.');

            return $resposta;
        }

        $usuario = self::find_by_id_pessoa($id);
        $usuario->email = $atributos['email'];

        $existeEmail = self::find_by_email($atributos['email']);

        if(!is_null($existeEmail) && $id != $existeEmail->id) {
            array_push($resposta->errors, 'Já existe um usuário com esse e-mail cadastrado.');
        }

        if(!empty($resposta->errors)) {
            return $resposta;
        }

        if($usuario->save(false)){
            $resposta->usuario = $usuario;
            $resposta->status = true;

            return $resposta;
        }

        $errors = $usuario->errors->get_raw_errors();

        foreach ($errors as $key => $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function alterarSenha($id, $senha)
    {
        $resposta = new \stdClass;
        $resposta->usuario = null;
        $resposta->status = false;

        $usuario = self::find_by_id_pessoa($id);
        $usuario->senha = $senha;

        if($usuario->save(false)) {
            $resposta->uuario = $usuario;
            $resposta->status = true;
            return $resposta;
        }

        return $resposta;
    }

    public static function ativar($id, $ativar = true)
    {
        $resposta = new \stdClass;
        $resposta->usuario = null;
        $resposta->status = false;

        $usuario = self::find_by_id_pessoa($id);
        $usuario->is_ativo = ($ativar) ? 1 : 0;

        if($usuario->save(false)) {
            $resposta->usuario = $usuario;
            $resposta->status = true;
            return $resposta;
        }

        return $resposta;
    }

    public static function getAllByIsAtivoAndPessoasNome($isAtivo, $nome)
    {
        return self::find_by_sql(
            "select * 
            from usuarios 
            inner join pessoas 
            on usuarios.id_pessoa = pessoas.id 
            where pessoas.nome like '%$nome%' and usuarios.is_ativo = $isAtivo"
        );
    }

    public static function getAllByEmailOrPessoasNome($email, $nome)
    {
        return self::find_by_sql(
            "select *
            from usuarios
            inner join pessoas
            on usuarios.id_pessoa = pessoas.id
            where pessoas.nome like '%$nome%' or usuarios.email like '%$email%'"
        );
    }
}