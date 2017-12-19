<?php 
class ControllerAccountSuccess extends Controller {  
	public function index() {
    	$this->load_language('account/success');
  
    	$this->document->setTitle($this->language->get('heading_title'));

    	if ($this->customer->isLogged()) {
    	    $this->load->model('account/coupon');
    	    
    	    //查询是否存在活动定义
    	    if(isset($this->session->data['promo'])){
	            $result = $this->model_account_coupon->getPacketByCampaignCode('register', $this->session->data['promo']);
	        }
	        
	        // 未定义活动码或者活动码不明的，寻找通用定义
	        if(!$result || !isset($this->session->data['promo'])){
	           $result = $this->model_account_coupon->getPacketByCampaignCode('register', 'normal');
	        }
	        
	        if($result) {
	        	// 如果活动代码设置了礼包
	            if(!empty($result['packet_id'])){
    	            $ret =  $this->model_account_coupon->addPacket2Customer($result['packet_id'], $this->customer->getId());
    	            if($ret==1){  //追加成功
    	                // 更新红包名称
    	                $this->data['packet'] = $result['name'];                
    	            }
	            }
	                
	        }
	        
	        //追加活动记录
	        if(isset($this->session->data['promo'])){
	            $this->model_account_coupon->addCampaignHistory($this->session->data['promo'], $this->customer->getId());
	        }
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
        	'text'      => $this->language->get('text_success'),
			'href'      => $this->url->link('account/success'),
        	'separator' => $this->language->get('text_separator')
      	);

      	/*
    	$this->data['heading_title'] = $this->language->get('heading_title');

	/*	if (!$this->config->get('config_customer_approval')) {
    		
		} else {
			$this->data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), $this->url->link('information/contact'));
		}*/
		
		$this->data['text_message'] = sprintf($this->language->get('text_message'),$this->customer->getDisplayName(), $this->url->link('account/account'),$this->url->link('information/information&information_id=45'));
		
		
    	$this->data['button_continue'] = $this->language->get('button_continue');
		
		if ($this->cart->hasProducts()) {
			$this->data['continue'] = $this->url->link('checkout/cart', '', 'SSL');
		} else {
			$this->data['continue'] = $this->url->link('account/account', '', 'SSL');
		}
if($this->session->data['redirect'])
{			
$this->response->addHeader("refresh:3;url=".$this->session->data['redirect']);
}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/success.tpl';
		} else {
			$this->template = 'default/template/account/success.tpl';
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
}
?>