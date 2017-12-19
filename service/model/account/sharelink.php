<?php
class ModelAccountSharelink extends Model {
	public function getTotalCustomerLinks($customer_id) {
		$sql = "SELECT COUNT(link_id) AS total FROM " . DB_PREFIX . "sharelink WHERE customer_id=$customer_id";
	
		$query = $this->db->query($sql);
	
		return $query->row['total'];
	}
	

    //添加优惠劵到指定用户$coupon_id,$customer_id,$point_id=0,$partner_code='',$mark=''
	public function addShareLink($data){
		$sqlstr.="date_add = '".date('Y-m-d H:i:s',time())."'";
		$sqlstr.=",ip = '".$this->db->escape($this->request->server['REMOTE_ADDR'])."'";
		
		foreach($data as $key=>$val)
		{
			$sqlstr.=",$key = '$val'";  
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "sharelink SET ".$sqlstr)
		;
		return $this->db->getLastId();
	}
	
	
	/*
	 * 根据合作者id获取分享的链接数
	 * $partner 合作者id
	 * return datalist
	 * */
	public function getTotalShareLinksByPartner($partner,$other=array())
	{
		
		
	}
	/* 
	 * 根据自提点获取分享的链接数
	 * $partner 合作者id
	 * $code    活动优惠编码
	 * return datalist
	 * */
	public function getTotalShareLinksByPointId($poinr_id,$other=array())
	{
	
	
	}


}
?>