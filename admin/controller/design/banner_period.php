<?php

class ControllerDesignBannerPeriod extends Controller {
    private $error = array();

    protected function init() {
        $this->load_language('design/banner_period');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/banner_period');
        $this->load->model('design/banner');
        $this->load->model('tool/image');
    }

    protected function redirectToList() {
        $this->session->data['success'] = $this->language->get('text_success');

        $url = $this->getUrlCommonParameters();

        $this->redirect($this->url->link('design/banner_period', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function index() {
        $this->init();

        $this->getList();
    }

    public function insert() {
        $this->init();

        $tmp=$this->request->get['banner_id'];
        $banner_id = $this->data['banner_id'] = (int)$this->request->get['banner_id'];
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $postData = array_merge(array(), $this->request->post);
            $postData['creator_id'] = $this->user->getId();
            $postData['banner_id'] = $banner_id;
            $this->model_design_banner_period->create($postData);

            $this->redirectToList();
        }

        $this->getForm();
    }

    public function update() {
        $this->init();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_design_banner_period->update($this->request->get['id'], $this->request->post);

            $this->redirectToList();
        }



        $this->getForm();
    }

    public function delete() {
        $this->init();

        $ids = str_replace('&quot;', '"', $this->request->post['ids']);
        $ids = json_decode($ids);
        foreach ($ids as $id) {
            $this->model_design_banner_period->delete($id);
        }

        $json = array();
        $json['success'] = true;
        $this->load->library('json');
        $this->response->setOutput(Json::encode($json));
    }


    private function getUrlCommonParameters() {
        $url = '';

        if (isset($this->request->get['banner_id'])) {
            $url .= "&banner_id=" . $this->request->get['banner_id'];
        }

        return $url;
    }

    private function getList() {
        $banner_id = $this->data['banner_id'] = (int)$this->request->get['banner_id'];

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => '横幅',
            'href' => $this->url->link('design/banner', '', 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );


        $this->data['results'] = $this->model_design_banner_period->all($banner_id);
        $this->data['banner'] = $this->model_design_banner->getBanner($banner_id);

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

        $this->template = 'design/banner_period_list.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    private function getImages($bannerId) {
        $banner_images = $this->model_design_banner->getBannerImages($bannerId);

        $this->data['all_banner_images'] = array();

        foreach ($banner_images as $banner_image) {
            if ($banner_image['image'] && file_exists(DIR_IMAGE . $banner_image['image'])) {
                $image = $banner_image['image'];
            } else {
                $image = 'no_image.jpg';
            }

            $this->data['all_banner_images'][] = array(
                'banner_image_description' => $banner_image['banner_image_description'],
                'link' => $banner_image['link'],
                'banner_image_id' => $banner_image['banner_image_id'],
                'image' => $image,
                'preview' => $this->model_tool_image->resize($image, 100, 100)
            );
        }
    }

    private function getForm() {
        $banner_id = (int)$this->request->get['banner_id'];
        $this->data['banner'] = $this->model_design_banner->getBanner($banner_id);

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
            'href' => $this->url->link('common/home', '', 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => '横幅',
            'href' => $this->url->link('design/banner', '', 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );
        $this->data['breadcrumbs'][] = array(
            'text' => '周期',
            'href' => $this->url->link('design/banner_period', 'banner_id=' . $banner_id, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->getImages($banner_id);
        $periodId = $this->request->get['id'];

        $url = $this->getUrlCommonParameters();
        if (!isset($periodId)) {
            $this->data['action'] = $this->url->link('design/banner_period/insert',$url,  'SSL');

//            $this->data['breadcrumbs'][] = array(
//                'text' => $this->language->get('text_add'),
//                'href' => $this->data['action'],
//                'separator' => $this->language->get('text_breadcrumb_separator')
//            );
        } else {
            $this->data['action'] = $this->url->link('design/banner_period/update', 'id=' . $this->request->get['id'] . $url, 'SSL');
//            $this->data['breadcrumbs'][] = array(
//                'text' => $this->language->get('text_edit'),
//                'href' => $this->data['action'],
//                'separator' => $this->language->get('text_breadcrumb_separator')
//            );
        }

        if (isset($periodId) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $this->data['result'] = $this->model_design_banner_period->get($periodId);
            $this->data['images'] = $this->model_design_banner_period->getItems($periodId);

        } else {
            $this->data['result'] = array();
            $this->data['images'] = array();
        }

        $this->load->model('localisation/language');

        $this->data['languages'] = $this->model_localisation_language->getLanguages();


//
//        if (isset($this->request->post['category_layout'])) {
//            $this->data['category_layout'] = $this->request->post['category_layout'];
//        } elseif (isset($category_info)) {
//            $this->data['category_layout'] = $this->model_banner_period->getCategoryLayouts($this->request->get['id']);
//        } else {
//            $this->data['category_layout'] = array();
//        }


        $this->template = 'design/banner_period_form.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
        $this->render();
    }

    private function validateForm() {
        $rules = $this->load->rule();
        $this->load_language('error_msg');

        if (!$this->user->hasPermission('modify', 'design/banner_period')) {
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

}

?>