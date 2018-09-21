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

    public static function editar($id, array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->telefone = null;
        $resposta->status = false;
        $resposta->errors = array();

        if(in_array('', $atributos)) {
            array_push($resposta->errors, 'Todos os campos são obrigatórios.');

            return $resposta;
        }

        $telefone = self::find_by_id_pessoa($id);
        $telefone->ddd = $atributos['ddd'];
        $telefone->numero = $atributos['numero'];

        if(!empty($resposta->errors)) {
            return $resposta;
        }

        $save = $telefone->save(false);

        if($save){
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