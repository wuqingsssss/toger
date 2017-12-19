<?php
class ControllerSaleOrder extends Controller {
	private $error = array ();
	
	private $payment_method_arr = array(
			'alipay' => '支付宝',
			'wxpay' => '微信支付',
			'balance' => '储值支付',
			'balance+wxpay' => '储值支付+微信支付',
			'balance+alipay' => '储值支付+支付宝',
			'free_checkout' => '免费支付',
			'cash' => '现金支付',
			'bdpay' => '百度支付'
	);
	
	private $order_type_arr = array(
			0 =>'普通',100=>'拼团',200=>'T+0'
	);
	public function index() {
		$this->load_language ( 'sale/order' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'sale/order' );
		
		$this->getList ();
	}
	
	/**
	 * 追加新订单
	 */
	public function insert() {
		$this->load_language ( 'sale/order' );
		// $this->load_language('sale/order_update');
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'sale/order' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			$ip = $_SERVER ['REMOTE_ADDR'];
			$this->model_sale_order->addOrder ( $this->request->post, $ip );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$url = '';
			
			$this->redirect ( $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
		}
		
		$this->data ['operation'] = EnumOperation::INSERT;
		
		$this->getForm ();


	}
	protected function getCbdsByCityId($city_id) {
		$this->load->model ( 'localisation/cbd' );
		
		$results = $this->model_localisation_cbd->getCbdsByCityId ( $city_id );
		
		foreach ( $results as $index => $result ) {
			$points = $this->getPointsByCbdId ( $result ['id'] );
			if ($points) {
				$results [$index] ['points'] = $this->getPointsByCbdId ( $result ['id'] );
			} else {
				unset ( $results [$index] );
			}
		}
		
		return $results;
	}
	protected function getPointsByCbdId($cbd_id) {
		$this->load->model ( 'catalog/point' );
		
		$filter = array ();
		$points = array ();
		
		$filter ['filter_point_cbd_id'] = $cbd_id;
		$filter ['filter_status'] = 1; // 只取有效自提点
		
		$this->data ['points'] = array ();
		
		$point_results = $this->model_catalog_point->getPoints ( $filter );
		
		foreach ( $point_results as $result ) {
			if ($result ['status']) {
				$points [] = array (
						'point_id' => $result ['point_id'],
						'name' => $result ['name'],
						'address' => $result ['address'],
						'business_hour' => $result ['business_hour'],
						'telephone' => $result ['telephone'] 
				);
			}
		}
		
		return $points;
	}
	public function initdata() {
		$this->load->model ( 'localisation/city' );
		
		$cities = $this->model_localisation_city->getCitiesByZoneId ( $this->config->get ( 'config_zone_id' ) );
		
		foreach ( $cities as $index => $city ) {
			$cbds = $this->getCbdsByCityId ( $city ['city_id'] );
			
			if ($cbds) {
				$cities [$index] ['cbds'] = $this->getCbdsByCityId ( $city ['city_id'] );
			} else {
				unset ( $cities [$index] );
			}
		}
		
		$json = $cities;
		
		$this->load->library ( 'json' );
		
		$this->response->setOutput ( Json::encode ( $json ) );
	}
	
	/**
	 * 订单修改
	 */
	public function update() {
		$this->load_language ( 'sale/order' );
		// $this->load_language('sale/order_update');
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'sale/order' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			$this->model_sale_order->updateOrder ( $this->request->get ['order_id'], $this->request->post );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$this->redirect ( $this->url->link ( 'sale/order/info', 'token=' . $this->session->data ['token'] . '&order_id=' . $this->request->get ['order_id'], 'SSL' ) );
		}
		$this->data ['action'] = 'update';
		
