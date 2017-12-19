<?php
class ServiceWeixinInterface extends Service {
	private $appid = '';
	private $secret = '';
	private $c_appid = '';
	private $c_secret = '';
	private $uri = '';
	private $c_uri = '';
	private $token = '';
	private $ticket='';
	private $web_token = '';
	private $runc;
	public function __construct($registry) {//1中控模式  
		header("Access-Control-Allow-Origin: *");//跨域问题
		parent::__construct ( $registry, dirname ( __FILE__ ) );
		$this->appid = WX_APPID;
		$this->secret = WX_APPSECRET;
		$this->c_appid = WX_C_APPID;
		$this->c_secret = WX_C_APPSECRET;
		$this->uri = WX_URL;
		$this->c_uri = WX_C_URL;	
		$this->set_runc(WX_RUN_C);
	
		
		// 获取token
		//$this->set_weixin_access_token ( $this->appid, $this->secret );	
	}
	public function __get($key) {
		return $this->registry->get($key);
	}
	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
	
	public function set_runc($var){
	
		$this->runc=$var;
	}
	public function get_runc($var){
	
		return $this->runc;
	}
	/*
	 * 获取操作链接token
	 */	
	public function set_weixin_access_token($appid, $appsecret, $reset = false) {
		
		$tokeninfo=$this->get_mem_access_token($appid);
		
		if (!$tokeninfo || $reset) {
			 if ($this->get_runc())
			 {
			 	$tokeninfo = $this->cget_weixin_access_token ( $appid, $appsecret ,$reset);
			 }
			 else 
			 {
			    $tokeninfo = $this->hget_weixin_access_token ( $appid, $appsecret );
			 }
			 
			 $this->set_mem_access_token($appid, $tokeninfo);

		}
		
		if ($tokeninfo) {
			$this->token = $tokeninfo;
		} else {
			$this->token = false;
		}	
	} 
	
	private function get_mem_access_token($appid)
	{
		if (is_object ( $this->mem )) {
			$token = $this->mem->get ( 'weixin_token_6' . $appid ,$this->get_runc());
			$this->log_sys->info ( 'memcache:get_weixin_token::'.serialize($token));
		} else {
			$token = $this->cache->get ( 'weixin_token_' . $appid );
			$this->log_sys->info ( 'cache:get_weixin_token::'.serialize($token) );
		}
		if(isset($token['expires'])&&$token['expires']<time())
	      	$token=false;
		return $token;
		
	}
	
	private function set_mem_access_token($appid,$token)
	{
		if (is_object ( $this->mem )) {
			$this->mem->set ( 'weixin_token_6' . $appid, $token, 0, (int)$token['expires_in'] ,$this->get_runc());
			$this->mem->set ( 'weixin_token_5' . $appid, $token, 0, (int)$token['expires_in'] ,$this->get_runc());
			//$this->mem->set ( 'weixin_access_token_' . $appid, $token['access_token'], 0, (int)$token['expires_in'] ,$this->get_runc());
			$this->log_sys->info ( 'memcache:set_weixin_token::'.serialize($token) );
		} else {
			$this->cache->set ( 'weixin_token_' . $appid, $token,$token['expires'] );
			$this->log_sys->info ( 'cache:set_weixin_token::'.serialize($token) );
		}

	}
	
