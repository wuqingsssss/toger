<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelSaleCouponShow extends Model {
	/**
	 * 获取搜索优惠卷列表 有效期内的
	 * @param type $key_word
	 * @param type $start
	 * @param type $limit
	 */
	public function search_list($key_word, $start = 1, $limit = 20){
		$date = date("Y-m-d", time());
		
		$sql = "select coupon_id, name, code, discount, date_start, date_end "
			. "from ".DB_PREFIX."coupon where 1=1 ";
		if (!empty($key_word)) {
			$sql .= "and (name like '%{$key_word}%' or code like '%{$key_word}%') ";
		}
		$sql .= " order by coupon_id desc limit {$start},{$limit}";
//		echo $sql;exit;
		$query = $this->db->query($sql);
		return $query->rows;
	}
	/**
	 * 返回搜索记录数
	 * @param type $key_word
	 * @return type
	 */
	public function count_search_list($key_word){
		$date = date("Y-m-d", time());
		
		$sql = "select coupon_id, name, code, discount, date_start, date_end "
			. "from ".DB_PREFIX."coupon where '{$date}' >= date_start and '{$date}' <= date_end ";
		if (!empty($key_word)) {
			$sql .= "and (name like '%{$key_word}%' or code like '%{$key_word}%')";
		}
		
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	/**
	 * 选取库内优惠卷列表
	 * @param type $type  -1--失效 1--正常显示 2--未开始
	 * @param type $start
	 * @param type $limit
	 */
	public function show_list($type = 1, $start = 1, $limit = 20){
		$date = date('Y-m-d', time());
		if(!in_array($type, array(1,-1,2))){
			return false;
		}
		if($type == -1){
			$where = "'{$date}' > end_time";
		}
		if($type == 1){
			$where = "'{$date}' >= start_time and '{$date}' <= end_time";
		}
		if($type == 2){
			$where = "'{$date}' < start_time";
		}
		$sql = "select * from ".DB_PREFIX."coupon_show where {$where} order by show_id desc "
		. "limit {$start},{$limit}";
//		echo $sql;exit;
		$query = $this->db->query($sql);
		$data = $query->rows;
		if(empty($data)){
			return false;
		}
		//去coupon表取详细信息
		$cids = array_column($data, 'coupon_id');
		$str = implode(',', $cids);
		$in_str = " ({$str}) ";
		$sql = "select coupon_id, name, code, discount,date_start,date_end  from ".DB_PREFIX."coupon where coupon_id in {$in_str}";
		$query = $this->db->query($sql);
		$infos = $query->rows;
		
		//合并信息
		foreach($data as &$da){
			foreach($infos as $in){
				if($da['coupon_id'] == $in['coupon_id']){
					$da['info'] = $in;
				}
			}
		}
		return $data;
	}
	/**
	 * 获取优惠卷数目
	 * @param type $type
	 * @return type
	 */
	public function count_show_list($type){
		$date = date('Y-m-d', time());
		if(!in_array($type, array(1,-1,2))){
			return false;
		}
		if($type == -1){
			$where = "'{$date}' > end_time";
		}
		if($type == 1){
			$where = "'{$date}' >= start_time and '{$date}' <= end_time";
		}
		if($type == 2){
			$where = "'{$date}' < start_time";
		}
		
		$sql = "select * from ".DB_PREFIX."coupon_show where {$where} order by show_id desc ";
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	/**
	 * 停用或启用 显示优惠卷信息
	 * @param type $ids
	 */
	public function audit_show($ids, $type){
		if(empty($ids) || empty($type)){
			return false;
		}
		if(!in_array($type, array(1,-1))){
			return false;
		}
		
		$str = implode(',', $ids);
		$in_str = " ({$str}) ";
		$sql = "update ".DB_PREFIX."coupon_show set status = {$type} where show_id in {$in_str}";
//		echo $sql;exit;
		$this->db->query($sql);
		return $this->db->countAffected();
	}
	/**
	 * 添加显示优惠卷列表  单条记录
	 * @param type $data
	 */
	public function add_to($data){
		if(empty($data)){
			return false;
		}
		//拼接入库 字符串 键值对
		$fields = array('coupon_id','code','start_time','endtime');
		$str = $this->format_insert_data($data, $fields);
		
		$sql = "insert ".DB_PREFIX."coupon_show set {$str}";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	/**
	 * 添加多条记录
	 * @param type $data
	 */
	public function add_datas($data){
		if(empty($data)){
			return false;
		}
		//判断数据是否存在 如果已经添加 则排除
		$cids = array_column($data, 'coupon_id');
		$str = implode(',', $cids);
		$in_str = " ({$str}) ";
//		echo $in_str;
		$sql = "select * from ".DB_PREFIX."coupon_show where coupon_id in {$in_str}";
//		echo $sql;exit; 
		$query = $this->db->query($sql);
		if($query->rows){
			foreach($query->rows as $r){
				unset($data[$r['coupon_id']]);
			}
		}
		if(empty($data)){
			return false;
		}
		//拼接插入数据字符串
		$fields = array('coupon_id','code','start_time','end_time');
		$field_str = implode(',', $fields);
		$str = $this->format_insert_datas($data, $fields);
		$sql = "insert ".DB_PREFIX."coupon_show ({$field_str}) values {$str}";
		$this->db->query($sql);
		return $this->db->countAffected();
	}
	/**
	 * 
	 * @param type $ids
	 * @param type $status 1--正常  -1 停用
	 */
	public function coupon_act($ids, $status = 1){
		
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