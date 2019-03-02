<?php

class Evento extends \HXPHP\System\Model
{
    static $table_name = 'eventos';

    public static function cadastrar(array $atributos)
    {
        $eventos = self::create($atributos);

        return $eventos->is_valid();
    }
}