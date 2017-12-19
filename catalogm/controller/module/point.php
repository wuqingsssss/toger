<?php
class ControllerModulePoint extends Controller {
	protected function lbs($setting) {
		
		$this->load->model('catalog/point');
		$points=$this->model_catalog_point->getAllCbd();
		
		$res=array();
		foreach($points as $key =>$item)
		{
			if(!isset($res[$item['city_id']]))
			{
				$res[$item['city_name']]['name']=$item['city_name'];
				$res[$item['city_name']]['code']=$item['city_code'];
				$res[$item['city_name']]['value']=$item['zone_name'].$item['city_name'];
				 
			}

			if(!isset($res[$item['city_name']]['child']['cbd']))
			{
			$res[$item['city_name']]['child']['cbd']['name']="商圈";
			}
			$res[$item['city_name']]['child']['cbd']['data'][$item['cbd_id']]['name']=$item['cbd_name'];
			$res[$item['city_name']]['child']['cbd']['data'][$item['cbd_id']]['value']=$item['cbd_id'].','.$item['cbd_id'];
		}
		
		$filterdata['location']['name']="区域";
		$filterdata['location']['data']=$res;
		

		$this->load->model('account/address');
		$addresses = $this->model_account_address->getAddresses('meishisong');

		foreach($addresses as $key=>$item)
		{
			$lbspointhome[$item['address_id']]=json_decode($item['shipping_data_info']);

		}
		$this->data['lbspointhome']= json_encode($lbspointhome);
		
		$this->data['show_location_tipbox']= $setting['show_location_tipbox'];
		$this->data['filterData']= json_encode($filterdata);
		$this->load->service('baidu/point');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/point_lbs.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/point_lbs.tpl';
		} else {
			$this->template = 'default/template/module/point_lbs.tpl';
		}

		$this->render();
	}
}
?>