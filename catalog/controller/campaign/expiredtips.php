<?php

class ControllerCampaignExpiredtips extends Controller {

	public function index() {
		ignore_user_abort(TRUE); //如果客户端断开连接，不会引起脚本abort		
		ini_set("max_execution_time", 0);
		if (!isset($this->request->post['password'])) {
			exit('passsword');
		}
		$password = $this->request->post['password'];
		if (!$password) {
			exit;
		}
		if ($password <> 'jkewjfkwejflwecvnmer32443432488') {
			exit;
		}
		/* */
		$this->load->model('campaign/expiredtips');
		$result = $this->model_campaign_expiredtips->get_openid();
		if (!$result) {
			exit('null');
		}
		$count = count($result);
		foreach ($result as $k => $key) {
			if (empty($key['openid_array']))
				continue;

			$template_id = 'b19VvfOa6FWB-YVO2o9TslF6kI6d_T6zwQx-O8uL1Y0';
			$url = $this->url->link('common/home');
			$date_add_news = strtotime($key['date_add']);
			$date_add = date('Y-m-d', $date_add_news);
			$msg_data = array(
					'first' => array(
							'value' => "您的1张优惠券将要到期",
							'color' => '#FF0000'
					),
					'keynote1' => array(
							'value' => $key['coupon_name'],
							'color' => '#FF0000'
					),
					'keynote2' => array(
							'value' => "适用青年菜君所有门面",
							'color' => '#FF0000'
					),
					'keynote3' => array(
							'value' => $date_add . "至" . $key['date_limit'],
							'color' => '#FF0000'
					),
					'remark' => array(
							'value' => "请尽快使用！",
							'color' => '#FF0000'
					)
			);
//                $commons = new Common($this->registry);
//                $outstr=$commons->send_msg_by_weixin($key['openid_array'], $template_id, $url, $msg_data);//oDJSbt2TJSW2aTTT7hS6DCVQhFxQ 李涛的微信

			$this->load->service('weixin/interface');
			$outstr = $this->service_weixin_interface->send_msg_by_weixin($key['openid_array'], $template_id, $url, $msg_data);
			//sleep(10);
			$this->log_sys->info('CampaignExpiredtips::' . $count . ':' . $k . ':' . $outstr);
			//print_r('CampaignExpiredtips::'.$count.':'.$k.':'.$outstr.'<br/>');
		}
	}

}

?>