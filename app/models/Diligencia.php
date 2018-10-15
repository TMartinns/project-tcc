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

    public static function cadastrar(array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->diligencia = null;
        $resposta->status = false;
        $resposta->errors = array();

        $diligencia = self::create($atributos);

        if ($diligencia->is_valid()) {
            $resposta->diligencia = $diligencia;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $diligencia->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function editarStatus($idDiligencia, $status)
    {
        $resposta = new \stdClass;
        $resposta->diligencia = null;
        $resposta->status = false;

        $diligencia = self::find_by_id($idDiligencia);
        $diligencia->status = $status;

        if($diligencia->save(false)) {
            $resposta->diligencia = $diligencia;
            $resposta->status = true;

            return $resposta;
        }

        return $resposta;
    }

    public static function getAllByRemessa($idRemessa)
    {
        return self::find_by_sql(
            "select diligencias.*
            from diligencias
            inner join aux_diligencias_remessas
            on diligencias.id = aux_diligencias_remessas.id_diligencia
            where aux_diligencias_remessas.id_remessa = $idRemessa"
        );
    }
}