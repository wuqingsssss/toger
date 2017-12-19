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
    var $mEmpty = $('#m-empty'),
        $mFoods = $('#m-foods'),
        $totalPrice = $mFoods.find('.total-price');

    function init() {
        if ($mFoods.find('li').length === 0) {
            $mFoods.remove();
            $mEmpty.forceShow();
            return false;
        }
        if($('#empty').val() == '1'){
            _.alert('有菜品超期或库存不足<br/>请修改后提交订单');
        }
        return true;
    }
    if (!init()) {
        return;
    }
    //修改商品价格   此方法无用
    function changePrice(delta) {
        $totalPrice.html((+$totalPrice.html() + delta).toFixed(2));
        if ($mFoods.find('.input-checked:not([value="on"])').length === 0) {
            $mFoods.find('.check-all>i').addClass('checked');
        } else {
            $mFoods.find('.check-all>i').removeClass('checked');
        }
    }
    //edit_cart copy common.js 购物车加减商品
    edit_cart = function (foodId, code, num, callback) {
        $.post('index.php?route=checkout/cart/update', {
            product_id: foodId,
            promotion_code: code,
            quantity: num
        }, function (json) {
            if (json['success']) { // success
                var $dom = _.$navbar.find('.cart-num');
                $dom.html(+$dom.html() + parseInt(num)).cssAnimateOnce('pulse fast');
//                alert(json['value']);
                $totalPrice.html(json['value']);
                if(json['refresh']){
                    location.reload();
                }else{
                    callback && callback();
                }
                
            }
        }, 'json');
    };
    //购物车 删除商品
    remove_pro = function (id, callback) {
        url = 'index.php?route=checkout/cart/remove';
        var arr = new Array();
        arr[0] = id;
        $.post(url, {
            remove: arr,
            type: 'mobile'
        }, function (json) {
            if (json['count'] > 0) {
                var $dom = _.$navbar.find('.cart-num');
                $dom.html(json['count']).cssAnimateOnce('pulse fast');
                callback && callback();
            }
            //购物车空，加载空页面
            if (json['count'] == 0) {
                location.reload();
            }
        }, 'json');
    };
    $mFoods.on('click', '.checkbox>i', function () {
        var $this = $(this).parent(),
            $food = $this.parent(),
            $checkbox = $this.find('i'),
            price,
            checked = $checkbox.hasClass('checked');
            
        checked ? $checkbox.removeClass('checked') : $checkbox.addClass('checked');
        $food.find('.input-checked').val(checked ? 'off' : 'on');
        price = $food.find('.input-unit-price').val() * $food.find('.input-num').val();
        changePrice(checked ? -price : price)
    }).on('click', '.icon-subtract', function () {
        var $this = $(this),
            $food = $this.closest('li'),
            foodId = $food.attr('data-id'),
            code = $food.attr('code'),
            $foodNum = $food.find('.food-num-val'),
            unitPrice = -$food.find('.input-unit-price').val(),
            foodNum = +$foodNum.html() - 1,
            checked = $food.find('.input-checked').val() === 'on';
    
        edit_cart(foodId, code, '-1', function () {
            $food.find('.input-num').val(foodNum);
            $foodNum.html(foodNum);
            if (foodNum <= 0) {
                var par = $this.parent().parent();
                par.remove();
            }
            //购物车空 加载空页面
            if ($('li').length == 0) {
                location.reload();
            }
        });
        //如果购物车为空 刷新页面

    }).on('click', '.icon-add', function () {
        var $this = $(this),
            $food = $this.closest('li'),
            foodId = $food.attr('data-id'),
            code = $food.attr('code'),
            $foodNum = $food.find('.food-num-val'),
            unitPrice = +$food.find('.input-unit-price').val(),
            foodNum = +$foodNum.html() + 1,
            checked = $food.find('.input-checked').val() === 'on';
        edit_cart(foodId, code, '1', function () {
            $food.find('.input-num').val(foodNum);
            $foodNum.html(foodNum);
        });
    }).on('click', '.delete>i', function () {
        var $this = $(this).parent(),
            $food = $this.closest('li'),
            foodId = $food.data('id'),
            code = $food.data('code'),
            key = $food.data('key'),
            $foodNum = $food.find('.food-num-val'),
            unitPrice = -$food.find('.input-unit-price').val(),
            foodNum = +$foodNum.html(),
            amount = unitPrice * foodNum,
            checked = $food.find('.input-checked').val() === 'on';
        _.confirm('确定删除吗?', function () {
            remove_pro(key, function () {
                changePrice(amount);
                $food.remove();
            });
        });
    }).on('click', '.check-all>i', function () {
        var $this = $(this),
                checked = $this.hasClass('checked');
        if (checked) {
            $mFoods.find('.checkbox>i.checked').trigger('click');
        } else {
            $mFoods.find('.checkbox>i:not(.checked)').trigger('click');
        }
    });
});