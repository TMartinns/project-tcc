<?php

class VeiculoUtilizado extends \HXPHP\System\Model
{
    static $table_name = 'veiculos_utilizados';

    public static function cadastrar(array $atributos)
    {
        return self::create($atributos)->is_valid();
    }
}