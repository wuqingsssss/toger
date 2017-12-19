<?php
class ModelToolPic extends Model {

	public function update($data) {
		ignore_user_abort(); // run script in background
        	set_time_limit(0); // run script forever

		$all=$data['all'];
		$manufacturer_id=$data['manufacturer_id'];
		$categories='';
		$products='';
		$skus = array();
		if($all==0){
			if (isset($data['categories'])) {
				$c=0;
				foreach ($data['categories'] as $category_id) {
					if(count($data['categories'])==1){
						$categories.=$category_id;
					}else{
						if($c==0)
						$categories.=$category_id;
						else
						$categories.=','.$category_id;
						$c++;
					}
				}
			}
			
			if (isset($data['products'])) {
				$c=0;
				foreach ($data['products'] as $product_id) {
					if(count($data['products'])==1){
						$products.=$product_id;
					}else{
						if($c==0)
						$products.=$product_id;
						else
						$products.=','.$product_id;
						$c++;
					}
				}
			}
			$skus = $this->getSKU($manufacturer_id,$categories,$products);
		}else if($all==1){
			$skus = $this->getSKU();
			
		}else{
			$skus = $this->getLastestSKU();
		}
		$this->batchUpdatePic($skus);
	}
	
	private function getSKU($manufacturer_id=0,$categories='',$products=''){
		$skus = array();
		
		$sql="SELECT DISTINCT p.product_id , p.sku,LEFT(p.sku, 3) AS cate,pc.category_id  FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category pc ON ";
		$sql.=" p.product_id=pc.product_id WHERE 1!=1 ";
		if($manufacturer_id!=0)
			$sql.=" OR  p.manufacturer_id = '" . (int)$manufacturer_id . "'";
		if($categories!='')
			$sql.=" OR	p.product_id IN ( SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id IN ( '" . $categories . "' ) )";
		if($products!='')
			$sql.=" OR	p.product_id IN ( '" . $products . "' )";
		
		if($manufacturer_id==0&&$categories==''&&$products=='')
			$sql.=" OR	1=1 ";
		//throw new exception($sql);
		$query=$this->db->query($sql);
		if ($query->num_rows) {
			foreach ($query->rows as $result) {
				$skus[] = array(
					'product_id'=>$result['product_id'],
					'sku'=>$result['sku'],
					'cate'=>$result['category_id']
				);
			}
		}
		return $skus;
	}
	
	private function getLastestSKU(){
		$skus = array();
	
		$sql="SELECT DISTINCT p.product_id , p.sku,LEFT(p.sku, 3) AS cate ,pc.category_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category pc ON "; 
		$sql.=" p.product_id=pc.product_id WHERE image='' ";
	
		$query=$this->db->query($sql);
		if ($query->num_rows) {
			foreach ($query->rows as $result) {
				$skus[] = array(
						'product_id'=>$result['product_id'],
						'sku'=>$result['sku'],
						'cate'=>$result['category_id']
				);
			}
		}
		return $skus;
	}
	
	public function import_batch_image(){
		$skus = array();
		
		$sql="SELECT DISTINCT p.product_id , p.sku,LEFT(p.sku, 6) AS cate WHERE p.image=''";
		
		$query=$this->db->query($sql);
		if ($query->num_rows) {
			foreach ($query->rows as $result) {
				$skus[] = array(
					'product_id'=>$result['product_id'],
					'sku'=>$result['sku'],
					'cate'=>$result['category_id']
				);
			}
		}
		
		print_r($skus);
	}
	
