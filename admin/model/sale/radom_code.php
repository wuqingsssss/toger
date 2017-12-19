<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelSaleRadomCode extends Model {
	
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
}