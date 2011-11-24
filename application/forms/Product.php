<?php

class Application_Form_Product extends Zend_Form
{
    private $product;
    private $edit;
    /** @var EntityManager */
    private static $em;

    public function __construct($options = null, $product=null, $edit=false)
    {
        $this->product = $product;
        $this->edit = $edit;
        self::$em = Zend_Registry::get('em');

        parent::__construct($options);
    }

    public function init()
    {
        require_once 'decorators/My_Decorator_Quali.php';
        $this->addElementPrefixPath('My_Decorator', 'My/Decorator/', 'decorator');

        $this->setAction("/product/" . ($this->edit ? 'pedit' : 'padd'));

        $this->setElementDecorators(array('Quali'));

        $ordersRes = self::$em->createQuery("SELECT o.ordernr FROM order o")->getResult();

        // build options
        foreach($ordersRes as $o) {
            $orders[$o['ordernr']] = $o['ordernr'];
        }

        if($this->edit) {
            $this->addElement('hidden', 'oldProduct', array(
                'decorators' => array('ViewHelper'),
                'value' => $this->product->getProductnr()
            ));
        }
        $this->addElement('hidden', 'productnr', array(
            'decorators' => array('ViewHelper'),
            'value' => ($this->product ? $this->product->getProductnr() : '')
        ));
        $this->addElement('select', 'ordernr', array(
            'label' => 'Ordernr',
        ));
        $this->getElement('ordernr')->addMultiOptions($orders)
                ->setValue(($this->product ? $this->product->getOrder()->getOrdernr() : ''));

        $this->addElement('checkbox', 'instock', array(
            'label' => 'Instock',
            'value' => ($this->product ? $this->product->getInstock() : '')
        ));
        $this->getElement('instock')->setOptions(array('class' => 'checkbox'));
        $this->addElement('text', 'aantal', array(
            'label' => 'Aantal',
            'value' => ($this->product ? $this->product->getAantal() : ''),
        ));
        $this->addElement('text', 'omschrijving', array(
            'label' => 'Omschrijving',
            'value' => ($this->product ? $this->product->getOmschrijving() : ''),
        ));
        $this->addElement('text', 'papier', array(
            'label' => 'Papier',
            'value' => ($this->product ? $this->product->getPapier() : ''),
        ));
        $this->addElement('text', 'formaat', array(
            'label' => 'Formaat',
            'value' => ($this->product ? $this->product->getFormaat() : ''),
        ));
        $this->addElement('text', 'kleurdruk', array(
            'label' => 'Kleurdruk',
            'value' => ($this->product ? $this->product->getKleurdruk() : ''),
        ));
        $this->addElement('text', 'afwerking', array(
            'label' => 'Afwerking',
            'value' => ($this->product ? $this->product->getAfwerking() : ''),
        ));
        $this->addElement('text', 'status', array(
            'label' => 'Status',
            'value' => ($this->product ? $this->product->getStatus() : ''),
        ));

        $this->addElement('submit', 'edit', array(
            'label' => ($this->edit ? 'Aanpassingen opslaan' : 'Product aanmaken'),
            'decorators' => array(
                'ViewHelper',
                array('HtmlTag', array('tag' => 'div', 'class' => 'buttons')),
            )
        ));
    }


}