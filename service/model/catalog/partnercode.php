<?php 
class ModelCatalogPartnerCode extends Model {
	const NORMAL_STATUS = 1;//启用状态
	
	public function get_platform_list(){
		$sql="SELECT * FROM " . DB_PREFIX ."partner_code WHERE `status` = " . self::NORMAL_STATUS . " order by sort_order , id";
		
		$query = $this->db->query($sql);

		if ($query->num_rows > 0) {
			return $query->rows;
		} else {
			return false;
		}
	}
	
}