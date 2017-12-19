<?php

class ControllerAccountMeituan extends Controller {

	private $error = array();

	/**
	 * 注册领卷页面
	 */
	public function index() {
//		//是否登录
//		if ($this->customer->isLogged()) {
//			$this->redirect($this->url->link('account/account', '', 'SSL'));
//		}
		//如果带活动代码，记入SESSION
		if (!empty($this->request->get['promo'])) {
			$this->session->data['promo'] = $this->request->get['promo'];
		}

		$infomation = new Common($this->registry);
		$infomation->get_openid();

		$this->load_language('account/meituan');
		$this->document->setTitle($this->language->get('heading_title'));

		//邀请码
		if (!empty($this->request->get['invitecode'])) {
			$this->data['invitecode'] = $this->request->get['invitecode'];
		} else {
			$this->data['invitecode'] = 0;
		}
		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->validateBasic();
			//$this->log_sys->info('controller->account->register->index::mobile:'.$this->request->post['mobile'].';post_mobile_vcode'.$this->request->post['mobile_vcode'].';mobile_validate_code:' . $this->session->data['mobile_validate_code']);
			$this->log_sys->trace('POST');

			if ($this->validate()) {
				//验证是否是已注册用户
				if ($this->model_account_customer->getCustomerByMobile($this->request->post['mobile'])) {//已注册用户 去登录
					$this->login();
				} else {//新用户
					unset($this->session->data['guest']);
					unset($this->session->data['mobile_validate_code']);
					unset($this->session->data['mobile_validate_time']);

					$customer_id = $this->model_account_customer->addCustomer($this->request->post);
					if ($customer_id) {
						//增加美团 绑定优惠券动作
						$mobile = $this->request->post['mobile'];
						$this->bindmeituan($customer_id, $mobile);
					}
					if ($this->config->get('config_active') == '1') {
						$this->redirect($this->url->link('account/register/active'));
					} else {
						//FIXED：#247 注册成功后提示错误bug
						$this->customer->login($this->request->post['mobile'], $this->request->post['password']);
						// 储值
						if (isset($this->session->data['trans_code'])) {
							$this->load->model('sale/transaction');
							$this->model_sale_transaction->addTransaction($this->customer->getId(), $this->session->data['trans_code']);
							unset($this->session->data['trans_code']);
						}
						$this->redirect($this->url->link('account/account'));
					}
				}
			}
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
						'text' => $this->language->get('text_home'),
						'href' => $this->url->link('common/home'),
						'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
						'text' => $this->language->get('text_account'),
						'href' => $this->url->link('account/account', '', 'SSL'),
						'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
						'text' => $this->language->get('text_register'),
						'href' => $this->url->link('account/register', '', 'SSL'),
						'separator' => $this->language->get('text_separator')
		);

		$error_fields = array('warning', 'email', 'mobile', 'password', 'confirm', 'mobile_vcode', 'agree', 'name', 'reference');

		foreach ($error_fields as $field) {
			if (isset($this->error[$field])) {
				$this->data['error_' . $field] = $this->error[$field];
			} else {
				$this->data['error_' . $field] = '';
			}
		}

		$this->data['action'] = $this->url->link('account/meituan', '', 'SSL');

