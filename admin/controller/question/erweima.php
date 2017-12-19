<?php
class ControllerQuestionErweima extends Controller {
    private $error = array();

    /**
     * 加载必要语言，model
     */
    protected function init(){
        $this->load_language('question/question');

        $this->document->setTitle($this->language->get('heading_title'));

       //$this->load->model('question/question');
    }

    public function index() {
        $this->init();

        $this->getList();
    }

    private function getList() {

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
        $this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title2'), $this->url->link('question/erweima', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));


        $this->data['heading_title']="二维码生成器";

        $this->template = 'question/erweima.tpl';
        $this->id = 'content';
        $this->layout = 'layout/default';

        $this->render();
    }

    public function insterinto(){
        if(isset($this->request->post['buildingid'])){
           $buildingid = $this->request->post['buildingid'];

            //$appid = 'wxe8daa4db0a6e8ccc';
            $appid = 'wx0d450546a613eb4f';
            //$appsecret = 'b01c9505939a9f457bb5e960027fe80e';
            $appsecret = 'd8a8951c5760e7a32c77ed48d20649b9';
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret."";
            $token_tmp=file_get_contents($url);
            $token_tmp_array=json_decode($token_tmp,true);
            $token=$token_tmp_array['access_token'];
//var_dump($token);
            $urls="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token."";
            $qrcode='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$buildingid.'}}}';
            $result=$this->https_post($urls,$qrcode);
            $jsoninfo=json_decode($result,true);
            $ticket=$jsoninfo["ticket"];

            $end_url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
            $this->data['url']=$end_url;
            $this->template = 'question/erweima.tpl';
            $this->id = 'content';
            $this->layout = 'layout/default';

            $this->render();
           // echo $end_url;
//            exit;
//            $end_result=file_get_contents($end_url);
//            header("Content-type: image/jpg");
//            echo $end_result;




        }else{
            $this->redirect($this->url->link('question/erweima', 'token=' . $this->session->data['token']  , 'SSL'));
        }
    }





    function https_post($urls,$data=null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urls);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if($data){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
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