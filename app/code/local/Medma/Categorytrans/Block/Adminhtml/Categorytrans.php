<?php
class Medma_Categorytrans_Block_Adminhtml_Categorytrans extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    parent::__construct();
    $this->_controller = 'adminhtml_categorytrans';
    $this->_blockGroup = 'categorytrans';
    $this->_headerText = Mage::helper('categorytrans')->__('Enter Category');
    //$this->_removeButton('add'); 
    //d($this);

  }
}
