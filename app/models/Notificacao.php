<?php

class Notificacao extends \HXPHP\System\Model
{
    static $table_name = 'notificacoes';

    public static function cadastrar(array $atributos)
    {
        self::create($atributos);
    }

    public static function visto($idNotificacao)
    {
        $notificacao = self::find_by_id($idNotificacao);
        $notificacao->visto = 1;

        $notificacao->save(false);
    }
}