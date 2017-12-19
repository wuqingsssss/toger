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
	public function get_group_list() {
	    $today = date("Y-m-d", time());
		$sql = "SELECT tgb.*, tp.price*tgb.quantity as price FROM ts_group_buy tgb, ts_product tp 
		        WHERE tgb.product_id=tp.product_id 
		        AND tgb.start_time <= '{$today}' 
		        AND tgb.end_time >= '{$today}' 
		        AND tgb.`status` = 1 
		        ORDER BY tgb.g_id DESC";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	
	/**
	 * 团购信息总数
	 * @return type
	 */
	public function count_list(){
		$sql = "select id from {$this->table}";
		
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	/**
	 * 团购信息
	 * @param type $id
	 */
	public function get_group_info($id) {
		if(empty($id)){
			return false;
		}
		
		$sql = "select * from {$this->table} where g_id = {$id} and `status` = 1";
		$query = $this->db->query($sql);
		
		return $query->row;
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
	
	/**
	 * 获取团购信息
	 * @param unknown $groupbuy_id
	 */
	public function getGroupbuyInfo($groupbuy_id){
	    $sql = "SELECT tgb.name, tgb.sell_price, tgb.duration, tgb.product_id, tgc.shipping_date, tgc.status, tgc.end_time FROM " . DB_PREFIX . "group_create tgc, " . DB_PREFIX . "group_buy tgb 
	            WHERE tgc.g_id = tgb.g_id AND tgc.c_id = '{$this->db->escape($groupbuy_id)}'";
	    
	    $ret = $this->db->query($sql);
	    
	    return $ret->row;
	}
	
	/**
	 * 获取用户发起团信息
	 * @param unknown $customer_id
	 * @param unknown $id
	 */
	public function getCustomerGroupbuy($customer_id, $id){
	    $sql = "SELECT c_id, status FROM " . DB_PREFIX . "group_create WHERE g_id = {$this->db->escape($id)} AND customer_id={$customer_id} AND status != -1 LIMIT 1 ";
	    $ret = $this->db->query($sql, false);   
	    
	    return $ret->row;
	}
	
	/**
	 * 获取用户参团信息
	 * @param unknown $customer_id
	 * @param unknown $id
	 */
	public function getCustomerGroupbuyMember($customer_id, $id){
	    $sql = "SELECT tsm.c_id, tsc.status FROM ts_group_member tsm, ts_group_create tsc 
	            WHERE tsm.c_id = tsc.c_id AND tsc.g_id = {$this->db->escape($id)} 
	            AND tsc.status != -1 
	            AND tsm.customer_id={$customer_id} LIMIT 1 ";
	    $ret = $this->db->query($sql, false);
	       
	    return $ret->row;
	}
	
	/**
	 * 获取拼团数量信息
	 * @param unknown $id
	 */
	public function getGroupbuyNum($id){
	    $sql = "SELECT count(*) as total FROM ts_group_create 
	    WHERE g_id = {$this->db->escape($id)} 
	    AND status != -1";
	    
	    $ret = $this->db->query($sql, false);
	
	    return $ret->row['total'];
	}
	
	/**
	 * 获取拼团信息
	 * @param unknown $cid
	 */
	public function getGroupbuyCreateInfo($cid){
	    $sql = "SELECT * FROM ts_group_create 
	    WHERE c_id = {$this->db->escape($cid)} LIMIT 1";
	     
	    $ret = $this->db->query($sql, false);
	
	    return $ret->row;
	}
	
	/**
	 * 发起人 付款后 创建团购
	 * @param type $g_id
	 */
	public function create($g_id, $customer_id){
	    if(empty($g_id)){
	        return false;
	    }
	    
	    //获取团购基本信息
	    
	    $g_info = $this->get_group_info($g_id);
	    
	    if(!$g_info){
	        return  false;
	    }
	    
	    //拼接 创建团信息
	    $createtime = date('Y-m-d',time());
	    //结束时间 = 创建时间 + 成团有效时间
	    $time_s = (($g_info['duration'] - 1) * 3600 * 24) + strtotime($createtime);
	    $endtime = date('Y-m-d', $time_s);
	    //配送时间为空
	    if($g_info['send_time'] == '0000-00-00' || !$g_info['send_time'] || $g_info['send_time']<=$endtime){
	        $shipping_date = date('Y-m-d',strtotime($endtime)+3600*24);
	    }
	    else{
	        $shipping_date = $g_info['send_time'];
	    }
	    
	    $pendingData = array(
	        array('g_id', $g_info['g_id'], true, true),
	        array('max_num', $g_info['member_num'], false, false),
	        array('real_num', 0, false, false),
	        array('create_time', $createtime, true, false),
	        array('end_time',   $endtime, true, false),
	        array('shipping_date',   $shipping_date, true, false),	
	        array('customer_id', $customer_id, true, false)         
	    );
	    DbHelper::insert('group_create', $pendingData);
	     
	    return $this->db->getLastId();
	}
	
	/**
	 * 追加拼团成员
	 * @param unknown $customer_id
	 * @param unknown $cid
	 * @param unknown $order_id
	 */
	public function addGroupMember($customer_id, $cid, $order_id){
	    $group_info = $this->getGroupbuyCreateInfo($cid, false);
	    $type = 2;
	    
	    if( $group_info){
	        // 判断团发起者
	        if( $group_info['customer_id'] == $customer_id){
	            $type = 1;
	        }
	        else{
	            $type = 2;
	        }

	        $sql = "INSERT ts_group_member 
	                SET c_id = '{$cid}', 
	                    customer_id = '{$customer_id}', 
	                    type = '{$type}', 
	                    order_id = '{$order_id}', 
	                    join_time = NOW()";
	        $this->db->query($sql);
	    }
	    else {
	        return false;
	    }
	}
	
	/**
	 * 更新拼团状态
	 * @param unknown $cid
	 */
	public function  updateStatus($cid){
	    $group_info = $this->getGroupbuyCreateInfo($cid, false);
	    $num = $this->getMemberNum($cid);
	    $today  = date('Y-m-d', time());
	    
	    $status = $group_info['status'];
	    
	    if( $group_info['status']!= '-1'){
    	    if( $today > $group_info['end_time']){ //过期
    	        $sql = "UPDATE ts_group_create
            	        SET real_num='{$num}',
            	        status = '-1',
            	        finish_time = NOW()
            	        WHERE c_id = '{$cid}'";
    	        $this->db->query($sql );
    	        
    	        $status = '-1';
    	    }
    	    elseif( $num >= $group_info['max_num'] ){
    	        $sql = "UPDATE ts_group_create 
    	                SET real_num='{$num}',
    	                    status = '2',
    	                    finish_time = NOW() 
    	                WHERE c_id = '{$cid}'";
    	        $this->db->query($sql );
    	        
    	        $status = '2';
    	    }
    	    elseif($num >0 ){ 
    	        $sql = "UPDATE ts_group_create
            	        SET real_num='{$num}',
            	        status = '1',
            	        finish_time = NOW()
            	        WHERE c_id = '{$cid}'";  
    	        $this->db->query($sql );
    	        
    	        $status = '1';
    	    }
	    }
	    
	    return $status;
	}
	
	/**
	 * 获取拼团人数
	 * @param unknown $cid
	 */
	public function getMemberNum($cid){
	    $sql = " SELECT count(*) AS total FROM ts_group_member WHERE c_id = '{$cid}'";
	    
	    $ret = $this->db->query($sql, false);
	    
	    return $ret->row['total'];
	}
	
	/**
	 * 获取团购商品信息
	 * @param unknown $cid
	 */
	public function getProductInfo($cid){
	    $sql = " SELECT tp.*, tpd.name, tgb.quantity, tp.price*tgb.quantity as price FROM ts_group_buy tgb, ts_group_create tgc, ts_product tp, ts_product_description tpd
	            WHERE tgb.g_id = tgc.g_id 
	            AND tgc.c_id = '{$cid}' 
	            AND tgb.product_id = tp.product_id 
	            AND tpd.product_id = tp.product_id";
	    
	    $ret = $this->db->query($sql);
	    
	    return $ret->row;
	}
	

	/**
	 * 获取团购商品信息
	 * @param unknown $cid
	 */
	public function getProductInfoByGroupID($id){
	    $sql = " SELECT tp.*, tpd.name, tpd.description, tgb.quantity, tp.price*tgb.quantity as price FROM ts_group_buy tgb,  ts_product tp, ts_product_description tpd
	    WHERE tgb.g_id = '{$id}' 
	    AND tgb.product_id = tp.product_id
	    AND tpd.product_id = tp.product_id";
	     
	    $ret = $this->db->query($sql);
	     
	    return $ret->row;
	}
	
	
	/**
	 * 获取拼团信息
	 * @param unknown $cid
	 * @return boolean
	 */
	public function get_c_info($cid){
	    if(empty($cid)){
	        return false;
	    }
	    $sql = "select * from ts_group_create where c_id = '{$cid}'";
	    $query = $this->db->query($sql);
	    return $query->row;
	}
	
	/**
	 * 获取团购成员信息
	 * @param unknown $cid
	 */
	public function getGroupMember($cid){
	    $sql = " SELECT customer_id FROM ts_group_member WHERE c_id = '{$cid}'";
	    
	    $ret = $this->db->query($sql, false);
	    
	    return $ret->rows;
	}
}
