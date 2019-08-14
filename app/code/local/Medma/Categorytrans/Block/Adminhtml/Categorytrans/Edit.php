<?php 
class Medma_Categorytrans_Block_Adminhtml_Categorytrans_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
  public function __construct()
  { 
	  parent::__construct();
        $this->_objectId = 'id'; 
        $this->_blockGroup = 'categorytrans';
        $this->_controller = 'adminhtml_categorytrans';
	 $this->_formScripts[] = "
		function setCategoryName(cat_name){
				//alert(cat_name);
				document.getElementById('category_name').value=cat_name;
			}	
        ";

  }

  
	public function getHeaderText()

  {

	  if( Mage::registry('categorytrans_data') && Mage::registry('categorytrans_data')->getId() ) {

		  return Mage::helper('categorytrans')->__("Edit Category '%s'", $this->htmlEscape(Mage::registry('categorytrans_data')->getName()));


	  } else {

		  return Mage::helper('categorytrans')->__('Add Category');

	  }

  }
}
