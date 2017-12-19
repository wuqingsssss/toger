<?php

class ModelSaleOrder extends Model {

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

    /**
     * @param $order_id
     * @param $data
     * @return mixed
     */
    public function insertHistoryData($order_id, $data, $operator) {
    	
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW(), operator='". $operator."'");
        $this->log_order->info($data);
        
        $order_info = $this->getOrder($order_id);
        
        	//通知第三方入口
        	if($order_info['partner_code']=='meituan'){      		 
        		$this->load->model('localisation/order_status');
        		$order_status= $this->model_localisation_order_status->getOrderStatuses();
        		$this->load->service('meituan/order','service');
        		$data['order_id']=$order_id;
        		$data['order_status']= $order_status[$order_info['order_status_id']]['name'];
        		$data['order_status_id']=$order_info['order_status_id'];
        		$data['reason']=$data['comment'];

        		//$res=$this->service_meituan_order->HMTUpdate($data);
        		
        		
           }

        
        
        // 如果需要，发送通知短信
        if($data['notify']) {
       
        	if(empty($order_info['shipping_point_id'])){
        		// 发送宅配短信模板消息
        		$this->sendSmsShipping($order_id, $order_info);
        		// 发送微信模板消息
        		$this->sendWeixinMsgShipping($order_id, $order_info);
        	}else {
        		// 发送自提短信模板消息
        		$this->sendSms($order_id, $order_info);
        		// 发送微信模板消息
        		$this->sendWeixinMsg($order_id, $order_info);
        	}
        }
    
    }

    public function insertShistoryData($order_id, $data, $operator) {
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "service_history SET order_id = '" . $order_id . "', operate_id = 0 , reason_id = 0 , comment = '" . $data['comment'] . "', date_added = NOW(), operator='". $operator."'");
        $this->log_admin->info($data);
        
