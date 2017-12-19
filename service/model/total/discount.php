<?php
class ModelTotalDiscount extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        $this->load->language('total/discount');
        $discount = 0;
        $text  = '';
        $title = '';
        $type      = $this->config->get('discount_type');
        $value     = $this->config->get('discount_value');
        $cond      = $this->config->get('discount_condition');
        $title     = $this->config->get('discount_name');
        $disc      = intval($value);
    
        //判断活动折扣类型
        if(!isset($this->session->data['discount']) || ($this->session->data['discount'] != $cond))
            return;
    
        $this->log_order->info($disc. "-".$cond. "-".$this->session->data['discount'] ."-". $type);
        if($type == 'F'){  // 现金折扣
            $discount = $disc;
            if($total['total']<=$disc)
                $discount = $total['total'];
        }
        elseif($type=='P'){             // 百分比折扣
            $discount = $disc*$total['total']/100;
        }
        else{
           return;
        }
    
        // 无折扣则不显示
        if( $discount==0 ){
           return;
        }
        
        //$title = sprintf($this->language->get('text_discount'));
    
        $text = $this->currency->format(-$discount);
        
        $total_data[] = array(
            'code'       => 'discount',
            'title'      => $title,
            'text'       => $text,
            'value'      => -$discount,
            'sort_order' => $this->config->get('discount_sort_order')
        );
        $total['discount']+= $discount;
        $total['total']=$total['promotion']+$total['general']+$total['fee']-$total['discount'];
    
    }
}
?>