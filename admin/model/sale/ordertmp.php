<?php
class ModelSaleOrderTmp extends Model {	
	
	public function addTmp(){
	    $error=array();
	    
	    $count = 0;
	    $sql = "SELECT * FROM order_tmp WHERE status=0 order by id DESC LIMIT 0,5 ";

	    $query = $this->db->query($sql);

	    foreach ($query->rows as $datatmp){
	        $data = array();

	        $result = $this->db->query("SELECT * FROM product_detail pd LEFT JOIN ts_customer tsc ON tsc.mobile= pd.客户手机 WHERE pd.id={$datatmp['id']} ");
            $detail = $result->row;
	        
	        $order = $this->db->query("SELECT * FROM ts_order WHERE order_id = {$datatmp['原单号']} LIMIT 1");
	        if(!$order){
	            $error[] = $datatmp['id'];
	            continue;
	        }else
	        {
	            
	            $data = $order->row;
	            
	            $result = $this->db->query("SELECT * FROM ts_order WHERE (telephone={$datatmp['手机号']} or shipping_mobile={$datatmp['手机号']})
	                                           ORDER BY date_modified desc");
	            if(!$result){
	                $error[] = $datatmp['id'];
	                continue;
	            }
	            $customer = $result->rows[0];
	            $data['invoice_no']  = '0';
	            $data['customer_id'] = $customer['customer_id'];
	            $data['customer_group_id']  = $customer['customer_group_id'];
	            $data['telephone']   = $customer['telephone'];
	            $data['shipping_firstname'] = $customer['shipping_firstname'];
	            $data['shipping_lastname']  = $customer['shipping_lastname'];
	            $data['shipping_address_1'] = $customer['shipping_address_1'];
	            $data['shipping_address_2'] = $customer['shipping_address_2'];
	            $data['shipping_mobile']    = $customer['shipping_mobile'];
	            $data['shipping_method']    = $customer['shipping_method'];
	            $data['shipping_code']      = $customer['shipping_code'];
	            $data['payment_method']     = $customer['payment_method'];
	            $data['ip']                 = $customer['ip'];
	            $data['payment_code']       = $customer['payment_code'];
	            $data['payment_trade_no']   = $customer['payment_trade_no'];
	            $data['partner_code']       = $datatmp['合作平台']=="饿了么"?'ele':'';
	            $data['payment_zone']       = '1';
	            
	            if($data['partner_code'] !=''){
	                $data['source_from'] = 1000;
	                $data['store_url']   = 'http://www.qingniancaijun.com.cn/apiv3/';
	            }               
	            elseif($detail['来源']=='mobile'){
	                $data['source_from'] = 3;
	                $data['store_url']   = 'http://www.qingniancaijun.com.cn/';
	            }	               
	            else{
	                $data['source_from'] = 1;
	                $data['store_url']   = 'http://www.qingniancaijun.com.cn/';
	            }
               
	            if($data['partner_code'] == 'ele'){
	                $data['verify']  = md5((string)rand(10000, 99999));
//	                $data['tp_order_id'] = 
	            }
	            $data['user_agent']        = $customer['user_agent'];
	            $data['shipping_data']     = $customer['shipping_data'];
	            $data['poi']               = $customer['poi'];
	            
	            $data['shipping_time']     = $detail['宅配日期'];
	            $data['date_added']        = $detail['下单日期'];
	            $data['date_modified']     = $detail['下单日期'];
	        }
	  
	        $result = $this->db->query("SELECT * FROM ts_order_total WHERE order_id = {$datatmp['原单号']}"); 
	        $data['totals']  = $result->rows;     
	        
	        $result = $this->db->query("SELECT * FROM ts_order_product WHERE order_id = {$datatmp['原单号']}");
	        $data['products']  = $result->rows;
	        
	        $result = $this->db->query("SELECT * FROM ts_order_history WHERE order_id = {$datatmp['原单号']}");
	        $data['historys'] = $result->rows;
	        
	        foreach ($data['historys'] as $history){
	            if($history['order_status_id'] > '16' 
                ||$history['order_status_id'] == '5'){ 
	                $datenew =  date('Y-m-d',strtotime($data['shipping_time'])).' '.date('H:i:s',strtotime($history['date_added'])); 
	                $history['date_added'] = $datenew;
	            }
	        }
	        
	        $this->createTmp($data);
	        $this->db->query("UPDATE order_tmp set status=1 where id={$datatmp['id']}");
	        $count++;
	    }
	    
	    $error['success'] = $count;
	    return $error;
	}
    
	

	/**
	 * 生成订单
	 * @param unknown $data
	 * @return string
	 */
	public function createTmp($data) {
	    $common=new Common($this->registry);

	    $temp_date = $data['date_added'];
	    $device_code = date('md',strtotime($temp_date));
	    $pickup_code=  $device_code. str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	
	    $datePart = date ('ymd',strtotime($temp_date));
	    
	    // $this->db->query('START TRANSACTION');//开始事务
	    $sql = "INSERT INTO `" . DB_PREFIX . "order` SET
    			  shipping_point_id='0'
    			, pickup_code='".$pickup_code."'
    			, pdate='".$this->db->escape($data['pdate'])."'
    			, shipping_time='".$this->db->escape($data['shipping_time'])."'
    			, device_code ='".$this->db->escape($data['device_code'])."'
    			, tp_order_id ='".$this->db->escape($data['tp_order_id'])."'
    			, sp_order_id ='".$this->db->escape($data['sp_order_id'])."'
    			, invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "'
    			, store_id = '" . (int)$data['store_id'] . "'
    			, store_name = '" . $this->db->escape($data['store_name']) . "'
    			, store_url = '" . $this->db->escape($data['store_url']) . "'
    			, customer_id = '" . (int)$data['customer_id'] . "'
    			, customer_group_id = '" . (int)$data['customer_group_id'] . "'
    			, firstname = '" . $this->db->escape($data['firstname']) . "'
    			, lastname = '" . $this->db->escape($data['lastname']) . "'
    			, email = '" . $this->db->escape($data['email']) . "'
    			, telephone = '" . $this->db->escape($data['telephone']) . "'
    			, fax = '" . $this->db->escape($data['fax']) . "'
    			, shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "'
    			, shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "'
    			, shipping_company = '" . $this->db->escape($data['shipping_company']) . "'
    			, shipping_address_1 = '" . $this->db->escape($this->db->clean_nonchar($data['shipping_address_1'])) . "'
    			, shipping_address_2 = '" . $this->db->escape($this->db->clean_nonchar($data['shipping_address_2'])) . "'
    			, shipping_city = '" . $this->db->escape($data['shipping_city']) . "'
    			, shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "'
    			, shipping_country = '" . $this->db->escape($data['shipping_country']) . "'
    			, shipping_mobile = '" . $this->db->match_phone($this->db->escape($data['shipping_mobile'])) . "'
    			, shipping_phone = '" . $this->db->escape($data['shipping_phone']) . "'
    			, shipping_country_id = '" . (int)$data['shipping_country_id'] . "'
    			, shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "'
    			, shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "'
    			, shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "'
    			, shipping_method = '" . $this->db->escape($shipping_method) . "'
    			, shipping_code = '" . $this->db->escape($data['shipping_code']) . "'
    			, shipping_data = '" . $this->db->escape($data['shipping_data']) . "'
    			, poi = '" . $this->db->escape($data['poi']) . "'
    			, verify = '" . $this->db->escape($data['verify']) . "'
    			, payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "'
    			, payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "'
    			, payment_company = '" . $this->db->escape($data['payment_company']) . "'
    			, payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "'
    			, payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "'
    			, payment_city = '" . $this->db->escape($data['payment_city']) . "'
    			, payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "'
    			, payment_country = '" . $this->db->escape($data['payment_country']) . "'
    			, payment_country_id = '" . (int)$data['payment_country_id'] . "'
    			, payment_zone = '" . $this->db->escape($data['payment_zone']) . "'
    			, payment_zone_id = '" . (int)$data['payment_zone_id'] . "'
    			, payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "'
    			, payment_method = '" . $this->db->escape($data['payment_method']) . "'
    			, payment_code = '" . $this->db->escape($data['payment_code']) . "'
    			, comment = '" . $this->db->escape($this->db->clean_nonchar($data['comment'])) . "'
    			, total = '" . (float)$data['total'] . "'
    			, reward = '" . (float)$data['reward'] . "'
    			, affiliate_id = '" . (int)$data['affiliate_id'] . "'
    			, commission = '" . (float)$data['commission'] . "'
    			, language_id = '" . (int)$data['language_id'] . "'
    			, currency_id = '" . (int)$data['currency_id'] . "'
    			, currency_code = '" . $this->db->escape($data['currency_code']) . "'
    			, currency_value = '" . (float)$data['currency_value'] . "'
    			, ip = '" . $this->db->escape($data['ip']) . "'
    			, date_added = '".$data['date_added']."'
    			, date_modified = '".$data['date_modified']."'
    			, source_from = '".$data['source_from']."'
    			, user_agent = '".$data['user_agent']."'
    			,partner_code='".$this->db->escape($data['partner_code'])."'";
	
	    //
	    $order_id = $this->genOrderSN($datePart);
	    $maxtry=100;
	    $trytimes=0;
	    $res=false;
	    $res=$this->db->query($sql.", order_id ='".$order_id."'");
	    while(!$res && $trytimes<=$maxtry){
	        $trytimes++;
	        //sleep(1);
	        $order_id = $this->genOrderSN($datePart);
	        $res=$this->db->query($sql.", order_id ='".$order_id."'");
	    }
	     
	    if(!$res){
	        return false;
	    }
	
	    foreach ($data['products'] as $product) {
	        //if (!isset($product['additional']['date']) || (isset($product['additional']['date']) && $product['additional']['date'] == ''))
	        //    $product['additional']['date'] = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
	        $sql =  "INSERT INTO " . DB_PREFIX . "order_product SET
                         pdate='" . $this->db->escape($data['select_date']) . "',
                         order_id = '" . $order_id . "',
                         product_id = '" . (int)$product['product_id'] . "',
                         name = '" . $this->db->clean_nonchar($this->db->escape($product['name'])) . "',
                         model = '" . $this->db->escape($product['model']) . "',
                         p_image = '" . $this->db->escape($product['image']) . "',
                         prod_type = '" . (int)$product['prod_type'] . "',
                         shipping = '" . (int)$product['shipping'] . "',
                         quantity = '" . (int)$product['quantity'] . "',
                         price = '" . (float)$product['price'] . "',
                         total = '" . (float)$product['total'] . "',
                         tax = '" . (float)$product['tax'] . "',
                         promotion_price = '" . (float)$product['promotion']['promotion_price'] . "',
                         promotion_code  = '" .  $product['promotion']['promotion_code'] . "',
                         packing_type = '". (int)$product['packing_type']."',
                         combine = '". (int)$product['combine'] ."'";
	        //
	        $this->db->query($sql);
	   }
	
        foreach ($data['totals'] as $total) {
            $sql ="INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'";
            $this->db->query($sql);
        }
        
        foreach ($data['historys'] as $history){
            
            $sql = "INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $order_id . "', order_status_id = '" . (int)$history['order_status_id'] . "', notify = '0', comment = '', date_added = '".$history['date_added']."', operator='".$history['operator']."'";
            
            $this->db->query($sql);
        }
        return $order_id;
    }
	
	        
    public function genOrderSN($datePart = '') {
        // mt_srand((double) microtime() * 1000000);
        // return date('ymd') . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        $prefix = $datePart;
        $seqLen = 5;
        $len = strlen ( $prefix ) + $seqLen;
        $query = $this->db->query ( "select max(order_id) maxOrderId from ts_order o where o.order_id like '{$prefix}%' and CHAR_LENGTH(o.order_id)={$len} ", false );
        if ($query->num_rows > 0 && ! is_null ( $query->row ['maxOrderId'] )) {
            $maxOrderId = $query->row ['maxOrderId'];
            $maxNum = ( int ) substr ( $maxOrderId, strlen ( $prefix ), $seqLen );
            $maxNum += 1;
        } else {
            @$maxNum = mt_rand ( 500, 900 );
        }
        $seqPart = sprintf ( "%0{$seqLen}d", $maxNum );
    
        //$GLOBALS['_LOGDATA']['order_id']=$prefix . $seqPart;
    
        return $prefix . $seqPart;
    }
    
	public function existOrderVerify($verify) {
		$sql="SELECT order_id FROM " . DB_PREFIX . "order WHERE verify = '" . $verify . "'";
		$query = $this->db->query($sql,false);

		if ($query->num_rows > 0) {	
			return $query->row['order_id'];
		}
		else
		{
			return false;
		}	
	}
	
    /**
     * 生成取菜码
     * @param unknown $device_code
     * @return string
     */
	private function getPickupCode($device_code=''){
		
		if(empty($device_code))$device_code = date('md');
		$pickup_code=$device_code. str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
		
		return $pickup_code;
	}
	
	/*
	 * $data 需要检测的参数
	 * $rtye 是否检测全部参数，0只要有错误即返回，1检测完然后返回错误
	 * return 无错误返回 true，否则返回false
	 * */
	public  function chk_data(&$data,$rtype=0){
		//必填字段集合
		$required=array('payment_method','payment_code','telephone');
		foreach($required as $key=>$val)
		{
			if(!(isset($data[$val])&&!empty($data[$val])))
			{
				$this->error['chk_data'][]='non_'.$val;
			
				if(!$rtype)return false;
			}
		}
        if($data['shipping_required']){
		if(empty($data['device_code'])&&(empty($data['shipping_code'])||empty($data['shipping_data'])))
		{
			$this->error['chk_data'][]='non_device_code OR non_shipping_code&&shipping_data';
			if(!$rtype)return false;
		}
		
		
		
		
		
		if(!(isset($data['shipping_time'])&&!empty($data['shipping_time'])&&strtotime($data['shipping_time']))&&!(isset($data['pdate'])&&!empty($data['pdate'])))
		{
			$this->error['chk_data'][]='non_shipping_time_pdate';
			if(!$rtype)return false;
		}
		
		//此处条件可以支持小时，当开启小时售卖预售时可以灵活修改
		if (date ( "Y-m-d", strtotime ( $data ['shipping_time'] ) ) < (date("Y-m-d",time()+(int)$data['min_pre_times'])) &&  date ( "Y-m-d", strtotime ( $data ['pdate'] ) )  <  date ( "Y-m-d",(time ()+(int)$data['min_pre_times']))) {
		
			$this->error['chk_data'][]='non_allow_shipping_time_pdate';
			if(!$rtype)return false;	
		}
		
		}
		
     
		if($this->error['chk_data']) 
		return false;
		else 
		return true;
	}

     /**
     * 生成订单
     * @param unknown $data
     * @return string
     */
	public function create($data) {
		$common=new Common($this->registry);
			
		

		if(!isset($data['shipping_required']))
			$data['shipping_required']=1;
		
		if(!$this->chk_data($data,1))
		{ 
			$this->error['create']=$this->error['chk_data'];
			return false;
		}

		
		
		
		/* apiv2*/
		
		if(isset($data['device_code'])&&$data['device_code']){
			//方法归一到point
			$this->load->model('catalog/point');
			$pointInfo =$this->model_catalog_point->getPointByDeviceCode($data['device_code']);
			
		//$pointInfo = $this->getPointByDeviceCode($data['device_code']);
			
			
		if($pointInfo){
			$shipping_method = $pointInfo['name'].'[ '.$pointInfo['address'].' ]';
			$pickup_code = $this->getPickupCode($pointInfo['device_code']);
			$shipping_point_id = $pointInfo['point_id'];
			
			$data['shipping_code'] = $pointInfo['shipping_code'];
			$data['shipping_data'] = $pointInfo['shipping_data'];
			
		}else{
			$pickup_code = "";
			$shipping_method="";
			$shipping_point_id = 0;	
			$this->error['create'][]='err_device_code';
			return false;
		}
		}
		else 
		{//如果不存在point——id则存取配送方式和配送数据
			$shipping_method=$data['shipping_code'].'['.$data['shipping_data'].']';
			$pickup_code = $this->getPickupCode();
		}
	//	print_r($data);
		//$this->log_order->data=$data;
		$this->log_order->addLogData($data);

		
		//print_r($this->log_order->data);
		/* apiv2*/

		

		
        // $this->db->query('START TRANSACTION');//开始事务
		$sql = "INSERT INTO `" . DB_PREFIX . "order` SET 
    			  shipping_point_id='".(int)$shipping_point_id."'
    			, pickup_code='".$pickup_code."'
    			, pdate='".$this->db->escape($data['pdate'])."'
    			, shipping_time='".$this->db->escape($data['shipping_time'])."'
    			, device_code ='".$this->db->escape($data['device_code'])."' 
    			, tp_order_id ='".$this->db->escape($data['tp_order_id'])."' 
    			, sp_order_id ='".$this->db->escape($data['sp_order_id'])."' 
    			, invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "'
    			, store_id = '" . (int)$data['store_id'] . "'
    			, store_name = '" . $this->db->escape($data['store_name']) . "'
    			, store_url = '" . $this->db->escape($data['store_url']) . "'
    			, customer_id = '" . (int)$data['customer_id'] . "'
    			, customer_group_id = '" . (int)$data['customer_group_id'] . "'
    			, firstname = '" . $this->db->escape($data['firstname']) . "'
    			, lastname = '" . $this->db->escape($data['lastname']) . "'
    			, email = '" . $this->db->escape($data['email']) . "'
    			, telephone = '" . $this->db->escape($data['telephone']) . "'
    			, fax = '" . $this->db->escape($data['fax']) . "'
    			, shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "'
    			, shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "'
    			, shipping_company = '" . $this->db->escape($data['shipping_company']) . "'
    			, shipping_address_1 = '" . $this->db->escape($this->db->clean_nonchar($data['shipping_address_1'])) . "'
    			, shipping_address_2 = '" . $this->db->escape($this->db->clean_nonchar($data['shipping_address_2'])) . "'
    			, shipping_city = '" . $this->db->escape($data['shipping_city']) . "'
    			, shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "'
    			, shipping_country = '" . $this->db->escape($data['shipping_country']) . "'
    			, shipping_mobile = '" . $this->db->escape($data['shipping_mobile']) . "'
    			, shipping_phone = '" . $this->db->escape($data['shipping_phone']) . "'
    			, shipping_country_id = '" . (int)$data['shipping_country_id'] . "'
    			, shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "'
    			, shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "'
    			, shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "'
    			, shipping_method = '" . $this->db->escape($shipping_method) . "'
    			, shipping_code = '" . $this->db->escape($data['shipping_code']) . "'
    			, shipping_data = '" . $this->db->escape($data['shipping_data']) . "'
    			, poi = '" . $this->db->escape($data['poi']) . "'
    			, verify = '" . $this->db->escape($data['verify']) . "'
    			, payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "'
    			, payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "'
    			, payment_company = '" . $this->db->escape($data['payment_company']) . "'
    			, payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "'
    			, payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "'
    			, payment_city = '" . $this->db->escape($data['payment_city']) . "'
    			, payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "'
    			, payment_country = '" . $this->db->escape($data['payment_country']) . "'
    			, payment_country_id = '" . (int)$data['payment_country_id'] . "'
    			, payment_zone = '" . $this->db->escape($data['payment_zone']) . "'
    			, payment_zone_id = '" . (int)$data['payment_zone_id'] . "'
    			, payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "'
    			, payment_method = '" . $this->db->escape($data['payment_method']) . "'
    			, payment_code = '" . $this->db->escape($data['payment_code']) . "'
    			, comment = '" . $this->db->escape($this->db->clean_nonchar($data['comment'])) . "'
    			, total = '" . (float)$data['total'] . "'
    			, reward = '" . (float)$data['reward'] . "'
    			, affiliate_id = '" . (int)$data['affiliate_id'] . "'
    			, commission = '" . (float)$data['commission'] . "'
    			, language_id = '" . (int)$data['language_id'] . "'
    			, currency_id = '" . (int)$data['currency_id'] . "'
    			, currency_code = '" . $this->db->escape($data['currency_code']) . "'
    			, currency_value = '" . (float)$data['currency_value'] . "'
    			, ip = '" . $this->db->escape($data['ip']) . "'
    			, date_added = NOW()
    			, date_modified = NOW()
    			, source_from = '".$data['source_from']."'
    			, user_agent = '".$data['user_agent']."'
    			,partner_code='".$this->db->escape($data['partner_code'])."'";
		
    	//
		$order_id = $common->genOrderSN();
		$maxtry=100;
		$trytimes=0;
		$res=false;
		$res=$this->db->query($sql.", order_id ='".$order_id."'");
    	while(!$res && $trytimes<=$maxtry){
    		$trytimes++;
    		$this->log_order->error('order->model->create::fail::trytimes::'.$trytimes."::".$order_id.'::serialize(data):'.serialize($data));
    		//sleep(1);
    		$order_id = $common->genOrderSN();
    		$res=$this->db->query($sql.", order_id ='".$order_id."'");	
    	}
    	
    	if(!$res){
    		return false;
    	}

		foreach ($data['products'] as $product) {
            //if (!isset($product['additional']['date']) || (isset($product['additional']['date']) && $product['additional']['date'] == ''))
            //    $product['additional']['date'] = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
			$sql =  "INSERT INTO " . DB_PREFIX . "order_product SET 
                         pdate='" . $this->db->escape($data['select_date']) . "', 
                         order_id = '" . $order_id . "', 
                         product_id = '" . (int)$product['product_id'] . "', 
                         name = '" . $this->db->clean_nonchar($this->db->escape($product['name'])) . "', 
                         model = '" . $this->db->escape($product['model']) . "', 
                         p_image = '" . $this->db->escape($product['image']) . "', 
                         prod_type = '" . (int)$product['prod_type'] . "',
                         shipping = '" . (int)$product['shipping'] . "',
                         quantity = '" . (int)$product['quantity'] . "', 
                         price = '" . (float)$product['price'] . "', 
                         total = '" . (float)$product['total'] . "', 
                         tax = '" . (float)$product['tax'] . "',
                         promotion_price = '" . (float)$product['promotion']['promotion_price'] . "',
                         promotion_code  = '" .  $product['promotion']['promotion_code'] . "',
                         packing_type = '". (int)$product['packing_type']."',
                         combine = '". (int)$product['combine'] ."'";
			//
			$this->db->query($sql);

            $order_product_id = $this->db->getLastId();

            foreach ($product['option'] as $option) {
                $sql = "INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . $order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'";

                //
                $this->db->query($sql);
            }

            foreach ($product['download'] as $download) {
                $sql ="INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . $order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'";
               // 
                $this->db->query($sql);
            }
        }

        foreach ($data['totals'] as $total) {
            $sql ="INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'";
            
           // 
            $this->db->query($sql);
        }

	/*v2 新增发送短信模块*/
		if(isset($data['is_send'])&&$data['is_send']=='true'){
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($order_id);
			//$this->sendMail($order_id);		
		        if(empty($order_info['shipping_point_id'])){
		        	// 发送微信模板消息
		        	if($this->sendWeixinMsgShipping($order_id,$order_info))
            		// 发送宅配短信模板消息
            		$this->sendSmsShipping($order_id, $order_info);

            	}else {
            		// 发送微信模板消息
            		if($this->sendWeixinMsg($order_id, $order_info))
            		// 发送自提短信模板消息
            		$this->sendSms($order_id, $order_info);
            	}
				
		}
		
     

        if (isset($data['re_code'])&&$data['re_code']) {
        
           $this->log_order->info("推荐码::insert::reference:".$data['re_code'].':$customer_id'.$data['customer_id']);
            
            $sql ="INSERT INTO " . DB_PREFIX . "reference SET order_id ='{$order_id}',code='{$data['re_code']}', customer_id='{$data['customer_id']}', status=1, date_added = NOW()";
            //
            $this->db->query($sql);
        }
        
        if (!empty($data['promo'])) {    
            $this->log_order->info("活动码::insert::campaign_history:".$data['promo'].':$customer_id'.$data['customer_id']);
        
            $sql= "INSERT INTO  ts_campaign_history SET campaign_code = '{$data['promo']}', 
                                                        customer_id ='{$data['customer_id']}',
                                                        order_id ='{$order_id}',  
                                                        date_added=NOW(), 
                                                        status='1'";
            $this->db->query($sql);
        }
        
        
        $this->log_order->info('order->model->create:'.$order_id.'::serialize(data):'.serialize($data));

        return $order_id;
    }

    private function getDiscountRate($order_id) {
        try {
            $DB_PREFIX = DB_PREFIX;
            $sql = "select `value` from {$DB_PREFIX}order_total where order_id='{$order_id}' and `code`='total' ";
            $total = $this->db->query($sql,false)->row['value'];

            $sql = "select `value` from {$DB_PREFIX}order_total where order_id='{$order_id}' and `code`='sub_total' ";
            $subTotal = $this->db->query($sql,false)->row['value'];

            return (float)$total / (float)$subTotal;
        } catch (Exception $e) {
            return 1;
        }
    }

    private function addOrderTotal($order_id, $type, $value, $text, $order) {
        $rowData = array(
            array('order_id', $order_id, true),
            array('code', $type, true),
            array('title', $text, true),
            array('text', '￥' . number_format($value,4), true),
            array('value', $value, true),
            array('sort_order', $order, false)
        );
        DbHelper::insert('order_total', $rowData);
    }

    /**
     * 生产子订单
     * @param unknown $p_order_id
     * @param unknown $order_status_id
     * @return number
     */
    public function genSubOrder($p_order_id, $order_status_id) {
       // $this->log_order->debug('model->order->genSubOrder::order_id:' . $p_order_id.'orderstate:'.$order_status_id);
       // $data = $this->getOrder($p_order_id);


    //    $this->db->query("UPDATE `" . DB_PREFIX . "order` SET  pdate ='" . $key . "' ,date_modified = NOW() WHERE order_id = '" . $p_order_id . "'");
        // 订单为已付款
       /* if($order_status_id==2)
        {
            

        }
*/

        return true;
    }

    /**
     *  发送微信模板消息
     */
     public  function  sendWeixinMsg($order_id, $data){
        //发送模板消息
        $pdate = $data['pdate'];
        $querynew = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");

        $product=$querynew->rows;
        $strs='';
        foreach($product as $k=>$p){
            $strs=$strs."\r\n".$p['name']."  "."数量".$p['quantity']."份"."\r\n";
        }
        $language = new Language($data['language_directory']);
        $language->load($data['language_filename']);
        $language->load('sms/order');
        $pdate = explode("-", $pdate);
        $pdate2 = (int)$pdate[1] . '月' . (int)$pdate[2] . '日';

        $pickup_code = $data['pickup_code'];

        $msgs = sprintf($language->get('text_order_sms'), $order_id, $pickup_code, $pdate2, $data['shipping_method']);
        //发送微信模板消息功能
        
        
        $com = new Common($this->registry);
        
        $customer_id = $data['customer_id'];
        $openid = $com->findOpenIdwithCustomerID($customer_id);
        
        $this->log_order->debug('sendWeixinMsg :openid '.'-'. $openid.'-'.$strs.'-'.$msgs);
        
        if(!empty($openid)) {
            $template_id = 'qZT5nxRG97_TVOOwXzqaltgHmYBpx6yiSM-sMmvBtjU';
            $url = 'http://www.qingniancaijun.com.cn/index.php?route=common/home';///$this->url->link('common/home');
            $msg_data = array(
                'name' => array(
                    'value' => $strs,
                    'color' => '#FF0000'
                ),
                'remark' => array(
                    'value' => $msgs,
                    'color' => '#898989'
                )
            );
           
			$this->load->service('weixin/interface');
			$this->service_weixin_interface->send_msg_by_weixin($openid, $template_id, $url, $msg_data);
//            $com->send_msg_by_weixin($openid, $template_id, $url, $msg_data);
           $this->log_order->debug('sendWeixinMsg::end');
            $this->log_order->info($openid.':'.$template_id.':'.$url.serialize($msg_data));
   
           return true;
        }
        return false;

    }

    
    /**
     *  发送微信模板消息
     */
     public  function  sendWeixinMsgShipping($order_id, $data){
        //发送模板消息
        $querynew = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");
    
        $product=$querynew->rows;
        $strs='';
        foreach($product as $k=>$p){
            $strs=$strs."\r\n".$p['name']."  "."数量".$p['quantity']."份"."\r\n";
        }
        $language = new Language($data['language_directory']);
        $language->load($data['language_filename']);
        $language->load('sms/order');

        $shiping_time =  strtotime($data['shipping_time']);
        $hour1 = date('H:i', $shiping_time- 3600);
        $hour2 = date('H:i', $shiping_time);
        $pdate = date('m月d日', $shiping_time);
                    
        $pickup_code = $data['pickup_code'];

        $msgs = sprintf($language->get('text_order_sms2'), $order_id, $pdate, $hour1,$hour2);
    
        //发送微信模板消息功能
        $com = new Common($this->registry);
        $customer_id = $data['customer_id'];
       
        $openid = $com->findOpenIdwithCustomerID($customer_id);
        $this->log_sys->debug('IlexDebug:: sendWeixinMsgShipping :openid '.'-'. $openid.'-'.$strs.'-'.$msgs);
        $this->log_sys->debug('sendWeixinMsgShipping :openid '.'-'. $openid.'-'.$strs.'-'.$msgs);
        
        if ($openid) {
        	
        	$this->log_sys->debug('IlexDebug:: sendWeixinMsgShipping::start');
        	$this->log_order->debug('sendWeixinMsgShipping::start');
        	
            $template_id = 'qZT5nxRG97_TVOOwXzqaltgHmYBpx6yiSM-sMmvBtjU';
            $url = 'http://www.qingniancaijun.com.cn/index.php?route=common/home';///$this->url->link('common/home');
            $msg_data = array(
                'name' => array(
                    'value' => $strs,
                    'color' => '#FF0000'
                ),
                'remark' => array(
                    'value' => $msgs,
                    'color' => '#898989'
                )
            );
             
			$this->load->service('weixin/interface');
			$this->service_weixin_interface->send_msg_by_weixin($openid, $template_id, $url, $msg_data);
			
//            $com->send_msg_by_weixin($openid, $template_id, $url, $msg_data);
            
            $this->log_order->debug('sendWeixinMsg::end');
            $this->log_order->info($openid.':'.$template_id.':'.$url.serialize($msg_data));           
        return true;
        }
        return false;
    
    }
    /**
     * 发送配送通知微信
     * @param unknown $order_id
     * @param unknown $order_info
     * @return boolean
     */
    public function sendWeixinMsgHasShipping($order_id, $data){
    	
    	return false;
    }
    /**
     * 发送配送通知短信
     * @param unknown $order_id
     * @param unknown $order_info
     * @return boolean
     */
    public function sendSmsHasShipping($order_id, $order_info) {
    		
    	return false;
    }
    /**
     * 发送订单通知邮件
     * @param unknown $order_id
     * @param unknown $order_info
     * @return boolean
     */
    public function sendMail($order_id) {
        $this->load->model('mail/order');
        $this->model_mail_order->send($order_id);
    }

    /**
     * 发送订单成功短信
     * @param unknown $order_id
     * @param unknown $order_info
     * @return boolean
     */
     public function sendSms($order_id, $order_info) {
        $language = new Language($order_info['language_directory']);
        $language->load($order_info['language_filename']);
        $language->load('sms/order');
        $paddr = $order_info['shipping_method'];
        $pdate = $order_info['pdate'];
        if (SMS_OPEN == 'ON') {
 
         $this->log_sys->info('Send SMS for order ' . $order_id);
            
            $mobile_no = $order_info['telephone'];
            if ($mobile_no != '' && SMS_OPEN == 'ON') {
                $mobilephone = trim($mobile_no);
                //手机号码的正则验证
                if (mobile_check($mobilephone)) {
                    // send sms
					$sms=new Sms();

                    $pdate = explode("-", $pdate);
                    $pdate2 = (int)$pdate[1] . '月' . (int)$pdate[2] . '日';

                    $pickup_code = $order_info['pickup_code'];

                    $msg = sprintf($language->get('text_order_sms'), $order_id, $pickup_code, $pdate2, $paddr);

                    $this->log_sys->debug('IlexDebug:: 发送短信: ' . $msg);
                    $this->log_order->info('发送短信: ' . $msg);
                    
					$msg =$msg;
                    $sms->send($mobilephone, $msg);

                    $this->log_sys->debug('IlexDebug::Already Sended SMS ' . $mobilephone . ',content ' . $msg);                  
                    $this->log_order->info('Already Sended SMS ' . $mobilephone . ',content ' . $msg);
                    
                    return true;
                } else {
                    //手机号码格式不对
        
          $this->log_order->debug(' Wrong Number,dun send sms : sub_order_id ' . $order_id);
   
                    
                    return false;
                }
            }
        } else {
            $this->log_sys->debug('IlexDebug:: SMS_OPEN :' . SMS_OPEN . ';sub_order_id:' . $order_id);
            $this->log_order->debug('SMS_OPEN :' . SMS_OPEN . ';sub_order_id:' . $order_id);
            return false;
        }
    }
    
    /**
     * 发送订单成功短信
     * @param unknown $order_id
     * @param unknown $pdate
     * @param unknown $paddr
     * @param unknown $order_info
     * @return boolean
     */
     public function sendSmsShipping($order_id, $order_info) {
        $language = new Language($order_info['language_directory']);
        $language->load($order_info['language_filename']);
        $language->load('sms/order');
    
        if (SMS_OPEN == 'ON') {

            $this->log_order->info('Send SMS for order ' . $order_id);
            $mobile_no = $order_info['telephone'];
            if ($mobile_no != '' && SMS_OPEN == 'ON') {
                $mobilephone = trim($mobile_no);
                //手机号码的正则验证
                if (mobile_check($mobilephone)) {
                    // send sms
                    $sms=new Sms();
                    $shiping_time =  strtotime($order_info['shipping_time']);
                    $hour1 = date('H:i', $shiping_time- 3600);
                    $hour2 = date('H:i', $shiping_time);
                    $pdate = date('m月d日', $shiping_time);
                                
                    $pickup_code = $order_info['pickup_code'];
    
                    $msg = sprintf($language->get('text_order_sms2'), $order_id, $pdate, $hour1,$hour2);
   
                    $msg =$msg;
                    $sms->send($mobilephone, $msg);
    
     
                    
                    $this->log_order->info('Already Sended SMS ' . $mobilephone . ',content ' . $msg);
                    
                    return true;
                } else {
                    //手机号码格式不对

                    $this->log_order->debug(' Wrong Number,dun send sms : sub_order_id ' . $order_id);
                    return false;
                }
            }
        } else {

            $this->log_order->debug('SMS_OPEN :' . SMS_OPEN . ';sub_order_id:' . $order_id);
            return false;
        }
    }

    /**
     * 更新订单信息
     * @param unknown $data
     * @return unknown
     */
    public function modify($data) {
        $common = new Common($this->registry);

        $order_id = $this->session->data['order_id'];

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "'");

        foreach ($data['products'] as $product) {
           // if (!isset($product['additional']['date']) || (isset($product['additional']['date']) && $product['additional']['date'] == ''))
           //     $product['additional']['date'] = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));

           $sql = "INSERT INTO " . DB_PREFIX . "order_product SET 
                         pdate='" . $this->db->escape($product['additional']) . "', 
                         order_id = '" . $order_id . "', 
                         product_id = '" . (int)$product['product_id'] . "', 
                         name = '" . $this->db->escape($product['name']) . "', 
                         model = '" . $this->db->escape($product['model']) . "', 
                         quantity = '" . (int)$product['quantity'] . "', 
                         price = '" . (float)$product['price'] . "', 
                         total = '" . (float)$product['total'] . "', 
                         tax = '" . (float)$product['tax'] . "',
                         packing_type = '". (int)$product['packing_type']. "', 
                         combine = '" . (int)$product['combine'] . "'";
           if( isset($product['promotion']['promotion_price'] )) {
               $sql .= " , promotion_price = '" . (float)$product['promotion']['promotion_price'] . "',
                           promotion_code  = '" .  $product['promotion']['promotion_code'] . "'";
           }

           //
           $this->db->query($sql);
            $order_product_id = $this->db->getLastId();


            foreach ($product['option'] as $option) {
                $sql ="INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . $order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'";
               // 
                 
                $this->db->query($sql);
            }
            
            foreach ($product['download'] as $download) {
                $sql ="INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . $order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'";
              //  
                 
                $this->db->query($sql);
            
            }
        }

        foreach ($data['totals'] as $total) {
            $sql ="INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'";
           // 
             
            $this->db->query($sql);
        }

        $sql = "UPDATE `" . DB_PREFIX . "order` SET 
                      total = '" . (float)$data['total'] . "', 
                      reward = '" . (float)$data['reward'] . "',
                      pdate  = '" . $data['select_date']."',
                      date_modified = NOW()  
                      WHERE order_id ='" . $order_id . "'";
        //
        $this->db->query($sql);
        

        $this->log_order->info('model->order->modify::'.$order_id.';serialize(data):'.serialize($data));
        
        return $order_id;
    }
/*
    public function updateOrderStatus($order_id, $order_status_id) {
    	$this->log_sys->debug('model->order->updateOrderStatus: order_id ' . $order_id . ';order_status_id ' . $order_status_id);
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . $order_status_id . "',  date_modified = NOW() WHERE  order_id ='" . $order_id . "'");

        $this->saveHistory($order_id, $order_status_id, '');
    }*/
/*apiv2*/
	public function updateOrderStatus($order_id,$order_status_id,$partner_code='') {	
		$where=" WHERE order_id ='".$order_id."'";
		if(!empty($partner_code))$where.=" and partner_code='".$partner_code."'";
		$sql = "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . $order_status_id . "',  date_modified = NOW() ". $where;	
		
		$this->db->query($sql);
		
        $this->saveHistory($order_id,$order_status_id,$partner_code);         

        $this->log_order->info('model->order->updateOrderStatus::'.$order_id.';order_status_id:'.$order_status_id.';partner_code:'.$partner_code);
	}

