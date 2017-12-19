<?php
class ModelAccountAddress extends Model {

	public function existAddress($data,$customer_id=0) {

		if($customer_id==0){
			$customer_id=(int)$this->customer->getId();
		}
		
		$sql="SELECT customer_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . $customer_id . "'";
		if($data['shipping_code'])$sql.=" AND shipping_code='".$data['shipping_code']."'";
		if($data['shipping_data'])$sql.=" AND shipping_data='".$data['shipping_data']."'";
		if($data['address_1'])    $sql.=" AND address_1='".$data['address_1']."'";
		if($data['address_1_poi'])    $sql.=" AND poi='".$data['address_1_poi']."'";
		if($data['address_2'])    $sql.=" AND address_2='".$data['address_2']."'";
		if($data['mobile'])       $sql.=" AND mobile='".$data['mobile']."'";
		if($data['firstname'])    $sql.=" AND firstname='".$data['firstname']."'";
		
		$query = $this->db->query($sql);

		if ($query->num_rows > 0) {
			
			return $query->row['customer_id'];
		}
		else 
		{
			return false;
		}
		
	}
	
	
	
	
	public function addAddress($data,$customer_id=0) {
		if($customer_id==0){
			$customer_id=(int)$this->customer->getId();
		}
		
		$sql = "INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . $customer_id 
		. "',  firstname = '" . str_replace(',',' ',$this->db->escape($data['firstname']))
		. "',  lastname = '" . str_replace(',',' ',$this->db->escape($data['lastname'])) 
		. "',address_1 = '" . $this->db->escape($this->db->clean_nonchar($data['address_1']))
		. "',poi = '" . str_replace(',',' ',$this->db->escape($data['address_1_poi']))
		. "', address_2 = '" . $this->db->escape($this->db->clean_nonchar($data['address_2'])) 
		. "', postcode = '" . $this->db->escape($data['postcode']) 
		. "', city = '" .$this->db->escape( $data['city'])
		. "', city_id = '" . (int)$data['city_id'] 
		. "', zone_id = '" . (int)$data['zone_id'] 
		. "'," ."mobile = '" .  $this->db->match_phone($this->db->escape($data['mobile'])) 
		. "', phone = '" .  $this->db->escape($data['phone'])
		. "', shipping_code = '" .  $this->db->escape($data['shipping_code'])
		. "', shipping_data = '" .  $this->db->escape($data['shipping_data'])
		//. "', shipping_data_info = '" .  $this->db->escape($data['shipping_data_info'])
		. "', country_id = '" . (int)$this->config->get('config_country_id') . "'";

		$this->db->query($sql);
		
		$address_id = $this->db->getLastId();
		
		$addresses=$this->getAddresses('meishisong');
		// 如果账户下没有地址就默认的添加新的为默认地址
		if($addresses){
			if (isset($data['default']) && $data['default'] == '1') {
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			}
		}else{
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}
		
		return $address_id;
	}
	
	public function editAddress($address_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "address SET  firstname = '" . $this->db->escape($data['firstname']) 
		. "',lastname = '" . $this->db->escape($data['lastname']) 
		. "',  address_1 = '" . $this->db->escape($data['address_1']) 
		. "',poi = '" . str_replace(',',' ',$this->db->escape($data['address_1_poi']))
		. "', address_2 = '" . $this->db->escape($data['address_2']) 
		. "', postcode = '" . $this->db->escape($data['postcode']) 
		. "', city_id = '" . (int)$data['city_id'] . "', zone_id = '" . (int)$data['zone_id'] 
		. "', country_id = '" . (int)$this->config->get('config_country_id') 
				. "' ,mobile = '" .  $this->db->escape($data['mobile'])
				 . "', phone = '" .  $this->db->escape($data['phone']) 
				. "', shipping_code = '" .  $this->db->escape($data['shipping_code'])
				. "', shipping_data = '" .  $this->db->escape($data['shipping_data'])
				. "'  WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	
		if (isset($data['default']) && $data['default'] == '1') {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}
	}
	
	public function deleteAddress($address_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	}	
	
	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
		$query_pd=false;
		if($address_query->row['shipping_code']&&$address_query->row['shipping_data']&&!empty(trim($address_query->row['poi']))){
			$this->load->model('catalog/pointdelivery');
			$query_pd=$this->model_catalog_pointdelivery->getDeliveryByName($address_query->row['shipping_code'],$address_query->row['shipping_data']);
		}
		
		if ($query_pd && $address_query->num_rows && $address_query->row['mobile']) {
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");
			
			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = $address_query->row['country'];
				$iso_code_2 = '';
				$iso_code_3 = '';	
				$address_format = '';
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$code = $zone_query->row['code'];
			} else {
				$zone = $address_query->row['zone'];
				$code = '';
			}		
			
