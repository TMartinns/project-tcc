<?php

class AuxDiligenciasRemessas extends \HXPHP\System\Model
{
    static $table_name = 'aux_diligencias_remessas';

    public static function cadastrar(array $atributos)
    {
        self::create($atributos);
    }
}