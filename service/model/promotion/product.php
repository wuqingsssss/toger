<?php
class ModelPromotionProduct extends Model {
	public function getProducts($data=array()){
		$sql="SELECT p.product_id FROM " . DB_PREFIX. "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)"
		." LEFT JOIN " . DB_PREFIX . "pr_to_product prp  ON p.product_id=prp.product_id"
		." LEFT JOIN " . DB_PREFIX . "p_rule pr  ON pr.pr_id=prp.pr_id";

		$sql.=" WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if(isset($data['filter_promotion_type']) && !is_null($data['filter_promotion_type'])){
			$sql.=" AND LCASE(pr.pr_code)='".$this->db->escape(strtolower($data['filter_promotion_type']))."'";
		}
		$query=$this->db->query($sql);
		$ids=array_column($query->rows,'product_id');
		return $ids;
	}
	
	/**
	 * 获取促销产品关系信息
	 * Enter description here ...
	 * @param unknown_type $data
	
	public function getPromotionProductInfo($data)
	{
		if(isset($data['product_id'])&&isset($data['pr_id'])&&!is_null($data['product_id'])&&!is_null($data['pr_id']))
		{
			$sql = "SELECT * FROM ".DB_PREFIX."pr_to_product  WHERE product_id='".(int)$data['product_id']."' AND pr_id ='".(int)$data['pr_id']."'";
			$query  = $this->db->query($sql);
			return $query->row;
		}
		return false;
		
	}

	*/
}
?>