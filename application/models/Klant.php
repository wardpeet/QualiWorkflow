<?php

/**
 * Description of Klant
 *
 * @author Ward
 * @Entity
 */
class Klant {
    /** @Id
     * @Column(length=10) **/
    private $klantnr;
    /** @Column(length=50) */
    private $voornaam;
    /** @Column(length=50) */
    private $achternaam;
    /**
     * @OneToOne(targetEntity="Gemeente")
     * @JoinColumn(name="postcode", referencedColumnName="postcode")
     */
    public $gemeente;
    /** @Column(length=50) */
    private $straat;
    /** @Column(length=4) */
    private $nummer;
    /** @Column(length=10) */
    private $telefoon;
    /** @Column(length=50) */
    private $email;
    /**
     * @OneToMany(targetEntity="Order", mappedBy="klantnr")
     * @JoinColumn(name="klantnr", referencedColumnName="klantnr")
     */
    private $orders;

    public function __construct($klantnr, $voornaam, $achternaam, Gemeente $gemeente, $straat, $nummer, $telefoon, $email) {
        $this->setKlantnr($klantnr);
        $this->setVoornaam($voornaam);
        $this->setAchternaam($achternaam);
        $this->setGemeente($gemeente);
        $this->setStraat($straat);
        $this->setNummer($nummer);
        $this->setTelefoon($telefoon);
        $this->setEmail($email);
    }

    public static function emptyObject() {
        $klant = new Klant(null, null, null, new Gemeente(null, null), null, null, null, null);
        
        return $klant;
    }
    
    public function addOrder(Order $order) {
        $this->orders->add($order);
    }
    public function removeOrder(Order $order) {
        $this->orders->remove($order);
    }

    public function getKlantnr() {
        return $this->klantnr;
    }
    public function setKlantnr($klantnr) {
        $this->klantnr = $klantnr;
    }

    public function getVoornaam() {
        return $this->voornaam;
    }
    public function setVoornaam($voornaam) {
        $this->voornaam = $voornaam;
    }

    public function getAchternaam() {
        return $this->achternaam;
    }
    public function setAchternaam($achternaam) {
        $this->achternaam = $achternaam;
    }

    public function getGemeente() {
        return $this->gemeente;
    }
    public function setGemeente(Gemeente $gemeente) {
        $this->gemeente = $gemeente;
    }

    public function getStraat() {
        return $this->straat;
    }
    public function setStraat($straat) {
        $this->straat = $straat;
    }

    public function getNummer() {
        return $this->nummer;
    }
    public function setNummer($nummer) {
        $this->nummer = $nummer;
    }

    public function getTelefoon() {
        return $this->telefoon;
    }
    public function setTelefoon($telefoon) {
        $this->telefoon = $telefoon;
    }

    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }

    public function getOrders() {
        return $this->orders;
    }

    public function setData($data=array()) {
        $this->setKlantnr($data['klantnr']);
        $this->setVoornaam($data['voornaam']);
        $this->setAchternaam($data['achternaam']);
        $this->setStraat($data['straat']);
        $this->setNummer($data['nummer']);
        $this->setGemeente($data['gemeente']);
        $this->setTelefoon($data['telefoon']);
        $this->setEmail(($data['email']));
    }
}
