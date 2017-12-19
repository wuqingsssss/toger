<?php

class ControllerSaleOrderPurchase extends Controller {
    private $error = array();

    public function index() {
        $this->load_language('sale/order_purchase');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/order');

        $this->getList();
    }


    public function confirm() {
        $this->load->model('sale/order_purchase');
        $user_id = $this->user->getId();

        $req = $this->request;
        $products = $req->post['data'];
        $orderIds = $req->post['orderIds'];
        $operateDate = $req->post['operate_date'];

        $purchase_id = $this->model_sale_order_purchase->createOrderPurchase($user_id, $operateDate, $products, $orderIds);

        $json = array();
        $json['success'] = true;
        $json['purchase_id'] = $purchase_id;
        $this->response->setOutput(json_encode($json));
    }

    public function update_status() {
        $this->load->model('sale/order_purchase');

        $req = $this->request;
        $orderId = $req->get['order_id'];
        $status = $req->post['status'];


        $this->model_sale_order_purchase->updatePurchaseOrderStatus($orderId, $status);

        $json = array();
        $json['success'] = true;
        $this->response->setOutput(json_encode($json));
    }

    private function getList() {
        $this->load->model('sale/order_purchase');
        $req = $this->request;

        $PAGE_URL = 'sale/order_purchase';


        $FILTER_ORDER_SERIAL_NO = 'filter_order_serial_no';
        $FILTER_ORDER_STATUS = 'filter_order_status';
        $SORT = 'sort';
        $ORDER = 'order';
        $PAGE = 'page';
        $TOKEN = 'token';

        $paramsMeta = array(
            array('get', $FILTER_ORDER_SERIAL_NO, null),
            array('get', $FILTER_ORDER_STATUS, null),
            array('get', $SORT, null),
            array('get', $ORDER, null),
            array('get', $PAGE, 1),
        );

        $queryParams = ReqHelper::parseQueryParams($req, $paramsMeta);
        $queryParams[$TOKEN] = $this->session->data[$TOKEN];


        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', ReqHelper::joinQueryParams($queryParams, array($TOKEN)), 'SSL'),
            'separator' => false
        );

        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $TOKEN
        ));

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($PAGE_URL, $queryUrlPart, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->data['link_detail_page_prefix'] = $this->url->link('sale/order_purchase/info', ReqHelper::joinQueryParams($queryParams, array($TOKEN)) . '&order_id=', 'SSL');
        $this->data['link_gen_purchase_order'] = $this->url->link('sale/order_purchase/pre_generate', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['link_confirm_gen_purchase_order'] = $this->url->link('sale/order_purchase/pre_confirm', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['link_source_order_confirm'] = $this->url->link('sale/order_purchase/source_order_confirm', 'token=' . $this->session->data['token'], 'SSL');

        $data = array(
            $FILTER_ORDER_SERIAL_NO => $queryParams[$FILTER_ORDER_SERIAL_NO],
            $FILTER_ORDER_STATUS => $queryParams[$FILTER_ORDER_STATUS],
            $SORT => $queryParams[$SORT],
            $ORDER => $queryParams[$ORDER],
            'start' => ($queryParams[$PAGE] - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $result = $this->model_sale_order_purchase->queryPurchaseOrders($queryParams[$FILTER_ORDER_STATUS], $queryParams[$FILTER_ORDER_SERIAL_NO], $data);

        $this->data['orders'] = $result['rows'];
        $order_total = $result['total'];

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
            $FILTER_ORDER_SERIAL_NO,
            $FILTER_ORDER_STATUS,
            $ORDER,
            $PAGE
        ));


        $this->data['link_sort_serial_no'] = $this->url->link($PAGE_URL, '&sort=serial_no&' . $queryUrlPart, 'SSL');
        $this->data['link_sort_created_at'] = $this->url->link($PAGE_URL, '&sort=created_at&' . $queryUrlPart, 'SSL');
        $this->data['link_sort_operate_date'] = $this->url->link($PAGE_URL, '&sort=operate_date&' . $queryUrlPart, 'SSL');

        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $TOKEN,
            $FILTER_ORDER_SERIAL_NO,
            $FILTER_ORDER_STATUS,
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

        $this->data[$FILTER_ORDER_SERIAL_NO] = $queryParams[$FILTER_ORDER_SERIAL_NO];
        $this->data[$FILTER_ORDER_STATUS] = $queryParams[$FILTER_ORDER_STATUS];

        $this->load->model('localisation/order_status');
        $this->data['order_statuses'] = $this->loadStatuses();

        $this->data[$SORT] = $queryParams[$SORT];
        $this->data[$ORDER] = $queryParams[$ORDER];

        $this->template = 'sale/order_purchase_list.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    public function pre_confirm() {
        $this->load->model('sale/order_purchase');

        $count = $this->model_sale_order_purchase->countPayedOrders();

        $json = array();
        $json['need_to_generate'] = $count > 0;
        $this->load->library('json');
        $this->response->setOutput(Json::encode($json));
    }


    public function pre_generate() {
        $this->load->model('sale/order');
        $this->load->model('sale/order_purchase');

        $this->load_language('sale/order_purchase');
        $this->document->setTitle($this->language->get('heading_title'));

        $token = $this->data['token'] = $this->session->data['token'];

        //switch flags
        $this->data['pre_generate'] = true;;

        //breadcrumbs
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $token, 'SSL'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/order_purchase', 'token=' . $token, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        //links
        $this->data['link_confirm'] = $this->url->link('sale/order_purchase/confirm', 'token=' . $token, 'SSL');
        $this->data['link_info_prefix'] = $this->url->link('sale/order_purchase/info', 'token=' . $token . '&order_id=', 'SSL');
        $this->data['link_site_product_detail_prefix'] = HTTP_CATALOG . 'index.php?route=product/product&product_id=';


        //main
        $this->data['order'] = array();
        //        $this->data['order_purchase'] = $this->model_sale_order_purchase->getOrderRefund($order_id);

        //products
        $this->data['products'] = $this->model_sale_order_purchase->getToBePurchasedOrderProducts();
        $this->data['orderIds'] = $this->model_sale_order_purchase->getToBePurchasedOrderIds();

        $this->template = 'sale/order_purchase_info.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    public function source_order_confirm() {
        $page_title = '待发注订单审核';
        $this->load_language('sale/order_purchase');
        $this->document->setTitle($page_title);
        $this->data['heading_title'] = $page_title;

        $this->load->model('sale/order_purchase');
        $this->load->model('sale/order');

        $req = $this->request;

        $PAGE_URL = 'sale/order_purchase/source_order_confirm';
        $DETAIL_PAGE_URL = 'sale/order/info';

        $FILTER_PDATE = 'filter_pdate';

        $endDate = new DateTime();
        $endDate->modify('+1 day');
        $endDateStr = date_format($endDate, 'Y-m-d');

        $paramsMeta = array(
            array('get', $FILTER_PDATE, $endDateStr)
        );

        $queryParams = ReqHelper::parseQueryParams($req, $paramsMeta);
//        if($queryParams[$FILTER_ORDER_STATUS_ID]=='*'){
//            $queryParams[$FILTER_ORDER_STATUS_ID]=null;
//        }


        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', 'SSL'),
            'separator' => false
        );

        $queryUrlPart = ReqHelper::joinQueryParams($queryParams, array(
            $FILTER_PDATE
        ));

//        $page_title = $this->language->get('heading_title');
        $this->data['breadcrumbs'][] = array(
            'text' => $page_title,
            'href' => $this->url->link($PAGE_URL, $queryUrlPart, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->data['orders'] = array();

        $data = array(
            $FILTER_PDATE => $queryParams[$FILTER_PDATE],
        );


        $results = $this->model_sale_order_purchase->getOrdersPickupTomorrow($data);
//        $order_total=count($results);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_view'),
                'href' => $this->url->link($DETAIL_PAGE_URL, 'order_id=' . $result['order_id'] . $queryUrlPart . "&ref=purchase", 'SSL')
            );

            $this->data['orders'][] = array(
                'order_id' => $result['order_id'],
                'p_order_id' => $result['p_order_id'],
                'pdate' => $result['pdate'],
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

//        $this->data[$TOKEN] = $queryParams[$TOKEN];

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
//        $queryParams2[$ORDER] = ($queryParams2[$ORDER] == 'ASC' ? 'DESC' : 'ASC');
        $queryUrlPart = ReqHelper::joinQueryParams($queryParams2, array(
//            $TOKEN,
            $FILTER_PDATE
        ));


        $this->data['sort_order'] = $this->url->link($PAGE_URL, '&sort=o.order_id&' . $queryUrlPart, 'SSL');
        $this->data['sort_customer'] = $this->url->link($PAGE_URL, '&sort=customer&' . $queryUrlPart, 'SSL');
        $this->data['sort_status'] = $this->url->link($PAGE_URL, '&sort=status&' . $queryUrlPart, 'SSL');
        $this->data['sort_total'] = $this->url->link($PAGE_URL, '&sort=o.total&' . $queryUrlPart, 'SSL');
        $this->data['sort_date_added'] = $this->url->link($PAGE_URL, '&sort=o.date_added&' . $queryUrlPart, 'SSL');
        $this->data['sort_date_modified'] = $this->url->link($PAGE_URL, '&sort=o.date_modified&' . $queryUrlPart, 'SSL');


        $this->data[$FILTER_PDATE] = $queryParams[$FILTER_PDATE];

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->loadStatuses();

//        $this->data[$SORT] = $queryParams[$SORT];
//        $this->data[$ORDER] = $queryParams[$ORDER];

        $this->template = 'sale/order_purchase_source_confirm_list.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }


    public function info() {
        $this->load->model('sale/order');
        $this->load->model('sale/order_purchase');

        $this->load_language('sale/order_purchase');

        $order_id = $this->request->get['order_id'];
        $order_info = $this->model_sale_order_purchase->getDetail($order_id);

        if (is_null($order_info)) {
            $this->renderNotFoundDetail();
            return;
        }


        $this->document->setTitle($this->language->get('heading_title'));

        $token = $this->data['token'] = $this->session->data['token'];

        //switch flags
//        $this->data['pre_generate'] = true;;

        //breadcrumbs
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $token, 'SSL'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/order_purchase', 'token=' . $token, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        //links
//        $this->data['link_confirm'] = $this->url->link('sale/order_purchase/confirm', 'token=' . $token, 'SSL');
//        $this->data['link_info_prefix'] = $this->url->link('sale/order_purchase/info', 'token=' . $token.'&order_id=', 'SSL');
        $this->data['link_site_product_detail_prefix'] = HTTP_CATALOG . 'index.php?route=product/product&product_id=';
        $this->data['link_confirm_done'] = $this->url->link('sale/order_purchase/update_status', 'token=' . $token . '&order_id=' . $order_id, 'SSL');

        //main
        $this->data['order'] = $order_info['order'];

        //products
        $this->data['products'] = $order_info['products'];
//        $this->data['orderIds'] = $this->model_sale_order_purchase->getToBePurchasedOrderIds();

        $this->template = 'sale/order_purchase_info.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    private function loadStatuses() {
        return array(
            array(
                'id' => 'PENDING',
                'name' => '进行'
            ),
            array(
                'id' => 'DONE',
                'name' => '完成'
            )
        );
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


}

?>