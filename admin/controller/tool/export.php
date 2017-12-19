<?php 
class ControllerToolExport extends Controller { 
	private $error = array();
	
	public function index() {
		$this->load_language('tool/export');
		
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/export');
	
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
		       		'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
		      		'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
		       		'text'      => $this->language->get('heading_title'),
					'href'      => $this->url->link('tool/export', 'token=' . $this->session->data['token'], 'SSL'),
		      		'separator' => $this->language->get('text_breadcrumb_separator')
		);
	
		
		$this->load->model('catalog/category');
		
		$this->data['categories'] = array();
		
		$results = $this->model_catalog_category->getCategories(0);

		foreach ($results as $result) {
			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name']
			);
		}
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=tool/export&token=' . $this->session->data['token'];

		$this->data['export_customer'] = HTTPS_SERVER . 'index.php?route=tool/export/customer&token=' . $this->session->data['token'];
		
		$this->data['image'] = HTTPS_SERVER . 'index.php?route=tool/export/image&token=' . $this->session->data['token'];

		$this->template = 'tool/export.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	public function image(){
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$skus = array();
			
			$sql="SELECT DISTINCT p.product_id , LEFT(p.sku, 10) AS sku,LEFT(p.sku, 6) AS category_code FROM " . DB_PREFIX . "product p";
			
			$sql.=" WHERE p.image=''";
			
//			$sql.=" WHERE p.sku='BC60020003'";
			
			$query=$this->db->query($sql);
			
			if ($query->num_rows) {
				foreach ($query->rows as $result) {
					$skus[] = array(
						'product_id'=>$result['product_id'],
						'sku'=>$result['sku'],
						'cate'=>$result['category_code']
					);
				}
			}
			
			$this->batchUpdatePic($skus);
			
			$this->clearCache();
			
			$this->session->data['success'] = '批量更新图片完成!';
			$this->redirect(HTTPS_SERVER . 'index.php?route=tool/export&token=' . $this->session->data['token']);
		}
	}
	
	private function batchUpdatePic($skus=array()){
		$allowed = array(
			'.gif',
			'.jpg',
			'.jpeg',
			'.png'
		);
				
					
		$floop=15;
		
		foreach ($skus as $sku) {
			$images=array();
			
			$directory=DIR_IMAGE.'data/products/'.$sku['cate'].'/';
			
			$insert_directory='data/products/'.$sku['cate'].'/';
			
			$image_cache_dir = DIR_IMAGE.'cache/data/products/'.$sku['cate'].'/';
			
			$product_id=$sku['product_id'];
			
			if (is_dir($directory)) {	
				
				//$this->db->query("UPDATE " . DB_PREFIX . "product_description SET  description =CONCAT(description,'".$this->db->escape($img)."') WHERE product_id = '" . (int)$product_id . "'");
				
			 	$directory.=$sku['sku'].'/';
			 	$insert_directory.=$sku['sku'].'/';
				$image_cache_dir .= $sku['sku'].'/';
	
				if (is_dir($directory)) {
					$files = glob(rtrim($directory, '/') . '/*');
					
					if ($files) {
						foreach ($files as $file) {
							if (is_file($file)) {
								$ext = strrchr($file, '.');
							} else {
								$ext = '';
							}	
							
							if (in_array(strtolower($ext), $allowed)) {
								/*$size = filesize($file);
					
								$i = 0;
					
								$suffix = array(
									'B',
									'KB',
									'MB',
									'GB',
									'TB',
									'PB',
									'EB',
									'ZB',
									'YB'
								);
					
								while (($size / 1024) > 1) {
									$size = $size / 1024;
									$i++;
								}*/
									
								$images[] = array(
									'file'     => substr($file, strlen(DIR_IMAGE)),
									'filename' => basename($file)
//									'size'     => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]
//									'thumb'    => $this->model_tool_image->resize(substr($file, strlen(DIR_IMAGE)), 100, 100)
								);
							}
						}
					}
				}	
			}

			
			if(isset($images[0])){
				$img=$images[0]['file'];
				
				if($img){
					$sql="UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($img) . "' WHERE product_id = '" . (int)$product_id . "'";
				}
			}
		}
		
	}
	
	private function clearCache() {
		$this->cache->delete('category');
		$this->cache->delete('category_description');
		$this->cache->delete('manufacturer');
		$this->cache->delete('product');
		$this->cache->delete('product_image');
		$this->cache->delete('product_option');
		$this->cache->delete('product_option_description');
		$this->cache->delete('product_option_value');
		$this->cache->delete('product_option_value_description');
		$this->cache->delete('product_to_category');
		$this->cache->delete('url_alias');
		$this->cache->delete('product_special');
		$this->cache->delete('product_discount');
   }


	public function customer(){
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->model('tool/excel');
			
			$workbook=$this->model_tool_excel->createWorkbook();
			
			$priceFormat =$workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '######0.00'));
        	$boxFormat =$workbook->addFormat(array('Size' => 10,'vAlign' => 'vequal_space' ));
        	$weightFormat =$workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '##0.00'));
        	$textFormat =$workbook->addFormat(array('Size' => 10, 'NumFormat' => "@" ));
        
        	$filename=urlencode("Customer".'_'.date('Y-m-d', time()).".xls");
        	$sheetname="Sheet0";
        	
	        // sending HTTP headers
	        $workbook->send($filename);

	       // Creating the categories worksheet
	       $worksheet =$workbook->addWorksheet($sheetname);
	       $worksheet->setInputEncoding ( 'UTF-8' );
                

                	
                // Set the column widths
                $j = 0;
                $worksheet->setColumn($j,$j++,strlen('Firstname')+1);
                $worksheet->setColumn($j,$j++,strlen('Lastname')+1);
                $worksheet->setColumn($j,$j++,strlen('Email')+1);
