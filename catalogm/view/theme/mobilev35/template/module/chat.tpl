<div style="position: absolute; right: 0pt; top: 200px;" id="siderIMchat1">

<div id="siderIMchat_hiddenbar1" style="display: block;"></div>

<div id="siderIMchat_main1" style="display: none;">



<div class="bg ">

<div class="infobox"><h3>在线客服</h3></div>

<ul class="clearfix">

	<?php foreach($qqs as $index=> $qq) { if($qq) {?>

	<li>

	

				<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $qq; ?>&site=ilexnet&menu=yes" target="_blank">

				<img border="0" src="http://wpa.qq.com/pa?p=7:<?php echo $qq; ?>:41" alt="点击这里给我发消息" title="点击这里给我发消息" />

				</a>

				

	</li>

			<?php }} ?>

</ul>

<div class="textcenter pushdown-2"><span class="lnk" id="closeSiderIMchat1">关闭在线客服</span></div>

</div>



</div>

</div>



<link href="catalog/view/javascript/imchat/css/StyleSheet.css" rel="stylesheet" type="text/css" />

<script src="catalog/view/javascript/imchat/JScript.js" type="text/javascript"></script>

