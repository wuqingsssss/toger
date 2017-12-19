<?php
class ModelSaleCouponDistAdmin extends Model {

    //新增问题
    public function inster_conponinfo($datab){
        $coupon_info= array();
        if(!$datab){
            return false;
        }
        $conpon_title=trim($datab['conpon_title']);
        $sql="SELECT * FROM " . DB_PREFIX . "coupon WHERE code='".$conpon_title."'";
        $sql.= " AND date_start <=DATE(NOW()) AND date_end >=DATE(NOW())";    // 优惠券有效期内
        $query = $this->db->query($sql);
            
        //$error_num=0;
        $error_array=array();
        if(!$query->row){
            $error_array[]="not_find_conpon:{$conpon_title}";
          //  $error_num=$error_num+1;
        return $error_array;
        }
        else{
            $coupon_info = $query->row;
        }

        $coupon_id  = $coupon_info['coupon_id'];
   
        //获取电话号码清单
        $coupon_value=array();
        foreach($datab as $key => $value) {
            if (strstr($key, "conpon_value_")) {
                if ($value) {
                    $coupon_value[$value] = $value;
                }
            }
        }


        foreach ($coupon_value as $mobile) {
            //$mobile = trim($mobile);
           
            $ret = $this->addCouponToCustomer($coupon_id, $mobile, $coupon_info, 0, "999", $error_array);
            if(!$ret){
                $error_array[]="add coupon failed:{$conpon_title}|mobile:{$mobile}";
                continue;
            }
	    }
	    
        
        return $error_array;

    }
    
    //新增礼包问题
    public function addPacket($data){
        if(!$data || empty($data['packet_id'])){
            return false;
        }     
        $packet_id = $this->db->escape($data['packet_id']);
         
        //获取电话号码清单
        $mobiles=array();
        foreach($data as $key => $value) {
            if (strstr($key, "mobile_")) {
                if ($value) {
                    $mobiles[$value] = $value;
                }
            }
        }
    
    
        foreach ($mobiles as $mobile) {
            //$mobile = trim($mobile);
             
            $ret = $this->addPacket2Customer($packet_id,  $mobile, 0, "999", $error_array);
            if(!$ret){
                $error_array[]="add coupon failed:{$data['packetname']}|mobile:{$mobile}";
                continue;
            }
        }
         
    
        return $error_array;
    
    }
    
    

    function addPacketByFile($file){
        global $config;
        global $log;
        $config = $this->config;
        $log = $this->log;
 
        register_shutdown_function('error_handler');
    
        $database =& $this->db;
        ini_set("memory_limit", "512M");
        ini_set("max_execution_time", 180);
        //set_time_limit( 60 );
        chdir('../system/PHPExcel');
        require_once('Classes/PHPExcel.php');
        chdir('../../admin');
        $inputFileType = PHPExcel_IOFactory::identify($file);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $reader = $objReader->load($file);
        $data = $reader->getSheet(0);//读第一个工作表
        $k = $data->getHighestRow();//读行
        $error_array=array();
        for ($i = 1; $i < $k; $i += 1) { //从第二行开始读
            $coupon_code = trim($this->getCell($data, $i, 1)); //获取第一列
            $mobile = trim($this->getCell($data, $i, 2));//获取第二列
            if(!$coupon_code){
                continue; //为空跳出
            }
            if(!$mobile){
                continue;//为空跳出
            }
    
            $query=$this->db->query("select * from ".DB_PREFIX."coupon where code='{$coupon_code}'");
            if(!$query->row){
                $error_array[]="not_find_conpon:{$coupon_code}|mobile:{$mobile}";
    
                continue;
            }
            $coupon_id=$query->row['coupon_id'];
            $tmp = $this->db->query("select * from " . DB_PREFIX . "customer where mobile='{$mobile}'");
            if (!$tmp->row) {
                $error_array[]="not_find_conpon:{$coupon_code}|mobile:{$mobile}";
                continue;
            }
    
            $customer_id = $tmp->rows[0]['customer_id'];
    
            $duration = $query->rows[0]['duration'];
            $sqlstr =",date_add = '".date('Y-m-d H:i:s',time())."'";
            $sqlstr.=",date_update = '".date('Y-m-d H:i:s',time())."'";
            if($duration<=0){  // 无限制，缺省为30天
                $sqlstr.=",date_limit = '".date('Y-m-d',time()+29*86400)."'";
            }
            else{  // 0 为30天，1当天有效，7包括当天1个星期有效
                $sqlstr.=",date_limit = '".date('Y-m-d',time()+($duration-1)*86400)."'";
            }
    
            $this->db->query("insert into ". DB_PREFIX ."coupon_to_customer set coupon_id={$coupon_id},customer_id={$customer_id}".$sqlstr);
        }
    
        chdir('../../..');
        return $error_array;
    
    }
    
