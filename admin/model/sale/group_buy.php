<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelSaleGroupBuy extends Model {

	var $table = "ts_group_buy";
	const NORMAL = 1;
	const IS_DEL = -1;

	/**
	 * 团购基本信息列表
	 * @param type $start
	 * @param type $limit
	 * @return type
	 */
	public function group_list($start = 1, $limit = 20) {

		$sql = "SELECT * FROM ts_group_buy  WHERE `status` != -1 ORDER BY g_id desc limit {$start},{$limit} ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	/**
	 * 团购信息总数
	 * @return type
	 */
	public function count_list(){
		$sql = "select g_id from {$this->table}";
		
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	/**
	 * 插入数据
	 * @param array $data
	 * @return type
	 */
	public function insert_data($data){
	    $pendingData = array(
	        array('name', $data['name'], true, true),
	        array('`desc`', $data['desc'], true, true),
	        array('image', $data['image'], true, true),
	        array('sell_price', floatval($data['sell_price']), false, true),
	        array('quantity',  $data['quantity'], true, true),
	        array('member_num', $data['member_num'], true, true),
	        array('group_num', $data['group_num'], true, true),
	        array('start_time', $data['start_time'], true, true),
	        array('end_time', $data['end_time'], true, true),
	        array('`status`', $data['status'], true, true),
	        array('rich_text', $data['rich_text'], true, true),
	        array('send_time', $data['send_time'], true, true),
	        array('product_id', $data['product_id'], true, true),
	        array('duration',   $data['duration'], true, true),
	        array('`share_title`', $data['share_title'], true, true),
	        array('`share_desc`', $data['share_desc'], true, true),
	        array('`share_image`', $data['share_image'], true, true),
	      
	    );
	    DbHelper::insert('group_buy', $pendingData);
	    
		return $this->db->getLastId();
	}
	
	/**
	 * 更新数据
	 * @param type $id
	 * @param type $data
	 */
	public function update_data($id, $data){
	    if( !$id ){
	        return false;
	    }
	        
	    $pendingData = array(
            array('name', $data['name'], true, true),
	        array('`desc`', $data['desc'], true, true),
	        array('image', $data['image'], true, true),
	        array('sell_price', floatval($data['sell_price']), false, true),
	        array('quantity',  $data['quantity'], true, true),
	        array('member_num', $data['member_num'], true, true),
	        array('group_num', $data['group_num'], true, true),
	        array('start_time', $data['start_time'], true, true),
	        array('end_time', $data['end_time'], true, true),
	        array('`status`', $data['status'], true, true),
	        array('rich_text', $data['rich_text'], true, true),
	        array('send_time', $data['send_time'], true, true),
	        array('product_id', $data['product_id'], true, true),
	        array('duration',  $data['duration'], true, true),
	        array('`share_title`', $data['share_title'], true, true),
	        array('`share_desc`', $data['share_desc'], true, true),
	        array('`share_image`', $data['share_image'], true, true),
	    );
	    
	    $pkData = array(
	        array('g_id', $id, true, true)
	    );
	    
	    DbHelper::update('group_buy', $pkData, $pendingData);		
	    
	    return true;
	}
	
	/**
	 * 团购信息
	 * @param type $id
	 */
	public function get_group_info($id) {
		if(empty($id)){
			return false;
		}
		
		$sql = "select * from {$this->table} where g_id = {$id}";
		$query = $this->db->query($sql);
//		var_dump($query->row);exit;
		$info = $query->row;
		if(!empty($info)){
			$name_sql = "select name from ".DB_PREFIX."product_description where product_id = {$info['product_id']}";
		}
		$n_query = $this->db->query($name_sql);
		$name = $n_query->row;
		$info['p_name'] = $name['name'];
		return $info;
	}

	/**
	 * 删除 团购信息
	 */
	public function del_group($ids){
		if(empty($ids)){
			return false;
		}
		$str = implode(',', $ids);
		$in_str = " ({$str}) ";
		$sql = "update {$this->table} set status = -1  where g_id in {$in_str}";
//		echo $sql;exit;
		$this->db->query($sql);
		return $this->db->countAffected();
	}
	/**
	 * 根据所给字段 格式化 insert 数据 
	 * @param type $data
	 */
	private function format_insert_data($data, $field_arr) {
		if (empty($data)) {
			return false;
		}
		foreach ($data as $k => $v) {
			if (in_array($k, $field_arr)) {
				$str .= $k . "= '{$v}' ,";
			}
		}
		return rtrim($str, ',');
	}
	/**
	 * 拼接插入sql
	 * @param type $arr
	 */
	private function format_data($arr){
		$count = count($arr);
		$i = 0;
		foreach($arr as $key => $a){
			$i++;
			if($i == $count){
				$str .= "`{$key}` = '$a'";
			}else{
				$str .= "`{$key}` = '$a',";
			}
		}
		return $str;
	}
}
