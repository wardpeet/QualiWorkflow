<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helper_Format
 *
 * @author Ward
 */
class Zend_View_Helper_Format extends Zend_View_Helper_Abstract {

    public function format() {
        return $this;
    }
    public function telephone($value) {
        $index = (strlen($value) == 10 ? 4 : 3);
        
        return substr($value, 0, $index)
                . ' ' . substr($value, $index, 2)
                . ' ' . substr($value, $index+2, 2)
                . ' ' . substr($value, -2);
    }
}