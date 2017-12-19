<?php 
class ControllerTotalCoupon extends Controller {
	public function index() {
		$this->language->load('total/coupon');
		
		$this->data['module_title'] = $this->language->get('heading_title');
		$coupon_total = $this->cart->getSubTotal();
	//	$this->data['heading_title'].= sprintf($this->language->get('text_coupon_total'), $this->currency->format($coupon_total['general']));
		$this->data['entry_coupon'] = $this->language->get('entry_coupon'); 
		$this->data['entry_coupon_info'] = $this->language->get('entry_coupon_info');
		$this->data['button_coupon'] = $this->language->get('button_coupon');
				
//		if (isset($this->session->data['coupon'])) {
//			$this->data['coupon'] = $this->session->data['coupon'];
//		} else {
//			$this->data['coupon'] = '';
//		}

        $customer_id=$this->customer->getId();
        $this->load->model('account/coupon');

        $coupon_info = $this->model_account_coupon->getCouponsByExceptR($customer_id);
 
        if($this->session->data['total_discount']){
        	$coupon_total_discount['name']=$this->session->data['total_discount'];
        	$coupon_total_discount['coupon_customer_id']='total_discount';
        	$coupon_total_discount['used']=0;

            array_unshift($coupon_info,$coupon_total_discount);
        
        }
        $this->data['coupon_info']= $coupon_info;
  
       // var_dump($this->data['coupon_info']);


        $this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/coupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/total/coupon.tpl';
		} else {
			$this->template = 'default/template/total/coupon.tpl';
		}
					
		$this->render();
  	}
		
	public function calculate() {
		$this->language->load('total/coupon');
		
		$json = array();
		
		if (!$this->cart->hasProducts()) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	
				
		if (isset($this->request->post['coupon'])) {
			$this->load->model('checkout/coupon');
	
			if($this->request->post['coupon']=='total_discount'){
				
				unset($this->session->data['coupon']);
				$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
			}
			else 
			{
			$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);			
			
			if ($coupon_info) {			
				$this->session->data['coupon'] = $this->request->post['coupon'];
				
//				$this->session->data['success'] = $this->language->get('text_success');
				
				$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
			} else {
                //TODO:强化显示coupon的错误
				$json['error'] = $this->language->get('error_coupon');
			}
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
	}
}
?>