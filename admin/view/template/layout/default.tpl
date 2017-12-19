<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="view/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="view/bootstrap/css/bootstrap-responsive.css" />
<script src="view/bootstrap/js/jquery.js"></script>
<script src="view/bootstrap/js/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.9.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/themes/flick/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/external/jquery.bgiframe-2.1.2.js"></script>
<script type="text/javascript" src="view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>

<link rel="stylesheet" type="text/css" href="view/javascript/upload/fileuploader.css" />
<script type="text/javascript" src="view/javascript/upload/fileuploader.js"></script>
<script type="text/javascript" src="view/javascript/lodash-2.4.1.compat.min.js"></script>
<script type="text/javascript" src="http://ilex-static.oss-cn-hangzhou.aliyuncs.com/comp/math/enhance.js"></script>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript">
//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------

$(document).ready(function(){
	// Confirm Delete
    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });

    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
});
</script>
</head>
<body>
<div id="container">
	<div id="header" class="noprint">
	  <?php echo $header; ?>
	</div>
	<div id="content" >
	<?php if ($logged) { ?>
		<?php if(isset($breadcrumbs)&&$breadcrumbs) {?>
		<div id="breadcrumb" class="navbar navbar-fixed-top noprint" style="margin-top:40px;z-index:100;">
		<ul class="breadcrumb">
		<i class="icon-home"></i>
			<?php foreach ($breadcrumbs as $index => $breadcrumb) { ?>
				<li ><a href="<?php echo $breadcrumb['href']; ?>" style="z-index: <?php echo count($breadcrumbs)-$index; ?>;"><span class="left-yarn"></span><?php echo $breadcrumb['text']; ?></a><span class="divider">/</span></li>
			<?php } ?>
		</ul>
		</div>
		<?php } ?>
	<?php } ?>
	  	<div><?php echo $content; ?></div>
	</div>
	<div id="footer" class="noprint">
		<?php echo $footer; ?>
	</div>
</div>
</body>

</html>