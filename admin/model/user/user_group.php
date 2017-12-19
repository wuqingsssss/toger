<?php
class ModelUserUserGroup extends Model {

	public function addUserGroup($data) {
		
		$sql = "INSERT INTO " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? serialize($data['permission']) : '') . "'";
		if(isset($data['parent_group_id']) && $data['parent_group_id'] != ''){
			$sql .= ",parent_group_id = " .intval($data['parent_group_id']);
		}
		$this->db->query($sql);
		
		$user_group_id=$this->db->getLastId();
		
		if(isset($data['user_right'])){
			$this->editUserGroupRight($user_group_id,$data['user_right']);
		}
	}
	
	public function editUserGroup($user_group_id, $data) {
		$sql =  "UPDATE " . DB_PREFIX . "user_group SET  name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? serialize($data['permission']) : '')."'" ;
		if(isset($data['parent_group_id']) && $data['parent_group_id'] != ''){
			$sql .= ",parent_group_id = " .intval($data['parent_group_id']);
		}
		$sql .= " WHERE user_group_id = '" . (int)$user_group_id . "'";
		$this->db->query($sql);

		if(isset($data['user_right'])){
			$this->editUserGroupRight($user_group_id,$data['user_right']);
		}
	}
	
	public function deleteUserGroup($user_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function addPermission($user_id, $type, $page) {
		$user_query = $this->db->query("SELECT DISTINCT user_group_id FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");
		
		if ($user_query->num_rows) {
			$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
		
			if ($user_group_query->num_rows) {
				$data = unserialize($user_group_query->row['permission']);
		
				$data[$type][] = $page;
		
				$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . serialize($data) . "' WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
			}
		}
	}
	
	public function getUserGroupDeep(){
		$g_info = $this->model_user_user_group->getUserGroup($this->getUserGroup['user_group_id']);
		
	}
	
	public function getUserGroup($user_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
		
		$user_group = array(
			'name'       => $query->row['name'],
			'user_group_id' =>$query->row['user_group_id'],
			'parent_group_id' =>$query->row['parent_group_id'],
			'user_right'       => $query->row['user_right'],
			'permission' => unserialize($query->row['permission'])
		);

		if($query->row['parent_group_id']){
			
			$user_group['parent']=$this->getUserGroup($query->row['parent_group_id']);
			$user_group['fullname']='——'.$user_group['parent']['fullname'].'-'.$user_group['name'];
			
		}else{
			
			$user_group['fullname']=$user_group['name'];
		}

		return $user_group;
	}
	
	public function getUserGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "user_group";
		if (isset($data['gid']) && ($data['gid'] != 1)) {
			$sql .= " where user_group_id = {$data['gid']} or parent_group_id = {$data['gid']} ";
		}
		$sql .= " ORDER BY name";	
			
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
	
	public function getTotalUserGroups() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");
		
		return $query->row['total'];
	}	
	
	private function editUserGroupRight($user_group_id,$user_right){
		$this->db->query("UPDATE  " . DB_PREFIX . "user_group set user_right=".(int)$user_right." WHERE user_group_id=".(int)$user_group_id);
	}
	/**
	 * 根据
	 * @param type $group_id
	 */
	public function get_fist_goup($group_id = 0){
		$sql = "select * from " . DB_PREFIX . "user_group where parent_group_id = {$group_id}";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function get_right_list($gid, $is_super = 0){
		$sql = "select * from " . DB_PREFIX . "user_group ";
		if(!$is_super){
			$sql .= "where user_group_id ={$gid} or parent_group_id = {$gid}";
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function add_sub_group($data){
		$sql = "insert ".DB_PREFIX."user_group SET  name = '{$this->db->escape($data['name'])}' ,"
				. "permission = '".(isset($data['permission']) ? serialize($data['permission']) : '')."' ,"
				. "parent_group_id = ".intval($data['pid']);
		$this->db->query($sql);
		if(isset($data['user_right'])){
			$this->editUserGroupRight($user_group_id,$data['user_right']);
		}
	}
}
?>