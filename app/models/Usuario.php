<?php

class Usuario extends \HXPHP\System\Model
{
    static $table_name = 'usuarios';

    static $validates_presence_of = array(
        array(
            'nome_usuario',
            'message' => 'O nome de usuário é um campo obrigatório.'
        ),
        array(
            'email',
            'message' => 'O e-mail é um campo obrigatório.'
        ),
        array(
            'permissao',
            'message' => 'A permissão é um campo obrigatório.'
        )
    );

    static $validates_uniqueness_of = array(
        array(
            'nome_usuario',
            'message' => 'Já existe um usuário com esse nome de usuário cadastrado.'
        ),
        array(
            'email',
            'message' => 'Já existe um usuário com esse e-mail cadastrado.'
        )
    );

    public function cadastrar(array $post)
    {
        $callback = new \stdClass;
        $callback->usuario = null;
        $callback->status = false;
        $callback->errors = array();

        $usuario = self::create($post);

        if($usuario->is_valid()) {
            $callback->usuario = $usuario;
            $callback->status = true;
            return $callback;
        }

        $errors = $usuario->errors->get_raw_errors();

        foreach ($errors as $field => $message) {
            array_push($callback->errors, $message[0]);
        }

        return $callback;
    }
}