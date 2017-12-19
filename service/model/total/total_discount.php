<?php
class ModelTotalTotalDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language ( 'total/total_discount' );
		$discount = 0;
		$text = '';
		$title = '';
		$step1 = $this->config->get ( 'total_discount_step1' );
		$discount1 = $this->config->get ( 'total_discount_discount1' );
		$step2 = $this->config->get ( 'total_discount_step2' );
		$discount2 = $this->config->get ( 'total_discount_discount2' );
		$step3 = $this->config->get ( 'total_discount_step3' );
		$discount3 = $this->config->get ( 'total_discount_discount3' );
		$date_start = $this->config->get ( 'total_discount_start_date' );
		$date_end = $this->config->get ( 'total_discount_end_date' );
		$today = date ( 'Y-m-d', time () );
		$step1val = intval ( $step1 );
		$step2val = intval ( $step2 );
		$step3val = intval ( $step3 );
		$disc1 = intval ( $discount1 );
		$disc2 = intval ( $discount2 );
		$disc3 = intval ( $discount3 );
		
		// 判断满减活动期间
		if ($date_start > $today)
			return;
		
		if ($date_end < $today)
			return;
		
		if ($step1val <= 0) {
			return;
		} else {
			if ($total['general'] < $step1val) {
				$discount = 0;
				$title_pre = sprintf ( $this->language->get ( 'text_total_prediscount' ), $step1, $discount1, $this->currency->format ( $step1val - $total['general'] ) );
			} elseif ($total['general'] >= $step1val) {
				$discount = $disc1;
				$title = sprintf ( $this->language->get ( 'text_total_discount' ), $step1, $discount1 );
				$text = $this->currency->format ( - $discount );
			
			
			if ($step2val > $step1val) {
				if ($total['general'] >= $step2val) {
					$discount = $disc2;
					$title = sprintf ( $this->language->get ( 'text_total_discount' ), $step2, $discount2 );
					$text = $this->currency->format ( - $discount );
				}
				else
				{
					$title_pre = sprintf ( $this->language->get ( 'text_total_prediscount' ), $step2, $discount2, $this->currency->format ( $step2val - $total['general'] ) );
		}
				
				if ($step3val > $step2val)
					if ($total['general'] >= $step3val) {
						$discount = $disc3;
						$title = sprintf ( $this->language->get ( 'text_total_discount' ), $step3, $discount3 );
						$text = $this->currency->format ( - $discount );
					}
				    else {
				    	$title_pre = sprintf ( $this->language->get ( 'text_total_prediscount' ), $step3, $discount3, $this->currency->format ( $step3val - $total['general'] ) );
				    	
				    	
				    }
			}
			}
		}
		
		$this->session->data['total_discount']=$title;
		if(isset($this->session->data['coupon']))
		{

			$title = sprintf ( $this->language->get ( 'text_total_discount' ), $step3, $discount3 );
			$text = $this->currency->format ( - 0 );
		}
		else {
			
		if($title&&$text&&$discount){
		$total_data [] = array (
				'code' => 'total_discount',
				'title' => $title,
				'text' => $text,
				'value' => - $discount,
				'sort_order' => $this->config->get ( 'total_discount_sort_order' ) 
		);
		$total['discount'] += $discount;
		$total['total']=$total['promotion']+$total['general']+$total['fee']-$total['discount'];
		}
		
		if($title_pre){
			$total_data [] = array (
					'code' => 'total_discount',
					'title' => $title_pre,
					'text' => '',
					'value' =>0,
					'sort_order' => $this->config->get ( 'total_discount_sort_order' )+0.1
			);
			
		}
			
		}
	}
}
?>