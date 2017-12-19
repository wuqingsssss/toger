<?php
class ModelCatalogVote extends Model {
	//vote user info
	public function insertVoteUserInfo($data)
	{
		$sql = "INSERT INTO ".DB_PREFIX . "product_vote_info SET product_id = '".$this->db->escape($data['product_id'])."'"
		." , vote_user_id = '".$this->db->escape($data['vote_user_id'])."'"
		." , vote_user_ip = '".$this->db->escape($data['vote_user_ip'])."'"
		." , vote_user_mac = '".$this->db->escape($data['vote_user_mac'])."'"
		." , vote_num = '".$this->db->escape($data['vote_num'])."'"
		." , date_added = NOW()"
		;
		$this->db->query($sql);

		return $this->getTotalVoteCounts($data);
	}

	public function getTotalVoteCounts($data)
	{
		$sql = "select count(*) as total from  ".DB_PREFIX . "product_vote_info where product_id = '".$this->db->escape($data['product_id'])."'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function checkHasVoted($data)
	{
		$sql = "SELECT count(*) AS total FROM ".DB_PREFIX . "product_vote_info where  product_id= '".$this->db->escape($data['product_id'])."'";

		if(isset($data['vote_user_id']))
		{
			$sql .= " AND vote_user_id = '".$this->db->escape($data['vote_user_id'])."'";
		}
		else{
			if(isset($data['vote_user_ip']))
			{
				$sql .= " AND vote_user_ip = '".$this->db->escape($data['vote_user_ip'])."'";
			}
			if(isset($data['vote_user_mac']))
			{
				$sql .= " AND vote_user_mac = '".$this->db->escape($data['vote_user_mac'])."'";
			}
		}

		$query = $this->db->query($sql);
		
		if (isset($query->row['total'])&&((int)$query->row['total'])>0) {
			return true;
		} else {
			return false;
		}
	}



	//vote
	public function updateProductVote($data) {
		$sql = "UPDATE " . DB_PREFIX . "product SET voted_good_num = '".$this->db->escape($data['voted_good_num'])."' WHERE product_id='".(int)$this->db->escape($data['product_id'])."'";
		$this->db->query($sql);
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
	
	public function getProductVoteNum($product_id){
		$sql="SELECT COUNT(*) AS total FROM ".DB_PREFIX."product_vote_info pvi2 WHERE pvi2.product_id=".(int)$product_id;
		
		$query=$this->db->query($sql);
		
		if($query->row){
			return $query->row['total'];
		}else{
			return 0;
		}
	}

	public function getVoteProductIds($data) {
		$sql="SELECT p.product_id, (select count(*) from ".DB_PREFIX."product_vote_info pvi2 where pvi2.product_id=p.product_id) as voted_good_num  FROM " . DB_PREFIX . "product p WHERE  p.status = '0' AND p.date_available <= NOW()";

		$sort_data = array(
				'voted_good_num',
				'p.product_id',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.product_id";
		}
			
			
		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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
			
		$query = $this->db->query($sql);

		$ids=array();

		foreach($query->rows as $row){
			$ids[]=$row['product_id'];
		}

		return $ids;
	}

	public function getTotalVoteProduct($data) {
		$sql="SELECT count(p.product_id) as total, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"." AND p.status = '0' AND p.date_available <= NOW()";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}


	private function getFilterSql($sql,$data=array()){
		if (isset($data['start'])) {
			if (isset($data['limit']) && $data['limit']) {
				$sql.=" LIMIT " . (int)$data['start'] . ",". (int)$data['limit'];
			}
		}
		return $sql;
	}


}
?>