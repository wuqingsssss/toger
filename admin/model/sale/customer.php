<?php
class ModelSaleCustomer extends Model {
	public function addCustomer($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) 
      	. "',lastname = '" . $this->db->escape($data['lastname']) 
      	. "', email = '" . $this->db->escape($data['email']) 
      	. "', telephone = '" . $this->db->escape($data['telephone']) 
      	. "', mobile = '" . $this->db->escape($data['mobile']) 
      	. "', fax = '" . $this->db->escape($data['fax']) 
      	. "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$data['customer_group_id'] 
      	. "', password = '" . $this->db->escape(md5($data['password'])) 
      	. "', status = '" . (int)$data['status'] 
      	. "', date_added = NOW()");
      	
      	$customer_id = $this->db->getLastId();
      	
      	if (isset($data['address'])) {		
      		foreach ($data['address'] as $address) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "address SET  mobile = '" .  $this->db->escape($address['mobile']) . "', phone = '" .  $this->db->escape($address['phone']) . "', customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "',lastname = '" . $this->db->escape($address['lastname']) . "',  address_1 = '" . $this->db->escape($this->db->clean_nonchar($address['address_1'])) . "', address_2 = '" . $this->db->escape($this->db->clean_nonchar($address['address_2'])) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$this->config->get('config_country_id') . "', zone_id = '" . (int)$address['zone_id'] . "'"
      					. ",poi = '" . str_replace(',',' ',$this->db->escape($address['address_1_poi']))			
				. "', shipping_code = '" .  $this->db->escape($address['shipping_code'])
				. "', shipping_data = '" .  $this->db->escape($address['shipping_data'])."'"
      					);
				if (isset($address['default'])) {
					$address_id = $this->db->getLastId();
					
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . $address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}
		
		return $customer_id;
	}
	
	public function editCustomer($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) 
		. "',lastname = '" . $this->db->escape($data['lastname']) 
		. "', email = '" . $this->db->escape($data['email']) 
		. "', telephone = '" . $this->db->escape($data['telephone']) 
		. "', mobile = '" . $this->db->escape($data['mobile']) 
		. "', fax = '" . $this->db->escape($data['fax']) 
		. "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', status = '" . (int)$data['status'] . "' WHERE customer_id = '" . (int)$customer_id . "'");
	
      	if ($data['password']) {
        	$this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE customer_id = '" . (int)$customer_id . "'");
      	}
      	
      //	$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
      	
      	$sql="";
      	if (isset($data['address'])) {
      		
      		$oldid=array();
      		
      		foreach ($data['address'] as $address) {
      			$address['country_id']=$this->config->get('config_country_id');
				if ($address['address_id']) {
					$oldid[]=$address['address_id'];
					$sql.="UPDATE " . DB_PREFIX . "address SET  mobile = '" .  $this->db->escape($address['mobile']) . "', phone = '" .  $this->db->escape($address['phone']) . "', customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "',lastname = '" . $this->db->escape($address['lastname']) . "',  address_1 = '" . $this->db->escape($this->db->clean_nonchar($address['address_1'])) . "', address_2 = '" . $this->db->escape($this->db->clean_nonchar($address['address_2'])) . "', city_id = '" . (int)$address['city_id'] . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'"
                  . ",poi = '" . str_replace(',',' ',$this->db->escape($address['address_1_poi']))			
				. "', shipping_code = '" .  $this->db->escape($address['shipping_code'])
				. "', shipping_data = '" .  $this->db->escape($address['shipping_data'])
							."' WHERE address_id = '" . $this->db->escape($address['address_id']) . "';";
					/*
					if (isset($address['default'])) {
						$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address['address_id'] . "' WHERE customer_id = '" . (int)$customer_id . "'");
					}*/
				} else {
					$sql.="INSERT INTO " . DB_PREFIX . "address SET  mobile = '" .  $this->db->escape($address['mobile']) . "', phone = '" .  $this->db->escape($address['phone']) . "',customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "',lastname = '" . $this->db->escape($address['lastname']) . "', address_1 = '" . $this->db->escape($this->db->clean_nonchar($address['address_1'])) . "', address_2 = '" . $this->db->escape($this->db->clean_nonchar($address['address_2'])) . "', city_id = '" . (int)$address['city_id'] . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'"
							. ",poi = '" . str_replace(',',' ',$this->db->escape($address['address_1_poi']))			
				. "', shipping_code = '" .  $this->db->escape($address['shipping_code'])
				. "', shipping_data = '" .  $this->db->escape($address['shipping_data'])."';";
					/*
					if (isset($address['default'])) {
						$address_id = $this->db->getLastId();
						$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
					}*/
				}
			}
			if($oldid){
				$sql="DELETE FROM " . DB_PREFIX . "address WHERE address_id not in(" . implode(',', $oldid) . ") AND customer_id = '" . (int)$customer_id ."';".$sql;
			}
		}
		else
		{
			$sql.="DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id ."';";
		}
		
		$this->db->multi_query($sql);
	}
	
	public function deleteCustomer($customer_id) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET is_delete=1,status=0 WHERE customer_id = '" . (int)$customer_id . "'");
		
		/*$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");*/
		//$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "openid_info WHERE customer_id = '" . (int)$customer_id . "'");
	
	}
	
	/**
	 * 根据用户ID获取用户信息
	 * @param unknown $customer_id
	 */
	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
	
		return $query->row;
	}
	
	/**
	 * 根据用户手机号获取用户信息
	 * @param unknown $mobile
	 */
	public function getCustomerByMobile($mobile) {
	    $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE mobile = '" . $mobile . "'");
	
	    return $query->row;
	}
	
	
	/**
	 * 开通储值
	 * @param unknown $customer_id
	 */
	public function setTransactionPayment($customer_id, $flag=1){
    	// 开通用户储值功能(如果没有)
    	$sql = " SELECT * FROM " . DB_PREFIX . "payment_transaction WHERE customer_id={$customer_id}";
    	$trans = $this->db->query($sql);
    	if($trans->row && $trans->row['status'] != $flag){
    	    $this->db->query("UPDATE " . DB_PREFIX . "payment_transaction
    	        SET date_modified = NOW(), status={$flag} 
    	        WHERE customer_id={$customer_id}
    	        ");
    	}
    	else if($flag ==1){
    	        $this->db->query("INSERT INTO " . DB_PREFIX . "payment_transaction
    	        SET customer_id   ={$customer_id},
    	        status={$flag}, 
    	        date_added    = NOW(),
    	        date_modified = NOW()");
    	}
	}
	
	public function getTransactionPayment($customer_id){
	    $sql = " SELECT status FROM " . DB_PREFIX . "payment_transaction WHERE customer_id={$customer_id}";
	    $trans = $this->db->query($sql);

	    if($trans->row){
	        return $trans->row['status'];
	    }
	    else
	        return false;
	}
	
	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id)";

		$implode = array();
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname, c.mobile, c.telephone)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
		}
		
		if (isset($data['filter_mobile']) && !is_null($data['filter_mobile'])) {
		    $implode[] = "mobile = '" . $this->db->escape($data['filter_mobile']) . "'";
		}
		
		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}
		
		if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
			$implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
		}	
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}	
		
		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}	
			
		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}	
				
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_date_latest']) && !is_null($data['filter_date_latest'])) {
		    $implode[] = "DATE(c.date_latest_login) = DATE('" . $this->db->escape($data['filter_date_latest']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE is_delete='0' AND " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'name',
		    'mobile',
			'c.email',
			'customer_group',
			'c.status',
			'c.ip',
			'c.date_added',
		    'c.data_latest_login'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY c.customer_id";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
	
	public function approve($customer_id) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int)$customer_id . "'");

			$this->load->language('mail/customer');
			
			$this->load->model('setting/store');
						
			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);
			
			if ($store_info) {
				$store_name = $store_info['name'];
				$store_url = $store_info['url'] . 'index.php?route=account/login';
			} else {
				$store_name = $this->config->get('config_name');
				$store_url = HTTP_CATALOG . 'index.php?route=account/login';
			}
	
			$message  = sprintf($this->language->get('text_approve_welcome'), $store_name) . "\n\n";;
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= $store_url . "\n\n";
			$message .= $this->language->get('text_approve_services') . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= $store_name;
	
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');							
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(sprintf($this->language->get('text_approve_subject'), $store_name));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}		
	}
	
	public function getCustomersByNewsletter() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE newsletter = '1' ORDER BY firstname, lastname, email");
	
		return $query->rows;
	}
	
	public function getCustomersByCustomerGroupId($customer_group_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int)$customer_group_id . "' ORDER BY firstname, lastname, email");
	
		return $query->rows;
	}
		
	public function getCustomersByProduct($product_id) {
		if ($product_id) {
			$query = $this->db->query("SELECT DISTINCT `email` FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "' AND o.order_status_id <> '0'");
	
			return $query->rows;
		} else {
			return array();	
		}
	}
	
	public function getCustomersMobileByProduct($product_id) {
		if ($product_id) {
			$query = $this->db->query("SELECT DISTINCT `mobile` FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "' AND o.order_status_id <> '0'");
	
			return $query->rows;
		} else {
			return array();
		}
	}
	
	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

		$default_query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$address_query->row['customer_id'] . "'");
				
		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");
			
			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';	
				$address_format = '';
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$code = $zone_query->row['code'];
			} else {
				$zone = '';
				$code = '';
			}		
			
			$city_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "city` WHERE city_id = '" . (int)$address_query->row['city_id'] . "'");
			
			if ($city_query->num_rows) {
				$city = $city_query->row['name'];
				$city_code = $city_query->row['code'];
			} else {
				$city = '';
				$city_code = '';
			}
			if($address_query->row['poi']){
				$location=explode(' ', $address_query->row['poi']);
				$location['lng']=$location[0];
				$location['lat']=$location[1];
			}
			return array(
				'address_id'     => $address_query->row['address_id'],
				'customer_id'    => $address_query->row['customer_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'poi'      => $address_query->row['poi'],
				'location'            => $location,
				'address_2'      => $address_query->row['address_2'],
				'shipping_code'       => $address_query->row['shipping_code'],
				'shipping_data'       => $address_query->row['shipping_data'],
				'shipping_data_info'  => $address_query->row['shipping_data_info'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $city,
				'city_code'      => $city_code,
				'city_id'           => $address_query->row['city_id'],
				'mobile'           => $address_query->row['mobile'],
				'phone'           => $address_query->row['phone'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'default'		 => ($default_query->row['address_id'] == $address_query->row['address_id']) ? true : false
			);
		}
	}
		
	public function getAddresses($customer_id) {
		$address_data = array();
		
		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
	
		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($result['address_id']);
			if ($address_info) {
				$address_data[] = $address_info;
			}
		}		
		
		return $address_data;
	}	
			
	public function getTotalCustomers($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";
		
		$implode = array();
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (isset($data['filter_mobile']) && !is_null($data['filter_mobile'])) {
			$implode[] = "mobile = '" . $this->db->escape($data['filter_mobile']) . "'";
		}
		
		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "LCASE(email) LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}
		
		if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
		}	
				
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}			
		
		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}		
				
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
		
	public function getTotalCustomersAwaitingApproval() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE status = '0' OR approved = '0'");

		return $query->row['total'];
	}
	
	public function getTotalAddressesByCustomerId($customer_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalAddressesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getTotalAddressesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalCustomersByCustomerGroupId($customer_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		
		return $query->row['total'];
	}
			
	public function addTransaction($customer_id, $description = '', $amount = '', $order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);
		
		if ($customer_info) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");

			$this->language->load('mail/customer');
			
			if ($customer_info['store_id']) {
				$this->load->model('setting/store');
		
				$store_info = $this->model_setting_store->getStore($customer_info['store_id']);
				
				if ($store_info) {
					$store_name = $store_info['store_name'];
				} else {
					$store_name = $this->config->get('config_name');
				}	
			} else {
				$store_name = $this->config->get('config_name');
			}	
						
			$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
			$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotal($customer_id)));
								
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')));
			$mail->setText($message);
			$mail->send();
		}
	}
	
	public function deleteTransaction($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . $order_id . "'");
	}
	
	public function getTransactions($customer_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}

	public function getTotalTransactions($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
	
		return $query->row['total'];
	}
			
	public function getTransactionTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
	
		return $query->row['total'];
	}
	
	public function getTotalCustomerTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . $order_id . "'");
	
		return $query->row['total'];
	}	
				
	public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);
			
		if ($customer_info) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . $order_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

			$this->language->load('mail/customer');
			
			if ($order_id) {
				$this->load->model('sale/order');
		
				$order_info = $this->model_sale_order->getOrder($order_id);
				
				if ($order_info) {
					$store_name = $order_info['store_name'];
				} else {
					$store_name = $this->config->get('config_name');
				}	
			} else {
				$store_name = $this->config->get('config_name');
			}		
				
			$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
			$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));
				
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(sprintf($this->language->get('text_reward_subject'), $store_name));
			$mail->setText($message);
			$mail->send();
		}
	}

	public function deleteReward($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . $order_id . "'");
	}
	
	public function getRewards($customer_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}
	
	public function getTotalRewards($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
	
		return $query->row['total'];
	}
			
	public function getRewardTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
	
		return $query->row['total'];
	}		
	
	public function getTotalCustomerRewardsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . $order_id . "'");
	
		return $query->row['total'];
	}
	
	
	public function getIpsByCustomerId($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC");

		return $query->rows;
	}	
	
	public function getTotalCustomersByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}	

	public function getCustomerAddresses($data=array()){
		$sql="SELECT a.*,c.email as email FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "customer c ON (a.customer_id=c.customer_id) order by a.customer_id ASC";
		
		$query=$this->db->query($sql);
		
		return $query->rows;
	}

	/*public function addCoupon($customer_id,$order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);
			
		if ($customer_info) {
		$this->db->query("SELECT  cus.customer_id,couc.order_id,CONCAT(cus.firstname, ' ', cus.lastname) AS customer,cus.mobile,couc.used,couc.date_add,couc.date_limit  FROM " . DB_PREFIX . "customer cus LEFT JOIN " . DB_PREFIX . "coupon_to_customer couc ON (cus.customer_id=couc.customer_id)");
		
		// $this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET
		// customer_id = '" . (int)$customer_id . "', order_id = '" . $order_id .
		// "', points = '" . (int)$points . "', description = '" .
		// $this->db->escape($description) . "', date_added = NOW()");
		
			$this->language->load('mail/customer');
			
			if ($order_id) {
				$this->load->model('sale/order');
		
				$order_info = $this->model_sale_order->getOrder($order_id);
				
				if ($order_info) {
					$store_name = $order_info['store_name'];
				} else {
					$store_name = $this->config->get('config_name');
				}	
			} else {
				$store_name = $this->config->get('config_name');
			}		
			
			$message  = sprintf($this->language->get('text_coupon_received'), $points) . "\n\n";
			$message .= sprintf($this->language->get('text_coupon_total'), $this->getCouponTotal($customer_id));

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(sprintf($this->language->get('text_coupon_subject'), $store_name));
			$mail->setText($message);
			$mail->send();
		}
	}*/

	/*public function getCoupons($customer_id, $start = 0, $limit = 10) {
        $query = $this->db->query("SELECT ch.order_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.mobile, ch.amount, ch.date_added FROM " . DB_PREFIX . "coupon_history ch LEFT JOIN " . DB_PREFIX . "customer c ON (ch.customer_id = c.customer_id) WHERE ch.coupon_id = '" . (int)$coupon_id . "' ORDER BY ch.date_added ASC LIMIT " . (int)$start . "," . (int)$limit");

        return $query->rows;
    }*/

    public function getCustomerCoupons($customer_id) {
		$query = $this->db->query("SELECT  couc.customer_id,cou.name,couc.used,couc.date_add,couc.date_limit  FROM " . DB_PREFIX . "coupon_to_customer couc LEFT JOIN " . DB_PREFIX . "coupon cou ON (cou.coupon_id=couc.coupon_id) WHERE couc.customer_id = '" . (int)$customer_id . "'");
					
		return $query->rows;
	}
	
	public function getTotalCustomerCoupons($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon_to_customer WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row['total'];
    }
	
}
?>