<?php
class ModelTotalCoupon extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['coupon'])) {
			$this->load->language('total/coupon');
			
			$this->load->model('checkout/coupon');
			 
			$coupon_info = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

			//如果存在优惠券
			if ($coupon_info) {
				// 计算折扣范围
				// 菜票
				if($coupon_info['mutual_prom']=='2')
				{//绝对与特价或促销互斥，如果有促销则直接返回不处理优惠券
				foreach ($this->cart->getProducts() as $product) {
				 if (isset($product['promotion']['promotion_code'])) {
				               return ;
				       }
					}
	
				}
				
				$sub_total = 0;
				if(isset($this->session->data['coupon_product']) && $this->session->data['coupon_product']){
					foreach ($this->cart->getProducts() as $product) {
						if ( $product['product_id'] == $this->session->data['coupon_product']) {
							
							
							if($coupon_info['mutual_prom']=='1'){
								if(!isset($product['promotion']['promotion_code']))
								{
									$sub_total = $product['price'];  // 一份菜折扣
									break;
								}
												
							}
							else {
							$sub_total = isset($product['promotion']['promotion_code'])?$product['promotion']['promotion_price']:$product['price'];  // 一份菜折扣
							break;
								
							}
							}
					}					
				}
				elseif (!$coupon_info['product']) {  //优惠券无绑定商品

				        foreach ($this->cart->getProducts() as $product) {
				            if (!isset($product['promotion']['promotion_code'])||!$coupon_info['mutual_prom']) {
				                $sub_total += $product['total']; // 折扣商品不在优惠券范围
				            }
				        }
				    	    
				} 
				else {  //优惠券绑定商品，需计算适用商品小计
					

					
				    if($coupon_info['type'] == 'Q') {// 一份菜折扣	  

				    	
				    	
				    	
				        foreach ($this->cart->getProducts() as $product) {
				            if (in_array($product['product_id'], $coupon_info['product'])) {
				                //绑定商品列表时，不管是否折扣都在优惠券范围
				               // $sub_total = isset($product['promotion']['promotion_code'])?$product['promotion']['promotion_price']:$product['price'];  
				               // break;
				            
				            
				            
				            
				                if($coupon_info['mutual_prom']=='1'){
				                	if(!isset($product['promotion']['promotion_code']))
				                	{
				                		$sub_total = $product['price'];  // 一份菜折扣
				                		break;
				                	}
				                
				                }
				                else {
				                	$sub_total = isset($product['promotion']['promotion_code'])?$product['promotion']['promotion_price']:$product['price'];  // 一份菜折扣
				                	break;
				                
				                }
				            
				            
				            }
				            
				            
				            
				            
				            
				        
				        }			        
				    }
				    else{	  // 绑定商品列表内所有菜作为折扣对象		  
    					foreach ($this->cart->getProducts() as $product) {			  
    						if (in_array($product['product_id'], $coupon_info['product'])) {
    						    //
    						    if (!isset($product['promotion']['promotion_code'])||!$coupon_info['mutual_prom']) {
      						        $sub_total += $product['total']; 
    					           }    
    						}
    						
    					}				
				   }	
				}
				

				
				// 最低消费金额
				if( $coupon_info['total'] > EPSILON){
				    if( $sub_total - $coupon_info['total']  < -EPSILON){
				        return;
				    }
				}
				// 固定金额
				if ($coupon_info['type'] == 'F' || $coupon_info['type'] == 'SF') {
				    $discount_total = min($coupon_info['discount'], $sub_total);	    
				}
				//  百分比
				elseif ($coupon_info['type'] == 'P'|| $coupon_info['type'] == 'SP'|| $coupon_info['type'] == 'Q'){
				    $discount_total = $sub_total* $coupon_info['discount'] / 100;
				}
				//  菜票
				elseif ($coupon_info['type'] == 'R'){
				    $discount_total = min($sub_total, $coupon_info['discount']);
				}
				// 未知类型
				else {
				    $discount_total = 0;
				}
				
/*				
				// 固定金额
				if ($coupon_info['type'] == 'F') {
					$coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
				}
				
				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;
					
					if(isset($this->session->data['coupon_product']) && $this->session->data['coupon_product']){
					    if ( $product['product_id'] == $this->session->data['coupon_product']) {
					        $status = true;
					    }
					    else {
					        $status = false;
					    }
					}
					elseif (!$coupon_info['product']) {
						$status = true;
					} else {
						if (in_array($product['product_id'], $coupon_info['product'])) {
							$status = true;
						} else {
							$status = false;
						}
					}
					
					if ($status) {
						if ($coupon_info['type'] == 'F') {
							$discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
						} elseif ($coupon_info['type'] == 'P') {
							$discount = $product['total'] / 100 * $coupon_info['discount'];
						} elseif ($coupon_info['type'] == 'R') {
						    $discount = min($product['price'], $coupon_info['discount']);
						}
				
						if ($product['tax_class_id']) {
							$taxes[$product['tax_class_id']] -= ($product['total'] / 100 * $this->tax->getRate($product['tax_class_id'])) - (($product['total'] - $discount) / 100 * $this->tax->getRate($product['tax_class_id']));
						}
					}
					
					$discount_total += $discount;
				}
*/				
				/*  取消税费计算   CWW 2015.5.23
				if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
					if (isset($this->session->data['shipping_method']['tax_class_id']) && $this->session->data['shipping_method']['tax_class_id']) {
						$taxes[$this->session->data['shipping_method']['tax_class_id']] -= $this->session->data['shipping_method']['cost'] / 100 * $this->tax->getRate($this->session->data['shipping_method']['tax_class_id']);
					}
					
					$discount_total += $this->session->data['shipping_method']['cost'];				
				}			*/	
      			
				$total_data[] = array(
					'code'       => 'coupon',
        			'title'      =>  sprintf($this->language->get('text_coupon'), $coupon_info['name'], $coupon_info['coupon_customer_id']),
	    			'text'       => $this->currency->format(-$discount_total),
        			'value'      => -$discount_total,
					'sort_order' => $this->config->get('coupon_sort_order')
      			);

				$total['discount'] += $discount_total;
				 
				$total['total']=$total['promotion']+$total['general']+$total['fee']-$total['discount'];
			} 
		}
	}
	
	// 改为绑定ID
	public function confirm($order_info, $order_total) {
		$coupon_customer_id = '';
		
		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');
		
		if ($start && $end) {  
			$coupon_customer_id = substr($order_total['title'], $start, $end - $start);
		}	
		
		
		$this->load->model('checkout/coupon');
		//Add new method  getCodeCoupon when confirm the order
		//$coupon_info = $this->model_checkout_coupon->getCodeCoupon($code);
		
   		
	    if($coupon_customer_id ){
	        //更新HISTORY记录
			$this->model_checkout_coupon->redeem($coupon_customer_id, $order_info['order_id'], $order_info['customer_id'], $order_total['value']);	
		}						
	}
}
?>