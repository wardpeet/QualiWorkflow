<?php

class KlantenController extends Zend_Controller_Action
{

    /** @var EntityManager */
    private static $em;
    public function init()
    {
        self::$em = Zend_Registry::get('em');
        Zend_Layout::getMvcInstance()->getView()->navigation()->getContainer()->findOneByLabel('Klanten')->active = true;
    }

    public function indexAction()
    {
        $klanten = self::$em->createQuery("SELECT k FROM klant k")->getResult();
        $this->view->assign("klanten", $klanten);

        $this->view->assign("success", $this->_getParam("success"));
        $this->view->assign("error", $this->_getParam("error"));
    }

    public function viewAction() {
        $id = $this->_getParam("id");
        
        $klant = self::$em->find("Klant", $id);
        if(!$klant) {
            $referer = Bootstrap::getReferer();
            $this->forward($referer['action'], $referer['module'], null, $referer['params']);
        }

        $this->view->assign("klant", $klant);
        $this->view->assign("success", $this->_getParam("success"));
    }

    public function newAction() {
        $klant = Klant::emptyObject();
        
        $klantnr = self::$em->createQuery("SELECT k.klantnr
            FROM Klant k
            ORDER by k.klantnr DESC")->getResult();
        $klantnr = $klantnr[0]['klantnr'] + 1;

        $klant->setKlantnr($klantnr);

        $form = new Application_Form_Klant(null, $klant);
        $this->view->assign('form', $form);
        $this->view->assign('error', $this->_getParam('error'));
    }

    public function editAction() {        
        $id = $this->_getParam("id");

        $klant = self::$em->find("Klant", $id);
        if(!$klant) {
            $referer = Bootstrap::getReferer();
            $this->forward($referer['action'], $referer['module'], null, $referer['params']);
        }

        $form = new Application_Form_Klant(null, $klant, true);
        $this->view->assign("form", $form);
        $this->view->assign("klant", $klant);
        $this->view->assign("error", $this->_getParam("error"));
    }
    
    public function paddAction() {
        $data = $this->_request->getPost();

        $form = new Application_Form_Klant();
        if($form->isValid($data)) {
            $klant = Klant::emptyObject();
            $this->addUpdAction($data, $klant);
            self::$em->refresh($klant);

            $success = "Klant #" . $klant->getKlantnr() . " is met success toegevoegd.";
            $this->_forward("view", "klanten", null, array('id' => $klant->getKlantnr(), 'success' => $success));
        }else {
            $errors = $form->getErrors();
            $keys = array_keys($errors);
            $error = null;

            for($i=0; $i<sizeof($keys) && $error==null; $i++) {
                if($errors[$keys[$i]]) {
                    $error = array_shift($errors[$keys[$i]]);
                }
            }

            $this->_forward("new", "klanten", null, array('error' => $error));
        }
    }

    public function peditAction() {
        $data = $this->_request->getPost();

        $form = new Application_Form_Klant();
        if($form->isValid($data)) {
            $klant = self::$em->find('Klant', $data['klantnr']);
            $this->addUpdAction($data, $klant);

            $success = "Klant #" . $klant->getKlantnr() . " is met success aangepast.";
            $this->_forward("view", "klanten", null, array('id' => $klant->getKlantnr(), 'success' => $success));
        }else {
            $errors = $form->getErrors();
            $keys = array_keys($errors);
            $error = null;

            for($i=0; $i<sizeof($keys) && $error==null; $i++) {
                if($errors[$keys[$i]]) {
                    $error = array_shift($errors[$keys[$i]]);
                }
            }

            $this->_forward("edit", "klanten", null, array('id' => $data['oldKlant'], 'error' => $error));
        }
    }
    
    public function deleteAction() {
        $id = $this->_getParam("id");

        try {
            $klant = self::$em->find("Klant", $id);

            self::$em->remove($klant);
            self::$em->flush();

            $success = "De klant(#" . $id . ") is met success verwijdert.";
        }catch(Exception $e) {
            $error = "De klant die je wil verwijderen bestaat niet.";
        }

        $referer = Bootstrap::getReferer();
        $this->_forward($referer['action'], $referer['module'], null, $referer['params']);
    }

    private function addUpdAction($data, $klant) {
        $gemeente = new Gemeente($data['postcode'], $data['gemeente']);
        $data['gemeente'] = self::$em->merge($gemeente);
        self::$em->flush();

        $klant->setData($data);
        self::$em->persist($klant);
        self::$em->flush();
    }
}

