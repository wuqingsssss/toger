<?php

class ControllerCatalogSupplyPeriod0 extends Controller {
    private $error = array();

    protected function init() {
        $this->load_language('catalog/supply_period0');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/supply_period0');
    }

    protected function redirectToList() {
        $this->session->data['success'] = $this->language->get('text_success');

        $url = $this->getUrlCommonParameters();

        $this->redirect($this->url->link('catalog/supply_period0', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function index() {
        $this->init();

        $this->getList();
    }

    public function insert() {
        $this->init();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $postData = array_merge(array(),$this->request->post);
            $postData['creator_id']=$this->user->getId();
            $this->model_catalog_supply_period0->create($postData);

            $this->redirectToList();
        }

        $this->getForm();
    }

    public function update() {
        $this->init();
 
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
        
            $this->model_catalog_supply_period0->update($this->request->get['id'], $this->request->post);

            $this->redirectToList();
        }

        $this->getForm();
    }

    public function delete() {
        $this->init();

        $ids = str_replace('&quot;', '"', $this->request->post['ids']);
        $ids=json_decode($ids);
        foreach($ids as $id){
            $this->model_catalog_supply_period0->delete($id);
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
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/supply_period0', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->data['results'] = $this->model_catalog_supply_period0->all();

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

        $this->template = 'catalog/supply_period0_list.tpl';
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
            'href' => $this->url->link('catalog/supply_period0', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $url = $this->getUrlCommonParameters();

        if (!isset($this->request->get['id'])) {
            $this->data['action'] = $this->url->link('catalog/supply_period0/insert',  'SSL');

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_add'),
                'href' => $this->data['action'],
                'separator' => $this->language->get('text_breadcrumb_separator')
            );
        } else {
            $this->data['action'] = $this->url->link('catalog/supply_period0/update', 'id=' . $this->request->get['id'] . $url, 'SSL');
            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->data['action'],
                'separator' => $this->language->get('text_breadcrumb_separator')
            );
        }

        $id = $this->request->get['id'];
        if (isset($id) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $this->data['result'] = $this->model_catalog_supply_period0->get($id);
            $products = $this->model_catalog_supply_period0->getProducts($id);
           
			$this->data['products'] = array();
			foreach ($products as $product) {
				$this->data['products'][$product['delivery_id']]['region_name']=$product['region_name'];
				$this->data['products'][$product['delivery_id']]['delivery_code']=$product['delivery_code'];
				$this->data['products'][$product['delivery_id']]['data'][$product['product_id']]["product_id"]=$product['product_id'];
				$this->data['products'][$product['delivery_id']]['data'][$product['product_id']]["delivery_id"]=$product['delivery_id'];
				$this->data['products'][$product['delivery_id']]['data'][$product['product_id']]["name"]=$product['name'];
				$this->data['products'][$product['delivery_id']]['data'][$product['product_id']]["sku"]=$product['sku'];
				$this->data['products'][$product['delivery_id']]['data'][$product['product_id']]["price"]=$product['price'];
				$this->data['products'][$product['delivery_id']]['data'][$product['product_id']]["sort_order"]=$product['sort_order'];
				$this->data['products'][$product['delivery_id']]['data'][$product['product_id']]["status"]=$product['status'];
				$this->data['products'][$product['delivery_id']]['data'][$product['product_id']]['date'][$product['period_date']]['quantity']=$product['quantity'];
				$this->data['products'][$product['delivery_id']]['data'][$product['product_id']]['date'][$product['period_date']]['stock']=$product['stock'];
			}
			//print_r($this->data['products']);
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

        $this->load->model('catalog/pointdelivery');
        $delivery=$this->config->get('delivery_express');
     
        foreach($delivery as $key=>$item)
        {
        	$item['points']=$this->model_catalog_pointdelivery->getDeliverys(array('filter_p_delivery_id'=>0,'filter_status'=>1,'filter_code'=>$item['code']));
        	$this->data['deliverys'][$item['code']]=$item;
        }
        

        $this->template = 'catalog/supply_period0_form.tpl';
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

}

?>