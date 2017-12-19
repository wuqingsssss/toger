/**
 * @file ${FILE_NAME}. Created by PhpStorm.
 * @desc ${FILE_NAME}.
 *
 * @author yangjunbao
 * @since 15/10/29 上午10:31
 * @version 1.0.0
 */
// 页面js文件
$(function () {
    var _module = 'login',
//        $mTab = $('#m-tab'),
        $mLogin = $('#m-login'),
        $mRegister = $('#m-register');

//    function changeModule() {
//        _module = _module === 'login' ? 'register' : 'login';
//        _.$header.find('.' + _module).removeClass('force-hidden').siblings().addClass('force-hidden');
//        $mTab.find('.' + _module).addClass('col-red').siblings().removeClass('col-red');
//        if (_module === 'login') {
//            $mLogin.removeClass('force-hidden');
//            $mRegister.addClass('force-hidden');
//        } else {
//            $mLogin.addClass('force-hidden');
//            $mRegister.removeClass('force-hidden');
//        }
//    }
//
//    $mTab.on('click', 'a', changeModule);

    function popupObject(obj) {
        for (var i in obj) {
            if (obj.hasOwnProperty(i)) {
                return [i, obj[i]];
            }
        }
    }

    // login
//    $mLogin.on('click', '.btn-submit', function () {
//        var error = $mLogin.validate();
//        if (error !== true) {
//            _.alert({
//                content: popupObject(error)[1][0],
//                callback: function () {
//                    $mRegister.find('[name=' + popupObject(error)[0] + ']').focus();
//                }
//            });
//            return;
//        }
//        $.post('', $mLogin.find('form').serialize(), function () {
//            _.alert('登陆失败');
//            _.alert('密码输入有错误, 请重新输入', function () {
//                _.go(_.location.search('redirect', '../home/home.html'));
//            });
//        });
//    });

    // register
    var captchaStatus = 0;
    $mRegister.on(_.touchEnd, '.icon-accept', function () {
        $(this).hasClass('gray')
            ? $(this).removeClass('gray').addClass('red')
            : $(this).addClass('gray').removeClass('red');
    }).on('click', '#form-group-register-captcha>a', function () {
        var $this = $(this);
        if (captchaStatus === 0) {
            var mobile = $('[name="mobile"]').val();
            var vcode_url = 'index.php?route=account/forgotten/validate_mobile&mobile=' + mobile;
            if(mobile.length != 11){
                _.alert('手机号错误');
                return;
            }
            if($('#sys_vcode').length > 0) {
	            var sysvcode = $('[name="sys_vcode"]').val();
	            if (!sysvcode) {
	                _.alert('请输入图形验证码');
	                return;
	            }
	            vcode_url+='&sysvcode=' + sysvcode;
        	}
            
            $.getJSON(vcode_url, function (captcha) {
//              alert(captcha.success);
          	//console.log(vcode_url,captcha);
                  if(captcha&&captcha.success != true){
                      _.alert(captcha.msg['vcode-error']);
                  }else{
                    var max = +new Date + 6e4;
                      captchaStatus = 1;
                      $this.countDown( '{ss}后重新获取', function () {
                          $this.html('重新获取验证码');
                          captchaStatus = 0;
                      }, 900,max) ;
                  }

          })
      }
    }).on('click', '.btn-submit', function () {
 /*       var error = $mRegister.validate();
        if (error !== true) {
            _.alert({
                content: popupObject(error)[1][0],
                callback: function () {
                    $mRegister.find('[name=' + popupObject(error)[0] + ']').focus();
                }
            });
            return;
        }*/
        $('#forgotten').submit();
//        $.post('', $mRegister.find('form').serialize(), function () {
//            _.alert('手机号码已注册, 请登录或找回密码');
//            _.alert('注册失败');
//            _.toast('恭喜您, 注册成功', 3e3, 'icon-success');
//        });
    });
});

function refreshCaptcha(name,url){
	var captcha_link=url+'&'+Date.parse(new Date());
	$('#'+name).attr('src',captcha_link);
}