			$city_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "city` WHERE city_id = '" . (int)$address_query->row['city_id'] . "'");
				
			if ($city_query->num_rows) {
				$city = $city_query->row['name'];
				$city_code = $city_query->row['code'];
			} else {
				$city = $address_query->row['city'];
				$city_code = '';
			}
			if($address_query->row['poi']){
				$locations=explode(' ', $address_query->row['poi']);
				if($locations&&$locations[0]&&$locations[1]){
				$location['lng']=$locations[0];
				$location['lat']=$locations[1];
				}
			}
			$address_data = array(
				'address_id'      => $address_query->row['address_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'poi'      => $address_query->row['poi'],
				'location'            => $location,
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'shipping_code'       => $address_query->row['shipping_code'],
				'shipping_data'       => $address_query->row['shipping_data'],
				'shipping_data_info'  => $address_query->row['shipping_data_info'],
				'city_id'        => $address_query->row['city_id'],
				'city'           => $city,
				'city_code'      => $city_code,
				'mobile'         => $address_query->row['mobile'],
				'phone'          => $address_query->row['phone'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);
			
			return $address_data;
		} else {
			return false;	
		}
	}
	
	public function getAddresses($shippingcode='',$shippingdata='') {
		$address_data = array();
		
		$sql="SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "' AND shipping_code!=''";

		if($shippingcode)$sql.=" AND shipping_code='".$shippingcode."'";
		if($shippingdata)$sql.=" AND shipping_data='".$shippingdata."'";

		
		$this->load->model('catalog/pointdelivery');
		
		/* 此处可增加memche缓存*/
		$query = $this->db->query($sql);
	
		foreach ($query->rows as $result) {
			
			$country = '';
			$iso_code_2 = '';
			$iso_code_3 = '';
			$address_format = '';
			
			if($result['country_id']){
				
    			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");
    		
    			if ($country_query->num_rows) {
    				$country = $country_query->row['name'];
    				$iso_code_2 = $country_query->row['iso_code_2'];
    				$iso_code_3 = $country_query->row['iso_code_3'];
    				$address_format = $country_query->row['address_format'];
    			} 
			}
			
			$zone = '';
			$code = '';
			if($result['zone_id']){
    			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");
    			
    			if ($zone_query->num_rows) {
    				$zone = $zone_query->row['name'];
    				$code = $zone_query->row['code'];
    			} 		
			}
			
			$city = '';
			$city_code = '';
			if($result['city_id']){
    			$city_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "city` WHERE city_id = '" . (int)$result['city_id'] . "'");			
    			if ($city_query->num_rows) {
    				$city = $city_query->row['name'];
    				$city_code = $city_query->row['code'];
    			} 
			}
			$location=array();
			if(!empty(trim($result['poi']))){
				   $locations=explode(' ', $result['poi']);
			  if($locations&&$locations[0]&&$locations[1]){
				      $location['lng']=$locations[0];
				      $location['lat']=$locations[1];

			/*检查地址当前区域是否可用*/
			if($result['shipping_code']&&$result['shipping_data'] && $result['mobile']){
			$query_pd=$this->model_catalog_pointdelivery->getDeliveryByName($result['shipping_code'],$result['shipping_data']);
			
			if(!$query_pd){
			   $status=2;//暂时无效
			}
			else{
				$status=1;
			}
			}
			else 
			{
				$status=0;//无效地址
			}
			}
			else {
				
				$status=0;//无效地址
			}
			}
			else 
			{
				$status=0;//无效地址
			}
			$address_data[] = array(	    
				'address_id'     => $result['address_id'],
				'firstname'      => $result['firstname'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
				'address_1'      => $result['address_1'],
				'poi'            => $result['poi'],
				'location'            => $location,
				'address_2'           => $result['address_2'],
				'postcode'            => $result['postcode'],
				'shipping_code'       => $result['shipping_code'],
				'shipping_data'       => $result['shipping_data'],
				'shipping_data_info'  => $result['shipping_data_info'],
				'city_id'        => $result['city_id'],
				'city'           => $city,
				'city_code'      => $city_code,
				'mobile'         => $result['mobile'],
				'phone'          => $result['phone'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $code,
				'country_id'     => $result['country_id'],
				'status'     => $status,
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);
		}		
		
		return $address_data;
	}	
	
	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	
		return $query->row['total'];
	}
	
	/**
	 * editDefaultAddress($address_id)		update customer default address id
	 * @param $address_id int
	 * */
	public function editDefaultAddress($address_id){
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}
}
?>