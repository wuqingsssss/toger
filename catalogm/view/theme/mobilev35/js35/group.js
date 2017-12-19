!function () {
    $.task.monitor({'ajax': false});//开启js任务控制
    console.log = function () {
    };
    function b() {
        var e, b = document.getElementById("J_style"),
                c = document.documentElement.clientWidth || document.body.clientWidth, //可视区域的宽高和body的宽高一样
                d = 1;
        d = c / 640, e = 100 * d,
                b.innerHTML = "html{font-size:" + e + "px;}", a = d;
        window._z = d;
    }
    var a = 0;
    b(), window.addEventListener("resize", b);
}();
window._idx = 10;
$(function () {
    console.log('header1');
    _.waiting = $('.waiting');
    _.waiting.hide();
});

$(function () {
    var $mAddCart = $('#m-add-cart'),
            foodId = $mAddCart.data('id'),
            code = $mAddCart.data('code'),
            $num = $mAddCart.find('.food-num-val');

    $('#m-add-cart .btn-fixed-submit').bind('click', function () {
        _.addCart(foodId, code, $num.html(), function () {
            $mAddCart.find('.btn-fixed-submit').tipsBox('<span class="col-red fz-14 bold">+' + $num.html() + '</span>');
            window.location = "index.php?route=checkout/cart";
        });
    });

    $('#m-add-cart .icon-subtract').bind('click', function () {
        $num.html(+$num.html() - 1 || 1);
    });
    $('#m-add-cart .icon-add').bind('click', function () {
        $num.html(+$num.html() + 1);
    });


    var $addFollow = $('.icon-heart');
    $addFollow.bind('click', function () {
        _.addFollow(foodId, function () {
            $addFollow.tipsBox('<span class="col-red fz-14 bold">&#10084;+1</span>');
        });
    });

});


// 页面js文件
$(function () {
    //不可重复点击
    var tiemer = "";
    var time = 100000;
    var fun = function bClick() {
        var gid = $("#click_btn").attr('gid');
        $("#click_btn").unbind("click");
        tiemer = setTimeout(function () {
            $("#click_btn").click(fun);
        }, time);
        get(gid);
    };
    $("#click_btn").click(fun);
    //创建团购方法
    function get(gid) {
        $.ajax({
            url: 'index.php?route=group/group/create_group&gid=' + gid,
            type: 'get',
            data: {
            },
            dataType: 'json',
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (json) {
                if (json['code'] > 0) {
                    //跳转到订单页
//                        alert(json['msg']);
                    location.href = "index.php?route=checkout/checkout_group&groupbuy_id=" + gid;
                } else {
                    _.alert(json['msg']);
                }
            }
        });
    }
    ;
});