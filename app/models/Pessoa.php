<?php

class Pessoa extends \HXPHP\System\Model
{
    static $table_name = 'pessoas';

    static $validates_presence_of = array(
        array(
            'nome',
            'message' => 'O nome é um campo obrigatório.'
        ),
        array(
            'cpf',
            'message' => 'O CPF é um campo obrigatório.'
        )
    );

    static $validates_uniqueness_of = array(
        array(
            'cpf',
            'message' => 'Já existe uma pessoa com esse CPF cadastrado.'
        )
    );

    public static function cadastrar(array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->pessoa = null;
        $resposta->status = false;
        $resposta->errors = array();

        $pessoa = self::create($atributos);

        if ($pessoa->is_valid()) {
            $resposta->pessoa = $pessoa;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $pessoa->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function editar($idPessoa, array $atributos, $genero = true)
    {
        $resposta = new \stdClass;
        $resposta->pessoa = null;
        $resposta->status = false;
        $resposta->errors = array();

        if (in_array('', $atributos)) {
            array_push($resposta->errors, 'Todos os campos são obrigatórios.');

            return $resposta;
        }

        $pessoa = self::find_by_id($idPessoa);
        $pessoa->nome = $atributos['nome'];
        $pessoa->cpf = $atributos['cpf'];
        $pessoa->data_nascimento = $atributos['data_nascimento'];

        if ($genero)
            $pessoa->genero = $atributos['genero'];

        $existeCpf = self::find_by_cpf($atributos['cpf']);

        if (!is_null($existeCpf) && $idPessoa != $existeCpf->id) {
            array_push($resposta->errors, 'Já existe uma pessoa com esse CPF cadastrado.');
        }

        if (!empty($resposta->errors)) {
            return $resposta;
        }

        if ($pessoa->save(false)) {
            $resposta->pessoa = $pessoa;
            $resposta->status = true;

            return $resposta;
        }

        $errors = $pessoa->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function getPrimeiroNome($idPessoa)
    {
        return explode(" ", self::find_by_id($idPessoa)->nome)[0];
    }

    public static function getAllByUsuariosFuncaoAndIsAtivo($funcao, $isAtivo)
    {
        return self::find_by_sql(
            "select pessoas.*
            from pessoas
            inner join usuarios
            on pessoas.id = usuarios.id_pessoa
            where usuarios.funcao = '$funcao' and usuarios.is_ativo = $isAtivo
            order by pessoas.nome"
        );
    }
}