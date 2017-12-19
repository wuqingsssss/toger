<?php
class ModelAccountCustomer extends Model {
    private $active_code;

    protected function setActiveCode($active_code) {
        $this->active_code = $active_code;
    }

    protected function getActiveCode() {
        return $this->active_code;
    }

    public function addCustomer($data, $flag=0) {
        $active_code = md5(uniqid());
        $this->setActiveCode($active_code);
        if(!isset($data['status'])){
            $data['status'] = 1;
              if ($this->config->get('config_active') == '1') 
               {
                  $data['status'] = 0;
               }
        }
        //如果密码为空则自动生成6位随机密码
        if(empty($data['password']))$data['password']=str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        if(($customer_id=$this->getCustomerByMobile($data['mobile'])))//,'',-1,false
        {//如果存在账户 则返回更新用户账号状态为新注册用户
        	$this->db->query("UPDATE " . DB_PREFIX . "customer SET active_code = '" . $active_code . "', store_id = '" . (int)$this->config->get('config_store_id') . "',  mobile = '" . $this->db->escape($data['mobile']) . "',password = '" . $this->db->escape(md5($data['password'])) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "', status = '" . (int)$data['status'] . "',is_delete=0, date_added = NOW() WHERE customer_id = '" . (int)$customer_id . "'");
        }else{
        	$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET active_code = '" . $active_code . "', store_id = '" . (int)$this->config->get('config_store_id') . "',  mobile = '" . $this->db->escape($data['mobile']) . "',password = '" . $this->db->escape(md5($data['password'])) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
        	//$customer_id = $this->db->getLastId();重新查询id
        	$customer_id=$this->getCustomerByMobile($data['mobile']);
        }
       
      
        if(!$customer_id) return false;
//添加推荐码
        if($data['reference']){
            $re_id=$this->db->escape($data['reference']);
           $result=$this->getReference($re_id);
            if($result) {
                $this->db->query("insert into " . DB_PREFIX . "reference set customer_id='{$customer_id}',code='{$re_id}',status=0, date_added = NOW()");
            }
            $this->session->data['reference'] =$re_id;
        }
//绑定openid
        
        if(isset($this->session->data['platform'])){
        	$platform=$this->session->data['platform'];
        
        	if ( $this->customer->existPlatForm($platform['platform_code'],$customer_id)) {
        		$this->customer->updatePlatForm($platform['openid'],$platform['platform_code'],$customer_id);
        	} else {
        		$this->customer->addPlatForm($platform['openid'],$platform['platform_code'],$customer_id);
        	}
        }

        if (isset($data['invite_code']) && $data['invite_code'] != '0') {
            $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE code = '" . $this->db->escape($data['invite_code']) . "' AND status = '1'");
            $send_id = 0;
            if ($customer_query->num_rows) {

                $send_id = $customer_query->row['customer_id'];

                $this->db->query("INSERT INTO " . DB_PREFIX . "invited_history SET invited_id = '" . (int)$customer_id . "',  customer_id = '" . (int)$send_id . "',  date_modified= NOW() , date_added = NOW()");
                if ($this->config->get('config_active') != '1' && (int)$this->config->get('config_invite_points') > 0) {
                    $this->addReward($send_id, $this->language->get('text_reward_system'), $this->config->get('config_invite_points'));
                }
            }
        }

        if (!$this->config->get('config_customer_approval')) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int)$customer_id . "'");
        }

        $email = $data['email'];
        if (!empty($email)) {
            $this->editCustomerTableField($customer_id, 'email', $email);
        }

        if (isset($data['name'])) {
            $this->editCustomerTableField($customer_id, 'firstname', $data['name']);
        }
        if (isset($data['salution'])) {
            $this->editCustomerTableField($customer_id, 'salution', $data['salution']);
        }

        //增加积分发放的逻辑
        if ($this->config->get('config_register_reward')) {
            $this->setRegisterRewardPoints($customer_id, $this->config->get('config_register_reward'));
        }

        //增加优惠券发送逻辑
        if($this->config->get('config_register_coupon')){
            $this->load->model('account/coupon');

            $coupon_code=$this->config->get('config_register_coupon');

            $coupon_info=$this->model_account_coupon->getCouponByCode($coupon_code);

            if($coupon_info){
                $discount=$coupon_info['discount'];
		        $total   =$coupon_info['total'];
		        
		        if(isset($data['partner']) && $data['partner']) {
		            $partner = $data['partner'];
		        }else{
		            $partner = '';
		        }
		        
		        if(isset($data['pid']) && $data['pid']) {
		            $pid = $data['pid'];
		        }else{
		            $pid = '';
		        }
		        
                // 绑定优惠券到该会员
               // $this->model_account_coupon->addCouponToCustomer($coupon_info['coupon_id'],$customer_id, $pid, $partner);
            }
        }
        
        
        //发送注册成功短信，微信
        $mobile=$data['mobile'];

        if(mobile_check($mobile)) {
            $sms=new Sms();
            if($platform['openid']&&$platform['platform_code']=='wechat'){

                $openid=$platform['openid'];
                $template_id = '79qJJ1G8FP6nQrOKa0FLGcviZ7oIEf_nYmCNa57ECrw';
                $url = $this->url->link('common/home');
                $msg_data = array(
                    'first' => array(
                        'value' => "你好，你已注册成功",
                        'color' => '#0AA39A'
                    ),
                    'remark' => array(
                        'value' => "谢谢你的注册",
                        'color' => '#0AA39A'
                    ),
                    'keyword1' => array(
                        'value' => $data['name'],
                        'color' => '#0AA39A'
                    ),
                    'keyword2' => array(
                        'value' => date('Y-m-d',time()),
                        'color' => '#0AA39A'
                    )
    
                );
        		$this->load->service('weixin/interface');
        		$this->service_weixin_interface->send_msg_by_weixin($openid,$template_id,$url,$msg_data);
            }
//            $commons= new Common($this->registry);
//            $commons->send_msg_by_weixin($openid,$template_id,$url,$msg_data);
		



            //$msg=sprintf('恭喜你成功注册青年菜君，首单购买菜品满%s立减%s元，偷偷告诉你优惠码：%s，输入才能减免哦',$this->currency->format($total),$this->currency->format($discount),$coupon_code);
            //$msg=sprintf('恭喜你成功注册青年菜君！新人报道菜君请客：一张特权菜票送给你，可于菜君网站免费获得一道特权菜，快来把美味晚餐带回家吧！');
            //$msg=sprintf('恭喜你注册完成！有我在身边，晚餐从此不将就。新人报到立刻领取50元礼包！点击→（dwz.cn/IeyfH）');
            //$msg=sprintf('恭喜你注册完成！有我在身边，晚餐从此不将就。国民晚餐1元购进行中，详情请点击http://dwz.cn/NWkCG 回TD退订');
            if(flag) {
                $msg=sprintf('恭喜您注册成功！有我在身边，晚餐从此不将就！您的初始密码为:%s，详情请点击http://dwz.cn/NWkCG 回TD退订', $data['password']);
            }
            else{
                $msg=sprintf('恭喜您注册成功！有我在身边，晚餐从此不将就！优惠活动进行中，详情请点击http://dwz.cn/NWkCG 回TD退订');
            }
            $this->log_sys->debug("注册成功短信:  ".$msg);

//            $sms->send($mobile, $msg);  
        
        }

        if (!empty($email) && $this->config->get('config_mail_protocol')) {
//            $this->sendRegisterMail();
        }

        return $customer_id;
    }

    public function sendMobileValidateSms($mobile) {
//	$validateCode = substr(md5(time()), -5); 
        $validateCode = (string)rand(100000,999999);

        //发送短信
//        if(mobile_check($mobile)) {
            $sms=new Sms();

            $msg= sprintf('验证码为：%s（请勿将验证码告知他人）', $validateCode);

            $this->log_sys->debug("caralog->account->coustomer->sendMobileValidateSms::".$mobile.':'.$msg);

            $msg = $msg;

            $sms->send($mobile, $msg);
//        }
        return $validateCode;
    }
    public function sendForgetPwdSms($mobile) {
       $validateCode = (string)rand(100000,999999);
        //发送短信
//        if(mobile_check($mobile)) {
            $sms=new Sms();


             $msg= sprintf('验证码为：%s（请勿将验证码告知他人）', $validateCode);

            $this->log_sys->debug("找回密码，手机验证:  ".$msg);

            $msg = $msg;

            $sms->send($mobile, $msg);
//        }
        return $validateCode;
    }


    public function editCustomerTableField($customer_id, $field, $value) {
        $sql = "UPDATE " . DB_PREFIX . "customer SET $field = '" . $this->db->escape($value) . "' WHERE customer_id = '" . (int)$customer_id . "'";

        $this->db->query($sql);
    }

    private function sendRegisterMail() {
        $this->language->load('mail/customer');

        if ($this->config->get('config_active') == '1') {
            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
        } else {
            $subject = sprintf($this->language->get('text_subject1'), $this->config->get('config_name'));
        }
        $message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

        if (!$this->config->get('config_customer_approval')) {
            if ($this->config->get('config_active') == '1') {
                $message .= $this->language->get('text_active') . "\n";
            } else {
                $message .= $this->language->get('text_login') . "\n";
            }
        } else {
            $message .= $this->language->get('text_approval') . "\n";
        }

        $active_code = $this->getActiveCode();

        if ($this->config->get('config_active') == '1') {
            $message .= $this->url->link('account/active&active_code=' . $active_code, '', 'SSL') . "\n\n";
            $message .= $this->language->get('text_services') . "\n\n";
            $message .= $this->language->get('text_thanks') . "\n";
            $message .= $this->config->get('config_name');
        } else {
            $message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
            $message .= $this->language->get('text_services') . "\n\n";
            $message .= $this->language->get('text_thanks') . "\n";
            $message .= $this->config->get('config_name');
        }

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($this->request->post['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($subject);
        $mail->setText($message);
        $mail->send();

        // Send to main admin email if new account email is enabled
        if ($this->config->get('config_account_mail')) {
            $mail->setTo($this->config->get('config_email'));
            $mail->send();

            // Send to additional alert emails if new account email is enabled
            $emails = explode(',', $this->config->get('config_alert_emails'));

            foreach ($emails as $email) {
                if (strlen($email) > 0 && preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }
    }

    public function addReward($customer_id, $description = '', $points = '') {
        $customer_info = $this->getCustomer($customer_id);

        if ($customer_info) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

            $this->language->load('mail/customer');

            $message = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
            $message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($customer_info['email']);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(sprintf($this->language->get('text_reward_subject'), $this->config->get('config_name')));
            $mail->setText($message);
            $mail->send();
        }
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row;
    }
/**
 * 根据用户ID 获取用户信息
 * @param type $ids 
 */
	public function get_customer_by_ids($ids, $fields = ''){
		if(empty($ids)){
			return false;
		}
		$table_name = DB_PREFIX.'customer';
		$str = implode(',', $ids);
		$in_str = " ({$str}} ";
		$fields = $fields ? $fields : 'customer_id,username,telephone';
		$sql = "select {$fields} from {$table_name} where customer_id in {$in_str}";
		$query = $this->db->query($sql);
		foreach($query->rows as $info){
			$data[$info['customer_id']] = $info;
		}
		return $data;
	}
    public function getRewardTotal($customer_id) {
        $query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row['total'];
    }
    public function getOrderTotal($customer_id,$status='') {
    	
    	$sql="SELECT SUM(total) AS total FROM " . DB_PREFIX . "order WHERE customer_id = '" . (int)$customer_id . "' AND total>0";
    	
    	if(is_array($status))$status=implode(',', $status);
    	
    	if($status)$sql.=" AND order_status_id in({$status})";
    	
    	$query = $this->db->query($sql);
    	return $query->row['total'];
    }
    public function getOrderNumTotal($customer_id,$status='') {
    	$sql="SELECT count(1) AS total FROM " . DB_PREFIX . "order WHERE customer_id = '" . (int)$customer_id . "'";

    	if(is_array($status))$status=implode(',', $status);
    	 
    	if($status)$sql.=" AND order_status_id in({$status})";
    	
    	$query = $this->db->query($sql);
    
    	return $query->row['total'];
    }
    
    public function updateGrade($customer_id){
    	$groups=$this->getCustomerGroups('DESC');
    	foreach($groups as $item)
    	{
    		if($this->getOrderTotal($customer_id)*100>=$item['level']){//订单金额，
    			$this->editCustomerTableField($customer_id,'customer_group_id',$item['customer_group_id']);	
    			return $item;
    		}		
    	}
    	 
    }
    public function activeCustomer($code) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET status = '1' WHERE active_code = '" . $this->db->escape($code) . "'");

        $customer_query = $this->db->query("SELECT ih.* FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "invited_history ih  ON ih.invited_id=c.customer_id WHERE c.active_code = '" . $this->db->escape($code) . "' AND c.status = '1'");
        $send_id = 0;
        if ($customer_query->num_rows) {
            $this->language->load('mail/customer');
            $send_id = $customer_query->row['customer_id'];
            $this->addReward($send_id, $this->language->get('text_reward_system'), $this->config->get('config_invite_points'));
        }
    }

    public function existsOtherCustomerWithSameField($fieldName, $fieldValue, $id) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "select count(*) from {$DB_PREFIX}customer where {$fieldName}='{$fieldValue}' and customer_id!={$id} ";
        $count = DbHelper::getSingleValue($sql, 0);
        return $count > 0;
    }

    public function editCustomer($data) {
        $id = $this->customer->getId();
        $email = $data['email'];
        $mobile = $data['mobile'];
//        if (isset($email) && $this->existSameFieldVal('email', $email, $id)) {
//            throw new Exception('该邮箱已被占用');
//        }
//        if (isset($mobile) && $this->existSameFieldVal('mobile', $mobile, $id)) {
//            throw new Exception('该手机号已被占用');
//        }

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "' WHERE customer_id = '" . (int)$id . "'");

        if (isset($mobile)) {
            $this->editCustomerTableField($id, 'mobile', $mobile);
        }

        if (isset($data['name'])) {
            $this->editCustomerTableField($id, 'firstname', $data['name']);
        }

        if (isset($data['salution'])) {
            $this->editCustomerTableField($id, 'salution', $data['salution']);
        }
        if (isset($email)) {
            $this->editCustomerTableField($id, 'email', $email);
        }
    }

    public function editPassword($customer_id, $password) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $this->db->escape(md5($password))
            . "' WHERE customer_id = '" . (int)$customer_id . "' limit 1");
    }
    public function editPasswordByMobile($mobile, $password) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $this->db->escape(md5($password)) . "' WHERE mobile = '" . $this->db->escape($mobile) . "'");
    }

    public function editNewsletter($newsletter) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
    }


    public function getInvitedHistory($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invited_history WHERE customer_id = '" . (int)$customer_id . "'");
        $invited = "";
        if ($query->num_rows) {
            $invited = '';
            $count = 1;
            foreach ($query->rows as $row) {
                if ($count == 1)
                    $invited .= $row['invited_id'];
                else
                    $invited .= ',' . $row['invited_id'];
                $count++;
            }
            $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id IN (" . $invited . ") AND status = '1'");
            return $customer_query->rows;
        } else {
            return 0;
        }
    }

    public function getCustomers($data = array()) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) ";

        $implode = array();
        $implode[]='is_delete=0';
        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
        }

        if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
            $implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
        }

        if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
            $implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
        }

        if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.ip',
            'c.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
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

        return $query->rows;
    }


    public function getCustomerGroups($orderby='ASC') {
    	$sql = "SELECT * FROM " . DB_PREFIX . "customer_group WHERE level>0";
    		
    	$sql .= " ORDER BY level {$orderby} ";

    	$query = $this->db->query($sql);
    
    	return $query->rows;
    }
    public function getTotalCustomersByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "'");

        return $query->row['total'];
    }

    // FIXME remove this method, we dun need it anymore
    public function editShippingMethod($shipping_method) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET shipping_method = '" . $this->db->escape($shipping_method) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
    }

    public function editPaymentMethod($payment_method) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET payment_method = '" . $this->db->escape($payment_method) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
    }

    public function editCustomerCompanyInfo($customer_id, $data) {
        $sql = "UPDATE " . DB_PREFIX . "customer SET firstname='" . $this->db->escape($data['firstname'])
            . "',department='" . $this->db->escape($data['department'])
            . "',telephone='" . $this->db->escape($data['telephone'])
            . "',mobile='" . $this->db->escape($data['mobile'])
            . "',company='" . $this->db->escape($data['company'])
            . "',company_address='" . $this->db->escape($data['company_address'])
            . "',website='" . $this->db->escape($data['website'])
            . "',type=1 WHERE customer_id=" . (int)$customer_id;

        $this->db->query($sql);
    }

    private function setRegisterRewardPoints($customer_id, $points) {
        $data = array(
            'customer_id' => $customer_id,
            'points' => $points,
            'order_id' => 0,
            'description' => $this->language->get('entry_register_reward')
        );

        $this->load->model('account/reward');

        $this->model_account_reward->addReward($data);
    }

    //add by lance 2014-03-03 begin   for check mobile is if have been used
    public function getCustomerByMobile($phone_num, $customer_id = '',$status=-1,$is_delete=0) {
        if (isset($phone_num)) {
            $sql = "SELECT count(mobile) AS total,customer_id FROM " . DB_PREFIX . "customer WHERE mobile = '" . $phone_num . "'";

            if ($is_delete!==false) {
            	$sql .= " AND is_delete =" . (int)$is_delete;
            }
            if ($customer_id) {
                $sql .= " AND customer_id !=" . (int)$customer_id;
            }
            if ($status!=-1) {
            	$sql .= " AND status=" . (int)$status;
            }

            $query = $this->db->query($sql);

            return $query->row['customer_id'];//$query->row['total'];
        } else {
            return 0;
        }
    }

    //add by lance 2014-03-03 end


    public function getReference($reference){
        $query = $this->db->query("SELECT id,name FROM " . DB_PREFIX . "reference_list 
                                   WHERE refer_code = '" . $this->db->escape($reference) . "'
                                   AND status=1 AND (s_valid_time='0000-00-00 00:00:00' OR s_valid_time<NOW() )  AND (e_valid_time='0000-00-00 00:00:00' OR e_valid_time>NOW() )
                                   LIMIT 1 ");
        if($query->row){
            return $query->row;
        }else{
            return false;
        }
    }
}

?>