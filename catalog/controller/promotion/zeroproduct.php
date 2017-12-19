<?php
class ControllerPromotionZeroproduct extends Controller {
	private $error = array();
	protected function init(){
		if (!$this->customer->isLogged()) {
			if(isset($this->request->get['route']) && $this->request->get['route']){
				$this->session->data['redirect'] = $this->url->link($this->request->get['route'], '', 'SSL');
			}else{
				$this->session->data['redirect'] = $this->url->link('promotion/zeroproduct', '', 'SSL');
			}
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
		$this->load_language('promotion/zeroproduct');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('promotion/zeroproduct');
		
		initOperStatus();
	}

	public function index() {
		
		$this->init();
		
		$this->getList();
	}
	
	private function getList() {
		$this->load->model('tool/image');
		$this->data['products'] = array();
		$limit = getParamValue('limit',true,'int',$this->config->get('config_admin_limit'));
		$page = getParamValue('page',true,'int',1);
		
		
		$rule = $this->model_promotion_zeroproduct->getRuleProductByCode(EnumPromotionTypes::ZERO_BUY);
		
		$defaultId = 0;
		if($rule)
		{
			$defaultId = $rule['pr_id'];
		}
		$pr_id = getParamValue('pr_id',true,'int',$defaultId);
		$type = getParamValue('type',false,'string',null);
		
		$image_height = getParamValue('image_height',false,'string',180);
		$image_width = getParamValue('image_width',false,'string',180);
		$position = getParamValue('position',false,'string','content_bottom');
		
		$filter=array();
		$filter['start']=($page - 1) * $limit;
		$filter['limit']=$limit;
		
		$total = $this->model_promotion_zeroproduct->getTotalProducts($filter,$pr_id,$type);
		$results = $this->model_promotion_zeroproduct->getProducts($filter,$pr_id,$type);

		$url = "&limit=".$limit."&image_width=".$image_width."&image_height=".$image_height."&position=".$position;
		foreach ($results as $product) {
			$product_info = $this->model_promotion_zeroproduct->getProduct($product['product_id'],$pr_id);
			if ($product_info) {
				$image=resizeThumbImage($product_info['image'],$this->config->get('config_image_product_width'),$this->config->get('config_image_product_height'),TRUE);

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $product_info['special'] : $product_info['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}
					
				$this->data['products'][] = array(
					'start_date' => $product_info['start_date'],
					'end_date' => (isset($product_info['end_date'])&&!is_null($product_info['end_date']))?$product_info['end_date']:"",
					'product_id' => $product_info['product_id'],
					'icons' => $product_info['icons'],
					'thumb'   	 => $image,
					'name'    	 => $product_info['name'],
					'subtitle'        => $product_info['subtitle'],
					'unit'        => $product_info['unit'],
					'origin'        => $product_info['origin'],
					'use_quantity'     =>isset($product_info['use_quantity'])?(int)$product_info['use_quantity']:0,
					'buy_quantity'     =>isset($product_info['buy_quantity'])?(int)$product_info['buy_quantity']:0,
					'description' => strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')),
					'price'   	 => $price,
					'special' 	 => '0.00',
					'tax'         => $tax,
					'rating'     => $rating,
					'donation'      => $product_info['donation'],
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id'].'&p_code='.EnumPromotionTypes::ZERO_BUY),
				);
			}
		}
	
		$pagination =getPageObj($total, $page, $limit, 'promotion/zeroproduct', 'page={page}'.$url);
		
		$this->data['url'] = $pagination->url;
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['promotion_type']=EnumPromotionTypes::ZERO_BUY;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/promotion/promotion_zero_product_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/promotion/promotion_zero_product_list.tpl';
		} else {
			$this->template = 'default/template/module/promotion/promotion_zero_product__list.tpl';
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
	
	
	
	/**
	 * 更新被购买数量
	 */
	public function buy()
	{
		$this->init();
		
		$rule = $this->model_promotion_zeroproduct->getRuleProductByCode("ZERO_BUY");
		$buyQuantity = getParamValue('buyQuantity', true,'int',0);
		$product_id = getParamValue("product_id", true,'int',0);
		
		if(isset($rule)&&isset($rule['pr_id'])&&!is_null($rule['pr_id']))
		{
			$pr_id = $rule['pr_id'];
			$product_id = $rule['product_id'];
			$buyQuantity = $buyQuantity+(int)$rule['buy_quantity'];
		}
		
		$data = array(
			'pr_id' => $pr_id, 
			'product_id' => $product_id,
			'buyQuantity' => $buyQuantity,
		);
		
		
		$updateResult = $this->model_promotion_zeroproduct->updateRuleProductBuy($data);
		
		if($updateResult)
		{
			$result = array(
				'success' => 'true',
			);
		}else{
			$result = array(
				'success' => 'false',
			);
		}
		
		$this->load->library('json');
		$this->response->setOutput(Json::encode($result));
	} 
	
}
?>