		$this->data ['operation'] = EnumOperation::EDIT;
		$this->getForm ();
	}
	public function delete() {
		$this->load_language ( 'sale/order' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'sale/order' );
		if (isset ( $this->request->post ['selected'] ) && ($this->validateDelete ())) {
			foreach ( $this->request->post ['selected'] as $order_id ) {
				$this->model_sale_order->deleteOrder ( $order_id );
			}
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$url = '';
			
			if (isset ( $this->request->get ['filter_order_id'] )) {
				$url .= '&filter_order_id=' . $this->request->get ['filter_order_id'];
			}
			
			if (isset ( $this->request->get ['filter_customer'] )) {
				$url .= '&filter_customer=' . $this->request->get ['filter_customer'];
			}
			
			if (isset ( $this->request->get ['filter_order_status_id'] )) {
				$url .= '&filter_order_status_id=' . $this->request->get ['filter_order_status_id'];
			}
			
			if (isset ( $this->request->get ['filter_total'] )) {
				$url .= '&filter_total=' . $this->request->get ['filter_total'];
			}
			
			if (isset ( $this->request->get ['filter_date_added'] )) {
				$url .= '&filter_date_added=' . $this->request->get ['filter_date_added'];
			}
			
			if (isset ( $this->request->get ['filter_date_pick'] )) {
				$url .= '&filter_date_pick=' . $this->request->get ['filter_date_pick'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$url .= '&page=' . $this->request->get ['page'];
			}
			
			$this->redirect ( $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
		}
		
		$this->getList ();
	}
	private function getList() {
		if (isset ( $this->request->get ['filter_order_id'] )) {
			$filter_order_id = $this->request->get ['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		
		if (isset ( $this->request->get ['filter_partner_code'] )) {
			$filter_partner_code = $this->request->get ['filter_partner_code'];
		} else {
			$filter_partner_code = null;
		}
		
		if (isset ( $this->request->get ['filter_source_from'] )) {
			$filter_source_from = $this->request->get ['filter_source_from'];
		} else {
			$filter_source_from = null;
		}
		
		if (isset ( $this->request->get ['filter_point_id'] )) {
			$filter_point_id = $this->request->get ['filter_point_id'];
		} else {
			$filter_point_id = null;
		}
		if (isset ( $this->request->get ['filter_point_name'] )) {
			$filter_point_name = $this->request->get ['filter_point_name'];
		} else {
			$filter_point_name = null;
		}
		
		if (isset ( $this->request->get ['filter_customer'] )) {
			$filter_customer = $this->request->get ['filter_customer'];
		} else {
			$filter_customer = null;
		}
		if (isset ( $this->request->get ['filter_customer_phone'] )) {
			$filter_customer_phone = $this->request->get ['filter_customer_phone'];
		} else {
			$filter_customer_phone = null;
		}
		
		if (isset ( $this->request->get ['filter_order_status_id'] )) {
			$filter_order_status_id = $this->request->get ['filter_order_status_id'];
		} else {
			$filter_order_status_id = null;
		}
		
		if (isset ( $this->request->get ['filter_total'] )) {
			$filter_total = $this->request->get ['filter_total'];
		} else {
			$filter_total = null;
		}
		
		if (isset ( $this->request->get ['payment_code'] )) {
			$payment_code = $this->request->get ['payment_code'];
		} else {
			$payment_code = null;
		}
		
		if (isset ( $this->request->get ['order_type'] ) && $this->request->get ['order_type'] != '*') {
			$order_type = intval($this->request->get ['order_type']);
		} else {
			$order_type = null;
		}
		
		if (isset ( $this->request->get ['filter_date_added'] )) {
			$filter_date_added = $this->request->get ['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		
		if (isset ( $this->request->get ['filter_date_pick'] )) {
			$filter_date_pick = $this->request->get ['filter_date_pick'];
		} else {
			$filter_date_pick = null;
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$sort = $this->request->get ['sort'];
		} else {
			$sort = 'o.order_id';
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$order = $this->request->get ['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset ( $this->request->get ['pdate'] )) {
			$pdate = $this->request->get ['pdate'];
		} else {
			$pdate = 'DESC';
		}
		
		if (isset ( $this->request->get ['date_added'] )) {
			$date_added = $this->request->get ['date_added'];
		} else {
			$date_added = 'DESC';
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$url = $this->getUrlParameters ();
		
		$this->data ['breadcrumbs'] = array ();
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => false 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . $url, 'SSL' ),
				'separator' => $this->language->get ( 'text_breadcrumb_separator' ) 
		);
		
		$this->data ['invoice'] = $this->url->link ( 'sale/order/invoice', 'token=' . $this->session->data ['token'], 'SSL' );
		$this->data ['delete'] = $this->url->link ( 'sale/order/delete', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		
		$this->data ['export_select'] = $this->url->link ( 'sale/order/export', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		$this->data ['button_export'] = $this->language->get ( 'button_export' );
		
		$this->data ['import'] = $this->url->link ( 'sale/order/import', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		$this->data ['link_insert_order'] = $this->url->link ( 'sale/order/insert', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		
		$this->data ['orders'] = array ();
		$this->data['method_arr'] = $this->payment_method_arr;
		$this->data['order_type_arr'] = $this->order_type_arr;
		$data = array (
				'filter_order_id' => $filter_order_id,
				'filter_customer' => $filter_customer,
				'filter_customer_phone' => $filter_customer_phone,
				'filter_order_status_id' => $filter_order_status_id,
				'filter_total' => $filter_total,
				'filter_date_added' => $filter_date_added,
				'filter_date_pick' => $filter_date_pick,
				'filter_partner_code' => $filter_partner_code,
				'filter_source_from' => $filter_source_from,
				'filter_point_id' => $filter_point_id,
				'filter_point_name' => $filter_point_name,
				'payment_code' => $payment_code,
				'order_type' => $order_type,
				'sort' => $sort,
				'order' => $order,
				'date_added' => $date_added,
				'pdate' => $pdate,
				'start' => ($page - 1) * $this->config->get ( 'config_admin_limit' ),
				'limit' => $this->config->get ( 'config_admin_limit' ) 
		);
		
		$order_total = $this->model_sale_order->getTotalOrders ( $data );
		
		$results = $this->model_sale_order->getOrders ( $data );

		// 获取自提点列表
				$points_option = array ();
		
		
		
		
		$this->load->model('catalog/pointdelivery');

		
		$delivers = $this->model_catalog_pointdelivery->getDeliverys ();

		$status=array(1=>'',2=>'(测试)',0=>'(离线)');
		
		$statustitle=array();
		
		foreach ( $delivers as $deliver ) {
			$points_option [EnumDelivery::getDeliveryInfo($deliver['code'])][] = array (
					'value' => $deliver['code'].$deliver ['region_name'],
					'name' => '宅配：'.$deliver ['region_name'].$status[$deliver ['status']]
			);
			
			$statustitle[$deliver['code']][$deliver ['region_name']]=$status[$deliver ['status']];
		}
		$this->load->model ( 'catalog/point' );
		$points = $this->model_catalog_point->getPoints ();
		
		foreach ( $points as $point ) {
			$points_option [EnumDelivery::getDeliveryInfo('qncj')][] = array (
					'value' => $point ['point_id'],
					'name' => '自提：'.$point ['name'].$status[$point ['status']]
			);
			$statustitle['qncj'][$point ['point_id']]=$status[$point ['status']];	
		}
		
		
		//获取第三方平台列表
		$this->load->model('catalog/partnercode');
		$partners = $this->model_catalog_partnercode->getAllPartners();
		foreach ( $results as $result ) {
			$action = array ();
			
			$action [] = array (
					'text' => $this->language->get ( 'text_view' ),
					'href' => $this->url->link ( 'sale/order/info', 'token=' . $this->session->data ['token'] . '&order_id=' . $result ['order_id'] . $url, 'SSL' ) 
			);
		
			$sn = $result ['shipping_point_id'] ? $result['pdate'] : $result['shipping_time'];
			$ptimes = strtotime($sn);
			$dtimes = strtotime(date('Y-m-d',strtotime('+1 day')));
			$dtimes = strtotime(date('Y-m-d'));
			//if ($ptimes > $dtimes) {

			if ($result ['order_status_id']=='2'&&$ptimes >= $dtimes) {
				$action [] = array (
					'text' => $this->language->get ( 'text_edit' ),
					'href' => $this->url->link ( 'sale/order/update', 'token=' . $this->session->data ['token'] . '&order_id=' . $result ['order_id'] . $url, 'SSL' ) 
				);
			}
			
			
			// $common = new Common($this->registry);
			if ($result ['shipping_point_id']) {
				$shipping_method = '自提：' . $this->model_catalog_point->getPoint ( $result ['shipping_point_id'] )['name'].$statustitle['qncj'][ $result ['shipping_point_id']];
			} else {
				$shipping_method = '宅配：' . $result ['shipping_code'] . $result ['shipping_data'].$statustitle[$result ['shipping_code']][ $result ['shipping_data']];
			}
			$this->data ['orders'] [] = array (
					'order_id' => $result ['order_id'],
					'p_order_id' => $result ['p_order_id'],
					'pdate' => $result ['pdate'],
					'shipping_time' => $result ['shipping_time'],
					'payment_method' => $result['payment_method'],
					'payment_code' => $result['payment_code'],
//					'customer' => $result ['customer']?$result ['customer']:$result ['telephone']?$result ['telephone']:$result ['email'],
					'customer_href'=>$this->url->link ( 'sale/customer/update', 'token=' . $this->session->data ['token'] . '&customer_id=' . $result ['customer_id'] , 'SSL' ) ,
					'telephone' => $result ['telephone'], // TODO
					'payment_method' => $result ['payment_method'], // TODO
					'status' => $result ['status'],
//					'partner' => EnumPartners::getPartnerInfo ( $result ['partner_code'] ),
					'partner' =>$result['partner_code']? isset($partners[$result['partner_code']])? $partners[$result['partner_code']]:$result['partner_code']:'内站',
					'total' => $this->currency->format ( $result ['total'], $result ['currency_code'], $result ['currency_value'] ),
					'date_added' => $result ['date_added'],
					'pdate' => $result ['pdate'],
					'source_from' => EnumOrderSourceFrom::getOptionValue ( $result ['source_from'] ),
					'shipping_point_id' => $result ['shipping_point_id'],
					'shipping_method' => $shipping_method,
					'order_type' => $result['order_type'],
					'selected' => isset ( $this->request->post ['selected'] ) && in_array ( $result ['order_id'], $this->request->post ['selected'] ),
					'action' => $action 
			);
		}
		
		$this->data ['token'] = $this->session->data ['token'];
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		$url = $this->getCommonUrlParameters ();
		
		if ($order == 'ASC') {
			$url .= '&order=' . 'DESC';
		} else {
			$url .= '&order=' . 'ASC';
		}
		
		if ($pdate == 'ASC') {
			$url .= '&pdate=' . 'DESC';
		} else {
			$url .= '&pdate=' . 'ASC';
		}
		
		if ($date_added == 'ASC') {
			$url .= '&date_added=' . 'DESC';
		} else {
			$url .= '&date_added=' . 'ASC';
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$this->data ['sort_order'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . '&sort=o.order_id' . $url, 'SSL' );
		$this->data ['sort_customer'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . '&sort=customer' . $url, 'SSL' );
		$this->data ['sort_status'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . '&sort=status' . $url, 'SSL' );
		$this->data ['sort_total'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . '&sort=o.total' . $url, 'SSL' );
		$this->data ['sort_date_added'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . '&sort=o.date_added' . $url, 'SSL' );
		$this->data ['sort_date_pick'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . '&sort=o.pdate' . $url, 'SSL' );
		
		$url = $this->getCommonUrlParameters ();
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['pdate'] )) {
			$url .= '&order=' . $this->request->get ['pdate'];
		}
		
		if (isset ( $this->request->get ['date_added'] )) {
			$url .= '&order=' . $this->request->get ['date_added'];
		}
		
		$pagination = new Pagination ();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get ( 'config_admin_limit' );
		$pagination->text = $this->language->get ( 'text_pagination' );
		$pagination->url = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . $url . '&page={page}', 'SSL' );
		
		$this->data ['pagination'] = $pagination->render ();
		
		$this->data ['filter_order_id'] = $filter_order_id;
		$this->data ['filter_customer'] = $filter_customer;
		$this->data ['filter_customer_phone'] = $filter_customer_phone;
		$this->data ['filter_order_status_id'] = $filter_order_status_id;
		$this->data ['filter_total'] = $filter_total;
		$this->data ['filter_date_added'] = $filter_date_added;
		$this->data ['filter_date_pick'] = $filter_date_pick;
		$this->data ['filter_partner_code'] = $filter_partner_code;
		$this->data ['filter_source_from'] = $filter_source_from;
		$this->data ['filter_point_id'] = $filter_point_id;
		$this->data ['filter_point_name'] = $filter_point_name;
		$this->data ['payment_code'] = $payment_code;
		$this->data ['order_type'] = $order_type;
		
		$this->load->model ( 'localisation/order_status' );
		
		$this->data ['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses ();
		
		$this->data ['source_from_options'] = EnumOrderSourceFrom::getOptions ();
		
		$this->data ['shipping_point_options'] = $points_option;
		$this->data ['sort'] = $sort;
		$this->data ['order'] = $order;
		$this->data ['pdate'] = $pdate;
		$this->data ['date_added'] = $date_added;
		//第三方平台列表
		$this->data['partners'] = $partners;
		$this->template = 'sale/order_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render ();
	}
	public function import() {
		$this->load->language ( 'sale/order' );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->load->model ( 'sale/order' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			if ((isset ( $this->request->files ['upload'] )) && (is_uploaded_file ( $this->request->files ['upload'] ['tmp_name'] ))) {
				$file = $this->request->files ['upload'] ['tmp_name'];
				if ($this->model_sale_order->upload ( $file )) {
					$this->session->data ['success'] = '导入成功';
					$this->redirect ( $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
				} else {
					$this->error ['warning'] = '导入失败,请重试';
				}
			}
		}
		
		$this->getList ();
	}
	public function export() {
		$this->load->language ( 'sale/order' );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->load->model ( 'sale/order' );
		
		if (isset ( $this->request->get ['filter_order_id'] )) {
			$filter_order_id = $this->request->get ['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		
		if (isset ( $this->request->get ['filter_customer'] )) {
			$filter_customer = $this->request->get ['filter_customer'];
		} else {
			$filter_customer = null;
		}
		
		if (isset ( $this->request->get ['filter_order_status_id'] )) {
			$filter_order_status_id = $this->request->get ['filter_order_status_id'];
		} else {
			$filter_order_status_id = null;
		}
		
		if (isset ( $this->request->get ['filter_total'] )) {
			$filter_total = $this->request->get ['filter_total'];
		} else {
			$filter_total = null;
		}
		
		if (isset ( $this->request->get ['filter_date_added'] )) {
			$filter_date_added = $this->request->get ['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		
		if (isset ( $this->request->get ['filter_date_pick'] )) {
			$filter_date_pick = $this->request->get ['filter_date_pick'];
		} else {
			$filter_date_pick = null;
		}
		if (isset ( $this->request->get ['filter_partner_code'] )) {
			$filter_partner_code = $this->request->get ['filter_partner_code'];
		} else {
			$filter_partner_code = null;
		}
		
		if (isset ( $this->request->get ['filter_source_from'] )) {
			$filter_source_from = $this->request->get ['filter_source_from'];
		} else {
			$filter_source_from = null;
		}
		
		if (isset ( $this->request->get ['filter_point_id'] )) {
			$filter_point_id = $this->request->get ['filter_point_id'];
		} else {
			$filter_point_id = null;
		}
		if (isset ( $this->request->get ['filter_point_name'] )) {
			$filter_point_name = $this->request->get ['filter_point_name'];
		} else {
			$filter_point_name = null;
		}
		
		$data = array (
				'filter_order_id' => $filter_order_id,
				'filter_customer' => $filter_customer,
				'filter_customer_phone' => $filter_customer_phone,
				'filter_order_status_id' => $filter_order_status_id,
				'filter_total' => $filter_total,
				'filter_date_added' => $filter_date_added,
				'filter_date_pick' => $filter_date_pick,
				'filter_partner_code' => $filter_partner_code,
				'filter_source_from' => $filter_source_from,
				'filter_point_name' => $filter_point_name,
				'filter_point_id' => $filter_point_id 
		);
		
		$results = $this->model_sale_order->getExOrders ( $data );
		
		if ($results) {
			$selectid = '';
			foreach ( $results as $result ) {
				$selectid .= $result ['order_id'] . ',';
			}
			
			$this->model_sale_order->exportOrder ( substr ( $selectid, 0, strlen ( $selectid ) - 1 ) );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$url = '';
			
			if (isset ( $this->request->get ['filter_order_id'] )) {
				$url .= '&filter_order_id=' . $this->request->get ['filter_order_id'];
			}
			
			if (isset ( $this->request->get ['filter_customer'] )) {
				$url .= '&filter_customer=' . $this->request->get ['filter_customer'];
			}
			
			if (isset ( $this->request->get ['filter_order_status_id'] )) {
				$url .= '&filter_order_status_id=' . $this->request->get ['filter_order_status_id'];
			}
			
			if (isset ( $this->request->get ['filter_total'] )) {
				$url .= '&filter_total=' . $this->request->get ['filter_total'];
			}
			
			if (isset ( $this->request->get ['filter_date_added'] )) {
				$url .= '&filter_date_added=' . $this->request->get ['filter_date_added'];
			}
			
			if (isset ( $this->request->get ['filter_date_pick'] )) {
				$url .= '&filter_date_pick=' . $this->request->get ['filter_date_pick'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$url .= '&page=' . $this->request->get ['page'];
			}
			
			$this->redirect ( $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
		}
		
		$this->getList ();
	}
	public function getForm() {
		$this->data ['token'] = $this->session->data ['token'];

		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['firstname'] )) {
			$this->data ['error_firstname'] = $this->error ['firstname'];
		} else {
			$this->data ['error_firstname'] = '';
		}

		if (isset ( $this->error ['order_product'] )) {
			$this->data ['error_order_product'] = $this->error ['order_product'];
		} else {
			$this->data ['error_order_product'] = '';
		}
		
		if (isset ( $this->error ['lastname'] )) {
			$this->data ['error_lastname'] = $this->error ['lastname'];
		} else {
			$this->data ['error_lastname'] = '';
		}
		
		if (isset ( $this->error ['email'] )) {
			$this->data ['error_email'] = $this->error ['email'];
		} else {
			$this->data ['error_email'] = '';
		}
		
		if (isset ( $this->error ['telephone'] )) {
			$this->data ['error_telephone'] = $this->error ['telephone'];
		} else {
			$this->data ['error_telephone'] = '';
		}
		
		if (isset ( $this->error ['shipping_firstname'] )) {
			$this->data ['error_shipping_firstname'] = $this->error ['shipping_firstname'];
		} else {
			$this->data ['error_shipping_firstname'] = '';
		}
		
		if (isset ( $this->error ['shipping_lastname'] )) {
			$this->data ['error_shipping_lastname'] = $this->error ['shipping_lastname'];
		} else {
			$this->data ['error_shipping_lastname'] = '';
		}
		
		
		if (isset ( $this->error ['shipping_mobile'] )) {
			$this->data ['error_shipping_mobile'] = $this->error ['shipping_mobile'];
		} else {
			$this->data ['error_shipping_mobile'] = '';
		}
		
		if (isset ( $this->error ['shipping_address_1'] )) {
			$this->data ['error_shipping_address_1'] = $this->error ['shipping_address_1'];
		} else {
			$this->data ['error_shipping_address_1'] = '';
		}
		
		if (isset ( $this->error ['shipping_address_2'] )) {
			$this->data ['error_shipping_address_2'] = $this->error ['shipping_address_2'];
		} else {
			$this->data ['error_shipping_address_2'] = '';
		}
		
		if (isset ( $this->error ['shipping_city'] )) {
			$this->data ['error_shipping_city'] = $this->error ['shipping_city'];
		} else {
			$this->data ['error_shipping_city'] = '';
		}
		
		if (isset ( $this->error ['shipping_postcode'] )) {
			$this->data ['error_shipping_postcode'] = $this->error ['shipping_postcode'];
		} else {
			$this->data ['error_shipping_postcode'] = '';
		}
		
		if (isset ( $this->error ['shipping_country'] )) {
			$this->data ['error_shipping_country'] = $this->error ['shipping_country'];
		} else {
			$this->data ['error_shipping_country'] = '';
		}
		
		if (isset ( $this->error ['shipping_zone'] )) {
			$this->data ['error_shipping_zone'] = $this->error ['shipping_zone'];
		} else {
			$this->data ['error_shipping_zone'] = '';
		}
		
		if (isset ( $this->error ['payment_firstname'] )) {
			$this->data ['error_payment_firstname'] = $this->error ['payment_firstname'];
		} else {
			$this->data ['error_payment_firstname'] = '';
		}
		
		if (isset ( $this->error ['payment_lastname'] )) {
			$this->data ['error_payment_lastname'] = $this->error ['payment_lastname'];
		} else {
			$this->data ['error_payment_lastname'] = '';
		}
		
		if (isset ( $this->error ['payment_address_1'] )) {
			$this->data ['error_payment_address_1'] = $this->error ['payment_address_1'];
		} else {
			$this->data ['error_payment_address_1'] = '';
		}
		
		if (isset ( $this->error ['payment_city'] )) {
			$this->data ['error_payment_city'] = $this->error ['payment_city'];
		} else {
			$this->data ['error_payment_city'] = '';
		}
		
		if (isset ( $this->error ['payment_postcode'] )) {
			$this->data ['error_payment_postcode'] = $this->error ['payment_postcode'];
		} else {
			$this->data ['error_payment_postcode'] = '';
		}
		
		if (isset ( $this->error ['payment_country'] )) {
			$this->data ['error_payment_country'] = $this->error ['payment_country'];
		} else {
			$this->data ['error_payment_country'] = '';
		}
		
		if (isset ( $this->error ['payment_zone'] )) {
			$this->data ['error_payment_zone'] = $this->error ['payment_zone'];
		} else {
			$this->data ['error_payment_zone'] = '';
		}
		
		if (isset ( $this->error ['shipping_date'] )) {
			$this->data ['error_shipping_date'] = $this->error ['shipping_date'];
		} else {
			$this->data ['error_shipping_date'] = '';
		}
		if (isset ( $this->error ['pdate'] )) {
			$this->data ['error_pdate'] = $this->error ['pdate'];
		} else {
			$this->data ['error_pdate'] = '';
		}
		if (isset ( $this->error ['style'] )) {
			$this->data ['error_style'] = $this->error ['style'];
		} else {
			$this->data ['error_style'] = '';
		}
		
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_order_id'] )) {
			$url .= '&filter_order_id=' . $this->request->get ['filter_order_id'];
		}
		
		if (isset ( $this->request->get ['filter_customer'] )) {
			$url .= '&filter_customer=' . $this->request->get ['filter_customer'];
		}
		
		if (isset ( $this->request->get ['filter_order_status_id'] )) {
			$url .= '&filter_order_status_id=' . $this->request->get ['filter_order_status_id'];
		}
		
		if (isset ( $this->request->get ['filter_total'] )) {
			$url .= '&filter_total=' . $this->request->get ['filter_total'];
		}
		
		if (isset ( $this->request->get ['filter_date_added'] )) {
			$url .= '&filter_date_added=' . $this->request->get ['filter_date_added'];
		}
		
		if (isset ( $this->request->get ['filter_date_pick'] )) {
			$url .= '&filter_date_pick=' . $this->request->get ['filter_date_pick'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$this->data ['breadcrumbs'] = array ();
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => false 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => $this->language->get ( 'text_breadcrumb_separator' ) 
		);
		
		/*

		if (! isset ( $this->request->get ['order_id'] )) {
			$this->data ['action'] = $this->url->link ( 'sale/order/insert', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		} else {
			if($id1==1){
				//调用自己写的方法
				$this->aa;
			} else {
				$this->data ['action'] = $this->url->link ( 'sale/order/update', 'token=' . $this->session->data ['token'] . '&order_id=' . $this->request->get ['order_id'] . $url, 'SSL' );
			}
		}*/
        if ( $this->data ['operation'] == EnumOperation::INSERT) {

			$this->data ['action'] = $this->url->link ( 'sale/order/insert', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
			
		} else {
			
				$this->data ['action'] = $this->url->link ( 'sale/order/update', 'token=' . $this->session->data ['token'] . '&order_id=' . $this->request->get ['order_id'] . $url, 'SSL' );
		}

		
		$this->data ['cancel'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		
		if (isset ( $this->request->get ['order_id'] )) { // && ($this->request->server['REQUEST_METHOD'] != 'POST')
			$order_info = $this->model_sale_order->getOrder ( $this->request->get ['order_id'] );
			
			if ( $this->data ['operation'] == EnumOperation::INSERT) {
				
				unset($order_info['pdate']);
				unset($order_info['shipping_time']);
				
			}
		}
		
		if (isset ( $this->request->post ['store_id'] )) {
			$this->data ['store_id'] = $this->request->post ['store_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['store_id'] = $order_info ['store_id'];
		} else {
			$this->data ['store_id'] = '';
		}
		
		$this->load->model ( 'setting/store' );
		
		$this->data ['stores'] = $this->model_setting_store->getStores ();
		
		$this->data ['store_url'] = HTTP_CATALOG;
		
		if (isset ( $this->request->post ['customer_id'] )) {
			$this->data ['customer_id'] = $this->request->post ['customer_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['customer_id'] = $order_info ['customer_id'];
		} else {
			$this->data ['customer_id'] = '';
		}
		
		if($this->data ['customer_id']){
			$this->load->model('sale/customer');
			$this->data['customer_addresses'] = $this->model_sale_customer->getAddresses($this->data ['customer_id']);
		}
		
		if (isset ( $this->request->post ['customer'] )) {
			$this->data ['customer'] = $this->request->post ['customer'];
		} elseif (isset ( $order_info )) {
			$this->data ['customer'] = $order_info ['customer'];
		} else {
			$this->data ['customer'] = '';
		}
		
		if (isset ( $this->request->post ['firstname'] )) {
			$this->data ['firstname'] = $this->request->post ['firstname'];
		} elseif (isset ( $order_info )) {
			$this->data ['firstname'] = $order_info ['firstname'];
		} else {
			$this->data ['firstname'] = '';
		}
		
		$this->data ['lastname'] = '';
		
		if (isset ( $this->request->post ['email'] )) {
			$this->data ['email'] = $this->request->post ['email'];
		} elseif (isset ( $order_info )) {
			$this->data ['email'] = $order_info ['email'];
		} else {
			$this->data ['email'] = '';
		}
		
		if (isset ( $this->request->post ['telephone'] )) {
			$this->data ['telephone'] = $this->request->post ['telephone'];
		} elseif (isset ( $order_info )) {
			$this->data ['telephone'] = $order_info ['telephone'];
		} else {
			$this->data ['telephone'] = '';
		}
		
		if (isset ( $this->request->post ['fax'] )) {
			$this->data ['fax'] = $this->request->post ['fax'];
		} elseif (isset ( $order_info )) {
			$this->data ['fax'] = $order_info ['fax'];
		} else {
			$this->data ['fax'] = '';
		}
		
		$this->load->model ( 'sale/customer' );
		
		if (isset ( $this->request->post ['customer_id'] )) {
			$this->data ['addresses'] = $this->model_sale_customer->getAddresses ( $this->request->post ['customer_id'] );
		} elseif (isset ( $order_info )) {
			$this->data ['addresses'] = $this->model_sale_customer->getAddresses ( $order_info ['customer_id'] );
		} else {
			$this->data ['addresses'] = array ();
		}
		
		if (isset ( $this->request->post ['shipping_firstname'] )) {
			$this->data ['shipping_firstname'] = $this->request->post ['shipping_firstname'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_firstname'] = $order_info ['shipping_firstname'];
		} else {
			$this->data ['shipping_firstname'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_lastname'] )) {
			$this->data ['shipping_lastname'] = $this->request->post ['shipping_lastname'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_lastname'] = $order_info ['shipping_lastname'];
		} else {
			$this->data ['shipping_lastname'] = '';
		}
		if (isset ( $this->request->post ['shipping_mobile'] )) {
			$this->data ['shipping_mobile'] = $this->request->post ['shipping_mobile'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_mobile'] = $order_info ['shipping_mobile'];
		} else {
			$this->data ['shipping_mobile'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_company'] )) {
			$this->data ['shipping_company'] = $this->request->post ['shipping_company'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_company'] = $order_info ['shipping_company'];
		} else {
			$this->data ['shipping_company'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_address_1'] )) {
			$this->data ['shipping_address_1'] = $this->request->post ['shipping_address_1'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_address_1'] = $order_info ['shipping_address_1'];
		} else {
			$this->data ['shipping_address_1'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_address_2'] )) {
			$this->data ['shipping_address_2'] = $this->request->post ['shipping_address_2'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_address_2'] = $order_info ['shipping_address_2'];
		} else {
			$this->data ['shipping_address_2'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_city'] )) {
			$this->data ['shipping_city'] = $this->request->post ['shipping_city'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_city'] = $order_info ['shipping_city'];
		} else {
			$this->data ['shipping_city'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_postcode'] )) {
			$this->data ['shipping_postcode'] = $this->request->post ['shipping_postcode'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_postcode'] = $order_info ['shipping_postcode'];
		} else {
			$this->data ['shipping_postcode'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_country_id'] )) {
			$this->data ['shipping_country_id'] = $this->request->post ['shipping_country_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_country_id'] = $order_info ['shipping_country_id'];
		} else {
			$this->data ['shipping_country_id'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_zone_id'] )) {
			$this->data ['shipping_zone_id'] = $this->request->post ['shipping_zone_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_zone_id'] = $order_info ['shipping_zone_id'];
		} else {
			$this->data ['shipping_zone_id'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_method'] )) {
			$this->data ['shipping_method'] = $this->request->post ['shipping_method'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_method'] = $order_info ['shipping_method'];
		} else {
			$this->data ['shipping_method'] = '';
		}
		if (isset ( $this->request->post ['shipping_date'] )) {
			$this->data ['shipping_date'] = $this->request->post ['shipping_date'];
		} elseif (isset ( $order_info ) && $order_info ['shipping_point_id'] == '0') {
			$this->data ['shipping_date'] = date ( 'Y-m-d', strtotime ( $order_info ['shipping_time'] ) );
		} else {
			$this->data ['shipping_date'] = '';
		}
		if (isset ( $this->request->post ['shipping_time'] )) {
			$this->data ['shipping_time'] = $this->request->post ['shipping_time'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_time'] = date ( 'H:s', strtotime ( $order_info ['shipping_time'] ) );
		} else {
			$this->data ['shipping_time'] = '';
		}
		
		if (isset ( $this->request->post ['tp_order_id'] )) {
			$this->data ['tp_order_id'] = $this->request->post ['tp_order_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['tp_order_id'] = $order_info ['tp_order_id'];
		} else {
			$this->data ['tp_order_id'] = '';
		}
		
		if (isset ( $this->request->post ['pdate'] )) {
			$this->data ['pdate'] = $this->request->post ['pdate'];
		} elseif (isset ( $order_info )) {
			$this->data ['pdate'] = $order_info ['pdate'];
		} else {
			$this->data ['pdate'] = '';
		}
		if (isset ( $this->request->post ['shipping_point_id'] )) {
			$this->data ['shipping_point_id'] = $this->request->post ['shipping_point_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_point_id'] = $order_info ['shipping_point_id'];
		} else {
			$this->data ['shipping_point_id'] = '';
		}
		
		if (isset ( $this->request->post ['shipping_data'] )) {
			$this->data ['shipping_data'] = $this->request->post ['shipping_data'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_data'] = $order_info ['shipping_data'];
		} else {
			$this->data ['shipping_data'] = '';
		}
		if (isset ( $this->request->post ['shipping_code'] )) {
			$this->data ['shipping_code'] = $this->request->post ['shipping_code'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_code'] = $order_info ['shipping_code'];
		} else {
			$this->data ['shipping_code'] = '';
		}
		if (isset ( $this->request->post ['shipping_poi'] )) {
			$this->data ['shipping_poi'] = $this->request->post ['shipping_poi'];
		} elseif (isset ( $order_info )) {
			$this->data ['shipping_poi'] = $order_info ['poi'];
		} else {
			$this->data ['shipping_poi'] = '';
		}
		if (isset ( $this->request->post ['payment_firstname'] )) {
			$this->data ['payment_firstname'] = $this->request->post ['payment_firstname'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_firstname'] = $order_info ['payment_firstname'];
		} else {
			$this->data ['payment_firstname'] = '';
		}
		
		if (isset ( $this->request->post ['payment_lastname'] )) {
			$this->data ['payment_lastname'] = $this->request->post ['payment_lastname'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_lastname'] = $order_info ['payment_lastname'];
		} else {
			$this->data ['payment_lastname'] = '';
		}
		
		if (isset ( $this->request->post ['payment_company'] )) {
			$this->data ['payment_company'] = $this->request->post ['payment_company'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_company'] = $order_info ['payment_company'];
		} else {
			$this->data ['payment_company'] = '';
		}
		
		if (isset ( $this->request->post ['payment_address_1'] )) {
			$this->data ['payment_address_1'] = $this->request->post ['payment_address_1'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_address_1'] = $order_info ['payment_address_1'];
		} else {
			$this->data ['payment_address_1'] = '';
		}
		
		if (isset ( $this->request->post ['payment_address_2'] )) {
			$this->data ['payment_address_2'] = $this->request->post ['payment_address_2'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_address_2'] = $order_info ['payment_address_2'];
		} else {
			$this->data ['payment_address_2'] = '';
		}
		
		if (isset ( $this->request->post ['payment_city'] )) {
			$this->data ['payment_city'] = $this->request->post ['payment_city'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_city'] = $order_info ['payment_city'];
		} else {
			$this->data ['payment_city'] = '';
		}
		
		if (isset ( $this->request->post ['payment_postcode'] )) {
			$this->data ['payment_postcode'] = $this->request->post ['payment_postcode'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_postcode'] = $order_info ['payment_postcode'];
		} else {
			$this->data ['payment_postcode'] = '';
		}
		
		if (isset ( $this->request->post ['payment_country_id'] )) {
			$this->data ['payment_country_id'] = $this->request->post ['payment_country_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_country_id'] = $order_info ['payment_country_id'];
		} else {
			$this->data ['payment_country_id'] = '';
		}
		
		if (isset ( $this->request->post ['payment_zone_id'] )) {
			$this->data ['payment_zone_id'] = $this->request->post ['payment_zone_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_zone_id'] = $order_info ['payment_zone_id'];
		} else {
			$this->data ['payment_zone_id'] = '';
		}
		
		$this->load->model ( 'localisation/country' );
		
		$this->data ['countries'] = $this->model_localisation_country->getCountries ();
		
		if (isset ( $this->request->post ['payment_method'] )) {
			$this->data ['payment_method'] = $this->request->post ['payment_method'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_method'] = $order_info ['payment_method'];
		} else {
			$this->data ['payment_method'] = '';
		}
		
		if (isset ( $this->request->post ['affiliate_id'] )) {
			$this->data ['affiliate_id'] = $this->request->post ['affiliate_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['affiliate_id'] = $order_info ['affiliate_id'];
		} else {
			$this->data ['affiliate_id'] = '';
		}
		
		if (isset ( $this->request->post ['affiliate'] )) {
			$this->data ['affiliate'] = $this->request->post ['affiliate'];
		} elseif (isset ( $order_info )) {
			$this->data ['affiliate'] = $order_info ['affiliate_firstname'] . '' . $order_info ['affiliate_lastname'];
		} else {
			$this->data ['affiliate'] = '';
		}
		
		if (isset ( $this->request->post ['order_status_id'] )) {
			$this->data ['order_status_id'] = $this->request->post ['order_status_id'];
		} elseif (isset ( $order_info )) {
			$this->data ['order_status_id'] = $order_info ['order_status_id'];
		} else {
			$this->data ['order_status_id'] = '';
		}
		
		if (isset ( $this->request->post ['payment_method'] )) {
			$this->data ['payment_method'] = $this->request->post ['payment_method'];
		} elseif (isset ( $order_info )) {
			$this->data ['payment_method'] = $order_info ['payment_method'];
		} else {
			$this->data ['payment_method'] = '';
		}
		
		if (isset ( $this->request->post ['partner_code'] )) {
			$this->data ['partner_code'] = $this->request->post ['partner_code'];
		} elseif (isset ( $order_info )) {
			$this->data ['partner_code'] = $order_info ['partner_code'];
		} else {
			$this->data ['partner_code'] = '';
		}
		
		$this->load->model ( 'localisation/order_status' );
		
		$this->data ['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses ();
		
		
		$order_status_info = $this->model_localisation_order_status->getOrderStatus ( $order_info ['order_status_id'] );
			
		if ($order_status_info) {
			$this->data ['order_status_info'] = $order_status_info;
		} else {
			$this->data ['order_status_info'] = '';
		}
		
		if (isset ( $this->request->post ['comment'] )) {
			$this->data ['order_comment'] = $this->request->post ['comment'];
		} elseif (isset ( $order_info )) {
			$this->data ['order_comment'] = $order_info ['comment'];
		} else {
			$this->data ['order_comment'] = '';
		}
		
		if (isset ( $this->request->post ['order_product'] )) {
			$order_products = $this->request->post ['order_product'];
		} elseif (isset ( $order_info )) {
			$order_products = $this->model_sale_order->getOrderProducts ( $this->request->get ['order_id'] );
		} else {
			$order_products = array ();
		}
		
		
		$this->load->model ( 'catalog/product' );
		
		$this->data ['order_products'] = array ();
		
		foreach ( $order_products as $order_product ) {
			$product_info = $this->model_catalog_product->getProduct ( $order_product ['product_id'] );
			if ($product_info) {
				$option_data = array ();
				
				// $this->data['order_products'][] = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product_option['option_id']);
				
				$product_options = $this->model_catalog_product->getProductOptions ( $order_product ['product_id'] );
				
				foreach ( $product_options as $product_option ) {
					if ($product_option ['type'] == 'select' || $product_option ['type'] == 'radio' || $product_option ['type'] == 'checkbox') {
						$option_value_data = array ();
						
						foreach ( $product_option ['product_option_value'] as $product_option_value ) {
							$option_value_data [] = array (
									'product_option_value_id' => $product_option_value ['product_option_value_id'],
									'option_value_id' => $product_option_value ['option_value_id'],
									'name' => $product_option_value ['name'],
									'price' => ( float ) $product_option_value ['price'] ? $this->currency->format ( $product_option_value ['price'], $this->config->get ( 'config_currency' ) ) : false,
									'price_prefix' => $product_option_value ['price_prefix'] 
							);
						}
						
						$option_data [] = array (
								'product_option_id' => $product_option ['product_option_id'],
								'option_id' => $product_option ['option_id'],
								'name' => $product_option ['name'],
								'type' => $product_option ['type'],
								'option_value' => $option_value_data,
								'required' => $product_option ['required'] 
						);
					} else {
						$option_data [] = array (
								'product_option_id' => $product_option ['product_option_id'],
								'option_id' => $product_option ['option_id'],
								'name' => $product_option ['name'],
								'type' => $product_option ['type'],
								'option_value' => $product_option ['option_value'],
								'required' => $product_option ['required'] 
						);
					}
				}
				
				$this->data ['order_products'] [] = array (
						'order_product_id' => $order_product ['order_product_id'],
						'order_id' => $order_product ['order_id'],
						'product_id' => $product_info ['product_id'],
						'name' => $product_info ['name'],
						'model' => $product_info ['model'],
						'combine' => $product_info ['combine'],
						'option' => $option_data,
						'quantity' => $order_product ['quantity'],
						'price' => $order_product ['price'],
						'promotion_code' => $order_product ['promotion_code'],
						'promotion_price' => $order_product ['promotion_price'],
						'total' => $order_product ['total'],
						'tax' => $order_product ['tax'] 
				);
			}
		}

		
		/*
		 * if (isset($this->request->post['order_total'])) {
		 * $this->data['order_totals'] = $this->request->post['order_total'];
		 * } elseif (isset($order_info)) {
		 * $this->data['order_totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
		 * } else {
		 * $this->data['order_totals'] = array();
		 * }
		 */
		$order_info = $this->model_sale_order->getOrder ( $this->request->get ['order_id'] );
		if ($order_info) {
			$this->data ['order_total'] = $order_info ['total'];
		} else {
			$this->date ['order_total'] = 0;
		}
		//获取第三方平台列表
		$this->load->model('catalog/partnercode');
		$this->data['partners'] = $this->model_catalog_partnercode->getAllPartners();
		
		
		$this->data ['shistories'] = array ();
		
		$results = $this->model_sale_order->getOrderShistories ( $this->request->get ['order_id']);
		
		foreach ( $results as $result ) {
			$this->data ['shistories'] [] = array (
					'comment' => $result ['comment'],
					'date_added' => date ( $this->language->get ( 'date_format_short' ) . ' H:i:s', strtotime ( $result ['date_added'] ) ),
					'operator' => $result ['operator'] 
			);
		}

		$this->template = 'sale/order_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render ();
	}
	
	/**
	 * 订单查询
	 */
	public function info() {
		$this->load->model ( 'sale/order' );
		$this->load->model ( 'sale/order_refund' );

		if (isset ( $this->request->get ['order_id'] )) {
			$order_id = $this->request->get ['order_id'];
		} else {
			$order_id = 0;
		}
		
		$order_info = $this->model_sale_order->getOrder ( $order_id );
		
		if ($order_info) {
			$this->load_language ( 'sale/order' );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->data ['token'] = $this->session->data ['token'];
			
			$url = '';
			
			if (isset ( $this->request->get ['filter_order_id'] )) {
				$url .= '&filter_order_id=' . $this->request->get ['filter_order_id'];
			}
			
			if (isset ( $this->request->get ['filter_customer'] )) {
				$url .= '&filter_customer=' . $this->request->get ['filter_customer'];
			}
			
			if (isset ( $this->request->get ['filter_order_status_id'] )) {
				$url .= '&filter_order_status_id=' . $this->request->get ['filter_order_status_id'];
			}
			
			if (isset ( $this->request->get ['filter_total'] )) {
				$url .= '&filter_total=' . $this->request->get ['filter_total'];
			}
			
			if (isset ( $this->request->get ['filter_date_added'] )) {
				$url .= '&filter_date_added=' . $this->request->get ['filter_date_added'];
			}
			
			if (isset ( $this->request->get ['filter_date_pick'] )) {
				$url .= '&filter_date_pick=' . $this->request->get ['filter_date_pick'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$url .= '&page=' . $this->request->get ['page'];
			}
			
			$this->data ['breadcrumbs'] = array ();
			
			$this->data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_home' ),
					'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
					'separator' => false 
			);
			
			$this->data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'heading_title' ),
					'href' => $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'], 'SSL' ),
					'separator' => $this->language->get ( 'text_breadcrumb_separator' ) 
			);
			if ( $this->data ['operation'] == EnumOperation::INSERT) {

			$this->data ['action'] = $this->url->link ( 'sale/order/insert', 'token=' . $this->session->data ['token'] . '&order_id=' . $order_id . $url, 'SSL' );

			}

			$this->data ['invoice'] = $this->url->link ( 'sale/order/invoice', 'token=' . $this->session->data ['token'] . '&order_id=' . $order_id, 'SSL' );
			$this->data ['cancel'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
			$en = $order_info ['shipping_point_id'] ? $order_info['pdate'] : $order_info['shipping_time'];
			$sa = strtotime($en);
			$da = strtotime(date('Y-m-d',strtotime('+1 day')));
			$da = strtotime(date('Y-m-d'));

			if($order_info ['order_status_id']=='2'&&$sa >= $da){
				$this->data ['update'] = $this->url->link ( 'sale/order/update', 'token=' . $this->session->data ['token'] . '&order_id=' . $order_id . $url, 'SSL' );
			 }else{
			    $this->data ['update'] = '';
			}
		
			
			$this->data ['order_id'] = $this->request->get ['order_id'];
			$this->data ['invoice_no'] = $order_info ['invoice_prefix'] . $order_info ['invoice_no'];
			$this->data ['store_name'] = $order_info ['store_name'];
			$this->data ['store_url'] = $order_info ['store_url'];
			
			if ($order_info ['firstname'] == '')
				$this->data ['firstname'] = $order_info ['email'];
			else
				$this->data ['firstname'] = $order_info ['firstname'];
			
			$this->data ['lastname'] = $order_info ['lastname'];
			
			if ($order_info ['customer_id']) {
				$this->data ['customer'] = $this->url->link ( 'sale/customer/update', 'token=' . $this->session->data ['token'] . '&customer_id=' . $order_info ['customer_id'], 'SSL' );
			} else {
				$this->data ['customer'] = '';
			}
			
			$this->load->model ( 'sale/customer_group' );
			
			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup ( $order_info ['customer_group_id'] );
			
			if ($customer_group_info) {
				$this->data ['customer_group'] = $customer_group_info ['name'];
			} else {
				$this->data ['customer_group'] = '';
			}
			
			$this->data ['email'] = $order_info ['email'];
			$this->data ['pdate'] = $order_info ['pdate'];
			$this->data ['shipping_time'] = $order_info ['shipping_time'];
			$this->data ['shipping_code'] = $order_info ['shipping_code'];
			$this->data ['shipping_data'] = $order_info ['shipping_data'];
			$this->data ['tp_order_id'] = $order_info ['tp_order_id'];
			$this->data ['sp_order_id'] = $order_info ['sp_order_id'];
			$this->data ['shipping_point_id'] = $order_info ['shipping_point_id'];
			$this->data ['p_order_id'] = $order_info ['p_order_id'];
			
			$this->data ['ip'] = $order_info ['ip'];
			$this->data ['telephone'] = $order_info ['telephone'];
			$this->data ['fax'] = $order_info ['fax'];
			$this->data ['comment'] = nl2br ( $order_info ['comment'] );
			$this->data ['shipping_method'] = $order_info ['shipping_method'];
			$this->data ['payment_method'] = $order_info ['payment_method'];
			$this->data ['total'] = $this->currency->format ( $order_info ['total'], $order_info ['currency_code'], $order_info ['currency_value'] );
			$this->data ['reward'] = $order_info ['reward'];
			
			$this->data['type_arr'] = $this->order_type_arr;
			$this->data ['order_type'] = $order_info ['order_type'];
			$this->data ['addition_info'] = $order_info ['addition_info'];
			
			if ($order_info ['certification']) {
				$this->data ['certification'] = HTTP_CATALOG . 'download/order-certification/' . $this->encryption->decrypt ( $order_info ['certification'] );
			} else {
				$this->data ['certification'] = NULL;
			}
			
			if ($order_info ['total'] < 0) {
				$this->data ['credit'] = $order_info ['total'];
			} else {
				$this->data ['credit'] = 0;
			}
			
			$this->load->model ( 'sale/customer' );
			
			$this->data ['credit_total'] = $this->model_sale_customer->getTotalCustomerTransactionsByOrderId ( $this->request->get ['order_id'] );
			
			$this->data ['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId ( $this->request->get ['order_id'] );
			
			$this->data ['affiliate_firstname'] = $order_info ['affiliate_firstname'];
			$this->data ['affiliate_lastname'] = $order_info ['affiliate_lastname'];
			
			if ($order_info ['affiliate_id']) {
				$this->data ['affiliate'] = $this->url->link ( 'sale/affliate/update', 'token=' . $this->session->data ['token'] . '&affiliate_id=' . $order_info ['affiliate_id'], 'SSL' );
			} else {
				$this->data ['affiliate'] = '';
			}
			
			$this->data ['commission'] = $this->currency->format ( $order_info ['commission'], $order_info ['currency_code'], $order_info ['currency_value'] );
			
			$this->load->model ( 'sale/affiliate' );
			
			$this->data ['commission_total'] = $this->model_sale_affiliate->getTotalTransactionsByOrderId ( $this->request->get ['order_id'] );
			
			$this->load->model ( 'localisation/order_status' );
			
			$order_status_info = $this->model_localisation_order_status->getOrderStatus ( $order_info ['order_status_id'] );
			
			if ($order_status_info) {
				$this->data ['order_status'] = $order_status_info ['name'];
			} else {
				$this->data ['order_status'] = '';
			}
			
			$this->data ['date_added'] = date ( $this->language->get ( 'date_format_short' ), strtotime ( $order_info ['date_added'] ) );
			$this->data ['pdate'] = date ( $this->language->get ( 'date_format_short' ), strtotime ( $order_info ['pdate'] ) );
			
			$this->data ['payment_firstname'] = $order_info ['payment_firstname'];
			$this->data ['payment_lastname'] = $order_info ['payment_lastname'];
			$this->data ['payment_company'] = $order_info ['payment_company'];
			$this->data ['payment_address_1'] = $order_info ['payment_address_1'];
			$this->data ['payment_address_2'] = $order_info ['payment_address_2'];
			$this->data ['payment_city'] = $order_info ['payment_city'];
			$this->data ['payment_postcode'] = $order_info ['payment_postcode'];
			$this->data ['payment_zone'] = $order_info ['payment_zone'];
			$this->data ['payment_zone_code'] = $order_info ['payment_zone_code'];
			$this->data ['payment_country'] = $order_info ['payment_country'];
			$this->data ['shipping_firstname'] = $order_info ['shipping_firstname'];
			$this->data ['shipping_lastname'] = $order_info ['shipping_lastname'];
			$this->data ['shipping_mobile'] = $order_info ['shipping_mobile'];
			$this->data ['shipping_phone'] = $order_info ['shipping_phone'];
			$this->data ['shipping_lastname'] = $order_info ['shipping_lastname'];
			$this->data ['shipping_company'] = $order_info ['shipping_company'];
			$this->data ['shipping_address_1'] = $order_info ['shipping_address_1'];
			$this->data ['shipping_address_2'] = $order_info ['shipping_address_2'];
			$this->data ['shipping_city'] = $order_info ['shipping_city'];
			$this->data ['shipping_postcode'] = $order_info ['shipping_postcode'];
			$this->data ['shipping_zone'] = $order_info ['shipping_zone'];
			$this->data ['shipping_zone_code'] = $order_info ['shipping_zone_code'];
			$this->data ['shipping_country'] = $order_info ['shipping_country'];
			
			$this->data ['products'] = array ();
			
			$products = $this->model_sale_order->getOrderProducts ( $this->request->get ['order_id'] );
			
			foreach ( $products as $product ) {
				$option_data = array ();
				
				$options = $this->model_sale_order->getOrderOptions ( $this->request->get ['order_id'], $product ['order_product_id'] );
				
				foreach ( $options as $option ) {
					if ($option ['type'] != 'file') {
						$option_data [] = array (
								'name' => $option ['name'],
								'value' => $option ['value'],
								'type' => $option ['type'] 
						);
					} else {
						$option_data [] = array (
								'name' => $option ['name'],
								'value' => substr ( $option ['value'], 0, strrpos ( $option ['value'], '.' ) ),
								'type' => $option ['type'],
								'href' => $this->url->link ( 'sale/order/download', 'token=' . $this->session->data ['token'] . '&order_id=' . $this->request->get ['order_id'] . '&order_option_id=' . $option ['order_option_id'], 'SSL' ) 
						);
					}
				}
				
				$this->data ['products'] [] = array (
						'order_product_id' => $product ['order_product_id'],
						'product_id' => $product ['product_id'],
						'name' => $product ['name'],
						'model' => $product ['model'],
						'option' => $option_data,
						'quantity' => $product ['quantity'],
						'price' => $this->currency->format ( $product ['price'], $order_info ['currency_code'], $order_info ['currency_value'] ),
						'total' => $this->currency->format ( $product ['total'], $order_info ['currency_code'], $order_info ['currency_value'] ),
						'promotion_code' => $product ['promotion_code'],
						'promotion_price' => $this->currency->format ( $product ['promotion_price'], $order_info ['currency_code'], $order_info ['currency_value'] ),
						'href' => HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $product ['product_id'] 
				);
			}
			
			// if ($order_info['p_order_id'])
			// $this->data['totals'] = $this->model_sale_order->getOrderTotals($order_info['p_order_id']);
			// else
			$this->data ['totals'] = $this->model_sale_order->getOrderTotals ( $this->request->get ['order_id'] );
			
			$payments=array();
			$paymentsres = $this->model_sale_order->getOrderPayments( $this->request->get ['order_id'] );
           foreach ($paymentsres as $p)
           {
           	$payments[$p['order_payment_id']]=$p;
           }
           
          /*
			$this->data ['refunds'] = $this->model_sale_order_refund->getOrderRefunds ( array (
					"filter_order_id" => $this->request->get ['order_id'] 
			) );
			
			foreach ( $this->data ['refunds'] as $r ) {
				if ($r ['order_payment_id'] && !($r ['status'] == "PHASE1_REFUSED" || $r ['status'] == "PHASE2_REFUSED" || $r ['status']=="FAIL" )) {
					unset ( $payments [$r ['order_payment_id']] );
				}
			}
	*/
			$this->data ['payments'] = $payments;
			
			
			
		
			
			$this->data ['sub_orders'] = $this->model_sale_order->getSubOrders ( $this->request->get ['order_id'] );
			// if ($order_info['p_order_id']) {
			// $this->data['sub_orders'] = $this->model_sale_order->getSubOrders($order_info['p_order_id']);
			// } else {
			// $this->data['sub_orders'] = array();
			// }
			
			$this->load->model ( 'localisation/logistics' );
			
			$this->data ['expresses'] = $this->model_localisation_logistics->getLogisticses ();
			
			$this->data ['downloads'] = array ();
			
			$results = $this->model_sale_order->getOrderDownloads ( $this->request->get ['order_id'] );
			
			foreach ( $results as $result ) {
				$this->data ['downloads'] [] = array (
						'name' => $result ['name'],
						'filename' => $result ['mask'],
						'remaining' => $result ['remaining'] 
				);
			}
			
			$this->data ['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses ();
			
			$this->data ['order_status_id'] = $order_info ['order_status_id'];
			
			if ($order_info ['invoice_type']) {
				$this->data ['invoice_detail_status'] = true;
			} else {
				$this->data ['invoice_detail_status'] = false;
			}
			
			$this->data ['invoice_type'] = getInvoiceTypeDetail ( $order_info ['invoice_type'] );
			$this->data ['invoice_head'] = getInvoiceHeadDetail ( $order_info ['invoice_head'] );
			$this->data ['invoice_name'] = $order_info ['invoice_name'];
			$this->data ['invoice_content'] = getInvoiceContentDetail ( $order_info ['invoice_content'] );
			
			if ($order_info ['order_status_id'] == $this->config->get ( 'config_order_nopay_status_id' )) {
				$this->data ['discount_status'] = $this->checkOrderDiscountStatus ( $this->request->get ['order_id'] );
			} else {
				$this->data ['discount_status'] = TRUE;
			}
			
			$this->data ['pickup_code'] = $order_info ['pickup_code'];
			$this->data ['ref'] = $this->request->get ['ref']; // ref=purchase

			//获取第三方平台列表
			$this->load->model('catalog/partnercode');
			$this->data['partner'] = $this->model_catalog_partnercode->getPartnerInfo($order_info ['partner_code']);
			print_r($this->data['partners']);
			$this->template = 'sale/order_info.tpl';
			$this->id = 'content';
			$this->layout = 'layout/default';
			$this->render ();
		} else {
			$this->load_language ( 'error/not_found' );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->data ['heading_title'] = $this->language->get ( 'heading_title' );
			
			$this->data ['text_not_found'] = $this->language->get ( 'text_not_found' );
			
			$this->data ['breadcrumbs'] = array ();
			
			$this->data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_home' ),
					'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
					'separator' => false 
			);
			
			$this->data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'heading_title' ),
					'href' => $this->url->link ( 'error/not_found', 'token=' . $this->session->data ['token'], 'SSL' ),
					'separator' => $this->language->get ( 'text_breadcrumb_separator' ) 
			);
			
			$this->template = 'error/not_found.tpl';
			$this->id = 'content';
			$this->layout = 'layout/default';
			$this->render ();
		}
	}
	private function validateForm() {
		if (! $this->user->hasPermission ( 'modify', 'sale/order' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		//if ((strlen ( utf8_decode ( $this->request->post ['firstname'] ) ) < 1) || (strlen ( utf8_decode ( $this->request->post ['firstname'] ) ) > 32)) {
		//	$this->error ['firstname'] = $this->language->get ( 'error_firstname' );
		//}
		
		if ($this->request->post ['shipping_address_1'] && $this->request->is_address ( $this->request->post ['shipping_address_1'] ) == false) {
			$this->error ['shipping_address_1'] = '地址格式不正确，只能填写汉字字母下划线（）【】#';
		}
		if ($this->request->post ['shipping_address_2'] && $this->request->is_address ( $this->request->post ['shipping_address_2'] ) == false) {
			$this->error ['shipping_address_2'] = '地址格式不正确，只能填写汉字字母下划线（）【】#';
		}
		

		if (! $this->request->is_phone ( utf8_decode ( $this->request->post ['shipping_mobile'] ) )) {
			$this->error ['shipping_mobile'] = $this->language->get ( 'error_mobile' );
		}
		

		// TODO shipping method
		// if ((strlen(utf8_decode($this->request->post['shipping_city'])) < 1) || (strlen(utf8_decode($this->request->post['shipping_city'])) > 128)) {
		// $this->error['shipping_city'] = $this->language->get('error_city');
		// }
		
		$this->load->model ( 'localisation/country' );
		
		$country_info = $this->model_localisation_country->getCountry ( $this->request->post ['shipping_country_id'] );
		
		// if ((strlen(utf8_decode($this->request->post['payment_firstname'])) < 1) || (strlen(utf8_decode($this->request->post['payment_firstname'])) > 32)) {
		// $this->error['payment_firstname'] = $this->language->get('error_firstname');
		// }
		//
		// if ((strlen(utf8_decode($this->request->post['payment_lastname'])) < 1) || (strlen(utf8_decode($this->request->post['payment_lastname'])) > 32)) {
		// $this->error['payment_lastname'] = $this->language->get('error_lastname');
		// }
		//
		// if ((strlen(utf8_decode($this->request->post['payment_address_1'])) < 1) || (strlen(utf8_decode($this->request->post['payment_address_1'])) > 128)) {
		// $this->error['payment_address_1'] = $this->language->get('error_address_1');
		// }
		//
		// if ((strlen(utf8_decode($this->request->post['payment_city'])) < 1) || (strlen(utf8_decode($this->request->post['payment_city'])) > 128)) {
		// $this->error['payment_city'] = $this->language->get('error_city');
		// }
		//
		// $country_info = $this->model_localisation_country->getCountry($this->request->post['payment_country_id']);
		//
		// if ($country_info && $country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['payment_postcode'])) < 2) || (strlen(utf8_decode($this->request->post['payment_postcode'])) > 10)) {
		// $this->error['payment_postcode'] = $this->language->get('error_postcode');
		// }
		//
		// if ($this->request->post['payment_country_id'] == '') {
		// $this->error['payment_country'] = $this->language->get('error_country');
		// }
		//
		// if ($this->request->post['payment_zone_id'] == '') {
		// $this->error['payment_zone'] = $this->language->get('error_zone');
		// }
		
	
		$this->load->model ('catalog/product');

		if($this->request->post['stype']=='1'){

			if(!(isset($this->request->post['pdate'])&&!empty($this->request->post['pdate'])))
			{
				$this->error['pdate']='请选择自提时间';
					
			}
				
			//此处条件可以支持小时，当开启小时售卖预售时可以灵活修改
			if (date ( "Y-m-d", strtotime ( $this->request->post ['pdate'] ) )  <  date ( "Y-m-d",(time ()+86400))) {
					
				$this->error['pdate']='自提时间无效'.$this->request->post ['pdate'];
					
			}


		if(isset($this->request->post['order_product'])){

			foreach ($this->request->post['order_product'] as $order_product) {
			
				if(!$this->model_catalog_product->isProductInPeriod ( $order_product ['product_id'],-1, $this->request->post ['pdate']))
				{
		
                   $this->error['order_product'][$order_product['product_id']]='当前菜品不在周期内';
                }

			}
		
		}



		}
		elseif($this->request->post['stype']=='2'){
			
			if(!(isset($this->request->post['shipping_date'])&&!empty($this->request->post['shipping_date'])&&strtotime($this->request->post['shipping_date'])))
			{
				$this->error['shipping_date']='配送时间不能为空';
			
			}
			//此处条件可以支持小时，当开启小时售卖预售时可以灵活修改
			if (date ( "Y-m-d", strtotime ($this->request->post['shipping_date'] ) ) < (date("Y-m-d",time()+86400))) {
			
				$this->error['shipping_date']='配送时间无效'.$this->request->post ['shipping_date'];
			
			}

			if(isset($this->request->post['order_product'])){

			foreach ($this->request->post['order_product'] as $order_product) {
			
				if(!$this->model_catalog_product->isProductInPeriod ( $order_product['product_id'],-1, $this->request->post['shipping_date']))
				{
               		$this->error['order_product'][$order_product ['product_id']]='当前菜品不在周期内';

				}
			
				}

			}
		}
		else {
			$this->error['stype']='配送方式无效';
		}
		

		if ($this->error && ! isset ( $this->error ['warning'] )) {
			$this->error ['warning'] = $this->language->get ( 'error_warning' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateDelete() {
		if (! $this->user->hasPermission ( 'modify', 'sale/order' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function zone() {
		$output = '<option value="">' . $this->language->get ( 'text_select' ) . '</option>';
		
		$this->load->model ( 'localisation/zone' );
		
		$results = $this->model_localisation_zone->getZonesByCountryId ( $this->request->get ['country_id'] );
		
		foreach ( $results as $result ) {
			$output .= '<option value="' . $result ['zone_id'] . '"';
			
			if (isset ( $this->request->get ['zone_id'] ) && ($this->request->get ['zone_id'] == $result ['zone_id'])) {
				$output .= ' selected="selected"';
			}
			
			$output .= '>' . $result ['name'] . $result ['name_fix'] . '</option>';
		}
		
		if (! $results) {
			$output .= '<option value="0">' . $this->language->get ( 'text_none' ) . '</option>';
		}
		
		$this->response->setOutput ( $output );
	}
	public function history() {

		$this->load_language ( 'sale/order' );
		$this->load_language ( 'sale/order_refund_check' );
		
		$this->load->model ( 'sale/order' );
		$this->load->model ( 'sale/order_refund' );
		$this->load->model ( 'localisation/order_status' );
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') ) {
			
			if($this->user->hasPermission ( 'modify', 'sale/order' )){

			$this->model_sale_order->addOrderHistory ( $this->request->get ['order_id'], $this->request->post, $this->user->getUserName () );
			
			//退优惠券动作
			if(intval($this->request->post['return_coupon']) == 1){
				$order_ids[] = $this->request->get ['order_id'];
				$this->load->model ( 'sale/coupon' );
				$this->model_sale_coupon->return_coupon($order_ids);
			}
			$this->data ['success'] = $this->language->get ( 'text_success' );
			
			
			
		} else {
			$this->data ['success'] = $this->language->get ( 'error_permission' );
		}
		}
		
		$order_info = $this->model_sale_order->getOrder (  $this->request->get ['order_id']);
		
		
		$payments=array();
		$paymentsres = $this->model_sale_order->getOrderPayments( $this->request->get ['order_id'] );
		foreach ($paymentsres as $p)
		{
			$payments[$p['order_payment_id']]=$p;
		}
		
		$this->data ['refunds'] = $this->model_sale_order_refund->getOrderRefunds ( array (
				"filter_order_id" => $this->request->get ['order_id']
		) );
		
		foreach ( $this->data ['refunds'] as $r ) {
			if ($r ['order_payment_id'] && !($r ['status'] == "PHASE1_REFUSED" || $r ['status'] == "PHASE2_REFUSED"|| $r ['status'] == "FAIL" )) {
				unset ( $payments [$r ['order_payment_id']] );
			}
		}
			
		$this->data ['payments'] = $payments;
		$this->data ['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses ();
		$this->data ['order_status_id'] = $order_info['order_status_id'];
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$this->data ['histories'] = array ();
		
		$results = $this->model_sale_order->getOrderHistories ( $this->request->get ['order_id'], ($page - 1) * 10, 10 );
		
		foreach ( $results as $result ) {
			$this->data ['histories'] [] = array (
					'notify' => $result ['notify'] ? $this->language->get ( 'text_yes' ) : $this->language->get ( 'text_no' ),
					'status' => $result ['status'],
					'comment' => $result ['comment'],
					'date_added' => date ( $this->language->get ( 'date_format_short' ) . ' H:i', strtotime ( $result ['date_added'] ) ),
					'operator' => $result ['operator'] 
			);
		}
		
		$history_total = $this->model_sale_order->getTotalOrderHistories ( $this->request->get ['order_id'] );
		
		$pagination = new Pagination ();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link ( 'sale/order/history', 'token=' . $this->session->data ['token'] . '&order_id=' . $this->request->get ['order_id'] . '&page={page}', 'SSL' );
		
		$this->data ['pagination'] = $pagination->render ();
		
		$order_info = $this->model_sale_order->getOrder ( $this->request->get ['order_id'] );
		
		if (isset ( $order_info ['express'] )) {
			$this->data ['express'] = $order_info ['express'];
			$this->data ['express_website'] = $order_info ['express_website'];
			$this->data ['express_no'] = $order_info ['express_no'];
		}
		$this->template = 'sale/order_history.tpl';
		
		$this->response->setOutput ( $this->render () );
	}

	private function checkOrderDiscountStatus($order_id) {
		$this->load->model ( 'sale/order_discount' );
		
		$status = $this->model_sale_order_discount->getTotalDiscountHistories ( $order_id );
		
		if ($status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function discount_cancel() {
		$this->load->model ( 'sale/order_discount' );
		
		if (isset ( $this->request->get ['order_id'] )) {
			$order_id = $this->request->get ['order_id'];
			
			// 移除折扣历史
			$this->model_sale_order_discount->removeOrderDiscount ( $order_id );
			
			// TODO:移除优惠价格在订单总额中
			$data = array (
					'order_id' => $order_id,
					'code' => 'discount' 
			);
			
			$discount = $this->model_sale_order_discount->getDiscountOrderTotalValue ( $data );
			
			$this->model_sale_order_discount->deleteDiscountOrderTotal ( $data );
			
			if ($discount) {
				// 更新订单总额
				$this->model_sale_order_discount->plusOrderTotal ( $order_id, $discount );
			}
		}
		
		$this->redirect ( $this->url->link ( 'sale/order/info', 'token=' . $this->session->data ['token'] . '&order_id=' . $this->request->get ['order_id'], 'SSL' ) );
	}
	public function discount() {
		$this->load_language ( 'sale/order_discount' );
		
		$this->load->model ( 'sale/order_discount' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			if (! $this->user->hasPermission ( 'modify', 'sale/order' )) {
				$this->data ['error_warning'] = $this->language->get ( 'error_permission' );
				$this->data ['success'] = '';
			} else {
				$this->data ['error_warning'] = '';
				
				$order_id = $this->request->get ['order_id'];
				
				$discount = ( float ) $this->request->post ['discount'];
				
				// 添加折扣历史
				$this->model_sale_order_discount->addOrderDiscount ( $order_id, $this->request->post );
				
				// TODO:增加优惠价格在订单总额中
				$data = array (
						'order_id' => $order_id,
						'code' => 'discount',
						'title' => $this->language->get ( 'heading_title' ),
						'text' => $this->currency->format ( $discount ),
						'value' => $discount 
				);
				
				$this->model_sale_order_discount->addDiscountOrderTotal ( $data );
				
				// 更新订单总额
				$this->model_sale_order_discount->minusOrderTotal ( $order_id, $discount );
				
				$this->data ['success'] = $this->language->get ( 'text_success_discount' );
			}
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$this->data ['histories'] = array ();
		
		$results = $this->model_sale_order_discount->getDiscountHistories ( $this->request->get ['order_id'], ($page - 1) * 10, 10 );
		
		foreach ( $results as $result ) {
			$action = array ();
			
			// TODO：增加取消的限制
			$action [] = array (
					'text' => $this->language->get ( 'text_cancel' ),
					'href' => $this->url->link ( 'sale/order/discount_cancel', 'token=' . $this->session->data ['token'] . '&order_id=' . $result ['order_id'] . $url, 'SSL' ) 
			);
			
			$this->data ['histories'] [] = array (
					'total' => $result ['total'],
					'comment' => $result ['comment'],
					'date_added' => $result ['date_added'],
					'action' => $action 
			);
		}
		
		$history_total = $this->model_sale_order_discount->getTotalDiscountHistories ( $this->request->get ['order_id'] );
		
		$pagination = new Pagination ();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link ( 'sale/order/discount', 'token=' . $this->session->data ['token'] . '&order_id=' . $this->request->get ['order_id'] . '&page={page}', 'SSL' );
		
		$this->data ['pagination'] = $pagination->render ();
		
		$this->template = 'sale/order_discount_history.tpl';
		
		$this->response->setOutput ( $this->render () );
	}
	public function addreward() {
		$this->language->load ( 'sale/order' );
		
		$json = array ();
		
		if (! $this->user->hasPermission ( 'modify', 'sale/order' )) {
			$json ['error'] = $this->language->get ( 'error_permission' );
		} elseif (isset ( $this->request->get ['order_id'] )) {
			$this->load->model ( 'sale/order' );
			
			$order_info = $this->model_sale_order->getOrder ( $this->request->get ['order_id'] );
			
			if ($order_info && $order_info ['customer_id']) {
				$this->load->model ( 'sale/customer' );
				
				$this->model_sale_customer->addReward ( $order_info ['customer_id'], $this->language->get ( 'text_order_id' ) . ' #' . $this->request->get ['order_id'], $order_info ['reward'], $this->request->get ['order_id'] );
				
				$json ['success'] = $this->language->get ( 'text_reward_added' );
			}
		}
		
		$this->load->library ( 'json' );
		
		$this->response->setOutput ( Json::encode ( $json ) );
	}
	public function removereward() {
		$this->language->load ( 'sale/order' );
		
		$json = array ();
		
		if (! $this->user->hasPermission ( 'modify', 'sale/order' )) {
			$json ['error'] = $this->language->get ( 'error_permission' );
		} elseif (isset ( $this->request->get ['order_id'] )) {
			$this->load->model ( 'sale/order' );
			
			$order_info = $this->model_sale_order->getOrder ( $this->request->get ['order_id'] );
			
			if ($order_info && $order_info ['customer_id']) {
				$this->load->model ( 'sale/customer' );
				
				$this->model_sale_customer->deleteReward ( $this->request->get ['order_id'] );
			}
			
			$json ['success'] = $this->language->get ( 'text_reward_removed' );
		}
		
		$this->load->library ( 'json' );
		
		$this->response->setOutput ( Json::encode ( $json ) );
	}
	public function addcommission() {
		$this->language->load ( 'sale/order' );
		
		$json = array ();
		
		if (! $this->user->hasPermission ( 'modify', 'sale/order' )) {
			$json ['error'] = $this->language->get ( 'error_permission' );
		} elseif (isset ( $this->request->get ['order_id'] )) {
			$this->load->model ( 'sale/order' );
			
			$order_info = $this->model_sale_order->getOrder ( $this->request->get ['order_id'] );
			
			if ($order_info && $order_info ['affiliate_id']) {
				$this->load->model ( 'sale/affiliate' );
				
				$this->model_sale_affiliate->addTransaction ( $order_info ['affiliate_id'], $this->language->get ( 'text_order_id' ) . ' #' . $this->request->get ['order_id'], $order_info ['commission'], $this->request->get ['order_id'] );
			}
			
			$json ['success'] = $this->language->get ( 'text_commission_added' );
		}
		
		$this->load->library ( 'json' );
		
		$this->response->setOutput ( Json::encode ( $json ) );
	}
	public function removecommission() {
		$this->language->load ( 'sale/order' );
		
		$json = array ();
		
		if (! $this->user->hasPermission ( 'modify', 'sale/order' )) {
			$json ['error'] = $this->language->get ( 'error_permission' );
		} elseif (isset ( $this->request->get ['order_id'] )) {
			$this->load->model ( 'sale/order' );
			
			$order_info = $this->model_sale_order->getOrder ( $this->request->get ['order_id'] );
			
			if ($order_info && $order_info ['affiliate_id']) {
				$this->load->model ( 'sale/affiliate' );
				
				$this->model_sale_affiliate->deleteTransaction ( $this->request->get ['order_id'] );
			}
			
			$json ['success'] = $this->language->get ( 'text_commission_removed' );
		}
		
		$this->load->library ( 'json' );
		
		$this->response->setOutput ( Json::encode ( $json ) );
	}
	public function addcredit() {
		$this->language->load ( 'sale/order' );
		
		$json = array ();
		
		if (! $this->user->hasPermission ( 'modify', 'sale/order' )) {
			$json ['error'] = $this->language->get ( 'error_permission' );
		} elseif (isset ( $this->request->get ['order_id'] )) {
			$this->load->model ( 'sale/order' );
			
			$order_info = $this->model_sale_order->getOrder ( $this->request->get ['order_id'] );
			
			if ($order_info && $order_info ['customer_id']) {
				$this->load->model ( 'sale/customer' );
				
				$this->model_sale_customer->addTransaction ( $order_info ['customer_id'], $this->language->get ( 'text_order_id' ) . ' #' . $this->request->get ['order_id'], $order_info ['total'], $this->request->get ['order_id'] );
			}
			
			$json ['success'] = $this->language->get ( 'text_credit_added' );
		}
		
		$this->load->library ( 'json' );
		
		$this->response->setOutput ( Json::encode ( $json ) );
	}
	public function removecredit() {
		$this->language->load ( 'sale/order' );
		
		$json = array ();
		
		if (! $this->user->hasPermission ( 'modify', 'sale/order' )) {
			$json ['error'] = $this->language->get ( 'error_permission' );
		} elseif (isset ( $this->request->get ['order_id'] )) {
			$this->load->model ( 'sale/order' );
			
			$order_info = $this->model_sale_order->getOrder ( $this->request->get ['order_id'] );
			
			if ($order_info && $order_info ['customer_id']) {
				$this->load->model ( 'sale/customer' );
				
				$this->model_sale_customer->deleteTransaction ( $this->request->get ['order_id'] );
			}
			
			$json ['success'] = $this->language->get ( 'text_credit_removed' );
		}
		
		$this->load->library ( 'json' );
		
		$this->response->setOutput ( Json::encode ( $json ) );
	}
	public function download() {
		$this->load->model ( 'sale/order' );
		
		if (isset ( $this->request->get ['order_option_id'] )) {
			$order_option_id = $this->request->get ['order_option_id'];
		} else {
			$order_option_id = 0;
		}
		
		$option_info = $this->model_sale_order->getOrderOption ( $this->request->get ['order_id'], $order_option_id );
		
		if ($option_info && $option_info ['type'] == 'file') {
			$file = DIR_DOWNLOAD . $option_info ['value'];
			$mask = basename ( substr ( $option_info ['value'], 0, strrpos ( $option_info ['value'], '.' ) ) );
			$mime = 'application/octet-stream';
			$encoding = 'binary';
			
			if (! headers_sent ()) {
				if (file_exists ( $file )) {
					header ( 'Pragma: public' );
					header ( 'Expires: 0' );
					header ( 'Content-Description: File Transfer' );
					header ( 'Content-Type: ' . $mime );
					header ( 'Content-Transfer-Encoding: ' . $encoding );
					header ( 'Content-Disposition: attachment; filename=' . ($mask ? $mask : basename ( $file )) );
					header ( 'Content-Length: ' . filesize ( $file ) );
					
					$file = readfile ( $file, 'rb' );
					
					print ($file) ;
				} else {
					exit ( 'Error: Could not find file ' . $file . '!' );
				}
			} else {
				exit ( 'Error: Headers already sent out!' );
			}
		} else {
			$this->load_language ( 'error/not_found' );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->data ['heading_title'] = $this->language->get ( 'heading_title' );
			
			$this->data ['text_not_found'] = $this->language->get ( 'text_not_found' );
			
			$this->data ['breadcrumbs'] = array ();
			
			$this->data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_home' ),
					'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
					'separator' => false 
			);
			
			$this->data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'heading_title' ),
					'href' => $this->url->link ( 'error/not_found', 'token=' . $this->session->data ['token'], 'SSL' ),
					'separator' => $this->language->get ( 'text_breadcrumb_separator' ) 
			);
			
			$this->template = 'error/not_found.tpl';
			$this->children = array (
					'common/header',
					'common/footer' 
			);
			
			$this->response->setOutput ( $this->render () );
		}
	}
	public function invoice() {
		$this->load_language ( 'sale/order' );
		
		$this->data ['title'] = $this->language->get ( 'heading_title' );
		
		if (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1'))) {
			$this->data ['base'] = HTTPS_SERVER;
		} else {
			$this->data ['base'] = HTTP_SERVER;
		}
		
		$this->data ['direction'] = $this->language->get ( 'direction' );
		$this->data ['language'] = $this->language->get ( 'code' );
		
		$this->load->model ( 'sale/order' );
		
		$this->load->model ( 'setting/setting' );
		
		$this->data ['orders'] = array ();
		
		$orders = array ();
		
		if (isset ( $this->request->post ['selected'] )) {
			$orders = $this->request->post ['selected'];
		} elseif (isset ( $this->request->get ['order_id'] )) {
			$orders [] = $this->request->get ['order_id'];
		}
		
		foreach ( $orders as $order_id ) {
			$order_info = $this->model_sale_order->getOrder ( $order_id );
			
			if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting ( 'config', $order_info ['store_id'] );
				
				if ($store_info) {
					$store_address = $store_info ['config_address'];
					$store_email = $store_info ['config_email'];
					$store_telephone = $store_info ['config_telephone'];
					$store_fax = $store_info ['config_fax'];
				} else {
					$store_address = $this->config->get ( 'config_address' );
					$store_email = $this->config->get ( 'config_email' );
					$store_telephone = $this->config->get ( 'config_telephone' );
					$store_fax = $this->config->get ( 'config_fax' );
				}
				
				if ($order_info ['invoice_no']) {
					$invoice_no = $order_info ['invoice_prefix'] . $order_info ['invoice_no'];
				} else {
					$invoice_no = '';
				}
				
				if ($order_info ['shipping_address_format']) {
					$format = $order_info ['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array (
						'{firstname}',
						'{lastname}',
						'{company}',
						'{address_1}',
						'{address_2}',
						'{mobile}',
						'{phone}',
						'{city}',
						'{postcode}',
						'{zone}',
						'{zone_code}',
						'{country}' 
				);
				
				$replace = array (
						'firstname' => $order_info ['shipping_firstname'],
						'lastname' => $order_info ['shipping_lastname'],
						'company' => $order_info ['shipping_company'],
						'address_1' => $order_info ['shipping_address_1'],
						'address_2' => $order_info ['shipping_address_2'],
						'mobile' => $order_info ['shipping_mobile'],
						'phone' => $order_info ['shipping_phone'],
						'city' => $order_info ['shipping_city'],
						'postcode' => $order_info ['shipping_postcode'],
						'zone' => $order_info ['shipping_zone'],
						'zone_code' => $order_info ['shipping_zone_code'],
						'country' => $order_info ['shipping_country'] 
				);
				
				$shipping_address = str_replace ( array (
						"\r\n",
						"\r",
						"\n" 
				), '<br />', preg_replace ( array (
						"/\s\s+/",
						"/\r\r+/",
						"/\n\n+/" 
				), '<br />', trim ( str_replace ( $find, $replace, $format ) ) ) );
				
				if ($order_info ['payment_address_format']) {
					$format = $order_info ['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array (
						'{firstname}',
						'{lastname}',
						'{company}',
						'{address_1}',
						'{address_2}',
						'{city}',
						'{postcode}',
						'{zone}',
						'{zone_code}',
						'{country}' 
				);
				
				$replace = array (
						'firstname' => $order_info ['payment_firstname'],
						'lastname' => $order_info ['payment_lastname'],
						'company' => $order_info ['payment_company'],
						'address_1' => $order_info ['payment_address_1'],
						'address_2' => $order_info ['payment_address_2'],
						'city' => $order_info ['payment_city'],
						'postcode' => $order_info ['payment_postcode'],
						'zone' => $order_info ['payment_zone'],
						'zone_code' => $order_info ['payment_zone_code'],
						'country' => $order_info ['payment_country'] 
				);
				
				$payment_address = str_replace ( array (
						"\r\n",
						"\r",
						"\n" 
				), '<br />', preg_replace ( array (
						"/\s\s+/",
						"/\r\r+/",
						"/\n\n+/" 
				), '<br />', trim ( str_replace ( $find, $replace, $format ) ) ) );
				
				$product_data = array ();
				
				$products = $this->model_sale_order->getOrderProducts ( $order_id );
				
				foreach ( $products as $product ) {
					$option_data = array ();
					
					$options = $this->model_sale_order->getOrderOptions ( $order_id, $product ['order_product_id'] );
					
					foreach ( $options as $option ) {
						if ($option ['type'] != 'file') {
							$option_data [] = array (
									'name' => $option ['name'],
									'value' => $option ['value'] 
							);
						} else {
							$option_data [] = array (
									'name' => $option ['name'],
									'value' => substr ( $option ['value'], 0, strrpos ( $option ['value'], '.' ) ) 
							);
						}
					}
					
					$product_data [] = array (
							'name' => $product ['name'],
							'model' => $product ['model'],
							'option' => $option_data,
							'quantity' => $product ['quantity'],
							'price' => $this->currency->format ( $product ['price'], $order_info ['currency_code'], $order_info ['currency_value'] ),
							'total' => $this->currency->format ( $product ['total'], $order_info ['currency_code'], $order_info ['currency_value'] ) 
					);
				}
				
				if ($order_info ['p_order_id'])
					$total_data = $this->model_sale_order->getOrderTotals ( $order_info ['p_order_id'] );
				else
					$total_data = $this->model_sale_order->getOrderTotals ( $order_id );
				
				if ($order_info ['p_order_id']) {
					$sub_orders = $this->model_sale_order->getSubOrders ( $order_info ['p_order_id'] );
				} else {
					$sub_orders = array ();
				}
				
				$this->data ['orders'] [] = array (
						'order_id' => $order_id,
						'p_order_id' => $order_info ['p_order_id'],
						'invoice_no' => $invoice_no,
						'invoice_date' => date ( $this->language->get ( 'date_format_short' ), strtotime ( 'now' ) ),
						'date_added' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $order_info ['date_added'] ) ),
						'store_name' => $order_info ['store_name'],
						'payment_method' => $order_info ['payment_method'],
						'shipping_method' => $order_info ['shipping_method'],
						'express' => $order_info ['express'],
						'express_no' => $order_info ['express_no'],
						'store_url' => rtrim ( $order_info ['store_url'], '/' ),
						'store_address' => nl2br ( $store_address ),
						'store_email' => $store_email,
						'store_telephone' => $store_telephone,
						'store_fax' => $store_fax,
						'email' => $order_info ['email'],
						'telephone' => $order_info ['telephone'],
						'pdate' => $order_info ['pdate'],
						'shipping_address' => $shipping_address,
						'payment_address' => $payment_address,
						'product' => $product_data,
						'total' => $total_data,
						'sub_orders' => $sub_orders,
						'comment' => nl2br ( $order_info ['comment'] ) 
				);
			}
		}
		
		$this->template = 'sale/order_invoice.tpl';
		
		$this->response->setOutput ( $this->render () );
	}
	public function upload() {
		$this->language->load ( 'sale/order' );
		
		$json = array ();
		
		if ($this->request->server ['REQUEST_METHOD'] == 'POST') {
			if (! empty ( $this->request->files ['file'] ['name'] )) {
				$filename = html_entity_decode ( $this->request->files ['file'] ['name'], ENT_QUOTES, 'UTF-8' );
				
				if ((utf8_strlen ( $filename ) < 3) || (utf8_strlen ( $filename ) > 128)) {
					$json ['error'] = $this->language->get ( 'error_filename' );
				}
				
				$allowed = array ();
				
				$filetypes = explode ( ',', $this->config->get ( 'config_upload_allowed' ) );
				
				foreach ( $filetypes as $filetype ) {
					$allowed [] = trim ( $filetype );
				}
				
				if (! in_array ( utf8_substr ( strrchr ( $filename, '.' ), 1 ), $allowed )) {
					$json ['error'] = $this->language->get ( 'error_filetype' );
				}
				
				if ($this->request->files ['file'] ['error'] != UPLOAD_ERR_OK) {
					$json ['error'] = $this->language->get ( 'error_upload_' . $this->request->files ['file'] ['error'] );
				}
			} else {
				$json ['error'] = $this->language->get ( 'error_upload' );
			}
			
			if (! isset ( $json ['error'] )) {
				if (is_uploaded_file ( $this->request->files ['file'] ['tmp_name'] ) && file_exists ( $this->request->files ['file'] ['tmp_name'] )) {
					$file = basename ( $filename ) . '.' . md5 ( rand () );
					
					$json ['file'] = $file;
					
					move_uploaded_file ( $this->request->files ['file'] ['tmp_name'], DIR_DOWNLOAD . $file );
				}
				
				$json ['success'] = $this->language->get ( 'text_upload' );
			}
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	
	/**
	 *
	 * @return string
	 */
	private function getUrlParameters() {
		$url = $this->getCommonUrlParameters ();
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
			return $url;
		}
		return $url;
	}
	
	/**
	 *
	 * @return string
	 */
	private function getCommonUrlParameters() {
		$url = '';
		
		if (isset ( $this->request->get ['filter_order_id'] )) {
			$url .= '&filter_order_id=' . $this->request->get ['filter_order_id'];
		}
		
		if (isset ( $this->request->get ['filter_customer_phone'] )) {
			$url .= '&filter_customer_phone=' . $this->request->get ['filter_customer_phone'];
		}
		
		if (isset ( $this->request->get ['filter_customer'] )) {
			$url .= '&filter_customer=' . $this->request->get ['filter_customer'];
		}
		
		if (isset ( $this->request->get ['filter_partner_code'] )) {
			$url .= '&filter_partner_code=' . $this->request->get ['filter_partner_code'];
		}
		
		if (isset ( $this->request->get ['filter_source_from'] )) {
			$url .= '&filter_source_from=' . $this->request->get ['filter_source_from'];
		}
		
		if (isset ( $this->request->get ['filter_point_id'] )) {
			$url .= '&filter_point_id=' . $this->request->get ['filter_point_id'];
		}
		if (isset ( $this->request->get ['filter_point_name'] )) {
			$url .= '&filter_point_name=' . $this->request->get ['filter_point_name'];
		}
		
		if (isset ( $this->request->get ['filter_order_status_id'] )) {
			$url .= '&filter_order_status_id=' . $this->request->get ['filter_order_status_id'];
		}
		
		if (isset ( $this->request->get ['filter_total'] )) {
			$url .= '&filter_total=' . $this->request->get ['filter_total'];
		}
		
		if (isset ( $this->request->get ['filter_date_added'] )) {
			$url .= '&filter_date_added=' . $this->request->get ['filter_date_added'];
		}
		
		if (isset ( $this->request->get ['filter_date_pick'] )) {
			$url .= '&filter_date_pick=' . $this->request->get ['filter_date_pick'];
//			return $url;
		}
		if (isset ( $this->request->get ['payment_code'] )) {
			$url .= '&payment_code=' . $this->request->get ['payment_code'];
//			return $url;
		}
		if (isset ( $this->request->get ['order_type'] )) {
			$url .= '&order_type=' . $this->request->get ['order_type'];
//			return $url;
		}
		return $url;
	}
	
	/*
	 * public function certification() {
	 * $this->language->load('sale/order');
	 *
	 * $json = array();
	 *
	 * if (!empty($this->request->files['file']['name'])) {
	 * $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
	 *
	 * if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
	 * $json['error'] = $this->language->get('error_filename');
	 * }
	 *
	 * // $allowed = array();
	 *
	 * // $filetypes = explode(',', $this->config->get('config_upload_allowed'));
	 *
	 * // foreach ($filetypes as $filetype) {
	 * // $allowed[] = trim($filetype);
	 * // }
	 *
	 * // if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
	 * // $json['error'] = $this->language->get('error_filetype');
	 * // }
	 *
	 * if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
	 * $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
	 * }
	 * } else {
	 * $json['error'] = $this->language->get('error_upload');
	 * }
	 *
	 * if (!$json) {
	 * if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
	 * $file = basename($filename) . '.' . md5(mt_rand());
	 *
	 * // Hide the uploaded file name so people can not link to it directly.
	 * $json['file'] = $this->encryption->encrypt($file);
	 *
	 * if(!is_dir (DIR_DOWNLOAD.'/order-certification/')){
	 * mkdir(DIR_DOWNLOAD.'/order-certification/');
	 * }
	 *
	 * move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD.'/order-certification/' . $file);
	 * }
	 *
	 * $this->load->model('sale/order');
	 *
	 * $this->model_sale_order->updateCertification($this->request->get['order_id'],$json['file']);
	 *
	 * $json['success'] = $this->language->get('text_upload');
	 * }
	 *
	 * $this->response->setOutput(json_encode($json));
	 * }
	 *
	 * public function removecertification() {
	 * $this->load->model('sale/order');
	 *
	 * $this->model_sale_order->updateCertification($this->request->get['order_id'],'');
	 *
	 * $json['success'] = '证书清除成功';
	 *
	 * $this->response->setOutput(json_encode($json));
	 * }
	 */
}

?>