	public function get_weixin_access_token() {
		
		if(!$this->token||isset($this->token['expires'])&&$this->token['expires']<time()){
			$this->set_weixin_access_token($this->appid, $this->secret);		
		}	

		return $this->token;
	}
	/** 获取后台服务器授权(中控)
	 *@param  string $appid
	 *@param  string $appsecret
	 *             是否重置
	 *@return string $ticket
	 */
	public function cget_weixin_access_token($appid, $appsecret,$reset) {
		$url = $this->c_uri . "/token/getAccessToken";
		
		$data['appid']=$this->c_appid;		
		$data['wx_appid']=$this->appid;
		$data['sign_method']=1;
		$data['reset']=$reset;
		$sign=HTTP::make_sign($data, $this->c_secret);
		$data['sign']=$sign;
		
		$resstr=HTTP::getSSCPOST($url,$data, 1);
		$res=json_decode($resstr,1);

		$this->log_sys->info ( 'cget_weixin_access_token::'.$resstr );
	
		if ($res ['errmsg']) {
			return false;
		} else {

			return $res;
		}
	}
	/** 获取后台服务器授权（微信远端）
	 *@param  string $appid 
	 *@param  string $appsecret 
	 *             是否重置
	 *@return string $ticket
	 */
	public function hget_weixin_access_token($appid, $appsecret) {
		$url =  $this->uri  . "/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret . "";
		$res = $this->hget_json_data ( $url );
		
		$this->log_sys->info ( 'hget_weixin_access_token::'.serialize( $res) );
		
		if ($res ['errmsg']) {
			return false;
		} else {
			$res['expires']=time()+$res['expires_in'];
		
			return $res;
		}
	}
	/** 获取jdk使用ticket
	 *@param  bool $reset 
	 *             是否重置
	 *@return string $ticket
	 */
	public function get_jsapi_ticket($reset=false) {
		
		if (is_object ( $this->mem )) {
			$ticket=$this->mem->get ( 'weixin_jsapi_tickets7_' . $this->appid,$this->get_runc() );
			$this->log_sys->info ( 'memcache:get_jsapi_ticket::'.serialize($ticket) );
		} else {
			$ticket = $this->cache->get ( 'weixin_jsapi_ticket_' . $this->appid );
			$this->log_sys->info ( 'cache:get_weixin_jsapi_ticket::'.serialize($ticket) );
		}
		
		if(isset($ticket['expires'])&&$ticket['expires']<time())$ticket=false;
			
		if (!$ticket || $reset) {
			
			$token=$this->get_weixin_access_token();
			if($token){
			if($this->get_runc())
			{	
				$ticket = $this->cget_jsapi_ticket ( $token['access_token'] ,$reset);
				//中控模式 获取ticket时有回传的token则对本地token进行强制更新
				if($ticket['token']['access_token']&&$ticket['access_token']!=$token['access_token'])
					$this->set_mem_access_token($appid,$ticket['token'],$expires_in=3600);
			}
			else 
			{
				$ticket = $this->hget_jsapi_ticket ( $token['access_token'] );
			}
			
			if ($ticket) {
				$this->ticket = $ticket;										
			} else {
				$this->ticket = false;
			}
			
			if (is_object ( $this->mem )) {
				$this->mem->set ( 'weixin_jsapi_tickets7_' . $this->appid, $ticket, 0,(int)$ticket['expires_in'] ,$this->get_runc());
				$this->mem->set ( 'weixin_jsapi_tickets5_' . $this->appid,$ticket['ticket'] , 0,(int)$ticket['expires_in'] ,$this->get_runc());
				$this->log_sys->info ( 'memcache:hget_jsapi_ticket::'.serialize($ticket) );
			} else {
				$this->cache->set ( 'weixin_jsapi_ticket_' . $this->appid, $ticket, $ticket['expires'] );
				$this->log_sys->info ( 'cache:hget_jsapi_ticket::'.serialize($ticket) );
			}
			}
			else 
			{
				$this->ticket = false;
			}
				
		}
		else 
		{
			$this->ticket = $ticket ;
		}
		return $this->ticket;
	}
	
	
	/** 根据token 从中控  获取网页授权ticket
	 *@param  string $token
	 *@param  bool $reset
	 *             是否重置
	 *@return string $ticket
	 */
	public function cget_jsapi_ticket($token = '',$try=true) {
		if (empty ( $token )) {
			$token = $this->token;
		}
		$url = $this->c_uri . "/token/getJsapiTicket";
		$data['appid']=$this->c_appid;
		$data['access_token']=$token['access_token'];
		$data['type']='jsapi';
		$data['sign_method']=1;
		$data['reset']=$reset;
		$sign=HTTP::make_sign($data, $this->c_secret);
		$data['sign']=$sign;
		
		$resstr=HTTP::getSSCPOST($url,$data, 1);
		$res=json_decode($resstr,1);

		$this->log_sys->info ('url::'.$url. '::cget_jsapi_ticket::'.serialize( $res ) );
	
		if ($res ['errcode']) {

			return false;
		} else {
			return $res;
		}
	}
	
	
	/**根据token 从微信服务器 获取网页授权ticket
	 *@param  string $token 
	 *@param  bool $reset 
	 *             是否重置
	 *@return string $ticket
	 */
	public function hget_jsapi_ticket($access_token = '',$try=true) {
		if (empty ( $access_token )) {
			
			$token=$this->get_weixin_access_token();
			if($token)
			$access_token = $token['access_token'];
			else 
			return false;
		}
		$url = $this->uri."/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
		$res = $this->hget_json_data ( $url );
	
		$this->log_sys->info ('url::'.$url. '::hget_jsapi_ticket::'.serialize( $res ) );
	
		if ($res['errcode']=='40001') {
			$this->set_weixin_access_token ( $this->appid, $this->secret,1 );
			$this->log_sys->info ( 'errcode::url::'.$url. 'hget_jsapi_ticket::'.serialize( $res ));
			if($try){
				return $this->hget_jsapi_ticket($this->token['access_token'],false);
			}
				
			return false;
		} else {
			//如果不是第一次获取到的 则返回时增加新的token选项
			$res['token']=$this->token;
			$res['expires']=time()+$res['expires_in'];
			return $res;
		}
	}
	
	
	
