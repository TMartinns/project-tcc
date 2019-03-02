<?php

class Notificacao extends \HXPHP\System\Model
{
    static $table_name = 'notificacoes';

    public static function cadastrar(array $atributos)
    {
        $notificacao = self::create($atributos);

        return $notificacao->is_valid();
    }

    public static function visto($idNotificacao)
    {
        $notificacao = self::find_by_id($idNotificacao);
        $notificacao->visto = 1;

        return $notificacao->save(false);
    }
}