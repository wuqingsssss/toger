<?php 
class ControllerAccountAddress extends Controller {
  	public function index() {
		$this->load_language('account/address');
			
		$this->load->model('account/address');
		
		if ($this->customer->isLogged()) {
			if(isset($this->session->data['shipping_address_id']))
				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
			else
				$shipping_address = $this->model_account_address->getAddress($this->customer->getAddressId());
		}else{
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load_language('misc/address');
		
		$this->load->model('account/address');

		if ($this->request->server['REQUEST_METHOD'] == 'GET') {
		    if( isset($this->request->get['address_id'])){
		        $this->session->data['shipping_address_id'] = $this->request->get['address_id'];
		        $this->redirect($this->url->link('account/account', '', 'SSL'));
		    }
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
//				if ($this->request->post['shipping_address'] == 'existing') {
				if ($this->request->post['address_id'] > 0) {
					if (!isset($this->request->post['address_id'])) {
						$json['error']['warning'] = $this->language->get('error_address');
					}
						
					if ($this->request->post['address_id']) {
						$this->customer->setAddress($this->request->post['address_id']);
					}
						
					$this->session->data['shipping_address_id'] = $this->request->post['address_id'];

				
					
					$address_info = $this->model_account_address->getAddress($this->request->post['address_id']);
					
					
							
					if ($address_info) {
						$this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
					}
					
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['shipping_method']);
				
				}

//				if ($this->request->post['shipping_address'] == 'new') {
				if ($this->request->post['address_id'] == '0') {
					if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
						$json['error']['firstname'] = $this->language->get('error_firstname');
					}
						
					
					if(empty($this->request->post['shipping_data'])){
						
						$json['error']['shipping_data'] = $this->language->get('error_address_1');
					}
					
					if ((strlen(utf8_decode($this->request->post['address_1'])) < 1) || (strlen(utf8_decode($this->request->post['address_1'])) > 64)) {
						$json['error']['address_1'] = $this->language->get('error_address_1');
					}
						
					$this->request->post['address_1']=$this->request->clean_address($this->request->post['address_1']);
					$this->request->post['address_2']=$this->request->clean_address($this->request->post['address_2']);
					
					
//					if ((strlen(utf8_decode($this->request->post['city'])) < 2) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {
//						$json['error']['city'] = $this->language->get('error_city');
//					}
						
					if ((strlen(utf8_decode($this->request->post['mobile'])) < 1)) {
						$json['error']['mobile'] = $this->language->get('error_mobile');
					}else if(strlen(utf8_decode($this->request->post['mobile'])) != 11){
						$json['error']['mobile'] = $this->language->get('error_mobile_length');
					}
						
					$this->load->model('localisation/country');
						
					$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
						
//					if ($country_info && $country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {
//						$json['error']['postcode'] = $this->language->get('error_postcode');
//					}

					if ($this->request->post['zone_id'] == '') {
						$json['error']['zone'] = $this->language->get('error_zone');
					}
					
					if ($this->request->post['city_id'] == '') {
						$json['error']['city'] = $this->language->get('error_city');
					}
						
					if (!$json) {
						$this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($this->request->post);

						$this->customer->setAddress($this->session->data['shipping_address_id']);

						if ($this->cart->hasShipping()) {
							$this->tax->setZone($this->config->get('config_country_id'), $this->request->post['zone_id']);
						}

						unset($this->session->data['shipping_methods']);
						unset($this->session->data['shipping_method']);
					}
						
			}
		}
		

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['navtop'] =  $this->getChild('module/navtop',array('navtop'=>array(
		    'left'=>'<a class="return" href="javascript:_.go();"></a>',
		    'center'=>'<a class="locate fz-15">'.$this->language->get('heading_title').'</a>',
		    'right'=>'<a class="fz-13" id="address-submit" href="javascript:selectAddress();">确定</a>'
		),"wechathidden"=>1, "right_show" =>1 ));
		
		$this->data['navtop2'] =  $this->getChild('module/navtop',array('navtop'=>array(
		    'left'=>'<a class="return" href="javascript:pages.nextPage(\'right\',1);"></a>',
		    'center'=>'<a class="locate fz-15">'.$this->language->get('heading_title2').'</a>',
		    'right'=>''
		),"wechathidden"=>1));
		
		$this->data['navtop3'] =  $this->getChild('module/navtop',array('navtop'=>array(
		    'left'=>'<a class="return" href="javascript:pages.nextPage(\'right\',2);"></a>',
		    'center'=>'<a class="locate fz-15">'.$this->language->get('heading_title3').'</a>',
		    'right'=>''
		),"wechathidden"=>1 ));
		
		$this->data['type'] = 'shipping';

		if (isset($this->session->data['shipping_address_id'])) {
			$this->data['address_id'] = $this->session->data['shipping_address_id'];
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}

		if(isset($this->session->data['order_id'])){
			$this->load->model('checkout/order');
			$this->load->model('account/address');
			$shipping_address = $this->model_account_address->getAddress($this->data['address_id']);
			$this->model_checkout_order->updateOrderAddrress($this->session->data['order_id'],$shipping_address);
			
			
		}
		
		$this->data['addresses'] = $this->model_account_address->getAddresses();

