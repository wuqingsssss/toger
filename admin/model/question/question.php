<?php
class ModelQuestionQuestion extends Model {

    function get_question_examination_info(){
        $sql="select * from ".DB_PREFIX."question_examination";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    function inster_examination($data){
        $this->db->query("insert into " . DB_PREFIX . "question_examination set examination_name='{$data['examination_title']}'");



    }

    function del_examination($data){
        foreach($data as $a_data){
            $this->db->query("delete from ". DB_PREFIX ."question_examination where id={$a_data}" );
        }
    }



}

?>