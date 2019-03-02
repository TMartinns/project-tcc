<?php

class VeiculoUtilizado extends \HXPHP\System\Model
{
    static $table_name = 'veiculos_utilizados';

    public static function cadastrar(array $atributos)
    {
        $veiculoUtilizado = self::create($atributos);

        return $veiculoUtilizado->is_valid();
    }

    public static function encerrar($atributos, $ocorrencia)
    {
        $atributos->data_termino = date('Y-m-d H:i:s');
        $atributos->ocorrencia = (empty($ocorrencia)) ? null : $ocorrencia;

        return $atributos->save(false);
    }
}