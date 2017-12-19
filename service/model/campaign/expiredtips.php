<?php
class ModelCampaignExpiredtips  extends Model
{

    public function get_openid()
    {
        $openid_array=array();
        $now_date=date('Y-m-d H:i:s',time());
        $next_date=date('Y-m-d H:i:s',strtotime("+1 days"));
        $sql="select * from " . DB_PREFIX ."coupon_to_customer where date_limit<'{$next_date}' and date_limit > '{$now_date}' and `used`=0 ";
        $result=$this->db->query($sql);
        $result=$result->rows;
        if(!$result){
            return false;
        }


       foreach($result as $k=>$key){
           $costomer_id=$key['customer_id'];
           $coupon_id=$key['coupon_id'];
           $openid_array[$k]['date_limit']=$key['date_limit'];
           $openid_array[$k]['date_add']=$key['date_add'];
           $coupon_tmp=$this->db->query("select * from ". DB_PREFIX ."coupon where  coupon_id={$coupon_id}");
           if(!$coupon_tmp){
               continue;
           }

           $coupon_tmp=$coupon_tmp->rows;
           $openid_array[$k]['coupon_name']=$coupon_tmp[0]['name'];
           $openid_tmp=$this->db->query("select * from ". DB_PREFIX ."openid_info where  customer_id={$costomer_id}");
           if(!$openid_tmp){
               continue;
           }
           $openid_tmp=$openid_tmp->rows;
           $openid_array[$k]['openid_array']=$openid_tmp[0]['openid'];
       }
      return  $openid_array;
    }

}

?>