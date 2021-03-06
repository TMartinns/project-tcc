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

    public static function cadastrar(array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->telefone = null;
        $resposta->status = false;
        $resposta->errors = array();

        $telefone = self::create($atributos);

        if ($telefone->is_valid()) {
            $resposta->telefone = $telefone;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $telefone->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function editar($idPessoa, array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->telefone = null;
        $resposta->status = false;
        $resposta->errors = array();

        if(in_array('', $atributos)) {
            array_push($resposta->errors, 'Todos os campos são obrigatórios.');

            return $resposta;
        }

        $telefone = self::find_by_id_pessoa($idPessoa);
        $telefone->ddd = $atributos['ddd'];
        $telefone->numero = $atributos['numero'];

        if(!empty($resposta->errors)) {
            return $resposta;
        }

        if($telefone->save(false)){
            $resposta->telefone = $telefone;
            $resposta->status = true;

            return $resposta;
        }

        $errors = $telefone->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }
}