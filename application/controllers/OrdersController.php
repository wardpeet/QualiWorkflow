<?php

class OrdersController extends Zend_Controller_Action
{

     /** @var EntityManager **/
    private static $em;

    public function init()
    {
        self::$em = Zend_Registry::get('em');
        Zend_Layout::getMvcInstance()->getView()->navigation()->getContainer()->findOneByLabel('Orders')->active = true;
    }

    public function klantAction() {
        $id = $this->_getParam('id');

        $klant = self::$em->find('Klant', $id);
        if(!$klant) {
            $referer = Bootstrap::getReferer();
            $this->_forward($referer['action'], $referer['module'], null, $referer['params']);
        }

        $this->view->assign('klant', $klant);
    }

    public function viewAction() {
        $id = $this->_getParam("id");

        $order = self::$em->find("Order", $id);
        if(!$order) {
            $this->_forward("index", "index", null, array("error" => 'De order die je zoekt kan niet worden gevonden.'));
        }

        $this->view->assign("order", $order);
        $this->view->assign("success", $this->_getParam("success"));
    }

    public function newAction() {
        $order = Order::emptyObject();
        
        $ordernr = self::$em->createQuery("SELECT o.ordernr
            FROM Order o
            ORDER by o.ordernr DESC")->getResult();
        $ordernr = $ordernr[0]['klantnr'] + 1;

        $order->setOrdernr($ordernr);

        $id = $this->_getParam('id');
        if($id) {
            $klant = self::$em->find('Klant', $id);
            $order->setKlant($klant);
        }

        $form = new Application_Form_Order(null, $order);
        $this->view->assign('form', $form);
        $this->view->assign('error', $this->_getParam('error'));
    }
    
    public function editAction() {        
        $id = $this->_getParam("id");

        $order = self::$em->find("Order", $id);
        if(!$order) {
            $referer = Bootstrap::getReferer();
            $this->_forward($referer['action'], $referer['module'], null, $referer['params']);
        }

        $form = new Application_Form_Order(null, $order, true);
        $this->view->assign("form", $form);
        $this->view->assign("order", $order);
        $this->view->assign("error", $this->_getParam("error"));
    }

    public function paddAction() {
        $data = $this->_request->getPost();

        $form = new Application_Form_Order();
        if($form->isValid($data)) {
            $data['klant'] = self::$em->find('Klant', $data['klant']);
            
            $order = Order::emptyObject();
            $this->addUpdAction($data, $order);
            self::$em->refresh($order);

            $success = "Order #" . $order->getOrdernr() . " is met success toegevoegd.";
            $this->_forward("view", "orders", null, array('id' => $order->getOrdernr(), 'success' => $success));
        }else {
            $errors = $form->getErrors();
            $keys = array_keys($errors);
            $error = null;

            for($i=0; $i<sizeof($keys) && $error==null; $i++) {
                if($errors[$keys[$i]]) {
                    $error = array_shift($errors[$keys[$i]]);
                }
            }

            $this->_forward("new", "orders", null, array('error' => $error));
        }
    }

    public function peditAction() {
        $data = $this->_request->getPost();

        $form = new Application_Form_Order();
        if($form->isValid($data)) {
            $data['klant'] = self::$em->find('Klant', $data['klant']);

            $order = self::$em->find('Order', $data['ordernr']);
            $this->addUpdAction($data, $order);

            $success = "Order #" . $order->getOrdernr() . " is met success aangepast.";
            $this->_forward("view", "orders", null, array('id' => $order->getOrdernr(), 'success' => $success));
        }else {
            $errors = $form->getErrors();
            $keys = array_keys($errors);
            $error = null;

            for($i=0; $i<sizeof($keys) && $error==null; $i++) {
                if($errors[$keys[$i]]) {
                    $error = array_shift($errors[$keys[$i]]);
                }
            }

            $this->_forward("edit", "orders", null, array('id' => $data['oldOrder'], 'error' => $error));
        }
    }
    
    public function deleteAction() {
        $id = $this->_getParam("id");

        try {
            $order = self::$em->find("Order", $id);

            self::$em->remove($order);
            self::$em->flush();

            $success = "De order(#" . $id . ") is met success verwijdert.";
        }catch(Exception $e) {
            $error = "De order die je wil verwijderen bestaat niet.";
        }

        $referer = Bootstrap::getReferer();
        $referer['params']['error'] = $error;
        $referer['params']['success'] = $success;
        $this->_forward($referer['action'], $referer['module'], null, $referer['params']);
    }

    private function addUpdAction($data, $order) {
        $order->setData($data);
        self::$em->persist($order);
        self::$em->flush();
    }
}