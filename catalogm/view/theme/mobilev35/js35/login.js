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
	 if($('#error_mobile').html() != '' &&  $('#error_mobile').html()!=null){
		 _.alert($('#error_mobile').html());
	 }
	 if($('#error_mobile_vcode').html() != '' && $('#error_mobile_vcode').html() != null){
		 _.alert($('#error_mobile_vcode').html());
	 }
	
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
//    $mRegister.on('click', '.icon-accept', function () {
//        $(this).hasClass('gray')
//            ? $(this).removeClass('gray').addClass('red')
//            : $(this).addClass('gray').removeClass('red');
//    });
    
    $('#form-group-register-captcha>a').bind('click',  function () {
        var $this = $(this);
        if (captchaStatus === 0) {
            var mobile = $('[name="mobile"]').val();
            var vcode_url = 'index.php?route=account/register/validate_mobile&mobile=' + mobile;
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

            	//console.log(captcha);
            	
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
    });
    
    
    $('#register .btn-submit').bind('click', function () {
      /*  var error = $mRegister.validate();
        if (error !== true) {
            _.alert({
                content: popupObject(error)[1][0],
                callback: function () {
                    $mRegister.find('[name=' + popupObject(error)[0] + ']').focus();
                }
            });
            return;
        }*/
//        if (!$mRegister.find('.icon-accept').hasClass('red')) {
//            _.alert({content: '请阅读用户协议'});
//            return;
//        }else{
//            $('[name="agree"]').val(1);
//        }
        $('#register').submit();
//        $.post('', $mRegister.find('form').serialize(), function () {
//            _.alert('手机号码已注册, 请登录或找回密码');
//            _.alert('注册失败');
//            _.toast('恭喜您, 注册成功', 3e3, 'icon-success');
//        });
    });
    
    $('#form-group-metro-captcha>a').bind('click',  function () {
        var $this = $(this);
        if (captchaStatus === 0) {
            var mobile = $('[name="mobile"]').val();
            var vcode_url = 'index.php?route=campaign/metro/validate_mobile&mobile=' + mobile;
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

            	//console.log(captcha);
            	
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
    });
    
    
    $('#metro .btn-submit').bind('click', function () {
          $('#metro').submit();
    });
});



function refreshCaptcha(name,url){
	var captcha_link=url+'&'+Date.parse(new Date());
	$('#'+name).attr('src',captcha_link);
}


/*
$(document).ready(function () {
	var curCount = 0;
    var getBtn$ = $('#form-group-register-captcha>a');
    getBtn$.click(function () {

        var mobile = $('[name="mobile"]').val();
        if (!mobile) {
            $('#mobile-error').text('请填写手机号码');
            return;
        }
        var url = 'index.php?route=account/register/validate_mobile&mobile=' + mobile;
 
        var sysvcode = $('[name="sys_vcode"]').val();
       if (!sysvcode) {
           $('#sys-vcode-error').text('<?php echo $entry_sys_vcode; ?>');
           return;
       }
       url+='&sysvcode=' + sysvcode;
       
     
        getBtn$.attr('disabled', 'disabled');
        $.getJSON(url, function (result) {
            console.log(result);
            getBtn$.attr("disabled", "true");
            curCount = 60;
            InterValObj= window.setInterval( function(){
               
          	   if (curCount == 0) {                
                   window.clearInterval(InterValObj);//停止计时器
                   getBtn$.removeAttr("disabled");//启用按钮
                   getBtn$.text("获取验证码");
                   $('.error').html('');
               }
               else {
                   curCount--;
                   getBtn$.text("重新发送(" + curCount +")" );
               }
            }, 1000); //启动计时器，1秒执行一次
            if (!result.success) {
            	curCount=1;
                $('.error').html('');
            	for(id in result.msg)
                    $('#'+id).text(result.msg[id]);
            	
            	if(result.msg['sys-vcode-error'])
            	        $("#sys_vcode").click();
            	
            } else {
                $('#mobile-vcode-error').html('<span style="color:#0000ff">请查看您的手机获取验证码</span>');
            }
        })
    });
});

*/