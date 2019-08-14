<?php

class Medma_Categorytrans_Model_Mysql4_Categorytrans extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the florist_id refers to the key field in your database table.
        $this->_init('categorytrans/categorytrans', 'trans_id');
    }
}
