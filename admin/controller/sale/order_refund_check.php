<?php

class ControllerSaleOrderRefundCheck extends Controller {
    private $error = array();

    public function index() {
    	if(!$this->user->hasPermission('access','sale/order_refund_check')){
    		return $this->forward('error/permission');
    	}
        $this->load_language('sale/order_refund_check');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/order');
        $this->load->model('sale/order_refund');
        $this->load->service('payment/wxpay/wxpay','service');
        $this->getList();
    }
	public function confirm() {
		$this->load->model ( 'sale/order_refund' );
		$json = array ();
		
		
		if ($this->request->get ['order_refund_id']) {
			$order_refund_id [] = $this->request->get ['order_refund_id'];
			$status = $this->request->post ['status'];
			$reason = $this->request->post ['reason'];
			
			if ($status == 'PHASE1_REFUSED' || $status == 'PHASE1_PASSED') {
				
				if($this->user->hasPermission('phase1','sale/order_refund_check')){
					$this->model_sale_order_refund->updatePhase1 ( $order_refund_id, $status, $reason );
					$json ['success'] = true;
				}
				else {
					$json ['success'] = false;
					$json ['message']="权限不足";
				}

			} else if ($status == 'PHASE2_REFUSED' || $status == 'PHASE2_PASSED') {
				
				if($this->user->hasPermission('phase2','sale/order_refund_check')){
					$this->model_sale_order_refund->updatePhase2 ( $order_refund_id, $status, $reason);
					$json ['success'] = true;
				}
				else {
					$json ['success'] = false;
					$json ['message']="权限不足";
				}
			}

			$this->load->library ( 'json' );
			$this->response->setOutput ( Json::encode ( $json ) );
			
		} elseif ($this->request->post ['order_refund_id']) {
			$order_refund_id = $this->request->post ['order_refund_id'];
			$status = $this->request->get ['status'];
			
			if($order_refund_id){
			if ($status == 'PHASE1_PASSED') {
				if($this->user->hasPermission('phase1','sale/order_refund_check')){
					$this->model_sale_order_refund->updatePhase1 ( $order_refund_id, $status, $reason);	
				}
				else {
					return $this->forward('error/permission');
				}
				
			} else if ($status == 'PHASE2_PASSED') {
				
				if($this->user->hasPermission('phase2','sale/order_refund_check')){
					$this->model_sale_order_refund->updatePhase2 ( $order_refund_id, $status, $reason);
				}
				else {
					return $this->forward('error/permission');
				}
			}
			else if ($status == 'ERROR') {
				if($this->user->hasPermission('modify','sale/order_refund')){
					$this->model_sale_order_refund->updateStatus($order_refund_id,'ERROR','未得到响应');
				}
				else {
					return $this->forward('error/permission');
				}
			}else if ($status == 'DONE'||$status == 'FAIL') {
				if($this->user->hasPermission('modify','sale/order_refund')){
					$this->model_sale_order_refund->updateStatus($order_refund_id,'FAIL');
				}
				else {
					return $this->forward('error/permission');
				}
			}
			}
			$this->redirect ( $this->url->link ( 'sale/order_refund_check', 'token=' . $this->session->data ['token'] . '&filter_order_refund_status=' . $status, 'SSL' ) );
		}
		else
		{
			return $this->forward('error/msg',array('msg'=>'数据错误'));
		}
	}

