<?php

class Evento extends \HXPHP\System\Model
{
    static $table_name = 'eventos';

    public static function cadastrar(array $post)
    {
        self::create($post);
    }
}