<?php

class ControllerCommonHeader extends Controller {

    private function initMenu($route, $lang_tag) {
        //判断是否有访问权限
        return array(
            'route' => $route,
            'href' => $this->url->link($route, 'token=' . $this->session->data['token'], 'SSL'),
            'title' => $this->language->get($lang_tag)
        );
    }


    protected function index() {
        $this->load_language('common/header');

        $this->data['guide'] = $this->document->getGuide();

        //TODO:DELETE token check
//   		if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
        if (!$this->user->isLogged()) {
            $this->data['logged'] = '';

            $this->data['home'] = $this->url->link('common/login', '', 'SSL');
        } else {
        	
        	$menus=$this->config->get('menu');
        	$this->dada['menus']=array();
        	foreach($menus as $kgroup=> $groups){
        		$this->data['menus'][$kgroup] = array();
        		foreach($groups as $rqroute=>$item)
        		{
        		   if($item['show_menu']&&$this->user->chkPermission('',$rqroute)) 
        			    $this->data['menus'][$kgroup][]=$this->initMenu($rqroute, $item['title']);;
        		}
        	}
        	if($this->dada['menus']){
        	
            if ($this->user->permitOr(array('super_admin', 'product_admin'))) {
                $this->data['groups1'] = array();
                $this->data['groups1'][] = $this->initMenu('catalog/category', 'text_category');
                $this->data['groups1'][] = $this->initMenu('catalog/supply_period', 'text_supply_period');
                $this->data['groups1'][] = $this->initMenu('catalog/supply_period0', 'text_supply_period0');
                $this->data['groups1'][] = $this->initMenu('catalog/product', 'text_product');
                $this->data['groups1'][] = $this->initMenu('catalog/attribute', 'text_attribute');
                $this->data['groups1'][] = $this->initMenu('catalog/attribute_group', 'text_attribute_group');
                $this->data['groups1'][] = $this->initMenu('catalog/option', 'text_option');
                $this->data['groups1'][] = $this->initMenu('catalog/manufacturer', 'text_manufacturer');
                $this->data['groups1'][] = $this->initMenu('catalog/download', 'text_download');
                $this->data['groups1'][] = $this->initMenu('catalog/review', 'text_review');
                $this->data['groups1'][] = $this->initMenu('catalog/consulation', 'text_consulation');
                $this->data['groups1'][] = $this->initMenu('catalog/vote', 'text_vote');
            }

            if ($this->user->permitOr(array('super_admin', 'info_admin'))) {
                $this->data['groups2'] = array();
                $this->data['groups2'][] = $this->initMenu('catalog/articlecate', 'text_article_categories');
                $this->data['groups2'][] = $this->initMenu('catalog/articlecate/insert', 'text_add_article_category');
                $this->data['groups2'][] = $this->initMenu('catalog/article', 'text_article');
                $this->data['groups2'][] = $this->initMenu('catalog/article/insert', 'text_add_article');
                $this->data['groups2'][] = $this->initMenu('catalog/note', 'text_note');
                $this->data['groups2'][] = $this->initMenu('catalog/information_group', 'text_information_group');
                $this->data['groups2'][] = $this->initMenu('catalog/information', 'text_information');
                $this->data['groups2'][] = $this->initMenu('catalog/link', 'text_link_lists');
                $this->data['groups2'][] = $this->initMenu('catalog/link_group', 'text_link_group');
				$this->data['groups2'][] = $this->initMenu('catalog/question', '问题后台管理系统');
            }


            if ($this->user->permitOr(array('super_admin'))) {
                $this->data['groups3'] = array();
                $this->data['groups3'][] = $this->initMenu('extension/module', 'text_module');
                $this->data['groups3'][] = $this->initMenu('extension/shipping', 'text_shipping');
                $this->data['groups3'][] = $this->initMenu('extension/payment', 'text_payment');
                $this->data['groups3'][] = $this->initMenu('extension/sms', 'text_smspath');
                $this->data['groups3'][] = $this->initMenu('extension/total', 'text_total');
                $this->data['groups3'][] = $this->initMenu('extension/feed', 'text_feed');
                $this->data['groups3'][] = $this->initMenu('extension/tool', 'text_tool');
                $this->data['groups3'][] = $this->initMenu('design/layout', 'text_layout');
                $this->data['groups3'][] = $this->initMenu('design/banner', 'text_banner');
				$this->data['groups3'][] = $this->initMenu('extension/thirdplatform', 'text_platform');
//                $this->data['groups3'][] = $this->initMenu('design/banner_period', 'text_banner_period');
//			$this->data['groups3'][]=$this->initMenu('common/filemanager','text_media');
            }

            if ($this->user->permitOr(array('super_admin', 'sale_orders', 'refund_orders', 'purchase_orders', 'other_sale_funcs','self_help_points'))) {
                $this->data['groups4'] = array();
                if ($this->user->permitOr(array('super_admin', 'sale_orders')))
                    $this->data['groups4'][] = $this->initMenu('sale/order', 'text_order');
                if ($this->user->permitOr(array('super_admin', 'refund_orders'))){
                    $this->data['groups4'][] = $this->initMenu('sale/order_refund', 'text_order_refund');
                	$this->data['groups4'][] = $this->initMenu('sale/order_refund_check', 'text_order_refund_check');
                }
                if ($this->user->permitOr(array('super_admin', 'purchase_orders')))
                    $this->data['groups4'][] = $this->initMenu('sale/order_purchase', 'text_order_purchase');
                if ($this->user->permitOr(array('super_admin', 'sorting_orders')))
                    $this->data['groups4'][] = $this->initMenu('sale/pick_stock', 'text_pick_stock');
                if ($this->user->permitOr(array('super_admin', 'coupons '))){
                    $this->data['groups4'][] = $this->initMenu('sale/coupon', 'text_coupon');
                    $this->data['groups4'][] = $this->initMenu('sale/coupon_dist_admin', 'text_coupon_dist');
                    $this->data['groups4'][] = $this->initMenu('catalog/packet', 'text_packet_manage');
                    $this->data['groups4'][] = $this->initMenu('catalog/campaign', 'text_campaign_manage');
                    $this->data['groups4'][] = $this->initMenu('sale/transaction', 'text_transaction');
                }
                if ($this->user->permitOr(array('super_admin', 'other_sale_funcs'))) {
                    $this->data['groups4'][] = $this->initMenu('sale/enquiry', 'text_enquiry');
                    $this->data['groups4'][] = $this->initMenu('sale/return', 'text_return');
                    $this->data['groups4'][] = $this->initMenu('sale/customer', 'text_customer');
                    $this->data['groups4'][] = $this->initMenu('sale/customer_group', 'text_customer_group');
                    $this->data['groups4'][] = $this->initMenu('sale/affiliate', 'text_affiliate');
                    $this->data['groups4'][] = $this->initMenu('sale/voucher', 'text_voucher');
                    $this->data['groups4'][] = $this->initMenu('sale/voucher_theme', 'text_voucher_theme');
                    $this->data['groups4'][] = $this->initMenu('sale/contact', 'text_contact');
                    $this->data['groups4'][] = $this->initMenu('sale/sms', 'text_sms');
                    $this->data['groups4'][] = $this->initMenu('sale/message', 'text_word');
                  //  $this->data['groups4'][] = $this->initMenu('report/sale', 'text_reports');
                    $this->data['groups4'][] = $this->initMenu('promotion/promotion', 'text_promotion');
                    $this->data['groups4'][] = $this->initMenu('question/question', 'text_user_info');
                    $this->data['groups4'][] = $this->initMenu('question/erweima', 'text_erweima_info');
                    $this->data['groups4'][] = $this->initMenu('catalog/reference_list', 'text_reference');
                }
                if ($this->user->permitOr(array('super_admin', 'self_help_points'))) {
                    $this->data['groups4'][] = $this->initMenu('catalog/cbd', 'text_cbd');
                    $this->data['groups4'][] = $this->initMenu('catalog/point', 'text_points');
                    $this->data['groups4'][] = $this->initMenu('catalog/pointdelivery', 'text_point_delivery');
                    $this->data['groups4'][] = $this->initMenu('sale/show_coupon', '优惠卷领取关联页面');
					$this->data['groups4'][] = $this->initMenu('sale/show_coupon/show_list', '领取页面管理');
					$this->data['groups4'][] = $this->initMenu('sale/group', '拼团');
                }
            }

            if ($this->user->permitOr(array('super_admin', 'system_admin'))) {
                $this->data['groups5'] = array();
                $this->data['groups5'][] = $this->initMenu('setting/store', 'text_setting');
                $this->data['groups5'][] = $this->initMenu('setting/parameter', 'text_localisation');
                $this->data['groups5'][] = $this->initMenu('user/user', 'text_user');
                $this->data['groups5'][] = $this->initMenu('user/user_permission', 'text_user_group');
               // $this->data['groups5'][] = $this->initMenu('tool/error_log', 'text_error_log');
                $this->data['groups5'][] = $this->initMenu('tool/sys_log', 'text_sys_log');
                $this->data['groups5'][] = $this->initMenu('tool/oss', 'text_oss');      
                $this->data['groups5'][] = $this->initMenu('tool/app_web', 'text_app_web');
                $this->data['groups5'][] = $this->initMenu('tool/backup', 'text_backup');
            }
        	}

            $this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
            $this->data['sitemap'] = $this->url->link('catalog/sitemap', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['home'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['seo'] = $this->url->link('seo/url_alias', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['affiliate'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['attribute'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['attribute_group'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['banner'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['coupon'] = $this->url->link('sale/coupon', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['customer_group'] = $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['download'] = $this->url->link('catalog/download', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['downloadcategory'] = $this->url->link('catalog/downloadcategory', 'token=' . $this->session->data['token'], 'SSL');
          //  $this->data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL');
              $this->data['sys_log'] = $this->url->link('tool/sys_log', 'token=' . $this->session->data['token'], 'SSL');
              $this->data['oss'] = $this->url->link('tool/oss', 'token=' . $this->session->data['token'], 'SSL');
              
              $this->data['feed'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['media'] = $this->url->link('common/filemanager/manager', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['tool'] = $this->url->link('extension/tool', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['stores'] = array();

            $this->load->model('setting/store');

            $results = $this->model_setting_store->getStores();

            foreach ($results as $result) {
                $this->data['stores'][] = array(
                    'name' => $result['name'],
                    'href' => $result['url']
                );
            }

            $this->data['total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['information_group'] = $this->url->link('catalog/information_group', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['layout'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['contact'] = $this->url->link('sale/contact', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['sms'] = $this->url->link('sale/sms', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['manufacturer'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['option'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['shipping'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['parameter'] = $this->url->link('setting/parameter', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['voucher'] = $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['voucher_theme'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['message'] = $this->url->link('catalog/message', 'token=' . $this->session->data['token'], 'SSL');
          //  $this->data['report'] = $this->url->link('report/sale', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['store'] = HTTP_CATALOG;

            $this->data['article_categories'] = HTTPS_SERVER . 'index.php?route=catalog/articlecate&token=' . $this->session->data['token'];
            $this->data['add_article_category'] = HTTPS_SERVER . 'index.php?route=catalog/articlecate/insert&token=' . $this->session->data['token'];
            $this->data['article'] = HTTPS_SERVER . 'index.php?route=catalog/article&token=' . $this->session->data['token'];
            $this->data['add_article'] = HTTPS_SERVER . 'index.php?route=catalog/article/insert&token=' . $this->session->data['token'];

            $this->data['export'] = HTTPS_SERVER . 'index.php?route=tool/export&token=' . $this->session->data['token'];

            $this->data['note'] = HTTPS_SERVER . 'index.php?route=catalog/note&token=' . $this->session->data['token'];

            $this->data['link_lists'] = $this->url->link('catalog/link', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['link_group'] = $this->url->link('catalog/link_group', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['gather'] = $this->url->link('tool/gather', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['enquiry'] = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['cbd'] = $this->url->link('catalog/cbd', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['points'] = $this->url->link('catalog/point', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['password'] = $this->url->link('user/password', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['consulation'] = $this->url->link('catalog/consulation', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['vote'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['promotion'] = $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL');
        }

        $this->id = 'header';
        $this->template = 'common/header.tpl';

        $this->render();
    }
}

?>