<?php  
class ControllerModuleProductFeatured extends Controller {
	public function index($setting=array()) {
		$this->data['products']=$setting['skus'];

		if($this->request->get['skus']){
			$setting=array_merge($setting,$this->request->get);
			$this->data['ajax'] = TRUE;		
			
		$this->load->model ( 'catalog/product' );
		$skus=explode(',',$setting['skus']);
	
foreach($skus as $sku){
	
			$product_id = $this->model_catalog_product->getProductIdBySku ( $sku );
		
			$product=$this->model_catalog_product->getProduct ($product_id ,$this->cart->sequence);

			if($product['available'])
			{
		    $products[] = $product;
			}
		
}
$this->data['products']=changeProductResults($products,$this);
		}	
		
		$this->data['setting']=$setting;
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/product_featured.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/product_featured.tpl';
		} else {
			print_r($this->config->get('config_template') . '/template/module/product_featured.tpl');
			return;
		}
		
		$output=$this->render();
		if($this->request->get['skus']){
		$json['setting']=$setting;
		$json['output']=$output;
		$this->response->setOutput(json_encode($json));
		}
	}
}
?>