<?php

class Diligencia extends \HXPHP\System\Model
{
    static $table_name = 'diligencias';

    static $validates_presence_of = array(
        array(
            'prazo_cumprimento',
            'message' => 'O prazo para cumprimento é um campo obrigatório.'
        ),
        array(
            'id_tipo_diligencia',
            'message' => 'O tipo de diligência é um campo obrigatório.'
        )
    );

    public static function cadastrar(array $post)
    {
        $resposta = new \stdClass;
        $resposta->diligencia = null;
        $resposta->status = false;
        $resposta->errors = array();

        $diligencia = self::create($post);

        if($diligencia->is_valid()) {
            $resposta->diligencia = $diligencia;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $diligencia->errors->get_raw_errors();

        foreach ($errors as $key => $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }
}