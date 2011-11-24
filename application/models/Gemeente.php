<?php

/**
 * Description of Gemeente
 *
 * @author Ward
 * @Entity
 */
class Gemeente {
    /** @Id
     * @Column(length=4) **/
    private $postcode;
    /** @Column(length=50) */
    private $gemeente;

    public function __construct($postcode, $gemeente) {
        $this->setPostcode($postcode);
        $this->setGemeente($gemeente);
    }

    public function getPostcode() {
        return $this->postcode;
    }
    public function setPostcode($postcode) {
        $this->postcode = $postcode;
    }

    public function getGemeente() {
        return $this->gemeente;
    }
    public function setGemeente($gemeente) {
        $this->gemeente = $gemeente;
    }
}
