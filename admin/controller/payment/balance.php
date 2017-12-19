<?php 
class ControllerPaymentBalance extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load_language('payment/balance');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('balance', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
	
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/balance', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('payment/balance', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	
		
		if (isset($this->request->post['balance_total'])) {
			$this->data['balance_total'] = $this->request->post['balance_total'];
		} else {
			$this->data['balance_total'] = $this->config->get('balance_total'); 
		}
				
		if (isset($this->request->post['balance_order_status_id'])) {
			$this->data['balance_order_status_id'] = $this->request->post['balance_order_status_id'];
		} else {
			$this->data['balance_order_status_id'] = $this->config->get('balance_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['balance_user_group_id'])) {
			$this->data['balance_user_group_id'] = $this->request->post['balance_user_group_id'];
		} else {
			$this->data['balance_user_group_id'] = $this->config->get('balance_user_group_id'); 
		} 
		
		$this->load->model('user/user_group');						
		
		$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();
		
		if (isset($this->request->post['balance_status'])) {
			$this->data['balance_status'] = $this->request->post['balance_status'];
		} else {
			$this->data['balance_status'] = $this->config->get('balance_status');
		}
		
		if (isset($this->request->post['balance_sort_order'])) {
			$this->data['balance_sort_order'] = $this->request->post['balance_sort_order'];
		} else {
			$this->data['balance_sort_order'] = $this->config->get('balance_sort_order');
		}

		$this->template = 'payment/balance.tpl';
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
				'href' => $this->url->link ( 'sale/order_refund', 'token=' . $this->session->data ['token'] ."&filter_payment_code=balance", 'SSL' ),
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
			
		$this->load->service('payment/balance/balance','service');
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
			
			$action = array();
			$action[] = array(
					'text' => $this->language->get('text_view'),
					'href' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $orderinfo['order_id'] . $queryUrlPart, 'SSL')
			);
			
			
			$order_list[]=array('order_refund_id'=>$orderinfo['order_refund_id'],
					'order_id'=>$orderinfo['order_id'],
					'customer_id'=>$orderinfo['customer_id'],
					'order_payment_id'=>$orderinfo['order_payment_id'],
					'payment_trade_no'=>$orderinfo['payment_trade_no'],
					'payment_code'=>$orderinfo['payment_code'],
					'value'=>$orderinfo['value'],
					'value1'=>$orderinfo['value1'],
					'payment_code1'=>$orderinfo['payment_code1'],
					'action'=>$action
			);
		}


		$this->data['resdata'] =  $this->service_payment_balance_balance->reFund($order_list);
		$this->template = 'payment/balance_refund.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
			
	}
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/balance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>