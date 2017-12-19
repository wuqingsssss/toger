<?php  
class ControllerModuleCart extends Controller {
	protected function index() {
		$this->load_language('module/cart');
		
	$this->data['products'] = array();

		foreach ($this->cart->getProducts() as $result) {
			$image=resizeThumbImage($result['image'], 40, 40,TRUE);
			
			$option_data = array();

			foreach ($result['option'] as $option) {
				if ($option['type'] != 'file') {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (strlen($option['option_value']) > 20 ? substr($option['option_value'], 0, 20) . '..' : $option['option_value'])
					);
				} else {
					$this->load->library('encryption');
					
					$encryption = new Encryption($this->config->get('config_encryption'));
					
					$file = substr($encryption->decrypt($option['option_value']), 0, strrpos($encryption->decrypt($option['option_value']), '.'));
					
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (strlen($file) > 20 ? substr($file, 0, 20) . '..' : $file)
					);					
				}
			}
				
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$total = $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$total = false;
			}
				
			$this->data['products'][] = array(
				'product_id'      => $result['product_id'],
				'key'      => $result['key'],
				'thumb'    => $image,
				'name'     => $result['name'],
				'model'    => $result['model'],
				'option'   => $option_data,
				'quantity' => $result['quantity'],
				'stock'    => $result['stock'],
				'price'    => $price,
				'additional' => $result['additional'],
				'total'    => $total,
				'href'     => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}
		
		// Gift Voucher
		$this->data['vouchers'] = array();
		
		if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$this->data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'])
				);
			}
		} 
		
		// Calculate Totals
		$total_data = array();	
		$total=array();
			$total['promotion'] = 0;
			$total['general'] = 0;
			$total['fee']=0;
			$total['discount']=0;
			$total['total']=0;
		$taxes = $this->cart->getTaxes();
		
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {						 
			$this->load->model('setting/extension');
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);

				}
			}
			
			$sort_order = array(); 
		  
			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $total_data);
		}
					
		$json['total'] = sprintf($this->language->get('text_items'),$this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total['total']));
		
		$this->data['totals'] = $total_data;
		
		$this->data['checkout'] = $this->url->link('checkout/cart', '', 'SSL');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cart.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/cart.tpl';
		} else {
			$this->template = 'default/template/module/cart.tpl';
		}
		
		$this->render();
	}
}
?>