    /**
     * @param $order_id
     * @param $order_status_id
     * @param $comment
     */
    public function saveHistory($order_id, $order_status_id, $comment,$msgsend=0) {
    	
        $sql = "INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '{$msgsend}', comment = '" . $this->db->escape($comment) . "', date_added = NOW(), operator='sys'";
        
        $this->db->query($sql); 
    }

    public function updateOrderComment($order_id, $comment) {
    	$this->log_sys->debug('model->order->updateOrderComment::order_id:' . $order_id . ';comment:' . $comment);
        $sql = "UPDATE `" . DB_PREFIX . "order` SET comment = '" . $this->db->escape($comment) . "',  date_modified = NOW() WHERE  order_id ='" . $order_id . "'";
 
        $this->db->query($sql);
    }

    public function updateOrderPayment($order_id, $payment) {
    	$this->log_sys->debug('model->order->updateOrderPayment::order_id:' . $order_id . ';serialize(payment):' .serialize($payment));
        $sql = "UPDATE `" . DB_PREFIX . "order` SET payment_code = '" . $this->db->escape($payment['code']) . "',  payment_method = '" . $this->db->escape($payment['title']) . "'WHERE  order_id ='" . $order_id . "'";
        
        
        $this->db->query($sql);
    }

    public function updateOrderShipping($order_id, $shipping) {
    	$this->log_sys->debug('model->order->updateOrderShipping::order_id:' . $order_id . ';serialize(shipping):' .serialize($shipping));
    	 
        $sql = "UPDATE `" . DB_PREFIX . "order` SET shipping_method = '" . $this->db->escape($shipping['title']) 
                      .", shipping_point_id = '" . $this->db->escape($shipping['shipping_point_id']) . "'"
        		      .", shipping_data = '" . $this->db->escape($shipping['shipping_data']) . "'"
        		      .", shipping_code = '" . $this->db->escape($shipping['shipping_code']) . "'"
        		      		
        . "'WHERE  order_id ='" . $order_id . "'";
        
        
        $this->db->query($sql);
    }

