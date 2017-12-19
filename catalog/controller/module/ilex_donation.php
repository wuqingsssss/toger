<?php
class ControllerModuleIlexDonation extends Controller {
	protected function index($setting) {
//		$cart_total=$this->cart->getTotal();
//		
//		if($cart_total < $this->getTotal()){
//			return TRUE;
//		}
		
		
		//TODO:如果购物车已有赠送商品，隐藏该模块
		if($this->isDonationExisted()){
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
		
		$this->load_language('module/ilex_donation');
 
      	$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->currency->format($this->getTotal()));
				
		$this->load->model('catalog/product');
		
		$this->data['products'] = array();
		
		$product_ids=$this->getTotalDonationProducts();
		
		$product_data=array();
		
		foreach ($product_ids as $product_id) { 		
			$product_data[$product_id] = $this->model_catalog_product->getProduct($product_id);
		}
					 	 		
		$results = $product_data;
		
		$this->data['products'] =changeProductResults($results,$this,EnumPromotionTypes::TOTAL_DONATION);
		
		$this->data['promotion_type']=EnumPromotionTypes::TOTAL_DONATION;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ilex_donation.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ilex_donation.tpl';
		} else {
			$this->template = 'default/template/module/ilex_donation.tpl';
		}

		$this->render();
	}
	
	private function getTotal(){
		$total=$this->config->get('config_donation_limit');//TODO:动态设定满额赠送的金额
		
		return $total;
	}
	
	private function getTotalDonationProducts(){
		$filter=array();
		
		$filter['filter_promotion_type']=EnumPromotionTypes::TOTAL_DONATION;
		$filter['filter_total']=$this->getTotal();
		
		$this->load->model('promotion/product');
		
		$product_ids=$this->model_promotion_product->getProducts($filter);
		
		return $product_ids;
		
	}
	
	private function isDonationExisted(){
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
      		
      		if(isset($additional['promotion_code']) && $additional['promotion_code']==EnumPromotionTypes::TOTAL_DONATION){
      			$result=true;
				
				break;
      		}
  		}
  		
		return $result;
	}
}
?>
