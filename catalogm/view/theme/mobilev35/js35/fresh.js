
$(function () {
	
	$('#m-metro .form-group.g2').show();
    input = function () {
        var mobile = $('#input-new-phone').val();
        var name = $('#name').val();
        var add = $('#address').val();
        var code = $('#code').val();
        var pickdate = $('#pickdate').val();
        
       /* var num = check();
        if (num == -1) {
            _.alert('手机号码输入有误');
            return;
        }
        if (num == -2) {
            _.alert('领取码输入有误');
            return;
        }
        if (num == -3) {
            _.alert('请正确填写表单');
            return;
        }*/
        url = 'index.php?route=campaign/fresh/get_input';
        $.post(url, {
            mobile: mobile,
            name: name,
            address: add,
            code: code,
            pickdate:pickdate
        }, function (json) {
        	console.log(json);
            if (json['code'] > 0) {   	
            	$('.form-middle').html('提交成功！请保持手机畅通<br/>青年菜君将在您指定的时间发货');
                _.alert('领取成功，请保持手机畅通');
            } else {
            	if(json['code']==-2)
                {
            		$('#m-metro .form-group.g1').hide();
            		$('#m-metro .form-group.g2').show();
                }
            	else if(json['code']==-1)
                {
            		$('#m-metro .form-group.g1').show();
            		$('#m-metro .form-group.g2').hide();
            		_.alert(json['msg']);
                }
            	else
            	{
            		_.alert(json['msg']);
            		$('#m-metro .form-group.g1').hide();
            		$('#m-metro .form-group.g2').show();
            	}
            }
        }, 'json');

    };

    function check() {
        var mobile = $('#input-new-phone').val();
        var name = $('#name').val();
        var add = $('#address').val();
        var code = $('#code').val();
        //验证手机号
        if (!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(mobile))) {
            return -1;
        }
        if (code.length != 10) {
            return -2;
        }
        if ($.trim(name) == '' || $.trim(add) == '' || $.trim(name) == '') {
            return -3;
        }
        return 1;
    }


});