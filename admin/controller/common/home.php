<?php   
class ControllerCommonHome extends Controller {   
	public function index() {
    	$this->load_language('common/home');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->log_admin->info('p');
		// Check install directory exists
 		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$this->data['error_install'] = $this->language->get('error_install');
		} else {
			$this->data['error_install'] = '';
		}

		// Check image directory is writable
		$file = DIR_IMAGE . 'test';
		
		$handle = fopen($file, 'a+'); 

		fclose($handle); 		
		
		if (!file_exists($file)) {
			$this->data['error_image'] = sprintf($this->language->get('error_image'). DIR_IMAGE);
		} else {
			$this->data['error_image'] = '';
			
			unlink($file);
		}
		
		// Check image cache directory is writable
		$file = DIR_IMAGE . 'cache/test';
		
		$handle = fopen($file, 'a+'); 
		
		fwrite($handle, '');
			
		fclose($handle); 		
		
		if (!file_exists($file)) {
			$this->data['error_image_cache'] = sprintf($this->language->get('error_image_cache'). DIR_IMAGE . 'cache/');
		} else {
			$this->data['error_image_cache'] = '';
			
			unlink($file);
		}
		
		// Check cache directory is writable
		$file = DIR_CACHE . 'test';
		
		$handle = fopen($file, 'wb'); 
		
		fwrite($handle, '');
			
		fclose($handle); 		
		
		if (!file_exists($file)) {
			$this->data['error_cache'] = sprintf($this->language->get('error_image_cache'). DIR_CACHE);
		} else {
			$this->data['error_cache'] = '';
			
			unlink($file);
		}
		
		// Check download directory is writable
		$file = DIR_DOWNLOAD . 'test';
		
		$handle = fopen($file, 'a+'); 
		
		fwrite($handle, '');
			
		fclose($handle); 		
		
		if (!file_exists($file)) {
			$this->data['error_download'] = sprintf($this->language->get('error_download'). DIR_DOWNLOAD);
		} else {
			$this->data['error_download'] = '';
			
			unlink($file);
		}
		
		// Check logs directory is writable
		$file = DIR_LOGS . 'test';
		
		$handle = fopen($file, 'a+'); 
		
		fwrite($handle, '');
			
		fclose($handle); 		
		
		if (!file_exists($file)) {
			$this->data['errorlogs'] = sprintf($this->language->get('error_logs'). DIR_LOGS);
		} else {
			$this->data['error_logs'] = '';
			
			unlink($file);
		}

		//监测用户是否有营销管理页面权限
		if(!$this->user->permitOr(array('super_admin','sale_manage_home'))){
			if($this->user->permit('product_admin')){
				$this->redirect($this->url->link('catalog/product',  'token=' . $this->session->data['token'], 'SSL'));
			}else if($this->user->permit('sale_orders')){
				$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'));
			}else if($this->user->permit('purchase_orders')){
				$this->redirect($this->url->link('sale/order_purchase', 'token=' . $this->session->data['token'], 'SSL'));
			}else if($this->user->permit('sorting_orders')){
				$this->redirect($this->url->link('sale/pick_stock', 'token=' . $this->session->data['token'], 'SSL'));
			}else if($this->user->permit('refund_orders')){
				$this->redirect($this->url->link('sale/order_refund', 'token=' . $this->session->data['token'], 'SSL'));
			}else if($this->user->permit('self_help_points')){
				$this->redirect($this->url->link('catalog/point', 'token=' . $this->session->data['token'], 'SSL'));
			}else{
				$this->id = 'content';
				$this->template = 'common/no-home-page.tpl';
				$this->layout = 'layout/default';

				$this->render();
			}
			return;
		}
		$this->log_admin->info('p');
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		
   		$searcher = array (
   				'filter_show_date' => date ( 'Y-m-d H:i:s', time () )
   		);
   		$this->load->model('catalog/supply_period');
   		$supplyperiods = $this->model_catalog_supply_period->all($searcher);
   		$this->data['periods'] = $supplyperiods;
   		
		$this->data['token'] = $this->session->data['token'];
		$this->log_admin->info('p');
		$this->load->model('sale/order');
		$this->log_admin->info('p');
		
		
		//获取第三方平台列表
		$this->load->model('catalog/partnercode');
		$partners = $this->model_catalog_partnercode->getAllPartners();
		$this->data['partners'] = $partners;
		
