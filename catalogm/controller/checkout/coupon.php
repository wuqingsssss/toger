<?php 
class ControllerCheckoutCoupon extends Controller {
	
	public function index() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/coupon', '', 'SSL');

	  		$this->redirect($this->url->link('account/login', '', 'SSL')); 
    	}    	
		
		$this->load_language('checkout/checkout');
		// 页面头
		$header_setting =  array('left'    =>  array( href => "javascript:pages.switchTo(0);",
                                        		     text => ""),
                                 'center'  =>  array( href => "#",
		                                             text => $this->language->get('coupon_title')),
		                         'name'    =>  $this->language->get('coupon_title')
		);
			
		$this->data['header'] = $this->getChild('module/header', $header_setting);
		
      	$this->data['success']='';
      	$this->data['error_warning'] ='';
      	
      	if(isset($this->session->data['success'] )){
      	    $this->data['success'] = $this->session->data['success'];
      	    unset($this->session->data['success']);
      	}
      	if(isset($this->session->data['error'] )){
      	    $this->data['error_warning'] = $this->session->data['error'];
      	    unset($this->session->data['error']);
      	}
      	
      	
      	if (isset($this->error['warning'])) {
      		$this->data['error_warning'] = $this->error['warning'];
      	} 
      	
 
      	$data = array();
      	$this->load->model('account/coupon');
      	
       	$results = $this->model_account_coupon->getCouponsByExceptR($this->customer->getId());
      
      	$this->data['text_add_coupon'] = $this->language->get('text_add_coupon');
      	$this->data['coupons']= array();
      	$coupon_total = 0;
    	foreach ($results as $result) {
    	    $coupon_total ++;
    
    	    if( $result['type'] == 'F'){
    	        $discount = $this->currency->format($result['discount']);
    	    }
    	    else if($result['type'] == 'P'|| $result['type'] == 'Q'){
    	        $discount = sprintf("%.1f%%",$result['discount']);
    	    }
    	    else{
    	        $discount = 'Unknown';
    	    }
			$this->data['coupons'][] = array(
				'coupon_id'  => $result['coupon_id'],
				'name'       => $result['name'],
			    'coupon_customer_id'  => $result['coupon_customer_id'],
				//'code'       => $result['code'],
				//'coupon_category'   => $coupon_cate_links,
//				'coupon_categories'   => $coupon_cate,
//				'href' =>	$coupon_cate_link,
				//'coupon_class_name'   => $result['coupon_class_name'],
				'total'      => $this->currency->format($result['total']),
				'discount'      => $discount,
			    'usage'      => $result['usage'],
				'used'      => $result['used'],
//				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
//				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
			    'date_add'   => date($this->language->get('date_format_short'), strtotime($result['date_add'])),
			    'date_limit' => $result['date_limit'],
//				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
//				'status_info'     => $this->getCouponStatusText($result)
				);
		}
									
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/coupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/coupon.tpl';
		} else {
			$this->template = 'default/template/checkout/coupon.tpl';
		}
							
		$this->render();		
	}
	
	
	private function getCouponStatusText($coupon_info){
		
		//$code=$this->model_account_coupon->getCouponStatusCode($coupon_customer_id);
		if($coupon_info['used'] == '1'){
		    return "已使用";
		}
		else{
	       $today = date('Y-m-d', time());
	       
	       if( $today < $coupon_info['date_start']){
	           return "未生效";
	       }
	       elseif($today >$coupon_info['date_end']){
	           return "已失效";
	       }
	       else{
	           return "有效";
	       }
		}
	    
//		switch(){
//			case 'used' : return "已使用"; break;
//			case 'valid' : return "有效"; break;
//			case 'invalid' : return "已失效"; break;
//			default: return 'N/A'; break;
//		}
	}
	
	
	private function validate() {
		
		if (utf8_strlen($this->request->post['coupon_code']) <1) {
			$this->error['warning'] = $this->language->get('error_coupon_required');
		}
	
		$this->load->model('account/coupon');
		if ($this->model_account_coupon->checkCoupon($this->request->post['coupon_code'])) {
			$this->error['warning'] = $this->language->get('error_coupon_exist');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function update() {
		$this->language->load('account/wishlist');
		
		$json = array();

		if (!isset($this->session->data['wishlist'])) {
			$this->session->data['wishlist'] = array();
		}
				
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->model('catalog/product');
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {
			if (!in_array($this->request->post['product_id'], $this->session->data['wishlist'])) {	
				$this->session->data['wishlist'][] = $this->request->post['product_id'];
			}
			 
			if ($this->customer->isLogged()) {			
				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));				
			} else {
				$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));				
			}
			
			$json['total'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}	
		
		$this->response->setOutput(json_encode($json));
	}		
}
?>