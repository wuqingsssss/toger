<?php if ($logged) { ?>
    <div class="navbar navbar-fixed-top">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="#" style="font-family:微软雅黑;"><?php echo $heading_title; ?></a>

                    <div class="nav-collapse">
                        <ul class="nav">
                            <li id="h_dashboard"><a href="<?php echo $home; ?>"><?php echo $text_dashboard; ?></a></li>
                         <?php if ($menus) {foreach($menus as $kg=>$groups){ ?>
                            <li class="dropdown" id="<?php echo $kg;?>">
                                    <a href="#" class="dropdown-toggle"
                                       data-toggle="dropdown"><?php echo $kg; ?>
                                        <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($groups as $result) {
                                            if ($result) { ?>
                                                <li>
                                                    <a href="<?php echo $result['href']; ?>"><?php echo $result['title']; ?></a>
                                                </li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </li>
                            <?php }}?>
                            <?php if (isset($groups1)) { ?>
                                <li class="dropdown" id="h_catalog">
                                    <a href="#" class="dropdown-toggle"
                                       data-toggle="dropdown"><?php echo $text_catalog; ?>
                                        <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($groups1 as $result) {
                                            if ($result) { ?>
                                                <li>
                                                    <a href="<?php echo $result['href']; ?>"><?php echo $result['title']; ?></a>
                                                </li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </li>
                            <?php } ?>

                            <?php if (isset($groups2)) { ?>
                                <li class="dropdown" id="h_article">
                                    <a href="#" class="dropdown-toggle"
                                       data-toggle="dropdown"><?php echo $text_article_manage; ?><b
                                            class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($groups2 as $result) {
                                            if ($result) { ?>
                                                <li>
                                                    <a href="<?php echo $result['href']; ?>"><?php echo $result['title']; ?></a>
                                                </li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </li>
                            <?php } ?>

                            <?php if (isset($groups3)) { ?>
                                <li class="dropdown" id="h_extension">
                                    <a href="#" class="dropdown-toggle"
                                       data-toggle="dropdown"><?php echo $text_extension; ?><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($groups3 as $result) {
                                            if ($result) { ?>
                                                <li>
                                                    <a href="<?php echo $result['href']; ?>"><?php echo $result['title']; ?></a>
                                                </li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </li>
                            <?php } ?>

                            <?php if (isset($groups4)) { ?>
                                <li class="dropdown" id="h_sale">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $text_sale; ?>
                                        <b
                                            class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($groups4 as $result) {
                                            if ($result) { ?>
                                                <li>
                                                    <a href="<?php echo $result['href']; ?>"><?php echo $result['title']; ?></a>
                                                </li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </li>
                            <?php } ?>

                            <?php if (isset($groups5)) { ?>
                                <li class="dropdown" id="h_system">
                                    <a href="#" class="dropdown-toggle"
                                       data-toggle="dropdown"><?php echo $text_system; ?><b
                                            class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($groups5 as $result) {
                                            if ($result) { ?>
                                                <li>
                                                    <a href="<?php echo $result['href']; ?>"><?php echo $result['title']; ?></a>
                                                </li>
                                            <?php }
                                        } ?>
                                        <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                        <ul class="nav pull-right">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown"><?php echo $text_front; ?><b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a target="_blank" href="<?php echo $store; ?>"><?php echo $text_front; ?></a>
                                    </li>
                                    <?php if (isset($stores) && $stores) { ?>
                                        <?php foreach ($stores as $stores) { ?>
                                            <li>
                                                <a onClick="window.open('<?php echo $stores['href']; ?>');"><?php echo $stores['name']; ?></a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </li>

                            <li class="divider-vertical"></li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $logged; ?> <b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $logout; ?>">  <?php echo $text_logout; ?></a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.nav-collapse -->
                </div>
            </div>
            <!-- /navbar-inner -->
        </div>
    </div>
<?php } ?>
<script type="text/javascript"><!--
    function getURLVar(urlVarName) {
        var urlHalves = String(document.location).toLowerCase().split('?');
        var urlVarValue = '';

        if (urlHalves[1]) {
            var urlVars = urlHalves[1].split('&');

            for (var i = 0; i <= (urlVars.length); i++) {
                if (urlVars[i]) {
                    var urlVarPair = urlVars[i].split('=');

                    if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
                        urlVarValue = urlVarPair[1];
                    }
                }
            }
        }

        return urlVarValue;
    }

    $(document).ready(function () {
        route = getURLVar('route');

        if (!route) {
            $('#h_dashboard').addClass('active');
        } else {
            part = route.split('/');

            url = part[0];

            if (part[1]) {
                url += '/' + part[1];
            }

            $('a[href*=\'' + url + '\']').parents('li[id]').addClass('active');
        }
    });
    //--></script>