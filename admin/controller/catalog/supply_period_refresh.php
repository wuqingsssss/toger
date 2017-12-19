<?php

class ControllerCatalogSupplyPeriodRefresh extends Controller {

    protected function init() {
        $this->load->model('catalog/supply_period');
    }


    public function index(){
        $this->init();
        $this->model_catalog_supply_period->refreshProductSupplyPeriods();

        $json = array();
        $json['success'] = true;
        $this->load->library('json');
        $this->response->setOutput(Json::encode($json));
    }
}

?>