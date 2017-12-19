<?php
class ModelSaleOrder extends Model {	
	/*apiv2-start*/
	protected function clearSpreadsheetCache() {
		$files = glob(DIR_CACHE . 'Spreadsheet_Excel_Writer' . '*');
	
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
			}
		}
	}
	/*apiv2-end*/

	protected function detect_encoding( $str ) {
		// auto detect the character encoding of a string
		return mb_detect_encoding( $str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R' );
	}
	/*apiv2-start*/
	function getCell(&$worksheet,$row,$col,$default_val='') {
		$col -= 1; // we use 1-based, PHPExcel uses 0-based column index
		$row += 1; // we use 0-based, PHPExcel used 1-based row index
		return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;
	}
	
	function clearCache() {
		$this->cache->delete('category');
		$this->cache->delete('category_description');
		$this->cache->delete('manufacturer');
		$this->cache->delete('product');
		$this->cache->delete('product_image');
		$this->cache->delete('product_option');
		$this->cache->delete('product_option_description');
		$this->cache->delete('product_option_value');
		$this->cache->delete('product_option_value_description');
		$this->cache->delete('product_to_category');
		$this->cache->delete('product_special');
		$this->cache->delete('product_discount');
	}
	
	function upload( $filename ) {
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;

		//set_error_handler('error_handler_for_export',E_ALL);
		set_error_handler(array($this, 'error_handler_for_export'));
		
		register_shutdown_function('fatal_error_shutdown_handler_for_export');
		
		$database =& $this->db;
		ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		//set_time_limit( 60 );
		chdir( '../system/PHPExcel' );
		require_once( 'Classes/PHPExcel.php' );
		chdir( '../../admin' );
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);
		
		$this->uploadOrders($reader, $database);
		chdir( '../../..' );
		return true;
	}
	
	
	function uploadOrders( &$reader, &$database ) {
		$data = $reader->getSheet(0);
		$orders = array();
		$order = array();
		$isFirstRow = TRUE;
		$i = 0;
		$k = $data->getHighestRow();
	
		for ($i=0; $i<$k; $i+=1) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			
			$orderId = trim($this->getCell($data,$i,1));
			$orderStatusId = trim($this->getCell($data,$i,13));
			 
			$this->log_order->info('update order ---- '.$orderId.' orderStatusId ==='.$orderStatusId);
			if($orderStatusId=='-1'){
				$order[0] = $orderId;
				$order[1] = $orderStatusId;
				array_push($orders,$orderId);
			}
			
		}
	
		return $this->storeOrdersIntoDatabase( $database, $orders );
	}
	/*apiv2-end*/
	function storeOrdersIntoDatabase(&$database, &$orders )
	{
		$orderStatusId=$this->config->get('config_order_received_status_id');
	
		// generate and execute SQL for storing the products
		$orders=array_unique($orders);
		
		foreach ($orders as $order) {
			$orderId = $order;
			
			$this->log_sys->info('storeOrdersIntoDatabase ---- '.$orderId);
			$database->query("UPDATE  " . DB_PREFIX . "order SET order_status_id='".$orderStatusId."' WHERE order_id='".$orderId."'");
		}
		return TRUE;
	}
	
	function getProductImage( &$database,$product_id ){
		$query  = "SELECT image  FROM " . DB_PREFIX . "product WHERE product_id='".$product_id."'";;
		$result = $database->query( $query );/*apiv2*/
		//$result =  $this->db->query( $query );
		return $result->row['image'];
	}
	
