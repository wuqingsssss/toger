<?php
class ModelDesignLayout extends Model {	
	public function getLayout($route) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route lr LEFT JOIN " . DB_PREFIX . "layout l ON (lr.layout_id=l.layout_id) WHERE '" . $this->db->escape($route) 
		. "' LIKE CONCAT(route, '%') AND store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY priority DESC,route ASC LIMIT 1");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;	
		}
	}
	public function getLayoutModules($layout_id, $position,$template='default') {
		$sql="SELECT * FROM " . DB_PREFIX . "layout_module";
		$sql.=" WHERE layout_id = '" . (int)$layout_id . "' AND position = '" . $this->db->escape($position) . "'";
		if($template)
			$sql.=" AND template='{$template}'";
		$sql.=" ORDER BY sort_order";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getModules($code,$template='default') {
		$sql="SELECT * FROM " . DB_PREFIX . "layout_module";
		$sql.=" WHERE code = '" . $code . "'";
		if($template)
			$sql.=" AND template='{$template}'";
		$sql.=" ORDER BY sort_order";
		$query = $this->db->query($sql);
		return $query->rows;
	}
}
?>