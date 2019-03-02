<?php

namespace HXPHP\System\Services\DateConverter;

class DateConverter
{
    public function toMySqlFormat(string $date)
    {
        $response = \DateTime::createFromFormat('d/m/Y', $date);
        if($response){

            return $response->format('Y-m-d');

        }

        $response = \DateTime::createFromFormat('d/m/Y H:i', $date);
        if($response){

            return $response->format('Y-m-d H:i');

        }

        $response = \DateTime::createFromFormat('d/m/Y H:i:s', $date);
        if($response){

            return $response->format('Y-m-d H:i:s');

        }

        return null;
    }
}