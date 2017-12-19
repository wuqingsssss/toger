<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/home.css" />
<style>
#tophead{display:none;}
a img {
    border: none;
    width: 100%;
}

.home {
    margin-top: 0px;
}
.home-advs {
    background: url(graphic_v2/home-advs-bg.png) no-repeat 0 bottom;
    height: 151px;
    padding: 0px 0 14px 0;
}
</style>
<div class="home">
	<div id="content">
	<?php echo $content_top; ?>
	</div>
</div>