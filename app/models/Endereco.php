<?php

class Endereco extends \HXPHP\System\Model
{
    static $table_name = 'enderecos';

    static $validates_presence_of = array(
        array(
            'logradouro',
            'message' => 'O logradouro é um campo obrigatório.'
        ),
        array(
            'numero',
            'message' => 'O número da casa é um campo obrigatório.'
        ),
        array(
            'complemento',
            'message' => 'O complemento é um campo obrigatório.'
        ),
        array(
            'cep',
            'message' => 'O CEP é um campo obrigatório.'
        ),
        array(
            'bairro',
            'message' => 'O bairro é um campo obrigatório.'
        ),
        array(
            'id_cidade',
            'message' => 'A cidade é um campo obrigatório.'
        )
    );

    public static function cadastrar(array $post)
    {
        $resposta = new \stdClass;
        $resposta->endereco = null;
        $resposta->status = false;
        $resposta->errors = array();

        $endereco = self::create($post);

        if ($endereco->is_valid()) {
            $resposta->endereco = $endereco;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $endereco->errors->get_raw_errors();

        foreach ($errors as $key => $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }
}