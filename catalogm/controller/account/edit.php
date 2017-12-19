<?php

class ControllerAccountEdit extends Controller {
    private $error = array();

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/edit', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load_language('account/edit');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/customer');

        if (isset($this->request->get['do'])) {
            $this->data['text_message'] = sprintf($this->language->get('text_message'), $this->url->link('information/contact'));
        } else {
            $this->data['text_message'] = '';
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            try {
                $this->model_account_customer->editCustomer($this->request->post);
                $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));
            } catch (Exception $e) {
                $this->session->data['errors'] = $error;
            }


            $this->redirect($this->url->link('account/account', '', 'SSL'));
        }

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
            'text' => $this->language->get('text_edit'),
            'href' => $this->url->link('account/edit', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );


        $error_fields = array('warning', 'email', 'mobile', 'telephone', 'name');

        foreach ($error_fields as $field) {
            if (isset($this->error[$field])) {
                $this->data['error_' . $field] = $this->error[$field];
            } else {
                $this->data['error_' . $field] = '';
            }
        }

        $this->data['action'] = $this->url->link('account/edit', '', 'SSL');

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            //New ADd logic for company role
            if ($customer_info['type'] == 1) {
                $this->redirect($this->url->link('account/edit/company'));
            }
        }

        $fields = array('firstname', 'salution', 'email', 'telephone', 'fax', 'mobile');

        foreach ($fields as $field) {
            if (isset($this->request->post[$field])) {
                $this->data[$field] = $this->request->post[$field];
            } elseif (isset($customer_info)) {
                $this->data[$field] = $customer_info[$field];
            } else {
                $this->data[$field] = '';
            }
        }

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } elseif (isset($customer_info)) {
            $this->data['name'] = $customer_info['firstname'];
        } else {
            $this->data['name'] = '';
        }


        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
				$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
				
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/edit.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/edit.tpl';
        } else {
            $this->template = 'default/template/account/edit.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer35',
            'common/header35'
        );

        $this->response->setOutput($this->render());
    }

    public function company() {
        $this->load_language('account/edit');
        $this->load_language('account/edit_company');

        $this->load->model('account/customer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate2()) {
            $this->model_account_customer->editCustomerCompanyInfo($this->customer->getId(), $this->request->post);

            $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));

            $this->redirect($this->url->link('account/edit', '', 'SSL'));
        }

        $this->getForm();
    }

    private function getForm() {
        $this->load_language('account/edit_company');

        $fields = array('firstname', 'department', 'telephone', 'mobile', 'company', 'company_address', 'website');

        foreach ($fields as $field) {
            if (isset($this->error[$field])) {
                $this->data['error_' . $field] = $this->error[$field];
            } else {
                $this->data['error_' . $field] = '';
            }
        }

        $this->data['action'] = $this->url->link('account/edit/company', 'SSL');

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        }

        foreach ($fields as $field) {
            if (isset($this->request->post[$field])) {
                $this->data[$field] = $this->request->post[$field];
            } elseif (isset($customer_info)) {
                $this->data[$field] = $customer_info[$field];
            } else {
                $this->data[$field] = '';
            }
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } elseif (isset($customer_info)) {
            $this->data['email'] = $customer_info['email'];
        } else {
            $this->data['email'] = '';
        }


        if (isset($this->request->post['fax'])) {
            $this->data['fax'] = $this->request->post['fax'];
        } elseif (isset($customer_info)) {
            $this->data['fax'] = $customer_info['fax'];
        } else {
            $this->data['fax'] = '';
        }

        $this->data['back'] = $this->url->link('account/account', '', 'SSL');

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }


        $this->data['action'] = $this->url->link('account/edit/company', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/edit_company.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/edit_company.tpl';
        } else {
            $this->template = 'default/template/account/edit_company.tpl';
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

    private function validate() {

        if ((strlen(utf8_decode($this->request->post['name'])) < 1)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        $id = $this->customer->getId();
        $mobile = $this->request->post['mobile'];
        $email = $this->request->post['email'];

        if ((strlen(utf8_decode($mobile)) < 1)) {
            $this->error['mobile'] = $this->language->get('error_mobile');
        } else if (!preg_match('/^[0-9]{11}$/', $mobile)) {
            $this->error['mobile'] = $this->language->get('error_mobile_format');
        } else if ($this->model_account_customer->existsOtherCustomerWithSameField('mobile', $mobile, $id)) {
            $this->error['mobile'] = $this->language->get('error_mobile_used');
        }
        if(!empty($email)){
            if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                $this->error['email'] = $this->language->get('error_email');
            }else if ($this->model_account_customer->existsOtherCustomerWithSameField('email', $email, $id)) {
                $this->error['email'] = $this->language->get('error_email_used');
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function validate2() {
        $fields = array('firstname', 'department', 'telephone', 'mobile', 'company', 'company_address', 'website');

        foreach ($fields as $field) {
            if ((strlen(utf8_decode($this->request->post[$field])) < 1)) {
                $this->error[$field] = $this->language->get('error_' . $field);
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}

?>