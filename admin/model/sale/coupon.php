<?php

class ModelSaleCoupon extends Model {
    private function existSameCode($code) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "select coupon_id from {$DB_PREFIX}coupon WHERE code='{$code}' limit 1";
        $dbId = DbHelper::getSingleValue($sql, null);
        return !empty($dbId);
    }

  
    /**
     * 生成随机码
     * @param unknown $codePrefix
     * @throws exception
     * @return string
     */
    private function getNextCode($length, $prefix=null) {
        $tryTimes = 0;
        while ($tryTimes < 100) {
            $tryTimes++;
    
            $code = Maths::genRandomCode($length, $prefix);
    
            if (!$this->existSameCode($code)) {
                return $code;
            }
        }
        throw new exception('生成ID失败');
    }
    

    /**
     * 批量生成优惠券
     * @param unknown $data
     */
    public function batchAddCoupon($data) {
        $batch = (int)$data['batch'];
        if ($batch == 1) {
            $this->addCoupon($data);
        } else {
            for ($i = 0; $i < $batch; $i++) {
                $dataCp = array_merge(array(), $data);
                $dataCp['code'] = $this->getNextCode(15, $data['code']);
           //     $dataCp['name'] = $dataCp['name'] . $i;
                $this->addCoupon($dataCp);
            }
        }

    }

    public function addCoupon($data) {
        $pendingData = array(
            array('name', $data['name'], true, true),
            array('code', $data['code'], true, true),
            array('discount', (float)$data['discount'], true, false),
            array('total', $data['total'], true, false),
            array('type', $data['type'], true, true),
            array('logged', $data['logged'], true, false),
            array('shipping', $data['shipping'], true, false),
            array('date_start', $data['date_start'], true, false),
            array('date_end', $data['date_end'], true, false),
            array('duration', $data['duration'], true, false),
            array('`usage`', $data['usage'], true, true),
            array('uses_total', $data['uses_total'], true, false),
            array('uses_customer', $data['uses_customer'], true, false),
        	array('free_get', $data['free_get'], true, false),
        	array('mutual_prom', $data['mutual_prom'], true, false),
            array('status', $data['status'], true, false),
            array('date_added', 'now()', false, false),
            array('creator_id', $data['creator_id'], false, false),
            array('owner_id', $data['owner_id'], false, false),
        		array('`share_title`', $data['share_title'], true, true),
        		array('`share_desc`', $data['share_desc'], true, true),
        		array('`share_image`', $data['share_image'], true, true),
        		array('`share_image1`', $data['share_image1'], true, true),
        		array('`share_image2`', $data['share_image2'], true, true),
        		array('`share_image3`', $data['share_image3'], true, true),
        		array('`share_btn`', $data['share_btn'], true, true),
        		array('`share_bg`', $data['share_bg'], true, true),
        		array('`share_link`', $data['share_link'], true, true),
        );
        DbHelper::insert('coupon', $pendingData);

        $ret = $this->db->query("SELECT coupon_id FROM " . DB_PREFIX . "coupon WHERE code = '" .  $data['code'] . "' LIMIT 1");
        $coupon_id = $ret->row['coupon_id'];

        if (isset($data['coupon_product'])) {
            foreach ($data['coupon_product'] as $product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
            }
        }
    }

    /**
     * 更新优惠券信息
     * @param unknown $coupon_id
     * @param unknown $data
     */
    public function editCoupon($coupon_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) .
                         "', code = '" . $this->db->escape($data['code']) . "'
        		, share_title = '" . $this->db->escape($data['share_title']) . "'
        		, share_desc = '" . $this->db->escape($data['share_desc']) . "'
        		, share_image = '" . $this->db->escape($data['share_image']) . "'
        		, share_image1 = '" . $this->db->escape($data['share_image1']) . "'
        		, share_image2 = '" . $this->db->escape($data['share_image2']) . "'
        		, share_image3 = '" . $this->db->escape($data['share_image3']) . "'
        		, share_btn = '" . $this->db->escape($data['share_btn']) . "'
        		, share_bg = '" . $this->db->escape($data['share_bg']) . "', share_link = '" . $this->db->escape($data['share_link']) . "', discount = '" . (float)$data['discount'] . 
                         "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . 
                         "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . 
                         $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', duration = '" . 
                         (int)$data['duration'] . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . 
                         (int)$data['uses_customer'] . "', owner_id = '" . (int)$data['owner_id'] . "', free_get = '" . (int)$data['free_get'] . "', mutual_prom = '" . (int)$data['mutual_prom'] . "', status = '" . (int)$data['status'] . 
                         "', `usage`='" .  $this->db->escape($data['usage']) . "' WHERE coupon_id = '" . (int)$coupon_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");

        if (isset($data['coupon_product'])) {
            foreach ($data['coupon_product'] as $product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
            }
        }
    }

    public function deleteCoupon($coupon_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");
    }

    public function getCoupon($coupon_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");

        return $query->row;
    }

    public function getCoupons($data = array()) {
        $DB_PREFIX = DB_PREFIX;

        $sqlBody = "FROM {$DB_PREFIX}coupon c"
            . " left join {$DB_PREFIX}user u on u.user_id=c.owner_id where 1=1 ";

        $name = $data['name'];
        $owner_id = $data['owner_id'];
        if (!empty($name)) {
            $name = $this->db->escape($name);
            $sqlBody .= "and name like '%{$name}%' ";
        }
        if (!empty($owner_id)) {
            $sqlBody .= "and owner_id ='{$owner_id}' ";
        }

        $total=DbHelper::getSingleValue('select count(*) '.$sqlBody,0); //总条数

        $sql = 'SELECT c.*,u.firstname as ownerName ' . $sqlBody;
        $sort_data = array(
            'name',
            'code',
            'discount',
            'date_start',
            'date_end',
            'status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY coupon_id";
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
                $data['limit'] = 20;  //分页 一页显示20条
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        $rows= $query->rows;
        return array(
            "total"=>$total,
            "rows"=>$rows
        );
    }

    public function getCouponProducts($coupon_id) {
        $coupon_product_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");

        foreach ($query->rows as $result) {
            $coupon_product_data[] = $result['product_id'];
        }

        return $coupon_product_data;
    }

//    public function getTotalCoupons() {
//        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon");
//
//        return $query->row['total'];
//    }

    public function getCouponHistories($coupon_id, $start = 0, $limit = 10) {
        $query = $this->db->query("SELECT ch.order_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.mobile, ch.amount, ch.date_added FROM " . DB_PREFIX . "coupon_history ch LEFT JOIN " . DB_PREFIX . "customer c ON (ch.customer_id = c.customer_id) WHERE ch.coupon_id = '" . (int)$coupon_id . "' ORDER BY ch.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalCouponHistories($coupon_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");

        return $query->row['total'];
    }
	
	/**
	 * 客服操作 退回优惠卷  
	 * @param type $orderids
	 * @return boolean
	 */
	public function return_coupon($order_ids){
		if(empty($order_ids)){
			return false;
		}
		$id_str = '(';
		foreach($order_ids as $v){
			$id_str .= "'".$v."',";
		}
		$id_str = rtrim($id_str,',').")";
//		$id_str = '('.implode(',', $order_ids).')';
		$sql = "update ".DB_PREFIX."coupon_to_customer c "
			. "LEFT JOIN ".DB_PREFIX."coupon_history h "
			. "on c.coupon_customer_id = h.coupon_customer_id "
			. "set c.used = 0 "
			. "where h.order_id in {$id_str}";
//		echo $sql;exit;
		$this->db->query($sql);
	}
}

?>