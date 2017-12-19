<?php
class ControllerCommonHome extends Controller {
	
public function index() {
	/* 
	$sms=new Sms('aaa');
	$sms->send('15810817660','你好菜君'.date('Y-m-d H:i:s',time()));
	die();
/* */
	//print_r('$redric_url1'.$redric_url = 'http://' . $_SERVER['HTTP_HOST'] . htmlspecialchars_decode($_SERVER['REQUEST_URI']));
	// $this->log_payment->info('$redric_url1'.$redric_url = 'http://' . $_SERVER['HTTP_HOST'] . htmlspecialchars_decode($_SERVER['REQUEST_URI']));
   
      // $this->log_payment->info($this->session->data);
       //储值功能
       if( !empty($this->request->get['trans_code'])){
           //登录成功后放入用户帐户
           $this->session->data['trans_code'] = $this->request->get['trans_code'];
       }
	    // 礼包参数保存到SESSION
       if( !empty($this->request->get['campaign'])){
           $this->session->data['campaign'] = $this->request->get['campaign'];
       }
       
       //如果带活动代码，记入SESSION
       if(!empty($this->request->get['promo'])){
           $this->session->data['promo'] = $this->request->get['promo'];
       }
       
       $infomation= new Common($this->registry);
       $infomation->get_openid();
       
       if ($this->customer->isLogged()) {
           if(isset($this->session->data['trans_code'])){
               $this->load->model('sale/transaction');
               $this->model_sale_transaction->addTransaction($this->customer->getId(),$this->session->data['trans_code']);
               
               unset($this->session->data['trans_code']);
           }
                      
	        $this->load->model('account/coupon');
	        //查询是否存在免费券
	        $results=$this->model_account_coupon->getCouponsByType($this->customer->getId(), 'R');
	        if($results) {
	            $this->session->data['freepromotion'] = $results[0]['coupon_customer_id'];
	        }
	        
	        //查询是否存在1元购
	        $result = $this->model_account_coupon->getCouponCustomerIDByCode($this->customer->getId(), '0817WBJCX');
	        if(!result){
	            $result = $this->model_account_coupon->getCouponCustomerIDByCode($this->customer->getId(), '3601515443');
	        }
	        if($result) {
	            $this->session->data['coupon'] = $result[0]['coupon_customer_id'];
	            $this->data['yiyuangou'] = 'true';
	        }else{
	            unset($this->session->data['coupon']);
	        }
	        
	        //查询是否存在老用户红包
	        if(isset($this->session->data['promo'])){
	        	
	            $result = $this->model_account_coupon->getPacketByCampaignCode('olduser', $this->session->data['promo']);
	        }
	        
	        // 未定义活动码或者活动码不明的，寻找通用红包
	        if(!$result || !isset($this->session->data['promo'])){
	           $result = $this->model_account_coupon->getPacketByCampaignCode('olduser', 'normal');
	        }
	        
	        if($result) {
	            $ret =  $this->model_account_coupon->addPacket2Customer($result['packet_id'], $this->customer->getId());
	            
	            if($ret==1){  //追加成功
	                // 更新红包名称
	                $this->data['packet'] = $result['name'];
	            }
	        }
        }
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));

		$this->data['heading_title'] = $this->config->get('config_title');
		
		$this->data['categories']=array();
		$this->load->model('catalog/category');
	//	$categories=$this->model_catalog_category->getSubCategories(0);
		$categories = $this->model_catalog_category->getChildCategories(0);

	    $this->data['categories']=$categories;
		
		if(isset($this->request->get['sequence'])){
			     $sequence = (int)$this->request->get['sequence'];
			     
			   if($sequence!=$this->cart->sequence)
			   {
			   	$this->cart->clear();
			   	$this->cart->setPeriod($sequence);
			   }
		}
		
		
		
		if(isset($this->request->get['filter_category_id'])){
			$filter_category_id = (int)$this->request->get['filter_category_id'];
		}
		elseif(isset($this->session->data['filter_category_id'])){
			$filter_category_id = (int)$this->session->data['filter_category_id'];
		}
		else{
		    $filter_category_id ='';
		}
		
		if(isset($this->request->get['filter_keyword'])){
			$filter_keyword = (int)$this->request->get['filter_keyword'];
		}else{
			$filter_keyword = null;
		}
		
		$this->session->data['filter_category_id'] = $filter_category_id;
		
		$this->data['sequence'] = $this->cart->sequence;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_keyword'] = $filter_keyword;
			
		
		
		$periods=$this->cart->getPeriods();
		$period=$this->cart->getPeriod();
		
		//$this->data['supply_periods']=$this->model_catalog_supply_period->getSupplyPeriods($searcher);


		$this->data['supply_periods']=$periods;
		$this->data['supply_period'] =$period;
		
		/**
		 * 周期不存在
		 */	

		    $this->data['sequence'] = $this->cart->sequence;
	


      //确定是否更改微信绑定
        if(isset($this->session->data['is_open_diag']) && $this->session->data['is_open_diag']==1){
            $this->data['is_open_diag']=1;
        }else{
            $this->data['is_open_diag']=0;
        }
		
        $this->data['homelink']=$this->url->link('common/home');

		$this->document->setPageId('home');
		$this->document->setPageRole('home-page');
	
		if($period['template']){
			
			
			  $this->load->model('catalog/product');
			 
			  $filter_data = array(
					'filter_show_date'=> date('Y-m-d H:i:s',time()),
					'start' => 0,
					'limit'=>getShowLimit(),
			);
			
		     $filter_data['filter_start_date']=$period['start_date'];
		     $filter_data['filter_end_date']  =$period['end_date'];
		     $filter_data['filter_supply_period_id']  =$period['id'];

		    if($filter_keyword){
			$filter_data['filter_name'] = $filter_keyword;
		    }
	
			$results = $this->model_catalog_product->getSupplyProducts($filter_data);
			
			$this->data['products']=changeProductResults($results,$this);
			
			
		    $template='/template/period/'.$period['template'].'/'.$period['template'].'.tpl';
		}
		else 
			$template='/template/common/home.tpl';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $template)) {
			$this->template = $this->config->get('config_template') . $template;
		} else {
			$this->template = 'default'.$template;
		}
		//获取可用的菜品周期
		//print_r($supply_periods);
		$this->data['supply_periods'] = $periods;
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());

        //mode by hyy

        $customer=$this->customer->getDisplayName();
        $email=$this->customer->getEmail();

	}
    function test(){
        $customer=$this->customer->getDisplayName();
        $email=$this->customer->getEmail();
        $detect = new Mobile_Detect();
        //if($detect->is_weixin_browser()) {
           // if (isset($_GET['code']) && ($_GET['code'])) {// weixin 注册回调

                require_once(DIR_ROOT . "catalog/controller/payment/wxpay_class/WxPayHelper.php");
                $appid = $this->config->get('wxpay_appid');
                $appsecret = $this->config->get('wxpay_appsecret');
                $partnerid = $this->config->get('wxpay_partnerid');
                $apikey = $this->config->get('wxpay_apikey');
                $jsApi = new JsApi($appid, $appsecret, $partnerid, $apikey);
                $jsApi->setCode($_GET['code']);

//                $openid = $jsApi->getOpenid();
                $openid='oDJSbt6yejt27pj6nnGUgA_XPI0Y';
                $template_id = 'qZT5nxRG97_TVOOwXzqaltgHmYBpx6yiSM-sMmvBtjU';
                $url = 'http://www.baidu.com';
                $msg_data = array(
                    'name' => array(
                        'value' => $customer,
                        'color' => '#FF0000'
                    ),
                    'remark' => array(
                        'value' => $email,
                        'color' => '#FF0000'
                    )
                );
		$this->load->service('weixin/interface');
		$this->service_weixin_interface->send_msg_by_weixin($appid, $appsecret, $openid, $template_id, $url, $msg_data);
//                $this->send_msg_by_weixin($appid, $appsecret, $openid, $template_id, $url, $msg_data);
            }
       // }
  //}

