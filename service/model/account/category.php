<?php
class ModelAccountCategory extends Model {
	public function addCategory($data) {
		$name='';
		$desciption = '';
		$this->db->query("INSERT INTO " . DB_PREFIX . "project_category SET parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_added = NOW(),createtor = '".$this->db->escape($this->session->data['loginUser'])."'");

		$category_id = $this->db->getLastId();

		foreach ($data['category_description'] as $language_id => $value) {
			if($this->config->get('config_language_id')==$language_id)
			{
				$name=$this->makeSlugs($this->db->escape($value['name']));
				$desciption = $this->makeSlugs($this->db->escape($value['description']));
				$this->db->query("UPDATE " .DB_PREFIX ."project_category SET category_name = '". $this->db->escape($name)."', description = '".$desciption."'  WHERE category_id = '". (int)$category_id . "'");
			}
		}

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "project_category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

	}

	public function editCategoryStatus($category_id, $status) {
		$this->db->query("UPDATE " . DB_PREFIX . "project_category SET status = '" . (int)$status. "' WHERE category_id = '" . (int)$category_id . "'");
	}

	public function editCategory($category_id, $data) {
		$name='';
		$desciption = '';
		$this->db->query("UPDATE " . DB_PREFIX . "project_category SET parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "project_category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
			if($this->config->get('config_language_id')==$language_id)
			{
				$name=$this->makeSlugs($this->db->escape($value['name']));
				$desciption = $this->makeSlugs($this->db->escape($value['description']));
				$this->db->query("UPDATE " .DB_PREFIX ."project_category SET category_name = '". $this->db->escape($name)."', description = '".$desciption."'  WHERE category_id = '". (int)$category_id . "'");
			}
		}
	}

	public function deleteCategory($category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "project_category WHERE category_id = '" . (int)$category_id . "'");

		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "project_category WHERE parent_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}

	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "project_category WHERE category_id = '" . (int)$category_id . "'");

		return $query->row;
	}

	public function getCategories($parent_id) {
		$category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "project_category c WHERE c.parent_id = '" . (int)$parent_id . "' ORDER BY c.sort_order, c.category_name ASC");

		foreach ($query->rows as $result) {
			$category_data[] = array(
					'category_id' => $result['category_id'],
					'code' => '',
					'name'        => $this->getPath($result['category_id'], $this->config->get('config_language_id')),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
			);

			$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
		}
		return $category_data;
	}

	public function getParentCategories($parent_id) {
		$category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "project_category c WHERE c.parent_id = '" . (int)$parent_id . "' ORDER BY c.sort_order, c.category_name ASC");
		return $query->rows;
	}

	

	public function getPath($category_id) {
		$query = $this->db->query("SELECT category_name, parent_id FROM " . DB_PREFIX . "project_category WHERE category_id = '".$category_id."' ORDER BY sort_order, category_name ASC");

		$category_info = $query->row;

		if ($category_info['parent_id']) {
			return $this->getPath($category_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $category_info['category_name'];
		} else {
			return $category_info['category_name'];
		}
	}

	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

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

	public function getCategoryStores($category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	public function getCategoryLayouts($category_id) {
		$category_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

		return $query->row['total'];
	}

	public function getTotalCategoriesByImageId($image_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}

	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}

	public function isHaveChild($data){
		$query = $this->db->query("select count(tp.category_id) as total from ts_project_product_to_category tp where tp.category_id = ".$data);

		return $query->row['total'];

		//		return 0;
	}
}
?>