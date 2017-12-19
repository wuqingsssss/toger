<?php
class ModelCatalogArticleCate extends Model {
	
	public function getArticleCategory($article_category_id){
		$sql="SELECT DISTINCT ac.*,acd.*, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_category_id=" . (int)$article_category_id . "') AS keyword FROM " . DB_PREFIX 
		. "article_category ac LEFT JOIN " . DB_PREFIX . "article_category_description acd ON (ac.article_category_id=acd.article_category_id) WHERE ac.article_category_id = '" . 
		(int)$article_category_id . "' AND  acd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$query=$this->db->query($sql);
		
		return $query->row;
	}

	public function getCategory($article_category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_category_id=" . (int)$article_category_id . "') AS keyword FROM " . DB_PREFIX . "article_category WHERE status=1 AND article_category_id = '" . (int)$article_category_id . "'");
	
		return $query->row;
	} 
	
	public function getCategories($parent_id) {
		$category_data = $this->cache->get('article_category.' . $this->config->get('config_language_id') . '.' . $parent_id);
	
		if (!$category_data) {
			$category_data = array();
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_category c LEFT JOIN " . DB_PREFIX . "article_category_description cd ON (c.article_category_id = cd.article_category_id) WHERE c.status=1 AND  c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
			foreach ($query->rows as $result) {
				$category_data[] = array(
					'article_category_id' => $result['article_category_id'],
					'name'        => $this->getPath($result['article_category_id'], $this->config->get('config_language_id')),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
				);
			
				$category_data = array_merge($category_data, $this->getCategories($result['article_category_id']));
			}	
	
			$this->cache->set('article_category.' . $this->config->get('config_language_id') . '.' . $parent_id, $category_data);
		}
		
		return $category_data;
	}
	
	public function getPath($article_category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "article_category c LEFT JOIN " . DB_PREFIX . "article_category_description cd ON (c.article_category_id = cd.article_category_id) WHERE c.status=1 AND c.article_category_id = '" . (int)$article_category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		$category_info = $query->row;
		
		if($category_info){
			return $category_info['name'];
		}
		
//		if ($category_info['parent_id']) {
//			return $this->getPath($category_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $category_info['name'];
//		} else {
//			return $category_info['name'];
//		}
	}
	
	
	public function getCategoryDescriptions($article_category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_category_description WHERE article_category_id = '" . (int)$article_category_id . "' 
		AND  language_id = '" . (int)$this->config->get('config_language_id') . "' ");
		
		return $query->row;
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
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article_category WHERE��status=1 ");
		
		return $query->row['total'];
	}	
		
	public function getTotalCategoriesByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article_category WHERE status=1 AND image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
	}
	
	public function getArticleCateLayoutId($article_cate_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_category_to_layout WHERE article_category_id = '" . (int)$article_cate_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		 
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_information');
		}
	}	
}
?>