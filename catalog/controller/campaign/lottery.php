

<?php 
class ControllerCampaignLottery extends Controller { 
	public function index() {
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('campaign/lottery', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
	   
        if($this->customer->getMobile() == "18610601636"){
            $this->session->data["campaign"] = "ON";
        }
		$this->load_language('campaign/lottery');

		$this->document->setTitle($this->language->get('heading_title'));

		//$this->data['tel'] =$this->customer->getTelephone();
		$sql = "SELECT mobile, prize_name, date_used FROM td_campaign WHERE `status`=1  ORDER BY  date_used DESC LIMIT 0,5";
		$query=$this->db->query($sql);
		if ($query->num_rows) {
		    foreach ($query->rows as $row) {
		        $this->data['prizes'][] = array(
    		        'mobile'=> substr_replace($row['mobile'], 'xxxx',3,4),
    		        'prize' => $row['prize_name'],
    		        'time'  => date("H:i",strtotime($row['date_used']))
    		       );
    		    }
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/campaign/lottery.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/campaign/lottery.tpl';
		} else {
			$this->template = 'default/template/campaign/lottery.tpl';
		}
		
		$this->data['template']=$this->config->get('config_template');
		
		$this->children = array(
		        'common/column_left',
		        'common/column_right',
		        'common/content_top',
		        'common/content_bottom',
		        'common/footer',
		        'common/header'
		);
		
		$this->response->setOutput($this->render());
  	}
  	
 
  	
  	public function getrand(){
  	
  	    $rid  = rand(0, 5);
  	    $find = 0;
  	    $row  = '';
  	    
  	    for($i=0; $i<=5; $i++){
  	        $rid++;
  	        if($rid >= 6)
  	        {
  	            $rid -=6;
  	        }
  	        
            $sql = "SELECT prize_type FROM td_lottery WHERE status=1 AND sort=".$rid;
      	    $query=$this->db->query($sql);
      	
      	    $index = $query->row['prize_type'];
      	    $sql   = "SELECT * FROM td_prize WHERE  prize_type=".$index." AND `status`=1 AND quantity>0 LIMIT 0,1";
      	    $query = $this->db->query($sql);
      	    if ($query->num_rows) {
      	        $row = $query->rows[0];
      	    }
      	    else
      	        return '';
          	    
  	        if($row!='')
  	        {
  	            $row['quantity'] -=1;
  	            if($row['quantity']==0){
  	                $row['status'] = 0;
  	            }
  	            $this->session->data['prize'] = $row['prize_id'];
  	            
  	            $sql  = "UPDATE `td_prize` SET `date_used`=NOW()";
  	            $sql .= " ,`status`=".$row['status'];
  	            $sql .= " ,`quantity`=".$row['quantity'];
  	            $sql .= "  WHERE `prize_id`=".$row['prize_id']; 
  	                  
  	            $query=$this->db->query($sql);
  	            $find = 1;
  	            break;
  	        }
  	        
  	    }
  	    
  	    if($find == 0){
  	        return;
  	    }
  	    
  	    $min = $rid* 60;
  	    $max = ($rid+1)*60;
  	    
  	    $result['angle'] = mt_rand($min,$max); //随机生成一个角度
  	    
  	    //$sql = "SELECT prize_name FROM td_prize_type WHERE prize_type_id=".$index;
  	    //$result['prize'] = $query->row['prize_name'];
  	    
  	    echo json_encode($result);
  	}
  	
  	
  	
  	public function check(){
  	    $result = '';
  	    if($this->session->data['campaign'] == 'ON')
  	    {
  	        $result['status'] = 'ON';
  	    }
  	    else {
  	        $result['status'] = 'OFF';
  	    }
  	    $sql = "SELECT COUNT(*) AS total FROM td_campaign WHERE status=1";
  	    $query=$this->db->query($sql);
  	    
  	    $result['text']   = sprintf('已经有%04d人参与抽奖', $query->row['total']+1000);
  	    
  	    echo json_encode($result);
  	}
  	
  	public function getResult(){
  	    $result = '';
        $index = $this->session->data['prize'];
        $sql = "SELECT prize_code, prize_type FROM td_prize WHERE prize_id=".$index;
        $query =$this->db->query($sql);
        $code  = $query->row['prize_code'];
        $prize_type = $query->row['prize_type'];
        
        $sql   = "SELECT prize_name, prize_code_name, prize_image FROM td_prize_type WHERE prize_type_id=".$prize_type;
        $query =$this->db->query($sql);
        $precode = $query->row['prize_code_name'];
        
        $result['path']   = $query->row['prize_image'];
        
        $result['text']   = $precode.$code;
        
        
  	    // clear session
  	    $this->session->data['campaign']='';
  	    
  	    // insert result
  	    $userid = $this->customer->getId();
  	    $mobile = $this->customer->getMobile();
  	    $prizename   = $query->row['prize_name'];
  	    $sql   ="INSERT INTO `td_campaign` (`user_id`, `mobile`, `prize_id`, `prize_name`, `date_added`, `date_used`, `status`)";
  	    $sql  .=" VALUES ($userid,
  	                      $mobile,
  	                      $index,
  	                      '$prizename',
  	                      NOW(),
  	                      NOW(),
  	                      1
  	                      )";
  	    
  	    $this->db->query($sql);
  	    
  	    echo json_encode($result);
  	}
  	
  	
  	private function getCustomerGroupName($customer_group_id){
  		$sql="SELECT  * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id=".(int)$customer_group_id;
  		
  		$query=$this->db->query($sql);
  		
  		if($query->rows){
  			return $query->row['name'];
  		}else{
  			return '';
  		}
  	}
  	
	protected function getTotalOrderCount($order_status_id){
  		$sql="SELECT  COUNT(*) AS total FROM " . DB_PREFIX . "order WHERE customer_id=".(int)$this->customer->getId()." AND order_status_id=".(int)$order_status_id;
  		
  		$query=$this->db->query($sql);
  		
  		return $query->row['total'];
  	}
  	
  	protected function findprize($rid){
  	    $sql = "SELECT prize_type FROM td_lottery WHERE status=1 AND sort=".$rid;
  	    $query=$this->db->query($sql);
  	
  	    $index = $query->row['prize_type'];
  	    $sql   = "SELECT * FROM td_prize WHERE  prize_type=".$index." AND `status`=1 AND quantity>0 LIMIT 0,1";
  	    $query=$this->db->query($sql);
  	    if ($query->num_rows) {
  	        $row = $query->rows[0];
  	        return $row;
  	    }
  	    else
  	        return '';
  	}
   
}
?>