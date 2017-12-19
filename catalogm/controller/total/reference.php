<?php 
class ControllerTotalReference extends Controller {
    public function index() {
        $this->language->load('total/reference');

        $this->data['module_title'] = $this->language->get('heading_title');

        $this->data['entry_reference'] = $this->language->get('entry_reference');

        $this->data['button_reference'] = $this->language->get('button_reference');




        // var_dump($this->data['coupon_info']);

        $this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/reference.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/total/reference.tpl';
        } else {
            $this->template = 'default/template/total/reference.tpl';
        }

        $this->render();
    }

    public function calculate() {
        $json = array();
        $this->language->load('total/reference');

        if (isset($this->request->post['reference'])&&$this->request->post['reference']) {
            //$this->load->model('campaign/reference');
            $this->load->model('account/customer');
            $re_code = $this->db->escape($this->request->post['reference']);
            $result  = $this->model_account_customer->getReference($re_code);
            if($result) {         	
                if(isset($this->session->data['order_id']))
                {
                   $order_id=$this->session->data['order_id'];
                   $customer_id=$this->customer->getId();
                	$tmp=$this->db->query("select order_id from " . DB_PREFIX . "reference where order_id='{$order_id}'");
                	if(!$tmp->row){
                		$this->log_sys->info("推荐码::insert::reference:".$re_code.':$customer_id'.$customer_id);
                		$this->db->query("insert into " . DB_PREFIX . "reference set order_id ='{$order_id}',code='{$re_code}', customer_id='{$customer_id}', status=1, date_added = NOW()");
                	}else{
                		$this->log_sys->info("推荐码::update::reference:".$re_code.':$customer_id'.$customer_id);
                		$this->db->query("UPDATE " . DB_PREFIX . "reference SET  code='{$re_code}' WHERE order_id = '" . $order_id . "'");	
                	}
                }
                $this->session->data['discount']  = "groupdiscount";
            	$this->session->data['reference'] = $this->db->escape($this->request->post['reference']);
           
     			$this->session->data['success'] = $this->language->get('text_success');
                $json['success'] = $this->language->get('text_success');
                $json['name']    = $result['name'];
                $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
            }else{
                $json['error'] = $this->language->get('error_reference_new');
            }

            } else {
                $json['error'] = $this->language->get('error_reference');
            }

            echo json_encode($json);
        }

}
?>