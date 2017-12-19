<?php

class ModelSaleTransaction extends Model {
    
    /**
     * 校验储值码重复
     * @param unknown $code
     * @return boolean
     */
    private function existSameCode($code) {
        $sql = "SELECT trans_id FROM " . DB_PREFIX . "trans_code WHERE trans_code='{$code}' limit 1";
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
     * 批量生产储值券
     * @param unknown $data
     */
    public function batchAddTransCode($data) {
        $batch = (int)$data['batch'];

        for ($i = 0; $i < $batch; $i++) {          
            $code = $this->getNextCode($data['length'], $data['prefix']);
            $this->addTransCode($code, $data['date_start'], $data['date_end'], $data['value'], $data['operator'],$data['is_tpl'] );
        }
    }

    /**
     * 追加储值券
     * @param unknown $code
     * @param unknown $date_start
     * @param unknown $date_end
     * @param unknown $value
     */
    public function addTransCode($code, $date_start, $date_end, $value, $operator=null,$is_tpl=0) {
        $pendingData = array(
            array('trans_code', $code, true, false),
            array('value', $value, false, true),
            array('date_start', $date_start, true, true),
            array('date_end', $date_end, true, true),
            array('used', 0, false, false),
        	array('is_tpl', $is_tpl, false, false),
            array('date_added', 'NOW()', false, false),
            array('operator', $operator?$operator:$this->user->getUserName(), true, false),
        );
        
        $this->log_admin->info($pendingData);
        DbHelper::insert('trans_code', $pendingData);
        
        $this->log_admin->info($pendingData);
    }

    /**
     * 更新优惠券信息
     * @param unknown $coupon_id
     * @param unknown $data
     */
    public function editTransCodeInfo($trans_id, $data) {
        $sql = "UPDATE " . DB_PREFIX . "trans_code  
                          SET value = {$data['value']},
                              is_tpl = '{$data['is_tpl']}',
                              date_start = '". $this->db->escape($data['date_start'])."',
                              date_end = '". $this->db->escape($data['date_end'])."' 
                          WHERE trans_id = ".(int)$trans_id;

        $this->log_admin->info($sql);
        
        $this->db->query($sql);
            
    }
    

    /**
     * 获取储值券信息
     * @param unknown $coupon_id
     */
    public function getTransCodeInfo($trans_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "trans_code WHERE trans_id = {$trans_id} ");

        return $query->row;
    }

    /**
     * 按过滤获取所有储值券信息
     * @param unknown $data
     * @return multitype:unknown
     */
    public function getTransCodeAll($data = array()) {
      
        $sql = "SELECT * FROM " . DB_PREFIX. "trans_code WHERE 1=1 ";
        
        $filter = '';
        
        if(isset($data['is_tpl'])){
            $filter .= " AND is_tpl ={$data['is_tpl']} ";
        }
        if(isset($data['used'])){
        	$filter .= " AND used ={$data['used']} ";
        }
        if(isset($data['keyword'])){
        	$filter .= " AND concat(LCASE(trans_code),LCASE(value)) like '%{$data['keyword']}%' ";
        }
        
        if(isset($data['trans_code'])){
            $filter .= " AND trans_code='{$data['trans_code']}'";
        }
        
        if(isset($data['date_start'])){
            $filter .= " AND date_start='{$data['data_start']}'";
        }

        if(isset($data['date_end'])){
            $filter .= " AND date_end='{$data['date_end']}'";
        }
        
        if(isset($data['customer_id'])){
            $filter .= " AND customer_id='{$data['customer_id']}'";
        }
        
        if(isset($data['operator'])){
            $filter .= " AND operator='{$data['operator']}'";
        }
        
        $ret = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX. "trans_code WHERE 1=1 ". $filter);
        
        if($ret) {
            $total = $ret->row['total'];
        }
        else{
            $total = 0;
        }
        
        $sql .= $filter;
        
        $sort_data = array(
            'trans_id',
            'trans_code',
            'value',
            'date_start',
            'date_end',
            'date_added',
            'date_modified',
            'operator',
            'customer_id',
            'used',
            'is_tpl'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY trans_id";
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

        return array(
            "total" =>$total,
            "rows"  =>$query->rows
        );
    }

}

?>