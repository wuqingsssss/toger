<?php 
class ControllerPaymentAlipay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load_language('payment/alipay');

		$this->document->settitle($this->language->get('heading_title'));
		
		if (isset($this->error['secrity_code'])) {
			$this->data['error_secrity_code'] = $this->error['secrity_code'];
		} else {
			$this->data['error_secrity_code'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}

		if (isset($this->error['partner'])) {
			$this->data['error_partner'] = $this->error['partner'];
		} else {
			$this->data['error_partner'] = '';
		}
		
		$this->data['breadcrumbs']  = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/alipay&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('alipay', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect( HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}


		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/alipay&token=' . $this->session->data['token'];
		
		$this->data['cancel'] =  HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
		
		if (isset($this->request->post['alipay_seller_email'])) {
			$this->data['alipay_seller_email'] = $this->request->post['alipay_seller_email'];
		} else {
			$this->data['alipay_seller_email'] = $this->config->get('alipay_seller_email');
		}

		if (isset($this->request->post['alipay_security_code'])) {
			$this->data['alipay_security_code'] = $this->request->post['alipay_security_code'];
		} else {
			$this->data['alipay_security_code'] = $this->config->get('alipay_security_code');
		}

		if (isset($this->request->post['alipay_partner'])) {
			$this->data['alipay_partner'] = $this->request->post['alipay_partner'];
		} else {
			$this->data['alipay_partner'] = $this->config->get('alipay_partner');
		}		

		if (isset($this->request->post['alipay_trade_type'])) {
			$this->data['alipay_trade_type'] = $this->request->post['alipay_trade_type'];
		} else {
			$this->data['alipay_trade_type'] = $this->config->get('alipay_trade_type');
		}
		
		if (isset($this->request->post['alipay_trade_bank'])) {
			$this->data['alipay_trade_bank'] = $this->request->post['alipay_trade_bank'];
		} else {
			$this->data['alipay_trade_bank'] = $this->config->get('alipay_trade_bank');
		}
		
		if (isset($this->request->post['alipay_order_status_id'])) {
			$this->data['alipay_order_status_id'] = $this->request->post['alipay_order_status_id'];
		} else {
			$this->data['alipay_order_status_id'] = $this->config->get('alipay_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['alipay_status'])) {
			$this->data['alipay_status'] = $this->request->post['alipay_status'];
		} else {
			$this->data['alipay_status'] = $this->config->get('alipay_status');
		}
		
		if (isset($this->request->post['alipay_sort_order'])) {
			$this->data['alipay_sort_order'] = $this->request->post['alipay_sort_order'];
		} else {
			$this->data['alipay_sort_order'] = $this->config->get('alipay_sort_order');
		}
		
		$this->template = 'payment/alipay.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}

	
	public function refund(){
		
		if(!$this->user->hasPermission('modify','sale/order_refund')){
			return $this->forward('error/permission');
		}
		
		$this->load_language('sale/order_refund');
		
		$this->data ['breadcrumbs'] = array ();
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => false
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'sale/order_refund', 'token=' . $this->session->data ['token'] ."&filter_payment_code=alipay", 'SSL' ),
				'separator' => $this->language->get ( 'text_breadcrumb_separator' )
		);
		
		$FILTER_PAYMENT_CODE = 'filter_payment_code';
		$FILTER_ORDER_REFUND_IDS     = 'filter_order_refund_ids';
		$FILTER_PARTNER_CODE = 'filter_partner_code';
		$FILTER_CUSTOMER     = 'filter_customer';
		$FILTER_CUSTOMER_PHONE = 'filter_customer_phone';
		$FILTER_ORDER_STATUS_ID = 'filter_order_status_id';
		$FILTER_ORDER_REFUND_STATUS = 'filter_order_refund_status';
		$FILTER_TOTAL = 'filter_total';
		$FILTER_DATE_ADDED = 'filter_date_added';
		$FILTER_DATE_MODIFIED = 'filter_date_modified';
		$SORT = 'sort';
		$ORDER = 'order';
		$PAGE = 'page';
		$TOKEN = 'token';
		
		$this->load->service('payment/alipay/alipay','service');
		$this->load->model('sale/order_refund');
		
		$data=array();
		if($this->request->post['selectedallpage']=='on'){
			
			$data = array(
				$FILTER_PAYMENT_CODE=>$this->request->post[$FILTER_PAYMENT_CODE],
				$FILTER_CUSTOMER => $this->request->post[$FILTER_CUSTOMER],
				$FILTER_CUSTOMER_PHONE => $this->request->post[$FILTER_CUSTOMER_PHONE],
				$FILTER_ORDER_STATUS_ID => $this->request->post[$FILTER_ORDER_STATUS_ID],
				$FILTER_ORDER_REFUND_STATUS => $this->request->post[$FILTER_ORDER_REFUND_STATUS],
				$FILTER_TOTAL => $this->request->post[$FILTER_TOTAL],
				$FILTER_DATE_ADDED => $this->request->post[$FILTER_DATE_ADDED],
				$FILTER_DATE_MODIFIED => $this->request->post[$FILTER_DATE_MODIFIED],
				$FILTER_PARTNER_CODE => $this->request->post[$FILTER_PARTNER_CODE],
		    );

		}elseif($this->request->post['selected']){
			$data = array(
					$FILTER_PAYMENT_CODE=>$this->request->post[$FILTER_PAYMENT_CODE],
					$FILTER_ORDER_REFUND_IDS => $this->request->post['selected'],
			);
		}
		if($data){
		$results = $this->model_sale_order_refund->getOrderRefunds($data);
		}

	
		$order_list=array();
		$order_list2=array();
		$order_refund_ids=array();
		foreach($results as $order_refund)
		{
			if($order_refund['payment_account']){
				
				$order_list2[]=array(
						'order_refund_id'=>$order_refund['order_refund_id'],
						'order_id'=>$order_refund['order_id'],
						'payment_account'=>$order_refund['payment_account'],
						'payment_name'=>$order_refund['payment_name'],
						'payment_code'=>$order_refund['payment_code'],
						'value'=>$order_refund['value'],
						'payment_code1'=>$order_refund['payment_code1'],
						'value1'=>$order_refund['value1']
				);
	
			}elseif($order_refund['payment_trade_no']){
			
			$order_list[]=array(
					'order_refund_id'=>$order_refund['order_refund_id'],
					'payment_trade_no'=>$order_refund['payment_trade_no'],
					'payment_code'=>$order_refund['payment_code'],
					'value'=>$order_refund['value'],
					'payment_code1'=>$order_refund['payment_code1'],
					'value1'=>$order_refund['value1']	
			);
			}
			$order_refund_ids[]=$order_refund['order_refund_id'];
			
		}
		$this->model_sale_order_refund->updateStatus($order_refund_ids,'PAYING','提到到支付宝平台处理');
        if ($order_list||$order_list2) {
        	
			if($order_list){
			    $this->data ['resdata1'] = $this->service_payment_alipay_alipay->reFund ($order_list,!($order_list&&$order_list2));
			}
			if($order_list2){
				$this->data ['resdata2'] = $this->service_payment_alipay_alipay->batchTrans ($order_list2 ,!($order_list&&$order_list2));
			}


			
		} else {
			$this->data ['htmlbody'] = "没有相关的记录";
		}
		$this->template = 'payment/alipay_refund.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render ();
		
	}
	

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/alipay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['alipay_seller_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (!$this->request->post['alipay_security_code']) {
			$this->error['secrity_code'] = $this->language->get('error_secrity_code');
		}

		if (!$this->request->post['alipay_partner']) {
			$this->error['partner'] = $this->language->get('error_partner');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>