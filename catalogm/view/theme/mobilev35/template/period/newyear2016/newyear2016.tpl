<?php echo $header35; ?>
<?php
$tplPath = 'catalogm/view/theme/'.$this->config->get('config_template').'/template/period/newyear2016/';
$cssPath = 'catalogm/view/theme/'.$this->config->get('config_template').'/template/period/newyear2016/';
$jsPath =  'catalogm/view/theme/'.$this->config->get('config_template').'/template/period/newyear2016/js/';
$imgPath = 'catalogm/view/theme/'.$this->config->get('config_template').'/template/period/newyear2016/img/';
?>

<link rel="stylesheet" type="text/css" href="<?php echo $cssPath; ?>newyear2016.css"/>

<?php echo $this->getChild('module/navtop',array('navtop'=>array(
       'left'=>'<a class="return" href="index.php?route=common/home&sequence=0"></a>',
       'center'=>'<a class="locate fz-18">'.$supply_period['name'].'</a>',
       'right'=>'<a class="search" href="javascript:"></a>
                 <a class="message has-new" href="javascript:"></a>'
),"wechathidden"=>0));?>

<div class="j-wrapper">
    <div class="j-banner">
        <div class="j-banner-item"><img src="<?php echo $tplPath;?>img/banner.jpg"></div>
    </div>
    <div class="j-food">
    <?php
     foreach($products as $key=>$product){?> 
        <div class="j-food-item">
        
           <a href="<?php echo $product[href];?>" class="j-food-url"><img id="pdt_img<?php echo $product[product_id];?>" src="<?php echo HTTPS_IMAGE;?>data/caipin/newyear2016/<?php echo $product[sku];?>M.jpg" alt="<?php echo $product[name];?>"/></a>            
             <?php if($product[available]&&false){?>
                <a class="j-buy-btn j-buy-active" data-id="<?php echo $product['product_id']; ?>" data-code="<?php echo $product[promotion][promotion_code];?>">
                <img src="<?php echo $imgPath;?>buy-active.jpg" alt=""/>
                </a>
                <?php }elseif(false){ ?>
               <a href="javascript:;" class="j-buy-btn j-buy-over">
                <img src="<?php echo $imgPath;?>buy-over.jpg" alt=""/>
                </a>
                <?php }?>
        </div>        
         <?php }?>  
    </div><?php if($supply_period['info']){?>
    <div class="j-intro fz-12">
        <h2>活动规则</h2>
        <div class="j-intro-content">
       <?php echo $supply_period['info'];?>   
        </div>
    </div>
     <?php }?>  
</div>
<script>
$('body .j-buy-active').bind('click', function () {
    var $this = $(this);
    _.addCart($this.data('id'),$this.data('code'),1, function () {
//           	_.addCartAnimation($this);
        $this.tipsBox('<span class="col-red fz-14 bold">+1</span>');
    });
});
</script>
<?php echo $content_bottom;?>
<?php echo $footer35; ?>