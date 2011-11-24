<?php

class IndexController extends Zend_Controller_Action
{

    /** @var EntityManager **/
    private static $em = null;

    public function init()
    {
        self::$em = Zend_Registry::get('em');
    }

    public function indexAction()
    {
        $orders = self::$em->createQuery("SELECT o FROM order o")->getResult();
        $this->view->assign("orders", $orders);

        $this->view->assign("error", $this->_getParam("error"));
        $this->view->assign("success", $this->_getParam("success"));
    }

}