<?php echo $header; ?>
    <div id="header" class="bar bar-header bar-default">
        <h1 class="title"><?php echo $heading_title; ?></h1>
    </div>
    <div id="content" class="content">
        <div id="login-panel">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="login">
                <div class="form">
                    <br/>
                    <input type="text" name="email" placeholder="手机号" value="<?php echo $email; ?>" class="login_form_mobile" autocomplete="off"/>
                    <?php if ($error_warning) { ?>
                        <span class="error"><?php echo $error_warning; ?></span>
                    <?php } ?>
                    <br/>
                    <br/>
                    <input type="password" name="password" placeholder="密码" value="" class="login_form_password" autocomplete="off"/><br/>

                    <br/>
                    <div class="left">
                        <input type="checkbox" class="checkbox" name="remember">
                        <label class="mar"><?php echo $entry_auto; ?></label>

                        <a href="<?php echo $forgotten; ?>" class="fr login_form_forgets"><?php echo $text_forgotten; ?></a>
                    </div>
                    <br>

                    <div class="left">
                        <button onclick="$('#login').submit();" class="button button-block button-positive">
                            <?php echo $button_login; ?>
                        </button>

                        <a href="<?php echo $this->url->link('account/register'); ?>" class="button button-block button-default">
                            注 册
                        </a>
                    </div>
                    <br>
                    <?php if ($redirect) { ?>
                        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>"/>
                    <?php } ?>
                </div>
            </form>

        </div>
    </div>
    <div>
        <span class="help" style="padding-left:30px">*系统自动生成帐号用户请点此<a href="<?php echo $forgotten; ?>"><u><font color=#0da299>【重置密码】</font></u></a></span>
    </div>
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
                    localStorage.setItem('login-auth', encoded);
                } else {
                    localStorage.removeItem('login-auth');
                }
            });
            function recoverRemember() {
                var encoded = localStorage.getItem('login-auth');
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
<?php echo $footer; ?>