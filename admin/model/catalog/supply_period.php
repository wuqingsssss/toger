<?php

class ModelCatalogSupplyPeriod extends Model {
    public function all($data=array()) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "select sp.*,count(pp.product_id) productsNum "
            . "from {$DB_PREFIX}supply_period sp left join {$DB_PREFIX}product_supply_period pp on pp.period_id=sp.id where 1=1 ";
        
        $sql .= $this->filterSql($data);      
        $sql.= " group by sp.id order by sp.start_date DESC";

        $q = $this->db->query($sql);
        return $q->rows;
    }
    private function filterSql($data = array()){
    	$sql = "";
    	if(isset($data['filter_show_date'])&&!is_null($data['filter_show_date'])){
    		$sql .= " and sp.end_date>=DATE('".$data['filter_show_date']."') and sp.start_date<=DATE('".$data['filter_show_date']."')";
    	}
    	return $sql;
    }
    public function get($id) {
        $pkArr = array(
            array('id', $id, false),
        );

        return DbHelper::get('supply_period', $pkArr);
    }

    public function create($data) {
        $params = array();
        $fieldName = 'start_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'name';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'title';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'name2';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'template';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'end_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'p_start_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'p_end_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'creator_id';
        $params[$fieldName] = array($fieldName, $data[$fieldName], false, false);
        $fieldName = 'sort_order';
        $params[$fieldName] = array($fieldName, $data[$fieldName], false, false);
        
        $fieldName = 'info';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'created_at';
        $params[$fieldName] = array($fieldName, 'now()', false, false);

        $id = DbHelper::insert('supply_period', $params);

        $productIds = json_decode(str_replace('&quot;', '"', $data['productIds']));
        $this->updateProducts($id, $productIds);

        $this->refreshProductSupplyPeriods();
        
    }

    /**
     * 菜品周期插入菜品
     * @param unknown $periodId
     * @param unknown $productIds
     */
    private function insertProducts($periodId, $productIds) {
        $colDefines = array(
            array('product_id', false, false),
            array('period_id', false, false),
            array('sort_order', false, false)
        );
        $data = array();
        $i = 0;
        foreach ($productIds as $pid) {
            $i++;
            $data[] = array($pid, $periodId, $i);
        }
        DbHelper::bulkInsert('product_supply_period', $colDefines, $data);
    }

    private function removeProducts($periodId, $productIds) {
        $DB_PREFIX = DB_PREFIX;
        foreach ($productIds as $pid) {
            $sql = "delete from {$DB_PREFIX}product_supply_period where product_id={$pid} and period_id={$periodId} ";
            $this->db->query($sql);
        }
    }

    /**
     * 更新菜品周期菜品列表
     * @param unknown $periodId
     * @param unknown $productIds
     */
    private function updateProducts($periodId, $productIds) {
        $oldPids = $this->getOldProductIds($periodId);
  //      $deletedPids = array_diff($oldPids, $productIds);
  //    $addedPids = array_diff($productIds, $oldPids);

        // 清楚旧列表
        if (!empty($oldPids)) {
            $this->removeProducts($periodId, $oldPids);
        }
        
        // 追加新记录
        if (!empty($productIds)) {
            $this->insertProducts($periodId, $productIds);
        }
        

        $this->refreshProductSupplyPeriods();
    }

    /**
     * 更新菜品周期表信息
     * @param unknown $id
     * @param unknown $data
     */
    public function update($id, $data) {
        $params = array();
        $fieldName = 'start_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'title';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'name';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'name2';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'template';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'end_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'p_start_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'p_end_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'sort_order';
        $params[$fieldName] = array($fieldName, $data[$fieldName], false, false);
	
	$fieldName = 'info';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $pkArr = array(
            array('id', $id, false),
        );
        DbHelper::update('supply_period', $pkArr, $params);

        $productIds = json_decode(str_replace('&quot;', '"', $data['productIds']));
        
        $this->updateProducts($id, $productIds);
    }

    public function delete($id) {
        $DB_PREFIX = DB_PREFIX;
        $id = (int)$id;

        $sql = "delete from {$DB_PREFIX}supply_period where id={$id}";
        $this->db->query($sql);

        $sql = "delete from {$DB_PREFIX}product_supply_period where period_id={$id}";
        $this->db->query($sql);
       if(is_object($this->mem)){
       	$this->mem->delete('SupplyPeriods');
        $this->mem->reset_namespace('products');
       }
    }

    /**
     * 获取周期菜品列表
     * @param unknown $periodId
     */
    public function getProducts($periodId) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "SELECT p.product_id,p.sort_order,d.`name`,p2.sku,p2.price from {$DB_PREFIX}product_supply_period p"
            . " LEFT JOIN {$DB_PREFIX}product p2 on p2.product_id=p.product_id "
            . " LEFT JOIN {$DB_PREFIX}product_description d on (d.product_id=p.product_id  AND d.language_id = '" . (int)$this->config->get('config_language_id') . "')"
            . " WHERE p.period_id={$periodId}"
            . " ORDER BY p.sort_order ";
        $q = $this->db->query($sql);
        return $q->rows;
    }

    private function getOldProductIds($periodId) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "select pp.product_id from {$DB_PREFIX}product_supply_period pp where pp.period_id=" . $periodId;
        $q = $this->db->query($sql);
        $oldPids = array();
        foreach ($q->rows as $item) {
            $oldPids[] = $item['product_id'];
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
        
        $this->cache->delete('SupplyPeriods');
        
        if(is_object($this->mem)){
        
       // $this->mem->delete('SupplyPeriods');
        $this->mem->reset_namespace('SupplyPeriods');
        $this->mem->reset_namespace('products');
        }
    }

}

?>