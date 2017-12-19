<?php
class ModelPromotionPromotion extends Model {

	/**
	 * 获取符合条件的促销活动总数
	 * @param unknown_type $filter
	 */
	public function getTotalPromotions($filter=array())
	{
		$sql = "SELECT count(*) AS total FROM ".DB_PREFIX."p_basic pb left join ".DB_PREFIX."p_description pd ON (pd.pb_id= pb.pb_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pb.status='0' ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	/**
	 * 获取符合条件的所有促销记录
	 * @param unknown_type $filter
	 */
	public function getPromotions($data=array())
	{
		$sql = "SELECT pb.pb_id,pb.pb_key,pd.pb_name,pb.start_time,pb.end_time FROM ".DB_PREFIX."p_basic pb left join ".DB_PREFIX
		."p_description pd ON (pd.pb_id= pb.pb_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') 
		. "' AND pb.status='0' ";

		
		if(isset($data['filter_datetime']) && !is_null($data['filter_datetime'])){
			$sql.=" AND (pb.end_time = '0000-00-00' OR pb.end_time >= NOW())";
		}

		$sort_data = array(
			'pb.pb_id',
		);
		$sql .= $this->initLimitOrder($data, $sort_data);

		$query = $this->db->query($sql);

		return $query->rows;

	}

	
	/**
	 * 获取促销信息
	 * @param unknown_type $data
	 */
	public function getPromotionRule($pr_id){
		if($pr_id)
		{
			$sql = "SELECT pr.*,p.pb_name,p.pb_key FROM ".DB_PREFIX."p_rule pr LEFT JOIN  ".DB_PREFIX."p_basic p ON pr.pb_id=p.pb_id  WHERE pr.pr_id ='".(int)$this->db->escape($pr_id)."'";
			$query = $this->db->query($sql);
			return  $query->row;
	
		}
		return false;
	}
	
	/**
	 * 获取促销信息
	 * @param unknown_type $data
	 */
	public function getPromotionPrs($pb_id){
		if($pb_id)
		{
			$sql = "SELECT * FROM ".DB_PREFIX."p_rule  WHERE pb_id ='".(int)$this->db->escape($pb_id)."' ORDER BY sort_order";
			$query = $this->db->query($sql);
			return  $query->rows;
				
		}
		return false;
	}
	
	/**
	 * 获取促销信息
	 * @param unknown_type $data
	 */
	public function getPromotionInfo($data){
		if(isset($data['pb_id']))
		{
			$sql = "SELECT pb.pb_name,pb.pb_key,pb.start_time,pb.end_time,pb.total,pd.* FROM ".DB_PREFIX."p_basic pb left join ".DB_PREFIX."p_description pd ON (pd.pb_id= pb.pb_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pb.pb_id ='".(int)$this->db->escape($data['pb_id'])."'";
			$sort_data = array(
				'pb.pb_id',
			);
			$sql .=$this->filterSql($data);
			$query = $this->db->query($sql);
			
			$result = $query->row;
		
				return $result;
			
		}
		return false;
	}

	public function insert($data)
	{
		if(isset($data['pb_name']))
		{
			$sql = "INSERT INTO ".DB_PREFIX."p_basic  SET pb_name='".$this->db->escape(mb_strtolower($data['pb_name'], 'UTF-8'))."'";
				
			if(isset($data['start_time']))
			{
				$sql .= " , start_time='".$data['start_time']."' ";
			}
			if(isset($data['pb_key']))
			{
				$sql .= " , pb_key='".$data['pb_key']."' ";
			}
				
			if(isset($data['end_time']))
			{
				$sql .= " , end_time='".$data['end_time']."' ";
			}
			
				
			$this->db->query($sql);

			$lastId = $this->db->getLastId();
				
			$this->insertPRule($data, $lastId);
				
			$this->insertDescription($data, $lastId);
		}

	}

	public function delete($pb_id)
	{
		$this->deletepd($pb_id);
		//		$this->deletePb($pb_id);
		//		$this->deletePr($pb_id);
	}

	public function update($data)
	{
		if(isset($data['pb_id']))
		{
			$this->updatePb($data);
			$this->updatePr($data);
			$this->updatePd($data);
		}
	}


	/**
	 * 保存描述信息
	 * @param unknown_type $data
	 * @param unknown_type $pb_id
	 */
	public function insertDescription($data,$pb_id)
	{
		if(isset($pb_id))
		{
			$sql = "INSERT INTO ".DB_PREFIX."p_description SET pb_id ='".$pb_id."' ,language_id ='".(int)$this->config->get('config_language_id')."'";

			if(isset($data['pb_name']))
			{
				$sql .= " , pb_name ='".$this->db->escape(mb_strtolower($data['pb_name'], 'UTF-8'))."'";
			}
			if(isset($data['share_title']))
			{
				$sql .= " , share_title='".$data['share_title']."' ";
			}
			if(isset($data['share_desc']))
			{
				$sql .= " , share_desc='".$data['share_desc']."' ";
			}
			if(isset($data['share_image']))
			{
				$sql .= " , share_image='".$data['share_image']."' ";
			}
			if(isset($data['share_link']))
			{
				$sql .= " , share_link='".$data['share_link']."' ";
			}
			if(isset($data['template']))
			{
				$sql .= " ,template='".$data['template']."' ";
			}
			if(isset($data['page_header']))
			{
				$sql .= " ,page_header='".$data['page_header']."' ";
			}
			if(isset($data['page_footer']))
			{
				$sql .= " ,page_footer='".$data['page_footer']."' ";
			}
				
			$this->db->query($sql);
		}
	}

	/**
	 * 保存促销活动规则信息
	 * @param unknown_type $data
	 */
	public function insertPRule($data,$pb_id)
	{
		$data['pb_id']=$pb_id;
	    $this->updatePr($data);
	}

	/**
	 * 促销活动产品关联表
	 * @param unknown_type $data
	 */
	public function insertPRToProduct($data)
	{
		if(isset($data['pr_id'])&&isset($data['product_id']))
		{
			$sql = "INSERT INTO ".DB_PREFIX."pr_to_product prp SET prp.pr_id ='".(int)$this->db->escape($data['pb_name'])."' ,prp.product_id = '".(int)$this->db->escape($data['product_id'])."'";
			$this->db->query($sql);
		}
	}


	/**
	 * 删除基本信息
	 * Enter description here ...
	 * @param unknown_type $pb_id
	 */
	public function deletePb($pb_id)
	{
		if(isset($pb_id))
		{
			$sql = "UPDATE ".DB_PREFIX."p_basic SET status='1' where pb_id ='".(int)$pb_id."'";
			$this->db->query($sql);
		}
	}

	/**
	 * 删除规则信息
	 * Enter description here ...
	 * @param unknown_type $pb_id
	 */
	public function deletePr($pb_id)
	{
		if(isset($pb_id))
		{
			$sql = "DELETE FROM ".DB_PREFIX."p_rule where pb_id ='".(int)$pb_id."'";
			$this->db->query($sql);
		}
	}


	/**
	 * 删除描述信息
	 * Enter description here ...
	 * @param unknown_type $pb_id
	 */
	public function deletepd($pb_id)
	{
		if(isset($pb_id))
		{
			$sql = "DELETE FROM ".DB_PREFIX."p_description where pb_id ='".(int)$pb_id."'";
			$this->db->query($sql);
		}
	}


	/**
	 * 更新基本信息
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function updatePb($data)
	{
		if(isset($data['pb_id']))
		{
			$sql = "UPDATE ".DB_PREFIX."p_basic set pb_name ='".$this->db->escape(mb_strtolower($data['pb_name'], 'UTF-8'))."'";
			
			if(isset($data['pb_key']))
			{
				$sql .= " , pb_key='".$data['pb_key']."' ";
			}
			if(isset($data['start_time']))
			{
				$sql .= " ,start_time='".$data['start_time']."'";
			}
				
			if(isset($data['end_time']))
			{
				$sql .= " ,end_time='".$data['end_time']."'";
			}
				
			$sql .=" WHERE pb_id ='".(int)$data['pb_id']."'";
			
			$this->db->query($sql);
		}
	}

	/**
	 * 更新规则信息
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function updatePr($data)
	{
	  if (isset($data['pr'])&&$data['pb_id']) {	
			$sql='';
			$oldid=array();
			foreach ($data['pr'] as $value) {			
				if($value['id']>0)
				{$oldid[]=$value['id'];
				$sql.="UPDATE " . DB_PREFIX . "p_rule SET pb_id='" .(int)$data['pb_id'] . "',pr_rule='" .$value['rule'] . "',pr_code ='" .$value['code'] . "',pr_banner ='" .$value['banner'] . "',sort_order ='" .$value['sort_order'] . "',pr_group ='" .$value['group'] . "' WHERE pr_id='".$value['id']."';";		
				}
				else{
				$sql.="INSERT INTO " . DB_PREFIX . "p_rule SET pb_id ='" .(int)$data['pb_id'] . "',pr_rule='" .$value['rule'] . "',pr_code ='" .$value['code'] . "',pr_banner ='" .$value['banner'] . "',sort_order ='" .$value['sort_order'] . "',pr_group ='" .$value['group'] . "';";
				}
			}
if($oldid){
			$sql="DELETE FROM " . DB_PREFIX . "p_rule WHERE pb_id='".$data['pb_id']."' AND pr_id not in(" . implode(',', $oldid) . ");"
				.$sql
				."DELETE FROM " . DB_PREFIX . "p_to_product WHERE pr_id not in(SELECT pr_id FROM p_rule WHERE pb_id='".$data['pb_id']."');"
			    ;
}

			$this->db->multi_query($sql);			
		}

	}
	/**
	 * 更新描述信息
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function updatePd($data)
	{
		if(isset($data['pb_id']))
		{
			$sql = "UPDATE ".DB_PREFIX."p_description set pb_name ='".$this->db->escape(mb_strtolower($data['pb_name'], 'UTF-8'))."'";
		
			if(isset($data['share_title']))
			{
				$sql .= ",share_title='".$data['share_title']."' ";
			}	
			if(isset($data['share_desc']))
			{
				$sql .= " , share_desc='".$data['share_desc']."' ";
			}
			if(isset($data['share_image']))
			{
				$sql .= " , share_image='".$data['share_image']."' ";
			}
			if(isset($data['share_link']))
			{
				$sql .= " , share_link='".$data['share_link']."' ";
			}
			if(isset($data['template']))
			{
				$sql .= " ,template='".$data['template']."' ";
			}
			if(isset($data['page_header']))
			{
				$sql .= " ,page_header='".$data['page_header']."' ";
			}
			if(isset($data['page_footer']))
			{
				$sql .= " ,page_footer='".$data['page_footer']."' ";
			}
			
			$sql.=" WHERE pb_id ='".(int)$data['pb_id']."' AND language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$this->db->query($sql);
		}
	}



	private function filterSql($filter)
	{
		$sql = "";
		
		if(isset($filter['pb_name'])){
			$sql .= " AND pb.pb_name LIKE  '%" . $this->db->escape(mb_strtolower($data['pb_name'], 'UTF-8'))."%' ";
		}
		return $sql;
	}




	private function initLimitOrder($filter,$sort_data)
	{
		$sql = "";
		if (isset($filter['sort']) && in_array($filter['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $filter['sort'];
		} else {
			$sql .= " ORDER BY pb.pb_id";
		}

		if (isset($filter['order']) && ($filter['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " DESC";
		}

		if (isset($filter['start']) || isset($filter['limit'])) {
			if ($filter['start'] < 0) {
				$filter['start'] = 0;
			}

			if ($filter['limit'] < 1) {
				$filter['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
		}
		return $sql;
	}




}
?>