	/*
	 * 获取开放平台网页登录授权链接
	 *
	 */
	public function wget_weixin_access_link($appid, $redirect_uri, $state = 'state', $type = 1, $response_type = 'code') {
		if ($type == 1) { // 'snsapi_base'
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=$response_type&scope=snsapi_base&state=$state#wechat_redirect";
		} elseif ($type == 2) { // snsapi_userinfo
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=$response_type&scope=snsapi_userinfo&state=$state#wechat_redirect";
		} else {
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=$response_type&scope=snsapi_base&state=$state#wechat_redirect";
		}
		return $url;
	}	
	/*
	 * 获取开放平台网页登录授权token
	 *
	 */
	public function wget_weixin_access_token($appid, $appsecret, $code) {
		$url = $this->uri . "/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=$code&grant_type=authorization_code";
		$res = $this->hget_json_data ( $url );
		
		if ($res ['errmsg']) {
			return false;
		} else {
			
			$this->web_token=$res;
			return $res;
		}
	}
	/*
	 * 远程请求提交方法GET
	 */
	public function hget_json_data($url, $type = 1) {
		$this->load->library ( 'json' );
		
		$res_str = file_get_contents ( $url );
	
			$res = Json::decode ( $res_str, $type );		
	
		return $res;	
	}
	/**远程请求提交方法POST
	 * @param string $url
	 *        	远程主机地址
	 * @param array $post
	 *        	post提交的数据
	 * @param  int $type
	 *        	返回数据类型 1数组 0为对象
	 * @return res
	 */
	public function hpost_json_data($url, $post = null, $type = 1) {
		$this->load->library ( 'json' );
		if (is_array ( $post )) {
			ksort ( $post );
			$content = http_build_query ( $post );
			$content_length = strlen ( $content );
		} else {
			$content = $post;
			$content_length = strlen ( $post );
		}
		;
	
		$options = array (
				'http' => array (
						'method' => 'POST',
						'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n",
						'content' => $content
				)
		);
		$res_str = file_get_contents ( $url, false, stream_context_create ( $options ) );
	
		$res = Json::decode ( $res_str, $type );
		return $res;
	}
	/**
	 * 发送微信模板消息
	 * 
	 * @param unknown $openid
	 *        	用户OPENID
	 * @param unknown $template_id
	 *        	模板ID
	 * @param unknown $newurl
	 *        	链接
	 * @param unknown $msg_data
	 *        	消息
	 * @return mixed
	 */
	public function send_msg_by_weixin($openid, $template_id, $newurl, $msg_data) {
		
		$token=$this->get_weixin_access_token();
		if($token)
			$access_token = $token['access_token'];
		else
			return false;
		
		
		$this->log_sys->info ( 'IlexDebug:: send_msg_by_weixin' );
		$template_array = array (
				'touser' => $openid,
				'template_id' => $template_id,
				'url' => $newurl,
				'topcolor' => '#FF0000',
				'data' => $msg_data 
		);
		
		
		
		$template_array = json_encode ( $template_array );
		$msgurl = $this->uri . "/cgi-bin/message/template/send?access_token={$access_token}";
		$output = HTTP::getPOST ( $msgurl, $template_array );
		$res = json_decode ( $output,1 );
		
		$this->log_sys->info ( 'send_msg_by_weixin ::' . print_r ( json_decode ( $template_array ), 1 ) );
		$this->log_sys->info ( 'send_msg_by_weixin ::output' . print_r ( $output, 1 ).'::'.$access_token );
		
		if ($res['errcode']=='40001') {
			$this->set_weixin_access_token ( $this->appid, $this->secret, true );
			$msgurl = $this->msg_uri . "?access_token={$access_token}";
			$output = HTTP::getPOST ( $msgurl, $template_array );
			$this->log_sys->info ( 'send_msg_by_weixin2::output' . print_r ( $output, 1 ) .'::'.$access_token);
		}	
		return $output;
	}
	/*
	 * 根据用户openid和token获取用户基本信息，web端
	 *
	 */
	public function wget_weixin_userinfo($openid, $access_token = '') {
		if (empty ( $access_token )) {
			$token=$this->get_weixin_access_token();
			if($token)
				$access_token = $token['access_token'];
			else
				return false;
			
			
			$access_token = $this->web_token['access_token'];
			
			
		}
		$url = $this->uri . "/sns/userinfo?access_token=" . $access_token . "&openid=$openid&lang=zh_CN"; // omLQFj3gs0A76K_vx79oKRTLzPM8
		$res = $this->hget_json_data ( $url );
		if ($res ['errcode']) {
			return false;
		} else {
			return $res;
		}
	}
	/*
	 * 根据用户openid和token获取用户基本信息，服务器端
	 *
	 */
	public function hget_weixin_userinfo($openid, $access_token = '',$try=true) {
		if (empty ( $access_token )) {
			$access_token = $this->token['access_token'];
		}
		$url = $this->uri . "/cgi-bin/user/info?access_token=" . $access_token . "&openid=$openid&lang=zh_CN"; // omLQFj3gs0A76K_vx79oKRTLzPM8
		$res = $this->hget_json_data ( $url );
		if ($res['errcode']=='40001') {
			
			$this->set_weixin_access_token ( $this->appid, $this->secret,1 );
			$this->log_sys->info ( 'errcode::url::'.$url. 'hget_weixin_userinfo::'.serialize( $res ));
			if($try){
				return $this->hget_weixin_userinfo($openid,$this->token['access_token'],false);
			}
			
			return false;
		} else {
			return $res;
		}
	}

	
	/*
	 * 远程获取客服列表
	 * @param string $token
	 * @param integer $online 0全部 1在线
	 *
	 * @return $arr
	 */
	public function hget_weixin_kefu_list($access_token = '', $online = 0) {
		if (empty ( $access_token )) {
			
			
			$token=$this->get_weixin_access_token();
			if($token)
				$access_token = $token['access_token'];
			else
				return false;
		}
		if ($online) {
			$url = $this->uri . "/cgi-bin/customservice/getonlinekflist?access_token=" . $access_token;
		} else {
			$url = $this->uri . "/cgi-bin/customservice/getkflist?access_token=" . $access_token;
		}
		$res = $this->hget_json_data ( $url );
		if ($res ['errcode']) {
			return false;
		} else {
			foreach ( $res ['kf_online_list'] as $key => $user )
				$arr [$user ['kf_account']] = $user;
			
			return $arr;
		}
	}
	
