<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelCatalogQuestion extends Model {

	const NORMAL = 3; //常见问题 id

	/**
	 * 问题列表页
	 * @param type $type
	 * @param type $start 
	 * @param type $limit
	 * @return type
	 */

	public function get_list($start=0, $limit = 10) {
		$list_cache = array('start' => $start, 'limit' => $limit);
		$cache = md5(http_build_query($list_cache));

		$qa_list = 0;//$this->cache->get('question.' . $cache);
		if (!$qa_list) {
			$pre = DB_PREFIX;
			$sql = "SELECT * FROM ts_qa tq LEFT JOIN ts_qa_catagory tqc 
			             ON tqc.qa_catagory_id=tq.catagory_id 
			             WHERE tqc.`status`=1 
			             AND tq.`status`=1  
			             ORDER BY tq.catagory_id 
			             LIMIT {$start}, {$limit}";
			$query = $this->db->query($sql);
			$res = $query->rows;
		}
		else{
		    return $qa_list;
		}
		
		if($res)
		{
		    $qa_list = array();
		    $catagory = '';
		    foreach ($res as $qa){
		        if($qa['catagory_name'] == $catagory){
    	            $qa_list[$qa['catagory_name']][] = array( 
    	                'qa_id'       => $qa['qa_id'],
    	                'description' => $qa['description'],
    	                'answer'      => $qa['answer']	                
    	            );
		        }
		        else{
		            $catagory = $qa['catagory_name'];
		            $qa_list[$catagory] = array();
		            $qa_list[$catagory][] = array(
		                'qa_id'       => $qa['qa_id'],
		                'description' => $qa['description'],
		                'answer'      => $qa['answer']
		            );
		        }
		    }
		    
		    $this->cache->set('question.' . $cache, $qa_list);
		    return $qa_list;
		}
		else{
		    return false;
		}
	}

	/**
	 * 获取所有type分类下的 记录总数 分页用
	 * @param type $type
	 * @return type
	 */
	public function get_list_count($type) {
		if (empty($type)) {
			$type = self::NORMAL;
		}
		$list_cache = array('type_count' => $type);
		$cache = md5(http_build_query($list_cache));
		$count = $this->cache->get('question.count' . $cache . '.' . $type);
		if (!$count) {
			$pre = DB_PREFIX;
			$sql = "select * from {$pre}qa where 1=1 ";
			//where 条件
			if ($type) {
				$sql .= "and catagory_id = {$type}";
			}
			$query = $this->db->query($sql);
			$this->cache->set('question.count' . $cache . '.' . $type, $query->num_rows);
			return $query->num_rows;
		} else {
			return $count;
		}
	}

}
