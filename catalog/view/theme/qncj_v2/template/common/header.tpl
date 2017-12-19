<!DOCTYPE html><?php
$tplPath = 'catalog/view/theme/qncj_v2/template/';
$cssPath = 'catalog/view/theme/qncj_v2/stylesheet/';
$jsPath = 'catalog/view/theme/qncj_v2/js/';

$detect = new Mobile_Detect();
if ($detect->isMobile() && !$detect->isTablet()) {
    $cl = 'm';
}
if ($detect->isTablet()) {
    $cl = 't';
}
if (!$detect->isMobile() && !$detect->isTablet()) {
    $cl = 'd';
}?><!--[if lt IE 7 ]>
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js"> <!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1">
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

    <base href="<?php echo $base; ?>"/>
    <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>"/>
    <?php } ?>
    <?php if ($keywords) { ?>
        <meta name="keywords" content="<?php echo $keywords; ?>"/>
    <?php } ?>
    <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon"/>
    <?php } ?>

    <script>document.cookie = 'resolution=' + Math.max(screen.width, screen.height) + '; path=/';</script>

    <?php foreach ($links as $link) { ?>
        <link href="<?php echo $link['href']; ?>"
              rel="<?php echo $link['rel']; ?>"/>
    <?php } ?>

    <!-- RESET USER AGENT -->
    <link rel="stylesheet" type="text/css" href="assets/css/reset/normalize.css"/>

    <link rel="stylesheet" type="text/css"
          href="catalog/view/theme/<?php echo $template; ?>/stylesheet/stylesheet.css"/>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $template; ?>/stylesheet/lbs.css?v=<?php echo STATIC_VERSION; ?>" />
    <!--<link media="only screen and (min-width: 980px)" rel="stylesheet" type="text/css" href="<?php echo $cssPath; ?>desktop.css" />-->
    <!--<link media="only screen and (min-width: 768px) and (max-width: 979px)" rel="stylesheet" type="text/css" href="<?php echo $cssPath; ?>tablet.css" />-->
    <!--<link media="only screen and (min-width: 200px) and (max-width: 767px)" rel="stylesheet" type="text/css" href="<?php echo $cssPath; ?>mobile.css" />-->
    <!--<script type="text/javascript" src="<?php echo $jsPath; ?>responsive.js?01"></script>-->
<!--[if lte IE 7]>
    <script src="https://raw.githubusercontent.com/douglascrockford/JSON-js/master/json2.js"></script>
<![endif]-->
    <?php foreach ($styles as $style) { ?>
        <link rel="<?php echo $style['rel']; ?>" type="text/css"
              href="<?php echo $style['href']; ?>"
              media="<?php echo $style['media']; ?>"/>
    <?php } ?>

    <script type="text/javascript" src="assets/js/jquery/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.tabs.ant-1.0.js"></script>
    <script src="assets/js/layer192/layer.js"></script>
    <script src="assets/libs/modernizr/modernizr.2.6.3.js"></script>

    <?php foreach ($scripts as $script) { ?>
        <script type="text/javascript" src="<?php echo $script; ?>"></script>
    <?php } ?>

    <!-- Fixes for IE -->
    <!--[if lt IE 9]>
    <script src="assets/libs/dist/html5shiv.js"></script>
    <![endif]-->

    <!--[if (gte IE 6)&(lte IE 8)]>
    <script type="text/javascript" src="assets/libs/selectivizr/1.0.2/selectivizr.js"></script>
    <noscript>
        <link rel="stylesheet" href="ie7.css"/>
    </noscript>
    <![endif]-->

    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/dss/stylesheet/ie7.css"/>
    <![endif]-->
    <script>
        var _hmt = _hmt || [];
        (function () {
            var hm = document.createElement("script");
       //     hm.src = "//hm.baidu.com/hm.js?556b5a04fc2b10d1fe58cdaf6dca495d";
            hm.src = "//hm.baidu.com/hm.js?61f1331aa9214144042cf468daaf9caf";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>

<!--disable it for production env -->
</head>
<body id="<?php echo $body_id; ?>" page-role="<?php echo $role; ?>" >
<div id="tophead">
        <div id="header" style="height: 160px;">
            <div class="header-container" >
                <div class="wrap">
                    <?php if ($logo) { ?>
                    <div id="logo">
                        <a href="<?php echo $home; ?>">
                            <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>"/>
                        </a>
                    </div>
                    <?php } ?>

                    <div class="top-wrap">
                        <!--/*新增导航*/-->
                        <div id="hnav">
                            <ul>
                                <li id="tab_home">
                                    <a href="<?php echo $this->url->link('common/home'); ?>">首页</a>
                                </li>
                                <li id="tab_products">
                                    <a href="<?php echo $this->url->link('common/home'); ?>#shopping" title="开始订餐">开始订餐</a>
                                </li>
                                <li id="tab_order"><a href="<?php echo $this->url->link('account/order'); ?>" title="">查询订单</a>
                                </li>
                                <!--							<li id="tab_week"><a href="<?php echo $this->url->link('vote/product'); ?>" title="下周菜品">下周菜品</a></li>-->
                                <li id="tab_help"><a href="index.php?route=information/information&information_id=28" title="帮助中心">帮助中心</a></li>

                                <li id="hnav-member">
                                    <?php if (!$logged) { ?>
                                    <a href="<?php echo $login; ?>"
                                       title="<?php echo $text_login; ?>"><?php echo $text_login; ?></a> /
                                    <a href="<?php echo $register; ?>"
                                       title="<?php echo $text_register; ?>"><?php echo $text_register; ?></a>
                                    <?php } else { ?>
                                    <?php echo $text_logged; ?>
                                    <?php } ?>
                                </li>

                                <li id="hnav-cart">
                                    <a class="heading" href="<?php echo $cart; ?>" title="<?php echo $text_cart; ?>">
                                        <span id="cart_total" class="ix-badge ix-radius"><?php echo $text_items; ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!--/*新增导航*/-->
                        <!--自提点切换-->
                        <div id="point-switch">
                            <!--a id="ztd-btn" class="fancybox"><?php echo $point_text; ?></a-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="notification"></div>
</div>
<script>
$("#header .header-container").antScroll();
</script>
<div id="container">