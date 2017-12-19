<?php if($products) {?>

<script type="text/javascript" language="javascript"> 
function iFrameHeight() { 
var ifm= document.getElementById("iframepage"); 
var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument; 
if(ifm != null && subWeb != null) { 
ifm.height = subWeb.body.scrollHeight; 
} 
} 
</script>
  		<div id="mostviewed" class="box">
  		<iframe src="<?php echo $url?>" id="iframepage" name="iframepage" frameBorder=0 scrolling=no width="100%" onLoad="iFrameHeight()" >
  		</iframe>
  		<div id="mostviewed" class="box">
<?php }?>
 

