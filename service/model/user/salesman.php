<?php
class ModelUserSalesman extends Model {
    

    public function checkSalesman($username, $password, $user_group_id){
        $sql="SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $username . "'  AND password = '" . $this->db->escape(md5($password))."'";
        
        $salesman_query =$this->db->query($sql);
         
        if ($salesman_query->num_rows) {
            if( $user_group_id == 0 || (int)$salesman_query->row['user_group_id'] == $user_group_id )
            {
                return true;
            }
            else {
                return false;
            }
        } else {       
            return false;
        }
    }

    public function checkuserpassword($username,$password){
        $sql="SELECT * FROM " . DB_PREFIX . "customer WHERE mobile = '" . $username . "'  AND password = '" . $this->db->escape(md5($password))."'";
        $salesman_query =$this->db->query($sql);
        return $salesman_query->rows;
    }


}
?>