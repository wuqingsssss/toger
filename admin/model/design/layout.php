<?php
class ModelDesignLayout extends Model {
	public function addLayout($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "layout SET name = '" . $this->db->escape($data['name']) . "'");
	
		$layout_id = $this->db->getLastId();
		
		if(isset($data['priority'])){
			$this->editLayoutPriority($layout_id,$data['priority']);
		}
		
		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', store_id = '" . (int)$layout_route['store_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "'");
			}	
		}
	}
	

	
	public function editLayout($layout_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "layout SET name = '" . $this->db->escape($data['name']) . "' WHERE layout_id = '" . (int)$layout_id . "'");
		
		if(isset($data['priority'])){
			$this->editLayoutPriority($layout_id,$data['priority']);
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");
		
		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', store_id = '" . (int)$layout_route['store_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "'");
			}
		}
	}
	
	private function editLayoutPriority($layout_id,$priority){
		$this->db->query("UPDATE " . DB_PREFIX . "layout SET priority = '" . (int)$priority . "' WHERE layout_id = '" . (int)$layout_id . "'");
	}
	
	public function deleteLayout($layout_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_layout WHERE layout_id = '" . (int)$layout_id . "'");		
	}
	
	public function deleteLayoutModule($layout_module_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_module WHERE layout_module_id = '" . (int)$layout_module_id . "'");
		}
	
	public function getLayout($layout_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "layout WHERE layout_id = '" . (int)$layout_id . "'");
		
		return $query->row;
	}
	
	
	public function updateModules($code,$modules){
		    $sql='';	
			$oldid=array();
			foreach ($modules as $value) {
				if($value['layout_module_id']){
					$oldid[]=$value['layout_module_id'];
				$sql.="UPDATE " . DB_PREFIX . "layout_module SET layout_id = '" . (int)$value['layout_id'] . "',code = '" . $code . "', position = '" . $this->db->escape($value['position']) . "',sort_order= '" . (int)$value['sort_order'] . "',status='".(int)$value['status']."', template = '" . $this->db->escape($value['template']) . "', setting = '" . $this->db->escape(serialize($value)) . "' WHERE layout_module_id='".(int)$value['layout_module_id']."';";	
				}else{
					unset($value['layout_module_id']);
				$sql.="INSERT INTO " . DB_PREFIX . "layout_module SET layout_id = '" . (int)$value['layout_id'] . "',code = '" . $code . "', position = '" . $this->db->escape($value['position']) . "',sort_order= '" . (int)$value['sort_order'] . "',status='".(int)$value['status']."', template = '" . $this->db->escape($value['template']) . "', setting = '" . $this->db->escape(serialize($value)) . "';";
				}

			}
	if($oldid)
			$sql="DELETE FROM " . DB_PREFIX . "layout_module WHERE layout_module_id not in(" . implode(',', $oldid) . ") AND code='".$code."';".$sql;
			else 
			$sql="DELETE FROM " . DB_PREFIX . "layout_module WHERE code='".$code."';".$sql;

			$this->db->multi_query($sql);
			
	}
	
	public function getLayoutModules($data) {
		$sql = "SELECT lm.*,l.name as layout_name FROM " . DB_PREFIX . "layout_module lm
				 LEFT JOIN " . DB_PREFIX . "layout l ON lm.layout_id=l.layout_id";
		
		if($data)
		{
			$sql.=" WHERE";
			if($data['layout_id'])
		    $sql.=" lm.layout_id='{$data['layout_id']}'";
			elseif($data['code'])
			$sql.=" lm.code='{$data['code']}'";
		}
		         		
		  $sql.=" ORDER BY template ASC, sort_order ASC";
		
		

		    $query = $this->db->query($sql);
            foreach($query->rows as $key=>$row)
             {
             	$query->rows[$key]=array_merge($query->rows[$key],unserialize($row['setting']));
	
             }	      
		 return $query->rows;
	}
	
	public function getLayouts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "layout";
		
		$sort_data = array('name');	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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
	
	public function getLayoutRoutes($layout_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");
		
		return $query->rows;
	}
		
	public function getTotalLayouts() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "layout");
		
		return $query->row['total'];
	}	
}
?>