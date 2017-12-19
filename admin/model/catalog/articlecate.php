<?php
class ModelCatalogArticlecate extends Model {
	public function addCategory($data) {
		$sql="INSERT INTO " . DB_PREFIX . "article_category SET parent_id = '" . (int)$data['parent_id'] 
		. "',   sort_order = '" . (int)$data['sort_order'] 
		. "', status = '" . (int)$data['status'] 
		. "', date_modified = NOW(), date_added = NOW()";
		$this->db->query($sql);
	
		$article_category_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->editImage($article_category_id,$data);
		}
		
		if (isset($data['code'])) {
			$this->editCode($article_category_id,$data);
		}
		
		if(isset($data['template_id'])){
			$this->editTemplate($article_category_id,$data);
		}
		
		foreach ($data['article_category_description'] as $language_id => $value) {
			$this->addCategoryDescription($article_category_id,$language_id,$value);
		}
		
		if (isset($data['article_category_to_store'])) {
			foreach ($data['article_category_to_store'] as $store_id) {
				$this->addArticleCategoryStore($article_category_id,$store_id);
			}
		}
		
		if (isset($data['article_category_layout'])) {
			foreach ($data['article_category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->addArticleCategoryLayout($article_category_id,$store_id,$layout);
				}
			}
		}
		
		if ($data['keyword']) {
			$this->addUrlAlias($article_category_id,$data);
		}
		
