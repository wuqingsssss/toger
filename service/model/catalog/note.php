<?php
class ModelCatalogNote extends Model {
	public function getNote($note_id,$code='') {
		$cache_language_id=$this->config->get('config_language_id');
		
		$note=$this->cache->get('note.'.$cache_language_id.'.'.$note_id.$code);
		
		if(!$note){
			if($code==''){
				$sql="SELECT DISTINCT * FROM " . DB_PREFIX 
				. "note i LEFT JOIN " . DB_PREFIX 
				. "note_description id ON (i.note_id = id.note_id)  WHERE i.note_id = '" . (int)$note_id 
				. "' AND id.language_id = '" . (int)$this->config->get('config_language_id') 
				. "' AND i.status = '1'";
				
				$query = $this->db->query($sql);
			}else{
				$sql="SELECT DISTINCT * FROM " . DB_PREFIX
				. "note i LEFT JOIN " . DB_PREFIX
				. "note_description id ON (i.note_id = id.note_id)  WHERE i.code = '" . $code
				. "' AND id.language_id = '" . (int)$this->config->get('config_language_id')
				. "' AND i.status = '1'";
					
				
				$query = $this->db->query($sql);
			}
			
			
			if($query->row){
				$this->cache->set('note.' . $cache_language_id.'.'.$note_id , $query->row);
				return $query->row;
			}else{
				return null;
			}
		}else{
			return $note;
		}
	}
	
	public function addMessage($data) {
		$sql="INSERT INTO " . $this->db->table('message') . " SET author = '" . $data['name'] 
		. "', email = '" . $data['email'] 
		. "', message = '" . $this->db->escape($data['enquiry']) 
		. "', date_modified = NOW(), date_added = NOW()";
		$this->db->query($sql);
	}
	
	public function getNotes() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "note i LEFT JOIN " . DB_PREFIX . "note_description id ON (i.note_id = id.note_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND i.status = '1' AND i.sort_order <> '-1' ORDER BY i.sort_order, LCASE(id.title) ASC");
		
		return $query->rows;
	}
	
	public function getNoteLayoutId($note_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "note_to_layout WHERE note_id = '" . (int)$note_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		 
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_note');
		}
	}	
}
?>