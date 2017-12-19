<?php
class ControllerSaleOrderTmp extends Controller {
	public function index() {
		$this->load->model ('sale/ordertmp');
		$error = $this->model_sale_ordertmp->addTmp();
		print_r($error);
		$this->template = 'sale/entrypage.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render ();
	}

}

?>