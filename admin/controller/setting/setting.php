<?php
class ControllerSettingSetting extends Controller {
	private $error = array();
 
	public function index() {
		$this->load_language('setting/setting'); 
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			ignore_user_abort(TRUE); //如果客户端断开连接，不会引起脚本abort
			ini_set("max_execution_time", 0);
			$this->model_setting_setting->updateSetting('config_pos', $this->request->post);

			if ($this->config->get('config_currency_auto')) {
				$this->load->model('localisation/currency');
		
				$this->model_localisation_currency->updateCurrencies();
			}	
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['token'] = $this->session->data['token'];

		$error_keys=array(
			'warning' => 'error_warning',
			'name' => 'error_name',
			'owner' => 'error_owner',
			'address' => 'error_address',
			'email' => 'error_email',
			'telephone' => 'error_telephone',
			'title' => 'error_title',
			'image_thumb' => 'error_image_thumb',
			'image_popup' => 'error_image_popup',
			'image_product' => 'error_image_product',
			'image_category' => 'error_image_category',
			'image_manufacturer' => 'error_image_manufacturer',
			'image_additional' => 'error_image_additional',
			'image_related' => 'error_image_related',
			'image_compare' => 'error_image_compare',
			'image_wishlist' => 'error_image_additional',
			'image_cart' => 'error_image_cart',
			'error_filename' => 'error_error_filename',
			'catalog_limit' => 'error_catalog_limit',
			'image_wishlist' =>'error_image_wishlist'
		);
		
		foreach ($error_keys as $key => $value) {
			if (isset($this->error[$key])) {
				$this->data[$value] = $this->error[$key];
			} else {
				$this->data[$value] = '';
			}
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
   		    'text'      => $this->language->get('heading_title'),
   			'href'      => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL'),
   		    'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
   	
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

		$post_keys=array(
			'config_name',
			'config_owner',
			'config_address',
			'config_email',
			'config_telephone',
			'config_fax',
			'config_title',
			'config_meta_description',
			'config_share_title',
			'config_share_desc',
			'config_share_linkurl',
			'config_share_image',
			'config_layout_id',
			'config_zone_id',
			'config_template',
			'config_country_id',
			'config_language',
			'config_meta_keyword',
			'config_currency',
			'config_catalog_limit',
			'config_tax',
			'config_customer_group_id',
			'config_customer_price',
			'config_customer_lr_captcha',
			'config_customer_lr_vcode',
			'config_customer_order_captcha',
			'config_customer_order_vcode',	
			'config_customer_approval',
			'config_guest_checkout',
			'config_account_id',
			'config_checkout_id',
			'config_stock_checkout',
			'config_order_nopay_status_id',
			'config_order_status_id',
			'config_logo',
			'config_stock_display',
			'config_icon',
			'config_image_thumb_width',
			'config_image_thumb_height',
			'config_image_popup_width',
			'config_image_popup_height',
			'config_image_product_width',
			'config_image_product_height',
			'config_image_category_width',
			'config_image_category_height',
			'config_image_manufacturer_width',
			'config_image_manufacturer_height',
			'config_image_additional_width',
			'config_image_additional_height',
			'config_image_related_width',
			'config_image_related_height',
			'config_image_compare_width',
			'config_image_compare_height',
			'config_image_wishlist_width',
			'config_image_wishlist_height',
			'config_image_cart_width',
			'config_image_cart_height',
			'config_online_status',
		
			'config_register_reward',
			'config_register_coupon',
			'config_complete_status_id',
			'config_order_refund_status_id',
			'config_order_cancel_status_id',
			'config_order_shipped_status_id',
			'config_order_received_status_id',
		
		
			'config_donation_limit'
		);
		
		foreach ($post_keys as $value) {
			if (isset($this->request->post[$value])) {
				$this->data[$value] = $this->request->post[$value];
			} else {
				$this->data[$value] = $this->config->get($value);
			}
		}
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->data['templates'] = array();
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}					
		
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
				
		$this->load->model('localisation/currency');
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
	
		if (isset($this->request->post['config_commission'])) {
			$this->data['config_commission'] = $this->request->post['config_commission'];
		} elseif ($this->config->has('config_commission')) {
			$this->data['config_commission'] = $this->config->get('config_commission');		
		} else {
			$this->data['config_commission'] = '5.00';
		}
				
		$this->load->model('catalog/information');
		
		$this->data['informations'] = $this->model_catalog_information->getInformations();

		$this->load->model('localisation/stock_status');
		
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();		
		
//		$this->load->model('localisation/return_status');
//		
//		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();	
				
		$this->load->model('tool/image');

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo')) && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);		
		} else {
			$this->data['logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon')) && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);		
		} else {
			$this->data['icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		if ($this->config->get('config_share_image') && file_exists(DIR_IMAGE . $this->config->get('config_share_image')) && is_file(DIR_IMAGE . $this->config->get('config_share_image'))) {
			$this->data['share_image'] = $this->model_tool_image->resize($this->config->get('config_share_image'), 100, 100);
		} else {
			$this->data['share_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		$this->template = 'setting/setting.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_name']) {
			$this->error['name'] = $this->language->get('error_name');
		}	
		
		if ((strlen(utf8_decode($this->request->post['config_owner'])) < 1) || (strlen(utf8_decode($this->request->post['config_owner'])) > 64)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		if ((strlen(utf8_decode($this->request->post['config_address'])) < 1) || (strlen(utf8_decode($this->request->post['config_address'])) > 256)) {
			$this->error['address'] = $this->language->get('error_address');
		}
		
    	if ((strlen(utf8_decode($this->request->post['config_email'])) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['config_email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((strlen(utf8_decode($this->request->post['config_telephone'])) < 1) || (strlen(utf8_decode($this->request->post['config_telephone'])) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

		if (!$this->request->post['config_title']) {
			$this->error['title'] = $this->language->get('error_title');
		}	
		
		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}	
		
		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}	
		
		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}
				
//		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
//			$this->error['image_category'] = $this->language->get('error_image_category');
//		} 
		
		if (!$this->request->post['config_image_manufacturer_width'] || !$this->request->post['config_image_manufacturer_height']) {
			$this->error['image_manufacturer'] = $this->language->get('error_image_manufacturer');
		} 
				
		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}
		
		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}
		
		if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
			$this->error['image_compare'] = $this->language->get('error_image_compare');
		}
		
		if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
			$this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
		}			
		
		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}
		
		if (!$this->request->post['config_catalog_limit']) {
			$this->error['catalog_limit'] = $this->language->get('error_limit');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
			
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function template() {
		$template = basename($this->request->get['template']);
		
		if (file_exists(DIR_IMAGE . 'templates/' . $template . '.png')) {
			$image = HTTPS_IMAGE . 'templates/' . $template . '.png';
		} else {
			$image = HTTPS_IMAGE . 'no_image.jpg';
		}
		
		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}		
		
	
}
?>