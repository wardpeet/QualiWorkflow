<?php
/**
 * Description of Product
 *
 * @author Ward
 * @Entity
 * @Table(name="orderinformatie")
 */
class Product {
    /** 
     * @Id
     * @GeneratedValue
     * @Column(type="integer",length=11)
     */
    private $productnr;

    /**
     * @ManyToOne(targetEntity="Order", inversedBy="producten")
     * @JoinColumn(name="ordernr", referencedColumnName="ordernr")
     **/
    private $ordernr;

    /** @Column(type="integer",length=1) **/
    private $instock;

    /** @Column(type="integer",length=10) **/
    private $aantal;

    /** @Column **/
    private $omschrijving;

    /** @Column(length=30) **/
    private $papier;

    /** @Column(length=7) **/
    private $formaat;

    /** @Column(length=30) **/
    private $kleurdruk;

    /** @Column **/
    private $afwerking;

    /** @Column(length=10) **/
    private $status;

    /** @Column(type="integer",length=10) **/
    private $werknemer;

    function __construct($productid, Order $order, $instock, $aantal, $omschrijving, $papier, $formaat, $kleurdruk, $afwerking, $status, $werknemer) {
        $this->setProductnr($productid);
        $this->setOrder($order);
        $this->setInstock($instock);
        $this->setAantal($aantal);
        $this->setOmschrijving($omschrijving);
        $this->setPapier($papier);
        $this->setFormaat($formaat);
        $this->setKleurdruk($kleurdruk);
        $this->setAfwerking($afwerking);
        $this->setStatus($status);
        $this->setWerknemer($werknemer);
    }
    
    public static function emptyObject() {
        $product = new Product(null, Order::emptyObject(), null, null, null, null, null, null, null, null, null, null, null);
        
        return $product;
    }

    public function getProductnr() {
        return $this->productnr;
    }
    public function setProductnr($productnr) {
        $this->productnr = $productnr;
    }
    
    public function getOrder() {
        return $this->ordernr;
    }
    public function setOrder($order) {
        $this->ordernr = $order;
    }

    public function getInstock() {
        return $this->instock;
    }
    public function setInstock($instock) {
        $this->instock = $instock;
    }

    public function getAantal() {
        return $this->aantal;
    }
    public function setAantal($aantal) {
        $this->aantal = $aantal;
    }

    public function getOmschrijving() {
        return $this->omschrijving;
    }
    public function setOmschrijving($omschrijving) {
        $this->omschrijving = $omschrijving;
    }

    public function getPapier() {
        return $this->papier;
    }
    public function setPapier($papier) {
        $this->papier = $papier;
    }

    public function getFormaat() {
        return $this->formaat;
    }
    public function setFormaat($formaat) {
        $this->formaat = $formaat;
    }

    public function getKleurdruk() {
        return $this->kleurdruk;
    }
    public function setKleurdruk($kleurdruk) {
        $this->kleurdruk = $kleurdruk;
    }

    public function getAfwerking() {
        return $this->afwerking;
    }
    public function setAfwerking($afwerking) {
        $this->afwerking = $afwerking;
    }

    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $this->status = $status;
    }

    public function getWerknemer() {
        return $this->werknemer;
    }
    public function setWerknemer($werknemer) {
        $this->werknemer = $werknemer;
    }

    public function setData($data) {
        $this->setProductnr($data['productnr']);
        $this->setOrder($data['order']);
        $this->setInstock($data['instock']);
        $this->setAantal($data['aantal']);
        $this->setOmschrijving($data['omschrijving']);
        $this->setPapier($data['papier']);
        $this->setFormaat($data['formaat']);
        $this->setKleurdruk($data['kleurdruk']);
        $this->setAfwerking($data['afwerking']);
        $this->setStatus($data['status']);
        $this->setWerknemer($data['werknemer']);
    }
}