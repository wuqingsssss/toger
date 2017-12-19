<?php 
class ModelCatalogPointDelivery extends Model {
	
	public function existDelivery($data,$delivery_id=0) {

		$sql="SELECT delivery_id FROM " . DB_PREFIX ."Point_Delivery WHERE 1=1";
		if($delivery_id)         $sql.=" AND delivery_id<>'".$delivery_id."'";
		if($data['name'])             $sql.=" AND name='".$data['name']."'";
		if($data['code'])             $sql.=" AND name='".$data['code']."'";
		if($data['region_name'])       $sql.=" AND region_name='".$data['region_name']."'";
		if($data['p_delivery_id'])       $sql.=" AND p_delivery_id='".$data['p_delivery_id']."'";
		if($data['zone_name'])    $sql.=" AND zone_name='".$data['zone_name']."'";
	
		$query = $this->db->query($sql);

		if ($query->num_rows > 0) {
				
			return $query->row['delivery_id'];
		}
		else
		{
			return false;
		}
	
	}
		
	public function addDelivery($data) {
		$sql="INSERT INTO " . DB_PREFIX ."Point_Delivery SET zone_name = '" .$this->db->escape($data['zone_name']) . "',
				zone_id = '" .$this->db->escape($data['zone_id']) . "',
				p_delivery_id = '" .$this->db->escape($data['p_delivery_id']) . "',
				name = '" .$this->db->escape($data['name']) . "',
				region_name = '" .$this->db->escape($data['region_name']) . "',
							region_id = '" .$this->db->escape($data['region_id']) . "',
									region_code = '" .$this->db->escape($data['region_code']) . "',
				address = '" .$this->db->escape($data['address']) 
		. "',telephone = '" .$this->db->escape($data['telephone']) 
		. "',region_coord = '" .$this->db->escape($data['region_coord']) 
		. "',business_hour = '" .$this->db->escape($data['business_hour'])
		. "',poi = '" .$this->db->escape($data['poi'])
		. "',poihash = '" .$this->db->escape($data['poihash'])
		. "',smodel = '" .(int)$this->db->escape($data['smodel'])
		. "',status = '" .(int)$this->db->escape($data['status']) 
		. "',code = '" .$this->db->escape($data['code'])
		. "',sort_order = '" .(int)$this->db->escape($data['sort_order']) . "'";
		
		$this->db->query($sql);
		
		$delivery_id = $this->db->getLastId();
		
		if($this->mem){
			$this->mem->delete($this->db->escape($data['code']).'_shipping_allowarea');
			$this->log_db->debug('mem->delete:'.$this->db->escape($data['code']).'_shipping_allowarea'.serialize($res));
			$res=$this->mem->delete($this->db->escape($data['code']).'Region');
				
			$this->log_db->debug('mem->delete:'.$this->db->escape($data['code']).'Region'.serialize($res));
		
		}

		return $delivery_id;
	}
	
	
	
	public function editDelivery($delivery_id, $data) {
		$sql="UPDATE " . DB_PREFIX ."Point_Delivery SET zone_name = '" .$this->db->escape($data['zone_name']) . "',
				zone_id = '" .$this->db->escape($data['zone_id']) . "',
				p_delivery_id = '" .$this->db->escape($data['p_delivery_id']) . "',
				name = '" .$this->db->escape($data['name']) . "',
				region_name = '" .$this->db->escape($data['region_name']) . "',
				region_id = '" .$this->db->escape($data['region_id']) . "',
				region_code = '" .$this->db->escape($data['region_code']) . "',
				address = '" .$this->db->escape($data['address']) 
		. "',telephone = '" .$this->db->escape($data['telephone']) 
		. "',region_coord = '" .$this->db->escape($data['region_coord']) 
		. "',business_hour = '" .$this->db->escape($data['business_hour'])
		. "',poi = '" .$this->db->escape($data['poi'])
		. "',poihash = '" .$this->db->escape($data['poihash'])
		. "',smodel = '" .(int)$this->db->escape($data['smodel'])
		. "',status = '" .(int)$this->db->escape($data['status']) 
		. "',code = '" .$this->db->escape($data['code'])
		. "',sort_order = '" .(int)$this->db->escape($data['sort_order']) . "' WHERE delivery_id = '" . (int)$delivery_id . "'";
		
		$this->db->query($sql);
		
		if($this->mem){
			$res=$this->mem->delete($this->db->escape($data['code']).'_shipping_allowarea');

			$this->log_db->debug('mem->delete:'.$this->db->escape($data['code']).'_shipping_allowarea'.serialize($res));
		
			$res=$this->mem->delete($this->db->escape($data['code']).'Region');
			
			$this->log_db->debug('mem->delete:'.$this->db->escape($data['code']).'Region'.serialize($res));
			
		}
	}
	public function updateDelivery($delivery_id, $data) {
		$sql="UPDATE " . DB_PREFIX ."Point_Delivery SET ";
		$first=true;
		foreach($data as $key=>$value){
			if(!$first){$sql.=",";}$first=false;
				$sql.="$key = '" .$this->db->escape($value) . "'";
		}
		if(is_array($delivery_id))
		{
			
			$sql.=" WHERE delivery_id in(" . implode(',', $delivery_id) . ")";	
			$this->db->query($sql);
			if($this->mem){	
				
				//$deliverys = EnumDelivery::getAllDelivery ();
				$deliverys=$this->config->get('delivery_express');

			foreach ( $deliverys as $key => $item ) {
				$res=$this->mem->delete($this->db->escape($item['code']).'_shipping_allowarea');
				$this->log_db->debug('mem->delete:'.$this->db->escape( $item['code']).'_shipping_allowarea'.serialize($res));
			}
			}

		}
		else
		{
			$sql.=" WHERE delivery_id = '" . (int)$delivery_id . "'";	
		
		$this->db->query($sql);
		if($this->mem){
			
			$data=$this->getDelivery($delivery_id);
			$res=$this->mem->delete($this->db->escape($data['code']).'_shipping_allowarea');

			$this->log_db->debug('mem->delete:'.$this->db->escape($data['code']).'_shipping_allowarea'.serialize($res));
		
			$res=$this->mem->delete($this->db->escape($data['code']).'Region');
				
			$this->log_db->debug('mem->delete:'.$this->db->escape($data['code']).'Region'.serialize($res));
		}
		}
	}
	
