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
        $resposta = new \stdClass;
        $resposta->pessoa = null;
        $resposta->status = false;
        $resposta->errors = array();

        $pessoa = self::create($post);

        if ($pessoa->is_valid()) {
            $resposta->pessoa = $pessoa;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $pessoa->errors->get_raw_errors();

        foreach ($errors as $key => $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function getPrimeiroNome($id_pessoa) {
        return explode(" ", self::find_by_id($id_pessoa)->nome)[0];
    }
}