        $order_info = $this->getOrder($order_id);
    }

    protected function detect_encoding($str) {  
        // auto detect the character encoding of a string
        return mb_detect_encoding($str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R');
    }

    function getCell(&$worksheet, $row, $col, $default_val = '') {
        $col -= 1; // we use 1-based, PHPExcel uses 0-based column index
        $row += 1; // we use 0-based, PHPExcel used 1-based row index
        return ($worksheet->cellExistsByColumnAndRow($col, $row)) ? $worksheet->getCellByColumnAndRow($col, $row)->getValue() : $default_val;
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

    function upload($filename) {
        global $config;
        global $log;
        $config = $this->config;
        $log = $this->log;
        global $config;
        global $log;
        $config = $this->config;
        $log = $this->log;
        //set_error_handler('error_handler_for_export',E_ALL);
       // set_error_handler(array($this, 'error_handler_for_export'));

       //   register_shutdown_function('fatal_error_shutdown_handler_for_export');
        register_shutdown_function('error_handler');
        

        $database =& $this->db;
        ini_set("memory_limit", "512M");
        ini_set("max_execution_time", 180);
        //set_time_limit( 60 );
        chdir('../system/PHPExcel');
        require_once('Classes/PHPExcel.php');
        chdir('../../admin');
        $inputFileType = PHPExcel_IOFactory::identify($filename);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $reader = $objReader->load($filename);

        $this->uploadOrders($reader, $database);
        chdir('../../..');
        return true;
    }


    function uploadOrders(&$reader, &$database) {
        $data = $reader->getSheet(0);
        $orders = array();
        $order = array();
        $isFirstRow = TRUE;
        $i = 0;
        $k = $data->getHighestRow();

        for ($i = 0; $i < $k; $i += 1) {
            if ($isFirstRow) {
                $isFirstRow = FALSE;
                continue;
            }

            $orderId = trim($this->getCell($data, $i, 1));
            $orderStatusId = trim($this->getCell($data, $i, 13));

           $this->log_admin->info('user:'.$this->user->getUserName().'::admin->model->sale->order->update order ::' . $orderId . ' orderStatusId ===' . $orderStatusId);
             if ($orderStatusId == '-1') {
                $order[0] = $orderId;
                $order[1] = $orderStatusId;
                array_push($orders, $orderId);
            }

        }

        return $this->storeOrdersIntoDatabase($database, $orders);
    }

    function storeOrdersIntoDatabase(&$database, &$orders) {
        $orderStatusId = $this->config->get('config_order_received_status_id');
        var_dump($orderStatusId);

        // generate and execute SQL for storing the products
        $orders = array_unique($orders);

        foreach ($orders as $order) {
            $orderId = $order;

              $this->log_admin->info('user:'.$this->user->getUserName().'::admin->model->sale->order->update order ::' . $orderId . ' orderStatusId ===' . $orderStatusId);
              $database->query("UPDATE  " . DB_PREFIX . "order SET order_status_id='" . $orderStatusId . "' WHERE order_id='" . $orderId . "'");
        }
        return TRUE;
    }

    function getProductImage(&$database, $product_id) {
        $query = "SELECT image  FROM " . DB_PREFIX . "product WHERE product_id='" . $product_id . "'";;
        $result = $database->query($query);
        return $result->row['image'];
    }

    function populateOrdersWorksheet(&$worksheet, &$database, $orderid) {
    	
    
        $orderStatusId = $this->config->get('config_order_shipped_status_id');
        // The options headings row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'order_id');
        $worksheet->writeString($i, $j++, 'customer');
        $worksheet->writeString($i, $j++, 'firstname');
        $worksheet->writeString($i, $j++, 'email');
        $worksheet->writeString($i, $j++, 'telephone');
        $worksheet->writeString($i, $j++, 'comment');
        $worksheet->writeString($i, $j++, 'sub_total');
        $worksheet->writeString($i, $j++, 'store credit');
        $worksheet->writeString($i, $j++, 'total');
        $worksheet->writeString($i, $j++, 'pdate');
        $worksheet->writeString($i, $j++, 'shipping_method');
        $worksheet->writeString($i, $j++, 'date_added');
        $worksheet->writeString($i, $j++, 'order_status');
        $worksheet->writeString($i, $j++, 'product_name');
        $worksheet->writeString($i, $j++, 'product_model');
        $worksheet->writeString($i, $j++, 'product_option');
        $worksheet->writeString($i, $j++, 'product_quantity');
        $worksheet->writeString($i, $j++, 'product_price');
        $worksheet->writeString($i, $j++, 'product_total');
        $worksheet->writeString($i, $j++, 'product_id');
        $worksheet->writeString($i, $j++, 'product_image');
        // The actual options data
        $i += 1;
        $j = 0;
        //$query  = "SELECT * FROM `".DB_PREFIX."order` WHERE `order_id` IN ( ".$orderid." )";
        $query = "SELECT o.*, op.name, op.model, op.order_product_id,op.product_id, op.quantity, op.price, op.total AS ptotal, op.tax, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total') AS sub_total, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'credit') AS store_credit FROM `" . DB_PREFIX . "order` o inner join `" . DB_PREFIX . "order_product` op on o.order_id=op.order_id  WHERE o.order_id IN ( " . $orderid . " ) order by o.order_id";
        $result = $database->query($query);
        foreach ($result->rows as $row) {
            //	$database->query("UPDATE  " . DB_PREFIX . "order SET order_status_id='".$orderStatusId."' WHERE order_id='".$row['order_id'] ."'");

            $worksheet->writeString($i, $j++, $row['order_id']);

            $worksheet->writeString($i, $j++, $row['customer_id']);
            $worksheet->writeString($i, $j++, $row['firstname']);

            $worksheet->writeString($i, $j++, $row['email']);
            $worksheet->writeString($i, $j++, $row['telephone']);
            $worksheet->writeString($i, $j++, $row['comment']);
            $worksheet->writeString($i, $j++, $row['sub_total']);
            $worksheet->writeString($i, $j++, $row['store_credit']);
            $worksheet->writeString($i, $j++, $row['total']);
            $worksheet->writeString($i, $j++, $row['pdate']);
            $worksheet->writeString($i, $j++, $row['shipping_method']);
            $worksheet->writeString($i, $j++, $row['date_added']);
            $worksheet->writeString($i, $j++, $row['order_status_id']);
            $worksheet->writeString($i, $j++, $row['name']);
            $worksheet->writeString($i, $j++, $row['model']);
            $order_options = array();
            $order_str = array();
            $order_options = $this->getOrderOptions($row['order_id'], $row['order_product_id']);
            foreach ($order_options as $order_option) {
                $order_str[] = $order_option['name'] . ':' . $order_option['value'];
            }
            $worksheet->writeString($i, $j++, implode('@@@', $order_str));
            $worksheet->writeString($i, $j++, $row['quantity']);
            $worksheet->writeString($i, $j++, $row['price']);
            $worksheet->writeString($i, $j++, $row['ptotal']);
            $worksheet->writeString($i, $j++, $row['product_id']);
            $worksheet->writeString($i, $j++, $this->getProductImage($database, $row['product_id']));
            $i += 1;
            $j = 0;
        }
    }

    function populateSalesWorksheet(&$worksheet, &$database, $orderid) {
        $orderStatusId = $this->config->get('config_order_shipped_status_id');
        // The options headings row
        $i = 0;
        $j = 0;
	
	    $worksheet->writeString($i, $j++, '日期');
        $worksheet->writeString($i, $j++, '订单号');
        $worksheet->writeString($i, $j++, '姓名');
        $worksheet->writeString($i, $j++, '电话');
        $worksheet->writeString($i, $j++, '物流');
        $worksheet->writeString($i, $j++, '站点');
        $worksheet->writeString($i, $j++, '菜名');
        $worksheet->writeString($i, $j++, '数量');
        $worksheet->writeString($i, $j++, '单价');
        $worksheet->writeString($i, $j++, '本品总价');
        $worksheet->writeString($i, $j++, '订单总价');
        $worksheet->writeString($i, $j++, '支付方式');
        $worksheet->writeString($i, $j++, '来源');
        $worksheet->writeString($i, $j++, '设备');
        // The actual options data
        $i += 1;
        $j = 0;
        //$query  = "SELECT * FROM `".DB_PREFIX."order` WHERE `order_id` IN ( ".$orderid." )";
        $query = "SELECT o.*, op.name, op.model, op.order_product_id,op.product_id, op.quantity, op.price, op.total AS ptotal, op.tax, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total') AS sub_total, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'credit') AS store_credit FROM `" . DB_PREFIX . "order` o inner join `" . DB_PREFIX . "order_product` op on o.order_id=op.order_id  WHERE o.order_id IN ( " . $orderid . " ) order by o.order_id";
        $result = $database->query($query);
        $this->load->model('catalog/partnercode');
		$partners = $this->model_catalog_partnercode->getAllPartners();	
		
        $tmp_order_id = 0;
        foreach ($result->rows as $row) {
            //	$database->query("UPDATE  " . DB_PREFIX . "order SET order_status_id='".$orderStatusId."' WHERE order_id='".$row['order_id'] ."'");
            $same_order = ($tmp_order_id == $row['order_id']);
            if($row['shipping_point_id'])
            $worksheet->writeString($i, $j++, $row['pdate']);
            else 
            $worksheet->writeString($i, $j++, $row['shipping_time']);
            	
            if (!$same_order) {
                $worksheet->writeString($i, $j++, $row['order_id']);
            } else {
                $worksheet->writeString($i, $j++, '');
            }
            $tmp_order_id = $row['order_id'];
            if (!$same_order) {
                $worksheet->writeString($i, $j++, $row['firstname']);
            } else {
                $worksheet->writeString($i, $j++, '');
            }

            if (!$same_order) {
                $worksheet->writeString($i, $j++, $row['telephone']);
            } else {
                $worksheet->writeString($i, $j++, '');
            }

            if (!$same_order) {
            	$worksheet->writeString($i, $j++, $row['shipping_code'].$row['shipping_data']);	
            } else {
            	$worksheet->writeString($i, $j++, '');
            }
   
            if (!$same_order) {
            	if($row['shipping_point_id']){
                $worksheet->writeString($i, $j++,'自提:'.$row['shipping_method']);}
                else
                {
                $worksheet->writeString($i, $j++,'宅配:'.$row['shipping_data'].'::'. $row['shipping_address_1']);	
                }
                
            } else {
                $worksheet->writeString($i, $j++, '');
            }

            $worksheet->writeString($i, $j++, $row['name']);
            $worksheet->writeString($i, $j++, $row['quantity']);
            $worksheet->writeString($i, $j++, $row['price']);
            $worksheet->writeString($i, $j++, $row['ptotal']);
            if (!$same_order) {
                $worksheet->writeString($i, $j++, $row['total']);
            } else {
                $worksheet->writeString($i, $j++, '');
            }
            $worksheet->writeString($i, $j++, "{$row[payment_method]}[{$row['payment_code']}]");
//            $worksheet->writeString($i, $j++, EnumPartners::getPartnerInfo($row['partnerCode']));
			 $worksheet->writeString($i, $j++, $row['partner_code']? $partners[$row['partner_code']]:'内站');
			 $worksheet->writeString($i, $j++, EnumOrderSourceFrom::getOptionValue($row['source_from']));
            $i += 1;
            $j = 0;
        }
    }

    function populateProductionWorksheet(&$worksheet, &$database, $orderid) {
        $orderStatusId = $this->config->get('config_order_shipped_status_id');
        // The options headings row

        /*
        $worksheet->writeString( $i, $j++, '日期' );
        $worksheet->writeString( $i, $j++, '菜名' );
        $worksheet->writeString( $i, $j++, '姓名' );
        $worksheet->writeString( $i, $j++, '电话' );
        $worksheet->writeString( $i, $j++, '取菜点' );
        $worksheet->writeString( $i, $j++, '菜名' );
        $worksheet->writeString( $i, $j++, '数量' );
        $worksheet->writeString( $i, $j++, '单价' );
        $worksheet->writeString( $i, $j++, '本品总价' );
        $worksheet->writeString( $i, $j++, '订单总价' );
        */
        // The actual options data
        $i = 0;
        $j = 0;

        $query = "SELECT production_name FROM `" . DB_PREFIX . "production_order_list` order by production_id";
        $result = $database->query($query);
        $production = array();
        foreach ($result->rows as $name) {
            $production[$name['production_name']] = array();
        }

        //$query  = "SELECT * FROM `".DB_PREFIX."order` WHERE `order_id` IN ( ".$orderid." )";
        $query = "SELECT o.*, op.name, op.model, op.order_product_id,op.product_id, op.quantity, op.price, op.total AS ptotal, op.tax, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total') AS sub_total, (SELECT ot.value FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'credit') AS store_credit FROM `" . DB_PREFIX . "order` o inner join `" . DB_PREFIX . "order_product` op on o.order_id=op.order_id  WHERE o.order_id IN ( " . $orderid . " ) order by o.order_id";
        $result = $database->query($query);
        $shipping_method = array();

        $i = 20;
        $j = 10;
        foreach ($result->rows as $row) {
            //	$database->query("UPDATE  " . DB_PREFIX . "order SET order_status_id='".$orderStatusId."' WHERE order_id='".$row['order_id'] ."'");
            $pdate = $row['pdate'];
            if ($production[$row['name']] == NULL) {
                $production[$row['name']] = array();
            }
            if ($shipping_method[$row['shipping_method']] == NULL) {
                $shipping_method[$row['shipping_method']] = $row['shipping_method'];
            }
            if ($production[$row['name']][$row['shipping_method']] == NULL) {
                $production[$row['name']][$row['shipping_method']] = 0;
            }
            if ($production[$row['name']]['total'] == NULL) {
                $production[$row['name']]['total'] = 0;
            }
            $production[$row['name']][$row['shipping_method']] += $row['quantity'];
            $production[$row['name']]['total'] = $production[$row['name']]['total'] + $row['quantity'];
        }
        
        
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, '日期');
        $worksheet->writeString($i, $j++, '菜名');
        foreach ($shipping_method as $key => $value) {
            $worksheet->writeString($i, $j++, $value);
        }
        $worksheet->writeString($i, $j++, '总数');

        $i += 1;
        $j = 0;
        foreach ($production as $prod_key => $prod) {
            $worksheet->writeString($i, $j++, $pdate);
            $worksheet->writeString($i, $j++, $prod_key);

            foreach ($shipping_method as $key => $value) {
                $worksheet->writeNumber($i, $j++, $production[$prod_key][$value]);
            }
            $worksheet->writeNumber($i, $j++, $prod['total']);

            $j = 0;
            $i += 1;
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
        chdir(DIR_SYSTEM . 'pear');
        require_once "Spreadsheet/Excel/Writer.php";
        chdir(DIR_APPLICATION);

        // Creating a workbook
        $workbook = new Spreadsheet_Excel_Writer();
        $workbook->setTempDir(DIR_CACHE);
        $workbook->setVersion(8); // Use Excel97/2000 BIFF8 Format

        // sending HTTP headers
        $workbook->send(date("Y-m-d") . '-export-orders.xls');

        // Creating the categories worksheet
        $worksheet =& $workbook->addWorksheet('sales sheet');
        $worksheet->setInputEncoding('UTF-8');
        //sunmoon 20140722 修改销售订单生成内容
        $this->populateSalesWorksheet($worksheet, $database, $selectid);

        //$worksheet->freezePanes(array(1, 1, 1, 1));

        $worksheet =& $workbook->addWorksheet('production sheet');
        $worksheet->setInputEncoding('UTF-8');
        //sunmoon 20140722 修改销售订单生成内容
        $this->populateProductionWorksheet($worksheet, $database, $selectid);

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
        global $log_admin;

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

        if (($errors == 'Warning') || ($errors == 'Unknown')) {
            return true;
        }

        if ($config->get('config_error_display')) {
            echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
        }

        if ($config->get('config_error_log')) {
            $log_admin->info('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
        }

        return true;
    }


    function fatal_error_shutdown_handler_for_export() {
        $last_error = error_get_last();
        if ($last_error['type'] === E_ERROR) {
            // fatal error
            error_handler_for_export(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }

    public function getCurrencyByCode($currency) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");

        return $query->row;
    }

    private function genOrderId() {
        $common = new Common($this->registry);

        return $common->genOrderSN();
    }
    private function generatePickupCode($device_code=''){
    	if(empty($device_code))$device_code = date('md');
        return $device_code. str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);;
    }
    private function getDeviceCode($point_id){
        $this->load->model('catalog/point');

        $point_info=$this->model_catalog_point->getPoint($point_id);

        if($point_info){
            //TODO:设备号如何获取还需要修改
            return $point_info['device_code'];
        }

        return 'Unknown';
    }
    
    /**
     * 追加订单
     * @param unknown $data
     * @param unknown $ip
     */
    public function addOrder($data,$ip) {
        $invoice_no = 0;
        $invoice_no_prefix = $this->config->get('config_invoice_prefix');
        // Add invoice no. if not set.
        if ( $data['order_status_id'] == 2) {
            $query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $invoice_no_prefix . "'");
        
            if ($query->row['invoice_no']) {
                $invoice_no = (int)$query->row['invoice_no'] + 1;
            } else {
                $invoice_no = 1;
            }
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
        $data['shipping_address_1']=$this->db->clean_nonchar($data['shipping_address_1']);
        $data['shipping_address_2']=$this->db->clean_nonchar($data['shipping_address_2']);
        //获取用户信息
        $this->load->model('sale/customer');

        $customer_info = $this->model_sale_customer->getCustomer($data['customer_id']);
        //用电话号码获取用户信息
        if( empty($customer_info) || !empty($data['telephone'])){
            $customer_info = $this->model_sale_customer->getCustomerByMobile($data['telephone']);
            $data['customer_id'] = $customer_info['customer_id'];
        }

        if ($customer_info) {
            $customer_group_id = $customer_info['customer_group_id'];
            
            if($data['customer_addresses']>0){
            	
            $address['address_id']=$data['customer_addresses'];
            $address['customer_id']=$customer_info['customer_id'];
            $address['address_1']=$data['shipping_address_1'];
            $address['address_1_poi']=$data['shipping_poi'];
            $address['address_2']=$data['shipping_address_2'];
            $address['shipping_code']=$data['shipping_code'];
            $address['shipping_data']=$data['shipping_data'];
            $address['shipping_poi']=$data['shipping_poi'];
            $address['mobile']=$this->db->match_phone($data['telephone']);
            $address['firstname']=$data['firstname'];
            $address['lastname']=$data['lastname'];
            
           $this->update_customer_address($address);
            }
            
        } elseif ($store_info) {
            $customer_group_id = $store_info['customer_group_id'];
        } else {
            $customer_group_id = '0';
//            $customer_group_id = $this->config->get('config_customer_group_id');
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
            $currency_code = $currency_info['code'];
            $currency_value = $currency_info['value'];
        } else {
            $currency_code = 0;
            $currency_value = 1.00000;
        }

        $total = $data['total'];
        $order_id = $this->genOrderId();

        $order_status_id = $data['order_status_id'];
  
        if($data['stype']=='1'){//自提
        $shipping_point_id = $data['shipping_point_id'];
        $device_code=$this->getDeviceCode($shipping_point_id);
        $data['pickup_code']=$this->generatePickupCode($device_code);

        $this->load->model('catalog/point');
        $point_info=$this->model_catalog_point->getPoint($shipping_point_id);
        $pdate = $data['pdate'];
        $shipping_code=$point_info['shipping_code'];
        $shipping_data=$point_info['shipping_data'];
        }
        else 
        {       	
        	$shipping_code= $data['shipping_code'];
        	$shipping_poi= $data['shipping_poi'];
        	$shipping_data= $data['shipping_data'];
        	$shipping_time=$data['shipping_date'].' '.$data['shipping_time'];
        	$data['pickup_code']=$this->generatePickupCode();
        }

        $rowData = array(
            array('order_id', $order_id, true),
            array('invoice_no', (int)$invoice_no, true),
            array('invoice_prefix', $invoice_no_prefix, true),
            array('store_id', (int)$data['store_id'], true),
            array('store_name', $store_name, true, true),
            array('store_url', $store_url, true, true),
            array('customer_id', $data['customer_id'], true),
            array('customer_group_id', $customer_group_id, true),
            array('firstname', $data['firstname'], true, true),
            array('lastname', $data['lastname'], true, true),//
            array('email', $data['email'], true, true),
            array('telephone', $data['telephone'], true, true),
            array('fax', $data['fax'], true, true),//
            array('shipping_firstname', $data['shipping_firstname'], true, true),
            array('shipping_lastname', $data['shipping_lastname'], true, true),
            array('shipping_company', $data['shipping_company'], true, true),
            array('shipping_address_1', $this->db->clean_nonchar($data['shipping_address_1']), true, true),
        	array('shipping_mobile', $this->db->match_phone($data['shipping_mobile']), true, true),
            array('shipping_city', $data['shipping_city'], true, true),
            array('shipping_postcode', $data['shipping_postcode'], true, true),
            array('shipping_address_2', $data['shipping_address_2'], true, true),
            array('shipping_country_id', $data['shipping_country_id'], true, true),
            array('shipping_country', $shipping_country, true, true),
            array('shipping_zone_id', $data['shipping_zone_id'], true, false),
            array('shipping_address_format', $data['shipping_address_format'], true, false),
            array('shipping_method', $data['shipping_method'], true, false),//取采点
            array('shipping_point_id', $shipping_point_id, true, false),//取采点
        	array('shipping_code', $shipping_code, true, false),//取采点
        	array('poi', $shipping_poi, true, false),//取采点
        	array('shipping_time', $shipping_time, true, false),//取采点
        	array('shipping_data', $shipping_data, true, false),//取采点
        	array('tp_order_id', $data['tp_order_id'], true, false),
            array('payment_firstname', $data['payment_firstname'], true, false),
            array('payment_lastname', $data['payment_lastname'], true, false),
            array('payment_company', $data['payment_company'], true, false),
            array('payment_address_1', $data['payment_address_1'], true, false),
            array('payment_address_2', $data['payment_address_2'], true, false),
            array('payment_city', $data['payment_city'], true, false),
            array('payment_postcode', $data['payment_postcode'], true, false),
            array('payment_country', $payment_country, true, false),
            array('payment_country_id', $data['payment_country_id'], true, false),
            array('payment_zone', $payment_zone, true, true),
            array('payment_zone_id', $data['payment_zone_id'], true, true),
            array('payment_address_format', $payment_address_format, true, true),
            array('payment_method', '免费支付', true, true),//支付宝?
            array('payment_code', 'free_checkout', true, true),//alipay
            array('comment', $this->db->clean_nonchar($data['comment']), true, true),
            array('total', $total, true, false),
            array('order_status_id', $order_status_id, true, false),
//            array('order_type', 0, false, false),//
            array('affiliate_id', $data['affiliate_id'], true, true),
            array('language_id', 1, true, true),//TODO //中文
            array('currency_id', $this->config->get('config_currency'), true, false),
            array('currency_code', $currency_code, true, false),
            array('currency_value', $currency_value, true, false),
            array('date_added', 'now()', false, false),
            array('date_modified', 'now()', false, false),
            array('commission', 0.0000, true),
            array('ip', $ip, true),
            array('pdate', $pdate,true),
            array('pickup_code', $data['pickup_code'], true),
            array('source_from', 999, false, false),
            array('device_code', $device_code, true, false),
            array('partner_code', $data['partner_code'], true, true),//alipay
        );
        DbHelper::insert('order', $rowData);
       
        if (isset($data['order_product'])) {
        	
        	foreach ($data['order_product'] as $order_product){
        		
        		if(isset($data['order_produt_real'][$order_product['product_id']])){

        		      $data['order_produt_real'][$order_product['product_id']]['quantity']+=$order_product['quantity'];
        		}
        		else 
        		{
        		      $data['order_produt_real'][$order_product['product_id']]=$order_product;
        		}
        	}
        	
        	
            foreach ($data['order_produt_real'] as $order_product) {
                $product_id = $order_product['product_id'];
                //$pdt = $this->getProductDescription($product_id);

                $this->load->model('catalog/product');                
                $product_info = $this->model_catalog_product->getProduct($product_id);
                               
                $quantity = intval($order_product['quantity']);
                $price = floatval($order_product['price']);

                if(!empty($order_product['promotion_code'])){
                    $itemTotal = $quantity * floatval($order_product['promotion_price']);
                }
                else{
                    $itemTotal = $quantity * $price;
                }
                $order_product['name']=$this->db->clean_nonchar($product_info['name']);
                $rowData = array(
                    array('order_id', $order_id, true),
                    array('product_id', $product_id, true),
                    array('name', $order_product['name'], true, true),
                    array('model', $product_info['sku'], true, true),//TODO  ?
                    array('quantity', $quantity, true),
                    array('price', $price, false),
                    array('promotion_code', $order_product['promotion_code'], true, true),
                    array('promotion_price', $order_product['promotion_price'], true, false),
                    array('combine', $product_info['combine'], true, false),
                    array('packing_type', $product_info['packing_type'], true, true),
                    array('total', $itemTotal, false),
                    array('tax', 0.0000, false),
//                    array('rule_code', '', true),
                    array('rule_code', '', true),
//                    array('pdate', $order_product['pdate'], true),
                    array('pdate', $pdate,true)
                );
                if ($order_product['rule_code']) {
                    $rowData[] = array('rule_code', $order_product['rule_code'], true, true);
                }
                DbHelper::insert('order_product', $rowData);
            }
        }

        if (isset($total)) {

            $rowData = array(
                array('order_id', $order_id, true),
                array('code', 'total', true),
                array('title', '应付总额', true),
                array('text', '￥' . $total, true),
                array('value', $total, true),
                array('sort_order', 9, false)
            );
            DbHelper::insert('order_total', $rowData);

            $rowData = array(
                array('order_id', $order_id, true),
                array('code', 'sub_total', true),
                array('title', '商品总金额', true),
                array('text', '￥' . $total, true),
                array('value', $total, true),
                array('sort_order', 1, false)
            );
            DbHelper::insert('order_total', $rowData);
        }
        
//增加支付历史逻辑

        
        
        $sql ="INSERT INTO " . DB_PREFIX . "order_payment
                   SET order_id = '" . $order_id . "',
                       payment_code = 'free_checkout',
                       `value` = '" . (float)$total . "',
                       date_added = NOW(),
                       `status` = ".($order_status_id==2?1:0);
        $this->db->query($sql);
        
        
        $history = array(
            'order_status_id' => $order_status_id,
        	'shipping_point_id' => $shipping_point_id,
        	'telephone'=>$data['telephone'],
            'notify' => $order_status_id==2?1:0,
            'comment' => '后台追加订单'
        );
        $shistory = array(
            'comment' => $data['comment1']
        );
        
 
        //$this->addOrderHistory($order_id, $order_info, $this->user->getUserName());
        $this->log_order->info($data);
        // 订单历史
        $this->insertHistoryData($order_id,$history, $this->user->getUserName());
        $this->insertShistoryData($order_id,$shistory,$this->user->getUserName());

    }

    /**
     * 更新订单信息
     * @param unknown $order_id
     * @param unknown $data
     */
    public function updateOrder($order_id, $data) {

    	$invoice_no = 0;
    	$invoice_no_prefix = $this->config->get('config_invoice_prefix');
    	// Add invoice no. if not set.
    	if ( $data['order_status_id'] == 2) {
    		$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $invoice_no_prefix . "'");
    	
    		if ($query->row['invoice_no']) {
    			$invoice_no = (int)$query->row['invoice_no'] + 1;
    		} else {
    			$invoice_no = 1;
    		}
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
    	
    	
    	
    	$data['shipping_address_1']=$this->db->clean_nonchar($data['shipping_address_1']);
    	$data['shipping_address_2']=$this->db->clean_nonchar($data['shipping_address_2']);
    	$this->load->model('sale/customer');
    	
    	$customer_info = $this->model_sale_customer->getCustomer($data['customer_id']);
    	
    	if ($customer_info) {
    		$customer_group_id = $customer_info['customer_group_id'];
    		
    		
    		
    		if($data['customer_addresses']>0){
    			$address['address_id']=$data['customer_addresses'];
    			$address['customer_id']=$customer_info['customer_id'];
    			$address['address_1']=$data['shipping_address_1'];
    			$address['address_2']=$data['shipping_address_2'];
    			$address['shipping_code']=$data['shipping_code'];
    			$address['shipping_data']=$data['shipping_data'];
    			$address['address_1_poi']=$data['shipping_poi'];
    			$address['mobile']=$this->db->match_phone($data['telephone']);
    			$address['firstname']=$data['firstname'];
    			$address['lastname']=$data['lastname'];
    		
    			$this->update_customer_address($address);
    		}
    		
    		
    		
    	} elseif ($store_info) {
    		$customer_group_id = $store_info['customer_group_id'];
    	} else {
    		$customer_group_id = '0';
    		//            $customer_group_id = $this->config->get('config_customer_group_id');
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
    		$currency_code = $currency_info['code'];
    		$currency_value = $currency_info['value'];
    	} else {
    		$currency_code = 0;
    		$currency_value = 1.00000;
    	}
    	
    	$total = $data['total'];
    	//$order_id = $this->genOrderId();
    	
    	$order_status_id = $data['order_status_id'];
    	
    	if($data['stype']=='1'){//自提
    		$shipping_point_id = $data['shipping_point_id'];
    		$device_code=$this->getDeviceCode($shipping_point_id);
    		$data['pickup_code']=$this->generatePickupCode($device_code);
    	
    		$this->load->model('catalog/point');
    		$point_info=$this->model_catalog_point->getPoint($shipping_point_id);
    		$pdate = $data['pdate'];
    		$shipping_code=$point_info['shipping_code'];
    		$shipping_data=$point_info['shipping_data'];
    	}
    	else
    	{
    		$shipping_code= $data['shipping_code'];
    		$shipping_data= $data['shipping_data'];
    		$shipping_poi= $data['shipping_poi'];
    		$shipping_time=$data['shipping_date'].' '.$data['shipping_time'];
    	}
    	
    	$rowData = array(
    			array('invoice_no', (int)$invoice_no, true),
    			array('invoice_prefix', $invoice_no_prefix, true),
    			array('store_id', (int)$data['store_id'], true),
    			array('store_name', $store_name, true, true),
    			array('store_url', $store_url, true, true),
    			array('customer_id', $data['customer_id'], true),
    			array('customer_group_id', $customer_group_id, true),
    			array('firstname', $data['firstname'], true, true),
    			array('lastname', $data['lastname'], true, true),//
    			array('email', $data['email'], true, true),
    			array('telephone', $data['telephone'], true, true),
    			array('fax', $data['fax'], true, true),//
    			array('shipping_firstname', $data['shipping_firstname'], true, true),
    			array('shipping_lastname', $data['shipping_lastname'], true, true),
    			array('shipping_company', $data['shipping_company'], true, true),
    			array('shipping_address_1', $data['shipping_address_1'], true, true),
    			array('shipping_mobile', $this->db->match_phone($data['shipping_mobile']), true, true),
    			array('shipping_city', $data['shipping_city'], true, true),
    			array('shipping_postcode', $data['shipping_postcode'], true, true),
    			array('shipping_address_2', $data['shipping_address_2'], true, true),
    			array('shipping_country_id', $data['shipping_country_id'], true, true),
    			array('shipping_country', $shipping_country, true, true),
    			array('shipping_zone_id', $data['shipping_zone_id'], true, false),
    			array('shipping_address_format', $data['shipping_address_format'], true, false),
    			array('shipping_method', $data['shipping_method'], true, false),//取采点
    			array('shipping_point_id', $shipping_point_id, true, false),//取采点
    			array('shipping_code', $shipping_code, true, false),//取采点
    			array('shipping_time', $shipping_time, true, false),//取采点
    			array('shipping_data', $shipping_data, true, false),//取采点
    			array('poi', $shipping_poi, true, false),//取采点
    			array('tp_order_id', $data['tp_order_id'], true, false),
    			array('payment_firstname', $data['payment_firstname'], true, false),
    			array('payment_lastname', $data['payment_lastname'], true, false),
    			array('payment_company', $data['payment_company'], true, false),
    			array('payment_address_1', $data['payment_address_1'], true, false),
    			array('payment_address_2', $data['payment_address_2'], true, false),
    			array('payment_city', $data['payment_city'], true, false),
    			array('payment_postcode', $data['payment_postcode'], true, false),
    			array('payment_country', $payment_country, true, false),
    			array('payment_country_id', $data['payment_country_id'], true, false),
    			array('payment_zone', $payment_zone, true, true),
    			array('payment_zone_id', $data['payment_zone_id'], true, true),
    			array('payment_address_format', $payment_address_format, true, true),
    			//array('payment_method', '免费支付', true, true),//支付宝?
    			//array('payment_code', 'free_checkout', true, true),//alipay
    			array('comment', $data['comment'], true, true),
    			//array('total', $total, true, false),
    			array('order_status_id', $order_status_id, true, false),
    			//array('order_type', 0, false, false),//
    			array('affiliate_id', $data['affiliate_id'], true, true),
    			array('language_id', 1, true, true),//TODO //中文
    			array('currency_id', $this->config->get('config_currency'), true, false),
    			array('currency_code', $currency_code, true, false),
    			array('currency_value', $currency_value, true, false),
    			//array('date_added', 'now()', false, false),
    			array('date_modified', 'now()', false, false),
    			array('commission', 0.0000, true),
    			//array('ip', $ip, true),
    			array('pdate', $pdate,true),
    			array('pickup_code', $data['pickup_code'], true),
    			//array('source_from', 999, false, false),
    			array('device_code', $device_code, true, false)
    	);

    	DbHelper::update('order',array(array('order_id',$order_id,false)), $rowData);
    	$this->log_admin->info('updateOrder::data:'.serialize($rowData));

    	
    	if($order_status_id == '2'){//如果是改为已付款，则更新支付历史为已支付
    		$sql ="UPDATE " . DB_PREFIX . "order_payment
                   SET `status` = 1 WHERE order_id = '" . $order_id . "'";
    		$this->db->query($sql);
    	}else if($order_status_id == '11'){
    		$sql ="UPDATE " . DB_PREFIX . "order_payment
                   SET `status` = 9 WHERE order_id = '" . $order_id . "'";
    		$this->db->query($sql);
    	}
    	
    	
    	$history = array(
    			'order_status_id' => $order_status_id,
    			'shipping_point_id' => $shipping_point_id,
    			'notify' => $order_status_id==2?1:0,
    			'telephone'=>$data['telephone'],
    			'comment' => '后台修改订单'
    	);
    	//客服记录
        $shistory = array(
                'comment' => $data['comment1']
        );
    	// 订单历史
    	$this->insertHistoryData($order_id,$history, $this->user->getUserName());
        $this->insertShistoryData($order_id,$shistory,$this->user->getUserName());
    	//$this->addOrderHistory($order_id, $order_info, $this->user->getUserName());
    	/* */
    	$this->log_order->info($data);
       
    }
    
    public function update_customer_address($address){
    	
    	if((int)$address['address_id']>0){
    		$sql="UPDATE " . DB_PREFIX . "address SET  mobile = '" .  $this->db->escape($address['mobile']) . "', phone = '" .  $this->db->escape($address['phone']) . "', customer_id = '" . (int)$address['customer_id'] . "', firstname = '" . $this->db->escape($address['firstname']) . "',lastname = '" . $this->db->escape($address['lastname']) . "',  address_1 = '" . $this->db->escape($this->db->clean_nonchar($address['address_1'])) . "', address_2 = '" . $this->db->escape($this->db->clean_nonchar($address['address_2'])) . "', city_id = '" . (int)$address['city_id'] . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'"
    				. ",poi = '" . str_replace(',',' ',$this->db->escape($address['address_1_poi']))
    				. "', shipping_code = '" .  $this->db->escape($address['shipping_code'])
    				. "', shipping_data = '" .  $this->db->escape($address['shipping_data'])
    				."' WHERE address_id = '" . $this->db->escape($address['address_id']) . "';";
    	
    	}
    	else {
    		 
    		$sql="INSERT INTO " . DB_PREFIX . "address SET  mobile = '" .  $this->db->escape($address['mobile']) . "', phone = '" .  $this->db->escape($address['phone']) . "',customer_id = '" . (int)$address['customer_id'] . "', firstname = '" . $this->db->escape($address['firstname']) . "',lastname = '" . $this->db->escape($address['lastname']) . "', address_1 = '" . $this->db->escape($this->db->clean_nonchar($address['address_1'])) . "', address_2 = '" . $this->db->escape($this->db->clean_nonchar($address['address_2'])) . "', city_id = '" . (int)$address['city_id'] . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'"
    				. ",poi = '" . str_replace(',',' ',$this->db->escape($address['address_1_poi']))
    				. "', shipping_code = '" .  $this->db->escape($address['shipping_code'])
    				. "', shipping_data = '" .  $this->db->escape($address['shipping_data'])."';";
    	}
    	
    	$this->log_sys->info('update_customer_address::'.serialize($address));
    	
    	$this->db->query($sql);
    }
    
    /* 退换货处理*/
    public function editOrder($order_id, $data) {
    	
    	$this->db->query("UPDATE " . DB_PREFIX . "return SET order_id = '" . $data['order_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', invoice_no = '" . $this->db->escape($data['invoice_no']) . "', invoice_date = '" . $this->db->escape($data['invoice_date']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', return_status_id = '" . (int)$data['return_status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");
    	
    	$this->db->query("DELETE FROM " . DB_PREFIX . "return_product WHERE return_id = '" . (int)$return_id . "'");
    	
    	if (isset($data['return_product'])) {
    		foreach ($data['return_product'] as $return_product) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "return_product SET return_id = '" . (int)$return_id . "', product_id = '" . (int)$return_product['product_id'] . "', name = '" . $this->db->escape($return_product['name']) . "', model = '" . $this->db->escape($return_product['model']) . "', quantity = '" . (int)$return_product['quantity'] . "', return_reason_id = '" . (int)$return_product['return_reason_id'] . "', opened = '" . (int)$return_product['opened'] . "', comment = '" . $this->db->escape($return_product['comment']) . "', return_action_id = '" . (int)$return_product['return_action_id'] . "'");
    		}
    	}/* */
    
    	$this->log_order->info($data);
    	
    }

    public function updateCertification($order_id, $certification) {
        $this->db->query("UPDATE " . DB_PREFIX . "order SET certification = '" . $certification . "',date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
        $this->log_order->info($order_id.':'.$certification);
    }

    public function deleteOrder($order_id) {
        if ($this->config->get('config_stock_subtract')) {
            $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . $order_id . "'");

            if ($order_query->num_rows) {
                $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");

                foreach ($product_query->rows as $product) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "'");

                    $option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

                    foreach ($option_query->rows as $option) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
                    }
                }
            }
        }

        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET is_delete=1 WHERE order_id = '" . $order_id . "'");
        $this->log_order->info($order_id);
        
        $history = array(
        		'notify' => 0,
        		'comment' => '后台删除订单'
        );
        
        $this->insertHistoryData($order_id,$history, $this->user->getUserName());
        
        /*
         * $this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . $order_id . "'");
        //$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE order_id = '" . $order_id . "'");
        */
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
                'order_id' => $order_query->row['order_id'],
                'p_order_id' => $order_query->row['p_order_id'],
                'pdate' => $order_query->row['pdate'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'customer' => $order_query->row['customer'],
                'customer_group_id' => $order_query->row['customer_group_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'email' => $order_query->row['email'],	
            	'shipping_point_id' => $order_query->row['shipping_point_id'],
            	'shipping_code' => $order_query->row['shipping_code'],
            	'shipping_data' => $order_query->row['shipping_data'],
            	'poi' => $order_query->row['poi'],
            	'shipping_time' => $order_query->row['shipping_time'],
            	'tp_order_id' => $order_query->row['tp_order_id'],
            	'sp_order_id' => $order_query->row['sp_order_id'],
                'shipping_firstname' => $order_query->row['shipping_firstname'],
                'shipping_lastname' => $order_query->row['shipping_lastname'],
                'shipping_mobile' => $order_query->row['shipping_mobile'],
                'shipping_phone' => $order_query->row['shipping_phone'],
                'shipping_company' => $order_query->row['shipping_company'],
                'shipping_address_1' => $order_query->row['shipping_address_1'],
                'shipping_address_2' => $order_query->row['shipping_address_2'],
                'shipping_postcode' => $order_query->row['shipping_postcode'],
                'shipping_city' => $order_query->row['shipping_city'],
                'shipping_zone_id' => $order_query->row['shipping_zone_id'],
                'shipping_zone' => $order_query->row['shipping_zone'],
                'shipping_zone_code' => $shipping_zone_code,
                'shipping_country_id' => $order_query->row['shipping_country_id'],
                'shipping_country' => $order_query->row['shipping_country'],
                'shipping_iso_code_2' => $shipping_iso_code_2,
                'shipping_iso_code_3' => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_method' => $order_query->row['shipping_method'],
                'payment_firstname' => $order_query->row['payment_firstname'],
                'payment_lastname' => $order_query->row['payment_lastname'],
                'payment_company' => $order_query->row['payment_company'],
                'payment_address_1' => $order_query->row['payment_address_1'],
                'payment_address_2' => $order_query->row['payment_address_2'],
                'payment_postcode' => $order_query->row['payment_postcode'],
                'payment_city' => $order_query->row['payment_city'],
                'payment_zone_id' => $order_query->row['payment_zone_id'],
                'payment_zone' => $order_query->row['payment_zone'],
                'payment_zone_code' => $payment_zone_code,
                'payment_country_id' => $order_query->row['payment_country_id'],
                'payment_country' => $order_query->row['payment_country'],
                'payment_iso_code_2' => $payment_iso_code_2,
                'payment_iso_code_3' => $payment_iso_code_3,
                'payment_address_format' => $order_query->row['payment_address_format'],
                'payment_method' => $order_query->row['payment_method'],
            	'payment_code'   => $order_query->row['payment_code'],  
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'reward' => $order_query->row['reward'],
                'order_status_id' => $order_query->row['order_status_id'],
                'affiliate_id' => $order_query->row['affiliate_id'],
                'affiliate_firstname' => $affiliate_firstname,
                'affiliate_lastname' => $affiliate_lastname,
                'commission' => $order_query->row['commission'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_filename' => $language_filename,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'date_added' => $order_query->row['date_added'],
                'date_modified' => $order_query->row['date_modified'],
                'ip' => $order_query->row['ip'],
                'express' => $order_query->row['express'],
                'express_website' => $order_query->row['express_website'],
                'express_no' => $order_query->row['express_no'],
                'certification' => $order_query->row['certification'],
                'pickup_code' => $order_query->row['pickup_code'],
            	'partner_code' => $order_query->row['partner_code'],
                'invoice_type' => $order_query->row['invoice_type'],
                'invoice_head' => $order_query->row['invoice_head'],
                'invoice_name' => $order_query->row['invoice_name'],
				'order_type' => $order_query->row['order_type'],
				'addition_info' => $order_query->row['addition_info'],
                'invoice_content' => $order_query->row['invoice_content']
            );
        } else {
            return false;
        }
    }

    
private function getfilters($data)
    {
    	if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
    		$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
    	} else {
    		$sql .= " WHERE o.order_status_id > '0'";
    	}
    	
    	$sql .= " AND o.is_delete = '0'";
    	
    	if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
    		$sql .= " AND o.order_id = '" . $data['filter_order_id'] . "'";
    	}
    	
    	if (isset($data['filter_partner_code']) && !is_null($data['filter_partner_code'])) {
    		if(empty($data['filter_partner_code'])){
    			$sql .= " AND (o.partner_code = '0' OR o.partner_code = '')";
    			
    		}else {
    		$sql .= " AND o.partner_code = '" . $data['filter_partner_code'] . "'";
    		}
    	}
    	
    	if (isset($data['filter_source_from']) && !is_null($data['filter_source_from'])) {
    		$sql .= " AND o.source_from = '" . (int)$data['filter_source_from'] . "'";
    	}
    	
    	if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
    		$sql .= " AND CONCAT(email) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
    	}
    	
    	$filter_customer_phone = $data['filter_customer_phone'];
    	if (isset($filter_customer_phone) && !is_null($filter_customer_phone)) {
    		$sql .= " AND CONCAT(IFNULL(o.telephone,''),IFNULL(o.shipping_mobile,''),IFNULL(o.shipping_phone,'')) LIKE '%" . $this->db->escape($data['filter_customer_phone']) . "%'";
    	}
    	
    	if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
    		$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    	}
    	
    	if (isset($data['filter_date_pick']) && !is_null($data['filter_date_pick'])) {
    		$sql .= " AND (o.pdate = '" . $this->db->escape($data['filter_date_pick']) . "' or date_format(o.shipping_time,'%Y-%m-%d')= '" . $this->db->escape($data['filter_date_pick']) . "')";
    	}
    	
    	if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
    		$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
    	}
    	if (isset($data['filter_order_refund']) && !is_null($data['filter_order_refund'])) {
    		$sql .= " AND order_status_id in (11,13) ";
    	}
    	
    	if (isset($data['filter_point_name']) && !is_null($data['filter_point_name'])) {
    	           $sql .= " AND CONCAT(shipping_method,shipping_data) like '%".$this->db->escape($data['filter_point_name'])."%'";
    	    }
    	
    	if (isset($data['filter_point_id']) && !is_null($data['filter_point_id'])) {
    		if(is_numeric($data['filter_point_id'])){
    			$sql .= " AND o.shipping_point_id='".(int)$data['filter_point_id']."'";
    		}else {
    			$sql .= " AND CONCAT(shipping_code,shipping_data)='".$data['filter_point_id']."'";
    		}
    	}

		if (isset($data['payment_code']) && !is_null($data['payment_code'])) {
    		$sql .= " AND payment_code = '{$data['payment_code']}' ";
    	}
		
		if (isset($data['order_type']) && !is_null($data['order_type'])) {
    		$sql .= " AND order_type = {$data['order_type']} ";
    	}

    	return $sql;
    }
    
    public function getOrders($data = array()) {
        $sql = "SELECT o.order_id,o.partner_code,o.source_from ,o.order_type, o.customer_id,o.pdate,o.p_order_id,o.order_status_id, CONCAT(o.firstname) AS customer,o.telephone,o.email, o.shipping_firstname AS username,o.shipping_time,o.tp_order_id,shipping_code,shipping_data ,(SELECT os.name FROM " . DB_PREFIX
            . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id')
            . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.pdate,o.shipping_method,o.payment_method,o.shipping_point_id FROM `" . DB_PREFIX . "order` o";

   $sql.=  $this->getfilters($data);

        $sort_data = array(
            'o.date_added',
            'o.pdate',
            'o.order_id',
            'customer',
            'status',
            'o.total'
        );

        // modified by cww 2015.4.2
    /*    if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY o.date_added DESC,o.pdate DESC ," . $data['sort'];
        } else {
            $sql .= " ORDER BY o.date_added DESC,o.pdate DESC,o.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
  */
        if( isset($data['sort']) && $data['sort'] == 'o.order_id') {
            $sql .= " ORDER BY o.order_id ". $data['order']. " ";
        }
        else if( isset($data['sort']) && $data['sort'] == 'o.date_added') {
            $sql .= " ORDER BY o.date_added ". $data['order']. ", o.order_id DESC ";
        }
        else if( isset($data['sort']) && $data['sort'] == 'o.pdate') {
            $sql .= " ORDER BY o.pdate ". $data['order']. ", o.order_id DESC";
        }
        else {
            $sql .= " ORDER BY o.date_added DESC";
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
        $sql = "SELECT o.order_id,o.shipping_time FROM `" . DB_PREFIX . "order` o";
/*, o.customer_id,o.pdate,o.p_order_id, CONCAT(o.firstname) AS customer,o.email, o.shipping_firstname AS username,o.shipping_time,(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified*/
      /*  if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        $sql .= " AND (o.pdate != '' or o.shipping_time!='') ";

        if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . $data['filter_order_id'] . "'";
        }

        if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
            $sql .= " AND CONCAT(email) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
            //	$sql .= " OR CONCAT(o.shipping_firstname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }
       if (isset($data['filter_customer_phone']) && !is_null($data['filter_customer_phone'])) {
        	$sql .= " AND CONCAT(o.telephone) LIKE '%" . $this->db->escape($data['filter_customer_phone']) . "%'";
        	//	$sql .= " OR CONCAT(o.shipping_firstname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_date_pick']) && !is_null($data['filter_date_pick'])) {

            $sql .= " AND (o.pdate = '" . $this->db->escape($data['filter_date_pick']) . "' or date_format(o.shipping_time,'%Y-%m-%d') = '" . $this->db->escape($data['filter_date_pick']) . "')";
             
        }

        if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
        }
*/
        
        $sql.=  $this->getfilters($data);
        
        
        $sort_data = array(
            'o.date_added',
            'o.pdate',
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

    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

        return $query->rows;
    }

    public function getOrderTotals($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "' ORDER BY sort_order");

        return $query->rows;
    }
    
    public function getOrderPayments($order_id) {
    	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_payment WHERE order_id = '" . $order_id . "' AND is_delete=0 ORDER BY order_payment_id desc");
    
    	return $query->rows;
    }


    public function getOrderDownloads($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . $order_id . "' ORDER BY name");

        return $query->rows;
    }

    public function getTotalOrders($data = array()) {
        $sql = "SELECT COUNT(1) AS total FROM `" . DB_PREFIX . "order` o";

        $sql.=  $this->getfilters($data);
        
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

    public function getTotalSales($date) {
        $query = $this->db->query("SELECT SUM(total) AS total ,count(1) as order_num,partner_code,DATE(date_added) as date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id not in( 0,10,11,7,16) AND date_added>'".$date."' GROUP BY partner_code,DATE(date_added) ORDER BY order_id DESC");
        return $query->rows;
    }

    public function getTotalSalesByYear($date) {
        $query = $this->db->query("SELECT SUM(total) AS total,count(1) as order_num,partner_code FROM `" . DB_PREFIX . "order` WHERE order_status_id not in( 0,10,11,7,16) AND date_added> '" . $date . "' GROUP BY partner_code");

        return $query->rows;
    }

    /**
     *  追加历史变更记录
     * @param unknown $order_id
     * @param unknown $data
     * @param unknown $operator
     */
    public function addOrderHistory($order_id, $data, $operator) {
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

        $data['express'] = '';
        $data['express_website'] = '';

        if (!isset($data['express_no'])) {
            $data['express_no'] = '';
        }
        
        $data['shipping_point_id']=$order_info['shipping_point_id'];
        $data['telephone']=$order_info['telephone'];
  //      if ($data['express'] != '')
  //          $this->db->query("UPDATE `" . DB_PREFIX . "order` SET express_no = '" . $this->db->escape($data['express_no']) . "', express = '" . $this->db->escape($data['express']) . "', express_website = '" . $this->db->escape($data['express_website']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . $order_id . "'");
  //      else
  
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET  order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . $order_id . "'");
        $this->log_order->info('addOrderHistory：:order_id:'.$order_id.':data:'.print_r($data,1));
        $this->insertHistoryData($order_id, $data, $operator);

        // Send out any gift voucher mails
        if ($this->config->get('config_complete_status_id') == $data['order_status_id']) {
            $this->load->model('sale/voucher');

            $results = $this->model_sale_voucher->getVouchersByOrderId($order_id);

            foreach ($results as $result) {
                $this->model_sale_voucher->sendVoucher($result['voucher_id']);
            }
        }
        
        
        if($data['order_status_id'] == '2'){//如果是改为已付款，则更新支付历史为已支付
        	$sql ="UPDATE " . DB_PREFIX . "order_payment
                   SET `status` = 1 WHERE order_id = '" . $order_id . "'";
        	$this->db->query($sql);
        }
        
        
     /* 系统自动退款请求*/
        if($data['order_status_id'] == '13')
        {
        if($data['payment_refund']){
        	$sql='';
        	foreach($data['payment_refund'] as $refund){
        		if($refund['checked']){
        			
        			if($refund['payment_code']=='returnback'){
        				$refund['payment_code']=$refund['payment_code1'];
        				$refund['payment_account']='';
        				$refund['payment_name']='';
        			}
        			

        			
        				$sql.="INSERT " . DB_PREFIX . "order_refund
                    SET `status` = 'PHASE1_PASSED',
        				`order_id`='{$order_id}',
        				`order_payment_id`='{$refund['order_payment_id']}',
        				`reason`='{$data['payment_refund_reason']}',
        				`payment_code`='{$refund['payment_code']}',
        				`value`='{$refund['value']}',
        				`phase1_user_name`='{$this->user->getUserName()}',
        				`phase1_updated_at`='".date('Y-m-d H:i:s')."',
        				`created_at`='".date('Y-m-d H:i:s')."',
        				`payment_account`='".trim($refund['payment_account'])."',
        				`payment_name`='".trim($refund['payment_name'])."'
        				;";

        		}
        	}	
        	$this->db->multi_query($sql);

$this->log_order->info('后台发起退款请求：'.serialize($data['payment_refund']));
        }

        	
        }
        

    }

    
    /**
     *  发送微信模板消息
     */
    private  function  sendWeixinMsg($order_id, $data){
    	//发送模板消息
    	$pdate = $data['pdate'];
    	$querynew = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");
    
    	$product=$querynew->rows;
    	$strs='';
    	foreach($product as $k=>$p){
    		$strs=$strs."\r\n".$p['name']."  "."数量".$p['quantity']."份"."\r\n";
    	}
    	$this->language->load('sms/order');
    	$pdate = explode("-", $pdate);
    	$pdate2 = (int)$pdate[1] . '月' . (int)$pdate[2] . '日';
    
    	$pickup_code = $data['pickup_code'];
    
    	$msgs = sprintf($this->language->get('text_order_sms'), $order_id, $pickup_code, $pdate2, $data['shipping_method']);
    	//发送微信模板消息功能
    
    
    	$com = new Common($this->registry);
    
    	$customer_id = $data['customer_id'];
    	$openid = $com->findOpenIdwithCustomerID($customer_id);
    
    	$this->log_admin->debug('IlexDebug:: sendWeixinMsg :openid '.':'. $openid.':'.$strs.':'.$msgs);
    	$this->log_admin->info(' sendWeixinMsg :openid '.':'. $openid.':'.$strs.':'.$msgs);
    	if(!empty($openid)) {
    		$this->log_admin->debug('IlexDebug:: sendWeixinMsg::start');
    		$template_id = 'qZT5nxRG97_TVOOwXzqaltgHmYBpx6yiSM-sMmvBtjU';
    		$url = 'http://www.qingniancaijun.com.cn/index.php?route=common/home';///$this->url->link('common/home');
    		$msg_data = array(
    				'name' => array(
    						'value' => $strs,
    						'color' => '#FF0000'
    				),
    				'remark' => array(
    						'value' => $msgs,
    						'color' => '#898989'
    				)
    		);
    		$this->log_admin->debug('IlexDebug:: sendWeixinMsg::end');
    		$this->log_admin->info(' sendWeixinMsg::end ');
			
		$this->load->service('weixin/interface','service');
		$this->service_weixin_interface->send_msg_by_weixin($openid, $template_id, $url, $msg_data);
//    		$com->send_msg_by_weixin($openid, $template_id, $url, $msg_data);
    	}
    
    }
    
    
    /**
     *  发送微信模板消息
     */
    private  function  sendWeixinMsgShipping($order_id, $data){
    	//发送模板消息
    	$querynew = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");
    
    	$product=$querynew->rows;
    	$strs='';
    	foreach($product as $k=>$p){
    		$strs=$strs."\r\n".$p['name']."  "."数量".$p['quantity']."份"."\r\n";
    	}
    	$this->language->load('sms/order');
    
    	$shiping_time =  strtotime($data['shipping_time']);
    	$hour1 = date('H:i', $shiping_time- 3600);
    	$hour2 = date('H:i', $shiping_time);
    	$pdate = date('m月d日', $shiping_time);
    
    	$pickup_code = $data['pickup_code'];
    
    	$msgs = sprintf($this->language->get('text_order_sms2'), $order_id, $pdate, $hour1,$hour2);
    
    	//发送微信模板消息功能
    	$com = new Common($this->registry);
    	$customer_id = $data['customer_id'];
    	 
    	$openid = $com->findOpenIdwithCustomerID($customer_id);
    	$this->log_admin->debug('IlexDebug:: sendWeixinMsgShipping :openid '.':'. $openid.':'.$strs.'-'.$msgs);
    	$this->log_admin->info(' sendWeixinMsgShipping :openid '.':'. $openid.':'.$strs.'-'.$msgs);
    	if ($openid) {
    		$template_id = 'qZT5nxRG97_TVOOwXzqaltgHmYBpx6yiSM-sMmvBtjU';
    		$url = 'http://www.qingniancaijun.com.cn/index.php?route=common/home';///$this->url->link('common/home');
    		$msg_data = array(
    				'name' => array(
    						'value' => $strs,
    						'color' => '#FF0000'
    				),
    				'remark' => array(
    						'value' => $msgs,
    						'color' => '#898989'
    				)
    		);
		
		$this->load->service('weixin/interface','service');
		$this->service_weixin_interface->send_msg_by_weixin($openid, $template_id, $url, $msg_data);
//    		$com->send_msg_by_weixin($openid, $template_id, $url, $msg_data);
    	}
    
    }
    
    
    /**
     * 发送订单信息短信
     * @param unknown $order_id
     * @param unknown $pdate
     * @param unknown $paddr
     * @param unknown $order_info
     * @return boolean
     */
    private function sendSms($order_id, $order_info) {
        //$language = new Language($order_info['language_directory']);
        //$language->load($order_info['language_filename']);
        $this->language->load('sms/order');
    
        if (SMS_OPEN == 'ON') {
            $mobile_no = $order_info['telephone'];
            if ($mobile_no != '' && SMS_OPEN == 'ON') {
                $mobilephone = trim($mobile_no);
                //手机号码的正则验证
                if (mobile_check($mobilephone)) {
                    // send sms
                    $sms=new Sms();
    
                    $pdate = explode("-", $order_info['pdate']);
                    $pdate2 = (int)$pdate[1] . '月' . (int)$pdate[2] . '日';
    
                    $pickup_code = $order_info['pickup_code'];
    
                    $msg = sprintf($this->language->get('text_order_sms'), $order_id, $pickup_code, $pdate2, $order_info['shipping_method']);
    
                    $this->log_admin->debug('IlexDebug:: 发送短信: ' . $msg);
    
                    $msg =$msg;
                    $sms->send($mobilephone, $msg);
    
                    $this->log_admin->debug('IlexDebug::Already Sended SMS for order ' . $order_id);
                    $this->log_admin->debug('IlexDebug::Already Sended SMS ' . $mobilephone . ',content ' . $msg);
                    $this->log_admin->info('Already Sended SMS ' . $mobilephone . ',content ' . $msg);
                    return true;
                } else {
                    //手机号码格式不对
                    $this->log_admin->debug('IlexDebug:: Wrong Number,dun send sms : sub_order_id ' . $order_id);
                    $this->log_admin->info('Wrong Number,dun send sms : sub_order_id ' . $order_id);
                    return false;
                }
            }
        } else {
            $this->log_admin->debug('IlexDebug:: SMS_OPEN :' . SMS_OPEN . ';sub_order_id:' . $order_id);
            return false;
        }
    }
    /**
     * 发送订单成功短信
     * @param unknown $order_id
     * @param unknown $pdate
     * @param unknown $paddr
     * @param unknown $order_info
     * @return boolean
     */
    private function sendSmsShipping($order_id, $order_info) {
    	//$language = new Language($order_info['language_directory']);
    	//$language->load($order_info['language_filename']);
    	$this->language->load('sms/order');
    
    	if (SMS_OPEN == 'ON') {
    		$this->log_admin->debug('IlexDebug:: Send SMS for order ' . $order_id);
    		$mobile_no = $order_info['telephone'];
    		if ($mobile_no != '' && SMS_OPEN == 'ON') {
    			$mobilephone = trim($mobile_no);
    			//手机号码的正则验证
    			if (mobile_check($mobilephone)) {
    				// send sms
    				$sms=new Sms();
    				$shiping_time =  strtotime($order_info['shipping_time']);
    				$hour1 = date('H:i', $shiping_time- 3600);
    				$hour2 = date('H:i', $shiping_time);
    				$pdate = date('m月d日', $shiping_time);
    
    				$pickup_code = $order_info['pickup_code'];
    
    				$msg = sprintf($this->language->get('text_order_sms2'), $order_id, $pdate, $hour1,$hour2);
    				 
    				$msg =$msg;
    				$sms->send($mobilephone, $msg);
    
    				//$this->log_admin->debug('IlexDebug::Already Sended SMS for order ' . $order_id);
    				$this->log_admin->debug('IlexDebug::Already Sended SMS ' . $mobilephone . ',content ' . $msg);
    				$this->log_admin->info('Already Sended SMS ' . $mobilephone . ',content ' . $msg);
    				return true;
    			} else {
    				//手机号码格式不对
    				$this->log_admin->debug('IlexDebug:: Wrong Number,dun send sms : sub_order_id ' . $order_id);
    				$this->log_admin->info('Wrong Number,dun send sms : sub_order_id ' . $order_id);
    				return false;
    			}
    		}
    	} else {
    		$this->log_admin->debug('IlexDebug:: SMS_OPEN :' . SMS_OPEN . ';sub_order_id:' . $order_id);
    		return false;
    	}
    }
    public function getSubOrders($p_order_id, $data = array()) {
        $sql = "SELECT o.order_id,o.p_order_id,o.pdate, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value,o.order_status_id, o.shipping_firstname as shipping_name FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.p_order_id = '" . (int)$p_order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id')
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
        $query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify, oh.operator FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . $order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added desc LIMIT " . (int)$start . "," . (int)$limit);

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

    /**
     * @param $product_id
     * @return null
     */
    private function getProductDescription($product_id) {
        return DbHelper::get('product_description', array(array('product_id', $product_id, false), array('language_id', (int)$this->config->get('config_language_id'), false)));
    }


    public function getOrderShistories($order_id, $start = 0, $limit = 10) {
        $query = $this->db->query("SELECT date_added,comment,operator FROM " . DB_PREFIX . "service_history WHERE order_id = '" . $order_id . "'  ORDER BY date_added desc LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

}

?>
