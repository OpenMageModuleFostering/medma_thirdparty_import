<?php

class Medma_Categorytrans_Model_Mysql4_Categorytrans_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('categorytrans/categorytrans');
    }
}