		//$total_sale=$this->model_sale_order->getTotalSales(date('Y-m-d',strtotime("last monday")-86400*7));//date('Y-m-d')
		$total_sales=array();

		foreach($total_sale as $key=> $row){
			$row['total_format']=$this->currency->format($row['total'], $this->config->get('config_currency'));
			$row['partner']=$row['partner_code']?$partners[$row['partner_code']]:'内站'.$row['partner_code'];
			$row['pre_sale']=$row['total']/$row['order_num'];
			$row['pre_sale_format']=$this->currency->format($row['pre_sale'], $this->config->get('config_currency'));
		
			$total_sales[$row['date_added']][$row['partner']]=$row;
		}
	
		
		foreach($total_sale as $key=> $row){
			$total_sales[$row['date_added']]['all']['total']+=$row['total'];
            $total_sales[$row['date_added']]['all']['order_num']+=$row['order_num'];
            $total_sales[$row['date_added']]['all']['total_format']=$this->currency->format($total_sales[$row['date_added']]['all']['total'], $this->config->get('config_currency'));
            $total_sales[$row['date_added']]['all']['pre_sale']=$total_sales[$row['date_added']]['all']['total']/$total_sales[$row['date_added']]['all']['order_num'];
            $total_sales[$row['date_added']]['all']['pre_sale_format']=$this->currency->format($total_sales[$row['date_added']]['all']['pre_sale'], $this->config->get('config_currency'));
            
            $total_sales[$row['date_added']]['all']['partner_code']='all';
            $total_sales[$row['date_added']]['all']['partner']='总计';

		}
		

		$this->data['total_sales'] = $total_sales;

		$this->log_admin->info('p');
		//$total_sale_year=$this->model_sale_order->getTotalSalesByYear(date('Y-m'));//date('Y-m')
		$total=array();
		$total['partner_code']='all';
		$total['partner']='总计';

		foreach($total_sale_year as $key=> $row){
			$row['total_format']=$this->currency->format($row['total'], $this->config->get('config_currency'));
			$row['partner']=$row['partner_code']?$partners[$row['partner_code']]:'内站'.$row['partner_code'];
			$row['pre_sale']=$row['total']/$row['order_num'];
			$row['pre_sale_format']=$this->currency->format($row['pre_sale'], $this->config->get('config_currency'));
            $total['total']+=$row['total'];
            $total['order_num']+=$row['order_num'];
			$total_sale_year[$key]=$row;
		}
		$total['total_format']=$this->currency->format($total['total'], $this->config->get('config_currency'));
		$total['pre_sale']=$total['total']/$total['order_num'];
		$total['pre_sale_format']=$this->currency->format($total['pre_sale'], $this->config->get('config_currency'));
		
		$total_sale_year[]=$total;

		$this->data['total_sale_year'] = $total_sale_year;
		
		$this->log_admin->info('p');
		$this->data['total_order'] = $this->model_sale_order->getTotalOrders(array('filter_date_added'=>date('Y-m-d')));
		
		$this->log_admin->info('p');
		$this->load->model('sale/customer');
		$this->data['total_customer'] = $this->model_sale_customer->getTotalCustomers(array('filter_date_added'=>date('Y-m-d')));
		$this->data['total_customer_approval'] = $this->model_sale_customer->getTotalCustomersAwaitingApproval();
		$this->log_admin->info('p');
		$this->load->model('catalog/review');
		
		$this->data['total_review'] = $this->model_catalog_review->getTotalReviews();
		$this->data['total_review_approval'] = $this->model_catalog_review->getTotalReviewsAwaitingApproval();
		$this->log_admin->info('p');
		/*$this->load->model('sale/affiliate');

		$this->data['total_affiliate'] = $this->model_sale_affiliate->getTotalAffiliates();
		$this->log_admin->info('p');
		$this->data['total_affiliate_approval'] = $this->model_sale_affiliate->getTotalAffiliatesAwaitingApproval();
			*/
		$this->log_admin->info('p');
	
		$this->data['orders'] = array(); 
	
		$data = array(
			'filter_date_added'=>date('Y-m-d',time()),
			'sort'  => 'o.order_id',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);
		
