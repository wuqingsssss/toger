// 页面js文件
$(function () {
    get = function (cid, code,obj) {
        $.ajax({
            url: 'index.php?route=account/getcoupon/get_coupon',
            type: 'post',
            data: {
                c_id: cid,
                coupon: code
            },
            dataType: 'json',
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (json) {
                if (json['code']>0) {
                	obj.removeClass().addClass("get_grey ticket-already");
                    _.alert('恭喜您，领取成功<br/>快去买菜吧');
                } else if(json['code'] == -998){
                    //跳转
                    window.location.href = json['url'];
                }else if(json['code'] == -2){
                    obj.removeClass().addClass("get_grey ticket-already");
                    _.alert(json['msg']);
                }else{
                    _.alert(json['msg']);
                }
            }
        });
    };
});