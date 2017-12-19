<?php

class ModelCatalogCampaign extends Model {
    public function getall($data = array()) {
        $sql = "SELECT * FROM ts_campaign ";

        if(isset($data['filter_name']) && !is_null($data['filter_name'])){
            $sql.=" WHERE name like '%".$this->db->escape($data['filter_name'])."%' ";
        }

        $sort_data = array(

        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY campaign_id";
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
        
        $campaign_data = array();
        
        if($query->num_rows){
            foreach ($query->rows as $campaign){
                $rules = $this->db->query("SELECT tp.name,tp.cond,tp.batch FROM ts_campaign_rule tr, ts_packet tp
                                              WHERE tr.campaign_id = {$campaign['campaign_id']} 
                                                AND tr.flag=1
                                                AND tr.packet_id = tp.packet_id 
                                                ");
                $campaign_data[]=array(
                    'campaign_id' => $campaign['campaign_id'],
                    'name'        => $campaign['name'],
                    'code'        => $campaign['code'],
                    'date_start'  => $campaign['date_start'],
                    'date_end'    => $campaign['date_end'],
                    'status'      => $campaign['status'],
                    'rules'       => $rules->rows
                );
            }
        }

        return $campaign_data;
    }


    /**
     * 获取总数
     * @param unknown $data
     */
    public function getTotal($data) {
        $sql = "SELECT COUNT(*) AS total FROM ts_campaign ";
        if(isset($data) && isset($data['filter_name']) && !is_null($data['filter_name'])){
            $sql.=" WHERE name like '%".$this->db->escape($data['filter_name'])."%' ";
        }
        $query = $this->db->query($sql);


        return $query->row['total'];
    }


    /**
     * 校验码重复
     * @param unknown $code
     * @return boolean
     */
    private function existSameCode($code) {
        $sql = "SELECT code FROM ts_campaign WHERE code='{$code}' limit 1";
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
     * 生成记录
     * @param unknown $data
     */
    public function create($data) {
        $params = array();
        $fieldName = 'name';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'date_start';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'date_end';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        
        $fieldName = 'code';
        //通用（非活动）设置
        if($data['codetype'] == '0'){
            $code = 'normal';
        }
        else{
            $code = $this->getNextCode(8);
        }
        
        $params[$fieldName] = array($fieldName, $code, true, true);
        
        $fieldName = 'date_added';
        $params[$fieldName] = array($fieldName, 'now()', false, false);
        
        $fieldName = 'status';
        $params[$fieldName] = array($fieldName, $data[$fieldName], false, false);

        $id = DbHelper::insert('campaign', $params);

        $rules = json_decode(str_replace('&quot;', '"', $data['rules']));
        $this->updateRules($id, $rules);
    }

    /**
     * 生成规则记录
     * @param unknown $periodId
     * @param unknown $productIds
     */
    private function insertRules($campaign_id, $ruleIds) {
        $params = array();
        $fieldName = 'campaign_id';
        $params[$fieldName] = array($fieldName, $campaign_id, false, false);

        foreach ($ruleIds as $pid) {
            $packet_id = $pid;
            $ret = $this->db->query("SELECT * from ts_packet WHERE packet_id='{$packet_id}'");
            
            if($ret->num_rows>0) {
                $fieldName = 'cond';
                $params[$fieldName] = array($fieldName, $ret->row[$fieldName], false, false);
                $fieldName = 'packet_id';
                $params[$fieldName] = array($fieldName, $packet_id, false, false);
                $fieldName = 'flag';
                $params[$fieldName] = array($fieldName, '1', false, false);
                DbHelper::insert('campaign_rule', $params);
           }
        }
    }

    private function removeRules($campaign_id) {
        $sql = "delete from ts_campaign_rule where campaign_id={$campaign_id} ";
        $this->db->query($sql);
    }

    private function updateRules($campaign_id, $ruleIds) {
        $this->removeRules($campaign_id);
     
        if (!empty($ruleIds)) {
            $this->insertRules($campaign_id, $ruleIds);
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
        
        $fieldName = 'status';
        $params[$fieldName] = array($fieldName, $data[$fieldName], false, false);
    
        $pkArr = array(
            array('campaign_id', $id, false),
        );
        DbHelper::update('campaign', $pkArr, $params);
        
        $ruleIds = json_decode(str_replace('&quot;', '"', $data['rules']));
        $this->updateRules($id, $ruleIds);
    }

    /**
     * 获取活动信息
     * @param unknown $id
     * @return Ambigous <NULL, unknown>
     */
    public function get($id) {
        $campaign_id = $this->db->escape($id);
        $sql = "SELECT * FROM ts_campaign WHERE campaign_id='{$campaign_id}'";
       
        $ret = $this->db->query($sql);
        
        return $ret->row;
    }
    
    public function getCampaignRules($id) {
        $campaign_id = $this->db->escape($id);
        $sql = "SELECT tp.packet_id,tp.name,tp.cond,tp.batch,tp.date_start,tp.date_end FROM ts_campaign_rule tr, ts_packet tp
                                              WHERE tr.campaign_id = {$campaign_id} 
                                                AND tr.flag=1
                                                AND tr.packet_id = tp.packet_id ";
        $q = $this->db->query($sql);
        return $q->rows;
    }

    /**
     * 
     * @param unknown $id
     */
    public function delete($id) {
        $sql = "delete from ts_campaign where campaign_id={$id}";
        $this->db->query($sql);

        $sql = "delete from ts_campaign_rule  where campaign_id={$id}";
        $this->db->query($sql);
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
    
    
    public function search_name($name){
        if(!$name){
            return array();
        }
        $sql="SELECT * FROM ts_packet WHERE name like '%{$name}%'";
        $query = $this->db->query($sql);
        
        return $query->rows;
    }
    
}

?>