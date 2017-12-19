<?php
class ModelCatalogNote extends Model {
	public function addNote($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "note SET sort_order = '" . (int)$data['sort_order'] 
		. "', status = '" . (int)$data['status'] . "',code = '" . $data['code'] . "'");

		$note_id = $this->db->getLastId(); 
		
		foreach ($data['note_description'] as $language_id => $value) {
			$this->addNoteDescription($note_id,$language_id,$value);
		}
		
		if (isset($data['note_store'])) {
			foreach ($data['note_store'] as $store_id) {
				$this->addNoteStore($note_id,$store_id);
			}
		}

		if (isset($data['note_layout'])) {
			foreach ($data['note_layout'] as $store_id => $layout) {
				if ($layout) {
					$this->addNoteLayout($note_id,$store_id,$layout);
				}
			}
		}
				
		
		$this->cache->delete('note');
	}
	
	public function editNote($note_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "note SET sort_order = '" . (int)$data['sort_order'] 
		. "', status = '" . (int)$data['status'] . "' ,code = '" . $data['code'] . "'   WHERE note_id = '" . (int)$note_id . "'");
		
		$this->deleteNoteDescription($note_id);
		foreach ($data['note_description'] as $language_id => $value) {
			$this->addNoteDescription($note_id,$language_id,$value);
		}

		$this->deleteNoteStore($note_id);
		if (isset($data['note_store'])) {
			foreach ($data['note_store'] as $store_id) {
				$this->addNoteStore($note_id,$store_id);
			}
		}
		
		$this->deleteNoteLayout($note_id);
		if (isset($data['note_layout'])) {
			foreach ($data['note_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->addNoteLayout($note_id,$store_id,$layout);
				}
			}
		}
				
		$this->cache->delete('note');
	}
	
	public function deleteNote($note_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "note WHERE note_id = '" . (int)$note_id . "'");
		$this->deleteNoteDescription($note_id);
		$this->deleteNoteStore($note_id);
		$this->deleteNoteLayout($note_id);
		$this->deleteUrlAlias($note_id);

		$this->cache->delete('note');
	}	

	public function getNote($note_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'note_id=" . (int)$note_id . "') AS keyword FROM " . DB_PREFIX . "note WHERE note_id = '" . (int)$note_id . "'");
		
		return $query->row;
	}
		
	public function getNotes($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "note i LEFT JOIN " . DB_PREFIX . "note_description id ON (i.note_id = id.note_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY id.title";	
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
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "note i LEFT JOIN " . DB_PREFIX . "note_description id ON (i.note_id = id.note_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
	
			return $query->rows;			
		}
	}
	
	public function getNoteDescriptions($note_id) {
		$note_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "note_description WHERE note_id = '" . (int)$note_id . "'");

		foreach ($query->rows as $result) {
			$note_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description'],
				'summary' => $result['summary'],
				'meta_keyword' => $result['meta_keyword'],
				'meta_description' => $result['meta_description']
			);
		}
		
		return $note_description_data;
	}
	
	public function getNoteStores($note_id) {
		$note_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "note_to_store WHERE note_id = '" . (int)$note_id . "'");

		foreach ($query->rows as $result) {
			$note_store_data[] = $result['store_id'];
		}
		
		return $note_store_data;
	}

	public function getNoteLayouts($note_id) {
		$note_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "note_to_layout WHERE note_id = '" . (int)$note_id . "'");
		
		foreach ($query->rows as $result) {
			$note_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $note_layout_data;
	}
		
	public function getTotalNotes() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "note");
		
		return $query->row['total'];
	}	
	
	public function getTotalNotesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "note_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}

	private function addNoteDescription($note_id,$language_id,$value){
		$sql="INSERT INTO " . DB_PREFIX . "note_description SET note_id = '" . (int)$note_id 
		. "', language_id = '" . (int)$language_id 
		. "', title = '" . $this->db->escape($value['title']) 
		. "', summary = '" . $this->db->escape($value['summary']). "'";
		
		$this->db->query($sql);
	}
	
	private function deleteNoteDescription($note_id){
		$sql="DELETE FROM " . DB_PREFIX . "note_description WHERE note_id=".(int)$note_id;
		$this->db->query($sql);
	}
	
	private  function addNoteStore($note_id,$store_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "note_to_store SET note_id = '" . (int)$note_id . "', store_id = '" . (int)$store_id . "'");
	}
	
	private function deleteNoteStore($note_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "note_to_store WHERE note_id = '" . (int)$note_id . "'");
	}
	
	private function addNoteLayout($note_id,$store_id,$layout){
		$this->db->query("INSERT INTO " . DB_PREFIX . "note_to_layout SET note_id = '" . (int)$note_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
	}
	
	private function deleteNoteLayout($note_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "note_to_layout WHERE note_id = '" . (int)$note_id . "'");
	}
	
	private function addUrlAlias($note_id,$data){
		$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'note_id=" . (int)$note_id 
		. "', keyword = '" . $this->db->escape($data['keyword']) . "'");
	}
	
	private function deleteUrlAlias($note_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'note_id=" . (int)$note_id. "'");
	}
}
?>
