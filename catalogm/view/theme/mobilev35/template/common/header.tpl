<?php 
require_once(DIR_ROOT."hm.php");
$_hmt = new _HMT("61f1331aa9214144042cf468daaf9caf");
$_hmtPixel = $_hmt->trackPageView();
?>
<!doctype html>
<!--[if lt IE 7 ]> <html lang="en-us" class="no-js ie6"  ng-app="app"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en-us" class="no-js ie7"  ng-app="app"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en-us" class="no-js ie8"  ng-app="app"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en-us" class="no-js ie9"  ng-app="app"> <![endif]-->   
<!--[if (gt IE 9)||!(IE)]><html lang="en-us" class="no-js"  ng-app="app"><![endif]-->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" >
<meta charset="utf-8">
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<!-- <script type="text/javascript" name="baidu-tc-cerfication" data-appid="4489645" src="http://apps.bdimg.com/cloudaapi/lightapp.js"></script> -->
<script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>

<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>"
	rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<!-- RESET USER AGENT -->
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $template; ?>/assets/ionic/css/ionic.css?v=<?php echo STATIC_VERSION; ?>" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $template; ?>/stylesheet/stylesheet.css?v=<?php echo STATIC_VERSION; ?>" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $template; ?>/stylesheet/mobile_v2.css?v=<?php echo STATIC_VERSION; ?>" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $template; ?>/stylesheet/lbs.css?v=<?php echo STATIC_VERSION; ?>" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css"
	href="<?php echo $style['href']; ?>"
	media="<?php echo $style['media']; ?>" />
<?php } ?>
<!-- script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.6.1.min.js"></script-->
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.2.min.js"></script>
<script src="assets/js/layer192/layer.js"></script>
<!--<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.min.js"></script>-->
<script src="assets/libs/modernizr/modernizr.2.6.3.js"></script>
<script type="text/javascript"
	src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.9.custom.min.js"></script>
<link rel="stylesheet" type="text/css"
	href="catalog/view/javascript/jquery/ui/themes/flick/jquery-ui-1.8.16.custom.css" />
	
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="js/lodash-2.4.1.compat.min.js"></script>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!-- Fixes for IE -->
<!--[if lt IE 9]>
<script src="assets/libs/dist/html5shiv.js"></script>
<![endif]-->
<!--[if (gte IE 6)&(lte IE 8)]>
  <script type="text/javascript" src="assets/libs/selectivizr/1.0.2/selectivizr.js"></script>
  <noscript><link rel="stylesheet" href="[fallback css]" /></noscript>
<![endif]-->
    <!-- script type="text/javascript" src="catalog/view/theme/mobilev2/javascript/angular/1.2.27/angular.min.js"></script-->
    <!-- script type="text/javascript" src="catalog/view/theme/mobilev2/javascript/angular/ui-router/0.2.13/angular-ui-router.min.js"></script-->
    <script>
       // angular.module('app', ['ui.router']);
    </script>
</head>
<body class="ionic">
<img src="<?php echo $_hmtPixel; ?>" width="0" height="0" style="display:none" />
<div id="page" class="page">