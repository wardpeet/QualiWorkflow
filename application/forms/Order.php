<?php

class Application_Form_Order extends Zend_Form
{
    private $order;
    private $edit;
    /** @var EntityManager */
    private static $em;

    public function __construct($options = null, $order=null, $edit=false)
    {
        $this->order = $order;
        $this->edit = $edit;
        self::$em = Zend_Registry::get('em');

        parent::__construct($options);
    }

    public function init()
    {
        require_once 'decorators/My_Decorator_Quali.php';
        $this->addElementPrefixPath('My_Decorator', 'My/Decorator/', 'decorator');

        $this->setAction("/orders/" . ($this->edit ? 'pedit' : 'padd'));

        $this->setElementDecorators(array('Quali'));
        
        $klantenRes = self::$em->createQuery("SELECT k.klantnr, k.voornaam, k.achternaam FROM klant k")->getResult();

        // build options
        foreach($klantenRes as $k) {
            $klanten[$k['klantnr']] = $k['voornaam'] . ' ' . $k['achternaam'];
        }
        
        if($this->edit) {
            $this->addElement('hidden', 'oldOrder', array(
                'decorators' => array('ViewHelper'),
                'value' => $this->order->getOrdernr()
            ));
        }
        $this->addElement('hidden', 'ordernr', array(
            'decorators' => array('ViewHelper'),
            'value' => ($this->order ? $this->order->getOrdernr() : '')
        ));
        $this->addElement('select', 'klant', array(
            'label' => 'Klant',
        ));
        $this->getElement('klant')->addMultiOptions($klanten)
                ->setValue(($this->order ? $this->order->getKlant()->getKlantnr() : ''));

        $this->addElement('text', 'startdatum', array(
            'label' => 'Startdatum',
            'value' => ($this->order && $this->order->getDatum() ? $this->order->getDatum()->format('Y-m-d') : ''),
        ));
        $this->addElement('text', 'deadline', array(
            'label' => 'Deadline',
            'value' => ($this->order && $this->order->getDeadline() ? $this->order->getDeadline()->format('Y-m-d') : ''),
        ));
        $this->addElement('text', 'status', array(
            'label' => 'Status',
            'value' => ($this->order ? $this->order->getStatus() : ''),
        ));

        $this->addElement('submit', 'edit', array(
            'label' => ($this->edit ? 'Aanpassingen opslaan' : 'Order aanmaken'),
            'decorators' => array(
                'ViewHelper',
                array('HtmlTag', array('tag' => 'div', 'class' => 'buttons')),
            )
        ));
    }


}

