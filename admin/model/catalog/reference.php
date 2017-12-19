<?php 
class ModelCatalogReference extends Model {
	public function addreference($data) {
        $code=mt_rand(00000,99999);
        $query=$this->db->query("DELETE FROM " . DB_PREFIX . "reference_list WHERE refer_code = '" .$code . "'");
        if(!$query->row) {
            $sql = "INSERT INTO " . DB_PREFIX . "reference_list SET point_code = '" . $this->db->escape($data['point_code']) . "',type = '" . $this->db->escape($data['type'])
                . "',name = '" . $this->db->escape($data['name']) . "',refer_code = '" . $code

                . "',s_valid_time = '" . $this->db->escape($data['s_valid_time']) . "',e_valid_time = '" . $this->db->escape($data['e_valid_time']) . "',date_added = now()";
            $this->db->query($sql);

            $refer_id = $this->db->getLastId();

            return $refer_id;
        }
	}



    public function getRefer($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "reference_list ";

        if(isset($data['filter_name']) && !is_null($data['filter_name'])){
            $sql.=" WHERE name like '%".$this->db->escape($data['filter_name'])."%' ";
        }

        $sort_data = array(

        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
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

        return $query->rows;
    }

    public function getTotalPoints($data) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "reference_list ";
        if(isset($data) && isset($data['filter_name']) && !is_null($data['filter_name'])){
            $sql.=" WHERE name like '%".$this->db->escape($data['filter_name'])."%' ";
        }
        $query = $this->db->query($sql);



        return $query->row['total'];
    }

	public function editReference($id, $data) {
		$sql="UPDATE " . DB_PREFIX . "reference_list SET point_code = '" .$this->db->escape($data['point_code']) . "',type = '" .$this->db->escape($data['type'])
            . "',name = '" .$this->db->escape($data['name'])

            . "',s_valid_time = '" .$this->db->escape($data['s_valid_time'])

            . "',e_valid_time = '" .$this->db->escape($data['e_valid_time'])

            . "',date_added = now()" . " WHERE id = '" . (int)$id . "'";
		
		$this->db->query($sql);
		

	}
	
	public function deletreference($id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "reference_list WHERE id = '" . (int)$id . "'");

		
		//$this->deletePointToType($refer_code);
	}
		
	public function getreferences($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "reference_list WHERE id = '" . (int)$id . "'");
		
		return $query->row;
	}
	

//
//    public function getFilterPoints($data= array()){
//        $sql = "SELECT * FROM " . DB_PREFIX . "point";
//
//        if(isset($data['filter_name']) && !is_null($data['filter_name'])){
//            $sql.=" WHERE name like '%".$this->db->escape($data['filter_name'])."%' ";
//        }
//
//        $query = $this->db->query($sql);
//
//        return $query->rows;
//    }
//
//


}
?>