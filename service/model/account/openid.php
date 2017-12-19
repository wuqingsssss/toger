<?php

class ModelAccountOpenid extends Model {

    function insert_openid($openid,$customer_id){
            if($openid){
                    $this->db->query("UPDATE " . DB_PREFIX . "openid_info SET  openid='{$openid}' WHERE customer_id = '" . (int)$customer_id . "'");
            }
    }

//检查是否绑定openid
    function check_open_diag_openid($openid,$customer_id){
        if(!$openid){
            return 0;
        }
        $tmp=$this->db->query("select * from " . DB_PREFIX . "openid_info where  customer_id='{$customer_id}'");
        if(!$tmp->row){
            return 0;
        }
        if($tmp->row['openid']<>$openid){
            return 1;
        }else{
            return 0;
        }
    }

//第一次登陆直接插入openid
    function check_first($openid,$customer_id){
        $tmp=$this->db->query("select * from " . DB_PREFIX . "openid_info where  customer_id='{$customer_id}'");
        if(!$tmp->row){
            $this->db->query("insert into " . DB_PREFIX . "openid_info set openid='{$openid}',customer_id='{$customer_id}',date_added = NOW()");
            return true;
        }else{
            return false;
        }
    }

}

?>