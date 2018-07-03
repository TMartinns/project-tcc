<?php
$configs = new HXPHP\System\Configs\Config;
ActiveRecord\Connection::$datetime_format = 'Y-m-d H:i:s';

//Globais
$configs->global->models->directory = APP_PATH . 'models' . DS;

$configs->global->views->directory = APP_PATH . 'views' . DS;
$configs->global->views->extension = '.phtml';

$configs->global->controllers->directory = APP_PATH . 'controllers' . DS;
$configs->global->controllers->notFound = 'Error404Controller';

$configs->title = 'ADUV';

//Configurações de Ambiente - Desenvolvimento
$configs->env->add('development');

$configs->env->development->baseURI = '/project-tcc/';

$configs->env->development->database->setConnectionData([
    'driver' => env('DB_DRIVER', 'mysql'),
    'host' => env('MYSQL_HOST', 'localhost'),
    'user' => env('MYSQL_ROOT_USER', 'root'),
    'password' => env('MYSQL_ROOT_PASSWORD', ''),
    'dbname' => env('MYSQL_DATABASE', 'db_aduv'),
    'charset' => env('MYSQL_CHARSET', 'utf8')
]);

return $configs;