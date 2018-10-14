<?php

class VeiculoUtilizado extends \HXPHP\System\Model
{
    static $table_name = 'veiculos_utilizados';

    public static function cadastrar(array $atributos)
    {
        self::create($atributos);
    }

    public static function encerrar($atributos, $ocorrencia)
    {
        $atributos->data_termino = date('Y-m-d H:i:s');
        $atributos->ocorrencia = (empty($ocorrencia)) ? null : $ocorrencia;

        $atributos->save(false);
    }
}