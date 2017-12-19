<?php echo $header; ?>
<style>
.txtbox_cart{
color: #868686;
font-size: 1.1em;
margin: 1em 0px 1.1em;
}
.btn_cart{
width: 155px;
height: 40px;
line-height: 40px;
border-radius: 5px;
color: #333;
font-size: 2em;
border: 1px solid #00A99C;
padding: 0.2em 1.5em;}
.bar-positive{
padding-top: 8px;
}
.bar .title{top:8px;left: 0px;}
</style>
<div id="header" class="bar bar-header bar-positive">
  <h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content" style="text-align: center;">
 <img src="catalog/view/theme/mobilev2/image/empty.png" width="30%" style="margin-top: 20%" alt="<?php echo $text_remove; ?>" title="<?php echo $text_remove; ?>">
  <div class="txtbox_cart">购物车都是空的耶，赶紧去挑几个好吃的吧！</div>
  <div style="text-align: center;"><a href="index.php?route=common/home" class="btn_cart" >去逛逛</a></div>
</div>
<?php echo $footer; ?>
