<?php

/*---nick
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelCatalogQuestion extends Model {
	
	const VIEWABLE = 1;//status 为正常状态
	const NORMAL = 3; //常见问题 id
	
	/**
	 * 问题列表页
	 * @param type $type
	 * @param type $start
	 * @param type $limit
	 * @return type
	 */

	public function get_list($start = 0, $limit = 10, $order = '') {
			$pre = DB_PREFIX;
			$sql = "select * from {$pre}qa where status = 1 limit {$start}, {$limit} ";
			if(!empty($order)){
				$sql .= "order by {$order}";
			}
			$query = $this->db->query($sql);
			return $query->rows;
	}

	/**
	 * 获取所有type分类下的 记录总数 分页用
	 * @param type $type
	 * @return type
	 */
	public function get_list_count() {
			$pre = DB_PREFIX;
			$sql = "select * from {$pre}qa where 1=1 ";
			//where 条件
			$sql .= 'and status = '.self::VIEWABLE;
			$query = $this->db->query($sql);
			return $query->num_rows;
	}
	/**
	 * 获取问题信息
	 * @param type $q_id
	 */
	public function get_question_info($q_id){
		if(empty($q_id)){
			return false;
		}
		$pre = DB_PREFIX;
		$sql = "select * from {$pre}qa where qa_id = {$q_id} and status = ".self::VIEWABLE;
		$query = $this->db->query($sql);
		return $query->row;
	}
	/**
	 * 获取分类列表
	 * 如果没有指定分类 获取所有分类列表
	 * @param type $c_id
	 */
	public function get_cat_list($c_id){
		if(empty($c_id)){
			$c_id = 1;
		}
		$pre = DB_PREFIX;
		$sql = "select * from {$pre}qa_catagory where 1=1 ";
		$sql .= "and status = ".self::VIEWABLE;
		if($c_id != 'all'){
			$sql .= " and parent_id = {$c_id} ";
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	/**
	 * 插入数据
	 * @param array $data
	 * @return type
	 */
	public function insert_data($data){
		$pre = DB_PREFIX;
		$data['status'] = self::VIEWABLE;
		$str = $this->format_data($data);
		$sql = "insert {$pre}qa set {$str}";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	/**
	 * 更新数据
	 * @param type $id
	 * @param type $data
	 */
	public function update_data($id, $data){
		if(empty($id)){
			return false;
		}
		$pre = DB_PREFIX;
		$str = $this->format_data($data);
		$sql = "update {$pre}qa set {$str} where qa_id = {$id}";
		return $this->db->query($sql);
		
	}
	//批量删除记录
	public function del_data($ids){
		if(empty($ids)){
			return false;
		}
		$pre = DB_PREFIX;
		$str = '(';
		$str .= implode(',', $ids);
		$str .=')';
		$sql = "delete from {$pre}qa where qa_id in " . $str;
		$this->db->query($sql);
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
				$str .= "{$key} = '$a'";
			}else{
				$str .= "{$key} = '$a',";
			}
		}
		return $str;
	}
}
