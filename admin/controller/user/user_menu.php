<?php

class ControllerUserUserMenu extends Controller {

	public function index() {
		//不显示的文件夹
		$dir_arr = array('.', '..', 'layput', 'error', 'cms','.svn','module'); 
		$dir = 'controller';

		$default_action='access:modify';

		$menu_list = $this->find_all_files($dir, $dir_arr);
		//排除已经分配的控制器
		$left_menu = $this->config->get('menu');
//		var_dump($left_menu);exit;
		$done_menu = $this->get_keys($left_menu);
		foreach ($menu_list as $k => $m) {
			if (in_array($m, $done_menu)) {
				unset($menu_list[$k]);
			}
		}
		$this->data['default_action'] = $default_action;
		$this->data['left_menu'] = $left_menu;
		$this->data['module_row'] = count($left_menu);
		$this->data['menu_list'] = $menu_list;
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$menu_data = $this->request->post;
			$menu_data = $this->format_data($menu_data);
			//存入setting表
			$this->load->model('setting/setting');

			$post = array('menu' => $menu_data);
			$this->model_setting_setting->updateSetting('permission', $post, $store_id = 0);
			$this->redirect($this->url->link('user/user_menu', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->document->setTitle('菜单配置');
		$this->data['heading_title'] = '菜单管理';
		$this->data['breadcrumbs'][] = array(
				'text' => '首页',
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
				'text' => '菜单配置页面',
				'href' => $this->url->link('sale/group', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);

		$this->data['url'] = $this->url->link('sale/group/add_edit', 'token=' . $this->session->data['token'], 'SSL');
		//渲染模板
		$this->id = 'content';
		$this->template = 'user/user_menu.tpl';
		$this->layout = 'layout/default';
		$this->render();
	}

	/**
	 * 获取控制器列表
	 * @param type $dir
	 * @param type $dir_arr
	 * @return type
	 */
	private function find_all_files($dir, $dir_arr) {

		$root = scandir($dir);
		foreach ($root as $value) {
			if (in_array($value, $dir_arr)) {
				continue;
			}
			if (is_file("$dir/$value")) {
				$result[] = "$dir/$value";
				continue;
			}
			foreach ($this->find_all_files("$dir/$value", $dir_arr)as $value) {
				$result[] = $value;
			}
		}
		foreach ($result as &$r) {
			$r = preg_replace('/controller\/|.php/', '', $r);
		}
		return $result;
	}

	private function get_keys($arr) {
		if (empty($arr)) {
			return '';
		}

		foreach ($arr as $a) {
			foreach ($a as $k => $m) {
				$keys[] = $k;
			}
		}
		return $keys;
	}

	private function format_data($arr){
		foreach($arr['title'] as $k => $v){
			foreach($v as $route => $name){
				$data[$k][$route]['title'] = $name;
				$data[$k][$route]['action'] = $arr['action'][$k][$route];
				if(isset($arr['menu'][$k][$route]) && $arr['menu'][$k][$route] == 1){
					$data[$k][$route]['show_menu'] = 1;
				}else{
					$data[$k][$route]['show_menu'] = 0;
				}
			}
		}
		
		foreach($arr['tag_name'] as $key => $tag){
			$return[$tag] = $data[$key];
		}
		return $return;
	}
}
