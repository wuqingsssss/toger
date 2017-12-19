<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControllerSaleCodeCreate extends Controller {

	public function index() {
		$num = intval($this->request->get['num']);
		if($num > 1000 || $num < 1){
			echo '本次生成最多1000，请输入正确数量,比如请在URL 后面添加 \'&num=500\'';
			exit;
		}
		$codes = Maths::create_code(array(),10, '', $num);
		foreach($codes as $c){
			$datas[]['code'] = $c;
		}
		$this->load->model('sale/radom_code');
		$flag = $this->model_sale_radom_code->add_code($datas);
		if($flag > 0){
			echo '生成码完毕，请到库里查看';
		}else{
			echo '生成失败,原因不明';
		}
	}
	
	public function qr_code(){
		require_once(DIR_SYSTEM . 'library/phpqrcode.php');
		QRcode::png('http://www.qingniancaijun.com.cn/index.php?route=campaign/fresh&code=ibm',false,M,6);
	}
}
