<?php

class ControllerCatalogCampaign extends Controller {
    private $error = array();

    protected function init() {
        $this->load_language('catalog/campaign');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/campaign');
    }

    protected function redirectToList() {
        $this->session->data['success'] = $this->language->get('text_success');

        $url = $this->getUrlCommonParameters();

        $this->redirect($this->url->link('catalog/campaign', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
            $this->model_catalog_campaign->create($postData);

            $this->redirectToList();
        }

        $this->getForm();
    }

    public function update() {
        $this->log_admin->trace("");
        
        $this->init();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_campaign->update($this->request->get['id'], $this->request->post);

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
            'href'      => $this->url->link('catalog/campaign', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->data['insert'] = $this->url->link('catalog/campaign/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['distribute'] = $this->url->link('catalog/campaign/distribute', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['delete'] = $this->url->link('catalog/campaign/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['points'] = array();

        $data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $point_total = $this->model_catalog_campaign->getTotal($data);

        $results = $this->model_catalog_campaign->getall($data);

        foreach ($results as $result) {
            $action = array();

            $this->data['campaigns'][] = array(
                'campaign_id'        => $result['campaign_id'],
                'name'               => $result['name'],
                'code'               => $result['code'],
                'date_start'         => $result['date_start'],
                'date_end'           => $result['date_end'],
                'rules'              => $result['rules'],
                'status'             => $result['status'],
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
        $pagination->url = $this->url->link('catalog/campaign', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'catalog/campaign_list.tpl';

        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
    }

    /**
     * 
     */
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
            'href' => $this->url->link('catalog/campaign', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $url = $this->getUrlCommonParameters();

        if (!isset($this->request->get['id'])) {
          $this->data['action'] = $this->url->link('catalog/campaign/insert',  'token=' . $this->session->data['token'], 'SSL');

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_add'),
                'href' => $this->data['action'],
                'separator' => $this->language->get('text_breadcrumb_separator')
            );
        } else {
            $this->data['action'] = $this->url->link('catalog/campaign/update', 'id=' . $this->request->get['id'] . $url, 'SSL');
            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->data['action'],
                'separator' => $this->language->get('text_breadcrumb_separator')
            );
        }

        $id = $this->request->get['id'];
        if (isset($id) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $this->data['campaign'] = $this->model_catalog_campaign->get($id);
            $rules = $this->model_catalog_campaign->getCampaignRules($id);
			$this->data['rules'] = $rules;
			
        }else{
            $this->data['campaign']=array();
            $this->data['rules']=array();
        }

        $this->load->model('localisation/language');

        $this->data['languages'] = $this->model_localisation_language->getLanguages();

        $this->template = 'catalog/campaign_form.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    /**
     * 
     * @return boolean
     */
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
        $this->load->model('catalog/campaign');
        $results = $this->model_catalog_campaign->search_name($name);
        foreach($results as $result){
            $json[] = array(
                'packet_id'    => $result['packet_id'],
                'name'         => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
                'cond'         => $result['cond'],
                'date_start'   => $result['date_start'],
                'date_end'     => $result['date_end'],
                'batch'        => $result['batch']
            );
        }


        $this->response->setOutput(json_encode($json));
    }


}

?>