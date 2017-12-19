<?php
class ModelAccountCoupon extends Model {
    //类型定义
    const TYPE_PERCENTAGE = 'F';//按比例
    const TYPE_FIXED_AMOUNT = 'P';//按金额
    const TYPE_FREE = 'R';//免费券
    //状态
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;
        

    /**
     * 通过手机号获取美团预绑定优惠券列表
     * @param array $data  data[mt_mobile] 美团手机号 
     * @return boolean
     */
    public function getCouponsBindMeituantg($data) {
    	$sql="SELECT * FROM " . DB_PREFIX . "coupon_meituantg WHERE 1= 1";
    	if($data['mt_mobile'])$sql.=" AND mt_mobile='".$data['mt_mobile']."'";
    	if($data['status'])$sql.=" AND status='".$data['status']."'";
    	if($data['mt_code'])$sql.=" AND mt_coupon_code='".$this->db->escape($data['mt_code'])."'";
    	$query = $this->db->query($sql);
    
    	if ($query->num_rows > 0) {
    		 
    		return $query->rows;
    	}
    	else
    	{
    		return false;
    	}
    
    }
    
    public function existCoupon($data) {
    

    
    	$sql="SELECT code FROM " . DB_PREFIX . "coupon WHERE 1= 1";
    	if($data['order_id'])$sql.=" AND order_id='".$data['order_id']."'";
    	if($data['type'])$sql.=" AND typea='".$data['type']."'";
    	
    	$query = $this->db->query($sql,false);
    
    	if ($query->num_rows > 0) {
    			
    		return $query->row['code'];
    	}
    	else
    	{
    		return false;
    	}
    
    }
    
    /* 前端自动生成优惠券
     * @param array $data:传入的要生成的优惠券信息集                              可否为空
     *   $data[order_id]     产生该优惠券的可能的订单号                是
     *   $data[customer_id]  产生该优惠券的可能的用户id               是
     *   $data[name]         优惠券名称                            否
     *   $data[code]         优惠券编码    默认随机生成               可
     *   $data[type]         优惠券类型 百分比P  固定金额F  菜票R      否
     *   $data[discount]     优惠价格或折扣                         否
     *   $data[logged]       限定登录后使用                         是
     *   $data[paoduct] array 限定菜品                             是
     *   $data[shipping]     免费运送                              是
     *   $data[total]        可使用订单最小金额 0为不限制              是
     *   $data[date_start]   开始日期                              是
     *   $data[date_end]     结束日期                              是
     *   $data[status]       优惠券状态  默认开启                    是
     *   $data[uses_total]   特权卷可被领用次数 默认不限次数            是
     *   $data[uses_customer] 用户可使用次数   默认不限次数            是
     *   $data[creator_id]   创建者                                是
     *   $data[owner_id]     拥有人                                是
     *   $data[duration]     是否可持续使用                          是
     *   $data[usage]        使用说明                              是
     * 
     * */
    public  function createCoupon($data){
    	/* 优惠券添加代码*/
    	
    	if(empty($data['code'])){
    	   $code = $this->getNextCode(8, PRO);
    	}
    	
    	if(isset($data['date_end'])){
    	    $date_end = $data['date_end'];
    	}
    	else{
    	    $date_end = date('Y-m-d', time()+86400*7);
    	}
    	
	    $pendingData = array(
	        array('name',$data['name'], true, true),
	        array('code', $code, true, true),
	        array('discount', (float)$data['discount'], true, false),
	        array('total', isset($data['total'])?$data['total']:0, true, false),
	        array('type', $data['type'], true, true),
	        array('logged', isset($data['logged'])?$data['logged']:1, true, false),
	        array('shipping', isset($data['shipping'])?$data['shipping']:0, true, false),
	        array('date_start',isset($data['date_start'])?$data['date_start']:'NOW()', true, false),
	        array('date_end', $date_end, true, false),
	        array('duration', isset($data['duration'])?$data['duration']:0, true, false),
	        array('`usage`', isset($data['usage'])?$data['usage']:'', true, false),
	        array('uses_total', isset($data['uses_total'])?$data['uses_total']:0, true, false),
	        array('uses_customer',isset($data['uses_customer'])?$data['uses_customer']:0, true, false),
	        array('status', isset($data['status'])?$data['status']:1, true, false),
	        array('date_added', 'now()', false, false),
	        array('creator_id', isset($data['creator_id'])?$data['creator_id']:'',  true, true),
	        array('owner_id', isset($data['owner_id'])?$data['owner_id']:'',  true, true),
	    	array('order_id', isset($data['order_id'])?$data['order_id']:'',  true, true),
	    );
	    DbHelper::insert('coupon', $pendingData);
	      
	    return $code;
    }
    

