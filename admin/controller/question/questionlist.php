<?php
class ControllerQuestionQuestionlist extends Controller {
    private $error = array();

    /**
     * 加载必要语言，model
     */
    protected function init(){
        $this->load_language('promotion/promotion');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('promotion/promotion');
    }

    public function index() {
        $this->init();

        $this->questionlist();
    }


//获取问题列表
    private function questionlist(){
     $this->data['id']=$this->request->get['id'];
       //var_dump($this->data['id']);
       $tmp=$_GET["id"];
        //var_dump($tmp);
        $this->load->model('question/questionlist');
        $question_id=$this->model_question_questionlist->get_question_examination_list($tmp);
        $examination_id=$question_id[0]['id'];
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));

        $this->data['question_info']=$this->model_question_questionlist->get_question_info($examination_id);
        //var_dump($this->data['question_info']);

       // $this->data['heading_title']=$question_id[0]['examination_name'];
        $this->data['column_name']="列名";
        $this->data['column_action']="action";
        $this->data['examination_id']=$examination_id;

        $this->template = 'question/questionlist.tpl';

        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
}


//加载修改视图
    public function edit_view(){
        $id=$this->request->get['id'];
        //var_dump($tmp);
        $this->load->model('question/questionlist');
        $this->data['question_info']=$this->model_question_questionlist->get_question_info_by_id($id);
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));


        $this->data['column_name']="列名";
        $this->data['column_action']="action";


        $this->template = 'question/edit_view.tpl';

        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
    }
//加载新增问题视图
    public function inster(){
        $this->data['examination_id']=$this->request->get['examination_id'];

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));


        $this->data['column_name']="列名";
        $this->data['column_action']="action";


        $this->template = 'question/inster_question.tpl';

        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
    }
//加载新增问题model。获取post提交值加载到model
    public function insterinto(){
        $this->load->model('question/questionlist');
        $this->model_question_questionlist->inster_question($this->request->post);

    }

//加载问题修改model

    function update_view(){
        $question_title=$this->request->post['question_title'];
        $id=$this->request->post['question_id'];
        $tmp_array=array();
        foreach($_POST as $key => $value){
            if (strstr($key,"question_value_")){
                $tmp_array[$value]=$value;
            }
        }
        if ($tmp_array){
            $value_string=json_encode($tmp_array);
            $value_string=addslashes($value_string);
        }else{
            $tmp_array=null;
        }

        $this->load->model('question/questionlist');
        $this->model_question_questionlist->update_question_info_by_id($question_title,$value_string,$id);





    }


    //删除问题

   public function del(){
        $ids=$this->request->post['ids'];
        $data=explode(',',trim($ids,','));
        $this->load->model('question/questionlist');
        $this->model_question_questionlist->del_question($data);


    }











    /**
     * 面包屑
     */
    private function createBreadcrumbs($text,$href,$separator)
    {
        return array(
            'text'      =>$text,
            'href'      => $href,
            'separator' => $separator
        );
    }




}
?>