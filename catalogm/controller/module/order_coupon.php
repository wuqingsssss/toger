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
			$code=$rule['coupon_code'];
			$code_type=$rule['code_type'];
		}
		
		//print_r($code);
		//print_r($code_type);

		if($code)
		{
			if($code_type=='1'){
			//如果存在下单奖励优惠劵级别 ，则发放优惠劵
			$this->load->model('account/coupon');
			$coupon_info=$this->model_account_coupon->getCouponByCode($code);
			if($coupon_info&&$coupon_info['free_get']){
				$res=$this->model_account_coupon->addCoupon($code,$order_info['customer_id'],0,'',$order_info['order_id']);
				if($res==1){
					$message=sprintf($this->language->get('message_coupon'),$coupon_info['name']);
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
				//$message=sprintf($this->language->get('message'),'5元优惠劵');
			}
		}
		elseif($code_type=='2'){
			$this->load->model('sale/transaction');
			$trans=$this->model_sale_transaction->getTransactionByCode($code);
			
		
		   if(strtotime($order_info['date_added'])>=strtotime($trans['date_start'])){
		  
			if($trans['is_tpl']){//则生成储值码
				
			
				
				if(!$this->model_sale_transaction->existCustomerTransaction($order_info['customer_id'],array('order_id'=>$order_info['order_id'],'amount'=>true))){

				   $trans['is_tpl']=0;
				   $trans['used']=0;
				   $trans['customer_id']='';
				   $trans['opeator']='sys';
				   $trans['tpl_id']=$trans['trans_id'];
				   $trans['prefix']=$trans['trans_code'];
				   $trans['length']=(int)strlen($trans['trans_code'])+8;

				  unset($trans['trans_id']);
				  unset($trans['trans_code']);

				   $code = $this->model_sale_transaction->get_recharge_key($trans);

				   $flag = $this->model_sale_transaction->addTransaction($order_info['customer_id'], $code,$order_info['order_id'],'下单赠送');

				}
				else 
				{
				
					$flag=false;
				}
	
			}
			else
			{//直接绑定
				$flag=$this->model_sale_transaction->addTransaction($order_info['customer_id'],$code,$order_info['order_id'],'下单赠送'); 
			}

			if($flag){
				$message=sprintf($this->language->get('message_transaction'),$this->currency->format($trans['value']));
			}
			else 
			{
				//$message='呵呵，出错了';
			}

		}
		else 
			{
				//$message='呵呵，超时了';
			}
			
			
		}
		else {
			//$message='呵呵，出错了';
		}

		$this->data['message']=$message;
		}
		}
		$this->log_payment->info('异步回调order_coupon'.$order_info['order_id'].'::'.$order_info['order_status_id'].'::'.$message);
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/order_coupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/order_coupon.tpl';
		} else {
		return;
		}
		
		$this->render();
	}
}
?>