<?php

class ModelSaleOrderPurchase extends Model {


    public function countPayedOrders() {
        $endDateStr = $this->getTodayStr();
        $DB_PREFIX = DB_PREFIX;

        $sql = "SELECT COUNT(*) TOTAL FROM {$DB_PREFIX}order t "
            . "WHERE t.order_status_id=2 and t.pdate is not null and t.pdate like '{$endDateStr}%' ";
        $query = $this->db->query($sql);
        $rows = $query->rows;
        return isset($rows) && count($rows) > 0 ? $rows[0]['TOTAL'] : 0;
    }

    public function getToBePurchasedOrderProducts() {
        $DB_PREFIX = DB_PREFIX;
        $endDateStr = $this->getTodayStr();

        $sql = "select sum(p.quantity) quantity,pd.`name`,p.product_id "
            . "from {$DB_PREFIX}order o join {$DB_PREFIX}order_product p on p.order_id=o.order_id "
            . "left join {$DB_PREFIX}product_description pd on pd.product_id=p.product_id "
            . "WHERE o.order_status_id=2  and o.pdate is not null and o.pdate like '{$endDateStr}%' "
            . "group by p.product_id "
            . "order by pd.name ";

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getToBePurchasedOrderIds() {
        $DB_PREFIX = DB_PREFIX;
        $endDateStr = $this->getTodayStr();

        $sql = "SELECT t.order_id FROM {$DB_PREFIX}order t "
            . "WHERE t.order_status_id=2 and t.pdate is not null and t.pdate like '{$endDateStr}%' ";

        $query = $this->db->query($sql);
        return ArrayHelper::pickFields($query->rows, 'order_id');
    }

    public function getOrderPurchase($orderId) {
        $sql = "SELECT * FROM " . DB_PREFIX . "order_purchase t WHERE t.order_id='" . $orderId . "'";
        $query = $this->db->query($sql);
        $rows = $query->rows;
        return isset($rows) && count($rows) > 0 ? $rows[0] : null;
    }

    private function insertOrderPurchase($userId, $operate_date) {
        $createdAt = (new DateTime());
        $createdAtStr = $createdAt->format('Y-m-d H:i:s');
        $data = array(
            array('status', 'PENDING', true),
            array('created_at', $createdAtStr, true),
            array('operate_date', $operate_date, true)
        );
        if (isset($userId)) {
            $data[] = array('user_id', $userId, false);
        }

        $id = DbHelper::insert('order_purchase', $data);

        $pkArr = array(
            array('id', $id, false)
        );

        $serial_no = $this->buildSerialNo($id, $createdAt);
        DbHelper::update('order_purchase', $pkArr, array(array('serial_no', $serial_no, true)));

        return DbHelper::get('order_purchase', $pkArr);
    }

    private function insertProduct($formProduct, $purchase_order_id) {
        $data = array(
            array('purchase_id', $purchase_order_id, false),
            array('product_id', $formProduct['pid'], false),
            array('comment', $formProduct['cmt'], true),
            array('quantity', $formProduct['num'], false)
        );
        DbHelper::insert('order_purchase_product', $data);
    }

    private function insertPurchaseRelation($purchase_order_id, $order_ids) {
        $columnsMeta = array(
            array('purchase_id', false),
            array('order_id', false),
        );

//        $rows = array_map(function ($item) use ($purchase_order_id) {
//            return array($purchase_order_id, $item);
//        }, $order_ids);
        $rows = array();
        foreach ($order_ids as $item) {
            $rows[] = array($purchase_order_id, $item);
        }
        DbHelper::bulkInsert('order_purchase_relation', $columnsMeta, $rows);
    }

    private function updateOrder2Purchased($order_id) {
        $this->load->model('sale/order');

        $statusId = 18;

        $data = array(
            array('order_status_id', $statusId, false),
        );
        $pkArr = array(
            array('order_id', $order_id, true)
        );
        DbHelper::update('order', $pkArr, $data);

        //history
        $history = array(
            'order_status_id' => $statusId,
            'notify' => 0,
            'comment' => '生成生产发注'
        );
        $this->model_sale_order->insertHistoryData($order_id, $history);
    }

    private function buildSerialNo($id, $created_at) {
        return $created_at->format('Ymd') . sprintf('%06d', $id);
    }

    private function getPurchasedOrderProducts($purchaseOrderId) {
        $DB_PREFIX = DB_PREFIX;

        $sql = "select p.*,pd.`name` "
            . "from {$DB_PREFIX}order_purchase_product p left join {$DB_PREFIX}product_description pd on pd.product_id=p.product_id "
            . "WHERE p.purchase_id={$purchaseOrderId} order by pd.name ";

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getDetail($purchaseOrderId) {
        $purchaseOrder = DbHelper::get('order_purchase', array(array('id', $purchaseOrderId, false)));
        if (is_null($purchaseOrder)) {
            return null;
        }

        $statusDefineMap = $this->getStatusDefineMap();
        $purchaseOrder['status_text'] = $statusDefineMap[$purchaseOrder['status']];

        $result = array();
        $result['order'] = $purchaseOrder;
        $result['products'] = $this->getPurchasedOrderProducts($purchaseOrderId);
        return $result;
    }

    public function autoSaveOrderPurchases() {
        $orderIds = $this->getToBePurchasedOrderIds();
        if(empty($orderIds)){
            return;
        }

        $productsRaw = $this->getToBePurchasedOrderProducts();
        $products=array();
        foreach($productsRaw as $pdt){
            $products[]=array(
                'num'=>$pdt['quantity'],
                'name'=>$pdt['name'],
                'pid'=>$pdt['product_id']
            );
        }

        $now = new DateTime();
        $opDate = $now->format('Y-m-d');
        $this->createOrderPurchase(null, $opDate, $products, $orderIds);
    }


    public function createOrderPurchase($userId, $operateDate, $products = array(), $orderIds = array()) {
        asort($orderIds);
        $orderIds = array_unique($orderIds);
        //create p order
        $order = $this->insertOrderPurchase($userId, $operateDate);
        $purchase_id = $order['id'];
        $this->insertPurchaseRelation($purchase_id, $orderIds);
        foreach ($products as $p) {
            $this->insertProduct($p, $purchase_id);

            $orderIds[] = $p['order_id'];
        }

        asort($orderIds);
        $orderIds = array_unique($orderIds);
        //change order statuses

        foreach ($orderIds as $oid) {
            $this->updateOrder2Purchased($oid);
        }

        return $purchase_id;
    }

    private function getTodayStr() {
        $endDate = new DateTime();
//        $endDate->modify('+1 day');
        return date_format($endDate, 'Y-m-d');
    }

    public function queryPurchaseOrders($status, $serialNo, $pagingParams) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "FROM {$DB_PREFIX}order_purchase p WHERE 1=1 ";

        if (!empty($status)) {
            $sql .= "and p.status = '" . $status . "' ";
        }
        if (!empty($serialNo)) {
            $sql .= "and p.serial_no like '%" . $serialNo . "%' ";
        }


        $total = DbHelper::getSingleValue('select count(*) ' . $sql, 0);


        $sql = $this->appendSorting($pagingParams, $sql);
        $sql = $this->appendLimiting($pagingParams, $sql);
        $sql = 'select * ' . $sql;

        $rows = $query = $this->db->query($sql)->rows;
        if (!empty($rows)) {
            $statusDefineMap = $this->getStatusDefineMap();
            $result = array();
            foreach ($rows as $row) {
                $st = $row['status'];
                $row['status_text'] = $statusDefineMap[$st];
                $result[] = $row;
            }
            $rows = $result;

//            $rows = array_map(function ($row) use ($statusDefineMap) {
//                $st = $row['status'];
//                $row['status_text'] = $statusDefineMap[$st];
//                return $row;
//            }, $rows);
        }

        return array(
            'total' => $total,
            'rows' => $rows
        );
    }

    private function getStatusDefineMap() {
        return array(
            'PENDING' => '进行',
            'DONE' => '完成'
        );
    }

    /**
     * @param $pagingParams
     * @param $sql
     * @return string
     */
    private function appendLimiting($pagingParams, $sql) {
        $start = $pagingParams['start'];
        $limit = $pagingParams['limit'];
        if (isset($start) || isset($limit)) {
            if ($start < 0) {
                $start = $pagingParams['start'] = 0;
            }

            if ($limit < 1) {
                $limit = $pagingParams['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$start . "," . (int)$limit;
            return $sql;
        }
        return $sql;
    }

    /**
     * @param $pagingParams
     * @param $sql
     * @return string
     */
    private function appendSorting($pagingParams, $sql) {
        $sort = $pagingParams['sort'];
        $order = $pagingParams['order'];
        if (!empty($sort) && !empty($order)) {
            $sql .= " ORDER BY {$sort} {$order}";
            return $sql;
        }
        return $sql;
    }

    public function updatePurchaseOrderStatus($orderId, $status) {
        $this->load->model('sale/order');


        $data = array(
            array('status', $status, true, true),
        );
        $pkArr = array(
            array('id', $orderId, false)
        );
        DbHelper::update('order_purchase', $pkArr, $data);

    }

    public function getOrderIdsPickupTomorrow($data) {
        $DB_PREFIX = DB_PREFIX;

        if(isset($data) && isset($data['filter_pdate'])){
            $endDateStr=$data['filter_pdate'];
        }else{
            $endDate = new DateTime();
            $endDate->modify('+1 day');
            $endDateStr = date_format($endDate, 'Y-m-d');
        }

        $sql = "SELECT t.order_id FROM {$DB_PREFIX}order t "
            . "WHERE t.order_status_id=2 and t.pdate is not null and t.pdate like '{$endDateStr}%' ";

        $query = $this->db->query($sql);
        return ArrayHelper::pickFields($query->rows, 'order_id');
    }

    public function getOrdersPickupTomorrow($filter) {
        $orderIds = $this->getOrderIdsPickupTomorrow($filter);
        $result = array();
        if (empty($orderIds)) {
            return $result;
        }

        $sql = "SELECT o.order_id,o.partner_code , o.customer_id,o.pdate,o.p_order_id, CONCAT(o.firstname) AS customer,o.telephone,o.email, o.shipping_firstname AS username ,(SELECT os.name FROM " . DB_PREFIX
            . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id')
            . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.shipping_method,o.shipping_point_id FROM `" . DB_PREFIX . "order` o ";
        $sql .= " where o.order_id in (" . join(',', $orderIds) . ") ";

        $pdate = $filter['filter_pdate'];
        if(!empty($pdate)){
            $sql.="and o.pdate like '{$pdate}%' ";
        }

        $sql .= " ORDER BY o.date_added DESC,o.date_modified DESC,o.order_id asc";
        $query = $this->db->query($sql);
        return $query->rows;
    }
}

?>