<?php

class Remessa extends \HXPHP\System\Model
{
    static $table_name = 'remessas';

    static $validates_presence_of = array(
        array(
            'id_destinatario',
            'message' => 'O destinatário é um campo obrigatório.'
        )
    );

    public static function cadastrar(array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->remessa = null;
        $resposta->status = false;
        $resposta->errors = array();

        $remessa = self::create($atributos);

        if($remessa->is_valid()) {
            $resposta->remessa = $remessa;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $remessa->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function receber($idRemessa)
    {
        $remessa = self::find_by_id($idRemessa);

        $remessa->status = 'R';

        return $remessa->save(false);
    }

    public static function getByDiligenciaAndStatus($idDiligencia, $status)
    {
        return self::find_by_sql(
            "select remessas.*
            from remessas
            inner join aux_diligencias_remessas
            on remessas.id = aux_diligencias_remessas.id_remessa
            where aux_diligencias_remessas.id_diligencia = $idDiligencia and remessas.status = '$status'
            order by remessas.data desc 
            limit 1"
        );
    }
}