    private function getList() {
    	
        $req = $this->request;
        $PAGE_URL = 'sale/order_refund_check';
        $DETAIL_PAGE_URL = 'sale/order_refund_check/info';

        $FILTER_PAYMENT_CODE = 'filter_payment_code';
        $FILTER_ORDER_ID     = 'filter_order_id';
        $FILTER_ORDER_REFUND_ID     = 'filter_order_refund_id';
        $FILTER_PARTNER_CODE = 'filter_partner_code';
        $FILTER_CUSTOMER     = 'filter_customer';
        $FILTER_CUSTOMER_PHONE = 'filter_customer_phone';
        $FILTER_ORDER_STATUS_ID = 'filter_order_status_id';
        $FILTER_ORDER_REFUND_STATUS = 'filter_order_refund_status';
        $FILTER_TOTAL = 'filter_total';
        $FILTER_DATE_ADDED = 'filter_date_added';
        $FILTER_DATE_MODIFIED = 'filter_date_modified';
        $SORT = 'sort';
        $ORDER = 'order';
        $PAGE = 'page';
        $TOKEN = 'token';

        
        $payments=array();
        $payments['alipay']=array(
        		'title'=>'支付宝',
        		'action'=>$this->url->link($PAGE_URL, 'token=' . $this->session->data[$TOKEN] . '&filter_payment_code=alipay' , 'SSL'),
        		'code'=>'alipay'
        );
        $payments['wxpay']=array(
        		'title'=>'微信支付',
        		'action'=>$this->url->link($PAGE_URL, 'token=' . $this->session->data[$TOKEN] . '&filter_payment_code=wxpay' , 'SSL'),
        		'code'=>'wxpay'
        );
        $payments['balance']=array(
        		'title'=>'储值支付',
        		'action'=>$this->url->link($PAGE_URL, 'token=' . $this->session->data[$TOKEN] . '&filter_payment_code=balance' , 'SSL'),
        		'code'=>'balance'
        );
        /*payment
        $payments['banck']=array(
        		'title'=>'银行转账',
        		'action'=>$this->url->link($PAGE_URL, 'token=' . $this->session->data[$TOKEN] . '&filter_payment_code=banck', 'SSL'),
        		'code'=>'banck'
        );
        */
        
        $this->data['payments']=$payments;
        
        
        
        $paramsMeta = array(
        	array('get', $FILTER_PAYMENT_CODE, null),
            array('get', $FILTER_ORDER_ID, null),
        	array('get', $FILTER_ORDER_REFUND_ID, null),
            array('get', $FILTER_PARTNER_CODE, null),
            array('get', $FILTER_CUSTOMER, null),
            array('get', $FILTER_CUSTOMER_PHONE, null),
        	array('get', $FILTER_ORDER_REFUND_STATUS, 'PENDING'),
            array('get', $FILTER_ORDER_STATUS_ID, 13),
            array('get', $FILTER_TOTAL, null),
            array('get', $FILTER_DATE_ADDED, null),
            array('get', $FILTER_DATE_MODIFIED, null),
            array('get', $SORT, 'o.order_id'),
            array('get', $ORDER, 'DESC'),
            array('get', $PAGE, 1),
        );

        $queryParams = ReqHelper::parseQueryParams($req, $paramsMeta);
        $queryParams[$TOKEN] = $this->session->data[$TOKEN];
        if($queryParams[$FILTER_ORDER_STATUS_ID]=='*'){
            $queryParams[$FILTER_ORDER_STATUS_ID]=null;
        }


        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', ReqHelper::joinQueryParams($queryParams, array($TOKEN)), 'SSL'),
            'separator' => false
        );

        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $TOKEN,
        	$FILTER_PAYMENT_CODE,
            $FILTER_ORDER_ID, 
        	$FILTER_ORDER_REFUND_ID,
            $FILTER_CUSTOMER_PHONE,
            $FILTER_CUSTOMER,
            $FILTER_PARTNER_CODE,
            $FILTER_ORDER_STATUS_ID,
        	$FILTER_ORDER_REFUND_STATUS,
            $FILTER_TOTAL,
            $FILTER_DATE_ADDED,
            $FILTER_DATE_MODIFIED,
            $SORT,
            $ORDER,
            $PAGE
        ));

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($PAGE_URL, $queryUrlPart, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->data['orders'] = array();

        $data = array(
        	$FILTER_PAYMENT_CODE=>$queryParams[$FILTER_PAYMENT_CODE],
            $FILTER_ORDER_ID => $queryParams[$FILTER_ORDER_ID],
        	$FILTER_ORDER_REFUND_ID => $queryParams[$FILTER_ORDER_REFUND_ID],
            $FILTER_CUSTOMER => $queryParams[$FILTER_CUSTOMER],
            $FILTER_CUSTOMER_PHONE => $queryParams[$FILTER_CUSTOMER_PHONE],
            $FILTER_ORDER_STATUS_ID => $queryParams[$FILTER_ORDER_STATUS_ID],
        	$FILTER_ORDER_REFUND_STATUS => $queryParams[$FILTER_ORDER_REFUND_STATUS],
            'filter_order_refund' => true,
            $FILTER_TOTAL => $queryParams[$FILTER_TOTAL],
            $FILTER_DATE_ADDED => $queryParams[$FILTER_DATE_ADDED],
            $FILTER_DATE_MODIFIED => $queryParams[$FILTER_DATE_MODIFIED],
            $FILTER_PARTNER_CODE => $queryParams[$FILTER_PARTNER_CODE],
            $SORT => $queryParams[$SORT],
            $ORDER => $queryParams[$ORDER],
            'start' => ($queryParams[$PAGE] - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $order_total = $this->model_sale_order_refund->getTotalOrderRefunds($data);

        $results = $this->model_sale_order_refund->getOrderRefunds($data);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
            		'text' => "订单",
            		'href' => $this->url->link('sale/order/info', 'token=' . $this->session->data[$TOKEN] . '&order_id=' . $result['order_id'] . $queryUrlPart, 'SSL')
            );
            $action[] = array(
                'text' => '退款单',
                'href' => $this->url->link($DETAIL_PAGE_URL, 'token=' . $this->session->data[$TOKEN] . '&order_refund_id=' . $result['order_refund_id'] . $queryUrlPart, 'SSL')
            );
            $refundQueryResult=array();
            if($result['status']=='PAYING'&&$result['payment_code']=='wxpay')
            {//如果是微信支付，则实时查询退款状态并处理
            	$refundQueryResult=$this->service_payment_wxpay_wxpay->reFundQuery($result);
            }
            elseif($result['status']=='PAYING'&&$result['payment_code']=='alipay'){
                //如果是支付宝，则对回调是否超时进行状态error处理
                if($result['modify_at']&&(strtotime($result['modify_at'])+7200)>time()){//如果等待时间超过2小时则认为提交异常
            	  $this->model_sale_order_refund->updateStatus($order_refund['order_refund_id'],'ERROR','未得支付宝到响应');
                }
            }
            
            $this->data['orders'][] = array(
            	'order_refund_id' => $result['order_refund_id'],
                'order_id' => $result['order_id'],
                'p_order_id' => $result['p_order_id'],
                'pdate' => strtotime($result['pdate'])?$result['pdate']:(strtotime($result['shipping_time'])?$result['shipping_time']:''),
                'customer' => $result['email'],
                'telephone' => $result['telephone'],//TODO
                'status' => $result['status'],
            	 'commnet' => $result['commnet'],
            	'payment_code' => $result['payment_code'],
            		'payment_code1' => $result['payment_code1'],
            		'payment_account' => $result['payment_account'],
            		'payment_name' => $result['payment_name'],
            		'payment_trade_no' => $result['payment_trade_no'],
                'partner' => $result['partner_code'] ? $result['partner_code'] : "内站",
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
            	'value' => $this->currency->format($result['value'], $result['currency_code'], $result['currency_value']),
            	'value1' => $this->currency->format($result['value1'], $result['currency_code1'], $result['currency_value1']),
            	'reason' => $result['reason'],
            	'phase1_refused_reason' => $result['phase1_refused_reason'],
            	'phase2_refused_reason' => $result['phase2_refused_reason'],
            	'created_at' => $result['created_at'],
            	'phase1_updated_at' => $result['phase1_updated_at'],
            	'phase2_updated_at' => $result['phase2_updated_at'],
            		'phase1_user_name' => $result['phase1_user_name'],
            		'phase2_user_name' => $result['phase2_user_name'],
            	'date_added' => $result['date_added'],
                'date_modified' => $result['date_modified'],
                'selected' => isset($req->post['selected']) && in_array($result['order_id'], $req->post['selected']),
                'action' => $action,
                'rq'=>$refundQueryResult
            );
        }

        $this->data[$TOKEN] = $queryParams[$TOKEN];

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }


        $queryParams2 = $queryParams;//copy
        $queryParams2[$ORDER] = ($queryParams2[$ORDER] == 'ASC' ? 'DESC' : 'ASC');
        $queryUrlPart = ReqHelper::joinQueryParams($queryParams2, array(
            $TOKEN, 
        	$FILTER_ORDER_REFUND_ID,
        	$FILTER_PAYMENT_CODE,
        	$FILTER_PARTNER_CODE,
            $FILTER_ORDER_ID,
            $FILTER_CUSTOMER,
            $FILTER_PARTNER_CODE,
            $FILTER_ORDER_STATUS_ID,
        	$FILTER_ORDER_REFUND_STATUS,
            $FILTER_TOTAL,
            $FILTER_DATE_ADDED,
            $FILTER_DATE_MODIFIED,
            $ORDER,
            $PAGE
        ));


        $this->data['sort_order'] = $this->url->link($PAGE_URL, '&sort=o.order_id&' . $queryUrlPart, 'SSL');
        $this->data['sort_customer'] = $this->url->link($PAGE_URL, '&sort=customer&' . $queryUrlPart, 'SSL');
        $this->data['sort_status'] = $this->url->link($PAGE_URL, '&sort=status&' . $queryUrlPart, 'SSL');
        $this->data['sort_total'] = $this->url->link($PAGE_URL, '&sort=o.total&' . $queryUrlPart, 'SSL');
        $this->data['sort_date_added'] = $this->url->link($PAGE_URL, '&sort=o.date_added&' . $queryUrlPart, 'SSL');
        $this->data['sort_date_modified'] = $this->url->link($PAGE_URL, '&sort=o.date_modified&' . $queryUrlPart, 'SSL');

        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $TOKEN,
            $FILTER_ORDER_ID,
        	$FILTER_ORDER_REFUND_ID,
        	$FILTER_PAYMENT_CODE,
            $FILTER_PARTNER_CODE,
            $FILTER_CUSTOMER,
            $FILTER_ORDER_STATUS_ID,
        	$FILTER_ORDER_REFUND_STATUS,
            $FILTER_TOTAL,
            $FILTER_DATE_ADDED,
            $FILTER_DATE_MODIFIED,
            $SORT,
            $ORDER
        ));


        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $queryParams[$PAGE];
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link($PAGE_URL, $queryUrlPart . '&page={page}', 'SSL');
        
        $this->data['pagination'] = $pagination->render();

        $this->data[$FILTER_PAYMENT_CODE] = $queryParams[$FILTER_PAYMENT_CODE];
        $this->data[$FILTER_ORDER_ID] = $queryParams[$FILTER_ORDER_ID];
        $this->data[$FILTER_ORDER_REFUND_ID] = $queryParams[$FILTER_ORDER_REFUND_ID];
        $this->data[$FILTER_CUSTOMER] = $queryParams[$FILTER_CUSTOMER];
        $this->data[$FILTER_CUSTOMER_PHONE] = $queryParams[$FILTER_CUSTOMER_PHONE];

        $this->data[$FILTER_ORDER_REFUND_STATUS] = $queryParams[$FILTER_ORDER_REFUND_STATUS];
        $this->data[$FILTER_ORDER_STATUS_ID] = $queryParams[$FILTER_ORDER_STATUS_ID];
        $this->data[$FILTER_TOTAL] = $queryParams[$FILTER_TOTAL];
        $this->data[$FILTER_DATE_ADDED] = $queryParams[$FILTER_DATE_ADDED];
        $this->data[$FILTER_DATE_MODIFIED] = $queryParams[$FILTER_DATE_MODIFIED];
        $this->data[$FILTER_PARTNER_CODE] = $queryParams[$FILTER_PARTNER_CODE];

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->loadStatuses();
        
        $this->data['order_refund_statuses'] = EnumOrderRefundStatus::getOrderRefundAllStatus();
        
        $this->data[$SORT] = $queryParams[$SORT];
        $this->data[$ORDER] = $queryParams[$ORDER];
