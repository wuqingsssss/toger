<?php
class ControllerPointHome extends Controller { 
	public function index() {
        $city_id=0;
        $cbd_id=0;

		$this->data['city_id']=0;
		$this->data['cbd_id']=0;
		
		if(isset($this->request->cookie['point_city_id']) && $this->request->cookie['point_city_id']){
            $city_id=(int)$this->request->cookie['point_city_id'];
		}else if($this->customer->isLogged()){
            $city_id=$this->customer->getLocationCity();
		}
		
		if(isset($this->request->cookie['point_cbd_id']) && $this->request->cookie['point_cbd_id']){
            $cbd_id=(int)$this->request->cookie['point_cbd_id'];
		}else if($this->customer->isLogged()){
            $cbd_id=$this->customer->getLocationCbd();
		}





		
		if(isset($this->request->get['refresh']) && ($this->request->get['route']=='checkout/checkout')){
			$this->data['action'] = $this->url->link('point/home/location&refresh=auto');
		}else if(isset($this->request->get['refresh']) && ($this->request->get['refresh']=='auto')){
			$this->data['action'] = $this->url->link('point/home/location&refresh=auto');
		}else{
			$this->data['action'] = $this->url->link('point/home/location');
		}

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/point/home.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/point/home.tpl';
        } else {
            $this->template = 'default/template/point/home.tpl';
        }

    	$this->response->setOutput($this->render());
	}

    public  function initdata(){
        header("Access-Control-Allow-Origin: *");

        $json=array();

        $this->load->model('localisation/city');

        $cities=$this->model_localisation_city->getCitiesByZoneId($this->config->get('config_zone_id'));

        foreach($cities as $index => $city){
            $cbds=$this->getCbdsByCityId($city['city_id']);

            if($cbds){
                $cities[$index]['cbds']=$this->getCbdsByCityId($city['city_id']);
            }else{
                unset($cities[$index]);
            }

        }



        $json=$cities;

        $this->load->library('json');

        $this->response->setOutput(Json::encode($json));
    }

    protected  function getCbdsByCityId($city_id){
        $this->load->model('localisation/cbd');

        $results = $this->model_localisation_cbd->getCbdsByCityId($city_id);

        foreach($results as $index => $result){
            $points=$this->getPointsByCbdId($result['id']);
            if($points){
                $results[$index]['points']=$this->getPointsByCbdId($result['id']);
            }else{
                unset($results[$index]);
            }
        }

        return $results;
    }

    protected function getPointsByCbdId($cbd_id){
        $this->load->model('catalog/point');

        $filter = array();
        $points = array();

        $filter['filter_point_cbd_id'] = $cbd_id;

        $this->data['points'] = array();

        $point_results = $this->model_catalog_point->getPoints($filter);

        foreach ($point_results as $result) {
            if ($result['status']) {
                $points[] = array(
                    'point_id' => $result['point_id'],
                    'name' => $result['name'],
                    'address' => $result['address'],
                    'business_hour' => $result['business_hour'],
                    'telephone' => $result['telephone'],
                );
            }
        }

        return $points;
    }


	public function location(){
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			
			if(isset($this->request->post['point_id'])){
				$point_id=$this->request->post['point_id'];
				$meishiareacode=trim($this->request->post['meishiareacode']);
				$select_title=trim($this->request->post['select_title']);
				
                setcookie("select_point_id",$point_id,time()+3600*24*7);
                setcookie("select_meishiarea",$meishiareacode,time()+3600*24*7);


				//更新客户信息
				if($this->customer->getId()){
					$this->load->model('catalog/point');
					$result=$this->model_catalog_point->getPointByCode($point_id);
					$json['customer_id']=$this->customer->getId();
					$json['point_id']=$point_id;
					
					$json['result']=$result;
					$this->customer->setCustomerLocation($result['city_id'],$result['cbd_id'],$result['point_id']);

                    //清除配送session
                    unset($this->session->data['shipping_method']['code']);
				}
				
				if(isset($this->request->get['refresh']) && $this->request->get['refresh']=='auto'){
					$json['redirect']=true;
				}
				
				$json['success']=true;
			}else{
				$json['error']='请选择区域与商圈';
			}
		}
		
		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
	}
	
	
	public function filter(){
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/point/list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/point/list.tpl';
		} else {
			$this->template = 'default/template/point/list.tpl';
		}
		
    	$this->response->setOutput($this->render());
	}
	
	public function detail(){
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/point/detail.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/point/detail.tpl';
		} else {
			$this->template = 'default/template/point/detail.tpl';
		}
		
    	$this->response->setOutput($this->render());
	}
	
	public function cbd($city_id){
		$this->load->model('localisation/cbd');
		
		$results = $this->model_localisation_cbd->getCbdsByCityId($city_id);

		$this->data['cbds']=$results;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/point/cbd.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/point/cbd.tpl';
		} else {
			$this->template = 'default/template/point/cbd.tpl';
		}
		
    	$this->response->setOutput($this->render());
	}
	
	public function point($cbd_id){
		
		$this->load->model('catalog/point');
			
		$filter=array();
		
		$filter['filter_point_cbd_id']= $cbd_id;

		$this->data['points']=array();
			
		$point_results=$this->model_catalog_point->getPoints($filter);
		
		foreach($point_results as $result){
			if($result['status']){
				$this->data['points'][]=array(
					'point_id'         => $result['point_id'],
				 	'name'        => $result['name'],
					   'address'        => $result['address'],
				);
			}
		}
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/point/list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/point/list.tpl';
		} else {
			$this->template = 'default/template/point/list.tpl';
		}
		
    	$this->response->setOutput($this->render());
	}
}