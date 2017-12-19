<?php echo $header; ?>
</div>
<?php
$tplPath = 'catalog/view/theme/'.$this->config->get('config_template').'/template/promotion/'.$supply_period['template'].'/';
$cssPath = 'catalog/view/theme/'.$this->config->get('config_template').'/template/promotion/'.$supply_period['template'].'/';
$jsPath =  'catalog/view/theme/'.$this->config->get('config_template').'/template/promotion/'.$supply_period['template'].'/js/';
$imgPath = 'catalog/view/theme/'.$this->config->get('config_template').'/template/promotion/'.$supply_period['template'].'/img/';
?>
<link rel="stylesheet" type="text/css" href="<?php echo $cssPath; ?>common.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $cssPath; ?>12fw.css"/>
<div class="j-header"><a href="index.php?route=common/home&sequence=0"><span data-return-url="" class="j-btn-return"></span></a>12锋味预售</div>
<div class="j-wrapper">
    <div class="j-banner">
        <div class="j-banner-item"></div>
    </div>
    <div class="j-food">
    <?php foreach($products as $key=>$product){?> 
        <div class="j-food-item">
        
           <a href="<?php echo $product[href];?>" class="j-food-url"><img id="pdt_img<?php echo $product[product_id];?>" src="<?php echo HTTPS_IMAGE;?>data/caipin/12fw/<?php echo $product[sku];?>M.jpg" alt="<?php echo $product[name];?>"/></a>            
             <?php if($product[available]){?>
                <a href="javascript:addToCart('<?php echo $product[product_id];?>');" class="j-buy-btn j-buy-active">
                <img src="<?php echo $imgPath;?>buy-active.jpg" alt=""/>
                </a>
                <?php }else{ ?>
               <a href="javascript:;" class="j-buy-btn j-buy-over">
                <img src="<?php echo $imgPath;?>buy-over.jpg" alt=""/>
                </a>
                <?php }?>
        </div>        
         <?php }?>  
    </div><?php if($supply_period['info']){?>
    <div class="j-intro">
        <h2>活动规则</h2>
        <div class="j-intro-content">
       <?php echo $supply_period['info'];?>   
        </div>
    </div>
     <?php }?>  
</div>
<div>
<?php echo $footer; ?>