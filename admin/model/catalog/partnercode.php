<?php 
class ModelCatalogPartnerCode extends Model {
	
	public function get_platform_list(){
		$sql="SELECT * FROM " . DB_PREFIX ."partner_code WHERE 1=1 order by sort_order , id";
		
		$query = $this->db->query($sql);

		if ($query->num_rows > 0) {
			return $query->rows;
		} else {
			return false;
		}
	}
	
	public function get_platform_info($id){
		if(empty($id)){
			return false;
		}
		$pre = DB_PREFIX;
		$sql = "select * from {$pre}partner_code where id = {$id}";
		$query = $this->db->query($sql);
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}
	/**
	 * 新增平台信息记录
	 * @param type $data
	 */
	public function insert_info($data){
		$this->db->query("INSERT INTO " . DB_PREFIX . "partner_code SET "
			. "code = '{$data['code']}' , name = '{$this->db->escape($data['name'])}' , `key` = '{$data['key']}' "
			. ", point_code = '{$data['point_code']}' , sort_order = '{$data['sort_order']}' , `status` = '{$data['status']}'");
			return  $this->db->getLastId(); 
	}
	/**
	 * 更新平台信息记录
	 * @param type $id
	 * @param type $data
	 */
	public function update_info($id, $data){
		$this->db->query("UPDATE " . DB_PREFIX . "partner_code "
			. "SET name = '{$data['name']}' , `key` = '{$data['key']}' , "
			. "point_code = '{$data['point_code']}' , sort_order = '{$data['sort_order']}' , `status` = '{$data['status']}' "
			. "where id = {$id}");
		
		return $this->db->countAffected();
	}
	
	public function  getAllPartners(){
		$arr = $this->get_platform_list();
		foreach($arr as $val){
			$list[$val['code']] = $val['name'];
		}
		return $list;
	}

	public function getPartnerInfo($code){
		if(empty($code)){
			return '内站';
		}
		$arr = $this->getAllPartners();
		return $arr[$code];
	}
}