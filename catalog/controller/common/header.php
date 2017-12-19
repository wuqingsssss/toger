<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->load_language('common/header');
		$this->data['title'] = $this->document->getTitle();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}
		
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->language->load('common/header');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_IMAGE;
		} else {
			$server = HTTP_IMAGE;
		}	
				
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		$this->data['name'] = $this->config->get('config_name');
		$this->data['config_title'] = $this->config->get('config_title');
		$this->data['template'] = $this->config->get('config_template');
		
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}
		
		// Calculate Totals
		$total_data = array();					
					$total['promotion'] = 0;
			$total['general'] = 0;
			$total['fee']=0;
			$total['discount']=0;
			$total['total']=0;
		$taxes = $this->cart->getTaxes();
		
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {						 
			$this->load->model('setting/extension');
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);

				}
			}
		}
		
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_cart'] =  sprintf($this->language->get('text_cart'),$this->url->link('checkout/cart', '', 'SSL'));
		$this->data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total['total']));
		
		//print_r($this->language->get('text_items'));
		$display_name = $this->customer->getDisplayName();
		if(empty($display_name)){
		    $display_name = $this->customer->getMaskedMobile();
		}
		
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
    	$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $display_name, $this->url->link('account/logout', '', 'SSL'));
    	
		$this->data['text_my_account'] = sprintf($this->language->get('text_my_account'), $this->url->link('account/account', '', 'SSL'));		
		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$this->data['news'] = $this->url->link('information/news', '', 'SSL');
		$this->data['login'] = $this->url->link('account/login', '', 'SSL');
		$this->data['register'] = $this->url->link('account/register', '', 'SSL');
		$this->data['trade'] = $this->url->link('project/trade', '', 'SSL');
		$this->data['finance'] = $this->url->link('information/finance', '', 'SSL');
		$this->data['qixi'] = $this->url->link('account/login', '', 'SSL');
		
		
	
		
		if (isset($this->request->get['filter_name'])) {
			$this->data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$this->data['filter_name'] = '';
		}
		
		$this->data['action'] = $this->url->link('common/home');

		if (!isset($this->request->get['route'])) {
			$this->data['redirect'] = $this->url->link('common/home');
		} else {
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data));
			}			
			
			$this->data['redirect'] = $this->url->link($route, $url);
		}

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
			$this->session->data['language'] = $this->request->post['language_code'];
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
    	}		
						
		$this->data['language_code'] = $this->session->data['language'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = array();
		
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image']
				);	
			}
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['currency_code'])) {
      		$this->currency->set($this->request->post['currency_code']);
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
				
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
   		}
						
		$this->data['currency_code'] = $this->currency->getCode(); 
		
		$this->load->model('localisation/currency');
		 
		 $this->data['currencies'] = array();
		 
		$results = $this->model_localisation_currency->getCurrencies();	
		
		foreach ($results as $result) {
			if ($result['status']) {
   				$this->data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']				
				);
			}
		}
		
		$this->children[]='module/boss_megamenu';

		
		//区域选择触发条件,不存在cookie或者cookie 值不正确
		$this->data['show_location_tipbox']=false;	
		//增加取菜点名称显示
		if(isset($this->request->cookie['select_point_id']) &&!empty($this->request->cookie['select_point_id'])){

			$select_point_id=$this->request->cookie['select_point_id'];
			$this->load->model('catalog/point');
			$point_info=$this->model_catalog_point->getPointByCode($select_point_id);
			if($point_info){
				$this->data['show_location_tipbox']=false;
				$this->data['point_text']='自提['.$point_info['name'].']';
			}else{
				$this->data['point_text']='配送方式';
				$this->data['show_location_tipbox']= true;
			}
		}elseif(isset($this->request->cookie['select_meishiarea'])&&$this->request->cookie['select_meishiarea'])
		{
			
			$select_meishiarea=json_decode(htmlspecialchars_decode(htmlspecialchars_decode($this->request->cookie['select_meishiarea'])),1);
			
			$this->data['show_location_tipbox']=false;
			$this->data['point_text']=$select_meishiarea['title'];
			
			$this->session->data['shipping_method']['title']=$select_meishiarea['title'].$select_meishiarea['address'];
		}
		else
		{
			$this->data['show_location_tipbox']= true;
			$this->data['point_text']='配送方式';
		}

		$this->data['body_id']=$this->document->getPageId();
		$this->data['role']=$this->document->getPageRole();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
    	$this->render();
	} 	
}
?>