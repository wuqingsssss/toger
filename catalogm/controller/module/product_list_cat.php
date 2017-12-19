<?php
class ControllerModuleProductListCat extends Controller {
	public function index($setting = array()) {
		if ($this->data ['cat_id'])
			$setting ['cate'] = $this->data ['cat_id'];
		$setting ['cate'] = $this->request->get['path'];
		if ($setting ['title'])
			$this->document->setTitle ( $setting ['title'] );
		
		if ($this->request->get ['cate']) {
			$setting = array_merge ( $setting, $this->request->get );
			$this->data ['ajax'] = TRUE;
			
			$this->load->model ( 'catalog/product' );
			$this->load->model ( 'catalog/supply_period' );
			
			$periods = $this->cart->getPeriods ();
			$period = $this->cart->getPeriod ();
			$period = $periods [0];
			$filter_data = array (
					// 'sort' => $sort,
					'order' => $order,
					'start' => ($page - 1) * $limit,
					'limit' => $limit 
			);
			
			$filter_data ['filter_start_date'] = $period ['start_date'];
			$filter_data ['filter_end_date'] = $period ['end_date'];
			$filter_data ['filter_supply_period_id'] = $period ['id'];
			$this->data ['current_period'] = $period;
			
			$results = $this->model_catalog_product->getSupplyProductsList ( $filter_data );
			
			foreach ( $results as $k1 => $p ) {
				foreach ( $p ['cats'] as $k2 => $cats ) {
					$results [$k1] ['cats'] [$k2] ['goods'] = changeProductResults ( $cats ['goods'], $this, '', $setting ['image_width'], $setting ['image_height'] );
				}
			}
			$this->data ['products'] = $results;
		}
		
		$this->data ['setting'] = $setting;
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/product_list.tpl' )) {
			$this->template = $this->config->get ( 'config_template' ) . '/template/module/product_list_cat.tpl';
		} else {
			print_r ( $this->config->get ( 'config_template' ) . '/template/module/product_list_cat.tpl' );
			return;
		}
		
		$output = $this->render ();
		if ($this->request->get ['cate']) {
			$json ['setting'] = $setting;
			$json ['output'] = $output;
			$this->response->setOutput ( json_encode ( $json ) );
		}
	}
}
?>