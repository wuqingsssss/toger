<?php
class ControllerCommonHome extends Controller {
	
public function index() {
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
	        else{
    	        // 新用户红包
    	        if(isset($this->session->data['packet_name'])){
    	            $this->data['packet'] = $this->session->data['packet_name'];
    	            unset($this->session->data['packet_name']);
    	        }
	        }
        }
        /* 设置页面标题*/
		$this->document->setTitle ( $this->config->get ( 'config_title' ) );
		$this->document->setDescription ( $this->config->get ( 'config_meta_description' ) );
		
		//$navtop ['left'] = '<a class="return" href="javascript:_.go();"></a><a class="locate fz-18" href="javascript:location.reload(true);">北京</a>';
		//$navtop ['center'] = '<a class="logo" href="index.php?route=common/home"></a><div class="search-input"><input type="text" name="wd"/></div>';
		//$navtop ['right'] = '<a class="search" href="javascript:"></a><a class="message has-new" href="javascript:"></a>';
		/* 自定义显示app标题栏*/
		$navtop ['left'] = '';
		$navtop ['center'] = '<a class="logo" href="index.php?route=common/home"></a>';
		$navtop ['right'] = '';
		
        $this->data['navtop']=$navtop;
		
        
        /* 全部分类
		$this->data['categories']=array();
		$this->load->model('catalog/category');
	    //$categories=$this->model_catalog_category->getSubCategories(0);
		$categories = $this->model_catalog_category->getChildCategories(0);

	    $this->data['categories']=$categories;
		/* */
        /* 更具get参数获取｜重置周期*/
		if(isset($this->request->get['sequence'])){
			     $sequence = (int)$this->request->get['sequence'];
			     
			   if($sequence!=$this->cart->sequence)
			   {
			   	$this->cart->clear();
			   	$this->cart->setPeriod($sequence);
			   }
		}
		
		
		$this->data['sequence'] = $this->cart->sequence;
				
		$periods=$this->cart->getPeriods();
		$period=$this->cart->getPeriod();

		$this->data['supply_periods']=$periods;
		$this->data['supply_period'] =$period;
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
			/*获取周期全部菜品*/
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
		    
		    $this->document->setTitle ( $period['name'] );
		    $sharedata['share_title']=$period['name'];
		    $this->data['sharedata']=$sharedata;
		}
		else 
			$template='/template/common/home.tpl';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $template)) {
			$this->template = $this->config->get('config_template') . $template;
		} else {
			$this->template = 'default'.$template;
		}
		

		$this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';


		//获取可用的菜品周期
		//print_r($supply_periods);
		$this->data['supply_periods'] = $periods;
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer35',
			'common/header35'
		);

		$this->response->setOutput($this->render());

        //mode by hyy
	}
	public function chkPlatform() {
		
		unset($this->session->data ['platform']);//一定得先清除平台session再登出 否则可能会清除掉用户平台登录信息
		$this->customer->logout();
		$infomation = new Common ( $this->registry );
		$infomation->get_openid ();
		
		$this->load->model('design/banner');
		$results = $this->model_design_banner->getBanner(17);
		
		foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				$banners[] = array(
						'title' => $result['title'],
						'link'  => $result['link'],
						'image' =>  resizeThumbImage($result['image'], $setting['width'], $setting['height'],false)
				);
			}
		}
		
		
		$res=array();
		$res['status']=1;
		
		$res['banners']= $banners;
		
		if (! $this->customer->isLogged ()) {
			
		    $res['userinfo']['islogged']= 0;
		    $res['platform'] = $this->session->data ['platform'];
		} else {
			$res['userinfo']['islogged']= 1;
			$res['userinfo']['firstname']= $this->customer->getName();
			$res['userinfo']['getDateAdded']=  $this->customer->getDateAdded();
			$res['userinfo']['mobile']= $this->customer->getMobile();
			$res['platform'] = $this->session->data ['platform'];
		}	
		
		$this->log_sys->info('chkPlatform::'.serialize($res));
		$this->response->setOutput(json_encode($res));
	}
}
?>