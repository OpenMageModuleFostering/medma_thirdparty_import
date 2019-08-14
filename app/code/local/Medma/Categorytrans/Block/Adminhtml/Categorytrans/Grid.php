<?php
/**
 * @author Adjustware
 */ 
class Medma_Categorytrans_Block_Adminhtml_Categorytrans_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('categorytrans');
      $this->setDefaultSort('id');
	//$this->_removeButton('add_new');
	
  }

 protected function _prepareCollection()
  {

	 $collection = Mage::getModel('categorytrans/categorytrans')->getCollection();

 	
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }


  protected function _prepareColumns()
  {
   
 $this->addColumn('category_name', array(
            'header'=> Mage::helper('categorytrans')->__('Magento Category Name'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'category_name'
        ));
 $this->addColumn('sku', array(
            'header'=> Mage::helper('categorytrans')->__('English Translate'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'english'
        ));
        return $this;
  }

public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
  

}
