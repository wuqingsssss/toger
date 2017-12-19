<?php

class ModelSaleOrderDelivery extends Model {
    public function getToDeliveryOrders($data = array()) {
        $pointId = $data['filter_point_id'];
        $DB_PREFIX = DB_PREFIX;
        $langId = (int)$this->config->get('config_language_id');
        $sqlBody = $this->buildSqlBody($data, $DB_PREFIX, $pointId);

        $total = DbHelper::getSingleValue('select count(*) ' . $sqlBody, 0);

        $qSql = "SELECT o.order_id,o.partner_code , o.customer_id,o.pdate,o.p_order_id, CONCAT(o.firstname) AS "
            . "customer,o.telephone,o.email, o.shipping_firstname AS username ,(SELECT os.name FROM "
            . "{$DB_PREFIX}order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '{$langId}' "
            . ") AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.shipping_method,o.shipping_point_id ";
        $qSql .= $sqlBody;
        $qSql .= "order by o.order_id ";
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $qSql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($qSql);
        $rows = $query->rows;
        return array(
            "total" => $total,
            "rows" => $rows
        );
    }

    private function buildSqlBody($data, $DB_PREFIX, $pointId) {
        $sqlBody = "from {$DB_PREFIX}order_purchase p join {$DB_PREFIX}order_purchase_relation r on r.purchase_id=p.id "
            . "join {$DB_PREFIX}order o on o.order_id=r.order_id "
            . "where p.`status`='DONE' and o.order_status_id='18' and o.shipping_point_id={$pointId} ";
        if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
            $sqlBody .= " AND o.order_id = '" . $data['filter_order_id'] . "'";
        }

        if (isset($data['filter_partner_code']) && !is_null($data['filter_partner_code'])) {
            $sqlBody .= " AND o.partner_code = '" . $data['filter_partner_code'] . "'";
        }

        if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
            $sqlBody .= " AND CONCAT(email) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        $filter_customer_phone = $data['filter_customer_phone'];
        if (isset($filter_customer_phone) && !is_null($filter_customer_phone)) {
            $sqlBody .= " AND telephone LIKE '%" . $this->db->escape($data['filter_customer_phone']) . "%'";
        }

        if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
            $sqlBody .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }
        if (isset($data['filter_pdate']) && !is_null($data['filter_pdate'])) {
            $sqlBody .= " AND o.pdate like  '" . $this->db->escape($data['filter_pdate']) . "%' ";
        }

        if (isset($data['filter_date_modified']) && !is_null($data['filter_date_modified'])) {
            $sqlBody .= " AND o.pdate = '" . $this->db->escape($data['filter_date_modified']) . "'";
        }

        if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
            $sqlBody .= " AND o.total = '" . (float)$data['filter_total'] . "'";
            return $sqlBody;
        }
        return $sqlBody;
    }

    private function getTodayStr() {
        $endDate = new DateTime();
//        $endDate->modify('+1 day');
        return date_format($endDate, 'Y-m-d');
    }

    public function getAllToDeliveryDishes($pointId) {
        $DB_PREFIX=DB_PREFIX;
        $pdate=$this->getTodayStr();
        $sql = "select p.name,sum(p.quantity) num,pd.unit,o2.partner_code "
            . "from {$DB_PREFIX}order_product p left join {$DB_PREFIX}product pp on pp.product_id=p.product_id "
            . "left join {$DB_PREFIX}product_description pd on pd.product_id=p.product_id "
            . "left join {$DB_PREFIX}order o2 on o2.order_id=p.order_id "
            . "where p.order_id in ( "
            . "select o.order_id "
            . "from {$DB_PREFIX}order_purchase p join {$DB_PREFIX}order_purchase_relation r on r.purchase_id=p.id "
            . "join {$DB_PREFIX}order o on o.order_id=r.order_id "
            . "where p.`status`='DONE' and o.order_status_id='18' and o.shipping_point_id={$pointId} and o.pdate like '{$pdate}%' "
            . ")  group by p.product_id order by p.name ";

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getAllToDeliveryOrders($pointId) {
        $DB_PREFIX=DB_PREFIX;
        $pdate=$this->getTodayStr();
        $sql="select  p.name,p.quantity num,pd.unit,p.order_id,o2.telephone,o2.firstname as customer_name "
            ."from {$DB_PREFIX}order_product p left join {$DB_PREFIX}product pp on pp.product_id=p.product_id "
            ."left join {$DB_PREFIX}product_description pd on pd.product_id=p.product_id "
            ."left join {$DB_PREFIX}order o2 on o2.order_id=p.order_id "
            ."where p.order_id in ( "
            ."select o.order_id "
            ."from {$DB_PREFIX}order_purchase p join {$DB_PREFIX}order_purchase_relation r on r.purchase_id=p.id "
            ."join {$DB_PREFIX}order o on o.order_id=r.order_id "
            ."where p.`status`='DONE' and o.order_status_id='18' and o.shipping_point_id={$pointId} and o.pdate like '{$pdate}%' "
            .") order by p.order_id,p.name ";

        $query = $this->db->query($sql);
        return $query->rows;
    }
}

?>