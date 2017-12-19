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
    $('#m-charge').on('click', 'li', function () {
        $(this).addClass('active').siblings('.active').removeClass('active');
    }).on('click', '#m-charge .btn-submit', function () {
        var id = $('li.active').data('id');
 /*       $.post('index.php?account/transaction/', {
            id: id
        }, function (data) {
            if (data.status === 200) {
                location.href = "index.php?account/transaction/charge&orderId=" + data.data.orderId
            } else {
                _.alert('创建订单失败, 请稍候再试')
            }
        }, 'json')*/
        
      	$.ajax({
    		url: 'index.php?route=account/transaction/payment',
    		type: 'post',
    		data: "data-id=" + id.toString(),
    		dataType: 'json',
    		beforeSend: function() {
    			$('body').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/mobilev35/images/waiting.png"/></span>'); 			
    		},
    		complete: function() {
    			$('.waiting').remove(); 

    		},
    		success: function(json) {
    			$('.waiting').remove(); 

    			if (json['error']) {
    				if (json['error']['warning']) {
    					_.alert(json['error']['warning']);
    				}
    			}
    			else{
    				$('#content').append(json['output']);
    				$('#m-charge').hide();
    				//$('#m-payment').show();
    			}
    		}
    	});
    });
    
    $('#m-payment .btn-submit').live('click', function () {
    	var paytype = $("input[name='payment_method']:checked").val();
    	
    	$('#m-payment .btn-submit').attr({"disabled":"disabled"});
     	$.ajax({
    		url: 'index.php?route=account/transaction/paysubmit',
    		type: 'post',
    		data: "payment_method=" + paytype,
    		dataType: 'json',
    		beforeSend: function() {
    			$('body').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/mobilev35/images/waiting.png"/></span>'); 			
    		},
    		complete: function() {
    			$('.waiting').remove(); 

    		},
    		success: function(json) {
    			$('.waiting').remove(); 
    			console.log(json);

				if (json['error']) {					
					if (json['error']['warning']) {
						
						$('#m-payment .btn-submit').removeAttr('disabled');
						_.toast(json['error']['warning'], 3000);
					}
					if (json['redirect']) {			
						window.location = json['redirect'];
					}
				} else {
					_.toast('提交成功跳转中...');
					if (json['redirect']) {									
						window.location = json['redirect'];
					}
					if(json['payment']){
						$("#payment").html(json['payment']);	
					}
				}
			}
		});
    });
});