//                $worksheet->setColumn($j,$j++,max(strlen('Email'),32)+1);
                $worksheet->setColumn($j,$j++,strlen('Telephone')+1);
                $worksheet->setColumn($j,$j++,strlen('Mobile')+1);
//                $worksheet->setColumn($j,$j++,max(strlen('Fax'),12)+1);
                $worksheet->setColumn($j,$j++,max(strlen('Address'),12)+1);
                $worksheet->setColumn($j,$j++,max(strlen('Province'),12)+1);
                $worksheet->setColumn($j,$j++,max(strlen('City'),12)+1);

                // The heading row
                $i = 0;
                $j = 0;
                $worksheet->writeString($i,$j++,'Firstname',$boxFormat);
                $worksheet->writeString($i,$j++,'Lastname',$boxFormat);
                $worksheet->writeString($i,$j++,'Email',$boxFormat);
//                $worksheet->writeString($i,$j++,'Email',$boxFormat);
                $worksheet->writeString($i,$j++,'Telephone',$boxFormat);
                $worksheet->writeString($i,$j++,'Mobile',$boxFormat);
//                $worksheet->writeString($i,$j++,'Fax',$boxFormat);
                $worksheet->writeString($i,$j++,'Address',$boxFormat);
                $worksheet->writeString($i,$j++,'Province',$boxFormat);
                $worksheet->writeString($i,$j++,'City',$boxFormat);
                $worksheet->writeString( $i, 30, $boxFormat );

                	// The actual categories data
                	$i += 1;
                	$j = 0;
                	
                	$this->load->model('sale/customer');
                	$this->load->model('localisation/zone');
                	$this->load->model('localisation/city');
                	
                	$data=array();
                	
                	$customers=$this->model_sale_customer->getCustomerAddresses($data);
                	
                	foreach ($customers as $result) {
                		
                		$firstname=$result['firstname'];
                		$lastname=$result['lastname'];
                		$email=$result['email'];
                		$telephone=$result['phone'];
                		$mobile=$result['mobile'];
//                		$fax=$result['fax'];
						$address=$result['address_1'];
						$province=$this->getZoneName($result['zone_id']);
						$city=$this->getCityName($result['city_id']);
                		
                		$worksheet->write( $i, $j++, $firstname);
                		$worksheet->write( $i, $j++, $lastname );
                		$worksheet->writeString( $i, $j++,$email );
                		$worksheet->writeString( $i, $j++, $telephone);
                		$worksheet->writeString( $i, $j++, $mobile);
//                		$worksheet->write( $i, $j++, $fax );
                		$worksheet->write( $i, $j++, $address );
                		$worksheet->write( $i, $j++, $province );
                		$worksheet->write( $i, $j++, $city );
                			
                		$i += 1;
                		$j = 0;
                	}
                	
                	$worksheet->freezePanes(array(1, 1, 1, 1));
                	
                	// Let's send the file
                	$workbook->close();

                	// Clear the spreadsheet caches
                	$this->clearSpreadsheetCache();
                	exit;
		} 

		$this->redirect($this->url->link('tool/export'));
	}
	
	private function getZoneName($zone_id){
		$result_info=$this->model_localisation_zone->getZone($zone_id);
		
		if($result_info){
			return $result_info['name'];
		}else{
			return '';
		}
	}
	
	private function getCityName($city_id){
		$result_info=$this->model_localisation_city->getCity($city_id);
		
		if($result_info){
			return $result_info['name'];
		}else{
			return '';
		}
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/export')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}		
	}
}
?>