<?php
class ControllerModuleYiyuangou extends Controller {
	protected function index($setting) {
		

		$this->language->load('module/cates'); 

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_more'] = $this->language->get('text_more');
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->load->model('catalog/product'); 
		
		$this->load->model('tool/image');

		$this->data['products'] = array();

		$this->load->model('catalog/category');


		$category_info = $this->model_catalog_category->getCategory($setting['cate']);
		
		$this->data['heading_title'] = '';
		
		if($category_info){
			
			$this->data['heading_title'] = $category_info['name'];
			$this->data['href']      = $this->url->link('product/category', 'path=' . $setting['cate']);
			if ($category_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));;
			}
			
			$this->load->model('catalog/product');
			$data = array(
				'filter_category_id' => $setting['cate'], 
				'filter_sub_category' => TRUE, 
				'start'              => 0,
				'limit'              => $setting['count']
			);
			

		if(isset($this->request->get['sequence'])){
			$sequence = (int)$this->request->get['sequence'];
		
			if($sequence!=$this->cart->sequence)
			{
				$this->cart->clear();
				$this->cart->setPeriod($sequence);
			}
		}
		$sequence=$this->cart->sequence;
		
		$this->data['sequence'] = $sequence;
			
		 $periods=$this->cart->getPeriods();
		 $period=$this->cart->getPeriod();
		
		
		
		/* 根据session获取当前菜品周期 */
		$filter_data['filter_start_date']=$period['start_date'];
		$filter_data['filter_end_date']  =$period['end_date'];
		$filter_data['filter_category_id'] = 55;
		$filter_data['filter_supply_period_id']  =$period['id'];
		
		$this->data['current_period']    =$period;
			
		$results = $this->model_catalog_product->getSupplyProducts($filter_data);
			
		$this->data['products'] =changeProductResults($results,$this,'',$setting['image_width'],$setting['image_height']);

		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cates.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/cates.tpl';
		} else {
			$this->template = 'default/template/module/cates.tpl';
		}

		$this->render();
	}
}
?>