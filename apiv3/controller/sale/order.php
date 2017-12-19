<?php
/**
 * v3 订单接口
 * @author Lance
 */
class ControllerSaleOrder extends Controller {
	protected function init() {
		header ( "Access-Control-Allow-Origin: *" );
	}
	private $error;
	private $debug = DEBUG;
	public function _test() {
		
		// $this->request->post=json_decode('{"appid":"1829219078","is_send":"true","partner_code":"jd","payment_method":"\u5728\u7ebf\u652f\u4ed8(jd)","product_infos":"C193,1;C195,1;C192,1","shipping_address":"\u5317\u4eac\u5e02\u5ba3\u6b66\u533a\u5929\u6865\u5317\u91cc7\u53f7\u697c1\u5355\u5143203\u5929\u6865\u5317\u91cc7\u53f7\u697c1\u5355\u5143203","shipping_code":"meishisong","shipping_data":"\u9752\u5e74\u83dc\u541b-\u767d\u7eb8\u574a\u5e97","shipping_firstname":"\u7f57\u7433","shipping_mobile":"13661266102","sign_method":"1","stype":"2","time":"2015-07-31 18:00:00","tp_order_id":"100001002086287","sign":"574f9904ee5715d0b55e9f08bf3a5577"}'
		// ,1);
		// print_r(urlencode('YOU+国际青年社区(苏州桥店) (E112)').'<br />');
		
		// print_r(htmlspecialchars(('YOU+国际青年社区(苏州桥店) (E112)')).'<br />');
		
		// print_r(urldecode(urlencode('YOU+国际青年社区(苏州桥店) (E112)')).'<br />');
		
		// print_r(urldecode('YOU+国际青年社区(苏州桥店) (E112)').'<br />');
		
		$data=unserialize('a:21:{s:5:"appid";s:10:"1829219078";s:7:"is_send";s:4:"true";s:15:"order_status_id";s:1:"2";s:12:"partner_code";s:7:"meituan";s:14:"payment_method";s:12:"在线支付";s:3:"pid";s:2:"-1";s:13:"product_infos";s:37:"C023,1,1;C013,1,3;C075,1,14;C220,1,10";s:16:"shipping_address";s:77:"学创大厦(北门) (海淀区学院南路12号京师科技大厦B座一层)";s:13:"shipping_code";s:10:"meishisong";s:13:"shipping_data";s:9:"西直门";s:18:"shipping_firstname";s:14:"马静(女士)";s:15:"shipping_mobile";s:11:"13911522132";s:11:"sign_method";s:1:"1";s:5:"stype";s:1:"2";s:9:"sub_total";s:4:"57.0";s:4:"time";s:19:"2015-10-16 17:00:00";s:14:"total_discount";s:4:"28.0";s:9:"total_fee";s:3:"5.0";s:11:"total_value";s:4:"29.0";s:11:"tp_order_id";s:9:"600360914";s:4:"sign";s:32:"a311842f528726109bad1aaf92dbe142";}');


		$this->request->post = Array (
				'appid' => '1829219078',
				'is_send' => 'true',
				'order_status_id' => '2',
				'partner_code' => 'meituan',
				'payment_method' => '在线支付',
				'product_infos' => 'C106,20,1',
				'shipping_address' => 'YOU+国际青年社区(苏州桥店) (E112)',
				'shipping_code' => 'meishisong',
				'shipping_data' => '三元桥',
				'shipping_firstname' => '平台支付测试',
				'shipping_mobile' => '15810817660',
				'sign_method' => '1',
				'stype' => '2',
				'sub_total'=>48,
				'total_discount' => '14',
				'total_subsides' => '0',
				'total_value' => '34',
				'total_fee' => '5',
				'time' => '2016-04-08 17:00:00',
				'tp_order_id' => '499610317',
				'pid'=>'-1',
				'min_pre_times'=>0,
				'shipping_poi'=>'122.2 36.98'
		);
		$this->request->post ['sign'] = HTTP::make_sign ( $this->request->post, $this->get_sp_key ( $this->request->post ['appid'] ) );

		print_r($this->request->post);
		
		/*
		$this->request->post = Array (
				'appid' => '1829219078',
				'is_send' => 'true',
				'order_status_id' => '2',
				'partner_code' => 'meituan',
				'payment_method' => '在线支付',
				'product_infos' => 'C012,1;C121,1;C001,1',
				'shipping_address' => '彩商 (望京西园一区炫彩嘉轩凯蒂诗美甲)',
				'shipping_code' => 'meishisong',
				'shipping_data' => '望京',
				'shipping_firstname' => ' 小巧(女士)',
				'shipping_mobile' => '18800194833',
				'sign_method' => '1',
				'stype' => '2',
				'time' => '2015-09-14 17:00:00',
				'tp_order_id' => '517893528',
				'sign' => 'ee168b69f44fa68924de97ef08b3ccb5' 
		);
		*/
		
		$this->_create ();
		
		print_r($this->error);
	}
	private function get_sp_key($appid) {
		$config = array (
				'1829219078' => 'nurvrst31o0msmx8n2l4ryhg9lgyt6os', // 测试服务器182.92.190.78
				'1829219072' => 'ffsdfrewrwwesmx8n2l4ryhg9lgyt6os', // 测试服务器182.92.190.72
				'qncjhost' => 'ffsddfrewrwsdmx8n2l4rsewrwhgfkty' 
		); // 青年菜君本地服务器

		
		if (isset ( $config [$appid] ))
			return $config [$appid];
		else
			return false;
	}
	public function _create() {
		$this->load->model ( 'sale/order' );
		$this->init ();
		// -- $_GET params ------------------------------
		$param = array ();
		$this->log_sys->info ( 'start:' );
		$this->log_sys->info ( 'api:start:' );
		$this->log_sys->info ( 'post::serialize::' . serialize ( $this->request->post ) );
		if (HTTP::check_sign ( $this->request->post, $this->get_sp_key ( $this->request->post ['appid'] ) )) {
			$this->log_sys->info ( '校验成功' . serialize ( $this->request->post ) );
		} else {
			$this->log_sys->warn ( '校验失败' . serialize ( $this->request->post ) );
			
			$post = $this->request->post;
			unset ( $post ['sign'] );
			$sign = HTTP::make_sign ( $post, $this->get_sp_key ( $this->request->post ['appid'] ) );
			$this->log_sys->info ( 'sign:' . $sign );
			
			ksort ( $post );
			
			$arr_temp = array ();
			foreach ( $post as $key => $val ) {
				$arr_temp [] = $key . '=' . $val;
			}
			$sign_str = implode ( '&', $arr_temp );

			$this->log_sys->warn ( '校验失败::syssign:' . $sign . '::sign_str:' . $sign_str );
			// print_r('校验失败::syssign:'.$sign.'::sign_str:'.$sign_str);
			$this->response->setOutput ( '校验失败' );
			$this->error ['create'] ['error'] = '1';
			$this->error ['create'] ['message'] = '校验失败';
			$this->response->setOutput ( json_encode ( $this->error ['create'] ) );
			return;
		}
		
		if (! $this->_checkCreateRequest ( $param )) {
			$this->log_sys->info ( 'request:' . json_encode ( $param ) );
			
			$this->log_sys->warn ( serialize ( $this->error ['checkCreateRequest'] ) );
			// $this->m->setError(mCartResult::ERROR_SYSTEM_INVALID_API);
			// $this->response->setOutput(json_encode($this->m->returnResult()));
			$this->response->setOutput ( json_encode ( $this->error ['checkCreateRequest'] ) );
			
			return;
		}
		
		$this->log_sys->info ( 'param:' . json_encode ( $param ) );
		/*
		 * 测试数据
		 * $param=json_decode('{"time":"2015-05-20","point_id":"1003","point_name":"\u9752\u5e74\u6c47","partner_code":"andriod","phone_num":"15810562817","product_infos":["11,2"],"product_infos_par":"11,2","sign":"dd2b8a12de9c2f96e894fd34a1084382","appid":"1829219078","sign_method":"1"}',1);
		 */
		
		// -- End $_GET params --------------------------
		
		$this->session->data ['platformpparam'] = $param;
		
		$data = array ();
		
		$data ['invoice_prefix'] = $this->config->get ( 'config_invoice_prefix' );
		$data ['store_id'] = $this->config->get ( 'config_store_id' );
		$data ['store_name'] = $this->config->get ( 'config_name' );
		
		if ($data ['store_id']) {
			$data ['store_url'] = $this->config->get ( 'config_url' );
		} else {
			$data ['store_url'] = HTTP_SERVER;
		}
		
		$data ['firstname'] = '';
		$data ['lastname'] = '';
		$data ['email'] = '';
		
		$data ['point_id'] = $param ['point_id'];
		$data ['device_code'] = $param ['point_id'];
		
		$data ['tp_order_id'] = $param ['tp_order_id'];
		$data ['verify'] = $param ['sign'];
		
		$data ['fax'] = '';
		
		$payment_address = $this->session->data ['guest'] ['payment'];
		
		$data ['payment_firstname'] = '';
		$data ['payment_lastname'] = '';
		$data ['payment_company'] = '';
		$data ['payment_address_1'] = '';
		$data ['payment_address_2'] = '';
		$data ['payment_city'] = '';
		$data ['payment_postcode'] = '';
		$data ['payment_zone'] = '';
		$data ['payment_zone_id'] = '';
		$data ['payment_country'] = '';
		$data ['payment_country_id'] = '';
		$data ['payment_address_format'] = '';
		
		$data ['customer_id'] = 0;
		$data ['customer_group_id'] = $this->config->get ( 'config_customer_group_id' );
		
		/* $this->load->model('account/customer'); */
		$this->load->model ( 'account/customer', 'service' );
		
		
		
		$mobile=$param ['phone_num']?$param ['phone_num']:$param ['shipping_mobile'];
		
		
		
		if($mobile){
		
		if (($customer_id = $this->model_account_customer->getCustomerByMobile ($mobile )) > 0) {
			$data ['customer_id'] = $customer_id;
			$data ['customer_group_id'] = $this->model_account_customer->getCustomer ($customer_id )['customer_group_id'];
		} else {
			$customer_id = $this->model_account_customer->addCustomer ( array (
					'mobile' => $mobile,
					'status' =>0 //默认未激活账户，用户可以重新注册激活
			) );
			$data ['customer_id'] = $customer_id;
		 }
		}
		if ($data ['device_code']) { // 如果有自提点信息
			
			$data ['payment_method'] = '现金支付';
			$data ['pdate'] = $param ['time'];
			
			$data ['source_from'] = EnumOrderSourceFrom::CLIENT;
					


		} else {
			$data ['payment_method'] = $param ['payment_method'];
			$data ['shipping_time'] = $param ['time'];
			
			if (isset ( $param ['source_from'] ))
				$data ['source_from'] = $param ['source_from'];
			else
				$data ['source_from'] = EnumOrderSourceFrom::TP;
		}
		
		
		$data ['shipping_address_1'] = $this->request->clean_address ( $param ['shipping_address_1'] );
		$data ['shipping_code'] = $param ['shipping_code'];
		$data ['shipping_data'] = $param ['shipping_data'];
		$data ['poi'] = $param ['shipping_poi'];
		
		$data ['comment'] = $param ['comment'];
		
		$data ['shipping_firstname'] = $param ['shipping_firstname'];
		$data ['shipping_mobile'] = $param ['shipping_mobile'];
		
		$data ['shipping_lastname'] = '';
		$data ['shipping_phone'] = '';
		$data ['shipping_company'] = '';
		$data ['shipping_address_2'] = '';
		$data ['shipping_city'] = '';
		$data ['shipping_postcode'] = '';
		$data ['shipping_zone'] = '';
		$data ['shipping_zone_id'] = '';
		$data ['shipping_country'] = '';
		$data ['shipping_country_id'] = '';
		$data ['shipping_address_format'] = '';
		
		// 取菜点
		$data ['shipping_method'] = $param ['point_name'];
		// 联系电话
		$data ['telephone'] = $param ['phone_num'];
		$data ['partner_code'] = $param ['partner_code'];
	
	

		
		$product_data = array ();
		$sub_total=array();
		$taxes=0;
		$sub_total['promotion']=0;
		$sub_total['general']=0;
		$sub_total['fee']=0;
		
		$this->load->model ( 'catalog/product' );
		$this->load->model ( 'setting/extension' );
		
		
		foreach ( $param ['product_infos'] as $product_info ) {
			$product_info_arr = explode ( ",", $product_info );
			// 如果找不到菜品，直接报异常
			$product_info_arr_count = count ( $product_info_arr );
			if ($product_info_arr_count != 2 && $product_info_arr_count != 3) {
				// $this->m->setError(mCartResult::ERROR_SYSTEM_INVALID_API);
				$result ['error'] = '1';
				$result ['message'] = '菜品结构错误';
				
				$this->response->setOutput ( json_encode ( $result ) );
				return;
			}
			
			if (( int ) $product_info_arr [1] <= 0) {
				$result ['error'] = '1';
				$result ['message'] = '菜品' . $product_info_arr [0] . '数量错误';
				
				$this->response->setOutput ( json_encode ( $result ) );
				return;
			}
			
			if ($param ['stype'] == '2') {
				$product_id = $this->model_catalog_product->getProductIdBySku ( $product_info_arr [0] );
			} else {
				$product_id = $product_info_arr [0];
			}

			$product = $this->model_catalog_product->getProduct ($product_id ,$param['pid'],$param['time']);

			// 如果找不到菜品，直接报异常
			if (! $product) {
				// $this->m->setError(mCartResult::ERROR_SYSTEM_INVALID_API);
				$result ['error'] = '1';
				$result ['message'] = '菜品' . $product_info_arr [0] . '未找到';
				$this->error['check_product']=$result;
				$this->log_sys->info ( serialize ( $result ) );
				
				$this->log_sys->warn ( serialize ( $result ) );
				$this->response->setOutput ( json_encode ( $result ) );
				return;
			}
			
			if(!$product['available'])
				{
					// $this->m->setError(mCartResult::ERROR_SYSTEM_INVALID_API);
					$result ['error'] = '1';
					$result ['message'] = '菜品' . $product_info_arr [0] . '不在周期或者不可售卖';
					$this->log_sys->info ( serialize ( $result ) );
				    $this->error['check_product']=$result;
					$this->log_sys->warn ( serialize ( $result ) );
					$this->response->setOutput ( json_encode ( $result ) );
					return;
				}
			
			if ($product_info_arr [2]) {
				$promotion ['promotion_price'] = $product_info_arr [2];
				$promotion ['promotion_code'] = EnumPromotionTypes::PLATFORM_SPECIAL; // 平台特价
				$product ['promotion'] = $promotion;
			}
			
			$option_data = array ();
			if ($product ['option']) {
				foreach ( $product ['option'] as $option ) {
					if ($option ['type'] != 'file') {
						$option_data [] = array (
								'product_option_id' => $option ['product_option_id'],
								'product_option_value_id' => $option ['product_option_value_id'],
								'product_option_id' => $option ['product_option_id'],
								'product_option_value_id' => $option ['product_option_value_id'],
								'option_id' => $option ['option_id'],
								'option_value_id' => $option ['option_value_id'],
								'name' => $option ['name'],
								'value' => $option ['option_value'],
								'type' => $option ['type'] 
						);
					} else {
						$this->load->library ( 'encryption' );
						
						$encryption = new Encryption ( $this->config->get ( 'config_encryption' ) );
						
						$option_data [] = array (
								'product_option_id' => $option ['product_option_id'],
								'product_option_value_id' => $option ['product_option_value_id'],
								'product_option_id' => $option ['product_option_id'],
								'product_option_value_id' => $option ['product_option_value_id'],
								'option_id' => $option ['option_id'],
								'option_value_id' => $option ['option_value_id'],
								'name' => $option ['name'],
								'value' => $encryption->decrypt ( $option ['option_value'] ),
								'type' => $option ['type'] 
						);
					}
				}
			}
			$additional = array (
					'date' => date ( "Y-m-d", strtotime ( $param ['time'] ) ),
					'promotion_code' => '' 
			);
			$producttotal=0;
			if ($product ['promotion'] && $product ['promotion'] ['promotion_price'] > 0) {
			
				$producttotal = $product_info_arr [1] * $product ['promotion'] ['promotion_price'];
			
				$sub_total['promotion'] += $product ['promotion'] ['promotion_price'] * $product_info_arr [1];
			} else {
				$sub_total['general'] += $product ['price'] * $product_info_arr [1];
				$producttotal = $product_info_arr [1] * $product ['price'];
			}
			$taxvalue=$this->tax->getRate ( $product ['tax_class_id'] );
			$taxes+=$taxvalue/100*$producttotal;
			$product_data [] = array (
					'product_id' => $product ['product_id'],
					'href' => $this->url->link ( 'product/product', '&product_id=' . $product ['product_id'] ),
					'name' => $product ['name'],
					'model' => $product ['sku'],
					'p_image' => $product ['image'],
					'prod_type' => $product ['prod_type'],
					'shipping' => $product ['shipping'],
					'promotion' => $product ['promotion'],
					'additional' => $additional,
					'option' => $option_data,
					'download' => $product ['download'],
					'quantity' => $product_info_arr [1],
					'subtract' => $product ['subtract'],
					'price' => $product ['price'],
					'total' => $producttotal,
					'rule_code' => $product ['rule_code'],
					'combine' => $product ['combine'], // 套餐
					'packing_type' => $product ['packing_type'], // 包装
					'tax' => $taxvalue 
			);
			

		}
		
		
		
	
		
		/* 对结果按照时间重新排序 */
		
		$this->data ['groups'] = array ();
		
		foreach ( $product_data as $result ) {
			if (isset ( $result ['additional'] ['date'] ) && $result ['additional'] ['date']) {
				$this->data ['groups'] [$result ['additional'] ['date']] [] = $result;
			} else {
				$this->data ['groups'] [0] [] = $result;
			}
		}
		
		ksort ( $this->data ['groups'] );
		
		$data ['products'] = $product_data;
		

		if(!isset($this->session->data ['platformpparam']['sub_total']))
		$this->session->data ['platformpparam']['sub_total'] = $sub_total;

		
		$total_data = array();
		$total=array();
		$total['promotion'] = 0;
		$total['general'] = 0;
		$total['fee']=0;
		$total['discount']=0;
		$total['total']=0;


			$results=array();
			$results[0]['code']='sub_total';
			$results[1]['code']='platform';
			$results[2]['code']='total';
			foreach ($results as $result ) {
					$this->load->model ( 'total/' . $result ['code'] );
					$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
			}

		unset( $this->session->data['platformpparam']);
		$data ['totals'] = $total_data;		
		$data ['total'] = $total['total'];
		
		
		$data['payments'][] = array(
				'code'    =>  'platform',
				'value'   =>  $total['total'],
				'status'=>1
		);
		
		$data ['payment_code'] = 'platform';
		
		$data ['reward'] = $this->cart->getTotalRewardPoints ();
		
		$data ['affiliate_id'] = 0;
		$data ['commission'] = 0;
		
		$data ['language_id'] = $this->config->get ( 'config_language_id' );
		$data ['currency_id'] = $this->currency->getId ();
		$data ['currency_code'] = $this->currency->getCode ();
		$data ['currency_value'] = $this->currency->getValue ( $this->currency->getCode () );
		$data ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$data ['is_send'] = $param ['is_send'];
		
		$this->load->model ( 'checkout/order' );
		
		if ($order_id = $this->model_checkout_order->existOrderVerify ( $data ['verify'] )) {
			$this->error ['create'] ['error'] = '3';
			$this->error ['create'] ['message'] = '订单重复';
			$this->error ['create'] ['order_id'] = $order_id;
			$this->response->setOutput ( json_encode ( $this->error ['create'] ) );
			$this->log_sys->info ( 'error:' . serialize ( $this->error ['create'] ) );
			$this->log_sys->warn ( serialize ( $this->error ['create'] ) );
			return;
		}
		
		
		
		$data ['min_pre_times'] =$param ['min_pre_times'];
		
		
		$result = array ();

		$order_id = $this->model_checkout_order->create ( $data );
		$this->log_sys->info ( 'data:' . json_encode ( $data ) );
		$this->log_sys->info ( 'data:' . serialize ( $data ) );
		
		if ($order_id) {
			
			if (isset ( $param ['order_status_id'] ) && $param ['order_status_id'] > 0) {
				$order_status_id = $param ['order_status_id'];
			} else {
				$order_status_id = 2;
			}
			$this->model_checkout_order->updateOrderStatus ( $order_id, $order_status_id, $param ['partner_code'] );
			
			$this->log_sys->info ( 'order_id:' . $order_id );
			$orde_info = $this->model_sale_order->getOrder ( $order_id );
			$this->log_sys->info ( 'orde_info:' . json_encode ( $orde_info ) );
			
			$this->log_sys->info ( $orde_info );
			
			if ($orde_info) {
				$result ['pickupCode'] = $orde_info ['pickup_code'];
			}
			$result ['order_id'] = $order_id;
			$result ['customer_id'] = $customer_id;
			$result ['error'] = '0';
		} else {
			$result ['error'] = '1';
			$result ['message'] =json_encode($this->model_checkout_order->error);
			$this->error['create']=$this->model_checkout_order->error;
			$this->log_sys->info ( 'ordercreat:' . json_encode ( $this->model_checkout_order->error ) );
		}
		
		/*
		 * if($result){
		 * $this->m->setSuccess($result,null,1);
		 * }else{
		 * $this->m->setSuccess(array(),null,0);
		 * }
		 */
		
		$out = json_encode ( $result );
		$this->log_sys->info ( 'api:end:$out:' . $out );
		$this->log_sys->info ( 'out:' . $out );
		$this->log_sys->info ( 'end' );
		$this->response->setOutput ( $out );
	}
	public function _update() {
		$this->init ();
		
		// -- $_GET params ------------------------------
		$data = array ();
		if (! $this->_checkCancleRequest ( $data )) {
			$this->m->setError ( mCartResult::ERROR_SYSTEM_INVALID_API );
			$this->response->setOutput ( json_encode ( $this->m->returnResult () ) );
			return;
		}
		
		// -- End $_GET params --------------------------
		
		$this->model_checkout_order->updateOrderStatus ( $data ['order_id'], $data ['order_status_id'], $data ['partner_code'] );
		$result = array (
				'success' => true 
		);
		$this->m->setSuccess ( $result, null, 0 );
		
		if ($this->debug) {
			echo '<pre>';
			json_encode ( $this->m->returnResult () );
		} else {
			$this->response->setOutput ( json_encode ( $this->m->returnResult () ) );
		}
		
		if ($this->debug) {
			echo '<pre>';
			json_encode ( $this->m->returnResult () );
		} else {
			$this->response->setOutput ( json_encode ( $this->m->returnResult () ) );
		}
	}
	public function _list() {
		$this->init ();
		
		// -- $_GET params ------------------------------
		$data = array ();
		if (! $this->_checkListRequest ( $data )) {
			$this->m->setError ( mCartResult::ERROR_SYSTEM_INVALID_API );
			$this->response->setOutput ( json_encode ( $this->m->returnResult () ) );
			return;
		}
		
		// -- End $_GET params --------------------------
		
		$this->load->model ( 'sale/order' );
		
		$this->data ['orders'] = array ();
		
		$data = array (
				'filter_order_id' => $filter_order_id,
				// 'filter_customer' => $filter_customer,
				'filter_order_partner' => $data ['partner_code'],
				// 'filter_order_status_id' => $filter_order_status_id,
				// 'filter_total' => $filter_total,
				'filter_date_added' => $filter_date_added,
				// 'filter_date_modified' => $filter_date_modified,
				'start' => $data ['start'],
				'limit' => $data ['limit'] 
		);
		
		$order_total = $this->model_sale_order->getTotalOrders ( $data );
		
		$results = $this->model_sale_order->getOrders ( $data );
		
		foreach ( $results as $result ) {
			$action = array ();
			
			$action [] = array (
					'text' => $this->language->get ( 'text_view' ),
					'href' => $this->url->link ( 'sale/order/info', 'token=' . $this->session->data ['token'] . '&order_id=' . $result ['order_id'] . $url, 'SSL' ) 
			);
			$common = new Common ( $this->registry );
			
			$this->data ['orders'] [] = array (
					'order_id' => $result ['order_id'],
					'pdate' => $result ['pdate'],
					'customer' => $result ['email'],
					'status' => $result ['status'],
					'total' => $this->currency->format ( $result ['total'], $result ['currency_code'], $result ['currency_value'] ),
					'date_added' => $result ['date_added'],
					'date_modified' => $result ['date_modified'],
					'partner_code' => $result ['partner_code'] 
			);
		}
		
		if ($results) {
			$this->m->setSuccess ( $this->data ['orders'], null, $order_total );
		} else {
			$this->m->setSuccess ( array (), null, $order_total );
		}
		
		if ($this->debug) {
			echo '<pre>';
			json_encode ( $this->m->returnResult () );
		} else {
			$this->response->setOutput ( json_encode ( $this->m->returnResult () ) );
		}
	}
	public function _detail() {
		$this->init ();
		
		// -- $_GET params ------------------------------
		$data = array ();
		if (! $this->_checkDetailRequest ( $data )) {
			$this->m->setError ( mCartResult::ERROR_SYSTEM_INVALID_API );
			$this->response->setOutput ( json_encode ( $this->m->returnResult () ) );
			return;
		}
		
		// -- End $_GET params --------------------------
		
		$this->load->model ( 'checkout/order' );
		$order_info = $this->model_checkout_order->getOrder ( $data ['order_id'], $data ['partner_code'] );
		if ($order_info) {
			$this->load->model ( 'sale/order' );
			$order_products = $this->model_sale_order->getOrderProducts ( $data ['order_id'] );
			$result = array (
					'order_id' => $order_info ['order_id'],
					'shipping_method' => $order_info ['shipping_method'],
					'telephone' => $order_info ['telephone'],
					'pdate' => $order_info ['pdate'],
					'total' => $order_info ['total'],
					'products' => $order_products 
			);
			$this->m->setSuccess ( $result, null, 1 );
		} else {
			$this->m->setSuccess ( array (), null, 0 );
		}
		
		if ($this->debug) {
			echo '<pre>';
			json_encode ( $this->m->returnResult () );
		} else {
			$this->response->setOutput ( json_encode ( $this->m->returnResult () ) );
		}
		
		if ($this->debug) {
			echo '<pre>';
			json_encode ( $this->m->returnResult () );
		} else {
			$this->response->setOutput ( json_encode ( $this->m->returnResult () ) );
		}
	}
	protected function _checkCreateRequest(&$data) {
		$flag = true;
		$data ['stype'] = null;
		$data ['time'] = null;
		$data ['min_pre_times'] = null;
		$data ['pid'] = null;
		$data ['point_name'] = null;
		$data ['partner_code'] = null;
		$data ['phone_num'] = null;
		$data ['product_infos'] = null;
		$data ['comment'] = null;
		$data ['total_fee'] = null;
		$data ['total_discount'] = null;
		$data ['total_subsides'] = null;
		$data ['total_value'] = null;
		$data ['sub_total'] = null;
		
		$data ['product_infos_par'] = null;
		$data ['tp_order_id'] = null;
		
		$data ['sign'] = null;
		$data ['appid'] = null;
		$data ['sign_method'] = null;
		$data ['is_send'] = null;
		
		$data ['point_id'] = null;
		
		$data ['shipping_code'] = null;
		$data ['shipping_data'] = null;
		
		$data ['payment_method'] = '';
		$data ['shipping_firstname'] = '';
		$data ['shipping_mobile'] = '';
		$data ['shipping_address_1'] = '';
		
		if ($this->_postIsNotEmpty ( "stype" )) {
			$data ['stype'] = $this->request->post ['stype'];
		} else {
			$data ['stype'] = 1;
		}
		
		if ($this->_postIsNotEmpty ( "min_pre_times" )) {
			$data ['min_pre_times'] = $this->request->post ['min_pre_times'];
		} else {
			$data ['min_pre_times'] = 0;
		}
		
	
		
		if ($this->_postIsNotEmpty ( "appid" )) {
			$data ['appid'] = $this->request->post ['appid'];
		} else {
			$flag = false;
			$this->log_sys->info ( 'error:appid:' . json_encode ( $data ) );
			$this->error ['checkCreateRequest'] ['error'] = + 1;
			$this->error ['checkCreateRequest'] ['message'] .= 'error:appid not allow empty';
		}
		if ($this->_postIsNotEmpty ( "sign_method" )) {
			$data ['sign_method'] = $this->request->post ['sign_method'];
		} else {
			$flag = false;
			$this->log_sys->info ( 'error:sign_method:' . json_encode ( $data ) );
			$this->error ['checkCreateRequest'] ['error'] = + 1;
			$this->error ['checkCreateRequest'] ['message'] .= 'error:sign_method not allow empty';
		}
		
		if ($this->_postIsNotEmpty ( "sign" )) {
			$data ['sign'] = $this->request->post ['sign'];
		} else {
			$flag = false;
			$this->log_sys->info ( 'error:sign:' . json_encode ( $data ) );
			$this->error ['checkCreateRequest'] ['error'] = + 1;
			$this->error ['checkCreateRequest'] ['message'] .= 'error:sign not allow empty';
		}
		
		if ($this->_postIsNotEmpty ( "is_send" )) {
			$data ['is_send'] = $this->request->post ['is_send'];
		} else {
			$flag = false;
			$this->log_sys->info ( 'error:is_send:' . json_encode ( $data ) );
			$this->error ['checkCreateRequest'] ['error'] = + 1;
			$this->error ['checkCreateRequest'] ['message'] .= 'error:is_send not allow empty';
		}
		
		if ($this->_postIsNotEmpty ( "product_infos" )) {
			$data ['product_infos_par'] = $this->request->post ['product_infos'];
			$data ['product_infos'] = explode ( ';', $this->request->post ['product_infos'] );
		} else {
			$flag = false;
			$this->log_sys->info ( 'error:product_infos:' . json_encode ( $data ) );
			$this->error ['checkCreateRequest'] ['error'] = + 1;
			$this->error ['checkCreateRequest'] ['message'] .= 'error:product_infos not allow empty';
		}
		
		if ($this->_postIsNotEmpty ( "comment" )) {
			$data ['comment'] = $this->request->post ['comment'];
			
		}
		if ($this->_postIsNotEmpty ( "total_fee" )) {
			$data ['total_fee'] = $this->request->post ['total_fee'];
				
		}
		if ($this->_postIsNotEmpty ( "total_discount" )) {
			$data ['total_discount'] = $this->request->post ['total_discount'];
				
		}
		if ($this->_postIsNotEmpty ( "total_subsides" )) {
			$data ['total_subsides'] = $this->request->post ['total_subsides'];
		}
		
		if ($this->_postIsNotEmpty ( "sub_total" )) {
			
			$sub_total=array();
			$sub_total['promotion'] = $this->request->post ['sub_total'];
			$sub_total['general']=0;
			$sub_total['fee']=0;
			
			$data ['sub_total']=$sub_total;
		}
		
		if ($this->_postIsNotEmpty ( "total_value" )) {
			$data ['total_value'] = $this->request->post ['total_value'];
		
		}

		
		if ($this->_postIsNotEmpty ( "partner_code" )) {
			$data ['partner_code'] = $this->request->post ['partner_code'];
		} else {
			$flag = false;
			$this->log_sys->info ( 'error:partner_code:' . json_encode ( $data ) );
			$this->error ['checkCreateRequest'] ['error'] = + 1;
			$this->error ['checkCreateRequest'] ['message'] .= 'error:partner_code not allow empty';
		}
		
		$data ['payment_method'] = $this->request->post ['payment_method'];
		$data ['order_status_id'] = $this->request->post ['order_status_id'];
		
		
		if ($this->_postIsNotEmpty ( "pid" )) {
			$data ['pid'] = (int)$this->request->post ['pid'];
		} else {
			$data ['pid'] = 0;
		}
		
		if ($data ['stype'] == '1') { // 自提模式
			
			if ($this->_postIsNotEmpty ( "pid" )) {
				$data ['pid'] = $this->request->post ['pid'];
			} else {
				$flag = false;
				$this->log_sys->info ( 'error:pid:' . json_encode ( $data ) );
				$this->error ['checkCreateRequest'] ['error'] = + 1;
				$this->error ['checkCreateRequest'] ['message'] .= 'error:pid not allow empty';
			}
			
			if ($this->_postIsNotEmpty ( "point_id" )) {
				$data ['point_id'] = $this->request->post ['point_id'];
			} else {
				$flag = false;
				$this->log_sys->info ( 'error:point_id:' . json_encode ( $data ) );
				$this->error ['checkCreateRequest'] ['error'] = + 1;
				$this->error ['checkCreateRequest'] ['message'] .= 'error:pid not allow empty';
			}
			
			if ($this->_postIsNotEmpty ( "phone_num" )) {
				$data ['phone_num'] = $this->request->post ['phone_num'];
			} else {
				$flag = false;
				$this->log_sys->info ( 'error:phone_num:' . json_encode ( $data ) );
				$this->error ['checkCreateRequest'] ['error'] = + 1;
				$this->error ['checkCreateRequest'] ['message'] .= 'error:phone_num not allow empty';
			}
			
			if ($this->_postIsNotEmpty ( "point_name" )) {
				$data ['point_name'] = $this->request->post ['point_name'];
			}
		} elseif ($data ['stype'] == '2') { // 宅配模式
			
			if ($this->_postIsNotEmpty ( "shipping_code" )) {
				$data ['shipping_code'] = $this->request->post ['shipping_code'];
			} else {
				$flag = false;
				$this->log_sys->info ( 'error:point_id OR shipping_code:' . json_encode ( $data ) );
				$this->error ['checkCreateRequest'] ['error'] = + 1;
				$this->error ['checkCreateRequest'] ['message'] .= 'error:(point_id OR shipping_code) not allow empty';
			}
			
			if ($this->_postIsNotEmpty ( "shipping_data" )) {
				$data ['shipping_data'] = $this->request->post ['shipping_data'];
			} else {
				$flag = false;
				$this->log_sys->info ( 'error:shipping_data:' . json_encode ( $data ) );
				$this->error ['checkCreateRequest'] ['error'] = + 1;
				$this->error ['checkCreateRequest'] ['message'] .= 'error:shipping_data not allow empty';
			}
			
			if ($this->_postIsNotEmpty ( "shipping_poi" )) {
				$data ['shipping_poi'] = $this->request->post ['shipping_poi'];
			}
			
			if ($this->_postIsNotEmpty ( "shipping_firstname" )) {
				$data ['shipping_firstname'] = $this->request->post ['shipping_firstname'];
			} else {
				$flag = false;
				$this->log_sys->info ( 'error:shipping_firstname:' . json_encode ( $data ) );
				$this->error ['checkCreateRequest'] ['error'] = + 1;
				$this->error ['checkCreateRequest'] ['message'] .= 'error:shipping_firstname not allow empty';
			}
			if ($this->_postIsNotEmpty ( "shipping_mobile" ) && $this->request->is_phone ( $this->request->post ['shipping_mobile'] )) {
				$data ['shipping_mobile'] = $this->request->post ['shipping_mobile'];
				$data ['phone_num'] = $this->request->post ['shipping_mobile'];
			} else {
				$flag = false;
				$this->log_sys->info ( 'error:shipping_mobile:' . json_encode ( $data ) );
				$this->error ['checkCreateRequest'] ['error'] = + 1;
				$this->error ['checkCreateRequest'] ['message'] .= 'error:shipping_mobile not allow empty';
			}
			if ($this->_postIsNotEmpty ( "shipping_address" )) {
				$data ['shipping_address_1'] = $this->request->post ['shipping_address'];
			} else {
				$flag = false;
				$this->log_sys->info ( 'error:shipping_address:' . json_encode ( $data ) );
				$this->error ['checkCreateRequest'] ['error'] = + 1;
				$this->error ['checkCreateRequest'] ['message'] .= 'error:shipping_address not allow empty';
			}
			
			if ($this->_postIsNotEmpty ( "source_from" )) {
				$data ['source_from'] = $this->request->post ['source_from'];
			}
		}
		
		if ($this->_postIsNotEmpty ( "tp_order_id" )) {
			$data ['tp_order_id'] = $this->request->post ['tp_order_id'];
		}
		
		if ($this->_postIsNotEmpty ( "time" )) {
			$data ['time'] = $this->request->post ['time'];

		} else {
			$flag = false;
			$this->log_sys->info ( 'error:time:noempty:' . json_encode ( $data ) );
			$this->error ['checkCreateRequest'] ['error'] = + 1;
			$this->error ['checkCreateRequest'] ['message'] .= 'error:time not allow empty';
		}
		
		if (LOG) {
			$this->log_sys->info ( '[Order Create] Interface .Request From [' . $this->getIp () . ']' . 'Params[time:' . $data ['time'] . ']' . 'Params[point_id:' . $data ['point_id'] . ']' . 'Params[partner_code:' . $data ['partner_code'] . ']' . 'Params[phone_num:' . $data ['phone_num'] . ']' . 'Params[product_infos:' . $data ['product_infos_par'] . ']' );
		}
		$this->log_sys->info ( 'checkCreateRequest:$flag:' . $flag );
		return $flag;
	}
	protected function _checkDetailRequest(&$data) {
		$flag = true;
		$data ['order_id'] = null;
		$data ['partner_code'] = null;
		
		/*
		 * if($this->_postIsNotEmpty("order_id")){
		 * $data['order_id'] =$this->request->post['order_id'];
		 * }else{
		 * $flag = false;
		 * }
		 *
		 * if($this->_postIsNotEmpty("partner_code")){
		 * $data['partner_code'] =$this->request->post['partner_code'];
		 * }else{
		 * $flag = false;
		 * }
		 *
		 */
		
		if ($this->_getIsNotEmpty ( "order_id" )) {
			$data ['order_id'] = $this->request->get ['order_id'];
		} else {
			$flag = false;
		}
		
		if ($this->_getIsNotEmpty ( "partner_code" )) {
			$data ['partner_code'] = $this->request->get ['partner_code'];
		} else {
			$flag = false;
		}
		
		return $flag;
	}
	protected function _checkListRequest(&$data) {
		$flag = true;
		$data ['order_id'] = null;
		$data ['partner_code'] = null;
		$data ['pagenum'] = null;
		$data ['pagesize'] = null;
		
		/*
		 * if($this->_postIsNotEmpty("order_id")){
		 * $data['order_id'] =$this->request->post['order_id'];
		 * }else{
		 * $flag = false;
		 * }
		 *
		 * if($this->_postIsNotEmpty("partner_code")){
		 * $data['partner_code'] =$this->request->post['partner_code'];
		 * }else{
		 * $flag = false;
		 * }
		 */
		
		if ($this->_getIsNotEmpty ( "pagenum" ) && $this->_getIsNotEmpty ( "pagesize" )) {
			
			$data ['pagenum'] = ( int ) $this->request->get ['pagenum'];
			$data ['pagesize'] = ( int ) $this->request->get ['pagesize'];
			
			$pagenum = ( int ) $this->request->get ['pagenum'];
			if ($pagenum < 0) {
				$pagenum = 0;
			} else {
				$pagenum = $pagenum - 1;
			}
			
			$pagesize = $this->request->get ['pagesize'];
			$data ['limit'] = $pagesize;
			$data ['start'] = $pagenum * $pagesize;
		} else {
			$data ['limit'] = 20;
			$data ['start'] = 0;
		}
		
		if ($this->_getIsNotEmpty ( "order_id" )) {
			$data ['order_id'] = $this->request->get ['order_id'];
		} else {
			$flag = false;
		}
		
		if ($this->_getIsNotEmpty ( "partner_code" )) {
			$data ['partner_code'] = $this->request->get ['partner_code'];
		} else {
			$flag = false;
		}
		
		__log ( '[Order List] Interface .Request From [' . $this->getIp () . ']' . 'Params[order_id:' . $data ['order_id'] . ']' . 'Params[partner_code:' . $data ['partner_code'] . ']' . 'Params[pagenum:' . $data ['pagenum'] . ']' . 'Params[pagesize:' . $data ['pagesize'] . ']' );
		
		return $flag;
	}
	protected function _checkCancleRequest(&$data) {
		$flag = true;
		$data ['order_id'] = null;
		$data ['order_status_id'] = null;
		$data ['partner_code'] = null;
		
		if ($this->_postIsNotEmpty ( "order_id" )) {
			$data ['order_id'] = $this->request->post ['order_id'];
		} else {
			$flag = false;
		}
		
		if ($this->_postIsNotEmpty ( "partner_code" )) {
			$data ['partner_code'] = $this->request->post ['partner_code'];
		} else {
			$flag = false;
		}
		
		$this->load->model ( 'checkout/order' );
		$order_info = $this->model_checkout_order->getOrder ( $data ['order_id'], $data ['partner_code'] );
		if (! $order_info) {
			$flag = false;
		} else {
			if ($this->_postIsNotEmpty ( "order_status" )) {
				if ((( int ) $this->request->post ['order_status']) == 0) { // 取消订单
					if ($order_info ['pdate'] == date ( "Y-m-d", time () )) { // 当天取货
						if (time () >= mktime ( 04, 00, 00, date ( "m", time () ), date ( "d", time () ), date ( "Y", time () ) )) { // 如果当前时间大于凌晨4点不可取消订单
							$flag = false;
						}
					}
				}
			}
			if ($order_info ['order_status_id'] == EnumOrderStatus::Complete) { // 已完成的订单不能取消
				if ((( int ) $this->request->post ['order_status']) == 0) {
					$flag = false;
				}
			}
			
			if ($order_info ['order_status_id'] == EnumOrderStatus::Cancel) { // 已完成的订单不能取消
				if ((( int ) $this->request->post ['order_status']) == 1) {
					$flag = false;
				}
			}
		}
		
		if ($this->_postIsNotEmpty ( "order_status" )) {
			if ((( int ) $this->request->post ['order_status']) == 0) { // 取消订单
				$data ['order_status_id'] = EnumOrderStatus::Cancel;
			} else if ((( int ) $this->request->post ['order_status']) == 1) { // 订单完成
				$data ['order_status_id'] = EnumOrderStatus::Complete;
			} else {
				$flag = false;
			}
		} else {
			$flag = false;
		}
		
		/*
		 * if($this->_getIsNotEmpty("order_id")){
		 * $data['order_id'] =$this->request->get['order_id'];
		 * }else{
		 * $flag = false;
		 * }
		 *
		 * if($this->_getIsNotEmpty("partner_code")){
		 * $data['partner_code'] =$this->request->get['partner_code'];
		 * }else{
		 * $flag = false;
		 * }
		 *
		 * $this->load->model('checkout/order');
		 * $order_info = $this->model_checkout_order->getOrder($data['order_id'],$data['partner_code']);
		 * if(!$order_info){
		 * $flag = false;
		 * }else {
		 * if($this->_getIsNotEmpty("order_status")){
		 * if(((int)$this->request->get['order_status'])==0){//取消订单
		 * if($order_info['pdate']==date("Y-m-d",time())){//当天取货
		 * if(time()>=mktime(04,00,00,date("m",time()),date("d",time()),date("Y",time()))){//如果当前时间大于凌晨4点不可取消订单
		 * $flag = false;
		 * }
		 * }
		 * }
		 * }
		 * if($order_info['order_status_id']==EnumOrderStatus::Complete){//已完成的订单不能取消
		 * if(((int)$this->request->get['order_status'])==0){
		 * $flag = false;
		 * }
		 * }
		 * }
		 *
		 * if($this->_getIsNotEmpty("order_status")){
		 * if(((int)$this->request->get['order_status'])==0){//取消订单
		 * $data['order_status_id'] =EnumOrderStatus::Cancel;
		 * }else if(((int)$this->request->get['order_status'])==1){//订单完成
		 * $data['order_status_id'] =EnumOrderStatus::Complete;
		 * }else{
		 * $flag = false;
		 * }
		 * }else{
		 * $flag = false;
		 * }
		 */
		
		if (LOG) {
			__log ( '[Order update] Interface .Request From [' . $this->getIp () . ']' . 'Params[order_id:' . $data ['order_id'] . ']' . 'Params[partner_code:' . $data ['partner_code'] . ']' . 'Params[order_status_id:' . $data ['order_status_id'] . ']' );
		}
		return $flag;
	}
	private function _getIsNotEmpty($param) {
		return (isset ( $this->request->get [$param] ) && $this->request->get [$param] != null);
	}
	private function _getIsEmpty($param) {
		return (! isset ( $this->request->get [$param] ) || $this->request->get [$param] == null);
	}
	private function _postIsNotEmpty($param) {
		return (isset ( $this->request->post [$param] ) && $this->request->post [$param] != null);
	}
	private function _postIsEmpty($param) {
		return (! isset ( $this->request->post [$param] ) || $this->request->post [$param] == null);
	}
	function __call($methodName, $arguments) {
		// call_user_func(array($this, str_replace('.', '_', $methodName)), $arguments);
		call_user_func ( array (
				$this,
				"_$methodName" 
		), $arguments );
	}
	public function getIp() {
		if ($HTTP_SERVER_VARS ["HTTP_X_FORWARDED_FOR"]) {
			$ip = $HTTP_SERVER_VARS ["HTTP_X_FORWARDED_FOR"];
		} elseif ($HTTP_SERVER_VARS ["HTTP_CLIENT_IP"]) {
			$ip = $HTTP_SERVER_VARS ["HTTP_CLIENT_IP"];
		} elseif ($HTTP_SERVER_VARS ["REMOTE_ADDR"]) {
			$ip = $HTTP_SERVER_VARS ["REMOTE_ADDR"];
		} elseif (getenv ( "HTTP_X_FORWARDED_FOR" )) {
			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
		} elseif (getenv ( "HTTP_CLIENT_IP" )) {
			$ip = getenv ( "HTTP_CLIENT_IP" );
		} elseif (getenv ( "REMOTE_ADDR" )) {
			$ip = getenv ( "REMOTE_ADDR" );
		} else {
			$ip = "Unknown";
		}
		return $ip;
	}
}

?>