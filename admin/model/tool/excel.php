<?php
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


class ModelToolExcel extends Model {
    private function getCell(&$worksheet, $row, $col, $default_val = '') {
        $col -= 1; // we use 1-based, PHPExcel uses 0-based column index
        $row += 1; // we use 0-based, PHPExcel used 1-based row index
        return ($worksheet->cellExistsByColumnAndRow($col, $row)) ? $worksheet->getCellByColumnAndRow($col, $row)->getValue() : $default_val;
    }

    protected function init() {
        set_error_handler('error_handler_for_export', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export');

        ini_set("memory_limit", "512M");
        ini_set("max_execution_time", 180);
    }


    public function exportDistributionProducts1($filename, $sheet, $products, $point = array()) {
        //初始化
        $this->init();

        //set_time_limit( 60 );
        chdir('../system/PHPExcel');
        require_once('Classes/PHPExcel.php');
        chdir('../../admin');

        $objPHPExcel = PHPExcel_IOFactory::load($filename);

        $objPHPExcel->setActiveSheetIndex($sheet);


        /* 自动补全门店数据 */
        if ($point) {
            $objPHPExcel->getActiveSheet()->setCellValue('C2', $point['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C3', $point['address']);
            $objPHPExcel->getActiveSheet()->setCellValue('H2', ' '.$point['point_code']);
        }

        /* 从B15行开始 依次输入BCFGH字段值 填充产品 */
        $row = 15;

        $count_products = COUNT($products);

        if ($count_products > 20) {

            $new_rows = $count_products - 20;

            $start_line = 16;

            $objPHPExcel->getActiveSheet()->insertNewRowBefore($start_line, $new_rows);

            for ($i = 0; $i < $new_rows; $i++) {
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(2, $start_line + $i, 4, $start_line + $i);
            }
        }


        foreach ($products as $index => $product) {
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $index + 1);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $product['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $product['num']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $product['unit']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $product['comment']);

            $row++;
        }

        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $filename = urlencode("distribution1" . '_' . date('Y-m-d', time()) . ".xls");

        $outputFileName = $filename;//自行设置路径

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');
        header("Content-Transfer-Encoding:binary");

		$objWriter->save('php://output');
//        $objWriter->save('/Users/joecliff/Downloads/test/temp.xls');

        return true;

    }

    public function exportDistributionProducts2($filename, $sheet, $products, $point = array()) {
        //初始化
        $this->init();

        //set_time_limit( 60 );
        chdir('../system/PHPExcel');
        require_once('Classes/PHPExcel.php');
        chdir('../../admin');

        $objPHPExcel = PHPExcel_IOFactory::load($filename);

        $objPHPExcel->setActiveSheetIndex($sheet);


        /* 自动补全门店数据 */
        if ($point) {
            $objPHPExcel->getActiveSheet()->setCellValue('C2', $point['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C3', $point['address']);
            $objPHPExcel->getActiveSheet()->setCellValue('H2', ' '.$point['point_code']);
        }

        /* 从B15行开始 依次输入BCFGH字段值 填充产品 */
        $row = 15;

        $count_products = COUNT($products);

        if ($count_products > 20) {

            $new_rows = $count_products - 20;

            $start_line = 16;

            $objPHPExcel->getActiveSheet()->insertNewRowBefore($start_line, $new_rows);

            for ($i = 0; $i < $new_rows; $i++) {
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $start_line + $i, 4, $start_line + $i);
            }
        }


        foreach ($products as $index => $product) {
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $index + 1);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $product['order_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $product['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $product['num']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $product['customer_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $product['telephone']);

            $row++;
        }

        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $filename = urlencode("distribution2" . '_' . date('Y-m-d', time()) . ".xls");

        $outputFileName = $filename;//自行设置路径

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');
        header("Content-Transfer-Encoding:binary");

		$objWriter->save('php://output');
//        $objWriter->save('/Users/joecliff/Downloads/test/temp.xls');

        return true;

    }


    public function createWorkbook() {
        $this->init();

        chdir('../system/pear');
        require_once "Spreadsheet/Excel/Writer.php";
        chdir('../../admin');

        // Creating a workbook
        $workbook = new Spreadsheet_Excel_Writer();
        $workbook->setTempDir(DIR_CACHE);
        $workbook->setVersion(8); // Use Excel97/2000 Format

        return $workbook;

    }

    public function scanSheet($filename, $sheet, $mapping = array(), $start_row = 0) {
        $this->init();

        //set_time_limit( 60 );
        chdir('../system/PHPExcel');
        require_once('Classes/PHPExcel.php');
        chdir('../../admin');

        $inputFileType = PHPExcel_IOFactory::identify($filename);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $reader = $objReader->load($filename);

        $data = $reader->getSheet($sheet);

        $k = $data->getHighestRow();

        $results = array();

        for ($i = $start_row; $i < $k; $i++) {
            $result = array();

            foreach ($mapping as $index => $field) {
                $result[$field] = $this->getCell($data, $i, $index + 1);
            }

            array_push($results, $result);
        }

        return $results;
    }


    private $code = array(
        'en' => 'en',
        'jp' => 'ja'
    );

    function clean(&$str, $allowBlanks = FALSE) {
        $result = "";
        $n = strlen($str);
        for ($m = 0; $m < $n; $m++) {
            $ch = substr($str, $m, 1);
            if (($ch == " ") && (!$allowBlanks) || ($ch == "\n") || ($ch == "\r") || ($ch == "\t") || ($ch == "\0") || ($ch == "\x0B")) {
                continue;
            }
            $result .= $ch;
        }
        return $result;
    }


    function import(&$database, $sql) {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);
            if ($sql) {
                $database->query($sql);
            }
        }
    }


    protected function getDefaultLanguageId(&$database) {
        $code = $this->config->get('config_language');
        $sql = "SELECT language_id FROM `" . DB_PREFIX . "language` WHERE code = '$code'";
        $result = $database->query($sql);
        $languageId = 1;
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $languageId = $row['language_id'];
                break;
            }
        }
        return $languageId;
    }

    // added this for getting language code
    protected function getLanguageId(&$database, $code) {
        $sql = "SELECT language_id FROM `" . DB_PREFIX . "language` WHERE code = '$code'";
        $result = $database->query($sql);

        $languageId = 0;

        if ($result->row) {
            $languageId = $result->row['language_id'];
        }

        return $languageId;
    }


    protected function getDefaultWeightUnit() {
        $weightUnit = $this->config->get('config_weight_class');
        return $weightUnit;
    }


    protected function getDefaultMeasurementUnit() {
        $measurementUnit = $this->config->get('config_length_class');
        return $measurementUnit;
    }


    function storeManufacturersIntoDatabase(&$database, &$products, &$manufacturerIds) {
        // find all manufacturers already stored in the database

        $sql = "START TRANSACTION;\n";
        $sql .= "DELETE FROM `" . DB_PREFIX . "manufacturer` ;\n";
        $sql .= "DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` = 'manufacturer_id=%';\n";
        $this->import($database, $sql);

        $sql = "SELECT `manufacturer_id`, `name` FROM `" . DB_PREFIX . "manufacturer`;";
        $result = $database->query($sql);
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $manufacturerId = $row['manufacturer_id'];
                $name = $row['name'];
                if (!isset($manufacturerIds[$name])) {
                    $manufacturerIds[$name] = $manufacturerId;
                } else if ($manufacturerIds[$name] < $manufacturerId) {
                    $manufacturerIds[$name] = $manufacturerId;
                }
            }
        }

        // add newly introduced manufacturers to the database
        $maxManufacturerId = 0;
        foreach ($manufacturerIds as $manufacturerId) {
            $maxManufacturerId = max($maxManufacturerId, $manufacturerId);
        }
        $sql = "INSERT INTO `" . DB_PREFIX . "manufacturer` (`manufacturer_id`, `name`, `image`, `sort_order`) VALUES ";
        $k = strlen($sql);
        $first = TRUE;
        foreach ($products as $product) {
            $manufacturerName = $product[7];
            if ($manufacturerName == "") {
                continue;
            }
            if (!isset($manufacturerIds[$manufacturerName])) {
                $maxManufacturerId += 1;
                $manufacturerId = $maxManufacturerId;
                $manufacturerIds[$manufacturerName] = $manufacturerId;
                $sql .= ($first) ? "\n" : ",\n";
                $first = FALSE;
                $sql .= "($manufacturerId, '" . $database->escape($manufacturerName) . "', '', 0)";
            }
        }
        $sql .= ";\n";
        if (strlen($sql) > $k + 2) {
            $database->query($sql);
        }

        // populate manufacturer_to_store table

        foreach ($products as $product) {
            $manufacturerName = $product[7];
            if ($manufacturerName == "") {
                continue;
            }
            $manufacturerId = $manufacturerIds[$manufacturerName];
            $sql2_1 = "DELETE FROM  `" . DB_PREFIX . "manufacturer_to_store` WHERE `manufacturer_id`=" . $manufacturerId;;
            $database->query($sql2_1);
            $sql2 = "INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` (`manufacturer_id`,`store_id`) VALUES ($manufacturerId,0);";
            $database->query($sql2);
        }
        $database->query("COMMIT;");
        return TRUE;
    }


    function getWeightClassIds(&$database) {
        // find the default language id
        $languageId = $this->getDefaultLanguageId($database);

        // find all weight classes already stored in the database
        $weightClassIds = array();
        $sql = "SELECT `weight_class_id`, `unit` FROM `" . DB_PREFIX . "weight_class_description` WHERE `language_id`=$languageId;";
        $result = $database->query($sql);
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $weightClassId = $row['weight_class_id'];
                $unit = $row['unit'];
                if (!isset($weightClassIds[$unit])) {
                    $weightClassIds[$unit] = $weightClassId;
                }
            }
        }

        return $weightClassIds;
    }


    function getLengthClassIds(&$database) {
        // find the default language id
        $languageId = $this->getDefaultLanguageId($database);

        // find all length classes already stored in the database
        $lengthClassIds = array();
        $sql = "SELECT `length_class_id`, `unit` FROM `" . DB_PREFIX . "length_class_description` WHERE `language_id`=$languageId;";
        $result = $database->query($sql);
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $lengthClassId = $row['length_class_id'];
                $unit = $row['unit'];
                if (!isset($lengthClassIds[$unit])) {
                    $lengthClassIds[$unit] = $lengthClassId;
                }
            }
        }

        return $lengthClassIds;
    }

    function checkProductExisted($sku) {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product`  WHERE `sku`='$sku'";

        $query = $this->db->query($sql);

        if ($query->row['total'] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    function storeProductsIntoDatabase(&$reader, &$database, &$products) {
        // find the default language id
        $languageId = $this->getDefaultLanguageId($database);
        $languageId_2 = $this->getLanguageId($database, $this->code['jp']);

        // start transaction, remove products
        $sql = "START TRANSACTION;\n";

        // store or update manufacturers
        /*$manufacturerIds = array();
                	$ok = $this->storeManufacturersIntoDatabase( $database, $products, $manufacturerIds );
                	if (!$ok) {
                		$database->query( 'ROLLBACK;' );
                			
                		return FALSE;
                	}*/

        // get weight classes
        $weightClassIds = $this->getWeightClassIds($database);

        // get length classes
        $lengthClassIds = $this->getLengthClassIds($database);

        // generate and execute SQL for storing the products
        foreach ($products as $product) {

//                		$productName_2 = $database->escape($product[2]);

//                		$categories =  $product[4];
//                		$this->log_sys->info('inner categories ---- '.$categories.' [] '.$product[4]);


//                		$manufacturerName = $product[7];
//                		$manufacturerId = ($manufacturerName=="") ? 0 : $manufacturerIds[$manufacturerName];


//                		$shipping = ((strtoupper($shipping)=="YES") || (strtoupper($shipping)=="Y")) ? 1 : 0;


//                		$dateAvailable = $product[11];

//                		$weight = ($product[12]=="") ? 0 : $product[12];
//                		$unit = $product[13];
//                		$weightClassId = (isset($weightClassIds[$unit])) ? $weightClassIds[$unit] : 0;
//                		$length = $product[14];
//                		$width = $product[15];
//                		$height = $product[16];
//                		$lengthUnit = $product[17];
//                		$lengthClassId = (isset($lengthClassIds[$lengthUnit])) ? $lengthClassIds[$lengthUnit] : 0;
//                		$status = $product[18];
//                		$status = ((strtoupper($status)=="TRUE") || (strtoupper($status)=="YES") || (strtoupper($status)=="ENABLED")) ? 1 : 0;
//                		$taxClassId = $product[19];

//                		$keyword = $database->escape($product[20]);


//                		$productDescription_2 = $database->escape($product[22]);
//                		$meta_description = $database->escape($product[23]);
//                		$meta_description_2 = $database->escape($product[24]);
//                		$meta_keywords = $database->escape($product[25]);
//                		$meta_keywords_2 = $database->escape($product[26]);
//                		$images=	$product[27];
//                		$stockStatusId = $product[28];
//                		$related = $product[29];
//                		$tags = array();
//                		foreach ($product[30] as $tag) {
//                			$tags[] = $database->escape($tag);
//                		}
//                		$tags_2 = array();
//                		foreach ($product[31] as $tag) {
//                			$tags_2[] = $database->escape($tag);
//                		}
//                		$sort_order = $product[32];
//                		$subtract = $product[33];
//                		$subtract = ((strtoupper($subtract)=="TRUE") || (strtoupper($subtract)=="YES") || (strtoupper($subtract)=="ENABLED")) ? 1 : 0;
//                		
//                		$cost = trim($product[35]);

            $name = $database->escape($product['name']);
            $description = $database->escape($product['description']);
            $meta_description = '';
            $meta_keyword = '';

            $sku = $database->escape($product['sku']);
            $upc = $database->escape($product['upc']);
            $quantity = $product['quantity'];
            $model = $database->escape($product['model']);
            $image = '';

            $price = trim($product['price']);
            $special = trim($product['special']);

            $date_added = ' NOW() ';
            $date_modified = ' NOW() ';
            $date_available = ' NOW() ';

            $cas = $database->escape($product['cas']);
            $delivery_time = $database->escape($product['delivery_time']);
            $manufacturer = $database->escape($product['manufacturer']);

            $manufacturer_id = $this->getManufacturerId($database, $manufacturer);

            $mdl = $database->escape($product['mdl']);
            $formula = $database->escape($product['formula']);
            $molecular = $database->escape($product['molecular']);
            $level = $database->escape($product['level']);
            $purity = $database->escape($product['purity']);
            $package = $database->escape($product['package']);
            $size = $database->escape($product['size']);


            $category_id = $database->escape($product['category_id']);

            $status = 1;
            $minimum = 1;
            $subtract = 1;
            $stock_status_id = 7;
            $shipping = 1;
            $tax_class_id = 0;
            $sort_order = 1;
            $length = '';
            $width = '';
            $height = '';
            $weight = '';
            $length_class_id = 0;
            $weight_class_id = 0;

            if (!$this->checkProductExisted($sku)) {
                $viewed = 0;
                $location = '';

                $sql = "INSERT INTO `" . DB_PREFIX . "product` (`quantity`,`sku`,`cas`,`mdl`,`formula`,`molecular`,`level`,`purity`,`package`,`delivery_time`,";
                $sql .= "`upc`,`model`,`manufacturer_id`,`image`,`shipping`,`price`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`status`,";
                $sql .= "`tax_class_id`,`viewed`,`length`,`width`,`height`,`length_class_id`,`sort_order`,`subtract`,`minimum`) VALUES ";
                $sql .= "($quantity,'$sku','$cas','$mdl','$formula','$molecular','$level','$purity','$package','$delivery_time',";
                $sql .= "'$upc','$model',$manufacturer_id,'$image',$shipping,$price,";
                $sql .= ($date_added == 'NOW()') ? "$date_added," : " $date_added,";
                $sql .= ($date_modified == 'NOW()') ? "$date_modified," : "$date_modified,";
                $sql .= ($date_available == 'NOW()') ? "$date_available," : "$date_available,";
                $sql .= "'$weight',$weight_class_id,$status,";
                $sql .= "$tax_class_id,$viewed,'$length','$width','$height','$length_class_id','$sort_order','$subtract','$minimum');";

                $database->query($sql);

                $sql_product_id = "SELECT MAX(product_id) AS product_id  FROM `" . DB_PREFIX . "product`";

                $product_id_row = $database->query($sql_product_id);

                $productId = $product_id_row->row['product_id'];

                $sql2 = "INSERT INTO `" . DB_PREFIX . "product_description` (`product_id`,`language_id`,`name`,`description`,`meta_description`,`meta_keyword`) VALUES ";
                $sql2 .= "($productId,$languageId,'$name','$description','$meta_description','$meta_keyword');";

                $database->query($sql2);


                if ($category_id) {
                    $sql3 = "INSERT INTO `" . DB_PREFIX . "product_to_category` (`product_id`,`category_id`) VALUES ";
                    $sql3 .= "($productId,'$category_id');";

                    $database->query($sql3);
                }

//                			$sql3 = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`,`language_id`,`name`,`description`,`meta_description`,`meta_keywords`) VALUES ";
//                			$sql3 .= "($productId,$languageId_2,'$productName_2','$productDescription_2','$meta_description_2','$meta_keywords_2');";