	/*
	 * 判断微信code识别码并绑定商城账号
	 * $hash 身份验证识别码
	 * $w 微信类型对象
	 * 返回news数组
	 */
	public function validate_weixin_code($hash, $w) {
		if ($hash) {
			$sql = "SELECT user_id FROM " . $GLOBALS ['ecs']->table ( 'weixin_users' ) . " WHERE weixin_username = '" . $w->fromusername . "'";
			$count = $GLOBALS ['db']->getRow ( $sql );
			if (! $count) {
				include_once (ROOT_PATH . 'includes/lib_passport.php');
				$id = register_hash ( 'decode', $hash );
				if ($id > 0) {
					$sql = "UPDATE " . $GLOBALS ['ecs']->table ( 'weixin_users' ) . " SET user_id = '$id' WHERE weixin_username='" . $w->fromusername . "'";
					$GLOBALS ['db']->query ( $sql );
					
					return true;
				}
			}
		}
		return false;
	}
	
	/* 通过hash 解除绑定接口 */
	public function release_weixin_code($hash, $w) {
		if ($hash) {
			include_once (ROOT_PATH . 'includes/lib_passport.php');
			$id = register_hash ( 'decode', $hash );
			if ($id > 0) {
				/* 数据库操作 */
				
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 根据两点间的经纬度计算距离
	 * 
	 * @param float $lat
	 *        	纬度值
	 * @param float $lng
	 *        	经度值
	 */
	public function getDistance($lat1, $lng1, $lat2, $lng2) {
		$earthRadius = 6367000; // approximate radius of earth in meters
		
		/*
		 * Convert these degrees to radians
		 * to work with the formula
		 */
		
		$lat1 = ($lat1 * pi ()) / 180;
		$lng1 = ($lng1 * pi ()) / 180;
		
		$lat2 = ($lat2 * pi ()) / 180;
		$lng2 = ($lng2 * pi ()) / 180;
		
		/*
		 * Using the
		 * Haversine formula
		 *
		 * http://en.wikipedia.org/wiki/Haversine_formula
		 *
		 * calculate the distance
		 */
		
		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;
		$stepOne = pow ( sin ( $calcLatitude / 2 ), 2 ) + cos ( $lat1 ) * cos ( $lat2 ) * pow ( sin ( $calcLongitude / 2 ), 2 );
		$stepTwo = 2 * asin ( min ( 1, sqrt ( $stepOne ) ) );
		$calculatedDistance = $earthRadius * $stepTwo;
		
		return round ( $calculatedDistance );
	}
	public function checkSignature() {
		$signature = $_GET ["signature"];
		$timestamp = $_GET ["timestamp"];
		$nonce = $_GET ["nonce"];
		
		$token = TOKEN;
		$tmpArr = array (
				$token,
				$timestamp,
				$nonce 
		);
		sort ( $tmpArr );
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
		
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
}
