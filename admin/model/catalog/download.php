<?php
class ModelCatalogDownload extends Model {
	public function addDownload($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "download SET remaining = '" . (int)$data['remaining'] . "', date_added = NOW()");

      	$download_id = $this->db->getLastId(); 

      	if (isset($data['download'])) {
        	$this->db->query("UPDATE " . DB_PREFIX . "download SET filename = '" . $this->db->escape($data['download']) . "', mask = '" . $this->db->escape($data['mask']) . "' WHERE download_id = '" . (int)$download_id . "'");
      	}

      	foreach ($data['download_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
      	}	
      	
      	if (isset($data['download_category'])) {
			foreach ($data['download_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "download_to_category SET download_id = '" . (int)$download_id . "', download_category_id = '" . (int)$category_id . "'");
			}
		}

	}
	
	public function editDownload($download_id, $data) {
        $query = $this->db->query("SELECT filename from " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
        
        $old_filename = $query->row['filename'];
        
        $this->db->query("UPDATE " . DB_PREFIX . "download SET remaining = '" . (int)$data['remaining'] . "' WHERE download_id = '" . (int)$download_id . "'");
      	
		if (isset($data['download'])) {
        	$this->db->query("UPDATE " . DB_PREFIX . "download SET filename = '" . $this->db->escape($data['download']) . "', mask = '" . $this->db->escape($data['mask']) . "' WHERE download_id = '" . (int)$download_id . "'");
        	
        	if (isset($data['update'])) {
      			$query = $this->db->query("SELECT * from " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
	                
      			$this->db->query("UPDATE " . DB_PREFIX . "order_download SET remaining = '" . (int)$query->row['remaining'] . "', `filename` = '" . $this->db->escape($query->row['filename']) . "', mask = '" . $this->db->escape($query->row['mask']) . "' WHERE `filename` = '" . $this->db->escape($old_filename) . "'");
      		}
      	
      	}

      	$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");

      	foreach ($data['download_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
      	}	
      	
      	$this->db->query("DELETE FROM " . DB_PREFIX . "download_to_category WHERE download_id = '" . (int)$download_id . "'");
      	if (isset($data['download_category'])) {
      		foreach ($data['download_category'] as $category_id) {
      			$this->db->query("INSERT INTO " . DB_PREFIX . "download_to_category SET download_id = '" . (int)$download_id . "', download_category_id = '" . (int)$category_id . "'");
      		}
      	}
	}
	
	public function deleteDownload($download_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
	  	$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");	
	}	

	public function getDownload($download_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
		
		return $query->row;
	}

	public function getDownloads($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		$sort_data = array(
			'dd.name',
			'd.remaining'
		);
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY dd.name";	
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
	
	public function getDownloadDescriptions($download_id) {
		$download_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");
		
		foreach ($query->rows as $result) {
			$download_description_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $download_description_data;
	}
	
	public function getTotalDownloads() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "download");
		
		return $query->row['total'];
	}	
	

	public function addCategory($data) {
		$name='';
		$this->db->query("INSERT INTO " . DB_PREFIX . "download_category SET parent_id = '" . (int)$data['parent_id'] . "', code = '" . $this->db->escape($data['code']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");
	
		$download_category_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "download_category SET image = '" . $this->db->escape($data['image']) . "' WHERE download_category_id = '" . (int)$download_category_id . "'");
		}
		
		foreach ($data['category_description'] as $language_id => $value) {
			if($this->config->get('config_language_id')==$language_id)
				$name=$this->makeSlugs($this->db->escape($value['name']));
			$this->db->query("INSERT INTO " . DB_PREFIX . "download_category_description SET download_category_id = '" . (int)$download_category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "download_category_to_store SET download_category_id = '" . (int)$download_category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "download_category_to_layout SET download_category_id = '" . (int)$download_category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
						
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'download_category_id=" . (int)$download_category_id . "', keyword = '" . $this->makeSlugs($this->db->escape($data['keyword'])) . "'");
		}else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'download_category_id=" . (int)$download_category_id . "', keyword = '" .$name  . "'");
		}
		
		$this->cache->delete('dcategory');
	}
	
	public function editCategoryStatus($download_category_id, $status) {
		$this->db->query("UPDATE " . DB_PREFIX . "download_category SET status = '" . (int)$status. "' WHERE download_category_id = '" . (int)$download_category_id . "'");
	}
	
	public function editCategory($download_category_id, $data) {
		$name='';
		$this->db->query("UPDATE " . DB_PREFIX . "download_category SET parent_id = '" . (int)$data['parent_id'] . "', code = '" . $this->db->escape($data['code']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE download_category_id = '" . (int)$download_category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "download_category SET image = '" . $this->db->escape($data['image']) . "' WHERE download_category_id = '" . (int)$download_category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "download_category_description WHERE download_category_id = '" . (int)$download_category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			if($this->config->get('config_language_id')==$language_id)
				$name=$this->makeSlugs($this->db->escape($value['name']));
			$this->db->query("INSERT INTO " . DB_PREFIX . "download_category_description SET download_category_id = '" . (int)$download_category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_category_to_store WHERE download_category_id = '" . (int)$download_category_id . "'");
		
		if (isset($data['category_store'])) {		
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "download_category_to_store SET download_category_id = '" . (int)$download_category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "download_category_to_layout WHERE download_category_id = '" . (int)$download_category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "download_category_to_layout SET download_category_id = '" . (int)$download_category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
						
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'download_category_id=" . (int)$download_category_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'download_category_id=" . (int)$download_category_id . "', keyword = '" . $this->makeSlugs($this->db->escape($data['keyword'])) . "'");
		}else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'download_category_id=" . (int)$download_category_id . "', keyword = '" .$name  . "'");
		}
		
	}
	
	public function deleteCategory($download_category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_category WHERE download_category_id = '" . (int)$download_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_category_description WHERE download_category_id = '" . (int)$download_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_category_to_store WHERE download_category_id = '" . (int)$download_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_category_to_layout WHERE download_category_id = '" . (int)$download_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'download_category_id=" . (int)$download_category_id . "'");
		
		$query = $this->db->query("SELECT download_category_id FROM " . DB_PREFIX . "download_category WHERE parent_id = '" . (int)$download_category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['download_category_id']);
		}
		
		$this->cache->delete('dcategory');
	} 

	public function getCategory($download_category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'download_category_id=" . (int)$download_category_id . "') AS keyword FROM " . DB_PREFIX . "download_category WHERE download_category_id = '" . (int)$download_category_id . "'");
		
		return $query->row;
	} 
	
	public function getCategories($parent_id) {
		$category_data = $this->cache->get('dcategory.' . $this->config->get('config_language_id') . '.' . $parent_id);
	
		if (!$category_data) {
			$category_data = array();
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_category c LEFT JOIN " . DB_PREFIX . "download_category_description cd ON (c.download_category_id = cd.download_category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' => $result['download_category_id'],
					'code' => $result['code'],
					'name'        => $this->getPath($result['download_category_id'], $this->config->get('config_language_id')),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
				);
			
				$category_data = array_merge($category_data, $this->getCategories($result['download_category_id']));
			}	
	
			$this->cache->set('dcategory.' . $this->config->get('config_language_id') . '.' . $parent_id, $category_data);
		}
		
		return $category_data;
	}
	
	public function getPath($download_category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "download_category c LEFT JOIN " . DB_PREFIX . "download_category_description cd ON (c.download_category_id = cd.download_category_id) WHERE c.download_category_id = '" . (int)$download_category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		$category_info = $query->row;
		
		if ($category_info['parent_id']) {
			return $this->getPath($category_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $category_info['name'];
		} else {
			return $category_info['name'];
		}
	}
	
	public function getCategoryDescriptions($download_category_id) {
		$category_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_category_description WHERE download_category_id = '" . (int)$download_category_id . "'");
		
		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_title'     => $result['meta_title'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $category_description_data;
	}	
	
	public function getCategoryStores($download_category_id) {
		$category_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_category_to_store WHERE download_category_id = '" . (int)$download_category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}
		
		return $category_store_data;
	}

	public function getCategoryLayouts($download_category_id) {
		$category_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_category_to_layout WHERE download_category_id = '" . (int)$download_category_id . "'");
		
		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $category_layout_data;
	}
		
	public function getTotalCategories() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "download_category");
		
		return $query->row['total'];
	}	
		
	public function getTotalCategoriesByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "download_category WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
	}

	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "download_category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	
	
	public function getDownloadCategories($download_id) {
		$product_category_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_to_category WHERE download_id = '" . (int)$download_id . "'");
	
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['download_category_id'];
		}
	
		return $product_category_data;
	}
}
?>
