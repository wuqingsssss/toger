<?php 
class ControllerModuleBossStaticblock extends Controller {
	private $error = array(); 
	 
	public function index() {   
		$this->load->language('module/boss_staticblock');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('boss_staticblock', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_header_top'] = $this->language->get('text_header_top');
		$this->data['text_header_bottom'] = $this->language->get('text_header_bottom');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
        $this->data['text_footer_top'] = $this->language->get('text_footer_top');
		$this->data['text_footer_bottom'] = $this->language->get('text_footer_bottom');
        $this->data['text_alllayout'] = $this->language->get('text_alllayout');
		$this->data['text_default'] = $this->language->get('text_default');
		
		$this->data['entry_content'] = $this->language->get('entry_content');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_new_block'] = $this->language->get('button_add_new_block');
		
		$this->data['tab_block'] = $this->language->get('tab_block');
		
		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/boss_staticblock', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/boss_staticblock', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['boss_staticblock_module'])) {
			$this->data['modules'] = $this->request->post['boss_staticblock_module'];
		} elseif ($this->config->get('boss_staticblock_module')) { 
			$this->data['modules'] = $this->config->get('boss_staticblock_module');
		}	
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->template = 'module/boss_staticblock.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/boss_staticblock')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	private function getIdLayout($layout_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "layout WHERE LOWER(name) = LOWER('".$layout_name."')");
		return (int)$query->row['layout_id'];
	}
	
	public function _install() 
	{
		$this->load->model('setting/setting');
		
		$this->load->model('localisation/language');
			
		$languages = $this->model_localisation_language->getLanguages();
		
		$array_description0 = array();
		$array_description1 = array();
		$array_description2 = array();
		$array_description3 = array();
		$array_description4 = array();
		$array_description5 = array();
		$array_description6 = array();
		$array_description7 = array();
		$array_description8 = array();
						
		foreach($languages as $language){
			$array_description0{$language['language_id']} = '&lt;div class=&quot;static-footer-1&quot;&gt;
&lt;div class=&quot;sf-banner&quot;&gt;&lt;img alt=&quot;shipping&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_home_01.jpg&quot; title=&quot;shipping&quot; /&gt;&lt;/div&gt;

&lt;div class=&quot;sf-banner&quot;&gt;&lt;img alt=&quot;questions&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_home_02.jpg&quot; title=&quot;questions&quot; /&gt;&lt;/div&gt;

&lt;div class=&quot;sf-banner&quot;&gt;&lt;img alt=&quot;support&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_home_03.jpg&quot; title=&quot;support&quot; /&gt;&lt;/div&gt;

&lt;div class=&quot;sf-banner last&quot;&gt;&lt;img alt=&quot;livechat&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_home_04.jpg&quot; title=&quot;livechat&quot; /&gt;&lt;/div&gt;
&lt;/div&gt;';
			$array_description1{$language['language_id']} = '&lt;div class=&quot;static-footer-2&quot;&gt;
&lt;h3&gt;Lorem Ipsum DoLor&lt;/h3&gt;

&lt;div class=&quot;eight columns alpha&quot;&gt;&lt;a href=&quot;#&quot; title=&quot;shipping&quot;&gt;&lt;img alt=&quot;shipping&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_bottom_1.jpg&quot; title=&quot;shipping&quot; /&gt;&lt;/a&gt;&lt;/div&gt;

&lt;div class=&quot;eight columns &quot;&gt;&lt;a href=&quot;#&quot; title=&quot;shipping&quot;&gt;&lt;img alt=&quot;shipping&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_bottom_2.jpg&quot; title=&quot;shipping&quot; /&gt;&lt;/a&gt;&lt;/div&gt;

&lt;div class=&quot;eight columns omega&quot;&gt;&lt;a class=&quot;last&quot; href=&quot;#&quot; title=&quot;shipping&quot;&gt;&lt;img alt=&quot;shipping&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_bottom_3.jpg&quot; title=&quot;shipping&quot; /&gt;&lt;/a&gt;&lt;/div&gt;

&lt;div class=&quot;clear&quot;&gt;&amp;nbsp;&lt;/div&gt;

&lt;div class=&quot;sixteen columns alpha&quot;&gt;&lt;a href=&quot;#&quot; title=&quot;shipping&quot;&gt;&lt;img alt=&quot;shipping&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_bottom_4.jpg&quot; title=&quot;shipping&quot; /&gt;&lt;/a&gt;&lt;/div&gt;

&lt;div class=&quot;eight columns omega&quot;&gt;&lt;a class=&quot;last&quot; href=&quot;#&quot; title=&quot;shipping&quot;&gt;&lt;img alt=&quot;shipping&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_bottom_5.jpg&quot; title=&quot;shipping&quot; /&gt;&lt;/a&gt;&lt;/div&gt;
&lt;/div&gt;';
			$array_description2{$language['language_id']} = '&lt;div class=&quot;static-footer-column-1&quot;&gt;
&lt;h3&gt;Latest new&lt;/h3&gt;

&lt;ul&gt;
	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;Preview Autum/Winter 2013&lt;/a&gt;&lt;/li&gt;
	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;Stock Clearence Sales - Save 80%&lt;/a&gt;&lt;/li&gt;
	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;Exdrests Store\'s New Design Online&lt;/a&gt;&lt;/li&gt;
	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;New Customer Offer - $15 Off&lt;/a&gt;&lt;/li&gt;
	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;Grand Store Opening on 05/02&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
&lt;/div&gt;';
			$array_description3{$language['language_id']} = '&lt;div class=&quot;static-footer-column-2&quot;&gt;
&lt;h3&gt;About store&lt;/h3&gt;

&lt;p&gt;Quisque iaculis congue nulla sed sagittis. Sed congue bibendum lacus, id tempus masss porttitor vitae ispumes&lt;/p&gt;

&lt;p&gt;123 Street, XQ Ward, Green City&lt;br /&gt;
Tel: 0010 23199790&lt;br /&gt;
Email: Gomarket@gmail.com&lt;/p&gt;
&lt;/div&gt;';
			$array_description4{$language['language_id']} = '&lt;div class=&quot;static-footer-payment&quot;&gt;
&lt;h3&gt;Payment method&lt;/h3&gt;

&lt;div&gt;&lt;a href=&quot;#&quot; title=&quot;visa&quot;&gt;&lt;img alt=&quot;visa&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/payment_1.png&quot; title=&quot;visa&quot; /&gt;&lt;/a&gt; &lt;a href=&quot;#&quot; title=&quot;mastercard&quot;&gt;&lt;img alt=&quot;mastercard&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/payment_2.png&quot; title=&quot;mastercard&quot; /&gt;&lt;/a&gt; &lt;a href=&quot;#&quot; title=&quot;express&quot;&gt;&lt;img alt=&quot;express&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/payment_3.png&quot; title=&quot;express&quot; /&gt;&lt;/a&gt; &lt;a href=&quot;#&quot; title=&quot;paypal&quot;&gt;&lt;img alt=&quot;paypal&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/payment_4.png&quot; title=&quot;paypal&quot; /&gt;&lt;/a&gt; &lt;a href=&quot;#&quot; title=&quot;skrill&quot;&gt;&lt;img alt=&quot;skrill&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/payment_5.png&quot; title=&quot;skrill&quot; /&gt;&lt;/a&gt;&lt;/div&gt;
&lt;/div&gt;';
			$array_description5{$language['language_id']} = '&lt;div class=&quot;static-footer-follow&quot;&gt;
&lt;h3&gt;Follow Us On&lt;/h3&gt;
&lt;a href=&quot;#&quot; title=&quot;facebook&quot;&gt;&lt;img alt=&quot;facebook&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/follow_1.png&quot; title=&quot;facebook&quot; /&gt;&lt;/a&gt; &lt;a href=&quot;#&quot; title=&quot;twitter&quot;&gt;&lt;img alt=&quot;twitter&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/follow_2.png&quot; title=&quot;mastercard&quot; /&gt;&lt;/a&gt; &lt;a href=&quot;#&quot; title=&quot;flickr&quot;&gt;&lt;img alt=&quot;flickr&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/follow_3.png&quot; title=&quot;flickr&quot; /&gt;&lt;/a&gt; &lt;a href=&quot;#&quot; title=&quot;rss&quot;&gt;&lt;img alt=&quot;rss&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/follow_4.png&quot; title=&quot;rss&quot; /&gt;&lt;/a&gt;&lt;/div&gt;';
			$array_description6{$language['language_id']} = '&lt;div class=&quot;static-footer-link&quot;&gt;&lt;a href=&quot;#&quot;&gt;Fusce Uttest&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Diam Tempor&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Justo Malesuada&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Volutpat&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Aliquam Auctor&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Elit Quis&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Magna Porta&lt;/a&gt;&lt;a class=&quot;last&quot; href=&quot;#&quot;&gt;Commodo&lt;/a&gt;&lt;br /&gt;
&lt;a href=&quot;#&quot;&gt;Fusce Uttest&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Diam Tempor&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Justo Malesuada&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Volutpat&lt;/a&gt;&lt;a class=&quot;last&quot; href=&quot;#&quot;&gt;Aliquam Auctor&lt;/a&gt;&lt;br /&gt;
&lt;a href=&quot;#&quot;&gt;Diam Tempor&lt;/a&gt;&lt;a href=&quot;#&quot;&gt;Volutpat&lt;/a&gt;&lt;a class=&quot;last&quot; href=&quot;#&quot;&gt;Aliquam Auctor&lt;/a&gt;&lt;/div&gt;';
			$array_description7{$language['language_id']} = '&lt;div class=&quot;banner-hotdeal five columns alpha&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img alt=&quot;hot deals&quot; src=&quot;http://demo.bossthemes.com/gomarket/image/data/gv/banner_hotnew.jpg&quot; title=&quot;hot deals&quot; /&gt;&lt;/a&gt;&lt;/div&gt;';
			$array_description8{$language['language_id']} = '&lt;div id=&quot;powered&quot;&gt;&lt;a href=&quot;http://bossthemes.com/opencart-themes/gomarket.html&quot;&gt;GoMarket Theme &lt;/a&gt;by &lt;a href=&quot;http://bossthemes.com/&quot;&gt;BossThemes&lt;/a&gt;. &copy; 2013 Powered By &lt;a href=&quot;www.opencart.com&quot;&gt;OpenCart&lt;/a&gt;.&lt;/div&gt;';
		}
		$boss_block = array('boss_staticblock_module' => array ( 
			0 => array ( 'description' => $array_description0, 'layout_id' => 0, 'store_id' => array(0=>0), 'position' => 'footer_top', 'status' => 1, 'sort_order' => 1),
			1 => array ( 'description' => $array_description1, 'layout_id' => 0, 'store_id' => array(0=>0), 'position' => 'footer_top', 'status' => 1, 'sort_order' => 2),
			2 => array ( 'description' => $array_description2, 'layout_id' => 0, 'store_id' => array(0=>0), 'position' => 'footer_top', 'status' => 1, 'sort_order' => 3),
			3 => array ( 'description' => $array_description3, 'layout_id' => 0, 'store_id' => array(0=>0), 'position' => 'footer_bottom', 'status' => 1, 'sort_order' => 1),	
			4 => array ( 'description' => $array_description4, 'layout_id' => 0, 'store_id' => array(0=>0), 'position' => 'footer_bottom', 'status' => 1, 'sort_order' => 3),	
			5 => array ( 'description' => $array_description5, 'layout_id' => 0, 'store_id' => array(0=>0), 'position' => 'footer_bottom', 'status' => 1, 'sort_order' => 2),
			6 => array ( 'description' => $array_description6, 'layout_id' => 0, 'store_id' => array(0=>0), 'position' => 'footer_bottom', 'status' => 1, 'sort_order' => 4),	
			7 => array ( 'description' => $array_description7, 'layout_id' => $this->getIdLayout("home"), 'store_id' => array(0=>0), 'position' => 'content_top', 'status' => 1, 'sort_order' => 1),		
			8 => array ( 'description' => $array_description8, 'layout_id' => 0, 'store_id' => array(0=>0), 'position' => 'footer_bottom', 'status' => 1, 'sort_order' => 5),		
		));

		$this->model_setting_setting->editSetting('boss_staticblock', $boss_block);		
	}
}
?>