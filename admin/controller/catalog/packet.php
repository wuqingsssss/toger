<?php

class ControllerCatalogPacket extends Controller {
    private $error = array();

    protected function init() {
        $this->load_language('catalog/packet');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/packet');
    }

    protected function redirectToList() {
        $this->session->data['success'] = $this->language->get('text_success');

        $url = $this->getUrlCommonParameters();

        $this->redirect($this->url->link('catalog/packet', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function index() {
        $this->log_admin->trace("");
        
        $this->init();

        $this->getList();
    }

    public function insert() {
        $this->log_admin->trace("");
        
        $this->init();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $postData = array_merge(array(),$this->request->post);
            $this->model_catalog_packet->create($postData);

            $this->redirectToList();
        }

        $this->getForm();
    }

    public function update() {
        $this->log_admin->trace("");
        
        $this->init();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_packet->update($this->request->get['id'], $this->request->post);

            $this->redirectToList();
        }

        $this->getForm();
    }
    
 
    public function delete() {
        $this->log_admin->trace("");
        
        $this->init();

        $ids = str_replace('&quot;', '"', $this->request->post['ids']);
        $ids=json_decode($ids);
        foreach($ids as $id){
            $this->model_catalog_supply_period->delete($id);
        }

        $json = array();
        $json['success'] = true;
        $this->load->library('json');
        $this->response->setOutput(Json::encode($json));
    }


    private function getUrlCommonParameters() {
        $url = '';

        if (isset($this->request->get['filter_id'])) {
            $url .= "&filter_id=" . $this->request->get['filter_id'];
        }

        return $url;
    }

    private function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/packet', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->data['insert'] = $this->url->link('catalog/packet/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['delete'] = $this->url->link('catalog/packet/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['points'] = array();

        $data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $point_total = $this->model_catalog_packet->getTotalPoints($data);

        $results = $this->model_catalog_packet->getall($data);

        foreach ($results as $result) {
            $action = array();


            $this->data['points'][] = array(
                'packet_id' => $result['packet_id'],
                'name'               => $result['name'],
                'date_start'               => $result['date_start'],
                'date_end'               => $result['date_end'],
                'type'               => $result['type'],
                'cond'               => $result['cond'],
                'batch'               => $result['batch'],
                'selected'           => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
                'action'             => $action
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

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['sort_name'] = $this->url->link('catalog/point', 'token=' . $this->session->data['token'] . '&sort=agd.name' . $url, 'SSL');
        $this->data['sort_sort_order'] = $this->url->link('catalog/point', 'token=' . $this->session->data['token'] . '&sort=ag.sort_order' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $point_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('catalog/packet', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'catalog/packet_list.tpl';

        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
    }

    private function getForm() {
    	
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = array();
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/packet', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $url = $this->getUrlCommonParameters();

        if (!isset($this->request->get['id'])) {
          $this->data['action'] = $this->url->link('catalog/packet/insert',  'token=' . $this->session->data['token'], 'SSL');

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_add'),
                'href' => $this->data['action'],
                'separator' => $this->language->get('text_breadcrumb_separator')
            );
        } else {
            $this->data['action'] = $this->url->link('catalog/packet/update', 'id=' . $this->request->get['id'] . $url, 'SSL');
            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->data['action'],
                'separator' => $this->language->get('text_breadcrumb_separator')
            );
        }

        $id = $this->request->get['id'];
        if (isset($id) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $this->data['result'] = $this->model_catalog_packet->get($id);
            $products = $this->model_catalog_packet->getProducts($id);
			$this->data['products'] = array();
			
			foreach ($products as $product) {
				$this->data['products'][] = array(
                    'coupon_id'    => $product['coupon_id'],
                    'name'         => $product['name'],
                    'code'         => $product['code'],
                    'date_start'   => $product['date_start'],
                    'date_end'     => $product['date_end'],
                    'discount'     => $product['discount'],
				    'type'         => $product['type']
				);
			}
			

        }else{
            $this->data['result']=array();
            $this->data['products']=array();
        }

        $this->load->model('localisation/language');

        $this->data['languages'] = $this->model_localisation_language->getLanguages();


//
//        if (isset($this->request->post['category_layout'])) {
//            $this->data['category_layout'] = $this->request->post['category_layout'];
//        } elseif (isset($category_info)) {
//            $this->data['category_layout'] = $this->model_supply_period->getCategoryLayouts($this->request->get['id']);
//        } else {
//            $this->data['category_layout'] = array();
//        }

        $this->template = 'catalog/packet_form.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    private function validateForm() {
        $rules = $this->load->rule();
        $this->load_language('error_msg');

        if (!$this->user->hasPermission('modify', 'catalog/supply_period')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['category_description'] as $language_id => $value) {
            if ((strlen(utf8_decode($value['name'])) < 1)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_name');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/supply_period')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }


    public function autocomplete() {
        $json = array();
        $name=$this->request->post['filter_name'];
        $this->load->model('catalog/packet');
        $results = $this->model_catalog_packet->search_name($name);
        foreach($results as $result){
            $json[] = array(
                'coupon_id'    => $result['coupon_id'],
                'name'         => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
                'code'         => $result['code'],
                'date_start'   => $result['date_start'],
                'date_end'     => $result['date_end'],
                'discount'     => $result['discount'],
                'type'         => $result['type']
            );
        }

        /*
        if(isset($this->request->post['filter_name'])){
            $this->request->get['filter_name']=trim($this->request->post['filter_name']);
        }

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_sku'])  || isset($this->request->get['filter_category_id'])) {
            $this->load->model('catalog/packet');

            $requestes=array(
                'filter_name' => '',
                'filter_model' => '',
                'filter_sku' => '',
                'filter_category_id' => '',
                'filter_sub_category' => '',
                'limit' => 20
            );

            foreach ($requestes as $key => $value) {
                if (isset($this->request->get[$key])&&$this->request->get[$key]!='') {
                    $$key = trim($this->request->get[$key]);
                } else {
                    $$key = $value;
                }
            }

            $data = array(
                'filter_name'         => $filter_name,
                'filter_model'        => $filter_model,
                'filter_sku'       	  => $filter_sku,
                'filter_category_id'  => $filter_category_id,
                'filter_sub_category' => $filter_sub_category,
                'start'               => 0,
                'limit'               => $limit
                //,'filter_status'       => 1 菜品状态判断暂时关闭 20150404
            );

            $results = $this->model_catalog_packet->search_name($data);

            foreach ($results as $result) {
                $option_data = array();

                $product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

                foreach ($product_options as $product_option) {
                    if ($product_option['type'] == 'select' ||$product_option['type'] == 'virtual_product' ||$product_option['type'] == 'color' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' || $product_option['type'] == 'autocomplete') {
                        $option_value_data = array();

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                            $option_value_data[] = array(
                                'product_option_value_id' => $product_option_value['product_option_value_id'],
                                'option_value_id'         => $product_option_value['option_value_id'],
                                'name'                    => $product_option_value['name'],
                                'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
                                'price_prefix'            => $product_option_value['price_prefix']
                            );
                        }

                        $option_data[] = array(
                            'product_option_id' => $product_option['product_option_id'],
                            'option_id'         => $product_option['option_id'],
                            'name'              => $product_option['name'],
                            'type'              => $product_option['type'],
                            'option_value'      => $option_value_data,
                            'required'          => $product_option['required']
                        );
                    } else {
                        $option_data[] = array(
                            'product_option_id' => $product_option['product_option_id'],
                            'option_id'         => $product_option['option_id'],
                            'name'              => $product_option['name'],
                            'type'              => $product_option['type'],
                            'option_value'      => $product_option['option_value'],
                            'required'          => $product_option['required']
                        );
                    }
                }

                $json[] = array(
                    'product_id' => $result['upid'],
                    'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
                    'model'      => $result['model'],
                    'sku'      => $result['sku'],
                    'option'     => $option_data,
                    'price'      => $result['price']
                );
            }
        }
        */

        $this->response->setOutput(json_encode($json));
    }


}

?>