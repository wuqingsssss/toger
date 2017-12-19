<?php
class ControllerPromotionPromotion extends Controller {
	private $error = array();
	protected function init(){
		if (!$this->customer->isLogged()) {
			$this->setback();
			
			//if(isset($this->request->get['route']) && $this->request->get['route']){
			//	$this->session->data['redirect'] = HTTP_SERVER.$this->server['REQUEST_URI'];
			//}else{
			//	$this->session->data['redirect'] = $this->url->link('promotion/zeroproduct', '', 'SSL');
			//}
    	} 
		$this->load_language('promotion/promotion');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('promotion/promotion');
		$this->load->model('promotion/product');
		
		initOperStatus();
	}

	public function index() {
		
		$this->init();
		
		$this->getList();
	}
	
	private function getList() {
		$this->load->model('tool/image');
		$this->data['products'] = array();


		$this->data['header_navbar']=array(
				'center'=>array('name'=>'双十一活动专区','href'=>''),
		);
		
		$pb_id = getParamValue('pid',true,'int',0);
		$pb_key = getParamValue('pkey',true,'string','');
		
		if(!$pb_id&&!$pb_key){
			die('error/not_found');
		}
		
		$promotion=$this->model_promotion_promotion->getPromotion(array('pb_id'=>$pb_id,'pb_key'=>$pb_key));

		$pb_id=$promotion['pb_id'];
		
		$pr_id=$promotion['pr_id'];
		
		$promotion['page_header']=htmlspecialchars_decode($promotion['page_header']);
		$promotion['page_footer']=htmlspecialchars_decode($promotion['page_footer']);
		
		$this->document->setTitle($promotion['pb_name']);
		$this->data['title'] = $this->document->getTitle();
		
		$this->data['promotion']= $promotion;


		$source_url=$this->url->link('promotion/promotion', 'pkey='.$promotion['pb_key']."&pid=".$promotion['pb_id']);
		$this->session->data['source_url']=$source_url;

		$share_link=$promotion['share_link']?$promotion['share_link']:$source_url;

		$sharedata['pointid']=$this->request->get['point'];
		$sharedata['partner']=$this->request->get['partner'];
		if($this->request->get['partner'])
		setcookie('partner', $this->request->get['partner'], time() + 60 * 60 * 24, '/', WEB_HOST);
		
		
		$sharedata['linkparent']=$share_link;
			
		$sharedata['share_image']=$promotion['share_image']?HTTPS_IMAGE.$promotion['share_image']:'';

		$sharedata['share_title']=$promotion['share_title'];
		$sharedata['share_desc']=$promotion['share_desc'];
		 
		$this->data['sharedata']=$sharedata;

		$results = $this->model_promotion_promotion->getProducts($pb_id );

		foreach ($results as $product) {
			$product_info = $this->model_promotion_promotion->getProduct($product['product_id'],$pr_id);

			if ($product_info) {
	
				$products[] =array_merge($product,$product_info) ;
				$progroups[$product['pr_group']]['data'][] = $product_info;
				$progroups[$product['pr_group']]['banner']= $product['pr_banner'];
			}

		}
	

		$this->data['products']=changeProductResults($products,$this);
	
		foreach($progroups as $key=> $group)
		{
			$group['data']=changeProductResults($group['data'],$this);
			$this->data['progroups'][$key]=$group['data'];
			$this->data['productgroups'][$key]=$group;
		}
		
		if($promotion['template']&&file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/promotion/'.$promotion['template'].'/'.$promotion['template'].'.tpl')){
	
		    $this->template=$this->config->get('config_template') .'/template/promotion/'.$promotion['template'].'/'.$promotion['template'].'.tpl';
		}
		else 
			$this->template=$this->config->get('config_template') .'/template/promotion/promotion.tpl';
		
		$this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';

		$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer35',
				'common/header35',
				'common/header40',
			);
		$this->response->setOutput($this->render());
	}
	
	
	
	/**
	 * 更新被购买数量
	 */
	public function share_success()
	{
		$this->init();

        $this->session->data['coupon_code']='YYGFX';

        $result['error']=0;
        $result['redirect']="index.php?route=promotion/couponpickup";
        
		$this->load->library('json');
		$this->response->setOutput(Json::encode($result));
	} 
	
}
?>