	private function batchUpdatePic($skus=array()){
	//	$suffix = '.jpg';
		$suffixs = array(
					'.jpg',
					'.jpeg',
					'.png'
				);
		$floop=15;
		foreach ($skus as $sku) {
			
			$directory=DIR_IMAGE.'data/'.$sku['cate'].'/';
			$insert_directory='data/'.$sku['cate'].'/';
			$image_cache_dir = DIR_IMAGE.'cache/data/'.$sku['cate'].'/';
			
			$product_id=$sku['product_id'];
			if (is_dir($directory)) {	
				
				//$this->db->query("UPDATE " . DB_PREFIX . "product_description SET  description =CONCAT(description,'".$this->db->escape($img)."') WHERE product_id = '" . (int)$product_id . "'");
				
			 	$directory.=$sku['sku'].'/';
			 	$insert_directory.=$sku['sku'].'/';
				$image_cache_dir .= $sku['sku'].'/';
				//error_log("processing " . $directory . $sku['sku'],3,"/opt/www/www.007buy.ru/logs/error.log");
				if (is_dir($directory)) {
					
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
					foreach ($suffixs as $suffix) {
						$image=$directory.$sku['sku'].$suffix;
						$image1=$directory.$sku['sku'].strtoupper($suffix);
						if(file_exists($image)){
							$img=$insert_directory.$sku['sku'].$suffix;
						}elseif(file_exists($image1)){
							$img=$insert_directory.$sku['sku'].strtoupper($suffix);
						}
						
						if(isset($img)) {
							//error_log("main img found:" . $img,3,"/home/www/www.007buy.ru/logs/error.log");
							$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($img) . "' WHERE product_id = '" . (int)$product_id . "'");
						}
						unset($img);
						
						for ($i=0;$i<$floop;$i++) {
							$fimage=$directory.$sku['sku'].'-F'.$i.$suffix;
							$fimage1=$directory.$sku['sku'].'-F'.$i.strtoupper($suffix);
							if(file_exists($fimage)){
								$fimg=$insert_directory.$sku['sku'].'-F'.$i.$suffix;
							}elseif(file_exists($fimage1)){
								$fimg=$insert_directory.$sku['sku'].'-F'.$i.strtoupper($suffix);
							}
							if(isset($fimg)) $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($fimg) . "', sort_order = '" . (int)$i . "'");							
							unset($fimg);
							
							$ximage=$directory.$sku['sku'].'-X'.$i.$suffix;
							$ximage1=$directory.$sku['sku'].'-X'.$i.strtoupper($suffix);
							if(file_exists($ximage)){
								$tmpximg=$insert_directory.$sku['sku'].'-X'.$i.$suffix;
							}elseif(file_exists($ximage1)) {
								$tmpximg=$insert_directory.$sku['sku'].'-X'.$i.strtoupper($suffix);
							}
							if(isset($tmpximg)) $ximg.="<img src='".HTTP_IMAGE.$tmpximg ."' /><br/><br/>";
							unset($tmpximg);
							
							$gimage=$directory.$sku['sku'].'-G'.$i.$suffix;
							$gimage1=$directory.$sku['sku'].'-G'.$i.strtoupper($suffix);
							if(file_exists($gimage)){
								$tmpgimage=$insert_directory.$sku['sku'].'-G'.$i.$suffix;
							}elseif(file_exists($gimage1)) {
							    $tmpgimage=$insert_directory.$sku['sku'].'-G'.$i.strtoupper($suffix);
							}
							if(isset($tmpgimage)) $gimg.="<img src='".HTTP_IMAGE.$tmpgimage ."' /><br/><br/>";
							unset($tmpgimage);
						}	
					}
					
					if(isset($ximg)) { 
					  $this->db->query("UPDATE " . DB_PREFIX . "product SET  detail_images ='".$this->db->escape($ximg)."'  WHERE product_id = '" . (int)$product_id . "'");
					}else{
					  $this->db->query("UPDATE " . DB_PREFIX . "product SET  detail_images = null  WHERE product_id = '" . (int)$product_id . "'");
					}
					
					if(isset($gimg)) {
					    $this->db->query("UPDATE " . DB_PREFIX . "product SET  sizechart ='".$this->db->escape($gimg)."'  WHERE product_id = '" . (int)$product_id . "'");
					}	
						
					$ximg=null;
					$gimg=null;
					
				}
				
				//clear image cache
				//$this->clearCache($image_cache_dir);
				
			}
			
			
		}
		
	}
	
	public function allow_exec() {
  		if(!defined('PHP_OS') || strtolower(PHP_OS) != 'linux') return false;
			$disabled = explode(',', ini_get('disable_functions'));
			return !in_array('exec', $disabled);
  	}
	
	public function clearCache($key) {
  		if($this->allow_exec()) {
  			exec('rm -rf ' .  $key);
  		} 
  	}
	
	
	
	

}
?>
