<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelSaleCouponShow extends Model {
	/**
	 * 选取库内优惠卷列表
	 * @param type $customer_id 
	 */
	public function show_list(){
		$customer_id = $this->customer->getId();
		$date = date('Y-m-d', time());
		$sql = "select * from ".DB_PREFIX."coupon_show where '{$date}' >= start_time and '{$date}' <= end_time and status = 1";
//		echo $sql;exit;
		$query = $this->db->query($sql);
		$data = $query->rows;
		if($data){
		//获取优惠卷详细信息  
		$cids = array_column($data, 'coupon_id');
		$str = implode(',', $cids);
		$in_str = " ({$str}) ";
		$sql2 = "select coupon_id, name, code, uses_total, discount, uses_customer,date_start, date_end from ".DB_PREFIX."coupon "
			. "where coupon_id in {$in_str}";
		$query_info = $this->db->query($sql2);
		$coupon_info = $query_info->rows;

		//获取用户使用优惠卷 信息
		$sql3 = "select coupon_id, group_concat(customer_id) as customers, count(1) as num from ".DB_PREFIX."coupon_to_customer "
			. "where coupon_id in {$in_str} group by coupon_id";
		$cus_query = $this->db->query($sql3);
		//格式化 customer 数据信息 
		foreach($cus_query->rows as $c){
			$c['customers'] = explode(',', $c['customers']);
			$cus_info[$c['coupon_id']] = $c;
		}
		//该优惠卷没有用户领取过 需手动拼接数据
		foreach($cids as $cid){
			if(empty($cus_info[$cid])){
				$cus_info[$cid]['coupon_id'] = $cid;
				$cus_info[$cid]['customers'] = array();
				$cus_info[$cid]['num'] = 0;
			}
		}
		//页面显示优惠卷状态 -1 --已经领取  -2 --已经抢光了
		foreach($coupon_info as &$c_info){
			$cus_use_count = array_count_values($cus_info[$c_info['coupon_id']]['customers']);//该优惠卷每个用户领取的次数
			$all_use_total = $cus_info[$c_info['coupon_id']]['num'];//该优惠卷 被领用总数
			
			if($cus_use_count[$customer_id] != 0 && $c_info['uses_customer'] > 0 
				&& $cus_use_count[$customer_id] >= $c_info['uses_customer'] && $customer_id > 0){
				$c_info['flag'] = -1;
			}else if($all_use_total != 0 && $c_info['uses_total'] > 0 && $all_use_total >= $c_info['uses_total']){
				$c_info['flag'] = -2;
			}else{
				$c_info['flag'] = 1;
			}
			
		}
		//组合数据
		foreach ($data as &$da) {
			foreach ($coupon_info as $in) {
				if($da['coupon_id'] == $in['coupon_id']){
					$da['info'] = $in;
				}
			}
		}
		}
		return $data;
	}
}