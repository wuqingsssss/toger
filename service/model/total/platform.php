<?php
class ModelTotalPlatform extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        $this->load->language('total/platform');
        $discount = 0;
        $text  = '';
        $title = '';

         //$total['total']  = $this->session->data['platformpparam']['total_value'];
         if(isset($this->session->data['platformpparam'])){
          $discount   = $this->session->data['platformpparam']['total_discount'];
          $subsides   = $this->session->data['platformpparam']['total_subsides'];
          $fee   = $this->session->data['platformpparam']['total_fee'];

        $this->log_sys->info($this->session->data['platformpparam']['partner_code'] ."-". $total.'-'. $disc);
      

         $total_data [] = array (
        		'code' => 'fee',
        		'title' => $this->language->get ( 'text_platform_fee' ),
        		'text' => $this->currency->format ( max ( 0, $fee ) ),
        		'value' => max ( 0, $fee ),
        		'sort_order' => $this->config->get ( 'platform_sort_order' )
        );
        
        $total_data[] = array(
            'code'       => 'platform_dicount',
            'title'      => sprintf($this->language->get('text_platform_discount')),
            'text'       => $this->currency->format(-$discount),
            'value'      => -$discount,
            'sort_order' => $this->config->get('platform_sort_order')
        );
        
        $platform_total=(isset ( $this->session->data ['platformpparam'] ['total_value'] ) && $this->session->data ['platformpparam'] ['total_value'])?$this->session->data ['platformpparam'] ['total_value']:($total['total']-$discount);
        

        $total_data[] = array(
        		'code'       => 'platform_total',
        		'title'      => sprintf($this->language->get('text_platform_total')),
        		'text'       => $this->currency->format(+$platform_total),
        		'value'      => $platform_total,
        		'sort_order' => $this->config->get('platform_sort_order')
        );

        $total_data[] = array(
        		'code'       => 'platform_subsides',
        		'title'      => sprintf($this->language->get('text_platform_subsides')),
        		'text'       => $this->currency->format(+$subsides),
        		'value'      => +$subsides,
        		'sort_order' => $this->config->get('platform_sort_order')
        );
        
    
        
        
        $total['discount']+= $discount-$subsides;
        
        
        
        $total['fee']+= $fee;
 
        $total['total']=$total['promotion']+$total['general']+$total['fee']-$total['discount'];

         }
         else {
         	
         	return;
         }
    }
}
?>