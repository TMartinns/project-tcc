<?php

$cfg = \ActiveRecord\Config::instance();
$cfg->set_model_directory('..\app\models');
$cfg->set_connections(
    [
        'development' => 'mysql://root:@localhost/db_aduv?charset=utf8'
    ]
);