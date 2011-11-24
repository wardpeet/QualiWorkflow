<?php

class Application_Form_Klant extends Zend_Form
{
    private $klant;
    private $edit;
    private static $em;

    public function __construct($options = null, $klant=null, $edit=false)
    {
        $this->klant = $klant;
        $this->edit = $edit;
        self::$em = Zend_Registry::get('em');

        parent::__construct($options);
    }


    public function init()
    {
        require_once 'decorators/My_Decorator_Quali.php';
        $this->addElementPrefixPath('My_Decorator', 'My/Decorator/', 'decorator');

        $this->setAction("/klanten/" . ($this->edit ? 'pedit' : 'padd'));

        $this->setElementDecorators(array('Quali'));
        
        if($this->edit) {
            $this->addElement('hidden', 'oldKlant', array(
                'decorators' => array('ViewHelper'),
                'value' => $this->klant->getKlantnr()
            ));
        }
        $this->addElement('text', 'klantnr', array(
            'label' => 'Klantnr',
            'required' => true,
            'value' => ($this->klant ? $this->klant->getKlantnr() : ''),
            'errorMessages' => array('Klantnr moet worden ingevuld.')
        ));
        $this->addElement('text', 'voornaam', array(
            'label' => 'Voornaam',
            'value' => ($this->klant ? $this->klant->getVoornaam() : ''),
        ));
        $this->addElement('text', 'achternaam', array(
            'label' => 'Achternaam',
            'value' => ($this->klant ? $this->klant->getAchternaam() : ''),
        ));
        $this->addElement('text', 'straat', array(
            'label' => 'Straat',
            'value' => ($this->klant ? $this->klant->getStraat() : ''),
        ));
        $this->addElement('text', 'nummer', array(
            'label' => 'Nummer',
            'value' => ($this->klant ? $this->klant->getNummer() : ''),
        ));
        $this->addElement('text', 'postcode', array(
            'label' => 'Postcode',
            'value' => ($this->klant ? $this->klant->getGemeente()->getPostcode() : ''),
        ));
        $this->addElement('text', 'gemeente', array(
            'label' => 'Gemeente',
            'value' => ($this->klant ? $this->klant->getGemeente()->getGemeente() : ''),
        ));
        $this->addElement('text', 'telefoon', array(
            'label' => 'Telefoon',
            'value' => ($this->klant ? $this->klant->getTelefoon() : ''),
        ));
        $this->addElement('text', 'email', array(
            'label' => 'Email',
            'value' => ($this->klant ? $this->klant->getEmail() : ''),
        ));

        $this->addElement('submit', 'edit', array(
            'label' => ($this->edit ? 'Aanpassingen opslaan' : 'Klant aanmaken'),
            'decorators' => array(
                'ViewHelper',
                array('HtmlTag', array('tag' => 'div', 'class' => 'buttons')),
            )
        ));
    }


}

