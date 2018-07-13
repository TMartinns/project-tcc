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

$configs->env->development->auth->setURLs('/project-tcc/inicio/', '/project-tcc/');

$configs->env->development->mail->setFrom(array(
    'from' => 'Suporte ADUV',
    'from_mail' => 'suporte.aduv@gmail.com'
));

$configs->env->development->menu->setConfigs(array(
    'container_id' => 'sidebar',
    'container_class' => 'navbar navbar-light bg-white border',
    'menu_id' => 'sidebarItens',
    'menu_item_class' => 'nav-item',
    'link_before' => '<span class="sidebarItem">',
    'link_class' => 'nav-link',
    'link_active_class' => 'active border-top border-bottom border-primary'
));

$configs->env->development->menu->setMenus(array(
    'Início/fas fa-home' => '%baseURI%/inicio/',
    'Diligências/fas fa-tasks' => '%baseURI%/diligencias/',
    'Veículos/fas fa-car' => '%baseURI%/veiculos/'
), 'Oficial');

$configs->env->development->menu->setMenus(array(
    'Início/fas fa-home' => '%baseURI%/inicio/',
    'Usuários/fas fa-users' => '%baseURI%/usuarios/',
    'Diligências/fas fa-tasks' => '%baseURI%/diligencias/',
    'Veículos/fas fa-car' => '%baseURI%/veiculos/',
    'Relatórios/fas fa-file-alt' => '%baseURI%/relatorios/'
), 'Coordenador');

return $configs;