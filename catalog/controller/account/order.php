<?php

class ControllerAccountOrder extends Controller {
    private $error = array();
    // if you modify here, you need also modify ControllerCheckoutCheckout
    private $direct_payments = array('cod','cash', 'cheque', 'free_checkout', 'bank_transfer');
    private $cancel_order_partner=array('','0');
    protected function init() {
    
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }
		//增加订单时间判定，超过半小时的未付款订单自动取消
		$this->load->model('account/order');
		$this->load->model('account/coupon');
		$filter=array(
			'filter_timelimit' => true, //30分钟后自动失效
			'filter_order_status' => $this->config->get('config_order_nopay_status_id')
		);
		//获取当前用户未付款订单列表
		
		$unpay_orders=$this->model_account_order->getOrders($filter);
		
		//取消超过30分钟未付款订单
		if ($unpay_orders) {
			$order_ids = array_column($unpay_orders, 'order_id');
			$this->model_account_order->editOrderStatus($order_ids, $this->config->get('config_order_cancel_status_id'));
			//如果订单中 有使用优惠卷的 则退回
			$this->model_account_coupon->return_coupon($order_ids);
		}
		$this->load_language('account/order');
    }

    private function isExpired($date_added){  
        $nextPurchaseTime=clone $date_added;
        $nextPurchaseTime->setTime(4,0,0);
        if($date_added>$nextPurchaseTime){
            $nextPurchaseTime->modify('+1 day');
        }

        $now=new DateTime();
        return $now>$nextPurchaseTime;
    }
    
    public function getTotalOrderCount($order_status_id=null){

    	$filter = array(
    			'filter_order_status' => $order_status_id
    	);
    
    	return $this->model_account_order->getTotalOrders($filter);
    }
    
    
    public function index() {
        $this->init();

        $heading_title = $this->language->get('heading_title');

        if (isset($this->request->get['filter_order_status'])) {
            $heading_title = EnumOrderStatus::getOrderStatusTitle($this->request->get['filter_order_status']);
        }
        elseif(isset($this->request->get['filter_order_status_ids']))
         {
         	$heading_title="进行中";
         }

        $this->document->setTitle($heading_title);

        $this->data['heading_title'] = $heading_title;

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $heading_title,
            'href' => $this->url->link('account/order', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['action'] = $this->url->link('account/order', '', 'SSL');

        $this->load->model('account/order');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_order_status_ids'])) {
        	$filter_order_status_ids =explode(',',$this->request->get['filter_order_status_ids']);
        } else {
        	$filter_order_status_ids = null;
        }
        if (isset($this->request->get['filter_not_order_status_ids'])) {
        	$filter_not_order_status_ids =explode(',',$this->request->get['filter_not_order_status_ids']);
        } else {
        	$filter_not_order_status_ids = null;
        }

        $limit = $this->config->get('config_catalog_limit');

        $filter = array(
            'filter_order_status' => $filter_order_status,
        	'filter_order_status_ids' => $filter_order_status_ids,
        	'filter_not_order_status_ids' => $filter_not_order_status_ids,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );

        $this->data['orders'] = array();

        
        
        $order_total = $this->model_account_order->getTotalOrders($filter);
      
        $results = $this->model_account_order->getOrders($filter);

        $common = new Common($this->registry);

        foreach ($results as $result) {
            $actions = array();

            $actions[] = array(
                'text' => $this->language->get('button_view'),
                'href' => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL')
            );

            $cancel_order_array = array($this->config->get('config_order_nopay_status_id'));
            $cancel = in_array($result['order_status_id'], $cancel_order_array)&& in_array($order_info['partner_code'], $this->cancel_order_partner);
            $cancelExpired = $cancel && $this->isExpired(new DateTime($result['date_added']));

            $refund_order_array = array($this->config->get('config_order_status_id'));

            if (in_array($result['order_status_id'], $refund_order_array)) {
                $refund = true;
            } else {
                $refund = false;
            }

            $sub_results = $this->model_account_order->getSubOrders($result['order_id']);

            $count = count($sub_results);

            if ($count > 0)
                $this->data['porders'][$result['order_id']] = $count;

            $total=$result['total'];
            if( !empty($result['p_order_id'])){
                $parentOrder=$this->model_account_order->getOrder($result['p_order_id']);
                $total=$parentOrder['total'];
            }


            //	if($result['p_order_id']=='') {
            $this->data['orders'][] = array(
                'order_id' => $result['order_id'],
                'p_order_id' => $result['p_order_id'],
                'count' => $count,
                'pdate' => $result['pdate'],
                //	'sub_orders'   => $sub_results,
                'customer' => $result['firstname'],
            		'shipping_point_id'   => $result['shipping_point_id'],
            		'shipping_time'   => $result['shipping_time'],
                //				'name'       => $result['firstname'] . ' ' . $result['lastname'],
                'status' => $result['status'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'products' => $this->getOrderProducts($result['order_id']),
//                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'total' => $this->currency->format($total, $result['currency_code'], $result['currency_value']),
                'view' => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
                'cancel' => $cancel,
                'cancelExpired' => $cancelExpired,
                'refund' => $refund,
            );
            //	}
        }


        $this->data['cancel'] = $this->url->link('account/order/cancel', '', 'SSL');
        $this->data['refund'] = $this->url->link('account/order/refund', '', 'SSL');

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_catalog_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('account/order', 'page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/order_list.tpl';
        } else {
            $this->template = 'default/template/account/order_list.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

    public function info() {
        $this->init();

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $this->load->model('account/order');

        $order_info = $this->model_account_order->getOrder($order_id);

        if ($order_info) {
            $this->document->setTitle($this->language->get('text_order'));

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
                'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/order', $url, 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_order'),
                'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
            } else {
                $this->data['error_warning'] = '';
            }

            if ($order_info['invoice_no']) {
                $this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
            } else {
                $this->data['invoice_no'] = '';
            }
            $common = new Common($this->registry);
            $this->data['order_id'] = $this->request->get['order_id'];
            $this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

            $this->data['order_status_id'] = $order_info['order_status_id'];


            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . "\n" . '{zone}' . '{city}' . '{address_1}' . "\n" . '{address_2}' . "\n" . ' {postcode} ' . "\n" . ' {mobile}/ {phone}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{zone}',
                '{city}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{postcode}',
                '{mobile}',
                '{phone}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname' => $order_info['shipping_lastname'],
                'zone' => $order_info['shipping_zone'],
                'city' => $order_info['shipping_city'],
                'company' => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'postcode' => $order_info['shipping_postcode'],
                'mobile' => $order_info['shipping_mobile'],
                'phone' => $order_info['shipping_phone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country' => $order_info['shipping_country']
            );


            if ($order_info['shipping_firstname'] == '')
                $this->data['shipping_required'] = 0;
            else
                $this->data['shipping_required'] = 1;

            $this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->data['shipping_method'] = $order_info['shipping_method'];

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'lastname' => $order_info['payment_lastname'],
                'company' => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city' => $order_info['payment_city'],
                'postcode' => $order_info['payment_postcode'],
                'zone' => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country' => $order_info['payment_country']
            );

            $this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->data['payment_method'] = $order_info['payment_method'];

            if ($order_info['payment_code'] != '' && !in_array($order_info['payment_code'], $this->direct_payments) && $order_info['order_status_id'] == $this->config->get('config_order_nopay_status_id'))
                $this->data['payment'] = $this->getChild('payment/' . $order_info['payment_code'] . '/reorder');
            else
                $this->data['payment'] = '';

            if (isset($order_info['express'])) {
                $this->data['express'] = $order_info['express'];
                $this->data['express_website'] = $order_info['express_website'];
                $this->data['express_no'] = $order_info['express_no'];
            }

            if ($order_info['certification']) {
                $this->data['certification'] = HTTP_SERVER . 'download/order-certification/' . $this->encryption->decrypt($order_info['certification']);
            } else {
                $this->data['certification'] = NULL;
            }

            $this->data['products'] = array();

            $products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']),
                        );
                    } else {
                        $filename = substr($option['value'], 0, strrpos($option['value'], '.'));

                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => (strlen($filename) > 20 ? substr($filename, 0, 20) . '..' : $filename)
                        );
                    }
                }


                $this->data['products'][] = array(
                    'order_product_id' => $product['order_product_id'],
                    'name' => $product['name'],
                    'pdate' => $product['pdate'],
                    'model' => $product['model'],
                    'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
                    'selected' => isset($this->request->post['selected']) && in_array($result['order_product_id'], $this->request->post['selected'])
                );
            }

            $this->data['groups'] = array();

            foreach ($this->data['products'] as $result) {
                if (isset($result['pdate']) && $result['pdate']) {
                    $this->data['groups'][$result['pdate']][] = $result;
                } else {
                    $this->data['groups'][0][] = $result;
                }
            }

            ksort($this->data['groups']);

            if ($order_info['p_order_id'] == 0)
                $this->data['totals'] = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
            else
                $this->data['totals'] = $this->model_account_order->getOrderTotals($order_info['p_order_id']);

            $this->data['comment'] = $order_info['comment'];

            $this->data['p_order_id'] = $order_info['p_order_id'];
            $this->data['pdate'] = $order_info['pdate'];
            $this->data['pickup_code'] = $order_info['pickup_code'];
            $this->data['shipping_point_id'] = $order_info['shipping_point_id'];
            $this->data['shipping_code'] = $order_info['shipping_code'];
            $this->data['shipping_data'] = $order_info['shipping_data'];
            $this->data['shipping_address_1'] = $order_info['shipping_address_1'];
            $this->data['shipping_address_2'] = $order_info['shipping_address_2'];
            $this->data['shipping_time'] = $order_info['shipping_time'];
            $this->data['shipping_firstname'] = $order_info['shipping_firstname'];
            $this->data['shipping_mobile'] = $order_info['shipping_mobile'];
            $this->data['telephone'] = $order_info['telephone'];

            $this->data['histories'] = array();

            if($order_info['shipping_point_id']){
            
            $this->load->model('catalog/point');
            $pointInfo =$this->model_catalog_point->getPoint($order_info['shipping_point_id']);
            $this->data['pointinfo'] = $pointInfo;
  
            }
            $results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

            foreach ($results as $result) {
                $this->data['histories'][] = array(
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'status' => $result['status'],
                    'comment' => nl2br($result['comment'])
                );
            }

            $this->data['continue'] = $this->url->link('account/order', '', 'SSL');

            $cancel_order_array = array($this->config->get('config_order_nopay_status_id'));
            $cancel = in_array($order_info['order_status_id'], $cancel_order_array)&& in_array($order_info['partner_code'], $this->cancel_order_partner);
            $cancelExpired = $cancel && $this->isExpired(new DateTime($order_info['date_added']));
            $this->data['cancelable']= $cancel && !$cancelExpired;
            $this->data['link_cancel'] = $this->url->link('account/order/cancel', '', 'SSL');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/account/order_info.tpl';
            } else {
                $this->template = 'default/template/account/order_info.tpl';
            }

            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );

            $this->response->setOutput($this->render());
        } else {
            $this->document->setTitle($this->language->get('text_order'));

            $this->data['heading_title'] = $this->language->get('text_order');

            $this->data['text_error'] = $this->language->get('text_error');

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
                'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/order', '', 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_order'),
                'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['continue'] = $this->url->link('account/order', '', 'SSL');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
            } else {
                $this->template = 'default/template/error/not_found.tpl';
            }

            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );

            $this->response->setOutput($this->render());
        }
    }

    private function validate() {
        if (!isset($this->request->post['selected']) || !isset($this->request->post['action']) || !$this->request->post['action']) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function cancel() {
        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $order_id = $this->request->post['order_id'];

            $cancel_order_status_id = $this->config->get('config_order_cancel_status_id');

            $this->load->model('account/order');

            $this->model_account_order->editOrderStatus($order_id, $cancel_order_status_id);

            $json['success'] = true;
        }

        $this->load->library('json');

        $this->response->setOutput(Json::encode($json));
    }

    private function getOrderProducts($order_id) {
        $this->load->model('account/order');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $order_info = $this->model_account_order->getOrder($order_id);

        $results = array();

        $products = $this->model_account_order->getOrderProducts($order_id);

        foreach ($products as $product) {
            $option_data = array();

            $options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

            foreach ($options as $option) {
                if ($option['type'] != 'file') {
                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']),
                    );
                } else {
                    $filename = substr($option['value'], 0, strrpos($option['value'], '.'));

                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => (strlen($filename) > 20 ? substr($filename, 0, 20) . '..' : $filename)
                    );
                }
            }

            $product_info = $this->model_catalog_product->getProduct($product['product_id']);

            if ($product_info) {
                $image = $product_info['image'];
            } else {
                $image = false;
            }

            if ($image && file_exists(DIR_IMAGE . $image)) {
                $thumb = $this->model_tool_image->resize($image, 60, 60);
            } else {
                $thumb = false;
            }

            $results[] = array(
                'order_product_id' => $product['order_product_id'],
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'model' => $product['model'],
                'thumb' => $thumb,
                'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                'option' => $option_data,
                'quantity' => $product['quantity'],
                'price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
                'total' => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
                'selected' => isset($this->request->post['selected']) && in_array($result['order_product_id'], $this->request->post['selected'])
            );
        }

        return $results;
    }

    private function getOrderId() {
        return $this->request->get['order_id'];
    }

    public function refund() {
        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $order_id = $this->request->post['order_id'];
            $reason = $this->request->post['reason'];

            $order_status_id = $this->config->get('config_order_refund_status_id');

            $this->load->model('account/order');

            $this->model_account_order->refundOrder($order_id, $order_status_id,$reason);

            $json['success'] = true;
        }

        $this->load->library('json');

        $this->response->setOutput(Json::encode($json));
    }

}

?>