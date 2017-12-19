<?php echo $header; ?>
</div>
<?php
$tplPath = 'catalog/view/theme/'.$this->config->get('config_template').'/template/promotion/'.$supply_period['template'].'/';
$cssPath = 'catalog/view/theme/'.$this->config->get('config_template').'/template/promotion/'.$supply_period['template'].'/';
$jsPath =  'catalog/view/theme/'.$this->config->get('config_template').'/template/promotion/'.$supply_period['template'].'/js/';
$imgPath = 'catalog/view/theme/'.$this->config->get('config_template').'/template/promotion/'.$supply_period['template'].'/img/';
?>
<link rel="stylesheet" type="text/css" href="<?php echo $cssPath; ?>12fw.css"/>
<div id="container">
    <div id="content" class="wrap">
        <div id="j-content">
            <div id="j-banner" class="htabs2">
        	<?php $i=0; foreach ($supply_periods as $key => $supply_period) {$i++; ?>
	            <a href="javascript:get_product_home(<?php echo $key;?>,0)" class="j-buy-btn<?php if($i>1) echo $i.' selected';?> j-buy-over" title="<?php echo $supply_period['name']; ?>" >
                       <?php echo $supply_period['name'];?> 
 
                            （<?php echo date("m-d",strtotime($supply_period['ps_start_date']));?> - <?php echo date("m-d",strtotime($supply_period['ps_end_date']));?> ）
                       </a>
        	<?php }?>
        </div>
            <?php foreach($products as $key=>$product){?>
            <div class="j-item" id="j-item-<?php echo $product[product_id];?>">
                <a href="<?php echo $product[href];?>" class="j-food-url"><img id="pdt_img<?php echo $product[product_id];?>" src="<?php echo HTTPS_IMAGE;?>data/caipin/12fw/<?php echo $product[sku];?>.jpg" alt="<?php echo $product[name];?>"/></a>
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
            <?php }?><?php if($supply_period['info']){?>
            <div id="j-intro">
                <h2>活动规则</h2>
                <div class="j-intro-content">
                    <?php echo $supply_period['info'];?>  
                </div>
            </div>
            <?php }?>  
        </div>
    </div>
</div>
<div>
<script type="text/javascript">
<!--
var sequence=<?php echo $sequence;?>;
function get_product_home(s,c){
    var ci=false;
    if(s!=sequence){
            if(confirm('切换菜品周期，会清空您的购物车！')){
                sequence=s;
                ci=true;
                location.href="index.php?route=common/home&sequence="+sequence;
                return;
            }else {
                return;
        }
    }

    $.ajax({
            url: 'index.php?route=common/home/get_product_home',
            type: 'get',
            data: 'sequence='+s+'&filter_category_id='+c+ '&filter_keyword=' + $('#key').val(),
            dataType: 'text',
            success: function(json) {
                if(ci){
                    $('#cart_total').html('0');
                }
                $('#productbox').html(json);	
            }
    });
}
-->
</script>
<?php echo $footer; ?>