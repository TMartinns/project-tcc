<?php

class Notificacao extends \HXPHP\System\Model
{
    static $table_name = 'notificacoes';

    public static function cadastrar(array $atributos)
    {
        self::create($atributos);
    }

    public static function visto($id)
    {
        $notificacao = self::find_by_id($id);
        $notificacao->visto = 1;

        $notificacao->save(false);
    }
}