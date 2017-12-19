<?php echo $header; ?>
<div class="container"><?php echo $column_left; ?><?php echo $column_right; ?>
  <div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
    </div>
    <h1 class="mgb10"><?php echo $heading_title; ?></h1>
    <div class="content">
    	<div class="ilex-empty">
      <b><?php if($dates){echo $text_empty ;}
else
{ echo '当前没有开放的菜品周期，请您稍后再来！';
}?></b><br>
      去开始订餐选购菜品吧！ 
    	<a href="<?php echo $this->url->link('common/home'); ?>" class="btn">开始订餐</a>
      </div>
    </div>
    <?php echo $content_bottom; ?></div>
</div>
<?php echo $footer; ?>