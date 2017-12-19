<?php 
class ControllerPaymentWxpay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load_language('payment/wxpay');

		$this->document->settitle($this->language->get('heading_title'));
		
		if (isset($this->error['appid'])) {
			$this->data['error_appid'] = $this->error['appid'];
		} else {
			$this->data['error_appid'] = '';
		}
		
		if (isset($this->error['appsecret'])) {
			$this->data['error_appsecret'] = $this->error['error_appsecret'];
		} else {
			$this->data['error_appsecret'] = '';
		}

		if (isset($this->error['partnerid'])) {
			$this->data['error_partnerid'] = $this->error['partnerid'];
		} else {
			$this->data['error_partnerid'] = '';
		}

		if (isset($this->error['partnerkey'])) {
			$this->data['error_partnerkey'] = $this->error['partnerkey'];
		} else {
			$this->data['error_partnerkey'] = '';
		}
		
		if (isset($this->error['apikey'])) {
			$this->data['error_apikey'] = $this->error['error_apikey'];
		} else {
			$this->data['error_apikey'] = '';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/wxpay&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('wxpay', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect( HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['appid'])) {
			$this->data['error_appid'] = $this->error['appid'];
		} else {
			$this->data['error_appid'] = '';
		}

		if (isset($this->error['appsecret'])) {
			$this->data['error_appsecret'] = $this->error['appsecret'];
		} else {
			$this->data['error_appsecret'] = '';
		}

		if (isset($this->error['partnerid'])) {
			$this->data['error_partnerid'] = $this->error['partnerid'];
		} else {
			$this->data['error_partnerid'] = '';
		}

		if (isset($this->error['partnerkey'])) {
			$this->data['error_partnerkey'] = $this->error['partnerkey'];
		} else {
			$this->data['error_partnerkey'] = '';
		}

		if (isset($this->error['apikey'])) {
			$this->data['error_apikey'] = $this->error['apikey'];
		} else {
			$this->data['error_apikey'] = '';
		}

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/wxpay&token=' . $this->session->data['token'];
		
		$this->data['cancel'] =  HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
		

		if (isset($this->request->post['wxpay_appid'])) {
			$this->data['wxpay_appid'] = $this->request->post['wxpay_appid'];
		} else {
			$this->data['wxpay_appid'] = $this->config->get('wxpay_appid');
		}

		if (isset($this->request->post['wxpay_appsecret'])) {
			$this->data['wxpay_appsecret'] = $this->request->post['wxpay_appsecret'];
		} else {
			$this->data['wxpay_appsecret'] = $this->config->get('wxpay_appsecret');
		}
		
		if (isset($this->request->post['wxpay_partnerid'])) {
			$this->data['wxpay_partnerid'] = $this->request->post['wxpay_partnerid'];
		} else {
			$this->data['wxpay_partnerid'] = $this->config->get('wxpay_partnerid');
		}

		if (isset($this->request->post['wxpay_partnerkey'])) {
			$this->data['wxpay_partnerkey'] = $this->request->post['wxpay_partnerkey'];
		} else {
			$this->data['wxpay_partnerkey'] = $this->config->get('wxpay_partnerkey');
		}

		if (isset($this->request->post['wxpay_apikey'])) {
			$this->data['wxpay_apikey'] = $this->request->post['wxpay_apikey'];
		} else {
			$this->data['wxpay_apikey'] = $this->config->get('wxpay_apikey');
		}
		
		if (isset($this->request->post['wxpay_order_status_id'])) {
			$this->data['wxpay_order_status_id'] = $this->request->post['wxpay_order_status_id'];
		} else {
			$this->data['wxpay_order_status_id'] = $this->config->get('wxpay_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['wxpay_status'])) {
			$this->data['wxpay_status'] = $this->request->post['wxpay_status'];
		} else {
			$this->data['wxpay_status'] = $this->config->get('wxpay_status');
		}
		
		if (isset($this->request->post['wxpay_sort_order'])) {
			$this->data['wxpay_sort_order'] = $this->request->post['wxpay_sort_order'];
		} else {
			$this->data['wxpay_sort_order'] = $this->config->get('wxpay_sort_order');
		}
		
		$this->template = 'payment/wxpay.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}

	public function refund(){
		if(!$this->user->hasPermission('modify','sale/order_refund')){
			return $this->forward('error/permission');
		}
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
		 
		$this->load->service('payment/wxpay/wxpay','service');
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
		foreach($results as $orderinfo)
		{
			$req_list[]=$this->service_payment_wxpay_wxpay->reFund($orderinfo);
		}
		

		foreach($results as $orderinfo)
		{
			$action = array();
			$action[] = array(
					'text' => $this->language->get('text_view'),
					'href' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $orderinfo['order_id'] . $queryUrlPart, 'SSL')
			);
			
			
			$refundQueryResult=$this->service_payment_wxpay_wxpay->reFundQuery($orderinfo);

			$refundQueryResult['action']=$action;
			
			$res[]=$refundQueryResult;
		}
	
		
		 
		 $this->data['htmldata']=print_r($req_list,1).print_r($res,1);
		if($req_list){
			$this->data['reqlist']=$req_list;
			$this->data['resdata']=$res;
		}
		else {
			$this->data['resdata1']="没有相关的记录";
			 
		}
		$this->template = 'payment/wxpay_refund.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
		 
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/wxpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['wxpay_appid']) {
			$this->error['appid'] = $this->language->get('error_appid');
		}

		if (!$this->request->post['wxpay_appsecret']) {
			$this->error['appsecret'] = $this->language->get('error_appsecret');
		}
	
		if (!$this->request->post['wxpay_partnerid']) {
			$this->error['partnerid'] = $this->language->get('error_partnerid');
		}

		if (!$this->request->post['wxpay_partnerkey']) {
			$this->error['partnerkey'] = $this->language->get('wxpay_partnerkey');
		}
	
		if (!$this->request->post['wxpay_apikey']) {
			$this->error['apikey'] = $this->language->get('error_apikey');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>