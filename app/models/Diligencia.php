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

        foreach ($errors as $key => $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function editarStatus($id, $status)
    {
        $resposta = new \stdClass;
        $resposta->diligencia = null;
        $resposta->status = false;

        $diligencia = self::find_by_id($id);
        $diligencia->status = $status;

        if($diligencia->save(false)) {
            $resposta->diligencia = $diligencia;
            $resposta->status = true;

            return $resposta;
        }

        return $resposta;
    }

    public static function getAllByRemessa($id)
    {
        return self::find_by_sql(
            "select diligencias.*
            from diligencias
            inner join aux_diligencias_remessas
            on diligencias.id = aux_diligencias_remessas.id_diligencia
            where aux_diligencias_remessas.id_remessa = $id"
        );
    }

    public static function getByTipoDiligenciaAndEventosAutor($tipoDiligencia, $autor)
    {
        return self::find_by_sql(
            "select diligencias.*
            from diligencias
            inner join eventos
            on diligencias.id = eventos.id_diligencia
            where diligencias.id_tipo_diligencia = $tipoDiligencia and eventos.id_autor = $autor
            limit 1"
        );
    }
}