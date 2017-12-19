<?php

/**
 * 项目管理  碳排放和排污权
 * setting中包含tpl_type  参数：center  显示分类的图片
 * @author  liujin
 *
 */
class ControllerModuleProject extends Controller {

	protected function index($setting=array()) {

		$this->load_language('module/project');
		$this->load->model('project/category');
		$categories = $this->model_project_category->getCategories(0);

		$supply = '1';//供应
		$demand = '0';//求购
		$supplyResults = array();
		$demandResults = array();
		$data = array();

		$data['limit'] = 10;
		$data['start'] = 0;

		$this->data['supplyResults']= array();

		if(isset($setting['category_id']) && !is_null($setting['category_id']))
		{
			$data['category_id'] = $setting['category_id'];
		}
		else if(isset($categories)&& !is_null($categories))
		{
			$data['category_id'] = $categories[0]['category_id'];
		}


		if(isset($setting['limit']) &&!is_null($setting['limit']))
		{
			$data['limit'] =  $setting['limit'];
		}

		if(isset($setting['start']) &&!is_null($setting['start']))
		{
			$data['start'] =  $setting['start'];
		}



		//审核通过
		$data['verified'] = '0';

		if(isset($categories)&& !is_null($categories))
		{
			$this->load->model('project/product');

			if(isset($setting['supply_type']) && !is_null($setting['supply_type']))
			{
				if($setting['supply_type'] == $supply)
				{
					$allType = $this->model_project_product->getSpecifiedCategoryProjectInfo($data,$supply);
				}else if($setting['supply_type'] == $demand)
				{
					$allType = $this->model_project_product->getSpecifiedCategoryProjectInfo($data,$demand);
				}
			}
			else {
				$allType = $this->model_project_product->getSpecifiedCategoryProjectInfo($data,null);
			}
		}

		if(isset($allType)&&!is_null($allType))
		{
			$this->project_info_setter('allResults',$allType,$setting);
			
		}

		$this->data['categories'] = $categories;
		$this->data['demandResults'] = $demandResults;

		$category = $this->model_project_category->getCategory($data['category_id']);
		if(isset($category))
		{
			if(file_exists(DIR_IMAGE . $category['image']))
			{
				$this->data['img_href']=HTTP_IMAGE . $category['image'];
			}else{
				$this->data['img_href']=HTTP_IMAGE . 'no_image.jpg';
			}
		}
		else {
			$this->data['img_href']=HTTP_IMAGE . 'no_image.jpg';
		}

		$this->chooseTPL($setting);

		$this->render();
	}


	public function chooseTPL($setting)
	{
		
		//项目
		if(isset($setting['chooseType'])&&$setting['chooseType'] == '1')
		{
			if(isset($setting['tpl_type'])&&$setting['tpl_type'] == 'center')
			{
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/project_center.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/project_center.tpl';
				} else {
					$this->template = 'default/template/module/project_center.tpl';
				}
			}
			else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/project.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/project.tpl';
				} else {
					$this->template = 'default/template/module/project.tpl';
				}
			}
		}else if(isset($setting['suffix']) && $setting['suffix']){
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/project_'.$setting['suffix'].'.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/project_'.$setting['suffix'].'.tpl';
			} else {
				$this->template = 'default/template/module/project_'.$setting['suffix'].'.tpl';
			}
		}
		else {
			if(isset($setting['tpl_type'])&&$setting['tpl_type'] == 'center')
			{
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/project_center.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/project_center.tpl';
				} else {
					$this->template = 'default/template/module/project_center.tpl';
				}
			}else if(isset($setting['suffix']) && $setting['suffix']){
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/project_'.$setting['suffix'].'.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/project_'.$setting['suffix'].'.tpl';
				} else {
					$this->template = 'default/template/module/project_'.$setting['suffix'].'.tpl';
				}
			}else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/project.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/project.tpl';
				} else {
					$this->template = 'default/template/module/project.tpl';
				}
			}
		}
		
	}


	public function getProductById($productId)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "project_product p  WHERE p.product_id = '" . (int)$product_id . "'");
		return $query->row;
	}

	public function project_info_setter($name,$projects,$setting=array())
	{
		foreach ($projects as $supplyresult){
			$this->data[''.$name][]=array(
				'product_id' => $supplyresult['product_id'],
				'name' =>$supplyresult['name'],
				'number' =>$supplyresult['number'],
				'price' =>$supplyresult['price'],
				'local_addr_zone' =>$supplyresult['local_addr_zone'],
				'local_addr_city' =>$supplyresult['local_addr_city'],
				'trade_type' => $supplyresult['trade_type'],
				'date_added' =>date($this->language->get('date_format_short'), strtotime($supplyresult['date_added'])),
				'period' =>date($this->language->get('date_format_short'), strtotime($supplyresult['period'])),
				'href' => $this->url->link('project/trade/detail', 'category_id=' . $setting['category_id'].'&project_id='.$supplyresult['product_id'], 'SSL')
			);
		}
	}
	
	public function category($setting=array()){
		
		if(!isset($setting['cid']) || !$setting['cid']){
			return; 
		}
		
		$cid=$setting['cid'];
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/project_category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/project_category.tpl';
		} else {
			$this->template = 'default/template/module/project_category.tpl';
		}
		
		$this->render();
	}
}
?>