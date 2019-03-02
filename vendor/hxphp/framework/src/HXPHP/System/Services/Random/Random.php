<?php

namespace HXPHP\System\Services\Random;

class Random
{
    public function password($size = 8, $uppercase = true, $numbers = true, $symbols = false)
    {
        $password = '';

        $characters = array(
            'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
            'numbers' => '1234567890',
            'symbols' => '!@#$%*-'
        );

        $all_characters = '';

        $all_characters .= $characters['lowercase'];
        $uppercase ? $all_characters .= $characters['uppercase'] : '';
        $numbers ? $all_characters .= $characters['numbers'] : '';
        $symbols ? $all_characters .= $characters['symbols'] : '';

        for ($index = 1; $index <= $size; $index++) {
            $rand = mt_rand(1, strlen($all_characters));
            $password .= $all_characters[$rand - 1];
        }

        return $password;
    }
}
