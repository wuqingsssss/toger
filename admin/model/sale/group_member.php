<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelSaleGroupMember extends Model {
	
	
	public function get_member_by_cids($cids){
		if(empty($cids)){
			return false;
		}
		$str = implode(',', $cids);
		$in_str = " ({$str}) ";
		$sql = "select * from ".DB_PREFIX."group_member where c_id in {$in_str}";
		$query = $this->db->query($sql);
		$list = $query->rows;
		$order_ids = array_column($list, 'order_id');
//		$order_sql = "select "
		
		foreach($cids as $cid){
			foreach($list as $li){
				if($cid == $li['c_id']){
					$return[$cid][] = $li;
				}
			}
		}
		return $return;
	}
	public function get_by_order_id($order_id){
		if(empty($order_id)){
			return false;
		}
		$sql = "select * from ".DB_PREFIX."group_member where order_id = '{$order_id}'";
		$query = $this->db->query($sql);
		return $query->row;
	}
}