	public function deleteDelivery($delivery_id) {
		
		$data=$this->getDeliveryId($delivery_id);
		
		$this->db->query("DELETE FROM " . DB_PREFIX ."Point_Delivery WHERE delivery_id = '" . (int)$delivery_id . "'");
	
		if($this->mem){
			$res=$this->mem->delete($this->db->escape($data['code']).'_shipping_allowarea');
			$this->log_db->debug('mem->delete:'.$this->db->escape($data['code']).'_shipping_allowarea'.serialize($res));
		}
	}
		
	public function getDelivery($delivery_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."Point_Delivery WHERE delivery_id = '" . (int)$delivery_id . "'");
		
		return $query->row;
	}
	
	public function getDeliveryId($region_name) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."Point_Delivery WHERE region_name = '" . $region_name . "'");
		return $query->row;
	}
	/**
	 * 根据预设过滤获取有效自提点列表
	 * @param unknown $data
	 */
	public function getDeliverys($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX ."Point_Delivery WHERE 1=1 ";

		if(isset($data['filter_p_delivery_id']) && !is_null($data['filter_p_delivery_id'])){
			$sql.=" AND p_delivery_id = '".$this->db->escape($data['filter_p_delivery_id'])."' ";
		}
		
		if(isset($data['filter_zone_id']) && !is_null($data['filter_zone_id'])){
			$sql.=" AND zone_id = '".$this->db->escape($data['filter_zone_id'])."' ";
		}
		
		if(isset($data['filter_status'])&& !is_null($data['filter_status'])){
			$sql.=" AND `status` = '".$this->db->escape($data['filter_status'])."' ";
		}
		
		if(!empty($data['filter_name'])){
			$sql.=" AND name like '%".$this->db->escape($data['filter_name'])."%' ";
		}
		
		if(!empty($data['filter_region_name'])){
		    $sql.=" AND region_name like '%".$this->db->escape($data['filter_region_name'])."%' ";
		}
		
		if(!empty($data['filter_telephone'])){
			$sql.=" AND telephone like '%".$this->db->escape($data['filter_telephone'])."%' ";
		}
		
		if(!empty($data['filter_address'])){
			$sql.=" AND address like '%".$this->db->escape($data['filter_address'])."%' ";
		}

		if(!empty($data['filter_zone_name'])){
			$sql.=" AND zone_name like '%".$this->db->escape($data['filter_zone_name'])."%' ";
		}

		if(!empty($data['filter_code'])){
			$sql.=" AND code = '".$this->db->escape($data['filter_code'])."' ";
		}
		
		$sort_data = array(
			
		);	
		
		$sql .= " ORDER BY p_delivery_id ASC," ;
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " " . $data['sort'];	
		} else {
			$sql .= " delivery_id";	
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

    public function getFilterDeliverys($data= array()){
        $sql = "SELECT * FROM " . DB_PREFIX ."Point_Delivery";

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
	public function getTotalDeliverys($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX ."Point_Delivery WHERE 1=1 ";
		if(isset($data['filter_name']) && !is_null($data['filter_name'])){
			$sql.=" AND name like '%".$this->db->escape($data['filter_name'])."%' ";
		}
		if(!empty($data['filter_region_name'])){
		    $sql.=" AND region_name = '".$this->db->escape($data['filter_region_name'])."' ";
		}
		if(!empty($data['filter_address'])){
			$sql.=" AND address = '".$this->db->escape($data['filter_address'])."' ";
		}
		if(isset($data['filter_status'])){
		    $sql.=" AND `status` = '".$this->db->escape($data['filter_status'])."' ";
		}
	    if(!empty($data['filter_zone_name'])){
		    $sql.=" AND zone_name = '".$this->db->escape($data['filter_zone_name'])."' ";
		}
		if(!empty($data['filter_code'])){
		    $sql.=" AND code = '".$this->db->escape($data['filter_code'])."' ";
		}
			
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}	
}
?>