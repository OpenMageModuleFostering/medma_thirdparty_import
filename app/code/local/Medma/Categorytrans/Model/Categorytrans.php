<?php

class Medma_Categorytrans_Model_Categorytrans extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('categorytrans/categorytrans');
    }
}
