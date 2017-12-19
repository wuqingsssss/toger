<?php 
class ControllerAccountCoupon extends Controller {
	private $error = array();
	
	public function index() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/coupon', '', 'SSL');

	  		$this->redirect($this->url->link('account/login', '', 'SSL')); 
    	}    	
		
		$this->load_language('account/coupon');
		
		$this->load->model('account/coupon');
		
		$this->document->setTitle($this->language->get('heading_title'));	
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {

			$coupon_info=$this->model_account_coupon->getCouponByCode($this->request->post['coupon']);
			
			if($coupon_info&&$coupon_info['free_get']){
		   try{
		        $ret = $this->model_account_coupon->addCoupon($this->request->post['coupon'], $this->customer->getId());    
		        if($ret>0){
		            $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));
		        }
		        else{
		            $this->log_sys->info($ret);
		            
		            $this->session->data['error'] = sprintf($this->language->get('error_coupon_exist'), $this->language->get('heading_title'));
		        }
		    } catch (Exception $e) {
		        $this->session->data['errors'] = "error";
		    }
			
			}
			else 
			{
				    $this->session->data['error'] = sprintf($this->language->get('error_coupon_exist'), $this->language->get('heading_title'));
			}
		
		
		    $this->redirect($this->url->link('account/coupon', '', 'SSL'));
		}
		
      	
		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/coupon'),
        	'separator' => $this->language->get('text_separator')
      	);
      	
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
      	
      	//$this->data['success'] = $this->language->get('text_success');
      	
      /*	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      		$this->model_account_coupon->addCoupon($this->request->post['coupon']);
      			
      		$this->data['success'] = $this->language->get('text_success');
      	
      	}*/
      	
      	if (isset($this->error['warning'])) {
      		$this->data['error_warning'] = $this->error['warning'];
      	} 
      	
      	if (isset($this->request->get['page'])) {
      		$page = $this->request->get['page'];
      	} else {
      		$page = 1;
      	}
      	$data = array();
		
       	$results = $this->model_account_coupon->getCouponsByCustomerAll($this->customer->getId());
      
      	$this->data['text_add_coupon'] = $this->language->get('text_add_coupon');
      	$this->data['coupons']= array();
      	$coupon_total = 0;
    	foreach ($results as $result) {
    	    $coupon_total ++;
    		/*$coupon_cate='';
    		$coupon_cate_all='';
    		$coupon_categories  = $this->model_account_coupon->getCouponCategorys($result['coupon_id']);
    		
    		$coupon_cate_links=array();
    		foreach ($coupon_categories as $category) {
    			$coupon_cate_links[]=array(
    				'name' => $category['name'],
    				'href' => $this->url->link('product/category', 'path=' . $category['category_id'] )
    			);
    		}*/
    		
    		/*$coupon_cate_link='';
    		
    		$i=0;
    		foreach ($coupon_categories as $category) {
    			if($i==0&&count($coupon_categories)>1){
    				$coupon_cate_all=$category['name'].'...';
    				$coupon_cate_link=$this->url->link('product/category', 'path=' . $category['category_id'] );
    			}else{
    				$coupon_cate_all=$category['name'];
    				$coupon_cate_link=$this->url->link('product/category', 'path=' . $category['category_id'] );
    			}
    			if($i>0){
    				$coupon_cate.=','.$category['name'];
    			}else{
    				$coupon_cate.=$category['name'];
    			}
    			$i++;
    		}*/
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
				//'code'       => $result['code'],
				//'coupon_category'   => $coupon_cate_links,
//				'coupon_categories'   => $coupon_cate,
//				'href' =>	$coupon_cate_link,
				//'coupon_class_name'   => $result['coupon_class_name'],
				'total'      => $this->currency->format($result['total']),
				'discount'      => $discount,
			    'usage'      => $result['usage'],
//				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
//				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
			    'date_add'   => date($this->language->get('date_format_short'), strtotime($result['date_add'])),
			    'date_limit' => $result['date_limit'],
//				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
//				'status_info'     => $this->getCouponStatusText($result)
				);
		}
									
/*		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_catalog_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/coupon', 'page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render(); */
		$this->data['action'] = $this->url->link('account/coupon', '', 'SSL');
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/coupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/coupon.tpl';
		} else {
			$this->template = 'default/template/account/coupon.tpl';
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
	
	public function add(){
		
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