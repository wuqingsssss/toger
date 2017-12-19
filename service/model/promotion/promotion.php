<?php
class ModelPromotionPromotion extends Model {

	public function getPromotion($data){
		$sql = "SELECT pb.pb_id,pb.pb_name,pb.pb_key,pb.start_time,pb.end_time,pr.pr_id,pr.pr_code,pr.pr_rule,pd.* FROM ".DB_PREFIX."p_basic pb left join ".DB_PREFIX
		."p_rule pr ON (pb.pb_id=pr.pb_id) left join ".DB_PREFIX
		."p_description pd ON (pd.pb_id= pb.pb_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id')."'" ;
				
		 if($data['pb_id'])$sql.=" AND pb.pb_id ='".(int)$data['pb_id']."'";	
		 if($data['pb_key'])$sql.=" AND pb.pb_key ='".$data['pb_key']."'";
		$sql.=" AND (pb.start_time = '0000-00-00' OR pb.start_time <=NOW()) AND (pb.end_time = '0000-00-00' OR pb.end_time >= NOW())";

		$query=$this->db->query($sql);
		return $query->row;
	}
	
	/**
	 * 获取符合条件的促销活动总数
	 * @param unknown_type $filter
	 */
	public function getTotalPromotions($filter=array())
	{
		$sql = "SELECT count(*) AS total FROM ".DB_PREFIX."p_basic pb left join ".DB_PREFIX."p_description pd ON (pd.pb_id= pb.pb_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pb.status='0' ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	/**
	 * 获取符合条件的所有促销记录
	 * @param unknown_type $filter
	 */
	public function getPromotions($data=array())
	{
		$sql = "SELECT pb.pb_id,pr.pr_id,pr.pr_rule,pr.pr_code,pd.pb_name FROM ".DB_PREFIX."p_basic pb left join ".DB_PREFIX
		."p_rule pr ON (pb.pb_id=pr.pb_id) left join ".DB_PREFIX
		."p_description pd ON (pd.pb_id= pb.pb_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') 
		. "' AND pb.status='0' ";
		
		if(isset($data['filter_code']) && !is_null($data['filter_code'])){
			$sql.=" AND pr.pr_code='".$this->db->escape($data['filter_code'])."'";
		}
		
		if(isset($data['filter_datetime']) && !is_null($data['filter_datetime'])){
			$sql.=" AND pb.start_time <=NOW() AND pb.end_time >= NOW()";
		}

		$sort_data = array(
			'pb.pb_id',
		);
		$sql .= $this->initLimitOrder($data, $sort_data);

		$query = $this->db->query($sql);

		return $query->rows;

	}

	/**
	 * 获取促销信息
	 * @param unknown_type $data
	 */
	public function getPromotionInfo($data){
		if(isset($data['pb_id']))
		{
			$sql = "SELECT pb.pb_id,pb.pb_name,pb.start_time,pb.end_time,pr.pr_id,pr.pr_code,pr.pr_rule FROM ".DB_PREFIX."p_basic pb left join ".DB_PREFIX."p_rule pr ON (pb.pb_id=pr.pb_id) left join ".DB_PREFIX."p_description pd ON (pd.pb_id= pb.pb_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pb.pb_id ='".(int)$this->db->escape($data['pb_id'])."'";
			$sort_data = array(
				'pb.pb_id',
			);
			$sql .=$this->filterSql($data);
			$query = $this->db->query($sql);
			$result = $query->row;
			if ($query->num_rows) {
				$promotionInfo = array(
					'pb_id' =>$result['pb_id'],
					'pb_name' =>$result['pb_name'],
					'start_time' =>$result['start_time'],
					'end_time' =>$result['end_time'],
					'pr_id' =>$result['pr_id'],
					'pr_rule' =>$result['pr_rule'],
					'pr_code' =>$result['pr_code'],
				);
				return $promotionInfo;
			}
		}
		return false;
	}



	private function filterSql($filter)
	{
		$sql = "";
		
		if(isset($filter['pb_name'])){
			$sql .= " AND pb.pb_name LIKE  '%" . $this->db->escape(mb_strtolower($data['pb_name'], 'UTF-8'))."%' ";
		}
		return $sql;
	}


	/**
	 * 获取促销对象菜品
	 * @param unknown $promotion_code
	 */
	public function getPromotionProduct($promotion_code)
	{
	    $sql = "SELECT tpp.product_id,  tpd.name, tpp.price, tpb.pb_id, tpb.pb_name, tpp.buy_quantity FROM ts_pr_to_product tpp, ts_p_rule tpr, ts_p_basic tpb, ts_product_description tpd WHERE 
	           tpr.pr_code ='{$promotion_code}' AND 
	           tpr.pr_id   = tpb.pr_id AND
	           tpp.pr_id   = tpr.pr_id AND 
	           tpp.product_id = tpd.product_id AND
	           NOW() >= tpp.start_date AND
	           NOW() <= tpp.end_date  AND
	           NOW() >= tpb.start_time AND
	           NOW() <= tpb.end_time";
	    
	    $result = $this->db->query($sql);   
	    
	    return $result->rows;
	}
	
	/**
	 * 获取促销方式
	 * @param unknown $promotion_code
	 */
	public function getPromotionRule($promotion_code)
	{
	    $sql = "SELECT tpb.pb_name, tpb.total,tpb.total,tpb.pr_id FROM ts_p_basic tpb, ts_p_rule tpr WHERE 
                tpr.pr_id = tpb.pr_id AND 
                tpr.pr_code = '{$promotion_code}' AND 
                NOW()>= tpb.start_time AND 
                NOW()<= tpb.end_time
                LIMIT 1";

	    $result = $this->db->query($sql);
	     
	    return $result->row;
	}
	
	/**
	 * 获取促销对象菜品价钱
	 * @param unknown $promotion_code
	 */
	public function getPromotionProductPrice($promotion_code, $product_id)
	{
	    $sql = "SELECT tpp.product_id, tpp.price, tpb.pb_name FROM ts_pr_to_product tpp, ts_p_rule tpr, ts_p_basic tpb, ts_product_description tpd WHERE
	    tpr.pr_code ='{$promotion_code}' AND
	    tpp.product_id ='{$product_id}' AND
	    tpr.pr_id   = tpb.pr_id AND
	    tpp.pr_id   = tpr.pr_id AND
	    tpp.product_id = tpd.product_id AND
	    NOW() >= tpp.start_date AND
	    NOW() <= tpp.end_date  AND
	    NOW() >= tpb.start_time AND
	    NOW() <= tpb.end_time 
	    LIMIT 1";
	     
	    $result = $this->db->query($sql);
	     
	    return $result->row;
	}
	public function getProducts($pb_id) {
		$sql = "SELECT p.product_id AS upid, p.*,pd.*,pr.pr_rule,ptp.pr_id,pr.pr_code,pr.pr_group,pr.pr_banner FROM " . DB_PREFIX . "pr_to_product ptp"
				." LEFT JOIN ".DB_PREFIX."product p on ( ptp.product_id=p.product_id) "
			    ." LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)"
	            ." LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id)"
	            ." LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id)"
			    ." WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"
			    	." AND pb.status='0' AND pb.pb_id='".(int)$pb_id."' AND pb.start_time<=NOW() AND pb.end_time>=now() AND (ptp.start_date<=NOW() OR ptp.start_date='0000-00-00 00:00:00') AND (ptp.end_date>=NOW() OR ptp.end_date='0000-00-00 00:00:00')";
				$sql .= " ORDER BY pr.sort_order ASC, ptp.sort_order ASC,p.product_id desc";
			    $query = $this->db->query($sql);
			return $query->rows;	
	}
	
	/**
	 * 获取促销产品关系信息
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getPromotionProductInfo($data)
	{
		if(isset($data['product_id'])&&isset($data['pr_id'])&&!is_null($data['product_id'])&&!is_null($data['pr_id']))
		{
			$sql = "SELECT ptp.*,pr.pr_rule,pr.pr_code,pr.pr_group,pr.pr_banner FROM ".DB_PREFIX."pr_to_product ptp"
					." LEFT JOIN ".DB_PREFIX."p_rule pr ON ptp.pr_id=pr.pr_id  WHERE ptp.product_id='".(int)$data['product_id']."' AND ptp.pr_id ='".(int)$data['pr_id']."'";
			$query  = $this->db->query($sql);
			return $query->row;
		}
		return false;
	
	}
	
	public function getProduct($product_id,$pr_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		$this->load->model ( 'catalog/product' );
		$product_data = $this->model_catalog_product->getProduct ($product_id ,$this->cart->sequence);
		
		  if($product_data){
			$data = array(
					'pr_id' =>$pr_id,
					'product_id'=>$product_id,
			);
			$promotionProductInfo = $this->getPromotionProductInfo($data);

			if($promotionProductInfo)
			{if($promotionProductInfo['pr_code']&&false){
				$promotion = array(
						'limited'          =>$promotionProductInfo['limited'],
						'promotion_price'  =>$promotionProductInfo['promotion_price'],
						'promotion_code'   =>$promotionProductInfo['pr_code']
				);
				$product_data['promotion'] =$promotion;}
				if($promotionProductInfo['use_quantity'] >$product_data['quantity'])
				$product_data['quantity']  =$promotionProductInfo['use_quantity'];
			}
	
			return $product_data;
		} else {
			return false;
		}
	}
}
?>