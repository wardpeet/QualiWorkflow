<?php

/**
 * Description of Order
 *
 * @author Ward
 * @Entity
 */
class Order {
    /** 
     * @Id
     * @GeneratedValue
     * @Column(type="integer",length=11)
     */
    private $ordernr;

    /**
     * @ManyToOne(targetEntity="Klant", inversedBy="orders")
     * @JoinColumn(name="klantnr", referencedColumnName="klantnr")
     */
    private $klantnr;

    /** @Column(type="datetime") **/
    private $datum;
    
    /** @Column(type="datetime") **/
    private $deadline;

    /** @Column(length=10) **/
    private $status;

    /** @OneToMany(targetEntity="Product", mappedBy="ordernr")
     *  @JoinColumn(name="ordernr", referencedColumnName="ordernr")
     */
    private $producten;

    public function __construct($ordernr, Klant $klant, $datum, $deadline, $status) {
        $this->setOrdernr($ordernr);
        $this->setKlant($klant);
        $this->setDatum($datum);
        $this->setDeadline($deadline);
        $this->setStatus($status);
        $this->producten = new Doctrine\Common\Collections\ArrayCollection();
    }

    public static function emptyObject() {
        $order = new Order(null, Klant::emptyObject(), null, null, null);
        
        return $order;
    }

    public function getOrdernr() {
        return $this->ordernr;
    }
    public function setOrdernr($ordernr) {
        $this->ordernr = $ordernr;
    }

    public function getKlant() {
        return $this->klantnr;
    }
    public function setKlant(Klant $klant) {
        $this->klantnr = $klant;
    }

    public function getDatum() {
        return $this->datum;
    }
    public function setDatum($datum) {
        $this->datum = $datum;
    }

    public function getDeadline() {
        return $this->deadline;
    }
    public function setDeadline($deadline) {
        $this->deadline = $deadline;
    }

    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $this->status = $status;
    }

    public function getProducten() {
        return $this->producten;
    }
    public function setProducten($producten) {
        $this->producten = $producten;
    }

    public function setData($data) {
        $this->setOrdernr($data['ordernr']);
        $this->setKlant($data['klant']);
        $this->setDatum(new DateTime($data['startdatum']));
        $this->setDeadline(new DateTime($data['deadline']));
        $this->setStatus($data['status']);
        
    }
}