/*apiv2-start*/
	function populateOrdersWorksheet( &$worksheet, &$database, $orderid )
	{
		$orderStatusId=$this->config->get('config_order_shipped_status_id');
		// The options headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'order_id' );
		$worksheet->writeString( $i, $j++, 'customer' );
		$worksheet->writeString( $i, $j++, 'firstname' );
		$worksheet->writeString( $i, $j++, 'email' );
		$worksheet->writeString( $i, $j++, 'telephone' );
		$worksheet->writeString( $i, $j++, 'comment' );
		$worksheet->writeString( $i, $j++, 'sub_total' );
		$worksheet->writeString( $i, $j++, 'store credit' );
		$worksheet->writeString( $i, $j++, 'total' );
		$worksheet->writeString( $i, $j++, 'pdate' );
		$worksheet->writeString( $i, $j++, 'shipping_method' );
		$worksheet->writeString( $i, $j++, 'date_added' );
		$worksheet->writeString( $i, $j++, 'order_status' );
		$worksheet->writeString( $i, $j++, 'product_name' );
		$worksheet->writeString( $i, $j++, 'product_model' );
		$worksheet->writeString( $i, $j++, 'product_option' );
		$worksheet->writeString( $i, $j++, 'product_quantity' );
		$worksheet->writeString( $i, $j++, 'product_price' );
		$worksheet->writeString( $i, $j++, 'product_total' );
		$worksheet->writeString( $i, $j++, 'product_id' );
		$worksheet->writeString( $i, $j++, 'product_image' );
		// The actual options data
		$i += 1;
		$j = 0;
		//$query  = "SELECT * FROM `".DB_PREFIX."order` WHERE `order_id` IN ( ".$orderid." )";
		$query  = "SELECT o.*, op.name, op.model, op.order_product_id,op.product_id, op.quantity, op.price, op.total AS ptotal, op.tax, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total') AS sub_total, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'credit') AS store_credit FROM `" . DB_PREFIX . "order` o inner join `" . DB_PREFIX . "order_product` op on o.order_id=op.order_id  WHERE o.order_id IN ( ".$orderid." ) order by o.order_id";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			//$database->query("UPDATE  " . DB_PREFIX . "order SET order_status_id='".$orderStatusId."' WHERE order_id='".$row['order_id'] ."'");
			
			$worksheet->writeString( $i, $j++, $row['order_id'] );
			
			$worksheet->writeString( $i, $j++, $row['customer_id'] );
			$worksheet->writeString( $i, $j++, $row['firstname'] );

			$worksheet->writeString( $i, $j++, $row['email'] );
			$worksheet->writeString( $i, $j++, $row['telephone'] );
			$worksheet->writeString( $i, $j++, $row['comment'] );
			$worksheet->writeString( $i, $j++, $row['sub_total'] );
			$worksheet->writeString( $i, $j++, $row['store_credit'] );
			$worksheet->writeString( $i, $j++, $row['total'] );
			$worksheet->writeString( $i, $j++, $row['pdate'] );
			$worksheet->writeString( $i, $j++, $row['shipping_method'] );
			$worksheet->writeString( $i, $j++, $row['date_added'] );
			$worksheet->writeString( $i, $j++, $row['order_status_id'] );
			$worksheet->writeString( $i, $j++, $row['name'] );
			$worksheet->writeString( $i, $j++, $row['model'] );
			$order_options = array();
			$order_str = array();
			$order_options = $this->getOrderOptions($row['order_id'], $row['order_product_id']);
			foreach($order_options as $order_option){
				$order_str[] = $order_option['name'].':'.$order_option['value'];
			}
			$worksheet->writeString( $i, $j++, implode('@@@' , $order_str));
			$worksheet->writeString( $i, $j++, $row['quantity'] );
			$worksheet->writeString( $i, $j++, $row['price'] );
			$worksheet->writeString( $i, $j++, $row['ptotal'] );
			$worksheet->writeString( $i, $j++, $row['product_id'] );
			$worksheet->writeString( $i, $j++, $this->getProductImage($database,$row['product_id']));
			$i += 1;
			$j = 0;
		}
	}
	
		public function exportOrder($selectid) {
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;
		//set_error_handler('error_handler_for_export',E_ALL);
		set_error_handler(array($this, 'error_handler_for_export'));
		
		register_shutdown_function('fatal_error_shutdown_handler_for_export');
		$database =& $this->db;
	
		// We use the package from http://pear.php.net/package/Spreadsheet_Excel_Writer/
		chdir( DIR_SYSTEM . 'pear' );
		require_once "Spreadsheet/Excel/Writer.php";
		chdir( DIR_APPLICATION );
	
		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setTempDir(DIR_CACHE);
		$workbook->setVersion(8); // Use Excel97/2000 BIFF8 Format
	
		// sending HTTP headers
		$workbook->send(date("Y-m-d").'-export-orders.xls');
	
		// Creating the categories worksheet
		$worksheet =& $workbook->addWorksheet('sheet');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateOrdersWorksheet( $worksheet, $database, $selectid);
		//$worksheet->freezePanes(array(1, 1, 1, 1));
	
			
		// Let's send the file
		$workbook->close();
	
		// Clear the spreadsheet caches
		$this->clearSpreadsheetCache();
		exit;
	}
	
    static $config = NULL;
	static $log = NULL;
	
	// Error Handler

	
		function error_handler_for_export($errno, $errstr, $errfile, $errline) {
		global $config;
		global $log_sys;
	
		switch ($errno) {
			case E_NOTICE:
			case E_USER_NOTICE:
				$errors = "Notice";
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$errors = "Warning";
				break;
			case E_ERROR:
			case E_USER_ERROR:
				$errors = "Fatal Error";
				break;
			default:
				$errors = "Unknown";
			break;
		}
	
		if (($errors=='Warning') || ($errors=='Unknown')) {
			return true;
		}
	
		if ($config->get('config_error_display')) {
			echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
		}
	
		if ($config->get('config_error_log')) {
			$log_sys->info('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
		}
	
		return true;
	}
	
	
	
	function fatal_error_shutdown_handler_for_export()
	{
		$last_error = error_get_last();
		if ($last_error['type'] === E_ERROR) {
			// fatal error
			error_handler_for_export(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
		}
	}
	
	
	public function addOrder($data) {
		if ($data['order_status_id']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order`");
	
			if ($query->row['invoice_no']) {
				$invoice_no = (int)$query->row['invoice_no'] + 1;
			} elseif ($this->config->get('config_invoice_no')) {
				$invoice_no = $this->config->get('config_invoice_no');
			} else {
				$invoice_no = 1;
			}
		} else {
			$invoice_no = 0;
		}


		// Add invoice no. if not set.
		if (!$order_info['invoice_no'] && $data['order_status_id']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");
	
			if ($query->row['invoice_no']) {
				$invoice_no = (int)$query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . $order_id . "'");
		}
		
		
				
		$this->load->model('setting/store');
		
		$store_info = $this->model_setting_store->getStore($data['store_id']);
		
		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_CATALOG;			
		}
		
		$this->load->model('sale/customer');
		
		$customer_info = $this->model_sale_customer->getCustomer($data['customer_id']);
		
		if ($customer_info) {
			$customer_group_id = $customer_info['customer_group_id'];
		} elseif ($store_info) {
			$customer_group_id = $store_info['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');		
		}
				
		$this->load->model('localisation/country');
		
		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$this->load->model('localisation/zone');
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	
					
		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}	

		$this->load->model('localisation/currency');

		$currency_info = $this->getCurrencyByCode($this->config->get('config_currency'));
		
		if ($currency_info) {
			$currency_id = $currency_info['currency_id'];
			$currency_value = $currency_info['value'];
		} else {
			$currency_id = 0;
			$currency_value = 1.00000;			
		}
		
      	$this->db->query("INSERT INTO " . DB_PREFIX . "order SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "', store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$customer_group_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "',  shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . ", shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$total . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', language_id = '" . (int)$data['affiliate_id'] . "', currency_id = '" . $this->db->escape($this->config->get('config_currency')) . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', date_added = NOW(), date_modified = NOW()");
      	
      	$order_id = $this->db->getLastId();
      	
      	if (isset($data['order_product'])) {		
      		foreach ($data['order_product'] as $order_product) {

      			$orderProductSql = "INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . $order_id . "', product_id = '" . (int)$return_product['product_id'] . "', name = '" . $this->db->escape($return_product['name']) . "', model = '" . $this->db->escape($return_product['model']) . "', quantity = '" . (int)$return_product['quantity'] . "', manufacturer = '" . (int)$return_product['manufacturer'] . "', return_reason_id = '" . (int)$return_product['return_reason_id'] . "', opened = '" . (int)$return_product['opened'] . "', comment = '" . $this->db->escape($return_product['comment']) . "', return_action_id = '" . (int)$return_product['return_action_id'] . "'";
      			if(isset($data['rule_code']))
      			{
      				$orderProductSql .= " , rule_code ='".$data['rule_code']."'";
      			}
      			$this->db->query($orderProductSql);
			}
			
			$order_product_id = $this->db->getLastId();

			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . $order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
			}
		}
		
		foreach ($product['download'] as $download) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . $order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
		}		
			
      	if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . $order_id . "', product_id = '" . (int)$return_product['product_id'] . "', name = '" . $this->db->escape($return_product['name']) . "', model = '" . $this->db->escape($return_product['model']) . "', quantity = '" . (int)$return_product['quantity'] . "', manufacturer = '" . (int)$return_product['manufacturer'] . "', return_reason_id = '" . (int)$return_product['return_reason_id'] . "', opened = '" . (int)$return_product['opened'] . "', comment = '" . $this->db->escape($return_product['comment']) . "', return_action_id = '" . (int)$return_product['return_action_id'] . "'");
			}
		}		
	}
	
	public function editOrder($order_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "return SET order_id = '" . $data['order_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', invoice_no = '" . $this->db->escape($data['invoice_no']) . "', invoice_date = '" . $this->db->escape($data['invoice_date']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', return_status_id = '" . (int)$data['return_status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "return_product WHERE return_id = '" . (int)$return_id . "'");
      	
		if (isset($data['return_product'])) {		
      		foreach ($data['return_product'] as $return_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "return_product SET return_id = '" . (int)$return_id . "', product_id = '" . (int)$return_product['product_id'] . "', name = '" . $this->db->escape($return_product['name']) . "', model = '" . $this->db->escape($return_product['model']) . "', quantity = '" . (int)$return_product['quantity'] . "', return_reason_id = '" . (int)$return_product['return_reason_id'] . "', opened = '" . (int)$return_product['opened'] . "', comment = '" . $this->db->escape($return_product['comment']) . "', return_action_id = '" . (int)$return_product['return_action_id'] . "'");
			}
		} 
	}
	
	public function updateCertification($order_id, $certification) {
		$this->db->query("UPDATE " . DB_PREFIX . "order SET certification = '" . $certification. "',date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function deleteOrder($order_id) {
		if ($this->config->get('config_stock_subtract')) {
			$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . $order_id . "'");

			if ($order_query->num_rows) {
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");

				foreach($product_query->rows as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "'");

					$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . $order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . $order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "'");
	  	$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . $order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . $order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . $order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . $order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE order_id = '" . $order_id . "'");
	}



	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . $order_id . "'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}				
				
			$this->load->model('sale/affiliate');
				
			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
				
			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';				
			}

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
				'pickup_code'             =>$order_query->row['pickup_code'],
				'order_id'                => $order_query->row['order_id'],
				'p_order_id'              => $order_query->row['p_order_id'],
				'pdate'            	      => $order_query->row['pdate'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_mobile'         => $order_query->row['shipping_mobile'],
				'shipping_phone'          => $order_query->row['shipping_phone'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $order_query->row['reward'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,				
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'ip'                      => $order_query->row['ip'],
				'express'                 => $order_query->row['express'],
				'express_website'         => $order_query->row['express_website'],
				'express_no'              => $order_query->row['express_no'],
				'certification'           => $order_query->row['certification'],
			
				'invoice_type'            => $order_query->row['invoice_type'],
				'invoice_head'            => $order_query->row['invoice_head'],
				'invoice_name'            => $order_query->row['invoice_name'],
				'invoice_content'         => $order_query->row['invoice_content'],
			    'shipping_point_id'       => $order_query->row['shipping_point_id'],
			    'shipping_time'           => $order_query->row['shipping_time']
			);
		} else {
			return false;
		}
	}

	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, o.customer_id,o.pdate,o.p_order_id,o.order_status_id, CONCAT(o.firstname) AS customer,o.email, o.shipping_firstname AS username ,o.partner_code as partner_code,(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if (isset($data['filter_order_partner']) && !is_null($data['filter_order_partner'])) {
			$sql .= " AND o.partner_code = '" . $data['filter_order_partner'] . "'";
		} 

		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . $data['filter_order_id'] . "'";
		}

		if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
			$sql .= " AND CONCAT(email) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		//	$sql .= " OR CONCAT(o.shipping_firstname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_date_modified']) && !is_null($data['filter_date_modified'])) {
			$sql .= " AND o.pdate = '" . $this->db->escape($data['filter_date_modified']) . "'";
		}

		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.date_added',
			'o.date_modified',
			'o.order_id',
			'customer',
			'status',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY o.date_added DESC,o.date_modified DESC ," . $data['sort'];
		} else {
			$sql .= " ORDER BY o.date_added DESC,o.date_modified DESC,o.order_id";
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
	public function getExOrders($data = array()) {
		$sql = "SELECT o.order_id, o.customer_id,o.pdate,o.p_order_id, CONCAT(o.firstname) AS customer,o.email, o.shipping_firstname AS username ,(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";
	
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
	
		$sql .= " AND o.pdate != ''";
	
		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . $data['filter_order_id'] . "'";
		}
	
		if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
			$sql .= " AND CONCAT(email) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
			//	$sql .= " OR CONCAT(o.shipping_firstname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
	
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
	
		if (isset($data['filter_date_modified']) && !is_null($data['filter_date_modified'])) {
			$sql .= " AND o.pdate = '" . $this->db->escape($data['filter_date_modified']) . "'";
		}
	
		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
	
		$sort_data = array(
				'o.date_added',
				'o.date_modified',
				'o.order_id',
				'customer',
				'status',
				'o.total'
		);
	
		
		$sql .= " ORDER BY o.date_added DESC,o.pdate DESC ";
		
	
		
	
		$query = $this->db->query($sql);
	
		return $query->rows;
	}
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");
		
		return $query->rows;
	}
	
	public function getOrderOption($order_id, $order_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "' AND order_option_id = '" . (int)$order_option_id . "'");

		return $query->row;
	}
	/*apiv2-end*/
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");
	
		return $query->rows;
	}
/*apiv2-start*/
	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getOrderDownloads($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . $order_id . "' ORDER BY name");

		return $query->rows;
	}
	
	public function getTotalOrders($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}
		
		if (isset($data['filter_order_partner']) && !is_null($data['filter_order_partner'])) {
			$sql .= " AND partner_code = '" . $data['filter_order_partner'] . "'";
		} 

		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . $data['filter_order_id'] . "'";
		}

		if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_date_modified']) && !is_null($data['filter_date_modified'])) {
			$sql .= " AND pdate = '" . $this->db->escape($data['filter_date_modified']) . "'";
		}

		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrdersByStoreId($store_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByLanguageId($language_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSales() {
      	$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSalesByYear($year) {
      	$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "'");

		return $query->row['total'];
	}

	public function addOrderHistory($order_id, $data) {
        $order_info = $this->getOrder($order_id);
		
		// Add invoice no. if not set.
		if (!$order_info['invoice_no'] && $data['order_status_id']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");
	
			if ($query->row['invoice_no']) {
				$invoice_no = (int)$query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . $order_id . "'");
		}
				
		/*if(isset($data['express'])&&$data['express']!=''){
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "logistics WHERE logistics_id = '" . (int)$data['express'] . "'");
			$data['express']=$query->row['logistics_name'];
			$data['express_website']=$query->row['logistics_link'];
		}else{
			$data['express']='';
			$data['express_website']='';
		}*/
		
		$data['express']='';
		$data['express_website']='';
		
		if(!isset($data['express_no'])){
			$data['express_no']='';
		}
		
		if($data['express']!=''){
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET express_no = '" . $this->db->escape($data['express_no']) . "', express = '" . $this->db->escape($data['express']) . "', express_website = '" . $this->db->escape($data['express_website']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . $order_id . "'");
			$this->log_order->info("addOrderHistory:: express_no = '" . $this->db->escape($data['express_no']) . "', express = '" . $this->db->escape($data['express']) . "', express_website = '" . $this->db->escape($data['express_website']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW()");
		}else 	{
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET  order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . $order_id . "'");
			$this->log_order->info("addOrderHistory:: order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW()");
			
		}
		
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		// Send out any gift voucher mails
		if ($this->config->get('config_complete_status_id') == $data['order_status_id']) {
			$this->load->model('sale/voucher');

			$results = $this->model_sale_voucher->getVouchersByOrderId($order_id);
			
			foreach ($results as $result) {
				$this->model_sale_voucher->sendVoucher($result['voucher_id']);
			}
		}

      	if ($data['notify']) {
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/order');

			$subject = sprintf($language->get('text_subject'), $order_info['store_name'], $order_id);

			$message  = $language->get('text_order') . ' ' . $order_id . "\n";
			$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";
			
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
			if ($order_status_query->num_rows) {
				$message .= $language->get('text_order_status') . "\n";
				$message .= $order_status_query->row['name'] . "\n\n";
			}
			
			if ($order_info['customer_id']) {
				$message .= $language->get('text_link') . "\n";
				$message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
			}
			
			if ($data['comment']) {
				$message .= $language->get('text_comment') . "\n\n";
				$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			}

			$message .= $language->get('text_footer');

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($order_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($order_info['store_name']);
			$mail->setSubject($subject);
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}
		
	
	public function getSubOrders($p_order_id,$data=array()) {
		$sql="SELECT o.order_id,o.p_order_id,o.pdate, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value,o.order_status_id, o.shipping_firstname as shipping_name FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.p_order_id = '" . (int)$p_order_id. "' AND os.language_id = '" . (int)$this->config->get('config_language_id')
		. "'";
	
		$sql .= " ORDER BY o.date_added,o.pdate DESC";
	
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
	
	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . $order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getTotalOrderHistories($order_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . $order_id . "'");

		return $query->row['total'];
	}	
		
	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}	
	/*apiv2-end*/
	
	public function getOrdersByPdate($pdate) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE pdate = '" . $pdate . "' ");
	
		return $query->rows;
	}
	
	public function getOrdersByPdate2($pdate,$pos_id) {
		$addrs=$this->config->get('addrs');
		$pos_name='';
		
		foreach ($addrs as $addr) {
			
			if($addr['no']==$pos_id){
				$pos_name=$addr['title'];
				$sql= "SELECT * FROM " . DB_PREFIX . "order WHERE pdate = '" . $pdate . "'  AND LCASE(shipping_method) LIKE BINARY '%" . $this->db->escape(mb_strtolower($pos_name, 'UTF-8')) . "%'";
				$query = $this->db->query($sql);
				return $query->rows;
			}
		}
		
		return false;
		
	}
	
	public function updateOrders($orders){
		$orderStatusId=$this->config->get('config_order_received_status_id');
		
		foreach ($orders as $order) {
			$orderId = $order['order_id'];
			$orderStatusId = $order['order_status_id'];
			$this->log_order->info("UPDATE  " . DB_PREFIX . "order SET order_status_id='".$orderStatusId."' WHERE order_id='".$orderId."'");
			
			$this->db->query("UPDATE  " . DB_PREFIX . "order SET order_status_id='".$orderStatusId."' WHERE order_id='".$orderId."'");
		}
	}
	
	public function populateOrders($pdate)
	{
		$orderStatusId=$this->config->get('config_order_shipped_status_id');
		
		$orders=$this->getOrdersByPdate($pdate);
		$return_orders=array();
		foreach ($orders as $order) {
			$orderid=$order['order_id'];
			$query  = "SELECT o.*, op.name, op.model, op.order_product_id,op.product_id, op.quantity, op.price, op.total AS ptotal, op.tax, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total') AS sub_total, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'credit') AS store_credit FROM `" . DB_PREFIX . "order` o inner join `" . DB_PREFIX . "order_product` op on o.order_id=op.order_id  WHERE o.order_id IN ( ".$orderid." ) order by o.order_id";
			$result = $this->db->query( $query );
			foreach ($result->rows as $row) {
				$order_options = array();
				$order_str = array();
				$order_options = $this->getOrderOptions($row['order_id'], $row['order_product_id']);
				foreach($order_options as $order_option){
					$order_str[] = $order_option['name'].':'.$order_option['value'];
				}
				$return_orders[]=array(
						'order_id' => $row['order_id'],
						'customer' => $row['customer_id'],
						'firstname' => $row['firstname'],
						'email' => $row['email'],
						'telephone' => $row['telephone'],
						'comment' => $row['comment'],
						'sub_total' => $row['sub_total'],
						'store_credit' => $row['store_credit'],
						'total' => $row['total'],
						'pdate' => $row['pdate'],
						'shipping_method' => $row['shipping_method'],
						'date_added' => $row['date_added'],
						'order_status' => $row['order_status_id'],
						'product_name' => $row['name'],
						'product_model' => $row['model'],
						'product_option' => implode('@@@' , $order_str),
						'product_quantity' => $row['quantity'],
						'product_price' => $row['price'],
						'product_total' => $row['ptotal'],
						'product_id' => $row['product_id'],
						'product_image' => $this->getProductImage($database,$row['product_id'])
				);
					
			}
		}
		return $return_orders;
	}
	
	public function populateOrders2($pdate,$pos_id)
	{
		$orderStatusId=$this->config->get('config_order_shipped_status_id');
	
		$orders=$this->getOrdersByPdate2($pdate,$pos_id);
		$return_orders=array();
		foreach ($orders as $order) {
			$orderid=$order['order_id'];
			$query  = "SELECT o.*, op.name, op.model, op.order_product_id,op.product_id, op.quantity, op.price, op.total AS ptotal, op.tax, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total') AS sub_total, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'credit') AS store_credit FROM `" . DB_PREFIX . "order` o inner join `" . DB_PREFIX . "order_product` op on o.order_id=op.order_id  WHERE o.order_id IN ( ".$orderid." ) order by o.order_id";
			$result = $this->db->query( $query );
			foreach ($result->rows as $row) {
				$order_options = array();
				$order_str = array();
				$order_options = $this->getOrderOptions($row['order_id'], $row['order_product_id']);
				foreach($order_options as $order_option){
					$order_str[] = $order_option['name'].':'.$order_option['value'];
				}
				$return_orders[]=array(
							'order_id' => $row['order_id'],
							'customer' => $row['customer_id'],
							'firstname' => $row['firstname'],
							'email' => $row['email'],
							'telephone' => $row['telephone'],
							'comment' => $row['comment'],
							'sub_total' => $row['sub_total'],
							'store_credit' => $row['store_credit'],
							'total' => $row['total'],
							'pdate' => $row['pdate'],
							'shipping_method' => $row['shipping_method'],
							'date_added' => $row['date_added'],
							'order_status' => $row['order_status_id'],
							'product_name' => $row['name'],
							'product_model' => $row['model'],
							'product_option' => implode('@@@' , $order_str),
							'product_quantity' => $row['quantity'],
							'product_price' => $row['price'],
							'product_total' => $row['ptotal'],
							'product_id' => $row['product_id'],
							'product_image' => $this->getProductImage($database,$row['product_id'])
				);
					
			}
		}
		return $return_orders;
	}
}
?>