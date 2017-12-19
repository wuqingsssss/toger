<?php

/* IBM 水果生鲜领取页面
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControllerCampaignFresh extends Controller {
	/**
	 * 活动页展示
	 */
	public function index(){
		$this->document->setTitle('领取您的自然蔬食');
		
		//渲染
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/campaign/fresh.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/campaign/fresh.tpl';
        } else {
            $this->template = 'default/template/account/register.tpl';
        }
		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
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
	
	/**
	 * 表单请求接口
	 */
	public function get_input(){
		//post_data
		$return=array();
		$code = trim($this->request->post['code']);
		$input['address'] = htmlspecialchars($this->request->post['address']);
		$input['phone'] = htmlspecialchars($this->request->post['mobile']);
		$input['name'] = htmlspecialchars($this->request->post['name']);
		$input['use_time'] = date('Y-m-d H:i:s', time());
		$input['pick_time'] =htmlspecialchars($this->request->post['pickdate']);
		$input['status'] = -1;
		
		
		
		if(!$return){
		//验证code是否存在 和是否使用
		$this->load->model('campaign/radom_code');
		$data = $this->model_campaign_radom_code->check($code);
	
		if(empty($data)){
			$return['code'] = -1;
			$return['msg'] = '领取码输入有误';
		}else if($data['status'] == -1){
			$return['code'] = -1;
			$return['msg'] = '该领取码已失效';
		}
		elseif(empty($input['phone'])){
			$return['code'] = -2;
			$return['msg'] = '';
		}
		elseif(!$this->request->isPhone($input['phone'])){
			$return['code'] = -3;
			$return['msg'] = '手机号码无效';
		}
		elseif(empty($input['name'])){
			$return['code'] = -3;
			$return['msg'] = '姓名不能为空';
		}
		else{
			
			
			$this->load->service('baidu/geocoder');
			$res=$this->service_baidu_geocoder->hgetLocation($input['address']);
			if($res['status']=='0'){
			
				$point= $res['result']['location']['lng'].' '.$res['result']['location']['lat'];
				$polygon=explode(',','116.55107 39.945658,116.554382 39.852071,116.516803 39.827294,116.486678 39.815895,116.466352 39.797409,116.438718 39.793333,116.429367 39.774504,116.391768 39.763935,116.383208 39.76619,116.37575 39.782455,116.316679 39.787249,116.285459 39.780074,116.267752 39.818336,116.214434 39.877794,116.218834 39.948069,116.228616 39.998034,116.279822 40.017335,116.345937 40.028167,116.436505 40.026279,116.463106 40.021894,116.488112 40.020562,116.517906 39.99267,116.55107 39.945658');
				if(LBS::pointInPolygon ($point, $polygon)==0){
					$return['code'] = -3;
					$return['msg'] = '请确认地址在五环内';
					//$return['data']=$res;
				}else {
					
					$flag = $this->model_campaign_radom_code->update($code, $input);
					if($flag > 0){
						$return['code'] = 1;
					}else{
						$return['code'] = -998;
						$return['msg'] = '领取失败，请认真核对信息';
					}				
				}	
			}
			else
			{
				$return['code'] = -3;
				$return['msg'] = '您的地址好像不准确，请重新填写';
			}
		}
		}
		echo json_encode($return);
	}
}