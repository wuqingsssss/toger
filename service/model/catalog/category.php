<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row;
	}
	/*qpiv2*/
	public function getCategoryByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)  WHERE c.code = '" . $code . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status = '1'");
		
		return $query->row;
	}
	
	public function getCategories($parent_id = 0) {
		$sql="SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, c.category_id ASC";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	
	/*apiv2*/
		public function getTotalCategories($parent_id = 0){
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)  WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status = '1'";
		$sql .= " AND c.parent_id = '" . (int)$parent_id. "'";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getSubCategories($parent_id){
		$sql="SELECT category_id FROM " . DB_PREFIX . "category c WHERE c.parent_id = '" . (int)$parent_id . "'  AND c.status = '1' ORDER BY c.sort_order";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getChildCategories($parent_id=0,$recursion=TRUE){
		$data=array(
			'parent_id' => $parent_id,
			'language_id' => $this->config->get('config_language_id'),
			'recursion' => $recursion
		);
		
		$cache = md5(http_build_query($data));
		
		$cache_data = $this->cache->get('category.' . $cache);
		
		if(!$cache_data){
			$sql="SELECT c.category_id AS category_id,cd.name AS name,c.image FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') 
			. "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order ASC,c.category_id ASC";
			
			$query=$this->db->query($sql);
			
			$categorys=array();
			
			foreach($query->rows as $result){
				$children_data = array();
					
				if($recursion){
					
				
				$children = $this->getChildCategories($result['category_id']);
					foreach ($children as $child) {
						$children_data[] = array(
							'category_id' => $child['category_id'],
							'name'        => $child['name'] ,
							'children'    => $child['children'] ,
							'image'       => $child['image']?HTTP_IMAGE. $child['image']:'',
							'href'        => $this->url->link('product/category', 'path='.$result['category_id'].'_'. $child['category_id'])	
						);				
					}
				}
				
				$categorys[]=array(
					'category_id' => $result['category_id'],
					'name'        => $result['name'] ,
					'image'        =>$result['image']?HTTP_IMAGE. $result['image']:'', 
					'children'    => $children_data,
					'href'        => $this->url->link('product/category', 'path=' . $result['category_id'])
				);
			}
			
			if($categorys){
				$this->cache->set('category.' . $cache, $categorys);
			}
			
			return $categorys;
		}
		
		return $cache_data;
	}
	
	
	public function getCategoriesByParentId($category_id) {
		$category_data = array();
		
		$category_data[] = $category_id;
		
		$category_query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$category_id . "'");
		
		foreach ($category_query->rows as $category) {
			$children = $this->getCategoriesByParentId($category['category_id']);
			
			if ($children) {
				$category_data = array_merge($children, $category_data);
			}			
		}
		
		return $category_data;
	}
		
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_category');
		}
	}
					
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
	
	public function getCategoryManufacturers($category_id){
		$sql="SELECT DISTINCT p.manufacturer_id FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX 
		. "product p ON (p2c.product_id=p.product_id) WHERE 1=1";
		
		$implode_data = array();
		
		$implode_data[] = "p2c.category_id = '" . (int)$category_id. "'";
		
		$categories = $this->getCategoriesByParentId($category_id);
	
		foreach ($categories as $category) {
			$implode_data[] = "p2c.category_id = '" . (int)$category. "'";
		}
						
		$sql .= " AND p.product_id IN (SELECT p2c.product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE " . implode(' OR ', $implode_data) . ")";			
		
		$query=$this->db->query($sql);
		
		return $query->rows;
	}
	/*apiv2*/
	public function getAllProducts($data=array()){
		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)  WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() "; 
			
		if (isset($data['filter_name']) && $data['filter_name']) {
			if (isset($data['filter_description']) && $data['filter_description']) {
				$sql .= " AND (LCASE(pd.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%' OR p.product_id IN (SELECT pt.product_id FROM " . DB_PREFIX . "product_tag pt WHERE pt.language_id = '" . (int)$this->config->get('config_language_id') . "' AND LCASE(pt.tag) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%') OR LCASE(pd.description) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%')";
			} else {
				$sql .= " AND (LCASE(pd.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%' OR p.product_id IN (SELECT pt.product_id FROM " . DB_PREFIX . "product_tag pt WHERE pt.language_id = '" . (int)$this->config->get('config_language_id') . "' AND LCASE(pt.tag) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'))";
			}
		}
			
		if (isset($data['filter_tag']) && $data['filter_tag']) {
			$sql .= " AND p.product_id IN (SELECT pt.product_id FROM " . DB_PREFIX . "product_tag pt WHERE pt.language_id = '" . (int)$this->config->get('config_language_id') . "' AND LCASE(pt.tag) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_tag'], 'UTF-8')) . "%')";
		}
										
		if (isset($data['filter_category_id']) && $data['filter_category_id']) {
			if (isset($data['filter_sub_category']) && $data['filter_sub_category']) {
				$implode_data = array();
				
				$this->load->model('catalog/category');
				
				$categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);
				
				foreach ($categories as $category_id) {
					$implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
				}
				
				$sql .= " AND p.product_id IN (SELECT p2c.product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE " . implode(' OR ', $implode_data) . ")";			
			} else {
				$sql .= " AND p.product_id IN (SELECT p2c.product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE p2c.category_id = '" . (int)$data['filter_category_id'] . "')";
			}
		}
			
		if (isset($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		$sql .= " GROUP BY p.product_id";
		
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
//		if (isset($data['start']) || isset($data['limit'])) {
//			if ($data['start'] < 0) {
//				$data['start'] = 0;
//			}				
//
//			if ($data['limit'] < 1) {
//				$data['limit'] = 20;
//			}	
//		
//			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
//		}
		echo $sql;
		
		$product_data = array();
		
		$query = $this->db->query($sql);
	
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}
			
		return $product_data;
	}
}
?>