<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelSaleGroupCreate extends Model {
	
	public function get_create_list($gid,$start = 0, $limit = 5, $filter = '',$arr = ''){
		if(empty($gid)){
			return false;
		}
		$sql = "select * from ".DB_PREFIX."group_create where g_id = {$gid}";
		if($filter){
			foreach($filter as $k => $v){
				$sql .= " and {$k} = '{$v}'";
			}
		}
		if($arr){
			$sql .= " order by {$arr['key']} {$arr['order']}";
		}
		$sql .= " limit {$start},{$limit}";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function get_create_count($gid,$filter = ''){
		if(empty($gid)){
			return false;
		}
		$sql = "select * from ".DB_PREFIX."group_create where g_id = {$gid}";
		if($filter){
			foreach($filter as $k => $v){
				$sql .= " and {$k} = '{$v}'";
			}
		}
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	public function get_create_info($c_id){
		if(empty($c_id)){
			return false;
		}
		$sql = "select * from ".DB_PREFIX."group_create where c_id = {$c_id}";
		$query = $this->db->query($sql);
		return $query->row;
	}
}