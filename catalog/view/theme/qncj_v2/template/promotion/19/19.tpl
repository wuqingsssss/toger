<?php echo $header; ?>
<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/promotion/'.$pid.'/';
$cssPath = 'catalog/view/theme/'.$template.'/template/promotion/'.$pid.'/css/';
$jsPath =  'catalog/view/theme/'.$template.'/template/promotion/'.$pid.'/js/';
$imgPath = 'catalog/view/theme/'.$template.'/template/promotion/'.$pid.'/img/';
?>
<link rel="stylesheet" type="text/css" href="<?php echo $cssPath; ?>19-pc.css"/>
<script src="<?php echo $jsPath; ?>zepto.js"></script>
<script src="<?php echo $jsPath; ?>common.js"></script>

      <div id="content" class="wrap">
        <div id="breadcrumb" class="breadcrumb">
            <a href="index.php?route=common/home" title="首页">首页</a>
            <span>&nbsp;»&nbsp;</span>
            <a href="#" title="活动页面">活动页面</a>
        </div>
        <div id="j-banner">
            <ul>
                <li>
                      <?php echo htmlspecialchars_decode($product_info['description']);?>
                </li>
            </ul>
        </div>
        <div id="j-rule">
            <div>
                <div class="j-rule-left">
                <?php echo htmlspecialchars_decode($product_info['cooking']);?>
                </div>
                <div class="j-rule-right">
                    <div>
                        <img id="pdt_img<?php echo $productid;?>" src="<?php echo $product_info['thumb']; ?>" alt="gift"/>
                    </div>
                    <div>
                        <a href="javascript:addToCart('<?php echo $productid;?>');">
                            <img src="<?php echo $imgPath; ?>buy.png" alt="buy"/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="j-foods">
            <h2>菜票限购菜品</h2>
            <ul>
            <?php foreach($productsrelated as $key=>$item ){?>
                <li>
                    <a href="<?php echo $item[href];?>">
                        <img src="<?php echo $item[thumb];?>" alt="food img"/>
                        <span>
                            <span><?php echo $item[name];?></span>
                            <span><?php echo $item[promotion]?$item[promotion_price]:$item[price];?></span>
                        </span>
                    </a>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>
<?php echo $footer; ?>