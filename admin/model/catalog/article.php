<?php
class ModelCatalogArticle extends Model {
	public function addArticle($data) {
		$sql="INSERT INTO " . DB_PREFIX . "article SET date_added = '" . $this->db->escape($data['date_added']) 
		. "',  status = '" . (int)$data['status']  
		. "',  sort_order = '" . (int)$data['sort_order'] 
		. "',editor='".$this->db->escape($data['editor'])
		."',date_modified = NOW(),featured=".(int)$data['featured'];
		
		$this->db->query($sql);
		
		$article_id = $this->db->getLastId();
		
		if(isset($data['image'])){
			$this->editArticleImage($article_id,$data['image']);
		}
		
		if(isset($data['quantity'])){
			$this->editArticleQuentity($article_id,$data['quantity']);
		}
		
		
		foreach($data['article'] as $language_id => $value){
			$this->addArticleDescription($article_id,$language_id,$value);
		}
		

		if (isset($data['article_category'])) {
			foreach ($data['article_category'] as $article_category_id) {
				$this->addArticleToCategory($article_id,$article_category_id);
			}
		}

		if ($data['keyword']) {
			$this->addUrlAlias($article_id,$data);
		}
		
		foreach ($data['article_tags'] as $language_id => $value) {
			$tags = explode(',', $value);
			foreach ($tags as $tag) {
				$this->addArticleTag($article_id,$language_id,$value);
			}
		}

		if (isset($data['article_related'])) {
			foreach ($data['article_related'] as $related_id) {
				$this->addArticleRelated($article_id,$related_id);
			}
		}

		if (isset($data['article_download'])) {
			foreach ($data['article_download'] as $download_id) {
				$this->addArticleDownload($article_id,$download_id);
			}
		}
		
		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->addArticleLayout($article_id,$store_id,$layout);
				}
			}
		}
		
		$this->cache->delete('article');
	}
	
	private function addArticleLayout($article_id,$store_id,$layout){
		$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_layout SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
	}
	
	private function deleteArticleLayout($article_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");
	}
	
	private function addArticleDownload($article_id,$download_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_download SET  article_id = '" . (int)$article_id . "', download_id = '" . (int)$download_id . "'");
	}
	
	private function deleteArticleDownload($article_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_download WHERE article_id = '" . (int)$article_id . "'");
	}
	
	private function addArticleRelated($article_id,$related_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$article_id . "', related_id = '" . (int)$related_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$related_id . "' AND related_id = '" . (int)$article_id . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$related_id . "', related_id = '" . (int)$article_id . "'");
	}
	
	private function deleteArticleRelated($article_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "'");
	}
	
	private function addArticleTag($article_id,$language_id,$tag){
		$this->db->query("INSERT INTO " . DB_PREFIX . "article_tags SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', tag = '" . $this->db->escape(trim($tag)) . "'");
	}
	
	private function deleteArticleTag($article_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_tags WHERE article_id = '" . (int)$article_id. "'");
	}
	
	private function addUrlAlias($article_id,$data){
		$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id ."', keyword = '" . $this->db->escape($data['keyword']) . "'");
	}
	
	private function deleteUrlAlias($article_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id. "'");
	}
	
	private function addArticleToCategory($article_id,$article_category_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_category SET article_id = '" . (int)$article_id . "', article_category_id = '" . (int)$article_category_id . "'");
	}
	
	private function deleteArticleToCategory($article_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_category WHERE  article_id = '" . (int)$article_id . "'");
	}
	
	private function addArticleDescription($article_id,$language_id,$value){
		$sql="INSERT INTO " . DB_PREFIX . "article_description SET article_id=".(int)$article_id
		.",language_id=".(int)$language_id.",name='" . $this->db->escape($value['title']) 
		. "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) 
		. "',  meta_description = '" . $this->db->escape($value['meta_description']) 
		. "',  description = '" . $this->db->escape($value['content']) 
		. "',  summary = '" . $this->db->escape($value['summary']) . "'";
		
		$this->db->query($sql);
	}
	
	private function deleteArticleDescription($article_id){
		$sql="DELETE FROM " . DB_PREFIX . "article_description WHERE article_id=".(int)$article_id;
		$this->db->query($sql);
	}
	
	private function editArticleImage($article_id,$image){
		$sql="UPDATE " . DB_PREFIX . "article SET image='" . $this->db->escape($image) 
		. "' WHERE article_id=".(int)$article_id;
		
		$this->db->query($sql);
	}
	
	private function editArticleQuentity($article_id,$image){
		$sql="UPDATE " . DB_PREFIX . "article SET quantity='" . $this->db->escape($image) 
		. "' WHERE article_id=".(int)$article_id;
		
		$this->db->query($sql);
	}
	
	private function editArticleFeatureImage($article_id,$status){
		$sql="UPDATE " . DB_PREFIX . "article SET feature_image='" . (int)$status
		. "' WHERE article_id=".(int)$article_id;
		
		$this->db->query($sql);
	}

	public function editArticle($article_id, $data) {
		$sql="UPDATE " . DB_PREFIX . "article SET date_added = '" . $this->db->escape($data['date_added']) 
		. "',  status = '" . (int)$data['status'] 
		. "',  sort_order = '" . (int)$data['sort_order'] 
		. "',editor='".$this->db->escape($data['editor'])
		."',date_modified = NOW(),featured=".(int)$data['featured']
		." WHERE article_id=".(int)$article_id;
		
		$this->db->query($sql);
		
		if(isset($data['image'])){
			$this->editArticleImage($article_id,$data['image']);
		}
		
		if(isset($data['quantity'])){
			$this->editArticleQuentity($article_id,$data['quantity']);
		}
		
		
		$this->deleteArticleDescription($article_id);
		
		foreach($data['article'] as $language_id => $value){
			$this->addArticleDescription($article_id,$language_id,$value);
		}
		
		$this->deleteArticleToCategory($article_id);
		if (isset($data['article_category'])) {
			foreach ($data['article_category'] as $article_category_id) {
				$this->addArticleToCategory($article_id,$article_category_id);
			}
		}
		
		$this->deleteUrlAlias($article_id);
		if ($data['keyword']) {
			$this->addUrlAlias($article_id,$data);
		}

		$this->deleteArticleTag($article_id);
		foreach ($data['article_tags'] as $language_id => $value) {
			$tags = explode(',', $value);
			foreach ($tags as $tag) {
				$this->addArticleTag($article_id,$language_id,$value);
			}
		}

		
		$this->deleteArticleRelated($article_id);
		if (isset($data['article_related'])) {
			foreach ($data['article_related'] as $related_id) {
				$this->addArticleRelated($article_id,$related_id);
			}
		}
		
		
		$this->deleteArticleDownload($article_id);
		if (isset($data['article_download'])) {
			foreach ($data['article_download'] as $download_id) {
				$this->addArticleDownload($article_id,$download_id);
			}
		}
		
		
		$this->deleteArticleLayout($article_id);
		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->addArticleLayout($article_id,$store_id,$layout);
				}
			}
		}
		
		$this->cache->delete('article');
	}

	public function deleteArticle($article_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "article WHERE article_id = '" . (int)$article_id . "'");
		$this->deleteArticleToCategory($article_id);
		$this->deleteUrlAlias($article_id);
		$this->deleteArticleTag($article_id);
		$this->deleteArticleRelated($article_id);
		$this->deleteArticleDownload($article_id);
		
		$this->cache->delete('article');
	}

	public function getOneArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "') AS keyword FROM  " . DB_PREFIX . "article p WHERE p.article_id = '" . (int)$article_id . "' AND p.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	private function getFilterSQL($sql,$data){
		if (isset($data['filter_title']) && !is_null($data['filter_title'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_title'])) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(p.date_added) = '" . $this->db->escape($data['filter_date_added']) . "'";
		}
		
		if(isset($data['filter_article_category_id']) && !is_null($data['filter_article_category_id'])){
			$sql .=" AND pd.article_id IN (Select article_id from  " . DB_PREFIX . "article_to_category WHERE article_category_id=".(int)$data['filter_article_category_id'].")";
		}
		
		
		return $sql;
	}
	
	public function getArticles($data = array()){
		$sql = "SELECT * FROM   " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX 
		. "article_description pd ON (p.article_id=pd.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$sql=$this->getFilterSQL($sql,$data);

			$sort_data = array(
				'pd.name',
				'p.status',
				'pd.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY pd.name";
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
	
	
	public function getArticle($article_id) {
		$sql="SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "') AS keyword  FROM  " . DB_PREFIX . "article p WHERE p.article_id=".(int)$article_id;
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getArticleName($article_id){
		$sql="SELECT * FROM  ". DB_PREFIX . "article_description WHERE article_id=".(int)$article_id." AND language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}


	public function getArticleByKeyword($keyword) {
		if ($keyword) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX 
		. "article_description pd ON (p.article_id=pd.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND (LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' )");

			return $query->rows;
		} else {
			return array();
		}
	}

	public function getArticleByCategoryId($category_id) {
		$sql="SELECT * FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX
		. "article_description pd ON (p.article_id=pd.article_id) LEFT JOIN " . DB_PREFIX 
		. "article_to_category p2c ON (p.article_id = p2c.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.article_category_id = '" . (int)$category_id 
		. "' ORDER BY pd.name ASC";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getArticleContent($article_id) {
		$article_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_data[$result['language_id']] = array(
				'title'             => $result['name'],
				'meta_keyword'    => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'summary' => $result['summary'],
				'content'      => $result['description']
			);
		}

		return $article_data;
	}


	public function getArticleCategories($article_id) {
		$article_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_category WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_category_data[] = $result['article_category_id'];
		}

		return $article_category_data;
	}

	public function getArticleRelated($article_id) {
		$article_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_related_data[] = $result['related_id'];
		}

		return $article_related_data;
	}

	public function getArticleTags($article_id) {
		$article_tag_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_tags WHERE article_id = '" . (int)$article_id . "'");

		$tag_data = array();

		foreach ($query->rows as $result) {
			$tag_data[$result['language_id']][] = $result['tag'];
		}

		foreach ($tag_data as $language => $tags) {
			$article_tag_data[$language] = implode(',', $tags);
		}

		return $article_tag_data;
	}

	public function getTotalArticles($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX 
		. "article_description pd ON (p.article_id=pd.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sql=$this->getFilterSQL($sql,$data);

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getArticleDownloads($article_id) {
		$article_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_download WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_download_data[] = $result['download_id'];
		}

		return $article_download_data;
	}

	public function getArticleLayouts($article_id) {
		$article_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $article_layout_data;
	}
	
	public function getArticleCategoryName($article_id){
		$sql="SELECT * FROM " . DB_PREFIX . "article_to_category WHERE article_id=".(int)$article_id." ORDER BY article_category_id DESC limit 1";
		
		$query=$this->db->query($sql);
		
		if($query->row){
			$this->load->model('catalog/articlecate');
			return $this->model_catalog_articlecate->getPath($query->row['article_category_id']);
		}else{
			return '';
		}
	}
}
?>