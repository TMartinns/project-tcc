<?php

class Veiculo extends \HXPHP\System\Model
{
    static $table_name = 'veiculos';

    static $validates_presence_of = array(
        array(
            'marca',
            'message' => 'A marca é um campo obrigatório.'
        ),
        array(
            'modelo',
            'message' => 'O modelo é um campo obrigatório.'
        ),
        array(
            'cor',
            'message' => 'A cor é um campo obrigatório.'
        ),
        array(
            'renavam',
            'message' => 'O renavam é um campo obrigatório.'
        ),
        array(
            'ano',
            'message' => 'O ano é um campo obrigatório.'
        ),
        array(
            'placa',
            'message' => 'A placa é um campo obrigatório.'
        )
    );

    static $validates_uniqueness_of = array(
        array(
            'renavam',
            'message' => 'Já existe um veículo com esse renavam cadastrado.'
        ),
        array(
            'placa',
            'message' => 'Já existe um veículo com essa placa cadastrada.'
        )
    );

    public static function cadastrar(array $post)
    {
        $resposta = new \stdClass;
        $resposta->veiculo = null;
        $resposta->status = false;
        $resposta->errors = array();

        $veiculo = self::create($post);

        if($veiculo->is_valid()) {
            $resposta->veiculo = $veiculo;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $veiculo->errors->get_raw_errors();

        foreach ($errors as $key => $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }
}