		$fields = array('email', 'mobile', 'password', 'confirm', 'mobile_vcode', 'name', 'reference');

		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} else {
				$this->data[$field] = '';
			}
		}

		if (isset($this->request->post['salution'])) {
			$this->data['salution'] = $this->request->post['salution'];
		} else {
			$this->data['salution'] = 'M';
		}

		if (isset($this->request->post['newsletter'])) {
			$this->data['newsletter'] = $this->request->post['newsletter'];
		} else {
			$this->data['newsletter'] = '';
		}


		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information' . '&information_id=' . $this->config->get('config_account_id'), '', 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}

		if (isset($this->request->post['agree'])) {
			$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = true;
		}

		if (isset($this->request->post['newsletter'])) {
			$this->data['newsletter'] = $this->request->post['newsletter'];
		} else {
			$this->data['newsletter'] = true;
		}

		$this->id = "register";

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/meituan.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/meituan.tpl';
		} else {
			$this->template = 'default/template/account/register.tpl';
		}

		$this->children = array(
						'common/column_left',
						'common/column_right',
						'common/content_top',
						'common/content_bottom',
						'common/footer',
						'common/header'
		);

		$this->response->setOutput($this->render());
	}

	/**
	 * 校验注册信息
	 */
	private function validateBasic() {
		// 校验手机号码
		$mobileError = $this->validateNewMobile($this->request->post['mobile']);
		if ($mobileError) {
			$this->error['mobile'] = $mobileError;
		}
		$mobile_validate_code = md5($this->request->post['mobile'] . $this->request->post['mobile_vcode']);
		$mobile_validate_code_session = $this->session->data['mobile_validate_code'];

		//验证码时间限定（2分钟）
		if (empty($mobile_validate_code)) {
			$this->error['mobile_vcode'] = $this->language->get('error_mobile_empty_vcode');
		} else if (!isset($this->session->data['mobile_validate_time']) || (time() - intval($this->session->data['mobile_validate_time'])) > (2 * 60)) {
			$this->error['mobile_vcode'] = $this->language->get('error_timeout');
		} else if (empty($mobile_validate_code_session) || strtolower($mobile_validate_code) != strtolower($mobile_validate_code_session)) {
			$this->error['mobile_vcode'] = $this->language->get('error_mobile_vcode');
		}

		// 校验密码长度
		if ((strlen(utf8_decode($this->request->post['password'])) < 4)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}
	}

	private function validate() {
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateNewMobile($mobile) {
		$errorMsg = null;
		if ((strlen(utf8_decode($mobile)) < 1)) {
			$errorMsg = $this->language->get('error_mobile');
		} else if (!preg_match('/^[0-9]{11}$/', $mobile)) {
			$errorMsg = $this->language->get('error_mobile_format');
		}
		return $errorMsg;
	}

	/**
	 * ajax 验证手机号获取手机验证码
	 * @return boolean
	 */
	public function validate_mobile() {
		if (!isset($this->session->data['enter_route']) || $this->session->data['enter_route'] == 'account/meituan/validate_mobile') {
			return false;
		}
		if (isset($this->session->data['mobile_validate_time']) && (time() - intval($this->session->data['mobile_validate_time'])) < (58)) {
			return false;
		}

//		$this->init();
		$this->load_language('account/meituan');
		$sysvcode = $this->request->get['sysvcode'];
		$sysvcodeError=null;
		
		if($this->config->get('config_customer_lr_captcha')&&!(!empty($this->session->data['captcha']) && $sysvcode==$this->session->data['captcha']))
		{
			$sysvcodeError=$this->language->get('error_sys_vcode_format');
			 
		}
		
		$mobile = $this->request->get['mobile'];
		$mobileError = $this->validateNewMobile($mobile);

		$json = array();
		 if (!is_null($sysvcodeError)) {
        	$json['success'] = false;
        	$json['msg']['sys-vcode-error'] = $sysvcodeError;
        }elseif (!is_null($mobileError)) {
			$json['success'] = false;
			   $json['msg']['mobile-vcode-error'] = $mobileError;
		} else {
			$this->load->model('account/customer');
			$mobile_validate_code = $this->model_account_customer->sendMobileValidateSms($mobile);
			$this->session->data['mobile_validate_code'] = md5($mobile . $mobile_validate_code);
			$this->log_sys->info('controller->account->register->validate_mobile::mobile:' . $mobile . ';mobile_validate_code:' . $mobile_validate_code);

			// 记录时间戳
			$this->session->data['mobile_validate_time'] = time();
			$json['success'] = true;
//			$json['vcode']=$mobile_validate_code;
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}

	//登录领卷页面
	public function login() {
		$flag = $this->login_validate();
		if (!$flag) {
			unset($this->session->data['guest']);

			$this->load->model('account/address');
			$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());
			if ($address_info) {
				$this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
			}

			$customer_id = $this->customer->getId();
			$mobile = $this->request->post['mobile'];
			//查询是否有未绑定美团团购优惠券
			$this->bindmeituan($customer_id, $mobile);
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		} else {
			$this->data['warning'] = $flag;
		}
	}

	private function login_validate() {
		$warning = '';
		if ((strlen(utf8_decode($this->request->post['mobile'])) < 1)) {
			$warning = $this->language->get('error_email_empty');
			return false;
		}

		if (!$this->customer->login($this->request->post['mobile'], $this->request->post['password'])) {
			if ($this->customer->getLoginCode() == EnumLoginCode::ERROR_ACCOUNT) {
				$warning = $this->language->get('error_login');
			}

			if ($this->customer->getLoginCode() == EnumLoginCode::ERROR_ACTIVE) {
				$warning = $this->language->get('error_active');
			}

			if ($this->customer->getLoginCode() == EnumLoginCode::ERROR_Approved) {
				$warning = $this->language->get('error_approved');
			}
		}
		return $warning;
	}

	/**
	 * 美团下单 具体操作 
	 * @param type $customer_id
	 * @param type $mobile
	 */
	private function bindmeituan($customer_id, $mobile) {
		$this->load->model('account/coupon');
		$this->load->model('sale/transaction');
		$param['mt_mobile'] = $mobile;
		$param['status'] = 1;
		//用户输入指定 code  功能未开启 需在coupon 添加code条件
		if ($this->request->post['mt_code']) {
			$param['mt_code'] = $this->request->post['mt_code'];
		}
		$coupon = $this->model_account_coupon->getCouponsBindMeituantg($param);
		if ($coupon) {
			//通过SKU判断 是哪种类型   菜品-0 优惠券 -1 充值卡-2 
			$this->load->model('catalog/product');
			//sku 是字符串
			foreach ($coupon as $c) {
				$sku_arr[] = '\'' . $c['code'] . '\'';
			}
//			$sku_arr = array_column($coupon, 'code');
			$pro_infos = $this->model_catalog_product->getProductIdBySkus($sku_arr);
			foreach ($coupon as &$c) {
				$c['product_id'] = $pro_infos[$c['code']]['product_id'];
				$c['prod_type'] = $pro_infos[$c['code']]['prod_type'];
				$c['productinfo'] = $pro_infos[$c['code']];
				if ($pro_infos[$c['code']]['prod_type'] == 1) {
					$arr1[$c['product_id']] = $c;
				} else if ($pro_infos[$c['code']]['prod_type'] == 2) {
					$arr2[$c['product_id']] = $c;
				} else {
					$arr3[$c['product_id']] = $c;
				}
			}
			//具体操作 --下单 储值 绑定优惠券
			if ($arr1) {//优惠券操作
				$p_ids = array_column($arr1, 'product_id');
				$coupon_list = $this->model_catalog_product->get_coupons_by_pids($p_ids);
				foreach ($coupon_list as $li) {
					//先生成订单
					$order_id = $this->create_order($arr1[$li['product_id']]['productinfo']);
					//判断 优惠券数量 循环绑定
					for($i = 0; $i < $li['coupon_num']; $i++){
						if ($li['is_tpl'] == 1) {//是模板  需要生成 码
							unset($li['code']);
							$code = $this->model_account_coupon->createCoupon($li);
							$flag = $this->model_account_coupon->addCoupon($code, $customer_id, 0, 'meituan'); //绑定
						} else {//不需要生成 直接绑定
							$flag = $this->model_account_coupon->addCoupon($li['code'], $customer_id, 0, 'meituan'); //绑定
						}
						//核销
						$des_arr['orderid'] = $arr1[$li['product_id']]['mt_order_id'];
						$des_arr['verifycode'] = $arr1[$li['product_id']]['mt_coupon_code'];
						$des_arr['token'] = $arr1[$li['product_id']]['mt_token'];
						$this->load->service('meituantg/coupon');
						$this->service_meituantg_coupon->HMTTGVerify($des_arr);
					}
					//生成订单
					if($flag > 0){
						//更新订单状态
						$this->load->model('checkout/order');
						$this->model_checkout_order->confirm($order_id, 2);
					}
				}
			}
			if ($arr2) {//储值卡操作
				$p_ids = array_column($arr2, 'product_id');
				$trans_list = $this->model_catalog_product->get_trans_by_pids($p_ids);
				foreach ($trans_list as $li) {
					//先生成订单
					$order_id = $this->create_order($arr1[$li['product_id']]['productinfo']);
					//判断 购买充值卡数量 做循环绑定
					for($i = 0; $i < $li['num']; $i++){
						if ($li['is_tpl'] == 1) {//是模版 需要生成码
							$amount = $li['value'];
							$data = $this->format_data($amount);
							$code = $this->model_sale_transaction->get_recharge_key($data);
							$flag = $this->model_sale_transaction->addTransaction($customer_id, $code);
						} else {//直接绑定
							$flag = $this->model_sale_transaction->addTransaction($customer_id, $li['trans_code']);
						}
						//核销
						$des_arr['orderid'] = $arr1[$li['product_id']]['mt_order_id'];
						$des_arr['verifycode'] = $arr1[$li['product_id']]['mt_coupon_code'];
						$des_arr['token'] = $arr1[$li['product_id']]['mt_token'];
						$this->load->service('meituantg/coupon');
						$this->service_meituantg_coupon->HMTTGVerify($des_arr);
					}
					//生成订单
					if($flag){
						//更新订单状态
						$this->load->model('checkout/order');
						$this->model_checkout_order->confirm($order_id, 2);
					}
				}
			}
			if ($arr3) {
				//下单购买菜品
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

	public function create_order($p_info) {
		$shipping_required = false; //不需要配送

		/*
		 * 构造订单数据 */
		$data = array();
		$data['shipping_required'] = $shipping_required;

		//商品信息
		$data['products'][0]['product_id'] = $p_info['product_id'];
		$data['products'][0]['href'] = $this->url->link('product/product') . '&amp;product_id=' . $p_info['product_id'];
		$data['products'][0]['name'] = $p_info['name'];
		$data['products'][0]['model'] = $p_info['model'];
		$data['products'][0]['prod_type'] = $p_info['prod_type'];
		$data['products'][0]['shipping'] = $p_info['shipping'];
		$data['products'][0]['promotion'] = array();
		
		$data['products'][0]['additional'] = '';
		$data['products'][0]['option'] = array();
		$data['products'][0]['download'] = array();
		$data['products'][0]['quantity'] = $p_info['quantity'];
		$data['products'][0]['subtract'] = $p_info['subtract'];
		$data['products'][0]['price'] = $p_info['price'];
		$data['products'][0]['total'] = $p_info['price'];
		$data['products'][0]['rule_code'] = null;
		$data['products'][0]['combine'] = $p_info['combine'];
		$data['products'][0]['packing_type'] = $p_info['packing_type'];
		
		$data['products'][0]['tax'] = $this->tax->getRate($p_info['tax_class_id']);
		//sub——total
		$data['totals'][] = array(
						'code' => 'sub_total',
						'title' => $this->language->get('text_sub_total'),
						'text' => $this->currency->format($p_info['price']),
						'value' => $p_info['price'],
						'sort_order' => $this->config->get('sub_total_sort_order')
		);
		//total
		$data['totals'][] = array(
						'code' => 'total',
						'title' => $this->language->get('text_total'),
						'text' => $this->currency->format(max(0, $p_info['price'])),
						'value' => max(0, $p_info['price']),
						'sort_order' => $this->config->get('total_sort_order')
		);
		$data['total'] = $p_info['price'];
		$this->language->load('checkout/checkout');

		$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$data['store_id'] = $this->config->get('config_store_id');
		$data['store_name'] = $this->config->get('config_name');
		if ($data['store_id']) {//如果存在id则读取配置url否则默认读取当前访问的url
			$data['store_url'] = $this->config->get('config_url');
		} else {
			$data['store_url'] = HTTP_SERVER;
		}

		$data['customer_id'] = $this->customer->getId();
		$data['customer_group_id'] = $this->customer->getCustomerGroupId();
		$data['firstname'] = $this->customer->getFirstName();
		$data['lastname'] = $this->customer->getLastName();
		$data['email'] = $this->customer->getEmail();
		$data['telephone'] = $this->customer->getMobile();
		$data['fax'] = $this->customer->getFax();
		$data['shipping_time'] = date('Y-m-d H:i:s', time() + 1800);

		/* 支付信息 */
		$this->load->model('account/address');
		$data['payment_firstname'] = '';
		$data['payment_lastname'] = '';
		$data['payment_company'] = '';
		$data['payment_address_1'] = '';
		$data['payment_address_2'] = '';
		$data['payment_city'] = '';
		$data['payment_postcode'] = '';
		$data['payment_zone'] = '';
		$data['payment_zone_id'] = '';
		$data['payment_country'] = '';
		$data['payment_country_id'] = '';
		$data['payment_address_format'] = '';
		$data['payment_method'] = '美团购买';
		$data['payment_code'] = '';
		$data['comment'] = '';
		//折扣信息
		$data['reward'] = $this->cart->getTotalRewardPoints();

		$data['affiliate_id'] = 0;
		$data['commission'] = 0;

		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency_code'] = $this->currency->getCode();
		$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$data['ip'] = $this->request->server['REMOTE_ADDR'];

		//增加订单来源
		$detect = new Mobile_Detect();
		if ($detect->isMobile()) {
			$source_from = EnumOrderSourceFrom::MOBILE;
		} else if ($detect->isTablet()) {
			$source_from = EnumOrderSourceFrom::TABLET;
		} else {
			$source_from = EnumOrderSourceFrom::DESKTOP;
		}

		$data['source_from'] = $source_from;
		$data['user_agent'] = $detect->getUserBrowser();

		$this->load->model('checkout/order');
		$order_id = $this->model_checkout_order->create($data);

		return $order_id;
	}

}
