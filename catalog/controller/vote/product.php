<?php
class ControllerVoteProduct extends Controller {
	protected function init(){
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('vote/product', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
    	
		$this->load_language('vote/product');
	} 
	
	public function index(){
		$this->init();
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/vote');
		$this->load->model('catalog/product');

		$this->data['products'] = array();

		if(isset($this->request->get['limit'])){
			$limit=(int)$this->request->get['limit'];
		}else{
			$limit= $this->config->get('config_catalog_limit');
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


		$filter=array();
		$filter['sort'] = "";
		$filter['order'] = "";
		$url = "";
		
		if(isset($this->request->get['sort'])){
			$filter['sort'] = $this->request->get['sort'];
			$this->data['sort'] = $this->request->get['sort'];
			$url .= ("&sort=".$filter['sort']);
		}
		
		if(isset($this->request->get['order'])){
			$filter['order'] = $this->request->get['order'];
			$this->data['order'] = $this->request->get['order'];
			$url .= ("&order=".$filter['order']);
		}
		
		$filter['start']=($page - 1) * $limit;
		$filter['limit']=$limit;

		$total = $this->model_catalog_vote->getTotalVoteProduct($filter);
		
		$products = $this->model_catalog_vote->getVoteProductIds($filter);;

		
		$image_width = $this->config->get('config_image_product_width');
		
		$image_height = $this->config->get('config_image_product_height');
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
				
			if ($product_info) {
				$image =resizeThumbImage($product_info['image'], $image_width, $image_height,TRUE);

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
					'product_id' => $product_info['product_id'],
					'icons' => $product_info['icons'],
					'voted_good_num' => $product_info['voted_good_num'],
					'thumb'   	 => $image,
					'name'    	 => $product_info['name'],
					'subtitle'        => $product_info['subtitle'],
					'unit'        => $product_info['unit'],
					'origin'        => $product_info['origin'],
					'description' => strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')),
					'price'   	 => $price,
					'special' 	 => $special,
					'tax'         => $tax,
					'rating'     => $rating,
					'donation'      => $product_info['donation'],
					'voted'      => $this->model_catalog_vote->checkHasVoted(array('product_id' => $product_info['product_id'],'vote_user_id' => $this->customer->getId())),
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
				);
			}
		}



		$page += 1;
		
		$totalPage = (($total%$limit)>0)?(($total/$limit)+1):($total/$limit);
		
		if($page>$totalPage){
			$page = 1;
		}
		
		$this->data['url'] = $this->url->link('vote/product','page='.$page.$url);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_vote_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/product_vote_list.tpl';
		} else {
			$this->template = 'default/template/product/product_vote_list.tpl';
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
	 
	public function dovote(){
		$this->init();
		
		try {
			if(isset($this->request->post['product_id'])&&isset($this->request->post['voteResult']))
			{
				$product_id = $this->request->post['product_id'];
				$voteResult = $this->request->post['voteResult'];
				$this->load->model('catalog/vote');
				$this->load->model('catalog/product');
				$product = $this->model_catalog_product->getProduct($product_id);
//				$votedGoodNum = (int)$product['voted_good_num'];
//				if($this->request->post['voteResult']=='good')
//				{
//					$votedGoodNum +=1;
//				}
//				$data = array(
//				'product_id' => $product_id,
//				'voted_good_num' => $votedGoodNum,
//				);
				
				if(isset($product))
				{
					$vote_user_id = "";
					$vote_user_ip = $this->getIP();
					$vote_user_mac = $this->getClientMac();
					$vote_user_id = $this->customer->getId();
					
					$dataInfo = array(
						'product_id' => $product_id,
						'vote_user_id' => $vote_user_id,
						'vote_user_ip' => $vote_user_ip,
						'vote_user_mac' => $vote_user_mac,
						'vote_num' =>1,
					);
					
					if($this->model_catalog_vote->checkHasVoted($dataInfo))
					{
						$article_data= array(
							'success'=>'0' ,
							'error' => $this->data['have_vote'],
						);
					}
					else{
						
						//update product vote num info
//						$this->model_catalog_vote->updateProductVote($data);
						$votedGoodNum = (int)$this->model_catalog_vote->insertVoteUserInfo($dataInfo);
						$article_data= array(
						'success'=>'1' ,
						'productId' => $product_id,
						'voteGoodNum'=> $votedGoodNum,
						);
					}
				}
				else
				{
					$article_data= array(
						'success'=>'0' ,
						'error' => $this->data['no_project_info'],
					);
				}
			}else{
				$article_data= array(
					'success'=>'0' ,
					'error' => $this->data['vote_param_error'].'【'.$this->request->post['product_id'].'】'.'【'.$this->request->post['product_id'].'】',
				);
			}
			$this->load->library('json');
			$this->response->setOutput(Json::encode($article_data));
		} catch (Exception $e) {
			$article_data= array(
				'success'=>'0' ,
				'error' => $e->getMessage(),
					);
			$this->load->library('json');
			$this->response->setOutput(Json::encode($article_data));
		}
	}
	
		//获取客户端ip
	private function getIP()
	{
//		global $ip;
//		if (getenv("HTTP_CLIENT_IP"))
//		$ip = getenv("HTTP_CLIENT_IP");
//		else if(getenv("HTTP_X_FORWARDED_FOR"))
//		$ip = getenv("HTTP_X_FORWARDED_FOR");
//		else if(getenv("REMOTE_ADDR"))
//		$ip = getenv("REMOTE_ADDR");
//		else $ip = "Unknow";
//		return $ip;

		return "";
	}
	
	//获取客户端MAC地址
	private function getClientMac() 
	{ 
//		$return_array = array(); 
//		$temp_array = array(); 
//		$mac_addr = ""; 
//		@exec("arp -a",$return_array); 
//		foreach($return_array as $value) { 
//		   if(StrPos($value,$_SERVER["REMOTE_ADDR"]) !== false && preg_match("/(:?[0-9a-f]{2}[:-]){5}[0-9a-f]{2}/i",$value,$temp_array)) { 
//			    $mac_addr = $temp_array[0]; 
//			    break; 
//		   } 
//		} 
//		return ($mac_addr); 

		return "";
	} 
	
public function filter(){
		$url='';
		if(isset($this->request->get['path'])){
			$this->data['path']=$this->request->get['path'];
		}else{
			$this->data['path']='';
		}
		
		$this->data['filter']=$this->url->link('vote/product');
		
		if (isset($this->request->get['sort'])) {
			$this->data['sort']=$this->request->get['sort'];
		} else {
			$this->data['sort']='voted_good_num';
		}
		
		if (isset($this->request->get['order'])) {
			$this->data['order']=$this->request->get['order'];
		} else {
			$this->data['order']= 'ASC';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_vote_filter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/product_vote_filter.tpl';
		} else {
			$this->template = 'default/template/product/product_vote_filter.tpl';
		}
		
    	$this->render();
	}
	
	

}
?>