//                			$database->query($sql3);


//                			if (count($categories) > 0) {
//                				$sql = "INSERT INTO `".DB_PREFIX."product_to_category` (`product_id`,`category_id`) VALUES ";
//                				$first = TRUE;
//                				foreach ($categories as $categoryId) {
//                					$sql .= ($first) ? "\n" : ",\n";
//                					$first = FALSE;
//                					$sql .= "($productId,$categoryId)";
//                				}
//                				$sql .= ";";
//                			//$this->log_sys->info($sql);
//                				$database->query($sql);
//                			}

//                			if ($keyword) {
//                				$sql4 = "INSERT INTO `".DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('product_id=$productId','$keyword');";
//                				$database->query($sql4);
//                			}

                //foreach ($storeIds as $storeId) {
                $storeId = 0;
                $sql6 = "INSERT INTO `" . DB_PREFIX . "product_to_store` (`product_id`,`store_id`) VALUES ($productId,$storeId);";
                $database->query($sql6);


                //}
//                			if (count($related) > 0) {
//                				$sql = "INSERT INTO `".DB_PREFIX."product_related` (`product_id`,`related_id`) VALUES ";
//                				$first = TRUE;
//                				foreach ($related as $relatedId) {
//                					$sql .= ($first) ? "\n" : ",\n";
//                					$first = FALSE;
//                					$sql .= "($productId,$relatedId)";
//                				}
//                				$sql .= ";";
//                				$database->query($sql);
//                			}
//                			if (count($tags) > 0) {
//                				$sql = "INSERT INTO `".DB_PREFIX."product_tags` (`product_id`,`tag`,`language_id`) VALUES ";
//                				$first = TRUE;
//                				$inserted_tags = array();
//                				foreach ($tags as $tag) {
//                					if ($tag == '') {
//                						continue;
//                					}
//                					if (in_array($tag,$inserted_tags)) {
//                						continue;
//                					}
//                					$sql .= ($first) ? "\n" : ",\n";
//                					$first = FALSE;
//                					$sql .= "($productId,'".$database->escape($tag)."',$languageId)";
//                					$inserted_tags[] = $tag;
//                				}
//                				$sql .= ";";
//                				if (count($inserted_tags)>0) {
//                					$database->query($sql);
//                				}
//                			}

