<?php 
class ControllerToolGather extends Controller { 
	private $error = array();
	
	public function index() {
		$this->load_language('tool/gather');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
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
					'href'      => $this->url->link('tool/gather', 'token=' . $this->session->data['token'], 'SSL'),
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
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=tool/gather&token=' . $this->session->data['token'];

		$this->data['category'] = HTTPS_SERVER . 'index.php?route=tool/gather/category&token=' . $this->session->data['token'];
		
		$this->data['product'] = HTTPS_SERVER . 'index.php?route=tool/gather/product&token=' . $this->session->data['token'];

		$this->template = 'tool/gather.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	public function product(){
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			if (is_uploaded_file($this->request->files['upload']['tmp_name'])) {
				$content = file_get_contents($this->request->files['upload']['tmp_name']);
			} else {
				$content = false;
			}

			if ($content) {
				$upload_file_name=$this->request->files['upload']['name'];
				
				$file = $this->request->files['upload']['tmp_name'];
	      		
	      		$a=explode('.',$upload_file_name);
	      		
	      		$b=explode('_',$a[0]);
	      		
	      		$category= array_pop($b);
	      		
	      		$this->load->model('catalog/category');
	      		
	      		$category_id=$this->getCategoryIdByCode($category);
	      		
	      		if(!$category_id){
	      			$this->error['warning'] = '分类不存在，导入失败';
	      		}else{
	      			
	      			
	      			/*$sample_mapping=array(
                		'name' => '商品名称',
                		'upc' => '原始编号',
                		'sku' => '商品编号',
                		'price' => '市场价格',
                		'special' => '优惠价格',
                		'delivery_time' => '货期',
                		'manufacturer' => '品牌',
                		'cas' => 'CAS号',
                		'mdl' => 'MDL号',
                		'formula' => '分子式',
                		'molecular' => '分子量',
                		'level' => '等级',
                		'purity' => '纯度',
                		'size' => '包装规格',
                		'image' => '主图',
                		'model' => '商品ID',
                	);*/
                	
                	$mapping=array('name','upc','sku','price','special',
                	'delivery_time','manufacturer','cas',
                	'mdl','formula','molecular','level','purity','size','image','model');
                	
                	$this->load->model('tool/excel');
                	
	      			$datas=$this->model_tool_excel->scanSheet($file,0,$mapping,1);
	      			
//	      			print_r($datas);
	      			
	      			foreach($datas as $product){
	      				$product['category_id']=$category_id;
	      				
	      				$exe_result=$this->insertProduct($product);
	      				
	      				if(!$exe_result){
	      					//log this result
	      				}
	      			}
	      			
	      			$this->clearCache();
	      			
	      			$this->session->data['success'] = '导入探索平台产品数据成功';
	      		}
	      		
				
			} else {
				$this->error['warning'] = $this->language->get('error_empty');
			}
			
			$this->redirect($this->url->link('tool/gather', 'token=' . $this->session->data['token'], 'SSL'));
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
	
	private function initProductData($datas,$category_id){
		
		
		foreach($datas as $product){
			
			
		}
	}
	
	private function checkProductExisted($sku){
		$sql="SELECT COUNT(*) AS total FROM `".DB_PREFIX."product`  WHERE `sku`='$sku'";
                	
                $query=$this->db->query($sql);
                
                if($query->row['total'] > 0){
                return TRUE;
                }else{
                return FALSE;
                }
	}
	
	private function getManufacturerId($manufacturer){
		$sql="SELECT manufacturer_id FROM `".DB_PREFIX."manufacturer` WHERE name='".$this->db->escape($manufacturer)."'";
		                
		$query=$this->db->query($sql);
		
			if($query->row){
				return $query->row['manufacturer_id'];
			}else{
				return 0;
			}
	}
	
	private function insertProduct($product){
			$languageId=1;
			
			$name = $this->db->escape($product['name']);
			$description='';
			$meta_description='';
			$meta_keyword='';
			
            $sku = $this->db->escape($product['sku']);
            $upc = $this->db->escape($product['upc']);
            $quantity = 0;
            $model = $this->db->escape($product['model']);  
            $image = '';
                
                
            $price = (float)trim($product['price']);
            $special = (float)trim($product['special']);
                		
                $date_added=' NOW() ';
                $date_modified=' NOW() ';
                $date_available=' NOW() ';
                
                $cas = $this->db->escape($product['cas']);
                $delivery_time = $this->db->escape($product['delivery_time']);
                
                $manufacturer = $this->db->escape($product['manufacturer']);
                
                $manufacturer_id=$this->getManufacturerId($manufacturer);
                
                $mdl = $this->db->escape($product['mdl']);
                $formula = $this->db->escape($product['formula']);
                $molecular = $this->db->escape($product['molecular']);
                $level = $this->db->escape($product['level']);
                $purity = $this->db->escape($product['purity']);
                
                $size = $this->db->escape($product['size']);
                $package = '';
                
                $category_id = $this->db->escape($product['category_id']);
                
                $status=1;
                $minimum = 1;
                $subtract = 1;
                $stock_status_id=7;
                $shipping = 1;
                $tax_class_id=0;
                $sort_order = 1;
                $length = '';
                $width = '';
                $height = '';
                $weight = '';
                $length_class_id=0;
                $weight_class_id=0;
                
				if(!$this->checkProductExisted($sku)){
                			$viewed=0;
                			$location='';
       
                			$sql  = "INSERT INTO `".DB_PREFIX."product` (`quantity`,`sku`,`cas`,`mdl`,`formula`,`molecular`,`level`,`purity`,`package`,`delivery_time`,";
                			$sql .= "`upc`,`model`,`manufacturer_id`,`image`,`shipping`,`price`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`status`,";
                			$sql .= "`tax_class_id`,`viewed`,`length`,`width`,`height`,`length_class_id`,`sort_order`,`subtract`,`minimum`,`size`) VALUES ";
                			$sql .= "($quantity,'$sku','$cas','$mdl','$formula','$molecular','$level','$purity','$package','$delivery_time',";
                			$sql .= "'$upc','$model',$manufacturer_id,'$image',$shipping,$price,";
                			$sql .= ($date_added=='NOW()') ? "$date_added," : " $date_added,";
                			$sql .= ($date_modified=='NOW()') ? "$date_modified," : "$date_modified,";
                			$sql .= ($date_available=='NOW()') ? "$date_available," : "$date_available,";
                			$sql .= "'$weight',$weight_class_id,$status,";
                			$sql .= "$tax_class_id,$viewed,'$length','$width','$height','$length_class_id','$sort_order','$subtract','$minimum','$size');";

                			$this->db->query($sql);

                			$sql_product_id  ="SELECT MAX(product_id) AS product_id  FROM `".DB_PREFIX."product`";

                			$product_id_row=$this->db->query($sql_product_id);
                				
                			$productId=$product_id_row->row['product_id'];

                			$sql2 = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`,`language_id`,`name`,`description`,`meta_description`,`meta_keyword`) VALUES ";
                			$sql2 .= "($productId,$languageId,'$name','$description','$meta_description','$meta_keyword');";

                			$this->db->query($sql2);
                			
                			
                			if($category_id){
                				$sql3="INSERT INTO `".DB_PREFIX."product_to_category` (`product_id`,`category_id`) VALUES ";
                				$sql3 .= "($productId,'$category_id');";
                				
                				$this->db->query($sql3);
                			}
                			
                			$storeId=0;
                			$sql6 = "INSERT INTO `".DB_PREFIX."product_to_store` (`product_id`,`store_id`) VALUES ($productId,$storeId);";
                			$this->db->query($sql6);
                			
                			return TRUE;
                		}else{
                			if($sku && $size){
                				$sql="UPDATE `".DB_PREFIX."product` SET size='".$size."' WHERE sku='".$sku."'";
                				
                				$database->query($sql);
                			}
                			
							return FALSE;
                		}  
	}
	
 	private  function detect_encoding( $str ) {
                // auto detect the character encoding of a string
    }
	
	public function category(){
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			if (is_uploaded_file($this->request->files['upload']['tmp_name'])) {
				$content = file_get_contents($this->request->files['upload']['tmp_name']);
			} else {
				$content = false;
			}

			if ($content) {
				$this->importCategory($content);

				$this->session->data['success'] = '导入探索平台分类成功';

				$this->redirect($this->url->link('tool/gather', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->error['warning'] = $this->language->get('error_empty');
			}
		}
	}
	
	private function importCategory($sql) {
		foreach (explode("\n", $sql) as $sql) {
    		$sql = trim($sql);
    		
    		$explode=array();
    		$explode=explode(',',$sql);
    		
    		if(COUNT($explode)!=3){
    			continue;
    		}
    		
    		$category_code=isset($explode[0]) ? $explode[0]: '';
    		$name=isset($explode[1]) ? $explode[1]: '';
    		$parent=isset($explode[2]) ? $explode[2]: '';
    		
    		$this->addCategory($category_code,$name,$parent);
    		
    		/*$category_id=$this->checkCategoryExisted($name);
    		
    		if(!$category_id){
    			$this->addCategory($category_code,$name,$parent);
    		}else{
    			//更新分类 CODE
    			$this->editCategoryCode($category_id,$category_code);
    			
    			$parent_id=$this->getCategoryIdByCode($parent);
		
				if($parent_id){
					$this->editCategoryParent($category_id,$parent_id);
				}
    		}*/
  		}
	}
	
	private function editCategoryCode($category_id,$code){
		$this->db->query("UPDATE ".DB_PREFIX."category SET code='".$this->db->escape($code)."' WHERE category_id=".(int)$category_id);
	}
	
	private function editCategoryParent($category_id,$parent_id){
		$this->db->query("UPDATE ".DB_PREFIX."category SET parent_id='".(int)$parent_id."' WHERE category_id=".(int)$category_id);
	}
	
	private function checkCategoryExisted($name){
		$sql="SELECT category_id FROM ".DB_PREFIX."category_description WHERE name='".$this->db->escape($name)."'";
		
		$query=$this->db->query($sql);
		
		if($query->num_rows){
			return $query->row['category_id'];
		}else{
			return FALSE;
		}
	}
	
	private function addCategory($code,$name,$parent){
		$sql="INSERT INTO ".DB_PREFIX."category SET code='".$this->db->escape($code)."',status=1,date_added=now(),date_modified=NOW()";
		
		$this->db->query($sql);
		
		$category_id=$this->db->getLastId();
		
		$language_id=1;
		
		$sql2="INSERT INTO ".DB_PREFIX."category_description SET category_id=".(int)$category_id.",language_id=".(int)$language_id.",name='".$this->db->escape($name)."'";
		
		$this->db->query($sql2);
		
		$sql3="INSERT INTO ".DB_PREFIX."category_to_store SET category_id=".(int)$category_id.",store_id=0";
		
		$this->db->query($sql3);
		
		$parent_id=$this->getCategoryIdByCode($parent);
		
		if($parent_id){
			$this->db->query("UPDATE ".DB_PREFIX."category SET parent_id='".(int)$parent_id."' WHERE category_id=".(int)$category_id);
		}
	}
	
	private function getCategoryIdByName($name){
		
	}
	
	private function getCategoryIdByCode($code){
		$sql="SELECT category_id FROM ".DB_PREFIX."category WHERE code='".$this->db->escape($code)."'";

		$query=$this->db->query($sql);
		
		if($query->row){
			return $query->row['category_id'];
		}else{
			return FALSE;
		}
	}
	
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/gather')) {
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