    /**
     * 红包加入个人账户
     * @param unknown $packet_id
     * @param unknown $customer_id
     * @param unknown $pick_tyle
     */
    public function addPacket2Customer($packet_id, $mobile, $pick_tyle=0,$point_id=0,$partner_code=''){
        $tmp = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE mobile='{$mobile}'");
        if (!$tmp->row) {
            $error_array[]="can't find customer id: mobile={$mobile}";
            return false;
        }
        else{
            $customer_id = $tmp->row['customer_id'];
        } 
        
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
       $query = $this->db->query($sql);
   
       if ($query->num_rows) {
           $coupon_id = $query->row['coupon_id'];
           $duration  = $query->row['duration'];
   
           // 当前用户关联次数
           $sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "coupon_to_customer WHERE customer_id = '{$customer_id}' and coupon_id= '{$coupon_id}'";
           $usescount = $this->db->query($sql);
           // 所有用户关联次数
           $sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "coupon_to_customer WHERE coupon_id= '{$coupon_id}'";
           $usestotal = $this->db->query($sql);
   
   
           //当前用户关联次数判断
           if((int)$usescount->row['total']!=0 && $query->row['uses_customer']>0 && (int)$usescount->row['total'] >= $query->row['uses_customer']){
   
               return -1;
           }
   
           //所有用户关联总次数判断
           if((int)$usestotal->row['total']!=0 && $query->row['uses_total']>0 && (int)$usestotal->row['total'] >= $query->row['uses_total']){
               return -2;
           }
   
           $date_limit = $query->row['date_end'];
            
           $res=$this->addCoupon2Customer((int)$coupon_id,(int)$customer_id, (int)$duration, $date_limit, $point_id,$partner_code,$order_id);
   
   
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
    * 添加优惠劵到指定用户
    * @param unknown $coupon_id
    * @param unknown $customer_id
    * @param unknown $point_id
    * @param unknown $partner_code
    */
   private function addCoupon2Customer($coupon_id, $customer_id, $duration, $date_limit='', $point_id=0, $partner_code='',$order_id=''){
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
        
     
       return $ret;
   }
   
    /**
     * 添加优惠劵到指定用户
     * @param unknown $coupon_id
     * @param unknown $mobile
     * @param unknown $coupon_info
     * @param number $point_id
     * @param string $partner_code
     * @return unknown
     */
     private function addCouponToCustomer($coupon_id, $mobile, $coupon_info=array(),  $point_id=0, $partner_code='', &$error_array){
        $date_end   = '';
        $duration   = $coupon_info['duration'];
        $date_limit = $coupon_info['date_end'];
        
        $tmp = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE mobile='{$mobile}'");
        if (!$tmp->row) {
            $error_array[]="can't find customer id: mobile={$mobile}";
            return false;
        }
        else{
            $customer_id = $tmp->row['customer_id'];
        }
        
		// 当前用户关联次数
	    $sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "coupon_to_customer WHERE customer_id = '{$customer_id}' and coupon_id= '{$coupon_id}'";
	    $usescount = $this->db->query($sql);
	    // 所有用户关联次数
	    $sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "coupon_to_customer WHERE coupon_id= '{$coupon_id}'";
	    $usestotal = $this->db->query($sql);
	    
	    //当前用户关联次数判断
	    if((int)$usescount->row['total']!=0 && (int)$usescount->row['total'] >= $coupon_info['uses_customer']){
	        $error_array[]="customer use count overflow: total={(int)$usescount->row['total']}";
	        return false;
	    }
	    
	    //所有用户关联总次数判断
	    if((int)$usestotal->row['total']!=0 && (int)$usestotal->row['total'] >= $coupon_info['uses_total']){
	        $error_array[]="all customer use total overflow: total={(int)$usestotal->row['total']}";
	        return false;
	    }
             
        if($point_id>0) {
            $sqlstr.=",point_id = '$point_id'";
        }
        if(!empty($partner_code)){
            $sqlstr.=",partner_code = '$partner_code'";
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
             if(mobile_check($mobile)) {
                $openid = $this->findOpenId($mobile);
                 
                if(isset($openid) && isset($coupon_info)) {
                    $template_id = 'w1M24PqCmGxb32v1fJj-QyXgaXxr0jFae85zG0CmoYQ';
                    $url = $this->url->link('common/home');
    
                    $msg_data = array(
                        'first' => array(
                            'value' => "您获得一张".$coupon_info['name']."优惠券，请在个人中心内查看。付款时请点击“使用优惠券”直接使用！",
                            'color' => '#0AA39A'
                        ),
                        'orderTicketStore' => array(
                            'value' => "不包含已特价之内的所有菜品",  //使用范围
                            'color' => '#0AA39A'
                        ),
                        'orderTicketRule' => array(
                            'value' => $coupon_info['usage']." 有效期:".$date_end,    //使用规则
                            'color' => '#0AA39A'
                        ),
                        'remark' => array(
                            'value' => "你每日忙碌，我牵肠挂肚【点击查看优惠券】",
                            'color' => '#0AA39A'
                        )
                         
                    );
					
				$this->load->service('weixin/interface','service');
				$this->service_weixin_interface->send_msg_by_weixin($openid,$template_id,$url,$msg_data);
//                    $commons= new Common($this->registry);
//                    $commons->send_msg_by_weixin($openid,$template_id,$url,$msg_data);
                }
            }
        }
         
        return $ret;
    }

     //分页方法
    function get_coupon_info($data){
        /*
        $result=$this->db->query("select * from ". DB_PREFIX ."coupon_to_customer");
        if(!$result){
            return false;
        }
        return $result->rows;
        */
            $DB_PREFIX = DB_PREFIX;
            $sqlBody = "FROM {$DB_PREFIX}coupon_to_customer";
            $total=DbHelper::getSingleValue('select count(*) '.$sqlBody,0);

            $sql = 'select * ' . $sqlBody;
            $sort_data = array(
                'name',
                'code',
                'discount',
                'date_start',
                'date_end',
                'duration',
                'status'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY coupon_id";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " DESC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);


            $rows= $query->rows;
        foreach($rows as $key=>$a_value){
            $userinfo_tmp=$this->db->query("select * from {$DB_PREFIX}customer where customer_id=".$a_value['customer_id']);
            if($userinfo_tmp->row){
                $rows[$key]['userinfo']=$userinfo_tmp->rows[0];
            }else{
                $rows[$key]['userinfo']=array();
            }
            $couponinfo_tmp=$this->db->query("select * from {$DB_PREFIX}coupon where coupon_id=".$a_value['coupon_id']);
            if($couponinfo_tmp->row){
                $rows[$key]['couponinfo']=$couponinfo_tmp->rows[0];
            }else{
                $rows[$key]['couponinfo']=array();
            }
        }



            return array(
                "total"=>$total,
                "rows"=>$rows
            );

    }

    /**
     * 获取优惠券信息
     * @param unknown $coupon_id
     * @return string
     */
    private function getCouponInfo($coupon_id){
        $sql="SELECT * FROM " . DB_PREFIX . "coupon WHERE coupon_id='{$coupon_id}'";
    
        $query=$this->db->query($sql);
    
        return $query->row;
    }

    private function findOpenId($mobile){
        $sql = "SELECT tso.openid FROM ts_customer tc, ts_openid_info tso WHERE tc.mobile = '{$mobile}' AND tso.customer_id = tc.customer_id";
        
        $query=$this->db->query($sql);
    
        return  $query->row['openid'];
    }
    
    function deleteConpon($coupon_id){
        $this->db->query("delete from ". DB_PREFIX ."coupon_to_customer where coupon_customer_id={$coupon_id}" );
    }

//导入excel方法

    function import_excel($file){
        global $config;
        global $log;
        $config = $this->config;
        $log = $this->log;
       // set_error_handler(array($this, 'error_handler_for_export'));
 
       // register_shutdown_function('fatal_error_shutdown_handler_for_export');
        register_shutdown_function('error_handler');
        
        
        $database =& $this->db;
        ini_set("memory_limit", "512M");
        ini_set("max_execution_time", 180);
        //set_time_limit( 60 );
        chdir('../system/PHPExcel');
        require_once('Classes/PHPExcel.php');
        chdir('../../admin');
        $inputFileType = PHPExcel_IOFactory::identify($file);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $reader = $objReader->load($file);
        $data = $reader->getSheet(0);//读第一个工作表
        $k = $data->getHighestRow();//读行
        $error_array=array();
        for ($i = 1; $i < $k; $i += 1) { //从第二行开始读
            $coupon_code = trim($this->getCell($data, $i, 1)); //获取第一列
            $mobile = trim($this->getCell($data, $i, 2));//获取第二列
            if(!$coupon_code){
                continue; //为空跳出
            }
            if(!$mobile){
                continue;//为空跳出
            }

            $query=$this->db->query("select * from ".DB_PREFIX."coupon where code='{$coupon_code}'");
            if(!$query->row){
                $error_array[]="not_find_conpon:{$coupon_code}|mobile:{$mobile}";

                continue;
            }
            $coupon_id=$query->row['coupon_id'];
            $tmp = $this->db->query("select * from " . DB_PREFIX . "customer where mobile='{$mobile}'");
            if (!$tmp->row) {
                $error_array[]="not_find_conpon:{$coupon_code}|mobile:{$mobile}";
                continue;
            }

            $customer_id = $tmp->rows[0]['customer_id'];
            
            $duration = $query->rows[0]['duration'];
            $sqlstr =",date_add = '".date('Y-m-d H:i:s',time())."'";
            $sqlstr.=",date_update = '".date('Y-m-d H:i:s',time())."'";
            if($duration<=0){  // 无限制，缺省为30天
                $sqlstr.=",date_limit = '".date('Y-m-d',time()+29*86400)."'";
            }
            else{  // 0 为30天，1当天有效，7包括当天1个星期有效
                $sqlstr.=",date_limit = '".date('Y-m-d',time()+($duration-1)*86400)."'";
            }
            
            $this->db->query("insert into ". DB_PREFIX ."coupon_to_customer set coupon_id={$coupon_id},customer_id={$customer_id}".$sqlstr);
        }

        chdir('../../..');
        return $error_array;

    }



    function getCell(&$worksheet, $row, $col, $default_val = '') {
        $col -= 1; // we use 1-based, PHPExcel uses 0-based column index
        $row += 1; // we use 0-based, PHPExcel used 1-based row index
        return ($worksheet->cellExistsByColumnAndRow($col, $row)) ? $worksheet->getCellByColumnAndRow($col, $row)->getValue() : $default_val;
    }

}

?>