<?php  
class ControllerModulePromotionFeatured extends Controller {
	public function index($setting=array()) {

		if($this->request->get['pid']){
			
			$setting=array_merge($setting,$this->request->get);
			$this->data['ajax'] = TRUE;
				
		
		$this->load->model('promotion/promotion');
		$this->load->model('promotion/product');
		$this->load->model('tool/image');
		
		$this->data['products'] = array();
		
		$promotion=$this->model_promotion_promotion->getPromotion(array('pb_id'=>$setting['pid']));
		
		$source_url=$this->url->link('promotion/promotion', 'pkey='.$promotion['pb_key']."&pid=".$promotion['pb_id']);

		$promotion['href']=$promotion['share_link']?$promotion['share_link']:$source_url;
		$promotion['share_title']=empty($promotion['share_title'])?$promotion['pb_name']:$promotion['share_title'];
		$promotion['title']      =$promotion['share_title'].$promotion['share_desc'];
		
		$this->data['promotion']=$promotion;
		
		$results = $this->model_promotion_promotion->getProducts($setting['pid'] );
		
		$pr_id=$promotion['pr_id'];
		
		foreach ($results as $product) {
			$product_info = $this->model_promotion_promotion->getProduct($product['product_id'],$pr_id);
		
			if ($product_info) {
		
				$this->data['products'][] = $product_info;
			}
		
		}
		
		$this->data['products']=changeProductResults($this->data['products'],$this);
		}
		$this->data['setting']=$setting;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/promotion_featured.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/promotion_featured.tpl';
		} else {
			print_r($this->config->get('config_template') . '/template/module/promotion_featured.tpl');
			return;
		}

		$output=$this->render();
		if($this->request->get['pid']){
			$json['setting']=$setting;
			$json['output']=$output;
			$this->response->setOutput(json_encode($json));
		}
	}
}
?>