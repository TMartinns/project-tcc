<?php

class Pessoa extends \HXPHP\System\Model
{
    static $table_name = 'pessoas';

    static $validates_presence_of = array(
        array(
            'nome',
            'message' => 'O nome é um campo obrigatório.'
        ),
        array(
            'cpf',
            'message' => 'O CPF é um campo obrigatório.'
        )
    );

    static $validates_uniqueness_of = array(
        array(
            'cpf',
            'message' => 'Já existe um usuário com esse CPF cadastrado.'
        )
    );

    public static function cadastrar(array $post)
    {
        $callback = new \stdClass;
        $callback->pessoa = null;
        $callback->status = false;
        $callback->errors = array();

        $pessoa = self::create($post);

        if ($pessoa->is_valid()) {
            $callback->pessoa = $pessoa;
            $callback->status = true;
            return $callback;
        }

        $errors = $pessoa->errors->get_raw_errors();

        foreach ($errors as $field => $message) {
            array_push($callback->errors, $message[0]);
        }

        return $callback;
    }
}