    /**
     * 校验码重复
     * @param unknown $code
     * @return boolean
     */
    private function existSameCode($code) {
        $sql = "SELECT coupon_id FROM " . DB_PREFIX . "coupon WHERE code='{$code}' limit 1";
        $dbId = DbHelper::getSingleValue($sql, null);
        return !empty($dbId);
    }
    
    /**
     * 生成随机码
     * @param unknown $codePrefix
     * @throws exception
     * @return string
     */
    private function getNextCode($length, $prefix=null) {
        $tryTimes = 0;
        while ($tryTimes < 100) {
            $tryTimes++;
    
            $code = Maths::genRandomCode($length, $prefix);
    
            if (!$this->existSameCode($code)) {
                return $code;
            }
        }
        throw new exception('生成ID失败');
    }
    
	public function getTotalCoupons() {
		$sql = "SELECT COUNT(coupon_id) AS total FROM " . DB_PREFIX . "coupon";
	
		$query = $this->db->query($sql);
	
		return $query->row['total'];
	}
	
	public function getCoupons($data = array(),$start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		$sql = "SELECT *  FROM " . DB_PREFIX . "coupon c";
		
		$sort_data = array(
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
		);	
			
		
		$sql .= " ORDER BY coupon_id,date_start,date_end,status DESC ,name";	
		
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		$sql.=" LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getCouponStatusCode($coupon_id){
		$sql="SELECT uses_customer FROM " . DB_PREFIX . "coupon WHERE coupon_id='{$coupon_id}' AND status=1 AND date_start <=NOW() AND date_end >=NOW()";
		
		$query=$this->db->query($sql,false);
		
		if($query->row){
			$limit_num_for_customer=$query->row['uses_customer'];
			
			$used_total=$this->getTotalCouponOfCustomerUsed($coupon_id,$this->customer->getId());
			
			if($used_total >=$limit_num_for_customer){
				return 'used';
			}else{
				return 'valid';
			}
			
		}else{
			return 'invalid';
		}
	}
	
	/**
	 * 获取优惠券信息
	 * @param unknown $coupon_id
	 * @return string
	 */
	public function getCouponInfo($coupon_id){
	    $sql="SELECT * FROM " . DB_PREFIX . "coupon WHERE coupon_id='{$coupon_id}'";
	
	    $query=$this->db->query($sql,false);
	
	    return $query->row;
	}
	
	/**
	 * 根据电话获取OPENID
	 * @param unknown $mobile
	 */
	private function findOpenId($mobile){
	    $sql = "SELECT tso.openid FROM ts_customer tc, ts_openid_info tso WHERE tc.mobile = '{$mobile}' AND tso.customer_id = tc.customer_id";
	
	    $query=$this->db->query($sql);
	
	    return $query->row['openid'];
	}
	
	
	public function getTotalCouponOfCustomerUsed($coupon_id,$customer_id){
		$sql="SELECT COUNT(coupon_id) AS total FROM " . DB_PREFIX . "coupon_history WHERE coupon_id=".(int)$coupon_id." AND customer_id=".(int)$customer_id;
		
		$query=$this->db->query($sql);
		
		return $query->row['total'];
	}
	
