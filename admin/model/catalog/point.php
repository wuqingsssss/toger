<?php 
class ModelCatalogPoint extends Model {
	
	public function existPoint($data,$point_id=0) {

		$sql="SELECT point_id FROM " . DB_PREFIX . "point WHERE 1=1";
		if($point_id)         $sql.=" AND point_id<>'".$point_id."'";
		if($data['name'])             $sql.=" AND name='".$data['name']."'";
		if($data['point_code'])       $sql.=" AND point_code='".$data['point_code']."'";
		if($data['point_code_new'])    $sql.=" AND point_code_new='".$data['point_code_new']."'";
	
		$query = $this->db->query($sql);

		if ($query->num_rows > 0) {
				
			return $query->row['point_id'];
		}
		else
		{
			return false;
		}
	
	}
		
	public function addPoint($data) {
		$sql="INSERT INTO " . DB_PREFIX . "point SET name = '" .$this->db->escape($data['name']) . "',address = '" .$this->db->escape($data['address']) 
		. "',business_hour = '" .$this->db->escape($data['business_hour']) . "',telephone = '" .$this->db->escape($data['telephone']) 
		. "',description = '" .$this->db->escape($data['description']) . "',status = '" .(int)$this->db->escape($data['status']) 
		. "',customer_group_id = '" .(int)$this->db->escape($data['customer_group_id'])
		. "',point_code = '" .$this->db->escape($data['point_code'])
            . "',point_code_new = '" .$this->db->escape($data['point_code_new'])
		. "',device_code = '" .$this->db->escape($data['device_code'])
		. "',image = '" .$this->db->escape($data['image'])
		. "',coordinate = '" .$this->db->escape($data['coordinate'])
		. "',cbd_id = '" .$this->db->escape($data['cbd_id'])
		. "',sort_order = '" .(int)$this->db->escape($data['sort_order']) . "'";
		
		$this->db->query($sql);
		
		$point_id = $this->db->getLastId();
		
		return $point_id;
	}
	
	
	
	public function editPoint($point_id, $data) {
		$sql="UPDATE " . DB_PREFIX . "point SET name = '" .$this->db->escape($data['name']) . "',address = '" .$this->db->escape($data['address']) 
		. "',business_hour = '" .$this->db->escape($data['business_hour']) . "',telephone = '" .$this->db->escape($data['telephone']) 
		. "',description = '" .$this->db->escape($data['description']) . "',status = '" .(int)$this->db->escape($data['status']) 
		. "',customer_group_id = '" .(int)$this->db->escape($data['customer_group_id'])
		. "',point_code = '" .$this->db->escape($data['point_code']) 
            . "',point_code_new = '" .$this->db->escape($data['point_code_new'])
		. "',device_code = '" .$this->db->escape($data['device_code'])
		. "',image = '" .$this->db->escape($data['image'])
		. "',coordinate = '" .$this->db->escape($data['coordinate'])
		. "',cbd_id = '" .$this->db->escape($data['cbd_id'])
		. "',sort_order = '" .(int)$this->db->escape($data['sort_order']) . "' WHERE point_id = '" . (int)$point_id . "'";
		
		$this->db->query($sql);
	}
	public function updatePoint($point_id, $data) {
		$sql="UPDATE " . DB_PREFIX . "point SET ";
		$first=true;
		foreach($data as $key=>$value){
			if(!$first){$sql.=",";}$first=false;
				$sql.="$key = '" .$this->db->escape($value) . "'";
		}
		$sql.=" WHERE point_id = '" . (int)$point_id . "'";	
		$this->db->query($sql);
	}
	
	public function deletePoint($point_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "point WHERE point_id = '" . (int)$point_id . "'");
		
		$this->deletePointToType($point_id);
	}
		
	public function getPoint($point_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "point WHERE point_id = '" . (int)$point_id . "'");
		
		return $query->row;
	}
	
	public function getPointId($point_code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "point WHERE point_code = '" . $point_code . "' or point_code_new='".$point_code."'");
	
		return $query->row;
	}
	/**
	 * 根据预设过滤获取有效自提点列表
	 * @param unknown $data
	 */
	public function getPoints($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "point WHERE 1=1 ";

		if(isset($data['filter_name']) && !is_null($data['filter_name'])){
			$sql.=" AND name like '%".$this->db->escape($data['filter_name'])."%' ";
		}
		if(!empty($data['filter_point_cbd_id'])){
		    $sql.=" AND cbd_id = '".$this->db->escape($data['filter_point_cbd_id'])."' ";
		}
		if(isset($data['filter_status'])){
		    $sql.=" AND `status` = '".$this->db->escape($data['filter_status'])."' ";
		}
		if(isset($data['filter_customer_group_id'])){
			$sql.=" AND `customer_group_id` = '".$this->db->escape($data['filter_customer_group_id'])."' ";
		}
	    if(!empty($data['filter_point_code_new'])){
		    $sql.=" AND point_code_new = '".$this->db->escape($data['filter_point_code_new'])."' ";
		}
		if(!empty($data['filter_point_code'])){
		    $sql.=" AND point_code = '".$this->db->escape($data['filter_point_code'])."' ";
		}
			
		$sort_data = array(
			
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY point_id";	
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

		$query = $this->db->query($sql);
		
		return $query->rows;
	}

    public function getFilterPoints($data= array()){
        $sql = "SELECT * FROM " . DB_PREFIX . "point";

        if(isset($data['filter_name']) && !is_null($data['filter_name'])){
            $sql.=" WHERE name like '%".$this->db->escape($data['filter_name'])."%' ";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

	
    /**
     *  获取检索条件下的记录数量
     * @param unknown $data
     */
	public function getTotalPoints($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "point WHERE 1=1 ";
		if(isset($data['filter_name']) && !is_null($data['filter_name'])){
			$sql.=" AND name like '%".$this->db->escape($data['filter_name'])."%' ";
		}
		if(!empty($data['filter_point_cbd_id'])){
		    $sql.=" AND cbd_id = '".$this->db->escape($data['filter_point_cbd_id'])."' ";
		}
		if(isset($data['filter_customer_group_id'])){
			$sql.=" AND `customer_group_id` = '".$this->db->escape($data['filter_customer_group_id'])."' ";
		}
		if(isset($data['filter_status'])){
		    $sql.=" AND `status` = '".$this->db->escape($data['filter_status'])."' ";
		}
		if(!empty($data['filter_point_code_new'])){
		    $sql.=" AND point_code_new = '".$this->db->escape($data['filter_point_code_new'])."' ";
		}
		if(!empty($data['filter_point_code'])){
		    $sql.=" AND point_code = '".$this->db->escape($data['filter_point_code'])."' ";
		}
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}	
}
?>