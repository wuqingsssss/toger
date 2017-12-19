<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', password = '" . $this->db->escape(md5($data['password'])) . "', firstname = '" . $this->db->escape($data['firstname']) . "', email = '" . $this->db->escape($data['email']) . "', is_admin = '" . (int)$data['is_admin'] . "', user_group_id = '" . (int)$data['sub_id'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}
	
	public function editUser($user_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', firstname = '" . $this->db->escape($data['firstname']) . "',  email = '" . $this->db->escape($data['email']) . "', user_group_id = '" . (int)$data['sub_id'] . "', is_admin = '" . (int)$data['is_admin']. "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");
		
		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}

	public function editPassword($user_id, $password) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape(md5($password)) . "' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editCode($email, $code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE email = '" . $this->db->escape($email) . "'");
	}
			
	public function deleteUser($user_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
	}
	
	public function getUser($user_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
	
		return $query->row;
	}
	
	public function getUserByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");
	
		return $query->row;
	}
		
	public function getUsers($data = array()) {
		
		$sql = "SELECT u.*,ug.parent_group_id FROM `" . DB_PREFIX . "user` as u LEFT JOIN `" . DB_PREFIX . "user_group` as ug ON u.user_group_id = ug.user_group_id ";
		
		if (isset($data['user_group_id'])) {
			$sql .= " WHERE (u.user_group_id='{$data[user_group_id]}' OR ug.parent_group_id='{$data[user_group_id]}') ";
		}
		
		$sort_data = array(
			'username',
			'status',
			'date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY CONCAT(ug.parent_group_id,ug.user_group_id),". $data['sort'];	
		} else {
			$sql .= " ORDER BY CONCAT(ug.parent_group_id,ug.user_group_id),username";	
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

	public function getAllUsersExceptSuperAdmin(){
		$DB_PREFIX=DB_PREFIX;
		$sql="select CONCAT_WS(' - ',g.`name`,u.firstname ) username ,u.user_id "
			."from {$DB_PREFIX}user u join {$DB_PREFIX}user_group g on g.user_group_id=u.user_group_id "
			."where g.user_group_id!=1 ";
		$data= $this->db->query($sql);
		return $data->rows;
	}

	public function getTotalUsers($data) {
		
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "user` as u LEFT JOIN `" . DB_PREFIX . "user_group` as ug ON u.user_group_id = ug.user_group_id ";
		
		if (isset($data['user_group_id'])) {
			$sql .= " WHERE (u.user_group_id='{$data[user_group_id]}' OR ug.parent_group_id='{$data[user_group_id]}') ";
		}

		
      	$query = $this->db->query($sql);
    
		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalUsersByEmail($email) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE email = '" . $this->db->escape($email) . "'");
		
		return $query->row['total'];
	}	
}
?>