	/**
	 * 通过优惠码添加优惠劵到当前用户
	 * @param unknown $code
	 * @param unknown $customer_id
	 * @param number $point_id
	 * @param string $partner_code
	 * @return boolean
	 */
	public function addCoupon($code,$customer_id,$point_id=0,$partner_code='',$order_id=''){
		
		$sql="SELECT coupon_id, uses_total, uses_customer, duration, date_end FROM " . DB_PREFIX . "coupon WHERE code='".$code."'";
		$sql.= " AND date_start <=DATE(NOW()) AND date_end >=DATE(NOW())";    // 优惠券有效期内
		$query = $this->db->query($sql,false);

		if ($query->num_rows) {
		    $coupon_id = $query->row['coupon_id'];
		    $duration  = $query->row['duration'];

		    if($order_id){
		    // 当前用户订单关联数量
		    $sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "coupon_to_customer WHERE customer_id = '{$customer_id}' and coupon_id= '{$coupon_id}' and order_id= '{$order_id}'";
		    $ordercount = $this->db->query($sql,false);
		    if((int)$ordercount->row['total']>0)
		    {//订单已经被领取
		    	return -3;
		    }
		    }
		    
		    // 当前用户关联次数
		    $sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "coupon_to_customer WHERE customer_id = '{$customer_id}' and coupon_id= '{$coupon_id}'";
		    $usescount = $this->db->query($sql,false);
		    // 所有用户关联次数
		    $sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "coupon_to_customer WHERE coupon_id= '{$coupon_id}'";
		    $usestotal = $this->db->query($sql,false);

		  
		    
		    
		    //当前用户关联次数判断
		    if((int)$usescount->row['total']!=0 && $query->row['uses_customer']>0 && (int)$usescount->row['total'] >= $query->row['uses_customer']){

		        return -1;
		    }

		    //所有用户关联总次数判断
		    if((int)$usestotal->row['total']!=0 && $query->row['uses_total']>0 && (int)$usestotal->row['total'] >= $query->row['uses_total']){
		        return -2;
		    }

		    $date_limit = $query->row['date_end'];
		    		    	    
            $res=$this->addCouponToCustomer((int)$coupon_id,(int)$customer_id, (int)$duration, $date_limit, $point_id,$partner_code,$order_id);


		    if($res){
		        return 1;
		    }
			else {
		        return 0;
			}
		}
		return -3;
	}
	
