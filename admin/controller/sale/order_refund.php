<?php

class ControllerSaleOrderRefund extends Controller {
    private $error = array();

    public function index() {
    	
    	if(!$this->user->hasPermission('modify','sale/order_refund')){
			return $this->forward('error/permission');
		}
        $this->load_language('sale/order_refund');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/order');
        $this->load->model('sale/order_refund');

        $this->getList();
    }

    private function getList() {
 
        $req = $this->request;
        $PAGE_URL = 'sale/order_refund';
        $DETAIL_PAGE_URL = 'sale/order_refund_check/info';

        $FILTER_PAYMENT_CODE = 'filter_payment_code';
        $FILTER_ORDER_ID     = 'filter_order_id';
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
        		'listurl'=>$this->url->link($PAGE_URL, 'token=' . $this->session->data[$TOKEN] . '&filter_payment_code=alipay' , 'SSL'),
        		'payurl'=>$this->url->link('payment/alipay/refund', 'token=' . $this->session->data[$TOKEN]  , 'SSL'),
        		'code'=>'alipay'
        );
        $payments['wxpay']=array(
        		'title'=>'微信支付',
        		'listurl'=>$this->url->link($PAGE_URL, 'token=' . $this->session->data[$TOKEN] . '&filter_payment_code=wxpay' , 'SSL'),
        		'payurl'=>$this->url->link('payment/wxpay/refund', 'token=' . $this->session->data[$TOKEN]  , 'SSL'),
        		'code'=>'wxpay'
        );
        $payments['balance']=array(
        		'title'=>'储值支付',
        		'listurl'=>$this->url->link($PAGE_URL, 'token=' . $this->session->data[$TOKEN] . '&filter_payment_code=balance' , 'SSL'),
        		'payurl'=>$this->url->link('payment/balance/refund', 'token=' . $this->session->data[$TOKEN]  , 'SSL'),
        		'code'=>'balance'
        );
        /*
        $payments['banck']=array(
        		'title'=>'银行转账',
        		'listurl'=>$this->url->link($PAGE_URL, 'token=' . $this->session->data[$TOKEN] . '&filter_payment_code=banck', 'SSL'),
        		//'payurl'=>$this->url->link('payment/banck/refund', 'token=' . $this->session->data[$TOKEN]  , 'SSL'),
        		'code'=>'banck'
        );
        */
        $this->data['payments']=$payments;
        
        
        
        $paramsMeta = array(
        	array('get', $FILTER_PAYMENT_CODE, 'alipay'),
            array('get', $FILTER_ORDER_ID, null),
            array('get', $FILTER_PARTNER_CODE, null),
            array('get', $FILTER_CUSTOMER, null),
            array('get', $FILTER_CUSTOMER_PHONE, null),
        	array('get', $FILTER_ORDER_REFUND_STATUS, 'PHASE2_PASSED'),
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
            	'value1' => $this->currency->format($result['value1'], $result['currency_code'], $result['currency_value']),
            	'reason' => $result['reason'],
            	'phase1_refused_reason' => $result['phase1_refused_reason'],
            	'phase2_refused_reason' => $result['phase2_refused_reason'],
            	'date_added' => $result['date_added'],
                'date_modified' => $result['date_modified'],
                'selected' => isset($req->post['selected']) && in_array($result['order_id'], $req->post['selected']),
                'action' => $action
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
            $FILTER_ORDER_ID,
            $FILTER_CUSTOMER,
        	$FILTER_PAYMENT_CODE,
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
        $pagination->total =  $order_total;
        $pagination->page  =  $queryParams[$PAGE];
        $pagination->limit =  $this->config->get('config_admin_limit');
        $pagination->text  =  $this->language->get('text_pagination');
        $pagination->url   =  $this->url->link($PAGE_URL, $queryUrlPart . '&page={page}', 'SSL');

        
        
        $this->data['pagination'] = $pagination->render();
        $this->data[$FILTER_PAYMENT_CODE] = $queryParams[$FILTER_PAYMENT_CODE];
        $this->data[$FILTER_ORDER_ID] = $queryParams[$FILTER_ORDER_ID];
        $this->data[$FILTER_CUSTOMER] = $queryParams[$FILTER_CUSTOMER];
        $this->data[$FILTER_CUSTOMER_PHONE] = $queryParams[$FILTER_CUSTOMER_PHONE];

        $this->data[$FILTER_ORDER_REFUND_STATUS] = $queryParams[$FILTER_ORDER_REFUND_STATUS];
        $this->data[$FILTER_ORDER_STATUS_ID] = $queryParams[$FILTER_ORDER_STATUS_ID];
        $this->data[$FILTER_TOTAL] = $queryParams[$FILTER_TOTAL];
        $this->data[$FILTER_DATE_ADDED] = $queryParams[$FILTER_DATE_ADDED];
        $this->data[$FILTER_DATE_MODIFIED] = $queryParams[$FILTER_DATE_MODIFIED];
        $this->data[$FILTER_PARTNER_CODE] = $queryParams[$FILTER_PARTNER_CODE];

        $this->load->model('localisation/order_status');

        
        //$this->data['order_refund_statuses'] = EnumOrderRefundStatus::getOrderRefundAllStatus();
        
        $this->data[$SORT] = $queryParams[$SORT];
        $this->data[$ORDER] = $queryParams[$ORDER];
//获取第三方平台列表
		$this->load->model('catalog/partnercode');
		$this->data['partners'] = $this->model_catalog_partnercode->getAllPartners();
		
        $this->template = 'sale/order_refund_list.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }


}

?>