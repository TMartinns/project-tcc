<?php

class BlankController extends \HXPHP\System\Controller
{
    public function indexAction()
    {
        $this->view->setTemplate(false);
    }
}