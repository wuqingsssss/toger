<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControllerAccountGetcoupon extends Controller {

	/**
	 * 领取优惠卷列表页
	 */
	public function index() {
		$this->document->setTitle('领取优惠券');
		
		// 页面头
		$header_setting =  array('left'    =>  array( href => "javascript:_.go();",
		    text => $this->language->get("header_left")),
		    'center'  =>  array( href => "#",
		        text => $this->document->getTitle()),
		    'name'    =>  $this->document->getTitle()
		);
			
		$this->data['header'] = $this->getChild('module/header', $header_setting);

		//获取优惠卷显示列表
		$this->load->model('sale/coupon_show');
		
		$this->data['list'] = $this->model_sale_coupon_show->show_list();
//		var_dump($this->data['list']);exit;
		

		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/getcoupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/getcoupon.tpl';
		} else {
			$this->template = 'default/template/account/getcoupon.tpl';
		}

		$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer35',
				'common/header35'
		);

		$this->response->setOutput($this->render());
	}

	/**
	 * 领取优惠卷接口
	 */
	public function get_coupon() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/getcoupon', '', 'SSL');
			$json['code'] = -998;
			$json['msg'] = '请登录后在领取';
			$json['url'] = $this->url->link('account/login', '', 'SSL');
			echo json_encode($json);exit;
		}
		//领卷用户ID
		$customer_id = $this->customer->getId();
		if(empty($customer_id) || $customer_id<0){
			$json['code'] = 0;
			$json['msg'] = '请登录后在领取';
			echo json_encode($json);exit;
		}
		$coupon_id = intval($this->request->post['c_id']);
		$this->load->model('account/coupon');
		$coupon_info = $this->model_account_coupon->getCouponInfo($coupon_id);
		
		if ($coupon_info && $coupon_info['free_get']) {
			$ret = $this->model_account_coupon->addCoupon($this->request->post['coupon'], $customer_id);
			//-1 已经领取过  -2  没有了 
			if ($ret > 0) {
				$json['code'] = 1;
				$json['msg'] = '领取成功';
			} else if($ret == -1){
				$this->log_sys->info($ret);
				$json['code'] = -1;
				$json['msg'] = '已经领取过了';
			}else{
				$this->log_sys->info($ret);
				$json['code'] = -2;
				$json['msg'] = '已被抢光';
			}
		} else {
			$this->log_sys->info($ret);
				$json['code'] = 0;
				$json['msg'] = '木有了';
		}
		echo json_encode($json);
		
	}

}