		/*$results = $this->model_sale_order->getOrders($data);
		$this->log_admin->info('p');

    	foreach ($results as $result) {
			$action = array();
			 
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL')
			);
					
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['email'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'action'     => $action
			);
		}*/
		$this->log_admin->info('p');
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');
		
//			$this->model_localisation_currency->updateCurrencies();
		}
		$this->log_admin->info('p');
		$shortcuts=array();
		
		if ($this->user->hasPermission ( '', 'catalog/supply_period' ))
			$shortcuts ['operation'] ['product'] ['supply_period'] = $this->url->link ( 'catalog/supply_period', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/product' ))
			$shortcuts ['operation'] ['product'] ['product'] = $this->url->link ( 'catalog/product', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( 'modify', 'catalog/product' ))
			$shortcuts ['operation'] ['product'] ['add_product'] = $this->url->link ( 'catalog/product/insert', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/category' ))
			$shortcuts ['operation'] ['product'] ['category'] = $this->url->link ( 'catalog/category', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( 'modify', 'catalog/category' ))
			$shortcuts ['operation'] ['product'] ['add_category'] = $this->url->link ( 'catalog/category/insert', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/review' ))
			$shortcuts ['operation'] ['product'] ['review'] = $this->url->link ( 'catalog/review', 'token=' . $this->session->data ['token'], 'SSL' );
		
		if ($this->user->hasPermission ( '', 'sale/coupon' ))
			$shortcuts ['operation'] ['product_sth'] ['coupon'] = $this->url->link ( 'sale/coupon', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'sale/transaction' ))
			$shortcuts ['operation'] ['product_sth'] ['transaction'] = $this->url->link ( 'sale/transaction', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/campaign' ))
			$shortcuts ['operation'] ['product_sth'] ['campaign'] = $this->url->link ( 'catalog/campaign', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'promotion/promotion' ))
			$shortcuts ['operation'] ['product_sth'] ['promotion'] = $this->url->link ( 'promotion/promotion', 'token=' . $this->session->data ['token'], 'SSL' );
		

		if ($this->user->hasPermission ( '', 'design/banner' ))
			$shortcuts ['operation'] ['banner'] ['banner'] = $this->url->link ( 'design/banner', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( 'modify', 'design/banner' ))
			$shortcuts ['operation'] ['banner'] ['banner_9'] = $this->url->link ( 'design/banner/update&banner_id=9', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( 'modify', 'design/banner' ))
			$shortcuts ['operation'] ['banner'] ['banner_17'] = $this->url->link ( 'design/banner/update&banner_id=17', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( 'modify', 'design/banner'  ))
			$shortcuts ['operation'] ['banner'] ['banner_12'] = $this->url->link ( 'design/banner/update&banner_id=12', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( 'modify', 'design/banner'  ))
			$shortcuts ['operation'] ['banner'] ['banner_13'] = $this->url->link ( 'design/banner/update&banner_id=13', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( 'modify', 'design/banner'  ))
			$shortcuts ['operation'] ['banner'] ['banner_14'] = $this->url->link ( 'design/banner/update&banner_id=14', 'token=' . $this->session->data ['token'], 'SSL' );
		
		
		if ($this->user->hasPermission ( '', 'catalog/information' ))
			$shortcuts ['customer_order'] ['customer_order'] ['catalog_information'] = $this->url->link ( 'catalog/information', 'token=' . $this->session->data ['token'], 'SSL' );		
		if ($this->user->hasPermission ( '', 'extension/module' ))
			$shortcuts ['customer_order'] ['customer_order'] ['extension_module'] = $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' );

		if ($this->user->hasPermission ( '', 'sale/order' ))
			$shortcuts ['customer_order'] ['page_sth'] ['order'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'sale/customer' ))
			$shortcuts ['customer_order'] ['page_sth'] ['customer'] = $this->url->link ( 'sale/customer', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/product' ))
			$shortcuts ['customer_order'] ['page_sth'] ['sale/customer_group'] = $this->url->link ( 'sale/customer_group', 'token=' . $this->session->data ['token'], 'SSL' );
		
		if ($this->user->hasPermission ( '', 'setting/store' ))
			$shortcuts ['customer_order'] ['system'] ['setting'] = $this->url->link ( 'setting/store', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/pointdelivery' ))
			$shortcuts ['customer_order'] ['system'] ['catalog_pointdelivery'] = $this->url->link ( 'catalog/pointdelivery', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'extension/sms' ))
			$shortcuts ['customer_order'] ['system'] ['extension_sms'] = $this->url->link ( 'extension/sms', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'extension/shipping' ))
			$shortcuts ['customer_order'] ['system'] ['extension_shipping'] = $this->url->link ( 'extension/shipping', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'extension/payment' ))
			$shortcuts ['customer_order'] ['system'] ['extension_payment'] = $this->url->link ( 'extension/payment', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'extension/total' ))
			$shortcuts ['customer_order'] ['system'] ['extension_total'] = $this->url->link ( 'extension/total', 'token=' . $this->session->data ['token'], 'SSL' );
		
		/*
		if ($this->user->hasPermission ( '', 'setting/custom' ))
			$shortcuts ['operation'] ['product'] ['custom'] = $this->url->link ( 'setting/custom', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'setting/server' ))
			$shortcuts ['operation'] ['product'] ['server'] = $this->url->link ( 'setting/server', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/download' ))
			$shortcuts ['operation'] ['product'] ['download'] = $this->url->link ( 'catalog/download', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'sale/voucher' ))
			$shortcuts ['operation'] ['product'] ['voucher'] = $this->url->link ( 'sale/voucher', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/manufacturer' ))
			$shortcuts ['operation'] ['product'] ['manufacturer'] = $this->url->link ( 'catalog/manufacturer', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/attribute' ))
			$shortcuts ['operation'] ['product'] ['attribute'] = $this->url->link ( 'catalog/attribute', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/attribute_group'' ))
			$shortcuts ['operation'] ['product'] ['attribute_group'] = $this->url->link ( 'catalog/attribute_group', 'token=' . $this->session->data ['token'], 'SSL' );
		if ($this->user->hasPermission ( '', 'catalog/option' ))
			$shortcuts ['operation'] ['product'] ['option'] = $this->url->link ( 'catalog/option', 'token=' . $this->session->data ['token'], 'SSL' );
		*/
		$this->data['shortcuts']=$shortcuts;
		
		
		/*
		$this->data['supply_period'] = $this->url->link('catalog/supply_period', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['add_product'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['add_category'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['transaction'] = $this->url->link('sale/transaction', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['campaign'] = $this->url->link('catalog/campaign', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['promotion'] = $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL');
		
		
		$this->data['catalog_information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['catalog_pointdelivery'] = $this->url->link('catalog/pointdelivery', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['extension_module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['extension_sms'] = $this->url->link('extension/sms', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['extension_shipping'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['extension_payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['extension_total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
		
		
		$this->data['manufacturer'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['attribute'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['attribute_group'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['option'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['coupon'] = $this->url->link('sale/coupon', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['customer_group'] = $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['download'] = $this->url->link('catalog/download', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['voucher'] = $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['custom'] = $this->url->link('setting/custom', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['server'] = $this->url->link('setting/server', 'token=' . $this->session->data['token'], 'SSL');
		
		
		
		$this->data['banner'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['banner_9'] = $this->url->link('design/banner/update&banner_id=9', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['banner_17'] = $this->url->link('design/banner/update&banner_id=17', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['banner_12'] = $this->url->link('design/banner/update&banner_id=12', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['banner_13'] = $this->url->link('design/banner/update&banner_id=13', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['banner_14'] = $this->url->link('design/banner/update&banner_id=14', 'token=' . $this->session->data['token'], 'SSL');
		*/
		$this->log_admin->info('p');
		$this->id = 'content';
		$this->template = 'common/home.tpl';
		$this->layout = 'layout/default';
		$this->log_admin->info('p');
		$this->render();
  	}
	
	public function login() {
		$route = '';

		if (isset($this->request->get['route'])) {
			$part = explode('/', $this->request->get['route']);
			
			if (isset($part[0])) {
				$route .= $part[0];
			}
			
			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
		}
		
		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset',
			'tool/auto_task'
		);	
					
		if (!$this->user->isLogged() && !in_array($route, $ignore)) {
			return $this->forward('common/login');
		}
		
		if (isset($this->request->get['route'])) {
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission',
				'tool/auto_task'
			);
						
			$config_ignore = array();
			
			if ($this->config->get('config_token_ignore')) {
				$config_ignore = unserialize($this->config->get('config_token_ignore'));
			}
				
			$ignore = array_merge($ignore, $config_ignore);
						
//			if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
//				return $this->forward('common/login');
//			}
		} else {
//			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
//				return $this->forward('common/login');
//			}
		}
	}
	
	public function permission() {
		if (isset($this->request->get['route'])) {
			$route = '';
			
			$part = explode('/', $this->request->get['route']);
			
			if (isset($part[0])) {
				$route .= $part[0];
			}
			
			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
			
			$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission',
				'tool/auto_task'
			);			
						
			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
				return $this->forward('error/permission');
			}
		}
	}	

}
?>