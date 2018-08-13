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

    public static function cadastrar(array $post)
    {
        $resposta = new \stdClass;
        $resposta->remessa = null;
        $resposta->status = false;
        $resposta->errors = array();

        $remessa = self::create($post);

        if($remessa->is_valid()) {
            $resposta->remessa = $remessa;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $remessa->errors->get_raw_errors();

        foreach ($errors as $key => $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function receber($id)
    {
        $remessa = self::find_by_id($id);

        $remessa->status = 'R';

        $remessa->save(false);
    }
}