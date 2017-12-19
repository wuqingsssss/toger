<?php

class ModelCatalogPacket extends Model {
    public function getall($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "packet ";

        if(isset($data['filter_name']) && !is_null($data['filter_name'])){
            $sql.=" WHERE name like '%".$this->db->escape($data['filter_name'])."%' ";
        }

        $sort_data = array(

        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY packet_id";
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


    public function getTotalPoints($data) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "packet ";
        if(isset($data) && isset($data['filter_name']) && !is_null($data['filter_name'])){
            $sql.=" WHERE name like '%".$this->db->escape($data['filter_name'])."%' ";
        }
        $query = $this->db->query($sql);



        return $query->row['total'];
    }


    public function create($data) {
        $params = array();
        $fieldName = 'name';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'date_start';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'date_end';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'type';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'pick_type';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'cond';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'batch';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'info';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'share_title';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'share_desc';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'share_image';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
//        $fieldName = 'creator_id';
//        $params[$fieldName] = array($fieldName, $data[$fieldName], false, false);

        $fieldName = 'date_added';
        $params[$fieldName] = array($fieldName, 'now()', false, false);

        $id = DbHelper::insert('packet', $params);

        $productIds = json_decode(str_replace('&quot;', '"', $data['productIds']));
        $this->updateProducts($id, $productIds);

        $this->refreshProductSupplyPeriods();
    }

    private function insertProducts($periodId, $productIds) {
        $colDefines = array(
            array('code', false, false),
            array('packet_id', false, false)
        );
        $data = array();
        foreach ($productIds as $pid) {
            $data[] = array("'{$pid}'", $periodId);
        }
        DbHelper::bulkInsert('packet_item', $colDefines, $data);
    }

    private function removeProducts($periodId, $productIds='') {
        $DB_PREFIX = DB_PREFIX;
        if($productIds){
           foreach ($productIds as $pid) {
            $sql = "delete from {$DB_PREFIX}packet_item where code='{$pid}' and packet_id={$periodId} ";
            $this->db->query($sql);
           }
        }else 
        {
        $sql = "delete from {$DB_PREFIX}packet_item where packet_id={$periodId} ";
        $this->db->query($sql);
        }
        
    }

    private function updateProducts($periodId, $productIds) {
      //  $productIds=array_unique($productIds);
       // $oldPids = $this->getOldProductIds($periodId);
        //$deletedPids = array_diff($oldPids, $productIds);
      //  $addedPids = array_diff($productIds, $oldPids);

        $this->removeProducts($periodId);
        if (!empty($productIds)) {
            $this->insertProducts($periodId, $productIds);
        }
        
    }

    public function update($id, $data) {
        $params = array();
        $fieldName = 'name';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'date_start';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'date_end';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'type';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'pick_type';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'cond';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'batch';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'info';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'share_title';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'share_desc';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'share_image';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        
        
        $pkArr = array(
            array('packet_id', $id, false),
        );
        DbHelper::update('packet', $pkArr, $params);

        $productIds = json_decode(str_replace('&quot;', '"', $data['productIds']));
        
        $this->updateProducts($id, $productIds);
    }

    public function get($id) {
        $pkArr = array(
            array('packet_id', $id, false),
        );

        return DbHelper::get('packet', $pkArr);
    }

    public function delete($id) {
        $DB_PREFIX = DB_PREFIX;
        $id = (int)$id;

        $sql = "delete from {$DB_PREFIX}supply_period where id={$id}";
        $this->db->query($sql);

        $sql = "delete from {$DB_PREFIX}product_supply_period where period_id={$id}";
        $this->db->query($sql);
    }

    public function getProducts($periodId) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "select * from {$DB_PREFIX}packet_item p inner join {$DB_PREFIX}coupon c on (c.code=p.code)  WHERE p.packet_id={$periodId}";
        $q = $this->db->query($sql);
        return $q->rows;
    }

    private function getOldProductIds($periodId) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "select * from {$DB_PREFIX}packet_item where packet_id=" . $periodId;
        $q = $this->db->query($sql);
        $oldPids = array();
        foreach ($q->rows as $item) {
            $oldPids[] = $item['code'];
        }
        return $oldPids;
    }

    public function refreshProductSupplyPeriods() {
        $DB_PREFIX = DB_PREFIX;

        $now = new DateTime();
        $nowStr = ($now->format('Y-m-d')) . ' 00:00:00';
        $sql = "select sp.id from {$DB_PREFIX}supply_period sp "
            . "where sp.start_date<='{$nowStr}' and sp.end_date>='{$nowStr}' "
            . "order by sp.start_date limit 1 ";
        $activePeriodId = DbHelper::getSingleValue($sql, null);

        $updateSql = null;
        if (is_null($activePeriodId)) {
            $updateSql = "update {$DB_PREFIX}product p set p.`status`='0' where p.product_id in "
                . "(select pp.product_id from {$DB_PREFIX}product_supply_period pp) ";
        } else {
            $updateSql = "update {$DB_PREFIX}product p, {$DB_PREFIX}product_supply_period pp,{$DB_PREFIX}supply_period sp "
                . "set p.`status`='1' , p.date_available=sp.start_date "
                . "where p.product_id=pp.product_id and pp.period_id={$activePeriodId} and sp.id=pp.period_id ";
        }
        $this->db->query($updateSql);
    }

    public function search_name($name){
        $DB_PREFIX = DB_PREFIX;
        if(!$name){
            return array();
        }
        $sql="select * from {$DB_PREFIX}coupon where name like '%{$name}%' or code like '%{$name}%' ";
        $query = $this->db->query($sql);
        $rows= $query->rows;
        return $rows;
    }

    /**
     * 
     * @param unknown $name
     * @return multitype:|unknown
     */
    public function searchPakcetByName($name){
        if(!$name){
            return array();
        }
        $sql="SELECT name,packet_id,batch FROM ts_packet WHERE name LIKE '%{$name}%' AND status=1 ";
        $query = $this->db->query($sql);
        $rows= $query->rows;
        return $rows;
    }

}

?>