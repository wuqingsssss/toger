<?php
class ModelShippingPickupaddr extends Model {
	function getQuote() {
		$this->load->language('shipping/pickupaddr');
		
		
		$status = true;

		$method_data = array();

		if ($status) {
			$quote_data = array();
			
			/*	
			$addrs = $this->config->get('addrs');
				
			foreach ($addrs as $addr) {
				if($addr['status']){
					$quote_data['pickupaddr_'.$addr['sort_order']] = array(
						   'code'         => 'pickupaddr.pickupaddr_'.$addr['sort_order'],
						   'title'        => $addr['title'].'[ '.$addr['addr'].' ]',
						   'cost'         => 0.00,
						   'tax_class_id' => 0,
						   'text'         => ''//$this->currency->format(0.00)
					);
				}

			}
			*/

			/*$addrs = $this->config->get('addrs');
				
			foreach ($addrs as $addr) {
				if($addr['status']){
					$quote_data['pickupaddr_'.$addr['sort_order']] = array(
						   'code'         => 'pickupaddr.pickupaddr_'.$addr['sort_order'],
						   'title'        => $addr['title'].'[ '.$addr['addr'].' ]',
						   'cost'         => 0.00,
						   'tax_class_id' => 0,
						   'text'         => ''//$this->currency->format(0.00)
					);
				}

			}*/

            $this->load->model('catalog/point');

			/*根据区域和商圈来显示所属取菜点

			$filter=array();
			
			if(isset($this->request->cookie['point_city_id']) && $this->request->cookie['point_city_id']){
				$filter['filter_point_city_id']=$this->request->cookie['point_city_id'];
			}else if($this->customer->isLogged()){
				$filter['filter_point_city_id']=$this->customer->getLocationCity();
			}else{
				$filter['filter_point_city_id']=$this->config->get('default_city_id');
				$filter['filter_point_city_id']='390'; //需要替换为设置选项
			}
			

			if(isset($this->request->cookie['point_cbd_id']) && $this->request->cookie['point_cbd_id']){
				$filter['filter_point_cbd_id']=$this->request->cookie['point_cbd_id'];
			}else if($this->customer->isLogged()){
				$filter['filter_point_cbd_id']=$this->customer->getLocationCbd();
			}else {
				$filter['filter_point_cbd_id']=$this->config->get('default_cbd_id');
				$filter['filter_point_cbd_id']= 5;
			}
			
			$point_results=$this->model_catalog_point->getPoints($filter);
			
			foreach($point_results as $result){
				if($result['status']){
					$quote_data['pickupaddr_'.$result['point_id']] = array(
						   'point_id'         => $result['point_id'],
						   'code'         => 'pickupaddr.pickupaddr_'.$result['point_id'],
						   'title'        => $result['name'].'[ '.$result['address'].' ]',
						   'name'        => $result['name'],
						   'address'        => $result['address'],
						   'cost'         => 0.00,
						   'tax_class_id' => 0,
						   'text'         => ''//$this->currency->format(0.00)
					);
				}
			}
			*/

            /**
            自提点可以显示当前的选择和历史的另外2个
             */

            $quote_data = array();
            //显示当前选择的自提点
            if(isset($this->request->cookie['select_point_id']) && $this->request->cookie['select_point_id']){
                $current_point_code=$this->request->cookie['select_point_id'];

                $result=$this->model_catalog_point->getPointByCode($current_point_code);               
                 
                if($result && $result['status']){
                    $quote_data['pickupaddr_'.$result['point_id']] = array(
                        'point_id'         => $result['point_id'],
                    	'device_code'      => $result['device_code'],
                    	'point_code'       => $result['point_code'],
                        'code'         => 'pickupaddr.pickupaddr_'.$result['point_id'],
                        'title'        => $result['name'].'[ '.$result['address'].' ]',
                        'name'        => $result['name'],
                        'address'        => $result['address'],
                        'cost'         => 0.00,
                        'tax_class_id' => 0,
                        'text'         => ''//$this->currency->format(0.00)
                    );
                }
            }
            elseif($this->customer->getShippingMethod())
            {//如果cookies没有获取到则从用户表里读取最近的一次更新的自提点
            	$shipping = explode('.', $this->request->post['shipping_method']);	
            	$pickup_address=explode('_', $shipping[1]);
            	$point_id=$pickup_address[1];
            	$current_point_id=$point_id;
            }
            else
            {
                $current_point_id=null;
            }

            //显示最新历史记录的自提点
            $this->load->model('checkout/order');

            $filter=array(
                'filter_point_id' => $current_point_id,
                'filter_customer_id' => $this->customer->getId()
            );

            $points_result=$this->model_checkout_order->getHistoryPoints($filter);

            foreach($points_result as $point_result){
                $result=$this->model_catalog_point->getPoint($point_result['shipping_point_id']);

                if($result && $result['status']){
                    $quote_data['pickupaddr_'.$result['point_id']] = array(
                        'point_id'         => $result['point_id'],
                    	'device_code'      => $result['device_code'],
                    	'point_code'       => $result['point_code'],
                        'code'         => 'pickupaddr.pickupaddr_'.$result['point_id'],
                        'title'        => $result['name'].'[ '.$result['address'].' ]',
                        'name'        => $result['name'],
                        'address'        => $result['address'],
                        'cost'         => 0.00,
                        'tax_class_id' => 0,
                        'text'         => ''//$this->currency->format(0.00)
                    );
                }
            }


			/*$this->data['points']=array();
			
			foreach($point_results as $result){
				$this->data['points'][]=array(
					'point_id' => $result['point_id'],
					'name' => $result['name'],
					'address' => $result['address'],
					'telephone' => $result['telephone'],
				);	
			}*/
			

			$method_data = array(
        		'code'       => 'pickupaddr',
        		'title'      => $this->language->get('text_title'),
      			'description'  => $this->config->get('addr_description'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('addr_sort_order'),
        		'error'      => false
			);
		}

		return $method_data;
	}
}
?>