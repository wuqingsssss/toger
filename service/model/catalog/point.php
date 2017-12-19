<?php
class ModelCatalogPoint extends Model {
	
	/*v1获取point——id*/
	public function getPointByDeviceCode($device_code){
		$sql ="select * from ".DB_PREFIX."point p where p.device_code='".$device_code."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	/*v1获取point——id,兼容新point——code*/
	public function getPointByCode($point_code){
		$sql ="select p.*,cbd.city_id,cbd.name as cbd_name,cbd.country_id,cbd.zone_id from ".DB_PREFIX."point p 
				Left join ".DB_PREFIX."cbd cbd ON p.cbd_id=cbd.id 
				where p.point_code='".$point_code."' or point_code_new='".$point_code."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function updatePointByCode($point_code, $data) {
		$sql="UPDATE " . DB_PREFIX . "point SET ";
		$first=true;
		foreach($data as $key=>$value){
			if(!$first){$sql.=",";}$first=false;
			
			$sql.="$key = '" .$this->db->escape($value) . "'";
		}
		$sql.=" WHERE point_code_new = '" . $point_code . "'";
		$this->db->query($sql);
	}
	
	public function getPoint($point_id){
		$sql="SELECT * FROM " . DB_PREFIX . "point WHERE point_id=".(int)$point_id;
		
		$query=$this->db->query($sql);
		
		return $query->row;
	}
	/*apiv2*/
	private function filterSql ($data){
		$sql = "";
		if(isset($data['filter_status'])&&!empty($data['filter_status'])){
			$sql .= " AND status='".(int)$data['filter_status']."'";
		}

		if(isset($data['filter_point_cbd_id']) && !is_null($data['filter_point_cbd_id'])){
			$sql .=" AND cbd_id=".(int)$data['filter_point_cbd_id'];
		}
		
	   if(isset($data['filter_point_cbd_id']) && !is_null($data['filter_point_cbd_id'])){
			$sql.=" AND cbd_id=".(int)$data['filter_point_cbd_id'];
			}
			
			
		if(isset($data['filter_point_code']) && !is_null($data['filter_point_code'])){
				$sql.=" AND point_code='".$data['filter_point_code']."'";
		}
		if(isset($data['filter_point_code_new']) && !is_null($data['filter_point_code_new'])){
			$sql.=" AND point_code_new='".$data['filter_point_code_new']."'";
		}
		//if($point_type){
//			$sql.="point_id IN (SELECT point_id FROM " . DB_PREFIX . "point_to_type WHERE type_id=".(int)$point_type.")";
//		}

		$sql.=" AND status=1";
		
		
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
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
		
		return $sql;
	}
	
	public function getPoints($data=array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "point where 1=1 ";
	$sql .= $this->filterSql($data);

		$query=$this->db->query($sql);
		
		return $query->rows;
	}
	/*apiv2*/
	public function getTotalPoints($data=array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "point where 1=1 ";
		$sql .= $this->filterSql($data);
		
      	$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	
	
	public function getAllCbd(){
		$sql = "SELECT cbd.id as cbd_id,cbd.name as cbd_name,city.city_id,city.name as city_name,city.code as city_code,z.code as zone_code,z.zone_id,z.name as zone_name FROM " . DB_PREFIX . "cbd cbd "
				." LEFT JOIN " . DB_PREFIX . "city city on city.city_id=cbd.city_id"
				." LEFT JOIN " . DB_PREFIX . "zone z on z.zone_id=cbd.zone_id"
				." where cbd.status=1 ";


		$query=$this->db->query($sql);
		
		
		
		return $query->rows;
	}

}