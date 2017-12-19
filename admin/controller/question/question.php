<?php
class ControllerQuestionQuestion extends Controller {
    private $error = array();

    /**
     * 加载必要语言，model
     */
    protected function init(){
        $this->load_language('question/question');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('question/question');
    }

    public function index() {
        $this->init();

        $this->getList();
    }

    private function getList() {

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
//        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('question/question', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));

        $this->data['heading_title']="问卷归类管理";
        $this->data['column_name']="列名";
        $this->data['column_action']="action";
        $this->load->model('question/question');
        $this->data['question_examination_info']=$this->model_question_question->get_question_examination_info();

        $this->template = 'question/list.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
    }

    public  function inster(){
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('question/question', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));


        $this->data['heading_title']="添加问卷分类";
        $this->data['column_name']="列名";
        $this->data['column_action']="action";

        $this->template = 'question/inster_examination.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
    }
    public function insterinto(){
        if(isset($this->request->post['examination_title'])){
            $this->data['examination_title'] = $this->request->post['examination_title'];
            $this->load->model('question/question');
            $this->model_question_question->inster_examination($this->request->post);

        }
    }

    public function del(){
        $ids=$this->request->post['ids'];
        $data=explode(',',trim($ids,','));
        $this->load->model('question/question');
        $this->model_question_question->del_examination($data);
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