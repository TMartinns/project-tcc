<?php

header("HTTP/1.0 404 Not Found");

class Error404Controller extends \HXPHP\System\Controller
{
    public function indexAction()
    {
        $this->view->setHeader('error404/header');
        $this->view->setFooter('error404/footer');
    }
}