		$this->data['country_id'] = $this->config->get('config_country_id');
			
		$this->load->model('localisation/country');
			
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->data['action'] = $this->url->link('account/address', '');
		$this->load->service('baidu/point');
		$this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping_method.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/shipping_method.tpl';
		} else {
			$this->template = 'default/template/checkout/shipping_method.tpl';
		}

		$this->setback(true, $this->url->link("account/account", '' ,'SSL'));
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer35',
			'common/header40'
		);
		
		$this->response->setNocache(); //这个no-store加了之后，Firefox下有效
		$this->response->setOutput($this->render());
  	}
  	
  	/**
  	 *  删除地址
  	 */
  	public function delete() {
  	    $this->load->model('account/address');
  	    $json=array();
	
	  	if($this->request->server['REQUEST_METHOD'] == 'POST'){//开始处理post请求
	  	    if(isset($this->request->post['data-id'])){
                $address_id = $this->request->post['data-id'];
                $this->model_account_address->deleteAddress($address_id);
                
                //如果当前地址为被删除，清除地址选择
                if($this->session->data['shipping_address_id'] == $address_id){
                    unset($this->session->data['shipping_address_id']);
                }
	  	    }
	  	    else{
	  	        $json['error']['warning'] = "删除请求失败！";
	  	    }
	  	}  	
	  	
	    $this->response->setOutput(json_encode($json));
  	}
  	
  	 
  	public function insert() {
 		$this->load_language('misc/address');
        $this->load->model('account/address');
  	    $json=array();
	
  	    if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

  	    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
  	    		$json['error']['warning'] = $this->language->get('error_firstname');
  	    	}
  	    	
  	    	if(empty($this->request->post['shipping_data'])){
  	    	
  	    		$json['error']['warning'] = $this->language->get('error_address_1');
  	    	}
  	    	
  	    	if ((strlen(utf8_decode($this->request->post['address_1'])) < 1) || (strlen(utf8_decode($this->request->post['address_1'])) > 64)) {
  	    		$json['error']['warning'] = $this->language->get('error_address_1');
  	    	}
  	    	
  	    	if ((strlen(utf8_decode($this->request->post['mobile'])) < 1)) {
  	    		$json['error']['warning'] = $this->language->get('error_mobile');
  	    	}else if(strlen(utf8_decode($this->request->post['mobile'])) != 11){
  	    		$json['error']['warning'] = $this->language->get('error_mobile_length');
  	    	}
  	    	
  	    	$this->request->post['address_1']=$this->request->clean_address($this->request->post['address_1']);
  	    	$this->request->post['address_2']=$this->request->clean_address($this->request->post['address_2']);
  	    	
  	    	if ($this->request->is_address($this->request->post['address_1'])==false) {
  	    		$json['error']['warning'] = '地址格式不正确，只能填写汉字字母下划线（）【】#';
  	    	}
  	    	
  	    	if(!$this->request->post['address_1_poi'])
  	    	{
  	    		$this->load->service('baidu/geocoder');
  	    		$res=$this->service_baidu_geocoder->hgetLocation($this->request->post['address_1']);
  	    		if($res['status']=='0'){
  	    		$this->request->post['address_1_poi']= $res['result']['location']['lng'].','.$res['result']['location']['lat'];
  	    		}
  	    		else 
  	    		{
  	    		$json['error']['warning'] = $this->language->get('您的地址可能不正确,请重新填写');
  	    		}
  	    	}
  	    	else
  	    	{
  	    		//$this->log_sys->info($this->request->post['address_1_poi']);
  	    		$poi=json_decode(htmlspecialchars_decode($this->request->post['address_1_poi']),1);
  	    		$this->log_sys->info($poi);
  	    		$this->request->post['address_1_poi']=$poi['location']['lng'].','.$poi['location']['lat'];
  	    	}

  	    	if (!$json) {
  	    	  			     	    			
                if((int)$this->request->post['address_id']>0){
                   $this->model_account_address->editAddress($this->request->post['address_id'],$this->request->post);
                   $this->session->data['shipping_address_id']=$this->request->post['address_id'];
                }
                else
                {  
                    if(!$this->model_account_address->existAddress($this->request->post)){
                         $this->session->data['shipping_address_id']= $this->model_account_address->addAddress($this->request->post);
                    }
                    else
                    {
                   	    $json['error']['warning'] ='该地址已经存在';
                    }
                }
                   
                if(!$json['error']){
      	       
          	        $this->customer->setAddress($this->session->data['shipping_address_id']);
          	       
          	      	$this->session->data['success'] = $this->language->get('text_insert');
          	      	$this->data = array();
          	      	$this->data['address'] = $this->model_account_address->getAddress( $this->session->data['shipping_address_id']);
          	      	$this->data['action'] = $this->url->link('account/address', '');
     
          	      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/address_item.tpl')) {
          	      	    $this->template = $this->config->get('config_template') . '/template/account/address_item.tpl';
          	      	} else {
          	      	    $this->template = 'default/template/account/address_item.tpl';
          	      	}
          	        $json['address'] = $this->data['address'];
          	      	$json['output'] = $this->render();
                }
  	    	}
  	    }
  	
  	    $this->response->setOutput(json_encode($json));
  	}
}
?>