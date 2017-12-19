<?php  
class ControllerModuleOrderCoupon extends Controller {
	protected function index($setting) {
		$this->language->load('module/order_coupon');
		
		$order_info=$this->data['order_info'];
	
		if($order_info['order_status_id']=='2'){//限定只能是已付款状态领取优惠劵
			$code='';
		foreach($setting['rule'] as $rule)
		{
			if($order_info['total']<$rule['order_total']){	
				break;
			}
			$code=$rule['order_total'];
		}

		if($code)
		{//如果存在下单奖励优惠劵级别 ，则发放优惠劵
			$this->load->model('account/coupon');
			$coupon_info=$this->model_account_coupon->getCouponByCode($code);
			if($coupon_info&&$coupon_info['free_get']){
				$res=$this->model_account_coupon->addCoupon($code,$order_info['customer_id'],0,'',$order_info['order_id']);
				if($res==1){
					$message=sprintf($this->language->get('message'),$order_info['name']);
				}
				elseif($res==-1)
				{ 
				  $message='抱歉，您可能已经领取太多了';
				}
				elseif($res==-2)
				{ 
			        $message='抱歉，红包应被抢完了';
				}
				elseif($res==-3)
				{
					$message='抱歉，您可能已经领取过了';
				}
			
			}else {
				//$message='呵呵，出错了';
			}

		   $this->data['message']=$message;
		}
		}
		
		
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/order_coupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/order_coupon.tpl';
		} else {
		return;
		}
		
		$this->render();
	}
}
?>