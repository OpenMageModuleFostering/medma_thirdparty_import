<?php

class Medma_Categorytrans_Block_Adminhtml_Categorytrans_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
	  $form = new Varien_Data_Form(array(
          'id' => 'edit_form',
          'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
          'method' => 'post'));

      $form->setUseContainer(true);
      $this->setForm($form);
      $hlp = Mage::helper('categorytrans');

$setCategoryName_key  = 	Mage::getSingleton('adminhtml/url')->getSecretKey("adminhtml_categorytrans","setCategoryNameAction");
	
	$collection = Mage::getResourceModel('categorytrans/categorytrans');
 
	$category_model=Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->load();

		//print_r($products_model);exit;
	//$products_values= array();

	$category_values[] = array(

                     'label' => 'Select Category',
			'value' => ''
	);
	
	foreach($category_model as $key => $values)
	{
		$category_values[] = array(
		'label' => $values['name'],
		'value' => $values['entity_id']
		);
	}	
		unset($category_values[1]);
		unset($category_values[2]);

 	
	// Most Important Line for printing the above query //

 	//$collection->printlogquery(true);
      //exit(0); 

    $fldInfo = $form->addFieldset('stockcontrol_info', array('legend'=> $hlp->__('Stockcontrol')));

	$fldInfo->addField('category_id', 'select', array(

          'label'     => $hlp->__('Category Name'),

           'class'     => 'required-entry',

          'name'      => 'category_id',

						'values'    =>$category_values,
						'onchange'  => "setCategoryName(this.options[this.selectedIndex].text);"

      ));

	$fldInfo->addField('category_name', 'hidden', array(

			'label'        => $hlp->__('Category Name'),

			'name'         => 'category_name',

			
			

	));
	
	$fldInfo->addField('english', 'text', array(
          'label'     => $hlp->__('English translate'),
           'class'     => 'required-entry',
          'name'      => 'english',
	
      ));
	
	
	

	
?>

<?php	
	if ( Mage::registry('categorytrans_data') ) {
          $form->setValues(Mage::registry('categorytrans_data')->getData());
	  }
	
	
      return parent::_prepareForm();

  }
}