/**
 * 已切换到service  此方法未用到
 */
    function send_msg_by_weixin($appid,$appsecret,$openid,$template_id,$newurl, $msg_data){
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret."";
        $token_tmp=file_get_contents($url);
        $token_tmp_array=json_decode($token_tmp,true);
       $token=$token_tmp_array['access_token'];
         // $token='OezXcEiiBSKSxW0eoylIeIXYm7jk65RjGSNrFOWtuiyCLcw8vojWXxEOFWkHddDgEE1Z-25xoS00ROXqbkov1S92bGpVExGQu-t3fyT5E7PKzceTHemZ3Qx3a4nS6EOtzViOZ5ku1wUNyTZp1ud8QA';
        $template_array=array(
            'touser'=>$openid,
            'template_id'=>$template_id,
            'url'=>$newurl,
            'topcolor'=>'#FF0000',
            'data' => $msg_data
        );
        $template_array = json_encode($template_array);
        $msgurl="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $msgurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $template_array);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

	
	function get_product_home()
	{
		if(isset($this->request->get['sequence'])){
			$sequence = (int)$this->request->get['sequence'];
			if($sequence!=$this->cart->sequence)
			{
				$this->cart->clear();
				$this->cart->setPeriod($sequence);
			}
			
			
		}
		
		if(isset($this->request->get['filter_category_id'])){
			$filter_category_id = (int)$this->request->get['filter_category_id'];
		}
		elseif(isset($this->session->data['filter_category_id'])){
			$filter_category_id = $this->session->data['filter_category_id'];
		}
		else{
		    $filter_category_id ='';
		}
		
		$this->session->data['filter_category_id']= $filter_category_id;
		
		if(isset($this->request->get['filter_keyword'])){
			$filter_keyword = (int)$this->request->get['filter_keyword'];
		}else{
			$filter_keyword = null;
		}
		
		echo($this->getChild('product/home',array('sequence'=>$sequence,'filter_category_id'=>$filter_category_id,'filter_keyword'=>$filter_keyword)));
		
		die();
	}
	
	protected function getNewsImageArticles($article_category_id,$limit){
		$filter=array(
			'article_category_id' => $article_category_id,
			'filter_sub_category' => TRUE,
			'has_image' => TRUE,
			'start' => 0,
			'limit' => $limit,
		);
		
		return $this->getFilterArticles($filter);
	}
	
	private function getFilterArticles($filter=array()){
		$this->load->model('catalog/article');
		
		$results=$this->model_catalog_article->getArticles($filter);
		
		$url='';
		
		if(isset($filter['article_category_id']) && $filter['article_category_id']){
			$url.='&article_category_id=' .$filter['article_category_id'];
		}
		
		$article_array=array();
		
		foreach($results as $result){
			
			$article_array[] = array(
					'article_id' 	=> $result['article_id'],
					'title'  	 => $result['name'],
					'thumb'  	 => resizeThumbImage($result['image'],180,120),
					'summary'  	 => $result['summary'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'href' 		=>  $this->url->link('information/article', 'article_id=' . $result['article_id'].$url)
				);
			
		}
		
		return $article_array;
	}
}
?>