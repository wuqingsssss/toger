<?php
class ControllerModuleIlexFirstbuy extends Controller {
	protected function index($setting) {
		//TODO:如果用户已有订单存在，隐藏该模块
		if(! $this->isFirstBuy()){
			return;
		}
		
		//TODO:如果购物车已有首次赠购商品，隐藏该模块
		if($this->checkExisted()){
			return;
		}
		
		if($this->cart->getTotal() <= 0){
			return;
		}
		
		if(isset($setting['pb_id'])){
			$pb_id=(int)$setting['pb_id'];
		}else{
			$pb_id=0;
		}
		
		$this->load->model('promotion/promotion');
		
		$promotion_info=$this->model_promotion_promotion->getPromotion($pb_id);
		
		if(!$promotion_info){
			return ;
		}
		
		$this->load_language('module/ilex_firstbuy');
 	
		$this->load->model('catalog/product');
		
		$this->data['products'] = array();
		
		$product_ids=$this->getFilterProducts();
		
		$product_data=array();
		
		foreach ($product_ids as $product_id) { 		
			$product_data[$product_id] = $this->model_catalog_product->getProduct($product_id);
		}
				 	 		
		$results = $product_data;
		
		$this->data['products'] =changeProductResults($results,$this,EnumPromotionTypes::REGISTER_DONATION);
		
		$this->data['promotion_type']=EnumPromotionTypes::REGISTER_DONATION;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ilex_firstbuy.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ilex_firstbuy.tpl';
		} else {
			$this->template = 'default/template/module/ilex_firstbuy.tpl';
		}

		$this->render();
	}

	private function getFilterProducts(){
		$filter=array();
		
		$filter['filter_promotion_type']=EnumPromotionTypes::REGISTER_DONATION;
		
		$this->load->model('promotion/product');
		
		$product_ids=$this->model_promotion_product->getProducts($filter);
		
		return $product_ids;
	}
	
	private function checkExisted(){
		$products=$this->cart->getProducts();
		
		$result=false;
		
		foreach($products as $key => $product){
  			$product = explode(':', $key);
      		$product_id = $product[0];
		
			// Options
      		if (isset($product[1]) && $product[1]) {
        		$options = unserialize(base64_decode($product[1]));
      		} else {
        		$options = array();
      		} 
      		
      		if (isset($product[2]) && $product[2]) {
        		$additional = unserialize(base64_decode($product[2]));
      		} else {
        		$additional = array();
      		} 
      		
      		if(isset($additional['promotion_code']) && $additional['promotion_code']==EnumPromotionTypes::REGISTER_DONATION){
      			$result=true;
				
				break;
      		}
  		}
  		
		return $result;
	}
	
	private function isFirstBuy(){
		$this->load->model('account/order');
		
		//订单完成后才算是完成首次赠购
		$filter=array(
			'filter_not_order_status_ids' => array($this->config->get('config_order_cancel_status_id'),$this->config->get('config_order_nopay_status_id'))
		);
		
		$order_total=$this->model_account_order->getTotalOrders($filter);
		
		if($order_total > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
}
?>
