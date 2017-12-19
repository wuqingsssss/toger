<?php
class ModelCampaignQuestion extends Model
{
//获取问题列表
    public function getquestion($userid)
    {

        $tmp=$this->db->query("select question_examination from " . DB_PREFIX ."question_save where userid={$userid}");

        if(!$tmp->rows){
            $examination_tmp=$this->db->query("select id from " . DB_PREFIX ."question_examination limit 0,1");
            $examination_id=$examination_tmp->rows[0]['id'];
            $result = $this->db->query("select * from " . DB_PREFIX . "question where question_examination = {$examination_id} order by sort");
            return $result->rows;
        }

        $tmp_result=$tmp->rows;
        $qid_array=array();
        foreach($tmp_result as $v){
            $qid_array[]=$v['question_examination'];
        }
       $new_result=implode(",",$qid_array);



        $examination_tmp=$this->db->query("select id from " . DB_PREFIX ."question_examination where id not in ($new_result) limit 0,1");
        if(!$examination_tmp->rows){
            return array();
        }
        $examination_id=$examination_tmp->rows[0]['id'];
        $result = $this->db->query("select * from " . DB_PREFIX . "question where question_examination = {$examination_id}");
        return $result->rows;
    }

//保存问题
    public function save_question($userid, $result)
    {
        foreach ($result as $key => $value) {

            $tmp=$this->db->query("select * from " . DB_PREFIX . "question_save where userid={$userid} and question_id={$key}");
               if(!$tmp->rows) {

                   $question_examination_tmp=$this->db->query("select * from " . DB_PREFIX . "question where id={$key}");
                   $question_examination=$question_examination_tmp->rows[0]['question_examination'];
                   $this->db->query("insert into " . DB_PREFIX . "question_save set question_id={$key},user_value='{$value}',userid={$userid},question_examination='{$question_examination}'");

               }
        }
    }

    public function getCustomer($userid)
    {
        $result=$this->db->query("select * from " . DB_PREFIX ."question_save where userid={$userid}");
        return $result->rows;
    }

}

?>