		$this->cache->delete('article_category');
	}
	
	private function addUrlAlias($article_category_id,$data){
		$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_category_id=" . (int)$article_category_id . "', keyword = '" . $this->makeSlugs($this->db->escape($data['keyword'])) . "'");
	}
	
	private function deleteUrlAlias($article_category_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_category_id=" . (int)$article_category_id. "'");
	}
	
	private function addArticleCategoryLayout($article_category_id,$store_id,$layout){
		$this->db->query("INSERT INTO " . DB_PREFIX . "article_category_to_layout SET article_category_id = '" . (int)$article_category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
	}
	
	private function deleteArticleCategoryLayout($article_category_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_category_to_layout WHERE article_category_id = '" . (int)$article_category_id . "'");
	}
	
	private function addArticleCategoryStore($article_category_id,$store_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "article_category_to_store SET article_category_id = '" . (int)$article_category_id . "', store_id = '" . (int)$store_id . "'");
	}
	
	private  function deleteArticleCategoryStore($article_category_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_category_to_store WHERE article_category_id = '" . (int)$article_category_id . "'");
	}
	
	private function addCategoryDescription($article_category_id,$language_id,$value){
		$sql="INSERT INTO " . DB_PREFIX 
		. "article_category_description SET article_category_id = '" . (int)$article_category_id 
		. "', language_id = '" . (int)$language_id 
		. "', name = '" . $this->db->escape($value['name']) 
		. "', description = '" . $this->db->escape($value['description']) 
		. "' , meta_keyword = '" . $this->db->escape($value['meta_keyword'])
		 . "' , meta_description = '" . $this->db->escape($value['meta_description']) . "'";
		
		$this->db->query($sql);
	}
	
	private function deleteCategoryDescription($article_category_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_category_description WHERE article_category_id = '" . (int)$article_category_id . "'");
	}
	
	private function editImage($article_category_id,$data){
		$this->db->query("UPDATE " . DB_PREFIX . "article_category SET image = '" . $this->db->escape($data['image']) . "' WHERE article_category_id = '" . (int)$article_category_id . "'");
	}
	
	private function editCode($article_category_id,$data){
		$this->db->query("UPDATE " . DB_PREFIX . "article_category SET code = '" . $this->db->escape($data['code']) . "' WHERE article_category_id = '" . (int)$article_category_id . "'");
	}
	
	private function editTemplate($article_category_id,$data){
		$this->db->query("UPDATE " . DB_PREFIX . "article_category SET template_id = '" . (int)$data['template_id'] . "' WHERE article_category_id = '" . (int)$article_category_id . "'");
	}
	
	public function editCategory($article_category_id, $data) {
		$sql="UPDATE " . DB_PREFIX . "article_category SET parent_id = '" . (int)$data['parent_id'] 
		. "',   sort_order = '" . (int)$data['sort_order'] 
		. "', status = '" . (int)$data['status'] 
		. "' ,date_modified = NOW() WHERE article_category_id = '" . (int)$article_category_id . "'";
		$this->db->query($sql);

		if (isset($data['image'])) {
			$this->editImage($article_category_id,$data);
		}
		
		if (isset($data['code'])) {
			$this->editCode($article_category_id,$data);
		}
		
		if(isset($data['template_id'])){
			$this->editTemplate($article_category_id,$data);
		}

		
		$this->deleteCategoryDescription($article_category_id);
		foreach ($data['article_category_description'] as $language_id => $value) {
			$this->addCategoryDescription($article_category_id,$language_id,$value);
		}
		
		
		$this->deleteArticleCategoryStore($article_category_id);
		if (isset($data['article_category_to_store'])) {		
			foreach ($data['article_category_to_store'] as $store_id) {
				$this->addArticleCategoryStore($article_category_id,$store_id);
			}
		}
		
		$this->deleteArticleCategoryLayout($article_category_id);
		if (isset($data['article_category_layout'])) {
			foreach ($data['article_category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->addArticleCategoryLayout($article_category_id,$store_id,$layout);
				}
			}
		}
		
		
		$this->deleteUrlAlias($article_category_id);
		if ($data['keyword']) {
			$this->addUrlAlias($article_category_id,$data);
		}
		
		$this->cache->delete('article_category');
	}
	
	public function deleteCategory($article_category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_category WHERE article_category_id = '" . (int)$article_category_id . "'");
		$this->deleteCategoryDescription($article_category_id);
		$this->deleteArticleCategoryStore($article_category_id);
		$this->deleteArticleCategoryLayout($article_category_id);
		
//		$query = $this->db->query("SELECT article_category_id FROM " . DB_PREFIX . "article_category WHERE parent_id = '" . (int)$article_category_id . "'");
//
//		foreach ($query->rows as $result) {
//			$this->deleteCategory($result['article_category_id']);
//		}
		
		$this->cache->delete('article_category');
	} 

	public function getCategory($article_category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_category_id=" . (int)$article_category_id . "') AS keyword FROM " . DB_PREFIX . "article_category WHERE article_category_id = '" . (int)$article_category_id . "'");
		
		return $query->row;
	} 
	
	public function getCategories($parent_id=0) {
		$category_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_category c LEFT JOIN " . DB_PREFIX . "article_category_description cd ON (c.article_category_id = cd.article_category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
	
		foreach ($query->rows as $result) {
			$category_data[] = array(
				'article_category_id' => $result['article_category_id'],
				'name'        => $this->getPath($result['article_category_id'], $this->config->get('config_language_id')),
				'code'  	  => $result['code'],
				'status'  	  => $result['status'],
				'sort_order'  => $result['sort_order']
			);
		
			$category_data = array_merge($category_data, $this->getCategories($result['article_category_id']));
		}	
			
		return $category_data;
	}
	
	public function getPath($article_category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "article_category c LEFT JOIN " . DB_PREFIX . "article_category_description cd ON (c.article_category_id = cd.article_category_id) WHERE c.article_category_id = '" . (int)$article_category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		$category_info = $query->row;
	
		if ($category_info && $category_info['parent_id']) {
			return $this->getPath($category_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $category_info['name'];
		} else if($category_info) {
			return $category_info['name'];
		}else{
			return '';
		}
	}
	
	public function getCategoryDescriptions($article_category_id) {
		$category_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_category_description WHERE article_category_id = '" . (int)$article_category_id . "'");
		
		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_keyword'    => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $category_description_data;
	}	

	public function getCategoryStores($article_category_id) {
		$category_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_category_to_store WHERE article_category_id = '" . (int)$article_category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}
		
		return $category_store_data;
	}
	
	public function getTotalCategories() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article_category");
		
		return $query->row['total'];
	}	
		
	public function getTotalCategoriesByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article_category WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
	}
	
	public function getCate4DefaultStore($parent_id) {
		$category_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_category c LEFT JOIN " . DB_PREFIX . "article_category_description cd ON (c.article_category_id = cd.article_category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd.article_category_id IN ( SELECT  article_category_id FROM " . DB_PREFIX . "article_category_to_store WHERE store_id = 0 ) ORDER BY c.sort_order, cd.name ASC");
		foreach ($query->rows as $result) {
				$category_data[] = array(
					'article_category_id' => $result['article_category_id'],
					'name'        => $this->getPath($result['article_category_id'], $this->config->get('config_language_id')),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
				);
			
				$category_data = array_merge($category_data, $this->getCate4DefaultStore($result['article_category_id']));
		}	
		$this->cache->set('article_category.' . $this->config->get('config_language_id') . '.' . $parent_id, $category_data);
		return $category_data;
	}
	
	
	public function getArticleCateLayoutId($article_category_id) {
		$article_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_category_to_layout WHERE article_category_id = '" . (int)$article_category_id . "'");

		foreach ($query->rows as $result) {
			$article_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $article_layout_data;
	}
	
	public function getTotalArticles($article_category_id){
		$sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article_to_category WHERE article_category_id=".(int)$article_category_id;
		$query = $this->db->query($sql);
		
		if($query->row){
			return $query->row['total'];
		}else{
			return 0;
		}
	}
}
?>