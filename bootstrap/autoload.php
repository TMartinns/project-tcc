<?php
/**
 * Verifica se o autoload do Composer está configurado
 */
$composer_autoload = dirname(__DIR__) . DS . 'vendor' . DS . 'autoload.php';

if (!file_exists($composer_autoload)) {
    die('Execute o comando: composer install');
}

require_once($composer_autoload);

