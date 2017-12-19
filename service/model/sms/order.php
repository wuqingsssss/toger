<?php
class ModelSMSOrder extends Model {
	public function sendNewOrder($order_id){
		$order_info=$this->getOrder($order_id);
		$language = new Language($order_info['language_directory']);
		$language->load($order_info['language_filename']);
		$language->load('sms/order');

		if(SMS_OPEN=='ON'){
			$this->log_sys->debug('IlexDebug:: Send SMS for order '.$order_id);
			if($this->customer->getMobile()!=''&&SMS_OPEN=='ON'){
				$mobilephone=trim($this->customer->getMobile());
				//手机号码的正则验证
				if(mobile_check($mobilephone)){
					// send sms
					$sms=new Sms();

					$msg=sprintf($language->get('text_new_order_success'), $order_id);
					$msg=$msg;
					$sms->send($mobilephone, $msg);
					$this->log_sys->debug('IlexDebug::Already Sended SMS for order '.$order_id);
					$this->log_sys->debug('IlexDebug::Already Sended SMS '.$mobilephone.',content '.$msg);
					return true;
				}else{
					//手机号码格式不对
					$this->log_sys->debug('IlexDebug:: Wrong Number,dun send sms : sub_order_id '.$order_id);
					return false;
				}
			}
		}else{
			$this->log_sys->debug('IlexDebug:: SMS_OPEN :'.SMS_OPEN.' sub_order_id '.$order_id);
			return false;
		}
	}
	
	private function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . $order_id . "'");
			
		if ($order_query->num_rows) {
			
			$this->load->model('localisation/language');
				
			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);
				
			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}
	
			return array(
					'order_id'                => $order_id,
					'language_id'             => $order_query->row['language_id'],
					'language_code'           => $language_code,
					'language_filename'       => $language_filename,
					'language_directory'      => $language_directory
			);
		} else {
			return false;
		}
	}
	
}