<?php

class ControllerSalePickStock extends Controller {
    private $error = array();

    protected function init() {
        $this->load_language('sale/pick_stock');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('sale/order');
        $this->load->model('sale/order_delivery');
        $this->load->model('catalog/point');
    }

    public function index() {
        $this->init();
        $this->points();
    }


    private function points() {
        $req = $this->request;

        $PAGE_URL = 'sale/pick_stock';

        $FILTER_POINT_NAME = 'filter_name';

        $PAGE = 'page';

        $paramsMeta = array(
            array('get', $FILTER_POINT_NAME, null),
            array('get', $PAGE, 1),
        );

        $queryParams = ReqHelper::parseQueryParams($req, $paramsMeta);

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', 'SSL'),
            'separator' => false
        );

        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $FILTER_POINT_NAME,
            $PAGE
        ));

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($PAGE_URL, $queryUrlPart, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->data['orders'] = array();

        $data = array(
            $FILTER_POINT_NAME => $queryParams[$FILTER_POINT_NAME],
            'start' => ($queryParams[$PAGE] - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );


        $total = $this->model_catalog_point->getTotalPoints($data);
        $results = $this->model_catalog_point->getPoints($data);
        $this->data['points'] = $results;


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


        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $FILTER_POINT_NAME
        ));

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $queryParams[$PAGE];
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link($PAGE_URL, $queryUrlPart . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data[$FILTER_POINT_NAME] = $queryParams[$FILTER_POINT_NAME];

        $this->template = 'sale/pick_stock_point_list.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    public function orders() {
        $this->init();
        $req = $this->request;

        $PAGE_URL = 'sale/pick_stock/orders';
        $DETAIL_PAGE_URL = 'sale/pick_stock/info';

        $FILTER_ORDER_ID = 'filter_order_id';
        $FILTER_POINT_ID = 'filter_point_id';
        $FILTER_CUSTOMER = 'filter_customer';
        $FILTER_CUSTOMER_PHONE = 'filter_customer_phone';
        $FILTER_TOTAL = 'filter_total';
        $FILTER_PDATE = 'filter_pdate';

        $SORT = 'sort';
        $ORDER = 'order';
        $PAGE = 'page';

        $endDate = new DateTime();
        $endDateStr = date_format($endDate, 'Y-m-d');

        $paramsMeta = array(
            array('get', $FILTER_ORDER_ID, null),
            array('get', $FILTER_POINT_ID, null),
            array('get', $FILTER_CUSTOMER, null),
            array('get', $FILTER_CUSTOMER_PHONE, null),
            array('get', $FILTER_TOTAL, null),
            array('get', $FILTER_PDATE, $endDateStr),
            array('get', $SORT, 'o.shipping_point_id'),
            array('get', $ORDER, 'DESC'),
            array('get', $PAGE, 1),
        );

        $queryParams = ReqHelper::parseQueryParams($req, $paramsMeta);


        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home','', 'SSL'),
            'separator' => false
        );

        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $FILTER_ORDER_ID,
            $FILTER_POINT_ID,
            $FILTER_CUSTOMER_PHONE,
            $FILTER_CUSTOMER,
            $FILTER_TOTAL,
            $FILTER_PDATE,
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
            $FILTER_ORDER_ID => $queryParams[$FILTER_ORDER_ID],
            $FILTER_POINT_ID => $queryParams[$FILTER_POINT_ID],
            $FILTER_CUSTOMER => $queryParams[$FILTER_CUSTOMER],
            $FILTER_CUSTOMER_PHONE => $queryParams[$FILTER_CUSTOMER_PHONE],
            $FILTER_TOTAL => $queryParams[$FILTER_TOTAL],
            $FILTER_PDATE => $queryParams[$FILTER_PDATE],
            $SORT => $queryParams[$SORT],
            $ORDER => $queryParams[$ORDER],
            'start' => ($queryParams[$PAGE] - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $dbResult = $this->model_sale_order_delivery->getToDeliveryOrders($data);
        $results = $dbResult['rows'];
        $order_total = $dbResult['total'];


        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_view'),
                'href' => $this->url->link($DETAIL_PAGE_URL, 'order_id=' . $result['order_id'] . $queryUrlPart, 'SSL')
            );

            if ($result['shipping_point_id']) {
                $this->load->model('catalog/point');

                $point_info = $this->model_catalog_point->getPoint($result['shipping_point_id']);

                if ($point_info) {
                    $shipping_point = $point_info['name'] . "[" . $point_info['address'] . "]";
                } else {
                    $shipping_point = '';
                }
            } else {
                $shipping_point = $result['shipping_method'];
            }

            $this->data['orders'][] = array(
                'order_id' => $result['order_id'],
                'p_order_id' => $result['p_order_id'],
                'pdate' => $result['pdate'],
                'shipping_point' => $shipping_point,

                'customer' => $result['email'],
                'telephone' => $result['telephone'],//TODO
                'status' => $result['status'],
                'partner' => $result['partner_code'] ? $result['partner_code'] : "内站",
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'date_added' => $result['date_added'],
                'date_modified' => $result['date_modified'],
                'selected' => isset($req->post['selected']) && in_array($result['order_id'], $req->post['selected']),
                'action' => $action
            );
        }


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
            $FILTER_ORDER_ID,
            $FILTER_POINT_ID,
            $FILTER_CUSTOMER,
            $FILTER_TOTAL,
            $FILTER_PDATE,
            $ORDER,
            $PAGE
        ));


        $this->data['sort_order'] = $this->url->link($PAGE_URL, '&sort=o.order_id&' . $queryUrlPart, 'SSL');
        $this->data['sort_customer'] = $this->url->link($PAGE_URL, '&sort=customer&' . $queryUrlPart, 'SSL');
        $this->data['sort_status'] = $this->url->link($PAGE_URL, '&sort=status&' . $queryUrlPart, 'SSL');
        $this->data['sort_total'] = $this->url->link($PAGE_URL, '&sort=o.total&' . $queryUrlPart, 'SSL');
        $this->data['sort_date_added'] = $this->url->link($PAGE_URL, '&sort=o.date_added&' . $queryUrlPart, 'SSL');
        $this->data['sort_date_modified'] = $this->url->link($PAGE_URL, '&sort=o.date_modified&' . $queryUrlPart, 'SSL');


        if ($queryParams[$FILTER_POINT_ID]) {
            $this->data['distribution'] = $this->url->link('sale/pick_stock/distribution', 'point_id=' . $queryParams[$FILTER_POINT_ID], 'SSL');
        } else {
            $this->data['distribution'] = '';
        }


        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $FILTER_ORDER_ID,
            $FILTER_POINT_ID,
            $FILTER_CUSTOMER,
            $FILTER_TOTAL,
            $FILTER_PDATE,
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
        $this->data['total'] = $order_total;

        $this->data[$FILTER_ORDER_ID] = $queryParams[$FILTER_ORDER_ID];
        $this->data[$FILTER_POINT_ID] = $queryParams[$FILTER_POINT_ID];
        $this->data[$FILTER_CUSTOMER] = $queryParams[$FILTER_CUSTOMER];
        $this->data[$FILTER_CUSTOMER_PHONE] = $queryParams[$FILTER_CUSTOMER_PHONE];
        $this->data[$FILTER_TOTAL] = $queryParams[$FILTER_TOTAL];
        $this->data[$FILTER_PDATE] = $queryParams[$FILTER_PDATE];

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->loadStatuses();

        $this->data[$SORT] = $queryParams[$SORT];
        $this->data[$ORDER] = $queryParams[$ORDER];

        $this->template = 'sale/pick_stock_order_list.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }


    public function autocomplete() {

        $json = array();

        if (isset($this->request->post['filter_name'])) {
            $this->request->get['filter_name'] = trim($this->request->post['filter_name']);
        }


        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/point');

            $filter = array(
                'filter_name' => $this->request->get['filter_name']
            );

            $results = $this->model_catalog_point->getFilterPoints($filter);

            foreach ($results as $result) {
                $json[] = array(
                    'point_id' => $result['point_id'],
                    'name' => $result['name']
                );
            }
        }

        $this->response->setOutput(json_encode($json));
    }


    public function info() {
        $this->init();

        $this->load->model('sale/order_refund');


        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_sale_order->getOrder($order_id);

        if ($order_info) {

            $this->document->setTitle($this->language->get('heading_title'));


            $queryUrlPart = $this->buildPrevUrlPart();

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', '', 'SSL'),
                'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('sale/pick_stock', '', 'SSL'),
                'separator' => $this->language->get('text_breadcrumb_separator')
            );

            $this->data['invoice'] = $this->url->link('sale/pick_stock/invoice', '' . '&order_id=' . $this->request->get['order_id'], 'SSL');
            $this->data['cancel'] = $this->url->link('sale/pick_stock', $queryUrlPart, 'SSL');


            $this->data['order_id'] = $this->request->get['order_id'];
            $this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
            $this->data['store_name'] = $order_info['store_name'];
            $this->data['store_url'] = $order_info['store_url'];

            if ($order_info['firstname'] == '')
                $this->data['firstname'] = $order_info['email'];
            else
                $this->data['firstname'] = $order_info['firstname'];

            $this->data['lastname'] = $order_info['lastname'];

            if ($order_info['customer_id']) {
                $this->data['customer'] = $this->url->link('sale/customer/update',  'customer_id=' . $order_info['customer_id'], 'SSL');
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

            $this->data['credit_total'] = $this->model_sale_customer->getTotalCustomerTransactionsByOrderId($this->request->get['order_id']);

            $this->data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

            $this->data['affiliate_firstname'] = $order_info['affiliate_firstname'];
            $this->data['affiliate_lastname'] = $order_info['affiliate_lastname'];

            if ($order_info['affiliate_id']) {
                $this->data['affiliate'] = $this->url->link('sale/affliate/update',  'affiliate_id=' . $order_info['affiliate_id'], 'SSL');
            } else {
                $this->data['affiliate'] = '';
            }

            $this->data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

            $this->load->model('sale/affiliate');

            $this->data['commission_total'] = $this->model_sale_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);

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

            $this->data['products'] = $this->getOrderProducts($order_info);

            if ($order_info['p_order_id'])
                $this->data['totals'] = $this->model_sale_order->getOrderTotals($order_info['p_order_id']);
            else
                $this->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

            if ($order_info['p_order_id']) {
                $this->data['sub_orders'] = $this->model_sale_order->getSubOrders($order_info['p_order_id']);
            } else {
                $this->data['sub_orders'] = array();
            }


            $this->load->model('localisation/logistics');

            $this->data['expresses'] = $this->model_localisation_logistics->getLogisticses();

            $this->data['downloads'] = array();

            $results = $this->model_sale_order->getOrderDownloads($this->request->get['order_id']);

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
                $this->data['discount_status'] = $this->checkOrderDiscountStatus($this->request->get['order_id']);
            } else {
                $this->data['discount_status'] = TRUE;
            }

            $this->data['pickup_code'] = $order_info['pickup_code'];

            $this->data['order_refund'] = $this->model_sale_order_refund->getOrderRefund($order_id);


            $this->template = 'sale/pick_stock_info.tpl';
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
            if ($order_status_id == 13 || $order_status_id == 11) {
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
            'href' => $this->url->link('common/home', '', 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('error/not_found', '', 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->template = 'error/not_found.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    private function getPointDistributionProducts($point_id) {
        $this->load->model('sale/order_distribution');

        $results = $this->model_sale_order_distribution->getPointDistributionProducts($point_id, 18);

        foreach ($results as $result) {
            $products[] = array(
                'name' => $result['name'],
                'quantity' => $result['quantity'],
                'unit' => '个',
                'comment' => '无'
            );
        }

        return $products;
    }

    public function print_dishes() {
        $this->load->model('tool/excel');
        $this->load->model('catalog/point');
        $this->load->model('sale/order_delivery');

        $point_id = (int)$this->request->get['point_id'];


        $point_info = $this->model_catalog_point->getPoint($point_id);
        $products = $this->model_sale_order_delivery->getAllToDeliveryDishes($point_id);
        if(empty($point_info) || empty($products)){
            return;
        }

//获取第三方平台列表
		$this->load->model('catalog/partnercode');
		$partners = $this->model_catalog_partnercode->getAllPartners();
		
        foreach($products as $index=>$item){
//            $products[$index]['comment']=EnumPartners::getPartnerInfo($item['partner_code']);
			$products[$index]['comment']=$partners[$item['partner_code']];
        }

        $this->model_tool_excel->exportDistributionProducts1(DIR_DOWNLOAD . 'distribution_sample1.xls', 0, $products, $point_info);
    }

//    public function print_dishes() {
//        $this->init();
//
//        $this->document->setTitle($this->language->get('heading_title'));
//
//        //breadcrumbs
//        $this->data['breadcrumbs'] = array();
//        $this->data['breadcrumbs'][] = array(
//            'text' => $this->language->get('text_home'),
//            'href' => $this->url->link('common/home', '', 'SSL'),
//            'separator' => false
//        );
//        $this->data['breadcrumbs'][] = array(
//            'text' => $this->language->get('heading_title'),
//            'href' => $this->url->link('sale/pick_stock', '', 'SSL'),
//            'separator' => $this->language->get('text_breadcrumb_separator')
//        );
//
//        $point_id=$this->request->get['point_id'];
//        //products
//        $this->data['rows'] =$this->model_sale_order_delivery->getAllToDeliveryDishes($point_id);
//
//        $this->template = 'sale/pick_stock_orders_print1.tpl';
//        $this->id = 'content';
//        $this->layout = 'layout/default';
//        $this->render();
//    }

    public function print_orders() {
        $this->load->model('tool/excel');
        $this->load->model('catalog/point');
        $this->load->model('sale/order_delivery');

        $point_id = (int)$this->request->get['point_id'];


        $point_info = $this->model_catalog_point->getPoint($point_id);
        $products = $this->model_sale_order_delivery->getAllToDeliveryOrders($point_id);
        if(empty($point_info) || empty($products)){
            return;
        }


//        foreach($products as $index=>$item){
//            $products[$index]['comment']=EnumPartners::getPartnerInfo($item['partner_code']);
//        }

        $this->model_tool_excel->exportDistributionProducts2(DIR_DOWNLOAD . 'distribution_sample2.xls', 0, $products, $point_info);
    }

//    public function print_orders() {
//        $this->init();
//
//        $this->document->setTitle($this->language->get('heading_title'));
//
//        //breadcrumbs
//        $this->data['breadcrumbs'] = array();
//        $this->data['breadcrumbs'][] = array(
//            'text' => $this->language->get('text_home'),
//            'href' => $this->url->link('common/home', '', 'SSL'),
//            'separator' => false
//        );
//        $this->data['breadcrumbs'][] = array(
//            'text' => $this->language->get('heading_title'),
//            'href' => $this->url->link('sale/pick_stock', '', 'SSL'),
//            'separator' => $this->language->get('text_breadcrumb_separator')
//        );
//
//        $point_id=$this->request->get['point_id'];
//        //products
//        $this->data['rows'] =$this->model_sale_order_delivery->getAllToDeliveryOrders($point_id);
//
//        $this->template = 'sale/pick_stock_orders_print2.tpl';
//        $this->id = 'content';
//        $this->layout = 'layout/default';
//        $this->render();
//    }

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
        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
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

    /**
     * @param $order_info
     */
    public function getOrderProducts($order_info) {
        $data = array();

        $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

        foreach ($products as $product) {
            $option_data = array();

            $options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

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
                        'href' => $this->url->link('sale/order_refund/download', '&order_id=' . $this->request->get['order_id'] . '&order_option_id=' . $option['order_option_id'], 'SSL')
                    );
                }
            }

            $data[] = array(
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

        return $data;
    }

}

?>