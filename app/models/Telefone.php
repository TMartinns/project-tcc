<?php

class Telefone extends \HXPHP\System\Model
{
    static $table_name = 'telefones';

    static $validates_presence_of = array(
        array(
            'ddd',
            'message' => 'O DDD é um campo obrigatório.'
        ),
        array(
            'numero',
            'message' => 'O número de telefone é um campo obrigatório.'
        )
    );

    public static function cadastrar(array $post)
    {
        $resposta = new \stdClass;
        $resposta->telefone = null;
        $resposta->status = false;
        $resposta->errors = array();

        $telefone = self::create($post);

        if ($telefone->is_valid()) {
            $resposta->telefone = $telefone;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $telefone->errors->get_raw_errors();

        foreach ($errors as $key => $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }
}