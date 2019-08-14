<?php
 
class Medma_Importproduct_Model_Observer
{
	

	/**
	* Flag to stop observer executing more than once
	*
	* @var static bool
	*/
	
	/**
	* This method will run when the product is saved from the Magento Admin
	* Use this function to update the product model, process the
	* data or anything you like
	*
	* @param Varien_Event_Observer $observer
	*/
 
	
	public function importProductCoolwear(){
	$flag=true;
	$i=0;
	$product_array=array();
	$category_array=array();
	$configurable_attribute = "variation"; $attr_id = 132;
	$importDir = Mage::getBaseDir('media') . DS . 'import/';
	$contents = file_get_contents ("http://devilwear.co.uk/store/feeds/es_edirectory.txt");
	//$contents = file_get_contents ("my.txt");
	$lines = explode("##,", $contents);
		foreach($lines as $line) {
			

			$result  =str_getcsv($line, ",", "\"");
				
			//if(($result['33']=='Jeans & Trousers')&&($result['32']=='Mens')){
			$sub_category_model = Mage::getModel('categorytrans/categorytrans')->getCollection()->addFieldToFilter('english', array('eq' => $result['33']));
			foreach ($sub_category_model as $sub_category_dtl)
				$sub_category_id=$sub_category_dtl['category_id'];
				
				array_push($category_array, $sub_category_id);
				//$category_array.push($sub_category_id);
			//print_r($result);
			try{
			$translate_model = Mage::getModel('categorytrans/categorytrans')->getCollection()->addFieldToFilter('english ', array('eq' => $result['32']));
			foreach ($translate_model as $translate_dtl){
				$translate_dtl['category_id'];
				array_push($category_array,$translate_dtl['category_id']);
			
					if($translate_dtl['category_id']){
						$cProduct = Mage::getModel('catalog/product'); 
						$exchange_rate=9.5;
						$name=basename($result['12']);
	
						$name = explode(".", $name);
						$finalprice=$result['20']*$exchange_rate;
						if($result['3']){
							$conf_attribute =$result['3'];
							$conf_attribute = explode("-",$conf_attribute);
							//echo 'conf'.$conf_attribute['0'];
							foreach ($conf_attribute as $value) {
								$variation_id = $this->attributeValueExists('variation',$value); 
								if($variation_id) 
								{
								
								}else{
								$this->addAttributeValue('variation',$value);
								$variation_id =$this->attributeValueExists('variation',$value);
								}
								
		
								$image_name=basename($result['12']);
								$mediaArray = array(
										'thumbnail'   =>$result['12'],
										'small_image' =>$result['12'],
										'image'       =>$result['12'],
										);
								/*create Simple Product*/
								$sProduct = Mage::getModel('catalog/product'); 
								
								$productId = $sProduct->getIdBySku($result['0'].'/'.$value);
								
								if($productId){
									$_product = $sProduct->load($productId ); 
									$stockData = $_product->getStockData();
									$stockData['qty'] = $result['25'];
									$stockData['is_in_stock'] = 1;
									$_product->setStockData($stockData);
									$_product->save();
									}else{
									
									$sProduct ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) 		   ->setWebsiteIds(array(1))    		   		 ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED) ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) 
									->setTaxClassId(0) 
									->setAttributeSetId(4) 
									
									// Populated further up the script 
									->setSku($result['0'].'/'.$value)
									// $main_product_data is an array created as part of a wider foreach loop, which this code is inside of 
									->setName($result['1'])
									->setShortDescription($result['1']) 
									->setDescription($result['2']) 
									->setCost(sprintf("%0.0f", $result['20'])) 
									->setPrice(sprintf("%0.0f", $finalprice)) 
									->setCategoryIds($category_array) ->setData($configurable_attribute,$variation_id) ;  // Set the stock data. Let Magento handle this as opposed to manually creating a cataloginventory/stock_item model.. 
									
								$sProduct->setStockData(array( 'is_in_stock' => 1, 'qty' => $result['25'] ));   $sProduct->save();
								
									// Store some data for later once we've created the configurable product, so we can // associate this simple product to it later.. 
								/*creating configure product*/
									$cProduct = Mage::getModel('catalog/product'); 
								
								$configureProductId =$cProduct->getIdBySku($result['0']);
									if($configureProductId){
										$this->addToConfigurable($configureProductId,$sProduct->getId());
									}
									else{
									$cProduct->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) 	->setTaxClassId(1) 
										->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED) 
										->setTaxClassId(0) 
										->setWebsiteIds(array(1)) 
										->setAttributeSetId(4) // You can determine this another way if you need to.
										->setCategoryIds($category_array)
										->setSku($result['0']) 
										->setName($result['1'])
										->setShortDescription($result['1']) 
										->setDescription($result['2']) 
										->setCost(sprintf("%0.0f", $result['20'])) 
										->setPrice(sprintf("%0.0f", $finalprice)) 
										//->setUrlKey($name['0']) 
										->setData($configurable_attribute,$variation_id)
										->setCreatedAt(strtotime('now'));
										$img =$result['12'];	
										/*creating image from url save it to given path*/
										$fullpath = $importDir.$image_name;
											
											$ch = curl_init ($img);
											
											curl_setopt($ch, CURLOPT_HEADER, 0);
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
											curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
											$rawdata=curl_exec($ch);
											curl_close ($ch);
											if(file_exists($fullpath)){
												unlink($fullpath);
											}
											
											$fp = fopen($fullpath,'x');
											fwrite($fp, $rawdata);
											fclose($fp);
											foreach($mediaArray as $imageType => $fileName) {
												
												if ( file_exists($fullpath) ) {
													$cProduct->addImageToMediaGallery($fullpath, $imageType, false);
													
												} 
												}
				// 							
											
									
									
									$cProduct->setStockData(array( 'use_config_manage_stock' => 1, 'is_in_stock' => 1, 'is_salable' => 1 ,'qty' =>$result['25']));
									$cProduct->setCanSaveConfigurableAttributes(true);
									$cProduct->setCanSaveCustomOptions(true);
									$cProductTypeInstance = $cProduct->getTypeInstance();
									$cProductTypeInstance->setUsedProductAttributeIds(array($_attributeIds[$configurable_attribute]));   
									// Now we need to get the information back in Magento's own format, and add bits of data to what it gives us.. 
									$data = array('0'=>array('id'=>NULL,'label'=>'Variation','position'=> NULL,
												'values'=>array('0'=>
													array('value_index'=>$conf_attribute['0'],'label'=>$conf_attribute['0'],'is_percent'=>0,
														'pricing_value'=>'0',
													'attribute_id'=> $attr_id),
										),	
										'attribute_id'=> $attr_id,'attribute_code'=>'variation','frontend_label'=>'Variation',
										'html_id'=>'config_super_product__attribute_0')
									);
									$cProduct->setConfigurableAttributesData($data);
									
									
									
								
									// $cProduct->setConfigurableProductsData($data);
									$cProduct->save();
									$this->addToConfigurable($cProduct->getId(),$sProduct->getId());
									}
									
									
								}
								
							}
						}else{
							$cProduct = Mage::getModel('catalog/product'); 
							$simpleproductId = $cProduct->getIdBySku($result['0']);
							if($simpleproductId){
								$_product = $cProduct->load($simpleproductId ); 
								$stockData = $_product->getStockData();
								$stockData['qty'] = $result['25'];
								$stockData['is_in_stock'] = 1;
								$_product->setStockData($stockData);
								$_product->save();
								

							}else{
								$image_name=basename($result['12']);
								$mediaArray = array(
										'thumbnail'   =>$result['12'],
										'small_image' =>$result['12'],
										'image'       =>$result['12'],
										);
								// Remove unset images, add image to gallery if exists
								
								
								$cProduct->setTypeId('simple') 
									->setTaxClassId(0) 
									->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED) 
									->setWebsiteIds(array(1)) 
									->setAttributeSetId(4) // You can determine this another way if you need to.
									->setCategoryIds($category_array)
									->setSku($result['0']) 
									->setName($name['0'])
									->setShortDescription($result['1']) 
									->setDescription($result['2']) 
									->setCost(sprintf("%0.0f", $result['20'])) 
									->setPrice(sprintf("%0.0f", $finalprice)) 
									//->setUrlKey($name['0']) 
									->setStockData(array(
												'is_in_stock' => 1,
												'qty' =>$result['25']
												))
									->setCreatedAt(strtotime('now'));
									$img =$result['12'];	
		
									$fullpath = $importDir.$image_name;
									/*creating image from url save it to given path*/
									$ch = curl_init ($img);
									
									curl_setopt($ch, CURLOPT_HEADER, 0);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
									$rawdata=curl_exec($ch);
									curl_close ($ch);
									if(file_exists($fullpath)){
										unlink($fullpath);
									}
									
									$fp = fopen($fullpath,'x');
									fwrite($fp, $rawdata);
									fclose($fp);
									foreach($mediaArray as $imageType => $fileName) {
										
										if ( file_exists($fullpath) ) {
											$cProduct->addImageToMediaGallery($fullpath, $imageType, false);
											
										} 
										}
								
									$cProduct->save();
							}
						}
						
					}
				}
			}catch(Exception $e){
			Mage::log('Product Import Error: '.$e->getMessage().' '.$e->getTraceAsString() , null, 'productImport.log');
			}
				
			//}//my if block
			
		}
		
		
	}

	/****************Checks Attribute Present or Not*************************/
	public function attributeValueExists($arg_attribute, $arg_value)
    	{
		$attribute_model        = Mage::getModel('eav/entity_attribute');
		$attribute_options_model= Mage::getModel('eav/entity_attribute_source_table') ;
	
		$attribute_code         = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
		$attribute              = $attribute_model->load($attribute_code);
		
		$attribute_table        = $attribute_options_model->setAttribute($attribute);
		$options                = $attribute_options_model->getAllOptions(false);
		
		foreach($options as $option)
		{
		if ($option['label'] == $arg_value)
		{
			return $option['value'];
		}
		}
		
		return false;
    	}
	
	/****************Add Attribute If not Present*************************/
	 public function addAttributeValue($arg_attribute, $arg_value)
    	{
		$attribute_model        = Mage::getModel('eav/entity_attribute');
		$attribute_options_model= Mage::getModel('eav/entity_attribute_source_table') ;
	
		$attribute_code         = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
		$attribute              = $attribute_model->load($attribute_code);
		
		$attribute_table        = $attribute_options_model->setAttribute($attribute);
		$options                = $attribute_options_model->getAllOptions(false);
		
		if(!$this->attributeValueExists($arg_attribute, $arg_value))
		{
		$value['option'] = array($arg_value,$arg_value);
		$result = array('value' => $value);
		$attribute->setData('option',$result);
		$attribute->save();
		}
		
		foreach($options as $option)
		{
		if ($option['label'] == $arg_value)
		{
			return $option['value'];
		}
		}
		return true;
    	}

	/****************Fetch Attribute Value*************************/
	public function getAttributeValue($arg_attribute, $arg_option_id)
    	{
		$attribute_model        = Mage::getModel('eav/entity_attribute');
		$attribute_table        = Mage::getModel('eav/entity_attribute_source_table');
		
		$attribute_code         = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
		$attribute              = $attribute_model->load($attribute_code);
		
					$attribute_table->setAttribute($attribute);
					
		$option                 = $attribute_table->getOptionText($arg_option_id);
		
		return $option;
   	 }
	
	/**************Mapping Simple Product to Configure Product*********************/
	public function addToConfigurable($config_product_id, $simple_product_id)
    	{
		$config_product    = Mage::getModel('catalog/product') -> load($config_product_id);
		$new_ids        = array();
		$current_ids        = $config_product -> getTypeInstance() -> getUsedProductIds();
		$current_ids[]    = $simple_product_id;
		$current_ids        = array_unique($current_ids);
	
		foreach($current_ids as $temp_id)
		{
		parse_str("position=", $new_ids[$temp_id]);
		}
	
		$config_product -> setConfigurableProductsData($new_ids) -> save();
    	} 


}
