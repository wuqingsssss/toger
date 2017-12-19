<?php 
class ControllerMobileLocation extends Controller {  
	
  	public function index(){
		$this->data['city_id']=0;
		$this->data['cbd_id']=0;
		
		$this->load->model('localisation/city');
		$cities=$this->model_localisation_city->getCitiesByZoneId($this->config->get('config_zone_id'));
		$this->data['cities'] = $cities;
		
		if(isset($this->request->cookie['point_city_id']) && $this->request->cookie['point_city_id']){
			$this->data['city_id']=(int)$this->request->cookie['point_city_id'];
		}else if($this->customer->isLogged()){
			$this->data['city_id']=$this->customer->getLocationCity();
		}else{
			$this->data['city_id']=$this->config->get('default_city_id');
			$this->data['city_id']='390'; //需要替换为设置选项
		}
		
		if(isset($this->request->cookie['point_cbd_id']) && $this->request->cookie['point_cbd_id']){
			$this->data['cbd_id']=(int)$this->request->cookie['point_cbd_id'];
		}else if($this->customer->isLogged()){
			$this->data['cbd_id']=$this->customer->getLocationCbd();
		}else{
			$this->data['cbd_id']=$this->config->get('default_cbd_id');
			$this->data['cbd_id']= 5; //需要替换为设置选项
		}

		
  		if($this->request->get['route']=='checkout/checkout'){
			$this->data['action'] = $this->url->link('point/home/location&refresh=auto');
		}else{
			$this->data['action'] = $this->url->link('point/home/location');
		}





		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mobile/location.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/mobile/location.tpl';
		} else {
			$this->template = 'default/template/mobile/location.tpl';
		}
		
		$this->render();				
  	}

    protected  function getCbdsByCityId($city_id){
        $this->load->model('localisation/cbd');

        $results = $this->model_localisation_cbd->getCbdsByCityId($city_id);

        foreach($results as $index => $result){
            $results[$index]['points']=$this->getPointsByCbdId($result['id']);
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
  	
  	public function select(){

        $this->load->model('localisation/city');

        $cities=$this->model_localisation_city->getCitiesByZoneId($this->config->get('config_zone_id'));

        foreach($cities as $index => $city){
            $cities[$index]['cbds']=$this->getCbdsByCityId($city['city_id']);
        }

        $this->data['cities']=$cities;
  		
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mobile/location_select.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/mobile/location_select.tpl';
		} else {
			$this->template = 'default/template/mobile/location_select.tpl';
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