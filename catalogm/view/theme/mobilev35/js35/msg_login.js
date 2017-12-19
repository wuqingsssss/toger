/**
 * @version 1.0.0
 */
// 页面js文件
$(function () {
    var captchaStatus = 0;
    $('#form-group-register-captcha>a').bind('click', function () {
        var $this = $(this);
        if (captchaStatus === 0) {
            var mobile = $('[name="mobile"]').val();
            var vcode_url = 'index.php?route=account/login/get_mobile_code&mobile=' + mobile;
            if (mobile.length != 11) {
                _.alert('手机号错误');
                return;
            }
            $.getJSON(vcode_url, function (captcha) {

                //            console.log(captcha);

                if (captcha && captcha.success != true) {
                    _.alert(captcha.msg['vcode-error']);
                } else {
                    _.alert('已发送，请注意查收');
                    var max = +new Date + 6e4;
                    captchaStatus = 1;
                    $this.countDown('{ss}后重新获取', function () {
                        $this.html('重新获取验证码');
                        captchaStatus = 0;
                    }, 900, max);
                }

            })
        }
    });
});

