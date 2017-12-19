<?php
class ModelQuestionQuestionlist extends Model {


    function get_question_examination_list($tmp){
        $query=$this->db->query("select * from ".DB_PREFIX."question_examination where id={$tmp}");
        return $query->rows;

    }

    function get_question_info($examination_id){
        $query=$this->db->query("select * from ".DB_PREFIX."question where question_examination={$examination_id}");
        return $query->rows;
    }


    function get_question_info_by_id($id){
        $query=$this->db->query("select * from ".DB_PREFIX."question where id={$id}");
        return $query->rows;
    }



    function update_question_info_by_id($question_title,$value_string,$id){

        $query=$this->db->query("update ".DB_PREFIX."question set question_title='{$question_title}',question_value='{$value_string}' where id={$id}");



    }
    //新增问题
    function inster_question($datab){

        $question_value=array();
        foreach($datab as $key => $value){
            if (strstr($key,"question_value_")){
                if($value){
                    $question_value[$value]=$value;
                }
            }
        }
        $question_value_data=addslashes(json_encode($question_value));
        $this->db->query("insert into ". DB_PREFIX ."question set question_title='{$datab['question_title']}',question_type='{$datab['question_type']}',question_value='{$question_value_data}',question_examination={$datab['examination_id']}");

    }

    function del_question($data){
             foreach($data as $a_data){
                 $this->db->query("delete from ". DB_PREFIX ."question where id={$a_data}" );
             }

    }
}

?>