    public function updateOrderAddrress($order_id, $shipping_address) {
    	
        $data['payment_firstname'] = $shipping_address['firstname'];
        $data['payment_lastname'] = $shipping_address['lastname'];
        $data['payment_company'] = $shipping_address['company'];
        $data['payment_address_1'] = $shipping_address['address_1'];
        $data['payment_address_2'] = $shipping_address['address_2'];
        $data['payment_city'] = $shipping_address['city'];
        $data['payment_postcode'] = $shipping_address['postcode'];
        $data['payment_zone'] = $shipping_address['zone'];
        $data['payment_zone_id'] = $shipping_address['zone_id'];
        $data['payment_country'] = $shipping_address['country'];
        $data['payment_country_id'] = isset($shipping_address['country_id']) ? $shipping_address['country_id'] : '';
        $data['payment_address_format'] = $shipping_address['address_format'];

        $data['shipping_firstname'] = $shipping_address['firstname'];
        $data['shipping_lastname'] = $shipping_address['lastname'];
        $data['shipping_mobile'] = $shipping_address['mobile'];
        $data['shipping_phone'] = $shipping_address['phone'];
        $data['shipping_company'] = $shipping_address['company'];
        $data['shipping_address_1'] = $shipping_address['address_1'];
        $data['shipping_address_2'] = $shipping_address['address_2'];
        $data['shipping_city'] = $shipping_address['city'];
        $data['shipping_postcode'] = $shipping_address['postcode'];
        $data['shipping_zone'] = $shipping_address['zone'];
        $data['shipping_zone_id'] = $shipping_address['zone_id'];
        $data['shipping_country'] = $shipping_address['country'];
        $data['shipping_country_id'] = $shipping_address['country_id'];
        $data['shipping_address_format'] = $shipping_address['address_format'];

        $sql = "UPDATE `" . DB_PREFIX . "order` SET shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "'," .
            "shipping_mobile = '" . $this->db->escape($data['shipping_mobile']) . "', shipping_phone = '" . $this->db->escape($data['shipping_phone']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "',  payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "',date_modified = NOW() WHERE  order_id ='" . $order_id . "'";
        
        
        $this->db->query($sql);
        
        $this->log_sys->debug('order->updateOrderAddrress::'.$order_id.';serialize(data):'.serialize($data));

    }

    /**
     * 获取订单信息
     * @param unknown $order_id
     * @return multitype:unknown string number NULL |boolean
     */
    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . $order_id . "'",false);

