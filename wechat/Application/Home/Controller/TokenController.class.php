<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Controller;

class TokenController extends HomeController {
	/**
	 * 800live客服   获取access_token 接口地址
	 */
	function get_access_token(){
		$flag = I('get.flag');
		if(empty($flag)){
			$data['errcode'] = -1;
			$data['errmsg'] = 参数错误;
			echo json_encode($data);
		}
		//菜君市集公众号
		if($flag == 'market'){
			$token = C('MARKET_TOKEN');
			$appid = C('MARKET_APPID');
			$secret = C('MARKET_SECRET');
		}
		//菜君公众号
		if($flag == 'fooder'){
			$token = C('FOODER_TOKEN');
			$appid = C('FOODER_APPID');
			$secret = C('FOODER_SECRET');
		}
		$nonce = I('get.nonce');
		$timestamp = I('get.timestamp');
		$signature = I('get.signature');
		$new_signature = strtoupper(md5(urlencode($nonce.$timestamp.$token)));
//		echo $new_signature;
		$key = $flag.'_astoken';
		$access_token = S($key);
		if(empty($access_token)){//缓存失效 重新获取
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
			$url .= '&appid='.$appid.'&secret='.$secret;
			$res = json_decode(wp_file_get_contents($url));
			S($key, $res->access_token, 7200);
			$access_token = $res->access_token;
		}
		if($new_signature == $signature){
			$data['access_token'] = $access_token;
			$data['expires_in'] = 7200;
			echo json_encode($data);
		}else{
			$data['errcode'] = -1;
			$data['errmsg'] = 验证失败;
			echo json_encode($data);
		}
	}
}