<?php
class ControllerModuleCates extends Controller {
	public function index($setting=array()) {

		
		if($this->request->get['cate']){
			$setting=array_merge($setting,$this->request->get);
			$this->data['ajax'] = TRUE;
				
		

		$this->language->load('module/cates'); 

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_more'] = $this->language->get('text_more');
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		//如果主驱动不传递products 则通过后台配置读取
		if(!$this->data['products']){
			$this->data['products'] = array();

		$this->load->model('tool/image');
		$this->load->model('catalog/product'); 
		

		$this->load->model('catalog/category');


		$category_info = $this->model_catalog_category->getCategory($setting['cate']);
		
		$this->data['heading_title'] = '';
		
		if($category_info){
			
			$this->data['href']      = $this->url->link('product/category', 'path=' . $setting['cate']);
			if ($category_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));;
			}	
			
			
			$this->load->model('catalog/product');
			$filter_data = array(
				'filter_category_id' => $setting['cate'], 
				'filter_sub_category' => TRUE, 
				'start'              =>(int)$setting['page']*$setting['count'],
				'limit'              => $setting['count']
			);
			$setting['page']+=1;
		$sequence=$this->cart->sequence;
		$this->data['sequence'] = $sequence;
			
	    $periods=$this->cart->getPeriods();
		$period=$this->cart->getPeriod();
		
		/* 根据session获取当前菜品周期 */
		$filter_data['filter_start_date']=$period['start_date'];
		$filter_data['filter_end_date']  =$period['end_date'];

		$filter_data['filter_supply_period_id']  =$period['id'];
		
		$this->data['current_period']    =$period;
			
		$results = $this->model_catalog_product->getSupplyProducts($filter_data);
			if(count($results)<$setting['count']){
				$setting['page']=-1;
			}
		$this->data['products'] =changeProductResults($results,$this,'',$setting['image_width'],$setting['image_height']);
		}
		}
		}
		$this->data['setting']=$setting;
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cates.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/cates.tpl';
		} else {
			$this->template = 'default/template/module/cates.tpl';
		}			
		$output=$this->render();
		if($this->request->get['cate']){	
			$json['setting']=$setting;	
			$json['output']=$output;
		$this->response->setOutput(json_encode($json));
		}		
	}
}
?>