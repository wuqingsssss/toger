<?php
class ModelCatalogArticle extends Model {
	public function getArticle($article_id) {
		$cache_language_id=$this->config->get('config_language_id');
		
		$article=$this->cache->get('article.'.$cache_language_id.'.'.$article_id);
	
		if(!$article){
			$sql="SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "') AS keyword FROM  " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX 
			. "article_description pd ON (p.article_id=pd.article_id) WHERE p.status=1 AND p.article_id = '" . (int)$article_id 
			. "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1'";
			
			$query = $this->db->query($sql);
			
			if($query->row){
				$this->cache->set('article.' . $cache_language_id.'.'.$article_id , $query->row);
				return $query->row;
			}else{
				return null;
			}
		}else{
			return $article;
		}
	}
	
	public function getOneArticle($article_id){
		return $this->getArticle($article_id);
	}

	public function getArticles($data = array()) {
		$sql = "SELECT  DISTINCT * FROM   " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX 
		. "article_description pd ON (p.article_id=pd.article_id) WHERE  pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		 AND p.status=1 ";
		
		if (isset($data['article_category_id']) && !is_null($data['article_category_id'])) {
			
			if(isset($data['filter_sub_category']) && !is_null($data['filter_sub_category'])){
				$implode_data = array();
				
				$this->load->model('catalog/articlecate');
				
				$categories = $this->model_catalog_articlecate->getCategories($data['article_category_id']);
				
				foreach ($categories as $category) {
					$implode_data[] = "article_category_id = '" . (int)$category['article_category_id'] . "'";
				}
				
				$sql .= " AND p.article_id in (SELECT article_id FROM " . DB_PREFIX . "article_to_category WHERE ". implode(' OR ', $implode_data) . ")";
				
			}else{
				$sql .= " AND p.article_id in (SELECT article_id FROM " . DB_PREFIX . "article_to_category WHERE article_category_id=".(int)$data['article_category_id'].")";
			}	
		}
		
		if (isset($data['title']) && !is_null($data['title'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_title'])) . "%'";
		}
		
		if(isset($data['has_image'])&& !is_null($data['has_image'])){
			$sql .=" AND (p.image is not null AND p.image != '')";
		}
		
		if(isset($data['feature_image'])&& !is_null($data['feature_image'])){
			$sql .=" AND p.feature_image=".(int)$data['feature_image'];
		}

		if(isset($data['featured'])&& !is_null($data['featured'])){
			$sql .=" AND p.featured=".(int)$data['featured'];
		}

		$sort_data = array(
			'p.date_added',
			'pd.name',
			'p.status',
			'p.sort_order',
			'p.viewed'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.sort_order ASC,p.date_added DESC,pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if (!isset($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}

			if (!isset($data['limit']) || $data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
						
		$query = $this->db->query($sql);

		return $query->rows;
	}
	


	public function getArticleByKeyword($keyword) {
		if ($keyword) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article p WHERE p.status=1 AND  p.language_id = '" . (int)$this->config->get('config_language_id') . "' AND (LCASE(p.title) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' )");

			return $query->rows;
		} else {
			return array();
		}
	}

	public function getArticleByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX 
		. "article_description pd ON (p.article_id=pd.article_id) LEFT JOIN " . DB_PREFIX . "article_to_category p2c ON (p.article_id = p2c.article_id) WHERE p.status=1  AND  pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.article_category_id = '" . (int)$category_id . "' ORDER BY p.date_added DESC ,pd.name ASC");
		
		return $query->rows;
	}

	public function getTotalArticleByCategoryId($category_id) {
		$sql="SELECT count(*) as total FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX 
		. "article_description pd ON (p.article_id=pd.article_id) LEFT JOIN " . DB_PREFIX . "article_to_category p2c ON (p.article_id = p2c.article_id) WHERE p.status=1 AND  pd.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND p2c.article_category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC";
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getArticleContent($article_id) {
		$article_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article WHERE p.status=1  AND article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_data[$result['language_id']] = array(
				'title'             => $result['title'],
				'meta_keywords'    => $result['meta_keywords'],
				'meta_description' => $result['meta_description'],
				'content'      => $result['content']
			);
		}

		return $article_data;
	}

	public function getArticleCategories($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_category WHERE article_id = '" . (int)$article_id . "'");
		return $query->rows;
	}

	public function getArticleCategory($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_category WHERE article_id = '" . (int)$article_id . "' LIMIT 1 ");
		return $query->row;
	}
	
	public function getArticleRelated($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "'");
		return $query->rows;
	}
	// TODO get all download files
	public function getArticleDownloads($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_download WHERE article_id = '" . (int)$article_id . "'");
		return $query->rows;
	}

	public function getArticleDownload($download_id) {
		$query = $this->db->query("SELECT d.*,dd.name FROM " . DB_PREFIX . "download d LEFT JOIN  download_description dd ON d.download_id=dd.download_id WHERE dd.language_id= '".(int)$this->config->get('config_language_id')."'  AND d.download_id = '" . (int)$download_id . "'");
		return $query->row;
	}
	
	public function updateRemaining($download_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "download SET remaining = (remaining - 1) WHERE download_id = '" . (int)$download_id . "'");
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

	public function getTotalArticle($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article pd WHERE  pd.status=1   AND  pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND pd.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getLatestArticle($limit=1){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article WHERE status = '1' AND language_id='" . (int)$this->config->get('config_language_id') . "' order by date_added DESC,article_id DESC limit ".$limit);
		return $query->rows;
	}
	
	public function getArticleLayoutId($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		 
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_information');
		}
	}	
	
	public function updateViewed($article_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "article SET viewed = (viewed + 1) WHERE article_id = '" . (int)$article_id . "'");
	}
}
?>