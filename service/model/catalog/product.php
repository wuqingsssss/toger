<?php
class ModelCatalogProduct extends Model {
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}
	
	private function getModProductName($product_info){
		$name=$product_info['name'];
				
		if($product_info['manufacturer']){
			$name.=" | ".$product_info['manufacturer'];
		}
		
		if($product_info['level']){
			$name.=" | ".$product_info['level'];
		}
		
		if($product_info['purity']){
			$name.=" | ".$product_info['purity'];
		}
		
		return $name;
	}

	/** 
	 * 获取菜品折扣信息
	 * @param unknown $product_id  菜品ID
	 * @param unknown $type        折扣类型，0：换购，1：限时抢购
	 */
	public function getProcutDiscountInfo($product_id, $type=0){
	    $sql="SELECT * FROM  ts_product_discount  WHERE product_id= ".(int)$product_id. " AND type=".(int) $type." AND NOW()>date_start AND NOW()<date_end LIMIT 1";
	    $query=$this->db->query($sql);
	    
	    return $query->row;
	}
	
	private function getProductToTags($product_id){
		$sql="SELECT * FROM " . DB_PREFIX . "product_to_tag WHERE product_id=".(int)$product_id;
		
		$query=$this->db->query($sql);
		
		$tags=array();
		
		foreach($query->rows as $result){
			$tags[]=$result['tag_id'];
		}
		
		return $tags;
	}
	
	public function getProductIdBySku($sku)
	{
		$sql="SELECT product_id FROM  ts_product  WHERE sku= '$sku'";
		$query=$this->db->query($sql);
        if($query->num_rows==1){
        	return $query->row['product_id'];
        }
        else
        return false;
	}
	/**
	 * 通过多个sku获取产品ID
	 * @param type $sku
	 * @return type
	 */
	public function getProductIdBySkus($sku){
		$str = '(' . implode(',', $sku).')';
		$sql="SELECT product_id, sku, prod_type FROM  ts_product  WHERE sku in {$str}";
		$query=$this->db->query($sql);
		$arr = $query->rows;
		foreach($arr as $a){
			$return[$a['sku']] = $a;
		}
		return $return;
	}
	
	public function isProductInPeriod($productId,$periodId,$pick_times){

		// 根据session标记获取当前菜品所在周期id
		$sql="select * from ts_product_supply_period p";
		
		if($pick_times) $sql.=" LEFT JOIN ts_supply_period as sp ON sp.id=p.period_id";
		
		$sql.=" WHERE p.product_id='".(int)$productId."'";
	if((int)$periodId>0)
		$sql.=" AND p.period_id='".(int)$periodId."'";
	if($pick_times&&$periodId!=0)
		$sql.=" AND sp.p_end_date>=DATE('".$pick_times."') and sp.p_start_date<=DATE('".$pick_times."')";
	
		$row=$this->db->query($sql)->row;
		
		return !empty($row);
	}
	
	/**
	 * 获取菜品信息
	 * @param unknown $product_id
	 * @param number $period_id
	 * @return multitype:number NULL unknown multitype:multitype:NULL unknown   |boolean
	 */
	public function getProduct($product_id,$period_id=0,$pick_times='') {
		
		if(empty($product_id))return false;
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		
		$sql="SELECT DISTINCT *, pd.name AS name,
				pd.share_title AS share_title,
				pd.share_desc AS share_desc,
				pd.subtitle AS subtitle,
				pd.storage AS storage,
				pd.unit AS unit,
				pd.origin AS origin,
				pd.delivery AS delivery,
				p.image, 
				m.name AS manufacturer";
				/*活动价在购物车单独处理
				 * $sql.=",(SELECT price FROM " . DB_PREFIX . "product_discount pd2
						 WHERE pd2.product_id = p.product_id
						 AND pd2.customer_group_id = '" . (int)$customer_group_id . "'
						 		 AND (pd2.quantity >'0' OR pd2.quantity ='-1')
						 		 AND ((pd2.date_start = '0000-00-00' OR pd2.date_start <= curdate())
						 		 AND (pd2.date_end = '0000-00-00' OR pd2.date_end >= curdate()))
						 		 ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount";
				$sql.=",(SELECT price FROM " . DB_PREFIX . "product_special ps
				 		 WHERE ps.product_id = p.product_id
				 		 AND ps.customer_group_id = '" . (int)$customer_group_id . "'
				 		 		 AND (ps.quantity >'0' OR ps.quantity ='-1')
				 		 		 AND ((ps.date_start = '0000-00-00' OR ps.date_start <= curdate())
				 		 		 AND (ps.date_end = '0000-00-00' OR ps.date_end >= curdate()))
				 		 		 ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";	*/		 		 		
				$sql.=",(SELECT points FROM " . DB_PREFIX . "product_reward pr
				 		 WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward,
				 (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss
				 		 WHERE ss.stock_status_id = p.stock_status_id
				 		 AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "')
				 		 		 AS stock_status,
				 (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd
				 		 WHERE p.weight_class_id = wcd.weight_class_id
				 		 AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class,
				  (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd
				  		 WHERE p.length_class_id = lcd.length_class_id
				  		 AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class,
				  (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1
				  		 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating,
				  (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2
				  		 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews
			 FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
			 		 LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
			 	     LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
			  WHERE p.product_id = '" . (int)$product_id . "'
			  		 AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			  		 AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		$cache = md5($sql);		
		if(is_object($this->mem)){
			$memNameSpace=$this->mem->get_namespace('product'.'.'.$product_id);
			$product_data = $this->mem->get($memNameSpace .'.'. $cache . '.' . $customer_group_id);
		}
		
		if(!$product_data){
		$query = $this->db->query($sql);
		
		$icons=$this->getProductTags($product_id);
		
		$this->load->model('catalog/vote');
		$voted_good_num=$this->model_catalog_vote->getProductVoteNum($product_id);
		
		

		if ($query->num_rows) {
			$product_data= array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'share_title'             => $query->row['share_title'],
				'share_desc'             => $query->row['share_desc'],
				'share_link'             => $query->row['share_link'],
				'share_image'             => $query->row['share_image'],
				'icons'             => $icons,
				'description'      => $query->row['description'],
				'meta_title'	   => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'subtitle'         => $query->row['subtitle'],
				'origin'           => $query->row['origin'],
				'unit'             => $query->row['unit'],
				'storage'          => $query->row['storage'],
				'delivery'         => $query->row['delivery'],
				'model'            => $query->row['model'],
				'prod_type'        => $query->row['prod_type'],
				'shipping'         => $query->row['shipping'] ,
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'location'         => $query->row['location'],
				'quantity'         => $specialquantity>$query->row['quantity']?$specialquantity:$query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => $query->row['price'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'date_unavailable'   => $query->row['date_unavailable'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => (int)$query->row['rating'],
				'reviews'          => $query->row['reviews'],
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
				'prod_type'        => $query->row['prod_type'],
				'donation'         => $query->row['donation'],
				'size'             => $query->row['size'],
				'garnish'          => $query->row['garnish'],
				'cooking_time'     => $query->row['cooking_time'],
				'calorie'          => $query->row['calorie'],
				'follow'           => $query->row['follow'],
				'voted_good_num'   => $voted_good_num,
				'period_id'        => $period_id,	
				'delivery_time'    => $query->row['delivery_time'],
			    'cooking'          => $query->row['cooking'],
				'combine'         => $query->row['combine'],
				'packing_type'    => $query->row['packing_type'],
				'link_url'    => $query->row['link_url']
			);	
			if(is_object($this->mem)){
			$memNameSpace=$this->mem->get_namespace('product'.'.'.$product_id);
			$this->mem->set($memNameSpace . '.'. $cache . '.' . $customer_group_id, $product_data,0,1800);
			$this->log_db->debug('memcache:set:'.$memNameSpace .'.'. $cache . '.' . $customer_group_id);
			}
		
		} 
		else 
		{
			$product_data=false;
				
		}
		}
		else 
		{
			$this->log_db->debug('memcache:get:'.$memNameSpace .'.'. $cache . '.' . $customer_group_id);
		//校准库存
			$query = $this->db->query("SELECT quantity,subtract FROM " . DB_PREFIX . "product WHERE product_id='".$product_id."'");
			if ($query->num_rows) {
				$product_data['quantity']=$query->row['quantity'];
				$product_data['subtract']=$query->row['subtract'];
			}
		}

		
		//促销信息
		$promotion = array();

		$specials=$this->getProductSpecial($product_id);
		//print_r($promotion);
		$priceresetpro=array('PROMOTION_SPECIAL','PROMOTION_RUSH');
		
		$promotion=array();
		$promotions=array();
		
		foreach ($specials as $special ) {
			if(! $special ['code'])$special ['code']='PROMOTION_SPECIAL';
			$specialquantity = 0;
		
			if(in_array($special ['code'], $priceresetpro)){
					
				if (!$promotion&&strtotime($special ['date_start'])<time()&&strtotime($special ['date_end'])>time()) {
					$specialquantity = $special ['quantity'];
					$promotion ['limited'] = $special ['limited'];
					$promotion ['promotion_price'] = $special ['price'];
					if ($special ['tags']) {
						$promotion ['promotion_code'] = $special ['code'] . '::' . $special ['tags'] . '::' . $special ['date_start'];
					} else {
						$promotion ['promotion_code'] = $special ['code'] . '::' . $special ['product_special_id'] . '::' . $special ['date_start'];
					}
					
					$promotions[$special['code']]=$special;
					if($special ['quantity']<=0 && $query->row['subtract'])
					{
						$promotions[$special['code']]['status_name']='已售完';
						$promotions[$special['code']]['status']='3';
						
						$promotion=array();
					}
					else{
						$query->row ['quantity']=$special ['quantity'];
						$promotions[$special['code']]['status_name']='进行中';
						$promotions[$special['code']]['status']='1';
						
					}
				}
		
				if($special ['code']=='PROMOTION_RUSH'&&!isset($promotions[$special['code']])){
		
					$promotions[$special['code']]=$special;
					if(strtotime($special ['date_start'])>time()){
						$promotions[$special['code']]['status_name']='未开始';
						$promotions[$special['code']]['status']='-1';
					}
					if(strtotime($special ['date_end'])<time()){
						$promotions[$special['code']]['status_name']='已结束';
						$promotions[$special['code']]['status']='2';
					}
						
						
				}
		
			}
		}
		$product_data['promotion']        = $promotion;
		$product_data['promotions']       = $promotions;
		
		$datenow = strtotime ( date ( 'Y-m-d', time () ) );
		if (strtotime ( $product_data ['date_available'] ) > $datenow) {
			$available = -1; // 未开始
			$status_name="未开始";
		}elseif (( int ) $product_data ['date_unavailable'] && strtotime ( $product_data ['date_unavailable'] ) < $datenow) {
			$available = 2; // 已过期
			$status_name="已过期";
		}
		else {
			$available = 1;
			$status_name="售卖中";
		}
		
		if ((int)$product_data ['quantity'] <= 0 && $product_data ['subtract']) {
			$available = 3; // 库存不足
			$status_name="已售完";
		}
		
		if ($period_id) {
			if (! $this->isProductInPeriod ( $product_id, $period_id, $pick_times ))
			{
				$available = 4;
				$status_name="不可售";
			}
		}

		$product_data['available']=$available;
		$product_data['status_name']=$status_name;

			return $product_data;
	}	
	/**
	 * 获取所有周期内的菜品
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getSupplyProductsList($data = array()) {
		if(!isset($data['filter_show_date']))
		{
			$data['filter_show_date']=date('Y-m-d',time());	
		}
		/*
		if(!isset($data['start']))
		{
			$data['start']=0;		
		}
		if(!isset($data['limit']))
		{
			$data['limit']=getShowLimit();	
		}
		*/
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
	
		$cache = md5(http_build_query($data));

		
		if(is_object($this->mem)){
			$memNameSpace=$this->mem->get_namespace('products');
		$product_data = $this->mem->get($memNameSpace.'.' . $cache . '.' . $customer_group_id);
		}
		else 
		{
		$product_data = $this->cache->get('products.' . $cache . '.' . $customer_group_id);
		}
		$this->log_db->debug('product.' . $cache . '.' . $customer_group_id);
		if (!$product_data) {
			$this->log_db->debug('getproduct_data');
			$sql = "SELECT p.product_id,sp.id as period_id,sp.name as sp_name,sp.name2 as sp_name2,sp.start_date,sp.end_date,sp.p_start_date,sp.p_end_date,cat.category_id,catd.name as cat_name,
					(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating 
					FROM " . DB_PREFIX . "product p 
					LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
					LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
					LEFT JOIN ".DB_PREFIX."product_supply_period psp on(psp.product_id=p.product_id) 
					LEFT JOIN ".DB_PREFIX."supply_period sp on(sp.id=psp.period_id) 
					LEFT JOIN ".DB_PREFIX."product_to_category cat on(cat.product_id=p.product_id) 
					LEFT JOIN ".DB_PREFIX."category c on(c.category_id=cat.category_id) 
					LEFT JOIN ".DB_PREFIX."category_description catd on(c.category_id=catd.category_id)  
					WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
					AND p.status = '1'  
					AND c.status = '1'  
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
				
			$sql=$this->getFilterSql($sql,$data);
			
			if(isset($data['filter_show_date'])&&!is_null($data['filter_show_date'])){
				$sql .= " and sp.end_date>=DATE('".$data['filter_show_date']."') and sp.start_date<=DATE('".$data['filter_show_date']."')";
			}
			
		//	$sql .= " GROUP BY p.product_id"; 
				
			$sort_data = array(
					'pd.name',
					'p.model',
					'p.quantity',
					'p.price',
					'rating',
					'p.date_added'
			);
				
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY c.sort_order, psp.sort_order, p.featured DESC,p.sort_order,p.product_id";
				//sp.start_date, c.sort_order, p.featured DESC,p.sort_order,p.product_id
			}
			
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			//去掉分页2015v3.0.1
			/* 
			 if (isset($data['start'])>-1&&isset($data['limit'])&&$data['limit']>0) {
			 $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}****/
	
			$product_data = array();

			$query = $this->db->query($sql);
	
			$timenow=strtotime(date('Y-m-d', time()));
			foreach ($query->rows as $result) {
				if(!isset($productlist[$result['product_id']])){
					$productlist[$result['product_id']]=$this->getProduct($result['product_id'],$result['period_id']);
				}
				
				if(!isset($product_data['p'.$result['period_id']])){

					$result['ps_start_date']=strtotime($result['p_start_date'])-3600*24;
					$result['ps_end_date']=strtotime($result['p_end_date'])-3600*24;
					$product_data['p'.$result['period_id']]['name']=$result['sp_name'];
					$product_data['p'.$result['period_id']]['period_id']=$result['period_id'];
					$product_data['p'.$result['period_id']]['name2']=$result['sp_name2'];
					$product_data['p'.$result['period_id']]['onsale']=($timenow>=strtotime($result['ps_start_date']) && $timenow<=strtotime($result['ps_end_date']))? 1:0;
					$product_data['p'.$result['period_id']]['start_date']=$result['start_date'];
					$product_data['p'.$result['period_id']]['end_date']=$result['end_date'];
					$product_data['p'.$result['period_id']]['p_start_date']=$result['p_start_date'];
					$product_data['p'.$result['period_id']]['p_end_date']=$result['p_end_date'];
					$product_data['p'.$result['period_id']]['ps_start_date']=date('m/d',$result['ps_start_date']);
					$product_data['p'.$result['period_id']]['ps_end_date']  =date('m/d',$result['ps_end_date']);
				}
				
				if(!isset($product_data['p'.$result['period_id']]['cats']['c'.$result['category_id']]))
				{
					$product_data['p'.$result['period_id']]['cats']['c'.$result['category_id']]['name']=$result['cat_name'];
					$product_data['p'.$result['period_id']]['cats']['c'.$result['category_id']]['category_id']=$result['category_id'];
				}
				    $product_data['p'.$result['period_id']]['cats']['c'.$result['category_id']]['product_total']+=1;
			        $product_data['p'.$result['period_id']]['cats']['c'.$result['category_id']]['goods'][$result['product_id']] = $productlist[$result['product_id']];
			
			}
			
			if(is_object($this->mem)){
			   $this->mem->set($memNameSpace.'.'. $cache . '.' . $customer_group_id, $product_data,0,1800);
			   $this->log_db->debug('memcache:set:'.$this->mem->get_namespace('products').'.' . $cache . '.' . $customer_group_id);
			}
			else 
			{
			   $this->cache->set('product.' . $cache . '.' . $customer_group_id, $product_data);
			   $this->log_db->debug('cache:set:products.' . $cache . '.' . $customer_group_id);
			}
			
		}
		else
		{
				
			$this->log_db->debug('cache|memcache:get:'.$memNameSpace.'.' . $cache . '.' . $customer_group_id);
		}

		return $product_data;
	}
	
	/**
	 * 获取周期内的菜品
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getSupplyProducts($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$cache = md5(http_build_query($data));
		
	if(is_object($this->mem)){
		$memNameSpace=$this->mem->get_namespace('products');
		$product_data = $this->mem->get($memNameSpace.'.' . $cache . '.' . $customer_group_id);
		$this->log_db->debug('memcache:_get:'.$memNameSpace.'.' . $cache . '.' . $customer_group_id);
		}
		else 
		{
		$product_data = $this->cache->get('products.' . $cache . '.' . $customer_group_id);
		$this->log_db->debug('cache:_get:products.' . $cache . '.' . $customer_group_id);
		}
		
		if (!$product_data) {
			$sql = "SELECT p.product_id,sp.id as period_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) left join ".DB_PREFIX."product_supply_period psp on(psp.product_id=p.product_id) left join ".DB_PREFIX."supply_period sp on(sp.id=psp.period_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1'  AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
			
			$sql=$this->getFilterSql($sql,$data);
		
			$sql .= " GROUP BY p.product_id";
			
			$sort_data = array(
				'pd.name',
				'p.model',
				'p.quantity',
				'p.price',
				'rating',
				'p.date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY psp.sort_order, p.featured DESC,p.sort_order,p.product_id";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			//暂时去掉分页20150326 ajax分页加载时可以开启
			 if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
		
		
			$product_data = array();
			
			//print_r($sql.'<br>');
			
			$query = $this->db->query($sql);
		
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id'],$result['period_id']);
			}
			
			if(is_object($this->mem)){
				$this->mem->set($memNameSpace.'.' . $cache . '.' . $customer_group_id, $product_data,0,1800);
				$this->log_db->debug('memcache:set:'.$memNameSpace.'.' . $cache . '.' . $customer_group_id);
			}
			else
			{
				$this->cache->set('products.' . $cache . '.' . $customer_group_id, $product_data);
				$this->log_db->debug('cache:set:products.' . $cache . '.' . $customer_group_id);
			}
		}
		else
		{
			
			$this->log_db->debug('memcache|cache:get:products.'. $memNameSpace. $cache . '.' . $customer_group_id);
		}
	//print_r($sql);
		return $product_data;
	}
	
	
	

	public function getProducts($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$cache = md5(http_build_query($data));
		
		$product_data = $this->cache->get('product.' . $cache . '.' . $customer_group_id);
		$product_data =array();
		
		if (!$product_data) {
			$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
			
			$sql=$this->getFilterSql($sql,$data);
		
			$sql .= " GROUP BY p.product_id";
			
			$sort_data = array(
				'pd.name',
				'p.model',
				'p.quantity',
				'p.price',
				'rating',
				'p.sort_order',
				'p.date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				//$sql .= " ORDER BY p.sort_order";//apiv2
				$sql .= " ORDER BY p.featured DESC,p.sort_order";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			$sql .= ",p.product_id DESC";
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
		
			$product_data = array();
			
			$query = $this->db->query($sql);
		
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			$this->cache->set('product.' . $cache . '.' . $customer_group_id, $product_data);
		}
		
		return $product_data;
	}
	
	public function getOrderProducts($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
	
		$cache = md5(http_build_query($data));
		$order_flag=1;
		$product_data = $this->cache->get('product.' . $cache . '.' .$order_flag. $customer_group_id);
	
		if (!$product_data) {
			$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
				
			$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "order_product )";
				
			$sql .= " GROUP BY p.product_id";
				
			$sort_data = array(
					'pd.name',
					'p.model',
					'p.quantity',
					'p.price',
					'rating',
					'p.sort_order',
					'p.date_added'
			);
				
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY p.sort_order";
			}
				
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
	
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
					
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
				
			$product_data = array();
				
			$query = $this->db->query($sql);
	
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
				
			$this->cache->set('product.' . $cache . '.' .$order_flag. $customer_group_id, $product_data);
		}
	
		return $product_data;
	}
	
	public function getProductSpecials($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}				
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";
		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}	
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();
		
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}
		return $product_data;
	}
		
	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $limit);

		if (!$product_data) { 
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);
		 	 
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			$this->cache->set('product.latest.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $limit, $product_data);
		}
		
		return $product_data;
	}
	
	public function getPopularProducts($limit) {
		$product_data = array();
		
		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);
		
		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}
					 	 		
		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $limit);

		if (!$product_data) { 
			$product_data = array();
			
			$sql="SELECT op.product_id, COUNT(*) AS total FROM " . DB_PREFIX 
			. "order_product op LEFT JOIN `" . DB_PREFIX 
			. "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX 
			. "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX 
			. "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND o.order_status_id='".(int)$this->config->get('config_complete_status_id')."' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit;
			$query = $this->db->query($sql);
			
	/*apiv2
	$query = $this->db->query("SELECT op.product_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);
*/
			
			
			foreach ($query->rows as $result) { 		
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			$this->cache->set('product.bestseller.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $limit, $product_data);
		}
		
		return $product_data;
	}
	
	public function getProductAttributes($product_id) {
		$cache_data = $this->cache->get('attribute.product.'.$product_id );
		
		if(!$cache_data){
			$product_attribute_group_data = array();
			
			$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
			
			foreach ($product_attribute_group_query->rows as $product_attribute_group) {
				$product_attribute_data = array();
				
				$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order,a.attribute_id,ad.name");
				
				foreach ($product_attribute_query->rows as $product_attribute) {
					$product_attribute_data[] = array(
						'attribute_id' => $product_attribute['attribute_id'],
						'name'         => $product_attribute['name'],
						'text'         => $product_attribute['text']		 	
					);
				}
				
				$product_attribute_group_data[] = array(
					'attribute_group_id' => $product_attribute_group['attribute_group_id'],
					'name'               => $product_attribute_group['name'],
					'attribute'          => $product_attribute_data
				);			
			}
			
			if($product_attribute_group_data){
				$this->cache->set('attribute.product.'.$product_id,$product_attribute_group_data);
			}
			
			return $product_attribute_group_data;
		}else{
			return $cache_data;
		}
	}
			
	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");
		
		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'color' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'autocomplete') {
				$product_option_value_data = array();
			
				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pov.product_option_value_id,ov.sort_order");
				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'color_product_id'         => $product_option_value['color_product_id'],
						'name'                    => $product_option_value['name'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']			
					);
				}
									
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option_value_data,
					'required'          => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);				
			}
      	}
		
		return $product_option_data;
	}
	
	
	
	public function productPromotionBoughtNumber($promotion){
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		
		$sql="SELECT sum(quantity) as num FROM ".DB_PREFIX."order_product op LEFT JOIN ".DB_PREFIX."order o ON op.order_id=o.order_id WHERE o.customer_id='{$this->customer->getId()}'";
		
		
		$sql.=" AND o.order_status_id not in(11,10,7,0,14)";
		if($promotion)
			$sql.=" AND op.promotion_code='{$promotion['promotion_code']}'";
		
		$query = $this->db->query($sql);
		
		return (int)$query->row['num'];
		
	}
	
	public function getProductSpecial($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' ORDER BY quantity ASC, priority ASC, price ASC");
		
		return $query->rows;
	}
	
	public function getProductDiscounts($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;		
	}
		
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}
	
	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		foreach ($query->rows as $result) { 
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}
		
		return $product_data;
	}
		
	public function getProductTags($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_tag WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->rows;
	}
		
	
	public function getProductCoupons($product_id) {
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_coupon WHERE product_id = '" . (int)$product_id . "'");
//	
//		return $query->rows;
		$query = $this->db->query("SELECT p.product_id,p.coupon_num,p.is_tpl,c.* FROM " . DB_PREFIX . "product_coupon as p 
LEFT JOIN " . DB_PREFIX . "coupon as c ON p.coupon_id = c.coupon_id
				WHERE p.product_id = " . (int)$product_id);
		return $query->rows;
	}
	public function getProductTrans($product_id) {
		$query = $this->db->query("SELECT p.*,t.* FROM " . DB_PREFIX . "product_trans_code as p
				LEFT JOIN " . DB_PREFIX . "trans_code as t on p.trans_code_id =t.trans_id
				WHERE p.product_id  =" . (int)$product_id);
		return $query->rows;
	}
	//多商品ID查询---------------------------------
	
	public function get_coupons_by_pids($pids) {
		$id_str = '(' . implode(',', $pids) . ')';
		$query = $this->db->query("SELECT p.*,c.* FROM " . DB_PREFIX . "product_coupon as p 
LEFT JOIN " . DB_PREFIX . "coupon as c on p.coupon_id =c.coupon_id
				WHERE p.product_id in " . $id_str);
		return $query->rows;
	}

	public function get_trans_by_pids($pids) {
		$id_str = '(' . implode(',', $pids) . ')';
		$query = $this->db->query("SELECT p.*,t.* FROM " . DB_PREFIX . "product_trans_code as p
LEFT JOIN " . DB_PREFIX . "trans_code as t on p.trans_code_id =t.trans_id
				WHERE p.product_id in " . $id_str);
		return $query->rows;
	}
	
	/*apiv2*/	
	public function getTags(){
		$sql="SELECT * FROM " . DB_PREFIX . "product_tag WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return  $this->config->get('config_layout_product');
		}
	}
	
	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		return $query->rows;
	}	
		
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		//print_r($sql.'<br>');
		$sql=$this->getFilterSql($sql,$data);
		$query = $this->db->query($sql);
		
		return $query->row['total'];
		}
	
	public function getTotalSupplyProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) left join ".DB_PREFIX."product_supply_period psp on(psp.product_id=p.product_id) left join ".DB_PREFIX."supply_period sp on(sp.id=psp.period_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		$sql=$this->getFilterSql($sql,$data);
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	
	private function getFilterSql2($sql,$data=array()){
		if (isset($data['filter_category_id'])&&$data['filter_category_id']) {
			$sql .= " AND p.product_id IN (SELECT p2c.product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE p2c.category_id = '" . (int)$data['filter_category_id'] . "')";
		}
		return $sql;
	}
	
	private function getFilterSql($sql,$data=array()){
		if (isset($data['filter_name'])) {
			if (isset($data['filter_description']) && $data['filter_description']) {
				$sql .= " AND (LCASE(pd.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%' OR p.product_id IN (SELECT pt.product_id FROM " . DB_PREFIX . "product_tag pt WHERE pt.language_id = '" . (int)$this->config->get('config_language_id') . "' AND LCASE(pt.tag) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%') OR LCASE(pd.description) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%')";
			} else {
				$sql .= " AND (LCASE(pd.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%' OR p.product_id IN (SELECT pt.product_id FROM " . DB_PREFIX . "product_tag pt WHERE pt.language_id = '" . (int)$this->config->get('config_language_id') . "' AND LCASE(pt.tag) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'))";
			}
		}

		if (isset($data['filter_tag']) && $data['filter_tag']) {
			$sql .= " AND p.product_id IN (SELECT pt.product_id FROM " . DB_PREFIX . "product_tag pt WHERE pt.language_id = '" . (int)$this->config->get('config_language_id') . "' AND LCASE(pt.tag) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_tag'], 'UTF-8')) . "%')";
		}
									
		if (isset($data['filter_category_id']) && $data['filter_category_id']) {
		    // 促销活动特殊处理   #TBD
		    if($data['filter_category_id']== 1){
		        $sql .= " AND p.price< 15.1";
		    }
			elseif (isset($data['filter_sub_category']) && $data['filter_sub_category']) {
				$implode_data = array();
		
				$this->load->model('catalog/category');
				
				$categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);
			
				foreach ($categories as $category) {
					$implode_data[] = "p2c.category_id = '" . (int)$category. "'";
				}
								
				$sql .= " AND p.product_id IN (SELECT p2c.product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE " . implode(' OR ', $implode_data) . ")";			
			} else {
				$sql .= " AND p.product_id IN (SELECT p2c.product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE p2c.category_id = '" . (int)$data['filter_category_id'] . "')";
			}
		}
		
		if (isset($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		if (isset($data['filter_type']) && !is_null($data['filter_type'])) {
			$sql .= " AND p.prod_type = '" . (int)$data['filter_type'] . "'";
		}
		
		if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
			$sql .= " AND p.model LIKE '%" . $data['filter_model'] . "%'";
		}
		
		if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
			$sql .= " AND p.sku LIKE '%" . $data['filter_sku'] . "%'";
		}
		
		if (isset($data['filter_cas']) && !is_null($data['filter_cas'])) {
			$sql .= " AND p.cas LIKE '%" . $data['filter_cas'] . "%'";
		}
		
		if (isset($data['filter_mdl']) && !is_null($data['filter_mdl'])) {
			$sql .= " AND p.mdl LIKE '%" . $data['filter_mdl'] . "%'";
		}
		
		if (isset($data['filter_supply_date']) && !is_null($data['filter_supply_date'])) {
			$sql .= " AND  sp.start_date <=DATE('" . $data['filter_supply_date'] . "') and sp.end_date >=DATE('" . $data['filter_supply_date'] . "') ";
		}
		
		if (isset($data['filter_supply_period_id']) && !is_null($data['filter_supply_period_id'])) {
			$sql .= " AND  sp.id ='" . $data['filter_supply_period_id'] . "'";
		}
		
		if (isset($data['filter_start_date']) && !is_null($data['filter_start_date'])) {
			$sql .= " AND  sp.start_date =DATE('" . $data['filter_start_date'] . "')";
		}
		
		if (isset($data['filter_end_date']) && !is_null($data['filter_end_date'])) {
			$sql .= " AND  sp.end_date =DATE('" . $data['filter_end_date'] . "') ";
		}
	
		return $sql;
	}
			
	public function getTotalProductSpecials() {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		
		
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");
		
		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}	
	
	public function checkIfPurchased($product_id) {
	  if ($this->customer->isLogged()) {
		  $sql="SELECT COUNT(o.order_id) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON o.order_id =op.order_id
				WHERE customer_id='".$this->customer->getId()."'  AND op.product_id='".$product_id."' AND o.order_status_id='".$this->config->get('config_complete_status_id')."'";
		  $query = $this->db->query($sql);
		  
		  if (isset($query->row['total'])) {
		  	return $query->row['total'];
		  } else {
		  	return 0;
		  }
	  }else{
	  		return 0;
	  }
	}
	private function getFirstLetter($str){
		$fchar = ord($str{0});
		if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($str{0});
		$s1 = iconv("UTF-8","gb2312", $str);
		$s2 = iconv("gb2312","UTF-8", $s1);
		if($s2 == $str){
			$s = $s1;
		}
		else{$s = $str;
		}
		$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
		if($asc >= -20319 and $asc <= -20284) return "A";
		if($asc >= -20283 and $asc <= -19776) return "B";
		if($asc >= -19775 and $asc <= -19219) return "C";
		if($asc >= -19218 and $asc <= -18711) return "D";
		if($asc >= -18710 and $asc <= -18527) return "E";
		if($asc >= -18526 and $asc <= -18240) return "F";
		if($asc >= -18239 and $asc <= -17923) return "G";
		if($asc >= -17922 and $asc <= -17418) return "I";
		if($asc >= -17417 and $asc <= -16475) return "J";
		if($asc >= -16474 and $asc <= -16213) return "K";
		if($asc >= -16212 and $asc <= -15641) return "L";
		if($asc >= -15640 and $asc <= -15166) return "M";
		if($asc >= -15165 and $asc <= -14923) return "N";
		if($asc >= -14922 and $asc <= -14915) return "O";
		if($asc >= -14914 and $asc <= -14631) return "P";
		if($asc >= -14630 and $asc <= -14150) return "Q";
		if($asc >= -14149 and $asc <= -14091) return "R";
		if($asc >= -14090 and $asc <= -13319) return "S";
		if($asc >= -13318 and $asc <= -12839) return "T";
		if($asc >= -12838 and $asc <= -12557) return "W";
		if($asc >= -12556 and $asc <= -11848) return "X";
		if($asc >= -11847 and $asc <= -11056) return "Y";
		if($asc >= -11055 and $asc <= -10247) return "Z";
		return null;
	}
	 
	private function pinyin($zh){
		$ret = "";
		$s1 = iconv("UTF-8","gb2312", $zh);
		$s2 = iconv("gb2312","UTF-8", $s1);
		if($s2 == $zh){
			$zh = $s1;
		}
		for($i = 0; $i < strlen($zh); $i++){
			$s1 = substr($zh,$i,1);
			$p = ord($s1);
			if($p > 160){
				$s2 = substr($zh,$i++,2);
				$ret .= $this->getFirstLetter($s2);
			}else{
				$ret .= $s1;
			}
		}
		return $ret;
	}
	
	public function getProductOptionValue($product_option_value_id){
		$sql="SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id=".(int)$product_option_value_id;
		
		$query=$this->db->query($sql);
		
		return $query->row;
	}
	
	public function getFeaturedProducts($limit=5) {
		$sql="SELECT pd.name AS name, p.image,p.model,p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX 
		. "product_description pd ON (p.product_id = pd.product_id) WHERE p.featured='1' AND p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->config->get('config_language_id') 
		. "'";

		if($category_id){
			$sql.=" AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id=".(int)$category_id.")";
		}	
		
		$sql.=" ORDER BY p.sort_order DESC";
		
		$sql.=" LIMIT " . (int)$limit;
			
		$query = $this->db->query($sql);
			
		return $query->rows;
		

		
		
	}
	
	/*apiv2-start
	 public function getFeaturedProducts($limit=5){
	 $sql="SELECT product_id FROM " . DB_PREFIX . "product WHERE featured=1 ORDER BY product_id DESC limit $limit";
	
	 $query=$this->db->query($sql);
	
	 return $query->rows;
	 }
	 */
	
    public function getFeaturedProductIds($filter) {
	
		$sql="SELECT p.product_id FROM " . DB_PREFIX . "product p left join ".DB_PREFIX."product_supply_period psp on(psp.product_id=p.product_id) left join ".DB_PREFIX."supply_period sp on(sp.id=psp.period_id)  WHERE p.featured='1' AND p.status = '1' and sp.id='$filter[filter_supply_period_id]' AND p.date_available <= NOW()";
	
		$sql.=" ORDER BY p.sort_order DESC";
	
		$sql.=" LIMIT " . (int)$filter['limit'];
	
		$query = $this->db->query($sql);
	
		$ids=array();
	
		foreach($query->rows as $row){
			$ids[]=$row['product_id'];
		}
	
		return $ids;
	}
	
	public function ReportKeyword($keyword, $search_history_data){
        $sql = "INSERT INTO " . DB_PREFIX . "report_search SET term ='".$this->db->escape($keyword)."', time='".Date("d-M-Y G:i:s", $_SERVER["REQUEST_TIME"])."',result='" . $this->db->escape($search_history_data)."',ipadd='".$_SERVER["REMOTE_ADDR"]."'" ;

        $this->db->query($sql);
    }
    
	public function getProductGroups($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_combine pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		foreach ($query->rows as $result) { 
			$product_data[$result['combine_id']] = $this->getProduct($result['combine_id']);
		}
		
		return $product_data;
	}
	
	public function follow($product_id,$customer_id){
		$rows=$this->is_followed($product_id,$customer_id);

		if ($rows){
			
			if($rows[0]['follow']){	
		    //点赞逻辑不能减少赞，如需要减少可以打开下面逻辑
			//$sql  = "update ".DB_PREFIX."product_follow set follow=0 where product_id='".(int)$product_id."' AND customer_id = '$customer_id'";
			//$sql2 = "update ".DB_PREFIX."product set follow=follow-1 where product_id='".(int)$product_id."' AND follow>1";	
			}
			else
			{
			//$sql  = "update ".DB_PREFIX."product_follow set follow=1 where product_id='".(int)$product_id."' AND customer_id = '$customer_id'";
			//$this->db->query($sql);
			//$sql2 = "update ".DB_PREFIX."product set follow=follow+1 where product_id='".(int)$product_id."'";
			}
			//点赞逻辑，只加不减
			$sql  = "update ".DB_PREFIX."product_follow set follow=1 where product_id='".(int)$product_id."' AND customer_id = '$customer_id'";
			$sql2 = "update ".DB_PREFIX."product set follow=follow+1 where product_id='".(int)$product_id."'";
		}
		else
		{
			
			$sql   = "INSERT INTO ".DB_PREFIX."product_follow set follow=1,product_id='".(int)$product_id."',customer_id='".(int)$customer_id."',date_added=NOW()";
			$sql2  = "update ".DB_PREFIX."product set follow=follow+1 where product_id='".(int)$product_id."'";

		}		
		$this->db->query($sql);
		$this->db->query($sql2);

		$sql3="SELECT follow FROM ".DB_PREFIX."product WHERE product_id='$product_id'";
		$res=$this->db->query($sql3);	
		return $res->rows[0]['follow'];

	}
	
	public function is_followed($product_id,$customer_id){
		
		$customer_query = $this->db->query("SELECT follow FROM " . DB_PREFIX . "product_follow where product_id = '$product_id' AND customer_id = '$customer_id'");
	
		    if ($customer_query->num_rows>0){
				return $customer_query->rows;
			}
			else
			{	
				 false;
			}
		
	}



	public function getRelatedArticles($product_id) {
		$related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_product WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$related_data[] = $result['article_id'];
		}

		return $related_data;
	}
	
	public function getProductDownloads($product_id){
		$data=array();
		
		$sql="SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'";
		
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			$data[]=$result['download_id'];
		}
		
		
		return $data;
	}
	
	
	
	public function getPrevProduct($product_id){
		$sql="SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id < ".(int)$product_id." ORDER BY product_id DESC limit 1";
		
		$query=$this->db->query($sql);
		
		if($query->num_rows){
			return $query->row['product_id'];
		}else{
			return false;
		}
	}
	
	public function getNextProduct($product_id){
		$sql="SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id > ".(int)$product_id." ORDER BY product_id ASC limit 1";
		
		$query=$this->db->query($sql);
		
		if($query->num_rows){
			return $query->row['product_id'];
		}else{
			return false;
		}
	}
	/*apivw-end*/
}
?>
