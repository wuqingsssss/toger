<?php
//require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect();
?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
    <div id="content">
        <div class="breadcrumb"><?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?></div>

        <?php if ($success) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>
        <div class="login-content">

            <?php if ($detect->isTablet() || !$detect->isMobile()) { ?>
                <div id="login-ad" class="fl">
                    <?php echo $content_top; ?>
                </div>
            <?php } ?>

            <div id="login-panel" class="fr">
                <div class="mt">
                    <?php echo $heading_title; ?><span><?php echo $text_no_account; ?></span>
                </div>

                <div class="content">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="login">
                        <div class="form">
                            <span class="required">*</span> <?php echo $entry_email; ?>
                            <br/>
                            <input type="text" name="email" value="<?php echo $email; ?>" class="span4"
                                   autocomplete="off"/>
                            <?php if ($error_warning) { ?>
                                <span class="error"><?php echo $error_warning; ?></span>
                            <?php } ?>
                            <br/>
                            <br/>
                            <span class="required">*</span> <?php echo $entry_password; ?><br/>
                            <input type="password" name="password" value="" class="span4" autocomplete="off"/><br/>

                            <div class="left">
                                <input type="checkbox" class="checkbox" name="remember">
                                <label class="mar"><?php echo $entry_auto; ?></label>
                                &nbsp;&nbsp;
                                <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
                            </div>
                            <br>

                            <div class="left">
                                <a onclick="$('#login').submit();" class="button">
                                    <span><?php echo $button_login; ?></span>
                                </a>

                            </div>
                            <br>
                   
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php echo $content_bottom; ?></div>
    <script src="http://ilex-static.oss-cn-hangzhou.aliyuncs.com/lib/json/3.3.2/json3.min.js"
            type="text/javascript"></script>
    <script src="http://ilex-static.oss-cn-hangzhou.aliyuncs.com/lib/storage/storage-polyfill.js"
            type="text/javascript"></script>
    <script src="http://ilex-static.oss-cn-hangzhou.aliyuncs.com/lib/crypto/3.1.2/aes.js"
            type="text/javascript"></script>
    <script type="text/javascript"><!--
        $(document).ready(function () {
            $('#login input').keydown(function (e) {
                if (e.keyCode == 13) {
                    $('#login').submit();
                }
            });
            var remember$ = $('[name=remember]');
            $('#login').submit(function () {
                var this$ = $(this);
                var remember = remember$.is(':checked');
                if (remember) {
                    var data = this$.serializeArray();
                    var encoded = CryptoJS.AES.encrypt(JSON.stringify(data), "ilex_auth_sec$").toString();
                    storage.local.setItem('login-auth', encoded);
                } else {
                    storage.local.removeItem('login-auth');
                }
            });
            function recoverRemember() {
                var encoded = storage.local.getItem('login-auth');
                if (encoded) {
                    try {
                        var parsedStr = CryptoJS.AES.decrypt(encoded, 'ilex_auth_sec$').toString(CryptoJS.enc.Utf8);
                        var authArray = JSON.parse(parsedStr);
                        _.forEach(authArray, function (item) {
                            $('[name="' + item.name + '"]').val(item.value);
                        });
                        remember$.attr('checked','checked');
                    } catch (e) {
                        console.error(e);
                    }
                }
            }
            recoverRemember();
        });
        //--></script>

    <script
        type="text/javascript"
        src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css"
          href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css"
          media="screen"/>
    <script type="text/javascript"><!--
        $('.fancybox').fancybox({
            width: 560,
            height: 560,
            autoDimensions: false
        });
        //--></script>
<?php echo $footer; ?>