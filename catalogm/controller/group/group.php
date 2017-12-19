<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControllerGroupGroup extends Controller {
	/*
	 * 团购列表页
	 */

	public function index() {
		/*
	    if (!$this->customer->isLogged()) {
			//$this->session->data['redirect'] = $this->url->link('group/group', '', 'SSL');
			$this->setback();
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}*/

		$this->data['header_type'] = 'normal';
		
		if($this->detect->is_weixin_browser()){
			$this->data['header_type'] = 'weixin';
		}
		else if(isset($this->session->data ['platform']['platform_code'])&&$this->session->data ['platform']['platform_code']=='app'){
			$this->data['header_type'] = 'app';
		}
		
		
		$this->load_language('group/group');		
		$this->document->setTitle($this->language->get('heading_title'));
		// 页面头
		$header_setting = 
			array('left' => array(href => $this->url->link('common/home'),
						text => ''),
				'center' => array(href => "#",
						text => $this->document->getTitle()),
				'name' => $this->document->getTitle()
		);

		$this->data['header'] = $this->getChild('module/header', $header_setting);


		$this->load->model('sale/group_buy');
		$list = $this->model_sale_group_buy->get_group_list();

		//图片URL地址
		foreach ($list as &$info) {
			$this->load->model('tool/image');
			if ($info['image']&& file_exists(DIR_IMAGE . $info['image'])) {
				$info['image'] = $this->model_tool_image->resize($info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$info['image'] = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			}
		}
//		var_dump($info);exit;
		//赋值
		$this->data['list'] = $list;
		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/group/list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/group/list.tpl';
		} else {
			$this->template = 'default/template/group/list.tpl';
		}

		$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer35',
				'common/header40'
		);

		$this->response->setOutput($this->render());
	}

	/**
	 * 详情页
	 */
	public function info() {
		$id = '';
		$cid = '';
		$uid = $this->customer->getId();
		$c_info = array();
		
		$this->load->model('sale/group_buy');
		
		// 团购类型为空
		if(!isset($this->request->get['id'])|| !$this->request->get['id']){
		    
		    // 无拼团ID
		    if((!isset($this->request->get['cid']) || !$this->request->get['cid'])){
		        $this->redirect($this->url->link('group/group', '', 'SSL'));
		    }
		    else{//从拼团信息获取团购
		        $cid = $this->request->get['cid'];
		        $c_info = $this->model_sale_group_buy->get_c_info($cid);
		        if($c_info){
		            $id = $c_info['g_id'];
		        }
		        else{
		            $this->redirect($this->url->link('group/group', '', 'SSL'));
		        }
		    }
		}
		else{
		    $id = $this->request->get['id'];
		    if(isset($this->request->get['cid']) && $this->request->get['cid']){
		        $cid = $this->request->get['cid'];
		    }
		}
			
		
		//当前状态
		$status = $this->checkStatus($id, $cid);
		$groupbuy_info = $this->model_sale_group_buy->get_group_info($id);
		
		switch ($status){
		    case '00': // 未登录，未产生拼团，拼团数未满，团购未过期 
		        $this->data['error_warning'] = '';
		        $this->data['btn_share']  = '分享给好友';
		        $this->data['action_share'] = ''; //本页本身
		        $this->data['is_share']   = true;
		        $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元发起拼团';
		        $this->data['btn_submit_status'] = true;
		        $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id, '','SSL');
		        break;
	        case '01': // 未登录，未产生拼团，拼团数满或下架
	            $this->data['error_warning'] = '盆友，您来晚了，这个团下架了，要不您去看看菜君的其他拼团。';
	            $this->data['btn_share']  = '此团购已下架，点此查看菜君其他拼团';
	            $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
	            $this->data['is_share']   = false;
	            $this->data['btn_submit'] = '已下架';
	            $this->data['btn_submit_status'] = false;
	            $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
	            break;
	        case '02': // 未登录，未成团，未下架，拼团过期 
	            $this->data['error_warning'] = '盆友，抱歉地告诉您这个团人数不够未能成团，您再发起一个试试。';
	            $this->data['btn_share']  = '拼团失败，点此查看菜君其他拼团';
	            $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
	            $this->data['is_share']   = false;
		        $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元重新发团';
		        $this->data['btn_submit_status'] = true;
		        $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id, '','SSL');
		        break;
	        case '03': // 未登录，未成团，下架，拼团过期 
	            $this->data['error_warning'] = '盆友，您来晚了，这个团下架了，要不您去看看菜君的其他拼团。';
	            $this->data['btn_share']  = '此团购已下架，点此查看菜君其他拼团';
	            $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
	            $this->data['is_share']   = false;
	            $this->data['btn_submit'] = '已下架';
	            $this->data['btn_submit_status'] = false;
	            $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
	            break;
	        case '04': // 未登录，成团，未下架
	           $this->data['error_warning'] = '抱歉盆友，就在刚刚一霎那此团已经满员，您可以再发起一个新团。';
	            $this->data['btn_share']  = '已成团，点此查看菜君其他拼团';
	            $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
	            $this->data['is_share']   = false;
	            $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元发起拼团';
	            $this->data['btn_submit_status'] = true;
	           $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id, '','SSL');
	            break;
	        case '05': // 未登录，成团，下架
	            $this->data['error_warning'] = '盆友，您来晚了，这个团下架了，要不您去看看菜君的其他拼团。';
	            $this->data['btn_share']  = '此团购已下架，点此查看菜君其他拼团';
	            $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
	            $this->data['is_share']   = false;
	            $this->data['btn_submit'] = '已下架';
	            $this->data['btn_submit_status'] = false;
	            $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
	            break;
            case '06': // 未登录，未成团，未下架
                $this->data['error_warning'] = '';
                $this->data['btn_share']  = '邀请好友参团';
                $this->data['action_share'] = $this->url->link('group/group/info&id='.$id.'&cid='.$cid, '','SSL');
                $this->data['is_share']   = true;
                $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元参加拼团';
                $this->data['btn_submit_status'] = true;
                $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
                break;
		    case '10': // 登录，未产生拼团，拼团数未满，团购未过期 
		        $this->data['error_warning'] = '';
		        $this->data['btn_share']  = '分享给好友';
		        $this->data['action_share'] = $this->url->link('group/group/info&id='.$id.'&cid='.$cid, '','SSL');
		        $this->data['is_share']   = true;
		        $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元发起拼团';
		        $this->data['btn_submit_status'] = true;
		        $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id, '','SSL');
		        break;
		    case '11': // 登录，未产生拼团，下架
		        $this->data['error_warning'] = '盆友，您来晚了，这个团下架了，要不您去看看菜君的其他拼团。';
		        $this->data['btn_share']  = '此团购已下架，点此查看菜君其他拼团';
		        $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
		        $this->data['is_share']   = false;
		        $this->data['btn_submit'] = '已下架';
		        $this->data['btn_submit_status'] = false;
		        $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
		        break;		        
	        case '12': // 登录，产生拼团，未支付，未过期
	            $this->data['error_warning'] = '盆友，上次您好像忘记支付了哟，赶紧支付，你的好友等你邀请呢。';
	            $this->data['btn_share']  = '分享给好友';
		        $this->data['action_share'] = $this->url->link('group/group/info&id='.$id.'&cid='.$cid, '','SSL');
		        $this->data['is_share']   = true;
		        $this->data['btn_submit'] = '去支付';
		        $this->data['btn_submit_status'] = true;
		        $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
		        break;
	        case '13': // 登录，产生拼团，未支付，过期
	            $this->data['error_warning'] = '盆友，抱歉地告诉您这个团人数不够未能成团，您再发起一个试试。';
	            $this->data['btn_share']  = '拼团失败，点此查看菜君其他拼团';
	            $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
	            $this->data['is_share']   = false;
		        $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元重新发团';
		        $this->data['btn_submit_status'] = true;
		        $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id, '','SSL');
	            break;
            case '14': // 登录，产生拼团，未支付，下架
                $this->data['error_warning'] = '盆友，您来晚了，这个团下架了，要不您去看看菜君的其他拼团。';
                $this->data['btn_share']  = '此团购已下架，点此查看菜君其他拼团';
	            $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
	            $this->data['is_share']   = false;
	            $this->data['btn_submit'] = '已下架';
	            $this->data['btn_submit_status'] = false;
	            $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
                break;
	        case '15': // 登录，产生拼团，未成团，过期
	            $this->data['error_warning'] = '盆友，抱歉地告诉您这个团人数不够未能成团，您再发起一个试试。';
	            $this->data['btn_share']  = '拼团失败，点此查看菜君其他拼团';
	            $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
	            $this->data['is_share']   = false;
		        $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元重新发团';
		        $this->data['btn_submit_status'] = true;
		        $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id, '','SSL');
	            break;
	        case '16': // 登录，产生拼团，未成团，未过期，参团
	            $this->data['error_warning'] = '';
	            $this->data['btn_share']  = '邀请好友参团';
	            $this->data['action_share'] = $this->url->link('group/group/info&id='.$id.'&cid='.$cid, '','SSL');
	            $this->data['is_share']   = true;
	            $this->data['btn_submit'] = '已参团';
	            $this->data['btn_submit_status'] = false;
	            $this->data['action_submit'] = $this->url->link('group/group', '','SSL');
	            break;
            case '17': // 登录，产生拼团，未成团，未过期，未参团
                $this->data['error_warning'] = '';
                $this->data['btn_share']  = '邀请好友参团';
                $this->data['action_share'] = $this->url->link('group/group/info&id='.$id.'&cid='.$cid, '','SSL');
                $this->data['is_share']   = true;
                $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元参加拼团';
                $this->data['btn_submit_status'] = true;
                $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
                break;
            case '18': // 登录，产生拼团，未成团，下架
                $this->data['error_warning'] = '盆友，抱歉地告诉您这个团人数不够未能成团，要不您去看看菜君的其他拼团。';
                $this->data['btn_share']  = '此团购已下架，点此查看菜君其他拼团';
                $this->data['action_share'] = $this->url->link('group/group', '', 'SSL'); //团购列表
                $this->data['is_share']   = false;
	            $this->data['btn_submit'] = '已下架';
	            $this->data['btn_submit_status'] = false;
	           $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
            case '19': // 登录，产生拼团，成团，参团
                $this->data['error_warning'] = '';
                $this->data['btn_share']  = '分享给好友';
                $this->data['action_share'] = $this->url->link('group/group/info&id='.$id.'&cid='.$cid, '','SSL');
                $this->data['is_share']   = true;
                $this->data['btn_submit'] = '已成团';
                $this->data['btn_submit_status'] = false;
                $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id.'&cid='.$cid, '','SSL');
                break;
            case '1a': // 登录，产生拼团，成团，未参团
                $this->data['error_warning'] = '抱歉盆友，就在刚刚一霎那此团已经满员，您可以再发起一个新团。';
                $this->data['btn_share']  = '分享给好友';
                $this->data['action_share'] = $this->url->link('group/group/info&id='.$id.'&cid='.$cid, '','SSL');
                $this->data['is_share']   = true;
                $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元发起拼团';
	            $this->data['btn_submit_status'] = true;
	            $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id, '','SSL');
                break;
            default:
                $this->data['error_warning'] = '';
                $this->data['btn_share']  = '分享给好友';
                $this->data['action_share'] = $this->url->link('group/group/info&id='.$id.'&cid='.$cid, '','SSL');
                $this->data['is_share']   = true;
                $this->data['btn_submit'] = $groupbuy_info['sell_price'].'元发起拼团';
                $this->data['btn_submit_status'] = true;
                $this->data['action_submit'] = $this->url->link('group/group/checkout&id='.$id, '','SSL');
                break;
		}
	
		//团购大图  选菜品自动补图 URL  与图片选择器 URL 规格不一致
		$this->load->model('tool/image');
		if ($groupbuy_info['image'] && file_exists( DIR_IMAGE.$groupbuy_info['image'])) {
		    $this->data['thumb'] = $this->model_tool_image->resize($groupbuy_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
		} else {
		    $this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
		}
		
		// 设置分享链接
		$sharedata['linkparent']= $this->data['action_share'];
		if ($groupbuy_info['share_image'] && file_exists( DIR_IMAGE.$groupbuy_info['share_image'])) {
		    $sharedata['share_image'] = $this->model_tool_image->resize($groupbuy_info['share_image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
		} elseif($groupbuy_info['image'] && file_exists( DIR_IMAGE.$groupbuy_info['image'])) {
		    $sharedata['share_image'] = $this->model_tool_image->resize($groupbuy_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
		} else{
		    $sharedata['share_image'] = '';
		}
		
		$sharedata['share_title']=isset($groupbuy_info['share_title'])&& !empty($groupbuy_info['share_title'])?$groupbuy_info['share_title']:$groupbuy_info['name'];
		$sharedata['share_desc'] =isset($groupbuy_info['share_desc'])&&  !empty($groupbuy_info['share_desc'])?$groupbuy_info['share_desc']:$groupbuy_info['desc'];
		$this->data['sharedata']=$sharedata;
		
		$this->data['share'] =  $this->getChild('module/sharebtn', array('sharedata' => $sharedata));
		
		// 拼团信息为空，读取拼团信息
		if(!$c_info && $cid){
		    $c_info = $this->model_sale_group_buy->get_c_info($cid);
		}
		
		$members = array();
		//取团购信息和 参加人员
		if($c_info){
		    $members = $this->getMemberIcon($c_info);
		    
		    if($c_info['max_num'] > $c_info['real_num'] ){
		        for( $i=0; $i< $c_info['max_num'] - $c_info['real_num']; $i++){
		            $members[] = array(
		                'is_blank'    => true,
    	                'icon'        => ''
	                );
		        }
		    }
		}
		else{
		    for( $i=0; $i< $groupbuy_info['member_num']; $i++){
		        $members[] = array(
		            'is_blank'    => true,
		            'icon'        => ''
		        );
		    }
		}
		
	
		// 拼团截止日期计算
		//拼接 创建团信息
		$createtime = date('Y-m-d',time());
		//结束时间 = 创建时间 + 成团有效时间
		$time_s = (($groupbuy_info['duration'] - 1) * 3600 * 24) + strtotime($createtime);
		$date_end = date('Y-m-d', $time_s);
		//配送时间为空
		if($groupbuy_info['send_time'] == '0000-00-00' || !$groupbuy_info['send_time'] || $groupbuy_info['send_time']<=$date_end){
		    $date_shipping = date('Y-m-d',strtotime($date_end)+3600*24);
		}
		else{
		    $date_shipping = $groupbuy_info['send_time'];
		}
		
		//赋值
		$this->data['gid'] = $id;
		$this->data['cid'] = $cid;
		$this->data['info'] = $groupbuy_info;
		$this->data['members'] = $members;
		$this->data['c_info'] = $c_info;
		$this->data['date_end'] = $date_end;
		$this->data['date_shipping'] = $date_shipping;
		
		//商品介绍，如果没有设置，使用菜品介绍
		if(!$groupbuy_info['rich_text']){
		    $product = $this->model_sale_group_buy->getProductInfoByGroupID($id);
		    $this->data['product_info'] = $product['description'];
		}else{
		    $this->data['product_info'] = $groupbuy_info['rich_text'];
		}
	
		
		// 页面头
		$header_setting = 
			array('left' => array('href' => $this->url->link('group/group'),
						'text' => $this->language->get("header_left")),
				'center' => array(href => "#",
						'text' => '菜君拼团'),
				'name' => ''
		);
		$this->data['header'] = $this->getChild('module/header', $header_setting);
		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
		$this->template = $this->config->get('config_template') . '/template/group/info.tpl';
		$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer35',
				'common/header40'
		);
		$this->response->setOutput($this->render());
	}
	
	/**
	 * 获取用户头像
	 * @param unknown $cid
	 */
	private function getMemberIcon($c_info){
	    $members = array();
	    $this->load->model('sale/group_buy');
	    
	    $member_info = $this->model_sale_group_buy->getGroupMember($c_info['c_id']);
	    
	    if($member_info){
	        foreach ($member_info as $member){
	            $members[] = array(
	                'customer_id' => $member['customer_id'],
	                'is_owner'    => $c_info['customer_id']== $member['customer_id'],
	                'icon'        => empty($member['icon'])?'': $member['icon']
	            );
	        }
	    }

	    return $members;
	}

	/**
	 * 判断拼团状态
	 * @param unknown $id
	 * @param unknown $cid
	 */
	private function checkStatus($id, &$cid){
	    $this->load_language('group/group');
	        
	    $status = '00'; 
	    $today  = date('Y-m-d', time());
	    
	    //获取团购基础信息
	    $groupbuy_info = $this->model_sale_group_buy->get_group_info($id);
	    $groupbuy_num  = $this->model_sale_group_buy->getGroupbuyNum($id);
	    
	    if($cid){
	        //获取拼团信息
	        $create_info = $this->model_sale_group_buy->getGroupbuyCreateInfo($cid);
	        if(!$create_info){ //获取失败
	            $cid = '';
	        }
	    }
	    
	    //未登录
	    if (!$this->customer->isLogged()) {
	        // 无拼团id
	        if(!$cid){
	            // 下架
	            if($today> $groupbuy_info['end_time'] || $groupbuy_num>= $groupbuy_info['group_num']){
	                $status = '01';
	            }
	            else{
	                $status = '00';
	            }            
	        }
	        else{
	            // 未成团
	            if($create_info['status'] != '2' ){
	                // 过期
	                if($today>$create_info['end_time']){
	                    //更新拼团状态
	                    $this->model_sale_group_buy->updateStatus($cid);
	                    // 下架
	                    if($today > $groupbuy_info['end_time']|| $groupbuy_num>= $groupbuy_info['group_num'])
	                    {
	                        $status = '03'; // 查看其它拼团
	                    }
	                    else{
	                       $status = '02';  // 重新发起
	                    }
	                }
	                else{ // 未过期
	                    $status = '06';  // 参团
	                }
	            }
	            else{ // 成团
	                // 下架
	                if($today > $groupbuy_info['end_time']|| $groupbuy_num>= $groupbuy_info['group_num']){
	                    $status = '05';
	                }
	                else{
	                    $status = '04';
	                }
	            }
	        }
	    }
	    else { // 已登录
	        // 获取用户参团信息
	        $groupbuy = $this->model_sale_group_buy->getCustomerGroupbuyMember($this->customer->getId(), $id);
	    
	        //已经参团
	        if($groupbuy){
	            if($cid != $groupbuy['c_id']){
	                //重新获取拼团信息
	                $create_info = $this->model_sale_group_buy->getGroupbuyCreateInfo($groupbuy['c_id']);
	                $cid = $groupbuy['c_id'];
	            }
	           	     
	            if($create_info['status'] == '2'){ // 已成团
	                $status = '19';
	            }
	            else{  // 未成团
	                if ($today > $create_info['end_time']) // 过期
	                {
	                    $status = '15';
	                    $this->model_sale_group_buy->updateStatus($cid);
	                }
	                else{ // 未过期
	                    $status = '16';  //参团分享
	                }
	            }
	        }
	        else{ //未参团
	            // 获取发起团信息
	            $groupbuy = $this->model_sale_group_buy->getCustomerGroupbuy($this->customer->getId(), $id);
	    
	            // 已经发起团但未支付
	            if($groupbuy){
	                
	                if($cid != $groupbuy['c_id']){
	                    //重新获取拼团信息
	                    $create_info = $this->model_sale_group_buy->getGroupbuyCreateInfo($groupbuy['c_id']);
	                    $cid = $groupbuy['c_id'];
	                }
	                
	                if ($today > $create_info['end_time']) { // 过期
	                    $this->model_sale_group_buy->updateStatus($cid);
	                    if($today > $groupbuy_info['end_time']|| $groupbuy_num>= $groupbuy_info['group_num']) { // 下架
	                        $status = '14';
	                    }
	                    else{
	                        $status = '13';  // 重新发起
	                    }
	                }
	                else{
	                    $status = '12';  //去支付
	                }                
	            }
	            else{  // 未参团未发起团
	                
	                if(!$cid){ // 无拼团id
	                    if($today > $groupbuy_info['end_time']|| $groupbuy_num>= $groupbuy_info['group_num']) { // 下架
	                        $status = '11'; //其它
	                    }
	                    else{ 
	                        $status = '10'; //发起拼团
	                    }
	                }
	                else{  // 有拼团ID
	                   
                        if( $create_info['status'] == 2) { // 已经成团
                            if( $today > $groupbuy_info['end_time']|| $groupbuy_num>= $groupbuy_info['group_num']) { //下架
                                $status = '18'; //其它
                            }
                            else{
                                $status = '1a'; //发起
                            }
                            
                        }
                        elseif($today > $create_info['end_time']){ // 过期
                            $this->model_sale_group_buy->updateStatus($cid);
                            if( $today > $groupbuy_info['end_time']|| $groupbuy_num>= $groupbuy_info['group_num']) { //下架
                                $status = '14';  // 其它
                            }
                            else{
                                $status = '15';  //重新发起
                            }
                            
                        }
                        else{
                            $status = '17';  //参团
                        }
	                }
	            }
	        }
	    }
	    	     
	    return $status;
	}
	
	
	/**
	 * 提交购物车
	 */
	public function checkout(){
	    $json = array();
	    $id = $this->request->get['id'];
	    $cid = $this->request->get['cid'];
	    
	    // 如果未登录
	    if (!$this->customer->isLogged()) {
	        $this->setback(true, $this->url->link('group/group/info&id='.$id.'&cid='.$cid,'','SSL'));
	        $json['redirect'] = $this->url->link('account/login','','SSL');
	    }
	    else{
	        //如果不存在拼团ID
	        if(!$cid){
	            $cid = $this->create_group($id);
	        }
	        
	        //添加到购物车
	        $this->cart->addGroupbuy($cid);
	         	        
	        $json['redirect'] = $this->url->link('checkout/checkout_group', '','SSL' );
	    }
	    

	    $this->load->library('json');
	    $this->response->setOutput(Json::encode($json));
	}
	
	/**
	 * 创建团接口
	 */
	private function create_group($id ){
		
		$this->load->model('sale/group_buy');
			
		$cid = $this->model_sale_group_buy->create($id, $this->customer->getId());

		return $cid;
	}
	
}
