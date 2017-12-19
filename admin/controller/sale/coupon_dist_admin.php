<?php
class ControllerSaleCouponDistAdmin extends Controller {
    private $error = array();

    /**
     * 加载必要语言，model
     */
    protected function init(){

        $this->load_language('sale/coupon_dist_admin');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/coupon_dist_admin');

        $this->getList();
    }

    public function index() {
        $this->init();
    }
//获取优惠券列表
    private function getList() {

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
//        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('sale/coupon_dist_admin', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));

        $this->data['heading_title']="用户优惠券信息";

        $params = $this->request->get;
        foreach($params as $k=>$v){
            $this->data[$k]=$v;
        }
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'coupon_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
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

        $token = $this->session->data['token'];
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
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $token, 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('sale/coupon_dist_admin', 'token=' . $token . $url, 'SSL'),
            'separator' => $this->language->get('text_breadcrumb_separator')
        );

        $this->data['insertCoupon'] = $this->url->link('sale/coupon_dist_admin/insertCoupon', 'token=' . $token . $url, 'SSL');
        $this->data['insertPacket'] = $this->url->link('sale/coupon_dist_admin/insertPacket', 'token=' . $token . $url, 'SSL');
        $this->data['delete'] = $this->url->link('sale/coupon_dist_admin/delete', 'token=' . $token . $url, 'SSL');
        $this->data['link-list'] = $this->url->link('sale/coupon', 'token=' . $token . $url, 'SSL');
        $this->data['token'] = $token;


        $this->data['coupons'] = array();

        $data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit'),
        );

        $dbResults = $this->model_sale_coupon_dist_admin->get_coupon_info($data);
        $coupon_total = $dbResults['total'];
        $results=$dbResults['rows'];
        //$this->data['result']=$results;
        foreach($results as $akey => $avalue){
            $results[$akey]['couponinfo']['status']=($avalue['couponinfo']['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'));
        }
        $this->data['result']=$results;


        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=name' . $url;
        $this->data['sort_code'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=code' . $url;
        $this->data['sort_discount'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=discount' . $url;
        $this->data['sort_date_start'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=date_start' . $url;
        $this->data['sort_date_end'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=date_end' . $url;
        $this->data['sort_duration'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=duration' . $url;
        $this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=status' . $url;


        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $coupon_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = HTTPS_SERVER . 'index.php?route=sale/coupon_dist_admin&token=' . $token . $url . '&page={page}';

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;




        $this->template = 'sale/coupon_dist_list.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
    }
    
   //加载view视图
    public  function insertCoupon(){
        $this->load_language('sale/coupon_dist_admin');
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('sale/coupon_dist_admin', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_dist_coupon'), $this->url->link('sale/coupon_dist_admin/insertCoupon', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
        
        $this->data['heading_title']="添加用户优惠券";
        $this->data['column_name']="列名";
        $this->data['column_action']="action";
        $this->data['token'] = $this->session->data['token'];
        
        $this->template = 'sale/coupon_dist_form.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
    }
    
    //加载view视图
    public  function insertPacket(){
        $this->load_language('sale/coupon_dist_admin');
        $this->data['breadcrumbs'] = array();
    
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('sale/coupon_dist_admin', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_dist_packet'), $this->url->link('sale/coupon_dist_admin/insertPacket', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
        
    
        $this->data['heading_title']="添加用户礼包";
        $this->data['column_name']="列名";
        $this->data['column_action']="action";
        $this->data['token'] = $this->session->data['token'];
        $this->data['action'] =  $this->url->link('sale/coupon_dist_admin/addPacket2User', 'token=' . $this->session->data['token'], 'SSL');
    
        $this->template = 'sale/packet_dist_form.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';
    
        $this->render();
    }
    

    //插入数据
    public function insterinto()
    {
        $this->load->model('sale/coupon_dist_admin');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];  //排序
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


        if ((isset($this->request->files['conpon_file'])) && (is_uploaded_file($this->request->files['conpon_file']['tmp_name']))) {
            $file = $this->request->files['conpon_file']['tmp_name'];
            $result = $this->model_sale_coupon_dist_admin->import_excel($file);//导入数据
            if ($result) {
                $error_str='';
                foreach($result as $v){
                    $error_str.=$v."<br>";
                }
                $this->session->data['errormsg']=$error_str;
                $this->redirect($this->url->link('error/msg', 'heading_title=发现错误', 'SSL'));
            } else {
                $this->redirect($this->url->link('sale/coupon_dist_admin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
        } else {
            $result = $this->model_sale_coupon_dist_admin->inster_conponinfo($this->request->post);
            if ($result) {
                $error_str='';
                foreach($result as $v){
                    $error_str.=$v."<br>";
                }
                $this->session->data['errormsg']=$error_str;
                $this->redirect($this->url->link('error/msg', 'heading_title=发现错误', 'SSL'));
            } else {

                $this->redirect($this->url->link('sale/coupon_dist_admin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

        }
    }
    
    //插入数据
    public function addPacket2User()
    {
        $this->load->model('sale/coupon_dist_admin');
    
        $url = '';
    
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];  //排序
        }
    
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
    
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
    
    
        if ((isset($this->request->files['conpon_file'])) && (is_uploaded_file($this->request->files['conpon_file']['tmp_name']))) {
            $file = $this->request->files['conpon_file']['tmp_name'];
            $result = $this->model_sale_coupon_dist_admin->addPacketByFile($file);//导入数据
            if ($result) {
                $error_str='';
                foreach($result as $v){
                    $error_str.=$v."<br>";
                }
                $this->session->data['errormsg']=$error_str;
                $this->redirect($this->url->link('error/msg', 'heading_title=发现错误', 'SSL'));
            } else {
                $this->redirect($this->url->link('sale/coupon_dist_admin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
        } else {
            $result = $this->model_sale_coupon_dist_admin->addPacket($this->request->post);
            if ($result) {
                $error_str='';
                foreach($result as $v){
                    $error_str.=$v."<br>";
                }
                $this->session->data['errormsg']=$error_str;
                $this->redirect($this->url->link('error/msg', 'heading_title=发现错误', 'SSL'));
            } else {
    
                $this->redirect($this->url->link('sale/coupon_dist_admin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
    
        }
    }


    //删除数据

    public function delete(){
        $this->load_language('sale/coupon');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/coupon_dist_admin');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {

            foreach ($this->request->post['selected'] as $coupon_id) {

                $this->model_sale_coupon_dist_admin->deleteConpon($coupon_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];  //排序
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->redirect($this->url->link('sale/coupon_dist_admin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }








    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'sale/coupon')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 面包屑
     */
    private function createBreadcrumbs($text,$href,$separator)
    {
        return array(
            'text'      =>$text,
            'href'      => $href,
            'separator' => $separator
        );
    }


    /**
     * 检索礼包
     */
    public function autocompletepacket() {
        $json = array();
        $name=$this->request->post['packetname'];
        $this->load->model('catalog/packet');
        $results = $this->model_catalog_packet->searchPakcetByName($name);
        foreach($results as $result){
            $json[] = array(
                'packet_id'    => $result['packet_id'],
                'name'         => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
                'batch'        => $result['batch']
            );
        }
    
        $this->response->setOutput(json_encode($json));
    }
    

}
?>