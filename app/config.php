<?php
$configs = new HXPHP\System\Configs\Config;

$configs->title = 'ADUV';

$configs->env->add('development');

$configs->env->development->baseURI = '/project-tcc/';
$configs->env->development->bower = '/project-tcc/public/bower_components/';
$configs->env->development->js = '/project-tcc/public/js/';

$configs->env->development->database->setConnectionData([
    'driver' => env('DB_DRIVER', 'mysql'),
    'host' => env('MYSQL_HOST', 'localhost'),
    'user' => env('MYSQL_ROOT_USER', 'root'),
    'password' => env('MYSQL_ROOT_PASSWORD', ''),
    'dbname' => env('MYSQL_DATABASE', 'db_aduv'),
    'charset' => env('MYSQL_CHARSET', 'utf8')
]);

$configs->env->development->auth->setURLs('/project-tcc/home/', '/project-tcc/');

$configs->env->development->mail->setFrom(array(
    'from' => 'Suporte ADUV',
    'from_mail' => 'suporte.aduv@gmail.com'
));

return $configs;