        if ($order_query->num_rows) {
        	
        	
        	$pdate=(int)$order_query->row['pdate']?$order_query->row['pdate']:$order_query->row['shipping_time'];
        	
        	if(defined('ORDER_PAY_TIMEOUT_MINS')&&(int)ORDER_PAY_TIMEOUT_MINS)
        	$date_expired          = date('YmdHis',strtotime($order_query->row['date_added'])+(int)ORDER_PAY_TIMEOUT_MINS*60);//计算订单过期时间，为下单时间延后30分钟有效
        	else 
        	$date_expired          = date('Ymd',strtotime($pdate));//计算订单过期时间，为下单时间延后30分钟有效
        	
        	$shipping_iso_code_2 = '';
        	$shipping_iso_code_3 = '';
        	if($order_query->row['shipping_country_id']){
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            }
                
            }
            
            $shipping_zone_code = '';
if($order_query->row['shipping_zone_id']){
            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            }   
            }
            
            $payment_iso_code_2 = '';
            $payment_iso_code_3 = '';
if($order_query->row['payment_country_id']){
            
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            }
               
            }
            $payment_zone_code = '';
if($order_query->row['payment_zone_id']){
            
            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } 
            }

            $language_code = '';
            $language_filename = '';
            $language_directory = '';
            if($order_query->row['language_id']){
            $this->load->model('localisation/language');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_filename = $language_info['filename'];
                $language_directory = $language_info['directory'];
            } 
   
            }

            return array(
                'order_id' => $order_id,
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'email' => $order_query->row['email'],
                'shipping_firstname' => $order_query->row['shipping_firstname'],
                'shipping_mobile' => $order_query->row['shipping_mobile'],
                'shipping_phone' => $order_query->row['shipping_phone'],
                'shipping_lastname' => $order_query->row['shipping_lastname'],
                'shipping_company' => $order_query->row['shipping_company'],
                'shipping_address_1' => $order_query->row['shipping_address_1'],
                'shipping_address_2' => $order_query->row['shipping_address_2'],
                'shipping_postcode' => $order_query->row['shipping_postcode'],
                'shipping_city' => $order_query->row['shipping_city'],
                'shipping_zone_id' => $order_query->row['shipping_zone_id'],
                'shipping_zone' => $order_query->row['shipping_zone'],
                'shipping_zone_code' => $shipping_zone_code,
                'shipping_country_id' => $order_query->row['shipping_country_id'],
                'shipping_country' => $order_query->row['shipping_country'],
                'shipping_iso_code_2' => $shipping_iso_code_2,
                'shipping_iso_code_3' => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_method' => $order_query->row['shipping_method'],
            	'shipping_code' => $order_query->row['shipping_code'],
            	'shipping_data' => $order_query->row['shipping_data'],
                'shipping_point_id' => $order_query->row['shipping_point_id'],
                'payment_firstname' => $order_query->row['payment_firstname'],
                'payment_lastname' => $order_query->row['payment_lastname'],
                'payment_company' => $order_query->row['payment_company'],
                'payment_address_1' => $order_query->row['payment_address_1'],
                'payment_address_2' => $order_query->row['payment_address_2'],
                'payment_postcode' => $order_query->row['payment_postcode'],
                'payment_city' => $order_query->row['payment_city'],
                'payment_zone_id' => $order_query->row['payment_zone_id'],
                'payment_zone' => $order_query->row['payment_zone'],
                'payment_zone_code' => $payment_zone_code,
                'payment_country_id' => $order_query->row['payment_country_id'],
                'payment_country' => $order_query->row['payment_country'],
                'payment_iso_code_2' => $payment_iso_code_2,
                'payment_iso_code_3' => $payment_iso_code_3,
                'payment_address_format' => $order_query->row['payment_address_format'],
                'payment_method' => $order_query->row['payment_method'],
            		'payment_code' => $order_query->row['payment_code'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
                'order_status' => $order_query->row['order_status'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_filename' => $language_filename,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'date_modified' => $order_query->row['date_modified'],
            	'modify_time'                => strtotime($order_query->row['date_modified']),
            	'date_added'              => $order_query->row['date_added'],
            	'add_time'                => strtotime($order_query->row['date_added']),
            	'date_expired'            => $date_expired,
            	'expire_time'             => strtotime($date_expired),
                'ip' => $order_query->row['ip'],

                'device_code' => $order_query->row['device_code'],
                'pickup_code' => $order_query->row['pickup_code'],

                'shipping_time' => $order_query->row['shipping_time'],
                'shipping_confirm' => $order_query->row['shipping_confirm'],
                'pdate' => $order_query->row['pdate'],
                'invoice_type' => $order_query->row['invoice_type'],
                'invoice_head' => $order_query->row['invoice_head'],
                'invoice_name' => $order_query->row['invoice_name'],
                'invoice_content' => $order_query->row['invoice_content']
            );
        } else {
            return false;
        }
    }


    
    /**
	 * 更新订单状态
	 * @param unknown $order_id
	 * @param unknown $order_status_id
	 * @param string $comment
	 * @return boolean
	 */
	public function confirm($order_id, $order_status_id, $comment = '', $para = array()) {

		$order_info = $this->getOrder($order_id);

		$this->log_sys->debug('model->order->confirm() : order_id ' . $order_id . ' ; order_status ' . $order_status_id);

		if ($order_info) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = (int) $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			//更新订单状态发票变好
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int) $invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "', order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . $order_id . "'");
			$this->log_order->info('confirm：' . $order_id . ':$order_status_id:' . $order_status_id);
			

			/* 成功支付后的相关处理 */
			if (trim($order_status_id) == '2') {//1507241758 3元优惠劵    发放指定优惠劵付款成功时
				$this->load->model('account/coupon');
				$this->load->model('catalog/product');
				/*
				  $coupon_info=$this->model_account_coupon->getCouponByCode('1507241758');
				  if($coupon_info&&$coupon_info['coupon_id']&&$order_info['customer_id'])
				  {
				  $res=$this->model_account_coupon->addCoupon('1507241758',$order_info['customer_id']);
				  }
				 */

				/* 订单完成产品后续处理逻辑 */
				$this->load->model('sale/transaction');
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");
				$hasshipping = false;
				foreach ($order_product_query->rows as $order_product) {
					//减库存逻辑
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int) $order_product['quantity'] . ") WHERE product_id = '" . (int) $order_product['product_id'] . "' AND subtract = '1'");
					$this->log_sys->info('promotion_code'.serialize($order_product['promotion_code']));
					if($order_product['promotion_code']){
						$promo=EnumPromotionTypes::decodeCode($order_product['promotion_code']);
						
						$this->log_sys->info('promo'.serialize($promo));
						
						if($promo['pid']){
						if($promo['code']=='PROMOTION_SPECIAL'){	
							
							$this->db->query("UPDATE " . DB_PREFIX . "product_special SET quantity = (quantity - " . (int) $order_product['quantity'] . ") WHERE product_special_id = '" . (int) $promo['pid'] . "' AND quantity >'0'");
						
						}
						elseif($promo['code']=='PROMOTION_RUSH'){
							$this->db->query("UPDATE " . DB_PREFIX . "product_special SET quantity = (quantity - " . (int) $order_product['quantity'] . ") WHERE product_special_id = '" . (int) $promo['pid'] . "' AND quantity >'0'");
								
						}
						
						}
					}
					
					
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "' AND order_product_id = '" . (int) $order_product['order_product_id'] . "'");
					foreach ($order_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int) $order_product['quantity'] . ") WHERE product_option_value_id = '" . (int) $option['product_option_value_id'] . "' AND subtract = '1'");
					}

					//虚拟商品处理逻辑
					if ($order_product['shipping'] == '0') {
						if ($order_product['prod_type'] == '1') {//优惠券
							$coupons = $this->model_catalog_product->getProductCoupons($order_product['product_id']);

							for ($i = 0; $i < (int) $order_product['quantity']; $i++) {//购买虚拟商品数量
								foreach ($coupons as $key => $productcoupon) {//虚拟商品包含优惠券种类
									for ($j = 0; $j < $productcoupon['coupon_num']; $j++) {//每个优惠券种类所含个数
										if($productcoupon['is_tpl']){//是模版 需生成码
											unset($productcoupon['code']);
											$code = $this->model_account_coupon->createCoupon($productcoupon);
											$res = $this->model_account_coupon->addCoupon($code, $order_info['customer_id'], 0, $order_info['partner_code'], $order_id); //绑定
											$this->log_order->info('confirm>>addCoupon::' . $code . ',customer_id' . $order_info['customer_id'] . ',order_id' . $order_id . ',res:' . serialize($res));
											
										}else{//直接绑定
											$couponinfo = $this->model_account_coupon->getCouponInfo($productcoupon['coupon_id']);
											$res = $this->model_account_coupon->addCoupon($couponinfo['code'], $order_info['customer_id'], 0, $order_info['partner_code'], $order_id);
											$this->log_order->info('confirm>>addCoupon::' . $couponinfo['code'] . ',customer_id' . $order_info['customer_id'] . ',order_id' . $order_id . ',res:' . serialize($res));
										}
									}
								}
							}
						} elseif ($order_product['prod_type'] == '2') {//储值卡
							$trans_list = $this->model_catalog_product->getProductTrans($order_product['product_id']);
							
							for ($i = 0; $i < (int) $order_product['quantity']; $i++) {//购买虚拟商品数量
								foreach ($trans_list as $key => $t) {//虚拟商品包含储值卡种类
									for ($j = 0; $j < $t['num']; $j++) {//每个储值卡种类所含个数
										if($t['is_tpl']){//是模版 需生成码
											$amount = $t['value'];
											$data = $this->format_data($amount);
											$code = $this->model_sale_transaction->get_recharge_key($data);
											$res = $this->model_sale_transaction->addTransaction($order_info['customer_id'], $code);
											$this->log_order->info('confirm>>addTransaction::' . $code . ',customer_id' . $order_info['customer_id'] . ',order_id' . $order_id . ',res:' . serialize($res));
										}else{//直接绑定
											$res = $this->model_sale_transaction->addTransaction($order_info['customer_id'], $t['trans_code']);
											$this->log_order->info('confirm>>addTransaction::' . $t['trans_code'] . ',customer_id' . $order_info['customer_id'] . ',order_id' . $order_id . ',res:' . serialize($res));
										}
									}
								}
							}
						}
					} else {
						$hasshipping = true;
					}
				}


				if (!$hasshipping) {
					//更新订单状态无需配送系统直接发货完成
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int) $invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "', order_status_id = '5', date_modified = NOW() WHERE order_id = '" . $order_id . "'");
					$this->log_order->info('confirm：无需配送系统直接发货完成' . $order_id . ':$order_status_id:5');
					$this->saveHistory($order_id, 5, '无需配送系统直接发货完成');
				} else {
					if (empty($order_info['shipping_point_id'])) {
						// 发送微信模板消息
						if(!$this->sendWeixinMsgShipping($order_id, $order_info))
						// 发送宅配短信模板消息
						{
						 if(!$this->sendSmsShipping($order_id, $order_info))
						{
							$msgsend=0;
						}
						else 
						{
							$msgsend=1;
						}
						}
						else 
						{
							$msgsend=2;
						}
	
					} else {
						// 发送微信模板消息
						if(!$this->sendWeixinMsg($order_id, $order_info))
						{// 发送自提短信模板消息
							
						if($this->sendSms($order_id, $order_info))
						{
							$msgsend=0;
						}
						else 
						{
							$msgsend=1;
						}
						
						}
						else 
						{
							$msgsend=2;
						}
	
					}
					
					$this->saveHistory($order_id, $order_status_id, $comment,$msgsend);
				}


				if ($para && $para['trade_no']) {
					//更新订单状态无需配送系统直接发货完成
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET payment_trade_no = '" . $para['trade_no'] . "', date_modified = NOW() WHERE order_id = '" . $order_id . "'");
					$this->log_order->info('支付成功交易号::' . $order_id . ':payment_trade_no:' . $para['trade_no']);
				}
			}
			else 
			{
				$this->saveHistory($order_id, $order_status_id, $comment);
			}

			/* 其它处理 */

			$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . $order_id . "'");

			foreach ($order_total_query->rows as $order_total) {
				$this->load->model('total/' . $order_total['code']);

				if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
					$this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
				}
			}

			// Send out any gift voucher mails
			if ($this->config->get('config_complete_status_id') == $order_status_id) {
				$this->load->model('checkout/voucher');

				$this->model_checkout_voucher->confirm($order_id);
			}
