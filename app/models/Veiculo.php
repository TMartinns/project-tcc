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

    public static function cadastrar(array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->veiculo = null;
        $resposta->status = false;
        $resposta->errors = array();

        $veiculo = self::create($atributos);

        if($veiculo->is_valid()) {
            $resposta->veiculo = $veiculo;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $veiculo->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function editar($idVeiculo, $atributos)
    {
        $resposta = new \stdClass;
        $resposta->veiculo = null;
        $resposta->status = false;
        $resposta->errors = array();

        if(in_array('', $atributos)) {
            array_push($resposta->errors, 'Todos os campos são obrigatórios.');

            return $resposta;
        }

        $veiculo = self::find_by_id($idVeiculo);
        $veiculo->marca = $atributos['marca'];
        $veiculo->modelo = $atributos['modelo'];
        $veiculo->cor = $atributos['cor'];
        $veiculo->renavam = $atributos['renavam'];
        $veiculo->ano = $atributos['ano'];
        $veiculo->placa = $atributos['placa'];

        $existeRenavam = self::find_by_renavam($atributos['renavam']);

        if(!is_null($existeRenavam) && $idVeiculo != $existeRenavam->id) {
            array_push($resposta->errors, 'Já existe um veículo com esse renavam cadastrado.');
        }

        $existePlaca = self::find_by_placa($atributos['placa']);

        if(!is_null($existePlaca) && $idVeiculo != $existePlaca->id) {
            array_push($resposta->errors, 'Já existe um veículo com essa placa cadastrada.');
        }

        if(!empty($resposta->errors)) {
            return $resposta;
        }

        if($veiculo->save(false)){
            $resposta->veiculo = $veiculo;
            $resposta->status = true;

            return $resposta;
        }

        $errors = $veiculo->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function ativar($idVeiculo, $ativar = true)
    {
        $resposta = new \stdClass;
        $resposta->veiculo = null;
        $resposta->status = false;

        $veiculo = self::find_by_id($idVeiculo);
        $veiculo->is_ativo = ($ativar) ? 1 : 0;

        if($veiculo->save(false)) {
            $resposta->veiculo = $veiculo;
            $resposta->status = true;
            return $resposta;
        }

        return $resposta;
    }
}