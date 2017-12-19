<?php
class ControllerVoteVote extends Controller { 
	public function index()
	{
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('vote/vote', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
    	
		$this->load_language('vote/vote');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_cart'] = $this->language->get('button_cart');

		$this->load->model('catalog/vote');

		$this->load->model('tool/image');

		$this->data['products'] = array();

		if(isset($this->request->get['limit'])){
			$limit=(int)$this->request->get['limit'];
		}else{
			$limit= $this->config->get('config_admin_limit');
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


		$filter=array();

		$filter['start']=($page - 1) * $limit;
		$filter['limit']=$limit;

		$total = $this->model_catalog_vote->getTotalVoteProduct($filter);
		$products = $this->model_catalog_vote->getVoteProductIds($filter);;



		if(isset($this->request->get['image_height']))
		{
			$image_height = $this->request->get['image_height'];
		}
		else{
			$image_height = 180;
		}

		if(isset($this->request->get['image_width']))
		{
			$image_width = $this->request->get['image_width'];
		}
		else{
			$image_width = 180;
		}

		if(isset($this->request->get['position']))
		{
			$position = $this->request->get['position'];
		}
		else{
			$position = 'content_bottom';
		}

		$url = "&limit=".$limit."&image_width=".$image_width."&image_height=".$image_height."&position=".$position;

		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_vote->getProduct($product_id);
				
			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $image_width, $image_height);
				} else {
					$image = false;
				}

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
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
				);
			}
		}



		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('vote/vote','page={page}'.$url);
		$this->data['url'] = $pagination->url;
		$this->data['pagination'] = $pagination->render();

		if($position=='content_top' || $position=='content_bottom' ){
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_vote_list.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/product_vote_list.tpl';
			} else {
				$this->template = 'default/template/module/product_vote_list.tpl';
			}
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
		
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
		
		$this->load_language('vote/vote');
		
		try {
			if(isset($this->request->post['product_id'])&&isset($this->request->post['voteResult']))
			{
				$product_id = $this->request->post['product_id'];
				$voteResult = $this->request->post['voteResult'];
				$this->load->model('catalog/vote');
				$product = $this->model_catalog_vote->getProduct($product_id);
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

}
?>