if($order_info['customer_id']){
	$this->load->model('account/customer');
			$this->model_account_customer->updateGrade($order_info['customer_id']);
}

			
			// Send out order confirmation mail
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/order');


			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $order_info['language_id'] . "'");

			if ($order_status_query->num_rows) {
				$order_status = $order_status_query->row['name'];
			} else {
				$order_status = '';
			}

			/*
			  $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");
			  $order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "' ORDER BY sort_order ASC");
			  $order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . $order_id . "'");
			 */
			// gen sub-orders
			//  $sub_exist = $this->genSubOrder($order_id, $order_status_id);
			// Admin Alert Mail
			if ($this->config->get('config_alert_mail')) {
				$this->sendAlertMailMsg($order_id, $order_info);
			}

			return true;
		} else {
			return false;
		}
	}

	public function sendAlertMailMsg($order_id,$order_info){

		$subject = sprintf($language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);
		
		// Text
		$text = $language->get('text_new_received') . "\n\n";
		$text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
		$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
		$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
		$text .= $language->get('text_new_products') . "\n";
		
		foreach ($order_product_query->rows as $result) {
			$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
		
			$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");
		
			foreach ($order_option_query->rows as $option) {
				$text .= chr(9) . '-' . $option['name'] . (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
			}
		}
		
		$text .= "\n";
		
		$text .= $language->get('text_new_order_total') . "\n";
		
		foreach ($order_total_query->rows as $result) {
			$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
		}
		
		$text .= "\n";
		
		if ($order_info['comment'] != '') {
			$comment = ($order_info['comment'] . "\n\n" . $comment);
		}
		
		if ($comment) {
			$text .= $language->get('text_new_comment') . "\n\n";
			$text .= $comment . "\n\n";
		}
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($order_info['store_name']);
		$mail->setSubject($subject);
		$mail->setText($text);
		$mail->send();
		
		// Send to additional alert emails
		$emails = explode(',', $this->config->get('config_alert_emails'));
		
		foreach ($emails as $email) {
			if ($email && preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email)) {
				$mail->setTo($email);
				$mail->send();
			}
		}
		
	}
	
	/**
	 * 拼装 生成充值码 参数
	 * @param type $amount
	 * @return type
	 */
	private function format_data($amount) {
		//构造数据
		$date = date('Y-m-d H:i:s', time());
		$data['operator'] = 'auto_create';
		$data['prefix'] = 'at';
		$data['length'] = 8;
		$data['batch'] = 1;
		$data['value'] = $amount;
		$data['date_start'] = $date;
		$data['date_end'] = $date;
		return $data;
	}
	public function update($order_id, $order_status_id, $comment = '', $notify = false) {
        $order_info = $this->getOrder($order_id);
        $this->log_sys->debug('order->update::'.$order_id.';status_id:'.$order_status_id.';comment:'.$comment.';notify:'.$notify);
        
        if ($order_info && $order_info['order_status_id']) {
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . $order_id . "'");
            $this->log_order->info('confirm：'.$order_id.':$order_status_id:'.$order_status_id);
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW(), operator='sys'");

            // Send out any gift voucher mails
            if ($this->config->get('config_complete_status_id') == $order_status_id) {
                $this->load->model('checkout/voucher');

                $this->model_checkout_voucher->confirm($order_id);
            }

            if ($notify) {
                $language = new Language($order_info['language_directory']);
                $language->load($order_info['language_filename']);
                $language->load('mail/order');

                $subject = sprintf($language->get('text_update_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

                $message = $language->get('text_update_order') . ' ' . $order_id . "\n";
                $message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

                $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

                if ($order_status_query->num_rows) {
                    $message .= $language->get('text_update_order_status') . "\n\n";
                    $message .= $order_status_query->row['name'] . "\n\n";
                }

                if ($order_info['customer_id']) {
                    $message .= $language->get('text_update_link') . "\n";
                    $message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
                }

                if ($comment) {
                    $message .= $language->get('text_update_comment') . "\n\n";
                    $message .= $comment . "\n\n";
                }

                $message .= $language->get('text_update_footer');

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');
                $mail->setTo($order_info['email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($order_info['store_name']);
                $mail->setSubject($subject);
                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                $mail->send();
            }
        }
    }

    public function editInvoiceDetail($order_id, $data) {
    	
         
        $this->log_sys->debug('order->editInvoiceDetail::'.$order_id.';serialize(data):'.serialize($data));
        $this->log_order->info('order->editInvoiceDetail::'.$order_id.';serialize(data):'.serialize($data));
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_type = '" . (int)$this->db->escape($data['invoice_type'])
            . "',invoice_head = '" . (int)$this->db->escape($data['invoice_head'])
            . "',invoice_content = '" . (int)$this->db->escape($data['invoice_content'])
            . "',invoice_name = '" . $this->db->escape($data['invoice_name']) . "' WHERE  order_id ='" . $order_id . "'");
    }

    public function editShippingTime($order_id, $data) {
    	$this->log_sys->debug('order->editInvoiceDetail::'.$order_id.';serialize(data):'.serialize($data));
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET shipping_time = '" . (int)$this->db->escape($data['shipping_time']) . "',shipping_confirm = '" . (int)$this->db->escape($data['shipping_confirm']) . "' WHERE  order_id ='" . $order_id . "'");
    
    }

    public function editShippingPoint($order_id, $shipping_point_id) {
    	$this->log_sys->debug('order->editInvoiceDetail::'.$order_id.';shipping_point_id:'.$shipping_point_id);
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET shipping_point_id = '" . (int)$shipping_point_id . "' WHERE  order_id ='" . $this->db->escape($order_id) . "'");
    }

    public function updateOrderPickupCode($order_id, $pickup_code, $device_code) {
    	$this->log_sys->debug('order->updateOrderPickupCode::'.$order_id.';pickup_code:'.$pickup_code.';device_code:'.$device_code);
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET pickup_code = '" . $this->db->escape($pickup_code)
            . "',device_code='" . $this->db->escape($device_code) . "' WHERE  order_id ='" . $this->db->escape($order_id) . "'");
    }




    public function getHistoryPoints($data) {
        $sql = "SELECT shipping_point_id FROM `" . DB_PREFIX . "order` WHERE order_status_id >0";

        if (isset($data['filter_customer_id']) && !is_null($data['filter_customer_id'])) {
            $sql .= " AND customer_id=" . (int)$data['filter_customer_id'];
        }

        if (isset($data['filter_point_id']) && !is_null($data['filter_point_id'])) {
            $sql .= " AND shipping_point_id !=" . (int)$data['filter_point_id'];
        }

        $sql .= " group by shipping_point_id ORDER BY date_added DESC";

        $query = $this->db->query($sql);

        return $query->rows;
    }
}

?>