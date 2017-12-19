<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelCampaignRadomCode extends Model {
	
	public function add_code($codes){
		if(empty($codes)){
			return false;
		}
		$fields = array('code');
		$str = $this->format_insert_datas($codes, $fields);
		$field_str = implode(',', $fields);
		$sql = "insert ".DB_PREFIX."radom_code ({$field_str}) values {$str}";
		$this->db->query($sql);
		return $this->db->countAffected();
	}
	/**
	 * 更新验证码
	 * @param type $data
	 */
	public function update($code, $data){
		if(empty($data)){
			return false;
		}
		$str = $this->format_update_data($data);
		$sql = "update ".DB_PREFIX."radom_code set {$str} where code = '{$code}'";
//		echo $sql;exit;
		$this->db->query($sql);
		return $this->db->countAffected();
	}
	
	public function check($code){
		if(empty($code)){
			return false;
		}
		$sql = "select * from ".DB_PREFIX."radom_code where code = '{$code}'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	/**
	 * 格式化 批量插入数据
	 * @param type $data 二维数组 多条数据  
	 * @param fields 要插入的字段
	 */
	private function format_insert_datas($data, $fields){
		if(empty($data) || empty($fields)){
			return '';
		}
		
		$insert_str = "(";
		foreach($data as $da){
			foreach($fields as $f){
				$insert_str .= '\''.$da[$f].'\',';
			}
			$insert_str = rtrim($insert_str,',');
			$insert_str .= ") ,(";
		}
		$insert_str = rtrim($insert_str,',(');
		return $insert_str;
	}
	
	/**
	 * 根据所给字段 格式化 insert 数据 
	 * @param type $data
	 */
	private function format_insert_data($data, $field_arr){
		if(empty($data)){
			return false;
		}
		foreach($data as $k => $v){
			if(in_array($k, $field_arr)){
				$str .= $k ."= '{$v}' ,";
			}
		}
		return rtrim($str,',');
	}
	private function format_update_data($data){
		foreach($data as $k => $v){
			$str .= " {$k} = '$v',";
		}
		return rtrim($str,',');
	}
}