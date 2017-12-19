<?php

class ModelCatalogSupplyPeriod extends Model {
    
	public function getSupplyPeriod($period_id){
		$sql="SELECT * FROM " . DB_PREFIX . "supply_period WHERE id=".(int)$period_id;
	
		$query=$this->db->query($sql);
	
		return $query->row;
	}
	
	private function filterSql($data = array()){
		$sql = "";
		if(isset($data['filter_show_date'])&&!is_null($data['filter_show_date'])){
			$sql .= " and sp.end_date>=DATE('".$data['filter_show_date']."') and sp.start_date<=DATE('".$data['filter_show_date']."')";
		}
		return $sql;
	}
	
	public function getSupplyPeriods($data = array(),$rtype=0){
		$sql ="select * from ".DB_PREFIX."supply_period sp where 1=1";
		
		$sql .= $this->filterSql($data);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY sort_order ASC, sp.start_date";	
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
		$timenow=strtotime(date('Y-m-d', time()));
		
		foreach ($query->rows as $key=>$supply_period) {
			
			$picktime=$this->getPickTimeOptions($query->rows[$key]);
			
			if(!$picktime){unset($query->rows[$key]); continue;}
			$query->rows[$key]['ps_start_date']=$supply_period['start_date'];//date('Y-m-d H:i:s',strtotime($supply_period['p_start_date']));
			$query->rows[$key]['ps_end_date']  =$supply_period['end_date'];//date('Y-m-d H:i:s',strtotime($supply_period['p_end_date']));
			$query->rows[$key]['name']=($timenow>=strtotime($query->rows[$key]['ps_start_date']) && $timenow<=strtotime($query->rows[$key]['ps_end_date']))? $supply_period['name2']:$supply_period['name'];
			$query->rows[$key]['href']=$this->url->link('common/home','sequence='.$key,'SSL');
			$query->rows[$key]['picktime']=$picktime;
			$query->rows[$key]['template']=$supply_period['template'];
			$query->rows[$key]['info']=html_entity_decode($supply_period['info'], ENT_QUOTES, 'UTF-8');
			$res['p'.$supply_period['id']]=$query->rows[$key];
		}

			
		return $rtype?$res: $query->rows;
	}

	
	// 获得取菜时间列表
	public function getPickTimeOptions($peroid) {
		$options = array();
	
		if($peroid){
			$start = $peroid['p_start_date'];
			$end  =  $peroid['p_end_date'];
			$p_start_date = date('Y-m-d', strtotime($start));
			$starttime = strtotime($p_start_date);
				
			$p_end_date = date('Y-m-d', strtotime($end));
			$endtime = strtotime($p_end_date);
				
			$current = date('Y-m-d', time()+86400);
			$now = strtotime($current);
				
			if($starttime<$now){
				$starttime= $now;
			}
				
			if($starttime> $endtime )
			{
				return $options;
			}
				
			for ($i=$starttime;$i<=$endtime; $i= $i + 86400){
				$options[] = date('Y-m-d', $i);
			}
		}
		return $options;
	}
}

?>