//                			if (count($tags_2) > 0) {
//                				$sql = "INSERT INTO `".DB_PREFIX."product_tags` (`product_id`,`tag`,`language_id`) VALUES ";
//                				$first = TRUE;
//                				$inserted_tags = array();
//                				foreach ($tags_2 as $tag) {
//                					if ($tag == '') {
//                						continue;
//                					}
//                					if (in_array($tag,$inserted_tags)) {
//                						continue;
//                					}
//                					$sql .= ($first) ? "\n" : ",\n";
//                					$first = FALSE;
//                					$sql .= "($productId,'".$database->escape($tag)."',$languageId_2)";
//                					$inserted_tags[] = $tag;
//                				}
//                				$sql .= ";";
//                				if (count($inserted_tags)>0) {
//                					$database->query($sql);
//                				}
//                			}


            } else {
                continue;

                // update product
                // start transaction, remove products
                /*$sql = "START TRANSACTION;\n";
                			$sql .= "DELETE FROM `".DB_PREFIX."product` WHERE `product_id` =$productId;\n";
                			$sql .= "DELETE FROM `".DB_PREFIX."product_description` WHERE `product_id` =$productId;\n";
                			$sql .= "DELETE FROM `".DB_PREFIX."product_to_category` WHERE `product_id` =$productId;\n";
                			$sql .= "DELETE FROM `".DB_PREFIX."product_to_store` WHERE `product_id` =$productId;\n";

                			$sql .= "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query` = 'product_id=$productId';\n";
                			$sql .= "DELETE FROM `".DB_PREFIX."product_related` WHERE `product_id` =$productId;\n";
                			$sql .= "DELETE FROM `".DB_PREFIX."product_tags` WHERE `product_id` =$productId;\n";

                			$this->import( $database, $sql );
                			
                			$sql  = "INSERT INTO `".DB_PREFIX."product` (`product_id`,`quantity`,`sku`,";
                			$sql .= "`stock_status_id`,`model`,`manufacturer_id`,`image`,`shipping`,`price`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`status`,";
                			$sql .= "`tax_class_id`,`length`,`width`,`height`,`length_class_id`,`sort_order`,`subtract`,`minimum`,`cost`) VALUES ";
                			$sql .= "($productId,$quantity,'$sku',";
                			$sql .= "$stockStatusId,'$model',$manufacturerId,'$imageName',$shipping,$price,";
                			$sql .= ($dateAdded=='NOW()') ? "$dateAdded," : " $dateAdded ,";
                			$sql .= ($dateModified=='NOW()') ? "$dateModified," : "$dateModified,";
                			$sql .= ($dateAvailable=='NOW()') ? "$dateAvailable," : "$dateAvailable,";
                			$sql .= "$weight,$weightClassId,$status,";
                			$sql .= "$taxClassId,$length,$width,$height,'$lengthClassId','$sort_order','$subtract','$minimum',$cost);";

                			$database->query($sql);
							//throw new Exception($sql);
                			$sql2 = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`,`language_id`,`name`,`description`,`meta_description`,`meta_keywords`) VALUES ";
                			$sql2 .= "($productId,$languageId,'$productName','$productDescription','$meta_description','$meta_keywords');";

                			$sql3 = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`,`language_id`,`name`,`description`,`meta_description`,`meta_keywords`) VALUES ";
                			$sql3 .= "($productId,$languageId_2,'$productName_2','$productDescription_2','$meta_description_2','$meta_keywords_2');";

                			$database->query($sql2);
                			$database->query($sql3);
							$this->log_sys->info('count( categories) '.count($categories));
                			if (count($categories) > 0) {
                				$sql = "INSERT INTO `".DB_PREFIX."product_to_category` (`product_id`,`category_id`) VALUES ";
                				$first = TRUE;
                				foreach ($categories as $categoryId) {
                					$sql .= ($first) ? "\n" : ",\n";
                					$first = FALSE;
                					$sql .= "($productId,$categoryId)";
                				}
                				$sql .= ";";
                				//$this->log_sys->info($sql);
                				$database->query($sql);
                			}
                			if ($keyword) {
                				$sql4 = "INSERT INTO `".DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('product_id=$productId','$keyword');";
                				$database->query($sql4);
                			}
                			//foreach ($storeIds as $storeId) {
                			$storeId=0;
                			$sql6 = "INSERT INTO `".DB_PREFIX."product_to_store` (`product_id`,`store_id`) VALUES ($productId,$storeId);";
                			$database->query($sql6);
                			//}
                			if (count($related) > 0) {
                				$sql = "INSERT INTO `".DB_PREFIX."product_related` (`product_id`,`related_id`) VALUES ";
                				$first = TRUE;
                				foreach ($related as $relatedId) {
                					$sql .= ($first) ? "\n" : ",\n";
                					$first = FALSE;
                					$sql .= "($productId,$relatedId)";
                				}
                				$sql .= ";";
                				$database->query($sql);
                			}
                			if (count($tags) > 0) {
                				$sql = "INSERT INTO `".DB_PREFIX."product_tags` (`product_id`,`tag`,`language_id`) VALUES ";
                				$first = TRUE;
                				$inserted_tags = array();
                				foreach ($tags as $tag) {
                					if ($tag == '') {
                						continue;
                					}
                					if (in_array($tag,$inserted_tags)) {
                						continue;
                					}
                					$sql .= ($first) ? "\n" : ",\n";
                					$first = FALSE;
                					$sql .= "($productId,'".$database->escape($tag)."',$languageId)";
                					$inserted_tags[] = $tag;
                				}
                				$sql .= ";";
                				if (count($inserted_tags)>0) {
                					$database->query($sql);
                				}
                			}

                			if (count($tags_2) > 0) {
                				$sql = "INSERT INTO `".DB_PREFIX."product_tags` (`product_id`,`tag`,`language_id`) VALUES ";
                				$first = TRUE;
                				$inserted_tags = array();
                				foreach ($tags_2 as $tag) {
                					if ($tag == '') {
                						continue;
                					}
                					if (in_array($tag,$inserted_tags)) {
                						continue;
                					}
                					$sql .= ($first) ? "\n" : ",\n";
                					$first = FALSE;
                					$sql .= "($productId,'".$database->escape($tag)."',$languageId_2)";
                					$inserted_tags[] = $tag;
                				}
                				$sql .= ";";
                				if (count($inserted_tags)>0) {
                					$database->query($sql);
                				}
                			}*/
            }

            //	$this->storeAdditionalImagesIntoDatabase($reader,$database,$productId,$images);
        }

        // final commit
        $database->query("COMMIT;");
        return TRUE;
    }


    protected function detect_encoding($str) {
        // auto detect the character encoding of a string
        return mb_detect_encoding($str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R');
    }


    function uploadProducts(&$reader, &$database, $category_id = '') {
        // find the default language id and default units
        $languageId = $this->getDefaultLanguageId($database);

        $defaultWeightUnit = $this->getDefaultWeightUnit();
        $defaultMeasurementUnit = $this->getDefaultMeasurementUnit();
        $defaultStockStatusId = $this->config->get('config_stock_status_id');

        $data = $reader->getSheet(0);

        $products = array();
        $product = array();
        $isFirstRow = TRUE;
        $k = $data->getHighestRow();

        $mapping = array(
            'name' => '商品名称',
            'upc' => '原始编号',
            'sku' => '商品编号',
            'price' => '市场价格',
            'special' => '优惠价格',
            'delivery_time' => '货期',
            'manufacturer' => '品牌',
            'model' => '型号',
            'quantity' => '商品数量',
            'cas' => 'CAS号',
            'mdl' => 'MDL号',
            'formula' => '分子式',
            'molecular' => '分子量',
            'level' => '等级',
            'purity' => '纯度',
            'size' => '包装规格',
            'description' => '商品介绍',
            'package' => '包装清单',
        );

        for ($i = 1; $i < $k; $i++) {
//                		if ($isFirstRow) {
//                			$isFirstRow = FALSE;
//                			continue;
//                		}

//                		$productId = trim($this->getCell($data,$i,1));

            $name = $this->getCell($data, $i, 1);
            $name = htmlentities($name, ENT_QUOTES, $this->detect_encoding($name));

            $upc = $this->getCell($data, $i, 2, '');
            $sku = $this->getCell($data, $i, 3, '');
            $price = $this->getCell($data, $i, 4, '0.00');
            $special = $this->getCell($data, $i, 5, '0.00');
            $delivery_time = $this->getCell($data, $i, 6, '');
            $manufacturer = $this->getCell($data, $i, 7, '');
            $model = $this->getCell($data, $i, 8, '');
            $quantity = $this->getCell($data, $i, 9, '0');
            $cas = $this->getCell($data, $i, 10, '');
            $mdl = $this->getCell($data, $i, 11, '');
            $formula = $this->getCell($data, $i, 12, '');
            $molecular = $this->getCell($data, $i, 13, '');
            $level = $this->getCell($data, $i, 14, '');
            $purity = $this->getCell($data, $i, 15, '');
            $size = $this->getCell($data, $i, 16, '');

            $description = $this->getCell($data, $i, 17);
            $description = htmlentities($description, ENT_QUOTES, $this->detect_encoding($description));

            $package = $this->getCell($data, $i, 18);
            $package = htmlentities($description, ENT_QUOTES, $this->detect_encoding($package));

            $product = array();

//                		$product[0] = $productId;
            $product['name'] = $name;
            $product['upc'] = $upc;
            $product['sku'] = $sku;
            $product['price'] = $price;
            $product['special'] = $special;
            $product['delivery_time'] = $delivery_time;
            $product['manufacturer'] = $manufacturer;
            $product['model'] = $model;
            $product['quantity'] = $quantity;
            $product['cas'] = $cas;
            $product['mdl'] = $mdl;
            $product['formula'] = $formula;
            $product['molecular'] = $molecular;
            $product['level'] = $level;
            $product['purity'] = $purity;
            $product['size'] = $size;
            $product['description'] = $description;
            $product['package'] = $package;

            $product['category_id'] = $category_id;


            array_push($products, $product);
        }

        return $this->storeProductsIntoDatabase($reader, $database, $products);
    }


    function storeCategoriesIntoDatabase(&$database, &$categories) {
        // find  language id
        $languageId = $this->getLanguageId($database, $this->code['en']);
        $languageId_2 = $this->getLanguageId($database, $this->code['jp']);

        // start transaction, remove categories
        $sql = "START TRANSACTION;\n";
        //$this->import( $database, $sql );

        // generate and execute SQL for inserting the categories
        foreach ($categories as $category) {
            $categoryId = $category[0];
            $imageName = $category[1];
            $parentId = $category[2];
            $sortOrder = $category[3];
            $dateAdded = "NOW()";
            $dateModified = "NOW()";

            $name = $database->escape($category[4]);
            $name_2 = $database->escape($category[5]);

            $description = $database->escape($category[6]);
            $description_2 = $database->escape($category[7]);
            $meta_description = $database->escape($category[8]);
            $meta_description_2 = $database->escape($category[9]);
            $meta_keywords = $database->escape($category[10]);
            $meta_keywords_2 = $database->escape($category[11]);
            $keyword = $database->escape($category[12]);
            $storeIds = $category[12];
            $status = $category[13];
            $status = ((strtoupper($status) == "TRUE") || (strtoupper($status) == "YES") || (strtoupper($status) == "ENABLED")) ? 1 : 0;
            if ($categoryId != '' || $categoryId != 0) {

                // UPDAET category
                $sql = "START TRANSACTION;\n";
                $sql .= "DELETE FROM `" . DB_PREFIX . "category` WHERE `category_id` =$categoryId;\n";
                $sql .= "DELETE FROM `" . DB_PREFIX . "category_description` WHERE `category_id` =$categoryId;\n";
                $sql .= "DELETE FROM `" . DB_PREFIX . "category_to_store` WHERE `category_id` =$categoryId;\n";
                $sql .= "DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` = 'category_id=$categoryId';\n";
                $this->import($database, $sql);


                $sql2 = "INSERT INTO `" . DB_PREFIX . "category` (`category_id`, `image`, `parent_id`, `sort_order`, `date_added`, `date_modified`, `status`) VALUES ";
                $sql2 .= "( $categoryId, '$imageName', $parentId, $sortOrder, ";
                $sql2 .= "'$dateAdded',";
                $sql2 .= "'$dateModified',";
                $sql2 .= " $status);";
                $database->query($sql2);


                $sql3 = "INSERT INTO `" . DB_PREFIX . "category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keywords`) VALUES ";
                $sql3 .= "( $categoryId, $languageId, '$name', '$description', '$meta_description', '$meta_keywords' );";

                $sql4 = "INSERT INTO `" . DB_PREFIX . "category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keywords`) VALUES ";
                $sql4 .= "( $categoryId, $languageId_2, '$name_2', '$description_2', '$meta_description_2', '$meta_keywords_2' );";

                $database->query($sql3);
                $database->query($sql4);
                if ($keyword) {
                    $sql5 = "INSERT INTO `" . DB_PREFIX . "url_alias` (`query`,`keyword`) VALUES ('category_id=$categoryId','$keyword');";
                    $database->query($sql5);
                }

                $sql6 = "INSERT INTO `" . DB_PREFIX . "category_to_store` (`category_id`,`store_id`) VALUES ($categoryId,0);";
                $database->query($sql6);

            } else {
                // new category
                $sql2 = "INSERT INTO `" . DB_PREFIX . "category` (`image`, `parent_id`, `sort_order`, `date_added`, `date_modified`, `status`) VALUES ";
                $sql2 .= "( '$imageName', $parentId, $sortOrder, ";
                $sql2 .= "'$dateAdded',";
                $sql2 .= "'$dateModified',";
                $sql2 .= " $status);";

                $sql_cate_id = "SELECT MAX(category_id)+1 AS category_id  FROM `" . DB_PREFIX . "category`";

                $category_id_row = $database->query($sql_cate_id);

                $categoryId = $category_id_row->row['category_id'];

                $database->query($sql2);
                $sql3 = "INSERT INTO `" . DB_PREFIX . "category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keywords`) VALUES ";
                $sql3 .= "( $categoryId, $languageId, '$name', '$description', '$meta_description', '$meta_keywords' );";
                $database->query($sql3);
                $sql4 = "INSERT INTO `" . DB_PREFIX . "category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keywords`) VALUES ";
                $sql4 .= "( $categoryId, $languageId_2, '$name_2', '$description_2', '$meta_description_2', '$meta_keywords_2' );";
                $database->query($sql4);
                if ($keyword) {
                    $sql5 = "INSERT INTO `" . DB_PREFIX . "url_alias` (`query`,`keyword`) VALUES ('category_id=$categoryId','$keyword');";
                    $database->query($sql5);
                }

                $sql6 = "INSERT INTO `" . DB_PREFIX . "category_to_store` (`category_id`,`store_id`) VALUES ($categoryId,0);";
                $database->query($sql6);

            }

        }

        // final commit
        $database->query("COMMIT;");
        return TRUE;
    }


    function uploadCategories(&$reader, &$database) {

        $data = $reader->getSheet(0);
        $categories = array();
        $isFirstRow = TRUE;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i = 0; $i < $k; $i += 1) {
            if ($isFirstRow) {
                $isFirstRow = FALSE;
                continue;
            }
            $categoryId = trim($this->getCell($data, $i, 1));
            if ($categoryId == "") {
                continue;
            }
            $parentId = $this->getCell($data, $i, 2, '0');
            $name = $this->getCell($data, $i, 3);
            $name = htmlentities($name, ENT_QUOTES, $this->detect_encoding($name));
            $name_2 = $this->getCell($data, $i, 4);
            $name_2 = htmlentities($name_2, ENT_QUOTES, $this->detect_encoding($name_2));
            $sortOrder = $this->getCell($data, $i, 5, '0');
            $imageName = trim($this->getCell($data, $i, 6));


            $keyword = $this->getCell($data, $i, 7);
            $description = $this->getCell($data, $i, 8);
            $description = htmlentities($description, ENT_QUOTES, $this->detect_encoding($description));
            $description_2 = $this->getCell($data, $i, 9);
            $description_2 = htmlentities($description_2, ENT_QUOTES, $this->detect_encoding($description_2));


            $meta_description = $this->getCell($data, $i, 10);
            $meta_description = htmlentities($meta_description, ENT_QUOTES, $this->detect_encoding($meta_description));
            $meta_description_2 = $this->getCell($data, $i, 11);
            $meta_description_2 = htmlentities($meta_description_2, ENT_QUOTES, $this->detect_encoding($meta_description_2));
            $meta_keywords = $this->getCell($data, $i, 12);
            $meta_keywords = htmlentities($meta_keywords, ENT_QUOTES, $this->detect_encoding($meta_keywords));
            $meta_keywords_2 = $this->getCell($data, $i, 13);
            $meta_keywords_2 = htmlentities($meta_keywords_2, ENT_QUOTES, $this->detect_encoding($meta_keywords_2));

            $status = $this->getCell($data, $i, 14, 'true');
            $category = array();
            $category[0] = $categoryId;
            $category[1] = $imageName;
            $category[2] = $parentId;
            $category[3] = $sortOrder;

            $category[4] = $name;
            $category[5] = $name_2;
            $category[6] = $description;
            $category[7] = $description_2;
            $category[8] = $meta_description;
            $category[9] = $meta_description_2;
            $category[10] = $meta_keywords;
            $category[11] = $meta_keywords_2;
            $category[12] = $keyword;
            $category[13] = $status;
            array_push($categories, $category);
        }
        return $this->storeCategoriesIntoDatabase($database, $categories);
    }


    function storeOptionNamesIntoDatabase(&$database, &$options, &$optionIds) {
        $this->log_sys->info('begin   storeOptionNamesIntoDatabase ');
        // find  language id
        $languageId = $this->getLanguageId($database, $this->code['en']);
        $languageId_2 = $this->getLanguageId($database, $this->code['jp']);

        // add option names, ids, and sort orders to the database
        $sql_option = "SELECT MAX(product_option_id) AS product_option_id FROM `" . DB_PREFIX . "product_option`  ";

        $sql_option_query = $database->query($sql_option);
        $maxOptionId = $sql_option_query->row['product_option_id'];
        $sortOrder = 0;

        $sql0 = '';
        $sql01 = '';
        foreach ($options as $option) {
            $sql0 .= "DELETE FROM `" . DB_PREFIX . "product_option`  WHERE product_id	=";
            $sql01 .= "DELETE FROM `" . DB_PREFIX . "product_option_description` WHERE product_id	=";
            $productId = $option['product_id'];
            $sql0 .= $productId;
            $sql01 .= $productId;
            $sql0 .= ";\n";
            $sql01 .= ";\n";
        }
        $this->import($database, $sql0);
        $this->import($database, $sql01);

        $sql = "INSERT INTO `" . DB_PREFIX . "product_option` (`product_option_id`, `product_id`, `sort_order`) VALUES ";
        $sql2 = "INSERT INTO `" . DB_PREFIX . "product_option_description` (`product_option_id`, `product_id`, `language_id`, `name`) VALUES ";
        $sql3 = "INSERT INTO `" . DB_PREFIX . "product_option_description` (`product_option_id`, `product_id`, `language_id`, `name`) VALUES ";
        $k = strlen($sql);
        $first = TRUE;
        foreach ($options as $option) {
            $productId = $option['product_id'];
            $name = $option['option'];
            $name_2 = $option['option_2'];

            if ($productId == "") {
                continue;
            }

            if (!isset($optionIds[$productId][$name])) {
                $maxOptionId += 1;
                $optionId = $maxOptionId;
                if (!isset($optionIds[$productId])) {
                    $optionIds[$productId] = array();
                    $sortOrder = 0;
                }
                $sortOrder += 1;
                $optionIds[$productId][$name] = $optionId;

                $sql .= ($first) ? "\n" : ",\n";
                $sql2 .= ($first) ? "\n" : ",\n";
                $sql3 .= ($first) ? "\n" : ",\n";
                $first = FALSE;
                $sql .= "($optionId, $productId, $sortOrder )";
                $sql2 .= "($optionId, $productId, $languageId, '" . $database->escape($name) . "' )";
                $sql3 .= "($optionId, $productId, $languageId_2, '" . $database->escape($name_2) . "' )";
            }
        }

        $sql .= ";\n";
        $sql2 .= ";\n";
        $sql3 .= ";\n";
        if (strlen($sql) > $k + 2) {
            $database->query($sql);
            $database->query($sql2);
            $database->query($sql3);
        }

        return TRUE;
    }

    function initAttributes(&$database) {
        $sql = "SELECT a.attribute_id,ad.name FROM `" . DB_PREFIX . "attribute` a LEFT JOIN `" . DB_PREFIX . "attribute_description` ad ON (a.attribute_id=ad.attribute_id)";

        $query = $database->query($sql);

        $attributes = array();

        foreach ($query->rows as $result) {
            $attributes[$result['attribute_id']] = $result['name'];
        }
        return $attributes;
    }

    function uploadAttributes(&$reader, &$database) {
        // find the default language id
        $languageId = $this->getDefaultLanguageId($database);

        $data = $reader->getSheet(1);

        $attributes = $this->initAttributes($database);

        $product_attributes = array();
        $i = 0;
        $step = 15;
        $k = $data->getHighestRow();

        $sku = '';
        $group = '';
        $name = '';

        for ($i = 0; $i < $k; $i += $step) {
            $productId = '';

            if (trim($this->getCell($data, $i, 2)) != '') {
                $sku = trim($this->getCell($data, $i, 2));

                $sql = "SELECT product_id FROM  `" . DB_PREFIX . "product` WHERE sku='$sku' ";

                if (!$sku) {
                    continue;
                }


                $result = $database->query($sql);

                if ($result->row) {
                    $productId = $result->row['product_id'];
                }
            }

            if ($productId) {
                for ($j = 0; $j < 15; $j++) {
                    $attribute_name = trim($this->getCell($data, $i + $j, 4));
                    $attribute_value = trim($this->getCell($data, $i + $j, 5));

                    $attribute_id = array_search($attribute_name, $attributes);

                    $product_attributes[$productId][] = array(
                        'language_id' => $languageId,
                        'attribute_id' => $attribute_id,
                        'text' => $attribute_value
                    );

                }
            }

//                		if(trim($this->getCell($data,$i,3))!=''){
//                			$group = trim($this->getCell($data,$i,3));
//                		}
//                		
//                		if(trim($this->getCell($data,$i,4))!=''){
//                			$name = trim($this->getCell($data,$i,3));
//                		}
//                		
//                		$text = $this->getCell($data,$i,5);

//                		$attributes[$i] = array();
//                		$attributes[$i]['product_id'] = $productId;
//                		$attributes[$i]['language_id'] = $languageId;
//                		$attributes[$i]['group'] = $group;
//                		$attributes[$i]['name'] = $name;
//                		$attributes[$i]['text'] = $text;


        }

        return $this->storeAttributesIntoDatabase($database, $product_attributes);


    }

    function storeAttributesIntoDatabase(&$database, &$attributes) {
        // start transaction, remove product attributes from database
        $sql = "START TRANSACTION;\n";

        foreach ($attributes as $product_id => $product_attribute) {
            $sql = "DELETE FROM `" . DB_PREFIX . "product_attribute` WHERE product_id='$product_id';\n";
//                		$this->multiquery( $database, $sql );
            $database->query($sql);

            foreach ($product_attribute as $attribute) {
                $productId = $product_id;
                $attributeId = $attribute['attribute_id'];
                $langId = $attribute['language_id'];
                $text = $attribute['text'];

                $sql = "INSERT INTO `" . DB_PREFIX . "product_attribute` (`product_id`,`attribute_id`,`language_id`,`text`) VALUES ";
                $sql .= "('$productId','$attributeId','$langId','" . $database->escape($text) . "');";
                $database->query($sql);
            }
        }

//                	$database->query("COMMIT;");
        return TRUE;
    }

    function multiquery(&$database, $sql) {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);
            if ($sql) {
                $database->query($sql);
            }
        }
    }

    function storeOptionDetailsIntoDatabase(&$database, &$options, &$optionIds) {
        $this->log_sys->info('begin   storeOptionDetailsIntoDatabase ');
        // find  language id
        $languageId = $this->getLanguageId($database, $this->code['en']);
        $languageId_2 = $this->getLanguageId($database, $this->code['jp']);

        $sql_option_value = "SELECT MAX(product_option_value_id) AS product_option_value_id FROM `" . DB_PREFIX . "product_option_value`  ";

        $sql_option_valuequery = $database->query($sql_option_value);
        $max_value_id = $sql_option_valuequery->row['product_option_value_id'];

        $sql0 = '';
        $sql01 = '';
        foreach ($options as $option) {
            $sql0 .= "DELETE FROM `" . DB_PREFIX . "product_option_value`  WHERE product_id	=";
            $sql01 .= "DELETE FROM `" . DB_PREFIX . "product_option_value_description` WHERE product_id	=";
            $productId = $option['product_id'];
            $sql0 .= $productId;
            $sql01 .= $productId;
            $sql0 .= ";\n";
            $sql01 .= ";\n";
        }

        $this->import($database, $sql0);
        $this->import($database, $sql01);

        $sql = "INSERT INTO `" . DB_PREFIX . "product_option_value` (`product_option_value_id`, `product_id`, `product_option_id`, `quantity`, `subtract`, `price`, `prefix`, `sort_order`) VALUES ";
        $sql2 = "INSERT INTO `" . DB_PREFIX . "product_option_value_description` (`product_option_value_id`, `product_id`, `language_id`, `name`) VALUES ";
        $sql3 = "INSERT INTO `" . DB_PREFIX . "product_option_value_description` (`product_option_value_id`, `product_id`, `language_id`, `name`) VALUES ";
        $k = strlen($sql);
        $first = TRUE;
        foreach ($options as $index => $option) {
            $max_value_id += 1;
            $productOptionValueId = $max_value_id;
            $productId = $option['product_id'];
            $optionName = $option['option'];

            if (!isset($optionIds[$productId][$optionName])) {
                continue;
            }

            $optionId = $optionIds[$productId][$optionName];
            $optionValue = $database->escape($option['option_value']);
            $optionValue_2 = $database->escape($option['option_value_2']);
            $quantity = $option['quantity'];
            $subtract = $option['subtract'];
            $subtract = ((strtoupper($subtract) == "TRUE") || (strtoupper($subtract) == "YES") || (strtoupper($subtract) == "ENABLED")) ? 1 : 0;
            $price = $option['price'];
            $prefix = $option['prefix'];
            $sortOrder = $option['sort_order'];

            $sql .= ($first) ? "\n" : ",\n";
            $sql2 .= ($first) ? "\n" : ",\n";
            $sql3 .= ($first) ? "\n" : ",\n";
            $first = FALSE;

            $sql .= "($productOptionValueId, $productId, $optionId, $quantity, $subtract, $price, '$prefix', $sortOrder)";
            $sql2 .= "($productOptionValueId, $productId, $languageId, '$optionValue')";
            $sql3 .= "($productOptionValueId, $productId, $languageId_2, '$optionValue_2')";
        }

        $sql .= ";\n";
        $sql2 .= ";\n";
        $sql3 .= ";\n";
        // execute the database query
        if (strlen($sql) > $k + 2) {
            $database->query($sql);
            $database->query($sql2);
            $database->query($sql3);
        }
        return TRUE;
    }


    function storeOptionsIntoDatabase(&$database, &$options) {
        // store option names
        $optionIds = array(); // indexed by product_id and name
        //$this->log_sys->info('options '.count($options));
        /*$ok = $this->storeOptionNamesIntoDatabase( $database, $options, $optionIds);
                	if (!$ok) {
                		$database->query( 'ROLLBACK;' );
                		return FALSE;
                	}

                	// store option details
                	$ok = $this->storeOptionDetailsIntoDatabase( $database, $options, $optionIds);
                	if (!$ok) {
                		$database->query( 'ROLLBACK;' );
                		return FALSE;
                	}

                	$database->query("COMMIT;");*/
        return TRUE;
    }


    function uploadOptions(&$reader, &$database) {
        $data = $reader->getSheet(2);
        $options = array();
        $i = 0;
        $k = $data->getHighestRow();
        $isFirstRow = TRUE;
        $this->log_sys->info('begin uploadOptions ');
        for ($i = 0; $i < $k; $i += 1) {

            if ($isFirstRow) {
                $isFirstRow = FALSE;
                continue;
            }
            $productId = trim($this->getCell($data, $i, 1));
            if ($productId == "") {
                continue;
            }

            $option = $this->getCell($data, $i, 3);
            $option_2 = $this->getCell($data, $i, 4);
            $optionValue = $this->getCell($data, $i, 5);
            $optionValue_2 = $this->getCell($data, $i, 6);
            $optionQuantity = $this->getCell($data, $i, 7, '0');
            $optionSubtract = $this->getCell($data, $i, 8, 'false');
            $optionPrice = $this->getCell($data, $i, 9, '0');
            $optionPrefix = $this->getCell($data, $i, 10, '+');
            $sortOrder = $this->getCell($data, $i, 11, '0');
            $options[$i] = array();
            $options[$i]['product_id'] = $productId;
            $options[$i]['option'] = $option;
            $options[$i]['option_2'] = $option_2;
            $options[$i]['option_value'] = $optionValue;
            $options[$i]['option_value_2'] = $optionValue_2;
            $options[$i]['quantity'] = $optionQuantity;
            $options[$i]['subtract'] = $optionSubtract;
            $options[$i]['price'] = $optionPrice;
            $options[$i]['prefix'] = $optionPrefix;
            $options[$i]['sort_order'] = $sortOrder;
        }
        //$this->log_sys->info('options first '.count($options));
        // store option names
        $optionIds = array(); // indexed by product_id and name
        //	$this->log_sys->info('options '.count($options));
        $ok = $this->storeOptionNamesIntoDatabase($database, $options, $optionIds);
        if (!$ok) {
            $database->query('ROLLBACK;');
            return FALSE;
        }

        // store option details
        $ok = $this->storeOptionDetailsIntoDatabase($database, $options, $optionIds);
        if (!$ok) {
            $database->query('ROLLBACK;');
            return FALSE;
        }

        $database->query("COMMIT;");
        return TRUE;
        //	return $this->storeOptionsIntoDatabase( $database, $options， );
    }


    function storeSpecialsIntoDatabase(&$database, &$specials) {
        $sql = "START TRANSACTION;\n";
        $sql .= "DELETE FROM `" . DB_PREFIX . "product_special`;\n";
        $this->import($database, $sql);

        // find existing customer groups from the database
        $sql = "SELECT * FROM `" . DB_PREFIX . "customer_group`";
        $result = $database->query($sql);
        $maxCustomerGroupId = 0;
        $customerGroups = array();
        foreach ($result->rows as $row) {
            $customerGroupId = $row['customer_group_id'];
            $name = $row['name'];
            if (!isset($customerGroups[$name])) {
                $customerGroups[$name] = $customerGroupId;
            }
            if ($maxCustomerGroupId < $customerGroupId) {
                $maxCustomerGroupId = $customerGroupId;
            }
        }

        // add additional customer groups into the database
        foreach ($specials as $special) {
            $name = $special['customer_group'];
            if (!isset($customerGroups[$name])) {
                $maxCustomerGroupId += 1;
                $sql = "INSERT INTO `" . DB_PREFIX . "customer_group` (`customer_group_id`, `name`) VALUES ";
                $sql .= "($maxCustomerGroupId, '$name')";
                $sql .= ";\n";
                $database->query($sql);
                $customerGroups[$name] = $maxCustomerGroupId;
            }
        }

        // store product specials into the database
        $productSpecialId = 0;
        $first = TRUE;
        $sql = "INSERT INTO `" . DB_PREFIX . "product_special` (`product_special_id`,`product_id`,`customer_group_id`,`priority`,`price`,`date_start`,`date_end` ) VALUES ";
        foreach ($specials as $special) {
            $productSpecialId += 1;
            $productId = $special['product_id'];
            $name = $special['customer_group'];
            $customerGroupId = $customerGroups[$name];
            $priority = $special['priority'];
            $price = $special['price'];
            $dateStart = $special['date_start'];
            $dateEnd = $special['date_end'];
            $sql .= ($first) ? "\n" : ",\n";
            $first = FALSE;
            $sql .= "($productSpecialId,$productId,$customerGroupId,$priority,$price,'$dateStart','$dateEnd')";
        }
        if (!$first) {
            $database->query($sql);
        }

        $database->query("COMMIT;");
        return TRUE;
    }


    function uploadSpecials(&$reader, &$database) {
        $data = $reader->getSheet(3);
        $specials = array();
        $i = 0;
        $k = $data->getHighestRow();
        $isFirstRow = TRUE;
        for ($i = 0; $i < $k; $i += 1) {
            if ($isFirstRow) {
                $isFirstRow = FALSE;
                continue;
            }
            $productId = trim($this->getCell($data, $i, 1));
            if ($productId == "") {
                continue;
            }
            $customerGroup = trim($this->getCell($data, $i, 2));
            if ($customerGroup == "") {
                continue;
            }
            $priority = $this->getCell($data, $i, 3, '0');
            $price = $this->getCell($data, $i, 4, '0');
            $dateStart = $this->getCell($data, $i, 5, '0000-00-00');
            $dateEnd = $this->getCell($data, $i, 6, '0000-00-00');
            $specials[$i] = array();
            $specials[$i]['product_id'] = $productId;
            $specials[$i]['customer_group'] = $customerGroup;
            $specials[$i]['priority'] = $priority;
            $specials[$i]['price'] = $price;
            $specials[$i]['date_start'] = $dateStart;
            $specials[$i]['date_end'] = $dateEnd;
        }
        return $this->storeSpecialsIntoDatabase($database, $specials);
    }


    function storeDiscountsIntoDatabase(&$database, &$discounts) {
        $sql = "START TRANSACTION;\n";
        $sql .= "DELETE FROM `" . DB_PREFIX . "product_discount`;\n";
        $this->import($database, $sql);

        // find existing customer groups from the database
        $sql = "SELECT * FROM `" . DB_PREFIX . "customer_group`";
        $result = $database->query($sql);
        $maxCustomerGroupId = 0;
        $customerGroups = array();
        foreach ($result->rows as $row) {
            $customerGroupId = $row['customer_group_id'];
            $name = $row['name'];
            if (!isset($customerGroups[$name])) {
                $customerGroups[$name] = $customerGroupId;
            }
            if ($maxCustomerGroupId < $customerGroupId) {
                $maxCustomerGroupId = $customerGroupId;
            }
        }

        // add additional customer groups into the database
        foreach ($discounts as $discount) {
            $name = $discount['customer_group'];
            if (!isset($customerGroups[$name])) {
                $maxCustomerGroupId += 1;
                $sql = "INSERT INTO `" . DB_PREFIX . "customer_group` (`customer_group_id`, `name`) VALUES ";
                $sql .= "($maxCustomerGroupId, '$name')";
                $sql .= ";\n";
                $database->query($sql);
                $customerGroups[$name] = $maxCustomerGroupId;
            }
        }

        // store product discounts into the database
        $productDiscountId = 0;
        $first = TRUE;
        $sql = "INSERT INTO `" . DB_PREFIX . "product_discount` (`product_discount_id`,`product_id`,`customer_group_id`,`quantity`,`priority`,`price`,`date_start`,`date_end` ) VALUES ";
        foreach ($discounts as $discount) {
            $productDiscountId += 1;
            $productId = $discount['product_id'];
            $name = $discount['customer_group'];
            $customerGroupId = $customerGroups[$name];
            $quantity = $discount['quantity'];
            $priority = $discount['priority'];
            $price = $discount['price'];
            $dateStart = $discount['date_start'];
            $dateEnd = $discount['date_end'];
            $sql .= ($first) ? "\n" : ",\n";
            $first = FALSE;
            $sql .= "($productDiscountId,$productId,$customerGroupId,$quantity,$priority,$price,'$dateStart','$dateEnd')";
        }
        if (!$first) {
            $database->query($sql);
        }

        $database->query("COMMIT;");
        return TRUE;
    }


    function uploadDiscounts(&$reader, &$database) {
        $data = $reader->getSheet(4);
        $discounts = array();
        $i = 0;
        $k = $data->getHighestRow();
        $isFirstRow = TRUE;
        for ($i = 0; $i < $k; $i += 1) {
            if ($isFirstRow) {
                $isFirstRow = FALSE;
                continue;
            }
            $productId = trim($this->getCell($data, $i, 1));
            if ($productId == "") {
                continue;
            }
            $customerGroup = trim($this->getCell($data, $i, 2));
            if ($customerGroup == "") {
                continue;
            }
            $quantity = $this->getCell($data, $i, 3, '0');
            $priority = $this->getCell($data, $i, 4, '0');
            $price = $this->getCell($data, $i, 5, '0');
            $dateStart = $this->getCell($data, $i, 6, '0000-00-00');
            $dateEnd = $this->getCell($data, $i, 7, '0000-00-00');
            $discounts[$i] = array();
            $discounts[$i]['product_id'] = $productId;
            $discounts[$i]['customer_group'] = $customerGroup;
            $discounts[$i]['quantity'] = $quantity;
            $discounts[$i]['priority'] = $priority;
            $discounts[$i]['price'] = $price;
            $discounts[$i]['date_start'] = $dateStart;
            $discounts[$i]['date_end'] = $dateEnd;
        }
        return $this->storeDiscountsIntoDatabase($database, $discounts);
    }


    function storeAdditionalImagesIntoDatabase(&$reader, &$database, $productId, $imageNames) {
        $this->log_sys->info('begin   storeAdditionalImagesIntoDatabase ');
        // start transaction
        $sql = "START TRANSACTION;\n";

        //		// delete old additional product images from database
        $sql = "DELETE FROM `" . DB_PREFIX . "product_image` WHERE product_id=$productId";
        $database->query($sql);
        $imageNames = trim($imageNames);
        //	$this->log_sys->info('imageName = '.$imageNames);
        $imageNames = trim($this->clean($imageNames, TRUE));
        $imageNames = ($imageNames == "") ? array() : explode(",", $imageNames);
        $sql = "INSERT INTO `" . DB_PREFIX . "product_image` (product_id, `image`) VALUES ";
        $first = TRUE;
        //	$this->log_sys->info('imageName '.$imageNames);
        foreach ($imageNames as $imageName) {
            $sql .= ($first) ? "\n" : ",\n";
            $sql .= "($productId,'$imageName') ";
            $first = FALSE;
        }
        //$this->log_sys->info('imageName SQL'.$sql);
        if (!$first)
            $database->query($sql);

        $database->query("COMMIT;");
        return TRUE;
    }


    function validateHeading(&$data, &$expected) {
        $heading = array();
        $k = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
        if ($k != count($expected)) {
            return FALSE;
        }
        $i = 0;
        for ($j = 1; $j <= $k; $j += 1) {
            $heading[] = $this->getCell($data, $i, $j);
        }
        $valid = TRUE;
        for ($i = 0; $i < count($expected); $i += 1) {
            if (!isset($heading[$i])) {
                $valid = FALSE;
                break;
            }
            if (strtolower($heading[$i]) != strtolower($expected[$i])) {
                $valid = FALSE;
                break;
            }
        }
        return $valid;
    }


    function validateCategories(&$reader) {
        $expectedCategoryHeading = array
        ("category_id", "parent_id", "name", "sort_order", "image_name", "date_added", "date_modified", "language_id", "seo_keyword", "description", "meta_description", "meta_keywords", "store_ids", "status\nenabled");
        //$data =& $reader->sheets[0];
        $data =& $reader->getSheet(0);
        return $this->validateHeading($data, $expectedCategoryHeading);
    }


    function validateProducts(&$reader) {
        $expectedProductHeading = array
        ("product_id", "name", "categories", "sku", "location", "quantity", "model", "manufacturer", "image_name", "requires\nshipping", "price", "date_added", "date_modified", "date_available", "weight", "unit", "length", "width", "height", "length\nunit", "status\nenabled", "tax_class_id", "viewed", "language_id", "seo_keyword", "description", "meta_description", "meta_keywords", "additional image names", "stock_status_id", "store_ids", "related_ids", "tags", "sort_order", "subtract", "minimum", "cost");
        //$data = $reader->sheets[1];
        $data =& $reader->getSheet(1);
        return $this->validateHeading($data, $expectedProductHeading);
    }


    function validateOptions(&$reader) {
        $expectedOptionHeading = array
        ("product_id", "language_id", "option", "option_value", "quantity", "subtract", "price", "prefix", "sort_order");
        //$data = $reader->sheets[2];
        $data =& $reader->getSheet(2);
        return $this->validateHeading($data, $expectedOptionHeading);
    }


    function validateSpecials(&$reader) {
        $expectedSpecialsHeading = array
        ("product_id", "customer_group", "priority", "price", "date_start", "date_end");
        //$data = $reader->sheets[3];
        $data =& $reader->getSheet(3);
        return $this->validateHeading($data, $expectedSpecialsHeading);
    }


    function validateDiscounts(&$reader) {
        $expectedDiscountsHeading = array
        ("product_id", "customer_group", "quantity", "priority", "price", "date_start", "date_end");
        //$data = $reader->sheets[4];
        $data =& $reader->getSheet(4);
        return $this->validateHeading($data, $expectedDiscountsHeading);
    }


    function validateUpload(&$reader) {
        /*if ($reader->getSheetCount() != 5) {
                	 //if (count($reader->sheets) != 5) {
                	 error_log(date('Y-m-d H:i:s - ', time())."Export/Import: Invalid number of worksheets, 5 worksheets expected\n",3,DIR_LOGS."error.txt");
                	 return FALSE;
                	 }
                	 if (!$this->validateCategories( $reader )) {
                	 error_log(date('Y-m-d H:i:s - ', time())."Export/Import: Invalid Categories header\n",3,DIR_LOGS."error.txt");
                	 return FALSE;
                	 }
                	 if (!$this->validateProducts( $reader )) {
                	 error_log(date('Y-m-d H:i:s - ', time())."Export/Import: Invalid Products header\n",3,DIR_LOGS."error.txt");
                	 return FALSE;
                	 }
                	 if (!$this->validateOptions( $reader )) {
                	 error_log(date('Y-m-d H:i:s - ', time())."Export/Import: Invalid Options header\n",3,DIR_LOGS."error.txt");
                	 return FALSE;
                	 }
                	 if (!$this->validateSpecials( $reader )) {
                	 error_log(date('Y-m-d H:i:s - ', time())."Export/Import: Invalid Specials header\n",3,DIR_LOGS."error.txt");
                	 return FALSE;
                	 }
                	 if (!$this->validateDiscounts( $reader )) {
                	 error_log(date('Y-m-d H:i:s - ', time())."Export/Import: Invalid Discounts header\n",3,DIR_LOGS."error.txt");
                	 return FALSE;
                	 }*/
        return TRUE;
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
        $this->cache->delete('url_alias');
        $this->cache->delete('product_special');
        $this->cache->delete('product_discount');
    }


    function upload($filename, $category_id = '') {
        global $config;
        global $log;
        $config = $this->config;
        $log = $this->log;
      //  set_error_handler('error_handler_for_export', E_ALL);
       // register_shutdown_function('fatal_error_shutdown_handler_for_export');
        
        register_shutdown_function(error_handler);
        
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
        $ok = $this->validateUpload($reader);

        if (!$ok) {
            return FALSE;
        }
        $this->clearCache();

        $ok = $this->uploadProducts($reader, $database, $category_id);
        if (!$ok) {
            return FALSE;
        }

        $ok = $this->uploadAttributes($reader, $database);

        if (!$ok) {
            return FALSE;
        }

        chdir('../../..');
        return $ok;
    }


    function getStoreIdsForCategories(&$database) {
        $sql = "SELECT category_id, store_id FROM `" . DB_PREFIX . "category_to_store` cs;";
        $storeIds = array();
        $result = $database->query($sql);
        foreach ($result->rows as $row) {
            $categoryId = $row['category_id'];
            $storeId = $row['store_id'];
            if (!isset($storeIds[$categoryId])) {
                $storeIds[$categoryId] = array();
            }
            if (!in_array($storeId, $storeIds[$categoryId])) {
                $storeIds[$categoryId][] = $storeId;
            }
        }
        return $storeIds;
    }


    function populateCategoriesWorksheet(&$worksheet, &$database, $languageId, &$boxFormat, &$textFormat, $categories) {

        $languageId = $this->getLanguageId($database, $this->code['en']);
        $languageId_2 = $this->getLanguageId($database, $this->code['jp']);
        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, strlen('category_id') + 1);
        $worksheet->setColumn($j, $j++, strlen('parent_id') + 1);
        $worksheet->setColumn($j, $j++, max(strlen('name'), 32) + 1);
        $worksheet->setColumn($j, $j++, strlen('sort_order') + 1);
        $worksheet->setColumn($j, $j++, max(strlen('image_name'), 12) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('seo_keyword'), 16) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('description'), 32) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('meta_description'), 32) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('meta_keywords'), 32) + 1);

        $worksheet->setColumn($j, $j++, max(strlen('status'), 5) + 1, $textFormat);

        // The heading row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'category_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'parent_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'name_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'name_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'sort_order', $boxFormat);
        $worksheet->writeString($i, $j++, 'image_name', $boxFormat);
        $worksheet->writeString($i, $j++, 'seo_keyword', $boxFormat);
        $worksheet->writeString($i, $j++, 'description_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'description_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_description_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_description_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_keywords_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_keywords_jp', $boxFormat);
        $worksheet->writeString($i, $j++, "status\nenabled", $boxFormat);
        $worksheet->setRow($i, 30, $boxFormat);

        // The actual categories data
        $i += 1;
        $j = 0;
        //$storeIds = $this->getStoreIdsForCategories( $database );
        $query = "SELECT c.* , cd.*, ua.keyword,cd2.name AS name_2,cd2.description AS description_2,cd2.meta_description AS meta_description_2, cd2.meta_keywords AS meta_keywords_2
		 FROM `" . DB_PREFIX . "category` c ";
        $query .= "INNER JOIN `" . DB_PREFIX . "category_description` cd ON cd.category_id = c.category_id ";
        $query .= " AND cd.language_id=$languageId ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "category_description` cd2 ON cd2.category_id = c.category_id ";
        $query .= " AND cd2.language_id=$languageId_2 ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "url_alias` ua ON ua.query=CONCAT('category_id=',c.category_id) ";
        if ($categories != '')
            $query .= " WHERE c.category_id IN (" . $categories . ")";
        $query .= "ORDER BY c.`parent_id`, `sort_order`, c.`category_id`;";
        $result = $database->query($query);
        foreach ($result->rows as $row) {
            $worksheet->write($i, $j++, $row['category_id']);
            $worksheet->write($i, $j++, $row['parent_id']);
            $worksheet->writeString($i, $j++, html_entity_decode($row['name'], ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, html_entity_decode($row['name_2'], ENT_QUOTES, 'UTF-8'));
            $worksheet->write($i, $j++, $row['sort_order']);
            $worksheet->write($i, $j++, $row['image']);
            $worksheet->writeString($i, $j++, ($row['keyword']) ? $row['keyword'] : '');
            $worksheet->writeString($i, $j++, html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, html_entity_decode($row['description_2'], ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, html_entity_decode($row['meta_description'], ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, html_entity_decode($row['meta_description_2'], ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, html_entity_decode($row['meta_keywords'], ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, html_entity_decode($row['meta_keywords_2'], ENT_QUOTES, 'UTF-8'));
            $categoryId = $row['category_id'];

            $worksheet->write($i, $j++, ($row['status'] == 0) ? "false" : "true", $textFormat);
            $i += 1;
            $j = 0;
        }
    }


    function getStoreIdsForProducts(&$database) {
        $sql = "SELECT product_id, store_id FROM `" . DB_PREFIX . "product_to_store` ps;";
        $storeIds = array();
        $result = $database->query($sql);
        foreach ($result->rows as $row) {
            $productId = $row['product_id'];
            $storeId = $row['store_id'];
            if (!isset($storeIds[$productId])) {
                $storeIds[$productId] = array();
            }
            if (!in_array($storeId, $storeIds[$productId])) {
                $storeIds[$productId][] = $storeId;
            }
        }
        return $storeIds;
    }


    function populateProductsWorksheet(&$worksheet, &$database, &$imageNames, $languageId, &$priceFormat, &$boxFormat, &$weightFormat, &$textFormat, $categories) {
        $languageId = $this->getLanguageId($database, $this->code['en']);
        $languageId_2 = $this->getLanguageId($database, $this->code['jp']);
        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, max(strlen('product_id'), 4) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('name_en'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('name_jp'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('categories'), 12) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('sku'), 10) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('quantity'), 4) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('model'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('manufacturer'), 10) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('image_name'), 12) + 1);;
        $worksheet->setColumn($j, $j++, max(strlen('shipping'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('price'), 10) + 1, $priceFormat);
        $worksheet->setColumn($j, $j++, max(strlen('date_available'), 10) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('weight'), 6) + 1, $weightFormat);
        $worksheet->setColumn($j, $j++, max(strlen('unit'), 3) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('length'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('width'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('height'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('length'), 3) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('status'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('tax_class_id'), 2) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('seo_keyword'), 16) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('description_en'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('description_jp'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('meta_description_en'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('meta_description_jp'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('meta_keywords_en'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('meta_keywords_jp'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('additional image names'), 24) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('related_ids'), 16) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('tags_en'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('tags_jp'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('sort_order'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('subtract'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('minimum'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('cost'), 10) + 1, $priceFormat);

        // The product headings row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'product_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'name_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'name_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'categories', $boxFormat);
        $worksheet->writeString($i, $j++, 'sku', $boxFormat);
        $worksheet->writeString($i, $j++, 'quantity', $boxFormat);
        $worksheet->writeString($i, $j++, 'model', $boxFormat);
        $worksheet->writeString($i, $j++, 'manufacturer', $boxFormat);
        $worksheet->writeString($i, $j++, 'image_name', $boxFormat);
        $worksheet->writeString($i, $j++, "requires\nshipping", $boxFormat);
        $worksheet->writeString($i, $j++, 'price', $boxFormat);
        $worksheet->writeString($i, $j++, 'date_available', $boxFormat);
        $worksheet->writeString($i, $j++, 'weight', $boxFormat);
        $worksheet->writeString($i, $j++, 'unit', $boxFormat);
        $worksheet->writeString($i, $j++, 'length', $boxFormat);
        $worksheet->writeString($i, $j++, 'width', $boxFormat);
        $worksheet->writeString($i, $j++, 'height', $boxFormat);
        $worksheet->writeString($i, $j++, "length\nunit", $boxFormat);
        $worksheet->writeString($i, $j++, "status\nenabled", $boxFormat);
        $worksheet->writeString($i, $j++, 'tax_class_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'seo_keyword', $boxFormat);
        $worksheet->writeString($i, $j++, 'description_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'description_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_description_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_description_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_keywords_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_keywords_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'additional image names', $boxFormat);
        $worksheet->writeString($i, $j++, 'stock_status_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'related_ids', $boxFormat);
        $worksheet->writeString($i, $j++, 'tags_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'tags_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'sort_order', $boxFormat);
        $worksheet->writeString($i, $j++, "subtract", $boxFormat);
        $worksheet->writeString($i, $j++, 'minimum', $boxFormat);
        $worksheet->writeString($i, $j++, 'cost', $boxFormat);
        $worksheet->setRow($i, 30, $boxFormat);

        // The actual products data
        $i += 1;
        $j = 0;
        $storeIds = $this->getStoreIdsForProducts($database);
        $query = "SELECT ";
        $query .= "  p.product_id,";
        $query .= "  pd.name,";
        $query .= "  pd2.name AS name_2,";
        $query .= "  GROUP_CONCAT( DISTINCT CAST(pc.category_id AS CHAR(11)) SEPARATOR \",\" ) AS categories,";
        $query .= "  p.sku,";
        $query .= "  p.location,";
        $query .= "  p.quantity,";
        $query .= "  p.model,";
        $query .= "  m.name AS manufacturer,";
        $query .= "  p.image AS image_name,";
        $query .= "  p.shipping,";
        $query .= "  p.price,";
        $query .= "  p.date_added,";
        $query .= "  p.date_modified,";
        $query .= "  p.date_available,";
        $query .= "  p.weight,";
        $query .= "  wc.unit,";
        $query .= "  p.length,";
        $query .= "  p.width,";
        $query .= "  p.height,";
        $query .= "  p.status,";
        $query .= "  p.tax_class_id,";
        $query .= "  p.viewed,";
        $query .= "  p.sort_order,";
        $query .= "  pd.language_id,";
        $query .= "  ua.keyword,";
        $query .= "  pd.description, ";
        $query .= "  pd.meta_description, ";
        $query .= "  pd.meta_keywords, ";
        $query .= "  pd2.description AS description_2, ";
        $query .= "  pd2.meta_description AS meta_description_2, ";
        $query .= "  pd2.meta_keywords AS meta_keywords_2, ";
        $query .= "  p.stock_status_id, ";
        $query .= "  mc.unit AS length_unit, ";
        $query .= "  p.subtract, ";
        $query .= "  p.minimum, ";
        $query .= "  p.cost, ";
        $query .= "  GROUP_CONCAT( DISTINCT CAST(pr.related_id AS CHAR(11)) SEPARATOR \",\" ) AS related, ";
        $query .= "  GROUP_CONCAT( DISTINCT pt.tag SEPARATOR \",\" ) AS tags, ";
        $query .= "  GROUP_CONCAT( DISTINCT pt2.tag SEPARATOR \",\" ) AS tags_2 ";
        $query .= "FROM `" . DB_PREFIX . "product` p ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id=pd.product_id ";
        $query .= "  AND pd.language_id=$languageId ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_description` pd2 ON p.product_id=pd2.product_id ";
        $query .= "  AND pd2.language_id=$languageId_2 ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_to_category` pc ON p.product_id=pc.product_id ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "url_alias` ua ON ua.query=CONCAT('product_id=',p.product_id) ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON m.manufacturer_id = p.manufacturer_id ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "weight_class_description` wc ON wc.weight_class_id = p.weight_class_id ";
        $query .= "  AND wc.language_id=$languageId ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "length_class_description` mc ON mc.length_class_id=p.length_class_id ";
        $query .= "  AND mc.language_id=$languageId ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_related` pr ON pr.product_id=p.product_id ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_tags` pt ON pt.product_id=p.product_id ";
        $query .= "  AND pt.language_id=$languageId ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_tags` pt2 ON pt2.product_id=p.product_id ";
        $query .= "  AND pt2.language_id=$languageId_2 ";
        if ($categories != '')
            $query .= " WHERE  pc.category_id IN(" . $categories . ") ";
        $query .= "GROUP BY p.product_id ";
        $query .= "ORDER BY p.product_id, pc.category_id; ";
        $result = $database->query($query);
        foreach ($result->rows as $row) {
            $productId = $row['product_id'];
            $worksheet->write($i, $j++, $productId);
            $worksheet->writeString($i, $j++, html_entity_decode($row['name'], ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, html_entity_decode($row['name_2'], ENT_QUOTES, 'UTF-8'));
            $worksheet->write($i, $j++, $row['categories'], $textFormat);
            $worksheet->writeString($i, $j++, $row['sku']);

            $worksheet->write($i, $j++, $row['quantity']);
            $worksheet->writeString($i, $j++, $row['model']);
            $worksheet->writeString($i, $j++, $row['manufacturer']);
            $worksheet->writeString($i, $j++, $row['image_name']);
            $worksheet->write($i, $j++, ($row['shipping'] == 0) ? "no" : "yes", $textFormat);
            $worksheet->write($i, $j++, $row['price'], $priceFormat);
            $worksheet->write($i, $j++, $row['date_available'], $textFormat);
            $worksheet->write($i, $j++, $row['weight'], $weightFormat);
            $worksheet->writeString($i, $j++, $row['unit']);
            $worksheet->write($i, $j++, $row['length']);
            $worksheet->write($i, $j++, $row['width']);
            $worksheet->write($i, $j++, $row['height']);
            $worksheet->writeString($i, $j++, $row['length_unit']);
            $worksheet->write($i, $j++, ($row['status'] == 0) ? "false" : "true", $textFormat);
            $worksheet->write($i, $j++, $row['tax_class_id']);
            $worksheet->writeString($i, $j++, ($row['keyword']) ? $row['keyword'] : '');
            $worksheet->writeString($i, $j++, html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8'), $textFormat, TRUE);
            $worksheet->writeString($i, $j++, html_entity_decode($row['description_2'], ENT_QUOTES, 'UTF-8'), $textFormat, TRUE);
            $worksheet->write($i, $j++, html_entity_decode($row['meta_description'], ENT_QUOTES, 'UTF-8'), $textFormat);
            $worksheet->write($i, $j++, html_entity_decode($row['meta_description_2'], ENT_QUOTES, 'UTF-8'), $textFormat);
            $worksheet->write($i, $j++, html_entity_decode($row['meta_keywords'], ENT_QUOTES, 'UTF-8'), $textFormat);
            $worksheet->write($i, $j++, html_entity_decode($row['meta_keywords_2'], ENT_QUOTES, 'UTF-8'), $textFormat);
            $names = "";
            if (isset($imageNames[$productId])) {
                $first = TRUE;
                foreach ($imageNames[$productId] AS $name) {
                    if (!$first) {
                        $names .= ",\n";
                    }
                    $first = FALSE;
                    $names .= $name;
                }
            }
            $worksheet->write($i, $j++, $names, $textFormat);
            $worksheet->write($i, $j++, $row['stock_status_id']);
            $worksheet->write($i, $j++, $row['related'], $textFormat);
            $worksheet->write($i, $j++, $row['tags'], $textFormat);
            $worksheet->write($i, $j++, $row['tags_2'], $textFormat);
            $worksheet->write($i, $j++, $row['sort_order']);
            $worksheet->write($i, $j++, ($row['subtract'] == 0) ? "false" : "true", $textFormat);
            $worksheet->write($i, $j++, $row['minimum']);
            $worksheet->write($i, $j++, $row['cost'], $priceFormat);
            $i += 1;
            $j = 0;
        }
    }


    function populateOptionsWorksheet(&$worksheet, &$database, $languageId, &$priceFormat, &$boxFormat, $textFormat, $categories) {
        $languageId = $this->getLanguageId($database, $this->code['en']);
        $languageId_2 = $this->getLanguageId($database, $this->code['jp']);
        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, max(strlen('product_id'), 4) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('model'), 2) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('option_en'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('option_jp'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('option_value_en'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('option_value_jp'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('quantity'), 4) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('subtract'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('price'), 10) + 1, $priceFormat);
        $worksheet->setColumn($j, $j++, max(strlen('prefix'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('sort_order'), 5) + 1);

        // The options headings row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'product_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'model', $boxFormat);
        $worksheet->writeString($i, $j++, 'option_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'option_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'option_value_en', $boxFormat);
        $worksheet->writeString($i, $j++, 'option_value_jp', $boxFormat);
        $worksheet->writeString($i, $j++, 'quantity', $boxFormat);
        $worksheet->writeString($i, $j++, 'subtract', $boxFormat);
        $worksheet->writeString($i, $j++, 'price', $boxFormat);
        $worksheet->writeString($i, $j++, 'prefix', $boxFormat);
        $worksheet->writeString($i, $j++, 'sort_order', $boxFormat);
        $worksheet->setRow($i, 30, $boxFormat);

        // The actual options data
        $i += 1;
        $j = 0;
        $query = "SELECT DISTINCT p.product_id, p.model,";
        $query .= "  pod.name AS option_name, ";
        $query .= "  pod2.name AS option_name_2, ";
        $query .= "  po.sort_order AS option_sort_order, ";
        $query .= "  povd.name AS option_value, ";
        $query .= "  povd2.name AS option_value_2, ";
        $query .= "  pov.quantity AS option_quantity, ";
        $query .= "  pov.subtract AS option_subtract, ";
        $query .= "  pov.price AS option_price, ";
        $query .= "  pov.prefix AS option_prefix, ";
        $query .= "  pov.sort_order AS sort_order ";
        $query .= "FROM `" . DB_PREFIX . "product` p ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id=pd.product_id ";
        $query .= "  AND pd.language_id=$languageId ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_option` po ON po.product_id=p.product_id ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_option_description` pod ON pod.product_option_id=po.product_option_id ";
        $query .= "  AND pod.product_id=po.product_id ";
        $query .= "  AND pod.language_id=$languageId ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_option_description` pod2 ON pod2.product_option_id=po.product_option_id ";
        $query .= "  AND pod2.product_id=po.product_id ";
        $query .= "  AND pod2.language_id=$languageId_2 ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_option_value` pov ON pov.product_option_id=po.product_option_id ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_option_value_description` povd ON povd.product_option_value_id=pov.product_option_value_id ";
        $query .= "  AND povd.language_id=$languageId ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product_option_value_description` povd2 ON povd2.product_option_value_id=pov.product_option_value_id ";
        $query .= "  AND povd2.language_id=$languageId_2 ";
        if ($categories != '')
            $query .= " WHERE  p.product_id  IN (SELECT product_id FROM product_to_category WHERE category_id IN(" . $categories . ") ) ";
        $query .= "ORDER BY product_id, option_sort_order, sort_order;";

        $result = $database->query($query);
        foreach ($result->rows as $row) {
            $worksheet->write($i, $j++, $row['product_id']);
            $worksheet->write($i, $j++, $row['model']);
            $worksheet->writeString($i, $j++, isset($row['option_name']) ? $row['option_name'] : '');
            $worksheet->writeString($i, $j++, isset($row['option_name_2']) ? $row['option_name_2'] : '');
            $worksheet->writeString($i, $j++, isset($row['option_value']) ? $row['option_value'] : '');
            $worksheet->writeString($i, $j++, isset($row['option_value_2']) ? $row['option_value_2'] : '');
            $worksheet->write($i, $j++, $row['option_quantity']);
            $worksheet->write($i, $j++, ($row['option_subtract'] == 0) ? "false" : "true", $textFormat);
            $worksheet->write($i, $j++, $row['option_price'], $priceFormat);
            $worksheet->writeString($i, $j++, $row['option_prefix'], $textFormat);
            $worksheet->write($i, $j++, $row['sort_order']);
            $i += 1;
            $j = 0;
        }
    }


    function populateSpecialsWorksheet(&$worksheet, &$database, &$priceFormat, &$boxFormat, &$textFormat, $categories) {
        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, strlen('product_id') + 1);
        $worksheet->setColumn($j, $j++, strlen('model') + 1);
        $worksheet->setColumn($j, $j++, strlen('customer_group') + 1);
        $worksheet->setColumn($j, $j++, strlen('priority') + 1);
        $worksheet->setColumn($j, $j++, max(strlen('price'), 10) + 1, $priceFormat);
        $worksheet->setColumn($j, $j++, max(strlen('date_start'), 19) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('date_end'), 19) + 1, $textFormat);

        // The heading row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'product_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'model', $boxFormat);
        $worksheet->writeString($i, $j++, 'customer_group', $boxFormat);
        $worksheet->writeString($i, $j++, 'priority', $boxFormat);
        $worksheet->writeString($i, $j++, 'price', $boxFormat);
        $worksheet->writeString($i, $j++, 'date_start', $boxFormat);
        $worksheet->writeString($i, $j++, 'date_end', $boxFormat);
        $worksheet->setRow($i, 30, $boxFormat);

        // The actual product specials data
        $i += 1;
        $j = 0;
        $query = "SELECT ps.*,p.model, cg.name FROM `" . DB_PREFIX . "product_special` ps ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "customer_group` cg ON cg.customer_group_id=ps.customer_group_id ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product` p ON p.product_id=ps.product_id ";
        if ($categories != '')
            $query .= " WHERE  p.product_id  IN (SELECT product_id FROM product_to_category WHERE category_id IN(" . $categories . ") ) ";
        $query .= "ORDER BY ps.product_id, cg.name";
        $result = $database->query($query);
        foreach ($result->rows as $row) {
            $worksheet->write($i, $j++, $row['product_id']);
            $worksheet->write($i, $j++, $row['model']);
            $worksheet->write($i, $j++, $row['name']);
            $worksheet->write($i, $j++, $row['priority']);
            $worksheet->write($i, $j++, $row['price'], $priceFormat);
            $worksheet->write($i, $j++, $row['date_start'], $textFormat);
            $worksheet->write($i, $j++, $row['date_end'], $textFormat);
            $i += 1;
            $j = 0;
        }
    }


    function populateDiscountsWorksheet(&$worksheet, &$database, &$priceFormat, &$boxFormat, &$textFormat, $categories) {
        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, strlen('product_id') + 1);
        $worksheet->setColumn($j, $j++, strlen('model') + 1);
        $worksheet->setColumn($j, $j++, strlen('customer_group') + 1);
        $worksheet->setColumn($j, $j++, strlen('quantity') + 1);
        $worksheet->setColumn($j, $j++, strlen('priority') + 1);
        $worksheet->setColumn($j, $j++, max(strlen('price'), 10) + 1, $priceFormat);
        $worksheet->setColumn($j, $j++, max(strlen('date_start'), 19) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('date_end'), 19) + 1, $textFormat);

        // The heading row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'product_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'model', $boxFormat);
        $worksheet->writeString($i, $j++, 'customer_group', $boxFormat);
        $worksheet->writeString($i, $j++, 'quantity', $boxFormat);
        $worksheet->writeString($i, $j++, 'priority', $boxFormat);
        $worksheet->writeString($i, $j++, 'price', $boxFormat);
        $worksheet->writeString($i, $j++, 'date_start', $boxFormat);
        $worksheet->writeString($i, $j++, 'date_end', $boxFormat);
        $worksheet->setRow($i, 30, $boxFormat);

        // The actual product discounts data
        $i += 1;
        $j = 0;
        $query = "SELECT pd.*,p.model, cg.name FROM `" . DB_PREFIX . "product_discount` pd ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "customer_group` cg ON cg.customer_group_id=pd.customer_group_id ";
        $query .= "LEFT JOIN `" . DB_PREFIX . "product` p ON p.product_id=pd.product_id ";
        if ($categories != '')
            $query .= " WHERE  p.product_id  IN (SELECT product_id FROM product_to_category WHERE category_id IN(" . $categories . ") ) ";
        $query .= "ORDER BY pd.product_id, cg.name";
        $result = $database->query($query);
        foreach ($result->rows as $row) {
            $worksheet->write($i, $j++, $row['product_id']);
            $worksheet->write($i, $j++, $row['model']);
            $worksheet->write($i, $j++, $row['name']);
            $worksheet->write($i, $j++, $row['quantity']);
            $worksheet->write($i, $j++, $row['priority']);
            $worksheet->write($i, $j++, $row['price'], $priceFormat);
            $worksheet->write($i, $j++, $row['date_start'], $textFormat);
            $worksheet->write($i, $j++, $row['date_end'], $textFormat);
            $i += 1;
            $j = 0;
        }
    }


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


    function download($data) {
        global $config;
        global $log;
        $config = $this->config;
        $log = $this->log;
        //set_error_handler('error_handler_for_export', E_ALL);
        //register_shutdown_function('fatal_error_shutdown_handler_for_export');
        register_shutdown_function(error_handler);
        $database =& $this->db;
        $languageId = $this->getDefaultLanguageId($database);

        // We use the package from http://pear.php.net/package/Spreadsheet_Excel_Writer/
        chdir('../system/pear');
        require_once "Spreadsheet/Excel/Writer.php";
        chdir('../../admin');

        // Creating a workbook
        $workbook = new Spreadsheet_Excel_Writer();
        $workbook->setTempDir(DIR_CACHE);
        $workbook->setVersion(8); // Use Excel97/2000 Format
        $priceFormat =& $workbook->addFormat(array('Size' => 10, 'Align' => 'right', 'NumFormat' => '######0.00'));
        $boxFormat =& $workbook->addFormat(array('Size' => 10, 'vAlign' => 'vequal_space'));
        $weightFormat =& $workbook->addFormat(array('Size' => 10, 'Align' => 'right', 'NumFormat' => '##0.00'));
        $textFormat =& $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@"));


        $c = 0;
        $categories = '';
        $categories_id = '';
        foreach ($data['category'] as $category_id) {
            if (count($data['category']) == 1) {
                $categories .= $category_id;
                $categories_id .= $category_id;
            } else {
                if ($c == 0) {
                    $categories .= $category_id;
                    $categories_id .= $category_id;
                } else {
                    $categories .= ',' . $category_id;
                    $category_id .= '_' . $category_id;
                }
                $c++;
            }
        }
        //	throw new exception($categories);
        // sending HTTP headers
        $workbook->send($category_id . '_categories_products.xls');

        // Creating the categories worksheet
        $worksheet =& $workbook->addWorksheet('Categories');
        $worksheet->setInputEncoding('UTF-8');
        $this->populateCategoriesWorksheet($worksheet, $database, $languageId, $boxFormat, $textFormat, $categories);
        $worksheet->freezePanes(array(1, 1, 1, 1));

        // Get all additional product images
        $imageNames = array();
        $query = "SELECT DISTINCT ";
        $query .= "  p.product_id, ";
        $query .= "  pi.product_image_id AS image_id, ";
        $query .= "  pi.image AS filename ";
        $query .= "FROM `" . DB_PREFIX . "product` p ";
        $query .= "INNER JOIN `" . DB_PREFIX . "product_image` pi ON pi.product_id=p.product_id ";
        $query .= "ORDER BY product_id, image_id; ";
        $result = $database->query($query);
        foreach ($result->rows as $row) {
            $productId = $row['product_id'];
            $imageId = $row['image_id'];
            $imageName = $row['filename'];
            if (!isset($imageNames[$productId])) {
                $imageNames[$productId] = array();
                $imageNames[$productId][$imageId] = $imageName;
            } else {
                $imageNames[$productId][$imageId] = $imageName;
            }
        }

        // Creating the products worksheet
        $worksheet =& $workbook->addWorksheet('Products');
        $worksheet->setInputEncoding('UTF-8');
        $this->populateProductsWorksheet($worksheet, $database, $imageNames, $languageId, $priceFormat, $boxFormat, $weightFormat, $textFormat, $categories);
        $worksheet->freezePanes(array(1, 1, 1, 1));

        // Creating the options worksheet
        $worksheet =& $workbook->addWorksheet('Options');
        $worksheet->setInputEncoding('UTF-8');
        $this->populateOptionsWorksheet($worksheet, $database, $languageId, $priceFormat, $boxFormat, $textFormat, $categories);
        $worksheet->freezePanes(array(1, 1, 1, 1));

        // Creating the specials worksheet
        $worksheet =& $workbook->addWorksheet('Specials');
        $worksheet->setInputEncoding('UTF-8');
        $this->populateSpecialsWorksheet($worksheet, $database, $priceFormat, $boxFormat, $textFormat, $categories);
        $worksheet->freezePanes(array(1, 1, 1, 1));

        // Creating the discounts worksheet
        $worksheet =& $workbook->addWorksheet('Discounts');
        $worksheet->setInputEncoding('UTF-8');
        $this->populateDiscountsWorksheet($worksheet, $database, $priceFormat, $boxFormat, $textFormat, $categories);
        $worksheet->freezePanes(array(1, 1, 1, 1));

        // Let's send the file
        $workbook->close();

        // Clear the spreadsheet caches
        $this->clearSpreadsheetCache();
        exit;
    }

    private function getManufacturerId(&$database, $manufacturer) {
        $sql = "SELECT manufacturer_id FROM `" . DB_PREFIX . "manufacturer` WHERE name='" . $database->escape($manufacturer) . "'";

        $query = $database->query($sql);

        if ($query->row) {
            return $query->row['manufacturer_id'];
        } else {
            return 0;
        }
    }
}

?>