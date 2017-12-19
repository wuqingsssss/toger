</div>

<div class="footer-service mt20">
	<div class="wrap">
		<img src="image/page/footer-service.jpg" alt="" />
	</div>
</div>
<div id="footer" class="clearfix">
	<div class="wrap">
		<div id="powered">
			<?php echo $powered; ?>
			<br/>
			<?php echo $text_support; ?>
		</div>
	</div>
</div>


<?php echo $google_analytics; ?>
<?php echo $this->getChild('module/chat'); ?>

<script type="text/javascript" src="js/lodash-2.4.1.compat.min.js"></script>

<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.9.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/flick/jquery-ui-1.8.16.custom.css" />
	
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>

<script type="text/javascript" src="catalog/view/javascript/common.js?v1.1"></script>


<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />



<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<script type="text/javascript">
$('#tabs a').tabs();
</script>


<!-- #gotop Start -->
<script type="text/javascript" src="catalog/view/javascript/jquery/go-top.js"></script>
<script type="text/javascript">
/* <![CDATA[ */
(new GoTop()).init({
	pageWidth		:1200,
	nodeId			:'go-top',
	nodeWidth		:50,
	distanceToBottom	:150,
	hideRegionHeight	:130,
	text			:'Top'
});
/* ]]> */
</script>
<!-- #gotop End -->
<?php if($this->config->get('pickupaddr_status')){?>
<?php echo $this->getChild('module/point/lbs',array('show_location_tipbox'=>$show_location_tipbox));?>
<?php }?>
</body>
</html>