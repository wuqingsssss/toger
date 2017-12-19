<?php

class ControllerToolAutoTask extends Controller {


    public function daily(){
        //refresh product period
        $this->load->model('catalog/supply_period');
        $this->model_catalog_supply_period->refreshProductSupplyPeriods();

        //purchase
        $this->load->model('sale/order_purchase');
        $this->model_sale_order_purchase->autoSaveOrderPurchases();

        $json = array();
        $json['msg'] = 'daily task done';
        $this->load->library('json');
        $this->response->setOutput(Json::encode($json));

    }
}

?>