	/**
	 * 通过优惠码添加优惠劵到当前用户（不作数量限制判断）
	 * @param unknown $code
	 * @param unknown $customer_id
	 * @param number $point_id
	 * @param string $partner_code
	 * @return boolean
	 */
	public function addCouponToCustomerByCode($code,$customer_id,$point_id=0,$partner_code='',$order_id=''){
		$sql=" SELECT coupon_id, duration, date_end FROM " . DB_PREFIX . "coupon WHERE code='".$code."' ";
		$sql.= " date_start <=DATE(NOW()) AND date_end >=DATE(NOW())";    // 优惠券有效期内
		$query = $this->db->query($sql,false);
		if ($query->num_rows) {
			return $this->addCouponToCustomer((int)$query->row['coupon_id'],$customer_id,(int)$query->row['duration'], $query->row['date_end'], $point_id,$partner_code,$order_id);
		}
		return false;
	}
	
	
     /**
      * 添加优惠劵到指定用户
      * @param unknown $coupon_id
      * @param unknown $customer_id
      * @param unknown $point_id
      * @param unknown $partner_code
      */
	private function addCouponToCustomer($coupon_id, $customer_id, $duration, $date_limit='', $point_id=0, $partner_code='',$order_id=''){
	    $date_end = '';
	    
		if($point_id>0) {
		    $sqlstr.=",point_id = '$point_id'";
		}
		if(!empty($partner_code)){
			$sqlstr.=",partner_code = '$partner_code'";
		}
		if(!empty($order_id)){
			$sqlstr.=",order_id = '$order_id'";
		}
		$sqlstr.=",date_add = '".date('Y-m-d H:i:s',time())."'";
		$sqlstr.=",date_update = '".date('Y-m-d H:i:s',time())."'";
		
	    if($duration<=0){  // 0 的规则是使用优惠券有效期
	       $sqlstr.=",date_limit = '".$date_limit."'";
	       $date_end = $date_limit;	       
	    }
	    else{  // 1当天有效，7包括当天1个星期有效
	        $date_end = date('Y-m-d',time()+($duration-1)*86400);
	        $sqlstr.=",date_limit = '".$date_end."'";
	    }
  
	    $ret =$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_to_customer SET coupon_id = '" . $coupon_id . "', customer_id = '" . $customer_id . "'".$sqlstr);
	    
	    // 发送通知
	    if($ret) {
	    	$this->log_order->info('addCouponToCustomer'.serialize($ret));
	    	$openid=$this->customer->existPlatForm('wechat',$customer_id);
	    	if($openid){	    		
	    		$this->log_order->info('addCouponToCustomer'.$openid);
	    		$coupon_info = $this->getCouponInfo($coupon_id);
	    		$this->log_order->info($coupon_info);
	    		if($coupon_info) {
	    			/*
	    			$template_id = 'w1M24PqCmGxb32v1fJj-QyXgaXxr0jFae85zG0CmoYQ';
	    			
	    			
	    			$url = $this->url->link('common/home');
	    			 
	    			$orderstr=!empty($order_id)? "
订单号：".$order_id:"";
	    		
	    			$msg_data = array(
	    					'first' => array(
	    							'value' => "您获得一张".$coupon_info['name']."优惠券，请在个人中心内查看。付款时请点击“使用优惠券”直接使用！".$orderstr,
	    							'color' => '#0AA39A'
	    					),
	    					'orderTicketStore' => array(
	    							'value' => "菜君可送达区域均可使用",  //使用范围
	    							'color' => '#0AA39A'
	    					),
	    					'orderTicketRule' => array(
	    							'value' => str_replace(array("<br/>", "<br />"),'',$coupon_info['usage'])."\r\n有效期:".$date_end,    //使用规则
	    							'color' => '#0AA39A'
	    					),
	    					'remark' => array(
	    							'value' => "你每日忙碌，我牵肠挂肚【点击查看优惠券】",
	    							'color' => '#0AA39A'
	    					)
	    					 
	    			);
	    			*/
	    			$template_id = 'vFQEfoT_uxWmR5OxfSzgCAdCS8eRkS32i5v5GPPyRPs';
	    			
	    			
	    			$url = $this->url->link('common/home');
	    			 
	    			$orderstr=!empty($order_id)? "
订单号：".$order_id:"";
	    			 
	    			$msg_data = array(
	    					'first' => array(
	    							'value' => "您获得一张".$coupon_info['name']."优惠券，请在个人中心内查看。付款时请点击“使用优惠券”直接使用！",
	    							'color' => '#0AA39A'
	    					),
	    					'keyword1' => array(
	    							'value' => "登录青年菜君查询",  //使用范围
	    							'color' => '#0AA39A'
	    					),
	    					'keyword2' => array(
	    							'value' => "青年菜君".$orderstr,    //使用规则
	    							'color' => '#0AA39A'
	    					),
	    					'keyword3' => array(
	    							'value' => $date_end,    //使用规则
	    							'color' => '#0AA39A'
	    					),
	    					'keyword4' => array(
	    							'value' => str_replace(array("<br/>", "<br />"),'',$coupon_info['usage']),    //使用规则
	    							'color' => '#0AA39A'
	    					),
	    					'remark' => array(
	    							'value' => "你每日忙碌，我牵肠挂肚【点击查看优惠券】",
	    							'color' => '#0AA39A'
	    					)
	    						
	    			);
	    			
	    			
	    			
	    			$this->log_order->info($msg_data);
	    				
	    			$this->load->service('weixin/interface');
	    			$this->service_weixin_interface->send_msg_by_weixin($openid,$template_id,$url,$msg_data);
	    		}
	    	}
	    	
	    }
	    
	    return $ret;
	}
	
	
	public function checkCoupon($code) {
		/*$sql=" SELECT * FROM " . DB_PREFIX . "coupon WHERE code='".$code."' AND coupon_id IN (
		SELECT coupon_id FROM  " . DB_PREFIX . "coupon_to_customer WHERE customer_id='".(int)$this->customer->getId() ."' )";
		*/
		$sql="SELECT c.coupon_id FROM ". DB_PREFIX . "coupon_to_customer as cc "
				     ."LEFT JOIN ". DB_PREFIX . "coupon as c ON cc.coupon_id=c.coupon_id"
				     ." WHERE customer_id='".(int)$this->customer->getId() ."' ";

		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			return true;
		}else{
			$sql=" SELECT coupon_id FROM " . DB_PREFIX . "coupon WHERE code='".$code."' ";
			$query = $this->db->query($sql);
			if ($query->num_rows) {
				return false;
			}else{
				return true;//?什么逻辑查不到了 还有效？？？
			}
		}
	}



	public function getCouponCategorys($coupon_id){
		$sql="select cd.name,cd.category_id FROM coupon_category cc left join category c on c.category_id=cc.category_id left join category_description cd  on cc.category_id=cd.category_id WHERE cc.coupon_id=0".(int)$coupon_id." AND cd.language_id='".(int)$this->config->get('config_language_id')."'";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getCouponProducts($coupon_id) {
		 //TODO if there are a lot of products, this SQL would have soem performance issue. and the better way is using cache or change the database design.
		$sql="create temporary table if not exists tmp_coupon_category (SELECT p2c.category_id FROM `product_to_category` p2c left join coupon_product cp on p2c.product_id=cp.product_id where coupon_id = '282'); ";
		$this->db->query($sql);
		
		$sql="select cd.name,cd.category_id FROM tmp_coupon_category cc left join category c on c.category_id=cc.category_id left join category_description cd  on cc.category_id=cd.category_id WHERE c.parent_id =0 AND cd.language_id='".(int)$this->config->get('config_language_id')."'";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

    public function getCouponByCode($code){
    	
        $sql=" SELECT * FROM " . DB_PREFIX . "coupon WHERE code='".$this->db->escape($code)."'";
        $query = $this->db->query($sql);
        
        return $query->row;
    }

   
    public function getCouponIdByCode($code){
    	$sql=" SELECT coupon_id FROM " . DB_PREFIX . "coupon WHERE code='".$code."'";    	  
    	$query = $this->db->query($sql);
    	
    	if ($query->num_rows) {
    	 		return (int)$query->row['coupon_id'];
    	}
    	return false;
    }
    
    
    /**
     * 
     * @param unknown $customer_id
     * @param unknown $coupon_state
     * @param unknown $start
     * @param unknown $limit
     * @return multitype:unknown
     */
    public function getCouponsByCustomer($customer_id, $coupon_state, $start, $limit) {
        $sql = "from ts_coupon c join ts_coupon_to_customer cc on cc.customer_id={$customer_id} ";
        if (!empty($coupon_state)) {
            $sql .= "and c.`status`='{$coupon_state}' ";
        }

        $total = DbHelper::getSingleValue("select count(*) " . $sql, 0);

        $querySql = "select c.* " . $sql . "limit {$start},{$limit} ";
        $query = $this->db->query($querySql);
        $rows = $query->rows;

        return array(
            'total' => $total,
            'rows' => $rows
        );
    }
        
    /**
     *  获取指定类型的有效优惠券
     * @param unknown $customer_id
     * @param unknown $type
     * @return unknown
     */
    public function getCouponsByType($customer_id, $type) {
        $sql = "select c.*, cc.coupon_customer_id from ts_coupon c , ts_coupon_to_customer cc where cc.customer_id={$customer_id} and 
                c.type='{$type}' AND c.`status`=1  AND cc.coupon_id=c.coupon_id AND cc.date_limit>=DATE(NOW()) 
                AND cc.used='0'";
        $query = $this->db->query($sql);
        $rows = $query->rows;

        return $rows;
    }
    
    /**
     *  获取类型以外的有效优惠券
     * @param unknown $customer_id
     * @param unknown $type
     * @return unknown
     */
    public function getCouponsByExceptR($customer_id) {
        $sql = "SELECT c.*, cc.coupon_customer_id, cc.used, cc.date_limit FROM ts_coupon c , ts_coupon_to_customer cc WHERE cc.customer_id={$customer_id} and
        c.type!='R' AND c.`status`=1  AND cc.coupon_id=c.coupon_id AND cc.date_limit>=DATE(NOW()) AND cc.used='0'";
        $query = $this->db->query($sql);
        $rows = $query->rows;
    
        return $rows;
    }
    
    /**
     *  获取用户特定优惠券绑定ID
     * @param unknown $customer_id
     * @param  $code
     * @return unknown
     */
    public function getCouponCustomerIDByCode($customer_id, $code) {
        $sql = "SELECT cc.coupon_customer_id FROM ts_coupon c , ts_coupon_to_customer cc where cc.customer_id={$customer_id} AND
        c.`status`=1  AND cc.coupon_id=c.coupon_id AND cc.date_limit>=DATE(NOW()) AND cc.used='0' AND c.code='{$code}'";
        $query = $this->db->query($sql);
        $rows = $query->rows;
        
        return $rows;
    }
    
    
    /**
     *  获取用户所有有效优惠券
     * @param unknown $customer_id
     * @return unknown
     */
    public function getCouponsByCustomerAll($customer_id) {
        $sql = "SELECT c.*, cc.coupon_customer_id, cc.used, cc.date_add, cc.date_limit FROM ts_coupon c , ts_coupon_to_customer cc where cc.customer_id={$customer_id} AND
         c.`status`=1  AND cc.coupon_id=c.coupon_id AND cc.date_limit>=DATE(NOW()) AND cc.used='0' ORDER BY cc.date_limit";
        $query = $this->db->query($sql);
        $rows = $query->rows;
        return $rows;
    }
    
    /**
     * 根据条件获取红包
     * @param string $conditon
     */
    public function getPacketByCondition($condition, $campaign=''){
       
        if($condition == 'register'){
            $cond = 0;
        }
        else if($condition == 'olduser') {
            $cond = 1;
        }
        else if($condition == 'campaign'){
            $cond = 2;
        }
        else {
            $cond = 9999;  //缺省值
        }
        
        if($cond == 2){
            $sql = "SELECT * FROM  ts_packet WHERE cond={$cond} AND code='{$this->db->escape($campaign)}' AND date_start<=DATE(NOW()) AND date_end>=DATE(NOW())  LIMIT 1";
        }
        else{
            $sql = "SELECT * FROM  ts_packet WHERE cond={$cond} AND date_start<=DATE(NOW()) AND date_end>=DATE(NOW())  LIMIT 1";
        }
        $result = $this->db->query($sql);
        
        return $result->row;
    }
    
    /**
     * 根据条件和活动编码
     * @param unknown $condition
     * @param string $promo
     */
    public function getPacketByCampaignCode($condition, $promo=''){
         
        if($condition == 'register'){
            $cond = 0;
        }
        else if($condition == 'olduser') {
            $cond = 1;
        }
        else if($condition == 'campaign'){
            $cond = 2;
        }
        else {
            $cond = 9999;  //缺省值
        }
    
        $code = $this->db->escape($promo);
        
         $sql = "SELECT tp.packet_id, tp.name, tp.batch FROM  ts_campaign tc, ts_campaign_rule tr, ts_packet tp 
                 WHERE tr.campaign_id=tc.campaign_id
                       AND tc.code='{$code}' 
                       AND tc.date_start<=DATE(NOW())
                       AND tc.date_end>=DATE(NOW())
                       AND tc.status = 1
                       AND tp.packet_id = tr.packet_id
                       AND tr.cond='{$cond}' 
                       AND tr.flag=1
                       LIMIT 1";
      
        $result = $this->db->query($sql);
    
        return $result->row;
    }
    
    
    /**
     * 追加活动履历
     * @param unknown $promo
     * @param unknown $customer_id
     * @param string $status
     */
    public function addCampaignHistory($promo, $customer_id, $status='0', $order_id=''){
        // 新用户需要去重
        if($status=='0'){
            $ret = $this->db->query("SELECT COUNT(*) AS total FROM ts_campaign_history WHERE
                                                               customer_id ='{$customer_id}' AND 
                                                               status='0'");
            if($ret->row['total'] > 0){
                return;
            }
        }
             
        $this->db->query("INSERT INTO  ts_campaign_history SET campaign_code = '{$promo}', 
                                                               customer_id ='{$customer_id}',
                                                               order_id ='{$order_id}',  
                                                               date_added=NOW(), 
                                                               status='{$status}'");
    }
    
    
    /**
     * 红包加入个人账户
     * @param unknown $packet_id
     * @param unknown $customer_id
     * @param unknown $pick_tyle
     */
    public function addPacket2Customer($packet_id, $customer_id,$pick_tyle=0,$point_id=0,$partner_code=''){
        $sql = "SELECT batch FROM  ts_packet WHERE packet_id = '{$packet_id}'";
        $result = $this->db->query($sql);
        if($result->num_rows > 0){  
            $batch = $result->row['batch'];
        }
        else {
            return -1;
        }
        
        // 查询是否有过同一批次红包领用记录
        $sql = "SELECT * FROM  ts_packet_history tph, ts_packet tp WHERE tph.batch = tp.batch 
                                                                         AND tp.packet_id = '{$packet_id}' 
                                                                         AND tph.customer_id ='{$customer_id}' 
                                                                         ";
        $result = $this->db->query($sql);
        if($result->num_rows > 0){  //已经有领用记录
            return -1;
        }
        
        // 获取红包内优惠券项目
        $sql = "SELECT * FROM  ts_packet_item WHERE packet_id='{$packet_id}' ";
        $result = $this->db->query($sql);
        if($result->num_rows > 0) {
      	
        	
        	if($pick_tyle>0)
        	{
        		$scn=mt_rand(0, $result->num_rows);//产生一个随机序号
        		//随机发发一个优惠券到用户账户
        		$this->addCoupon($result->rows[$scn]['code'], $customer_id, $point_id, $partner_code);
        	}
        	else 
        	{
                // 绑定红包里的优惠券到个人账户
                foreach ($result->rows as $coupon ) {
                    $this->addCoupon($coupon['code'], $customer_id, $point_id, $partner_code);
                }
        	}
            
            
            //添加红包领用历史
            $this->db->query("INSERT INTO  ts_packet_history SET packet_id = '{$packet_id}', customer_id ='{$customer_id}', date_added=NOW(), status=1,  batch='{$batch}'");
            return 1;
        }
        else{
            return -2;
            
        }
    }  
	/**
	 * 超过30分钟未付款订单 查询是否使用优惠卷 是则返还优惠卷
	 * @param type $order_ids
	 * @return boolean
	 */
	public function return_coupon($order_ids){
		if(empty($order_ids)){
			return false;
		}
		$id_str = '(';
		foreach($order_ids as $v){
			$id_str .= "'".$v."',";
		}
		$id_str = rtrim($id_str,',').")";
//		$id_str = '('.implode(',', $order_ids).')';
		$sql = "update ".DB_PREFIX."coupon_to_customer c "
			. "LEFT JOIN ".DB_PREFIX."coupon_history h "
			. "on c.coupon_customer_id = h.coupon_customer_id "
			. "set c.used = 0 "
			. "where h.order_id in {$id_str}";
//		echo $sql;exit;
		$this->db->query($sql);
	}
	
}

?>