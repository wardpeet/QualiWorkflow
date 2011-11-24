<?php

class ProductController extends Zend_Controller_Action {

    /**
     * @var EntityManager
     */
    private static $em = null;

    public function init() {
        self::$em = Zend_Registry::get('em');
        Zend_Layout::getMvcInstance()->getView()->navigation()->getContainer()->findOneByLabel('Orders')->active = true;
    }

    public function viewAction() {
        $id = $this->_getParam('id');
        $product = self::$em->find('Product', $id);

        $this->view->assign('product', $product);
    }

    public function newAction() {
        $id = $this->_getParam('id');
        $order = self::$em->find('Order', $id);

        $form = new Application_Form_Product();
        $this->view->assign('form', $form);
        $this->view->assign('order', $order);
        $this->view->assign('error', $this->_getParam('error'));
    }

    public function editAction() {
        $id = $this->_getParam("id");

        $product = self::$em->find("Product", $id);
        if (!$product) {
            $referer = Bootstrap::getReferer();
            $this->_forward($referer['action'], $referer['module'], null, $referer['params']);
        }

        $form = new Application_Form_Product(null, $product, true);
        $this->view->assign("form", $form);
        $this->view->assign("product", $product);
        $this->view->assign("error", $this->_getParam("error"));
    }

    public function paddAction() {
        $data = $this->_request->getPost();

        $form = new Application_Form_Product();
        if ($form->isValid($data)) {
            $data['order'] = self::$em->find('Order', $data['ordernr']);

            $product = Product::emptyObject();
            $this->addUpdAction($data, $product);
            self::$em->refresh($product);

            $success = "Product #" . $product->getProductnr() . " is met success toegevoegd.";
            $this->_forward("view", "orders", null, array('id' => $product->getOrder()->getOrdernr(), 'success' => $success));
        } else {
            $errors = $form->getErrors();
            $keys = array_keys($errors);
            $error = null;

            for ($i = 0; $i < sizeof($keys) && $error == null; $i++) {
                if ($errors[$keys[$i]]) {
                    $error = array_shift($errors[$keys[$i]]);
                }
            }

            $this->_forward("new", "product", null, array('error' => $error));
        }
    }

    public function peditAction() {
        $data = $this->_request->getPost();

        $form = new Application_Form_Product();
        if ($form->isValid($data)) {
            $data['order'] = self::$em->find('Order', $data['ordernr']);

            $product = self::$em->find('Product', $data['productnr']);
            $this->addUpdAction($data, $product);

            $success = "Product #" . $product->getProductnr() . " is met success aangepast.";
            $this->_forward("view", "product", null, array('id' => $product->getProductnr(), 'success' => $success));
        } else {
            $errors = $form->getErrors();
            $keys = array_keys($errors);
            $error = null;

            for ($i = 0; $i < sizeof($keys) && $error == null; $i++) {
                if ($errors[$keys[$i]]) {
                    $error = array_shift($errors[$keys[$i]]);
                }
            }

            $this->_forward("edit", "product", null, array('id' => $data['oldProduct'], 'error' => $error));
        }
    }

    public function deleteAction() {
        $id = $this->_getParam("id");

        try {
            $product = self::$em->find("Product", $id);

            $ordernr = $product->getOrder()->getOrdernr();
            self::$em->remove($product);
            self::$em->flush();

            $success = "Het product (#" . $id . ") van order (#" . $ordernr . ")is met success verwijdert.";
        } catch (Exception $e) {
            $error = "Het product die je wil verwijderen bestaat niet.";
        }

        $this->_forward("view", "orders", null, array("error" => $error, "success" => $success));
    }

    private function addUpdAction($data, $product) {
        $product->setData($data);
        self::$em->persist($product);
        self::$em->flush();
    }

}