//获取第三方平台列表
		$this->load->model('catalog/partnercode');
		$this->data['partners'] = $this->model_catalog_partnercode->getAllPartners();
		
        $this->template = 'sale/order_refund_check_list.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }


    public function info() {
        $this->load->model('sale/order');
        $this->load->model('sale/order_refund');

        if (isset($this->request->get['order_refund_id'])) {
            $order_refund_id = $this->request->get['order_refund_id'];
        } else {
            $order_refund_id = 0;
        }
        
        
        
        $order_refund=$this->model_sale_order_refund->getOrderRefund($order_refund_id);
        $this->data['order_refund']= $order_refund;
        
       
        $order_id=$order_refund['order_id'];

        
        $refunds = $this->model_sale_order_refund->getOrderRefunds ( array (
        		"filter_order_id" => $order_id
        ));
          
        foreach ($refunds as $refund){
        	
        	if($refund['order_refund_id']!=$order_refund['order_refund_id'])
        	{
        		$refund['action']=$this->url->link('sale/order_refund_check/info', 'token=' . $this->session->data[token] . '&order_refund_id=' . $refund['order_refund_id'], 'SSL');
            $this->data ['refunds'][] = $refund;
        	}
        }
        
        $order_info = $this->model_sale_order->getOrder($order_id);
       
        if ($order_info) {
            $this->load_language('sale/order_refund_check');
            
            $this->document->setTitle($this->language->get('heading_title'));

            $this->data['token'] = $this->session->data['token'];

            $queryUrlPart = $this->buildPrevUrlPart();

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('sale/order_refund_check', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => $this->language->get('text_breadcrumb_separator')
            );

            $this->data['invoice'] = $this->url->link('sale/order_refund_check/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, 'SSL');
            $this->data['cancel'] = $this->url->link('sale/order_refund_check', $queryUrlPart, 'SSL');


            $this->data['order_id'] = $order_id;
            $this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
            $this->data['store_name'] = $order_info['store_name'];
            $this->data['store_url'] = $order_info['store_url'];

            if ($order_info['firstname'] == '')
                $this->data['firstname'] = $order_info['email'];
            else
                $this->data['firstname'] = $order_info['firstname'];

            $this->data['lastname'] = $order_info['lastname'];

            if ($order_info['customer_id']) {
                $this->data['customer'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], 'SSL');
            } else {
                $this->data['customer'] = '';
            }

            $this->load->model('sale/customer_group');

            $customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);

            if ($customer_group_info) {
                $this->data['customer_group'] = $customer_group_info['name'];
            } else {
                $this->data['customer_group'] = '';
            }

            $this->data['email'] = $order_info['email'];
            $this->data['pdate'] = $order_info['pdate'];
            $this->data['p_order_id'] = $order_info['p_order_id'];

            $this->data['ip'] = $order_info['ip'];
            $this->data['telephone'] = $order_info['telephone'];
            $this->data['fax'] = $order_info['fax'];
            $this->data['comment'] = nl2br($order_info['comment']);
            $this->data['shipping_method'] = $order_info['shipping_method'];
            $this->data['payment_method'] = $order_info['payment_method'];
            $this->data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
            $this->data['reward'] = $order_info['reward'];


            if ($order_info['certification']) {
                $this->data['certification'] = HTTP_CATALOG . 'download/order-certification/' . $this->encryption->decrypt($order_info['certification']);
            } else {
                $this->data['certification'] = NULL;
            }

            if ($order_info['total'] < 0) {
                $this->data['credit'] = $order_info['total'];
            } else {
                $this->data['credit'] = 0;
            }

            $this->load->model('sale/customer');

            $this->data['credit_total'] = $this->model_sale_customer->getTotalCustomerTransactionsByOrderId($order_id);

            $this->data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($order_id);

            $this->data['affiliate_firstname'] = $order_info['affiliate_firstname'];
            $this->data['affiliate_lastname'] = $order_info['affiliate_lastname'];

            if ($order_info['affiliate_id']) {
                $this->data['affiliate'] = $this->url->link('sale/affliate/update', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], 'SSL');
            } else {
                $this->data['affiliate'] = '';
            }

            $this->data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

            $this->load->model('sale/affiliate');

            $this->data['commission_total'] = $this->model_sale_affiliate->getTotalTransactionsByOrderId($order_id);

            $this->load->model('localisation/order_status');

            $order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

            if ($order_status_info) {
                $this->data['order_status'] = $order_status_info['name'];
            } else {
                $this->data['order_status'] = '';
            }

            $this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
            $this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));

            $this->data['payment_firstname'] = $order_info['payment_firstname'];
            $this->data['payment_lastname'] = $order_info['payment_lastname'];
            $this->data['payment_company'] = $order_info['payment_company'];
            $this->data['payment_address_1'] = $order_info['payment_address_1'];
            $this->data['payment_address_2'] = $order_info['payment_address_2'];
            $this->data['payment_city'] = $order_info['payment_city'];
            $this->data['payment_postcode'] = $order_info['payment_postcode'];
            $this->data['payment_zone'] = $order_info['payment_zone'];
            $this->data['payment_zone_code'] = $order_info['payment_zone_code'];
            $this->data['payment_country'] = $order_info['payment_country'];
            $this->data['shipping_firstname'] = $order_info['shipping_firstname'];
            $this->data['shipping_lastname'] = $order_info['shipping_lastname'];
            $this->data['shipping_mobile'] = $order_info['shipping_mobile'];
            $this->data['shipping_phone'] = $order_info['shipping_phone'];
            $this->data['shipping_lastname'] = $order_info['shipping_lastname'];
            $this->data['shipping_company'] = $order_info['shipping_company'];
            $this->data['shipping_address_1'] = $order_info['shipping_address_1'];
            $this->data['shipping_address_2'] = $order_info['shipping_address_2'];
            $this->data['shipping_city'] = $order_info['shipping_city'];
            $this->data['shipping_postcode'] = $order_info['shipping_postcode'];
            $this->data['shipping_zone'] = $order_info['shipping_zone'];
            $this->data['shipping_zone_code'] = $order_info['shipping_zone_code'];
            $this->data['shipping_country'] = $order_info['shipping_country'];

            $this->data['products'] = array();

            $products = $this->model_sale_order->getOrderProducts($order_id);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => $option['value'],
                            'type' => $option['type']
                        );
                    } else {
                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => substr($option['value'], 0, strrpos($option['value'], '.')),
                            'type' => $option['type'],
                            'href' => $this->url->link('sale/order_refund_check/download', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . '&order_option_id=' . $option['order_option_id'], 'SSL')
                        );
                    }
                }

                $this->data['products'][] = array(
                    'order_product_id' => $product['order_product_id'],
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
                    'href' => HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $product['product_id']
                );
            }

            if ($order_info['p_order_id'])
                $this->data['totals'] = $this->model_sale_order->getOrderTotals($order_info['p_order_id']);
            else
                $this->data['totals'] = $this->model_sale_order->getOrderTotals($order_id);

            if ($order_info['p_order_id']) {
                $this->data['sub_orders'] = $this->model_sale_order->getSubOrders($order_info['p_order_id']);
            } else {
                $this->data['sub_orders'] = array();
            }


            $this->load->model('localisation/logistics');

            $this->data['expresses'] = $this->model_localisation_logistics->getLogisticses();

            $this->data['downloads'] = array();

            $results = $this->model_sale_order->getOrderDownloads($order_id);

            foreach ($results as $result) {
                $this->data['downloads'][] = array(
                    'name' => $result['name'],
                    'filename' => $result['mask'],
                    'remaining' => $result['remaining']
                );
            }

            $this->data['order_statuses'] = $this->loadStatuses();

            $this->data['order_status_id'] = $order_info['order_status_id'];

            if ($order_info['invoice_type']) {
                $this->data['invoice_detail_status'] = true;
            } else {
                $this->data['invoice_detail_status'] = false;
            }

            $this->data['invoice_type'] = getInvoiceTypeDetail($order_info['invoice_type']);
            $this->data['invoice_head'] = getInvoiceHeadDetail($order_info['invoice_head']);
            $this->data['invoice_name'] = $order_info['invoice_name'];
            $this->data['invoice_content'] = getInvoiceContentDetail($order_info['invoice_content']);


            if ($order_info['order_status_id'] == $this->config->get('config_order_nopay_status_id')) {
                $this->data['discount_status'] = $this->checkOrderDiscountStatus($order_id);
            } else {
                $this->data['discount_status'] = TRUE;
            }

            $this->data['pickup_code'] = $order_info['pickup_code'];

           // print_r($order_info);die();


            $this->template = 'sale/order_refund_check_info.tpl';
            $this->id = 'content';
            $this->layout = 'layout/default';
            $this->render();
        } else {
            $this->renderNotFoundDetail();
        }
    }


    private function loadStatuses() {
        $dbData = $this->model_localisation_order_status->getOrderStatuses();
        $results = array();
        foreach ($dbData as $item) {
            $order_status_id = $item['order_status_id'];
            if ($order_status_id == 13 || $order_status_id == 11 || $order_status_id == 8) {
                $results[] = $item;
            }
        }
        return $results;

    }

    
    private function renderNotFoundDetail() {
        $this->load_language('error/not_found');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_not_found'] = $this->language->get('text_not_found');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->template = 'error/not_found.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    /**
     * @return string
     */
    private function buildPrevUrlPart() {
        $FILTER_ORDER_ID = 'filter_order_id';
        $FILTER_PARTNER_CODE = 'filter_partner_code';
        $FILTER_CUSTOMER = 'filter_customer';
        $FILTER_CUSTOMER_PHONE = 'filter_customer_phone';
        $FILTER_ORDER_STATUS_ID = 'filter_order_status_id';
        $FILTER_TOTAL = 'filter_total';
        $FILTER_DATE_ADDED = 'filter_date_added';
        $FILTER_DATE_MODIFIED = 'filter_date_modified';
        $SORT = 'sort';
        $ORDER = 'order';
        $PAGE = 'page';
        $TOKEN = 'token';

        $paramsMeta = array(
            array('get', $FILTER_ORDER_ID, null),
            array('get', $FILTER_PARTNER_CODE, null),
            array('get', $FILTER_CUSTOMER, null),
            array('get', $FILTER_CUSTOMER_PHONE, null),
            array('get', $FILTER_ORDER_STATUS_ID, null),
            array('get', $FILTER_TOTAL, null),
            array('get', $FILTER_DATE_ADDED, null),
            array('get', $FILTER_DATE_MODIFIED, null),
            array('get', $SORT, 'o.order_id'),
            array('get', $ORDER, 'DESC'),
            array('get', $PAGE, 1),
        );

        $queryParams = ReqHelper::parseQueryParams($this->request, $paramsMeta);
        $queryParams[$TOKEN] = $this->session->data['token'];
        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $TOKEN,
            $FILTER_ORDER_ID,
            $FILTER_CUSTOMER_PHONE,
            $FILTER_CUSTOMER,
            $FILTER_PARTNER_CODE,
            $FILTER_ORDER_STATUS_ID,
            $FILTER_TOTAL,
            $FILTER_DATE_ADDED,
            $FILTER_DATE_MODIFIED,
            $SORT,
            $ORDER,
            $PAGE
        ));
        return $queryUrlPart;
    }

}

?>