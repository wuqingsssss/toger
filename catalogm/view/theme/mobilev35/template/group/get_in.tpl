<?php echo $headersimple; ?>
<!-- link href="assets/libs/mobiscroll/css/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css" />
<link href="assets/libs/mobiscroll/css/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css" /-->

<link href="assets/libs/mobiscroll-2.13.2/style/mobiscroll.2.13.2.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/order.css?v=<?php echo STATIC_VERSION; ?>" />
<link rel="stylesheet" type="text/css" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/cart.css?v=<?php echo STATIC_VERSION; ?>" />

<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/<?php echo $this->config->get("config_template"); ?>/images/waiting.png"/></span>
<div id="content" class="pg-page checkout-page" pg-name="checkout">
	<!-- 公共头开始 -->
	<?php echo $header ?>
	<!-- 公共头结束 -->
	<input name="verified" type="hidden" id="verified" value="1" >
	<div id="warnning"></div>
	<div class="checkout">
		<?php if ($shipping_required) { ?>
			<div id="shipping-method">
				<!--配送信息 START-->
				<div class="order_distribution">    
					<div class="o_d_title fl">配送信息：</div>
					<div class="o_d_information">
						<?php if ($address) { ?>
							<div class="o_address">
								<span><?php echo $entry_customer . "&nbsp;&nbsp;" . $address['firstname']; ?></span>
								<span class="mobile"><strong><?php echo $address['mobile']; ?></strong></span>
								<span><?php echo $entry_address . "&nbsp;&nbsp;" . $address['address_1'] . $address['address_2']; ?></span>
							</div>
							<div class= "o_right_arrow order_in_right icon icon-right-open-big col-green">
							   <!--  <img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/right.png" style="margin-top:15%;" class="order_in_right"/>--> 
							</div>
						<?php } else { ?>
							<img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/gps.png" class="order_in_img"/>
							<span style="line-height: 1.6rem;margin-left:10px;">请添加您的配送信息</span>
							<div class= "o_right_arrow order_in_right icon icon-right-open-big col-green"></div>
							<!--  <img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/right.png" class="order_in_right"/>-->
						<?php } ?>
					</div>
					<ul id="mapBox" style="display:none;">
						<div id="map" class="pull-left"></div>
						<div id="mapPanel" class="pull-right">
							<div id="mapListWrap">
								<table class="table table-hover">
									<tbody id="mapList">
									</tbody>
								</table>
							</div>
							<div id="mapPager" class="pagination text-center"></div>
						</div>
					</ul>
				</div>
				<!--配送信息 END-->

				<!--配送时间 START-->
				<div class="order_time show-overlay" id="m-result">
					<span class="order_t_title fl">配送时间：</span>
					<span data-role="fieldcontain" class="result demo demo-select-opt" style="line-height: 0.5rem;">
						<select id="pickupdate" name="pickupdate" class="demo-test-select-opt" data-role="none" onchange="updateAdditionalDate();">
							<?php foreach ($dates as $dkey => $date) { ?>	 
								<optgroup label="<?php echo $date['title']; ?>">
									<?php foreach ($date[times] as $ttkey => $tt) { ?>	 
										<option value="<?php echo $ttkey; ?>"><?php echo $tt; ?></option>
									<?php } ?>
								</optgroup>
							<?php } ?>	
						</select>	
					</span>

					<?php
					if ($address['shipping_data'] == '回龙观') {
						?><br><font color="red" class="fz-12" >因配送方原因，回龙观区暂时可配送时间为14点至17点！</font>
					<?php }
					?>  


					</span>
					<div class= "o_right_arrow order_in_right icon icon-right-open-big col-green"></div>
					<!--  <img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/right.png" class="order_in_right" style="margin-top: 0.3rem;"/>-->
				</div>
				<!--配送时间 END-->

				<div class="cart-module">
					<?php if ($shipping_code == 'flat.flat') { ?>
						<?php echo $this->getChild('total/shipping_time'); ?>
					<?php } else if ($shipping_code == 'free.free') { ?>
						<?php echo $this->getChild('total/point'); ?>
					<?php } ?>
				</div>

			</div>
		<?php } ?>

		<!-- 支付方式  START-->
        <div class="order_pay">
            <div class="order_p_way">支付方式：</div>
           	<div class="order_p_con">		
				<?php echo $payment_methods; ?>    		 
            </div>
        </div>

        <!-- 订单详情 START -->
        <div id="confirm" class="order_pay">
            <div class="order_p_way">
				<?php echo $text_checkout_product; ?>  
                <a href="<?php echo $cart; ?>" class="btn btn-green"><?php echo $text_modify_cart; ?></a>
            </div>

			<?php echo $order_confirm; ?>

        </div>

        <!-- 订单详情 END -->
        <div class="cart-module">
			<?php foreach ($modules as $module) { ?>
				<?php echo $module; ?>
			<?php } ?>
        </div>

        <!-- 结算 START -->
        <div class="checkout-block">

		</div>
		<!-- 结算 END -->


    </div>
</div>

<?php if ($coupons) { ?>
	<div class="pg-page coupon-select" pg-name="coupon">
		<?php echo $coupons ?>
	</div>
<?php } ?>
</div>


<script type="text/javascript">
<!--
    var pages;
    $(function () {
        pages = new PageSwitch();

        // MODULE备注绑定
        $('.cart-module #comment .module-heading').bind('click', function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }

            $(this).parent().find('.module-content').slideToggle('slow');
        });
        //MODULE VIP码绑定
        $('.cart-module #reference .module-heading').bind('click', function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }

            $(this).parent().find('.module-content').slideToggle('slow');
        });

        // MODULE 优惠券绑定
        $('.cart-module #coupon .module-heading').bind('click', function () {
            pages.switchTo(1);
        });

        $('.coupon-select').bind('click', function () {
            var $this = $(this);

            var id = $this.attr('data-id');
            selectCoupon(id);
        });
    });
    // 选择优惠券处理
    function selectCoupon(id) {
        $.ajax({
            url: 'index.php?route=checkout/checkout/selectcoupon',
            type: 'post',
            data: "data-id=" + id.toString(),
            dataType: 'json',
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (json) {
                if (json['error']) {
                    if (json['error']['warning']) {
                        _.alert(json['error']['warning']);
                    }
                }
                else {
                    checkoutComfirm();
                    pages.switchTo(0);
                }
            }
        });
    }

    // 地址选择
    $('.order_distribution .o_d_information').bind('click', function () {
        window.location = "index.php?route=checkout/shipping_method";
    });


    // 支付方法
    $("input[name='payment_method']").live('click', function () {
        $.ajax({
            url: 'index.php?route=checkout/payment/changemethod',
            type: 'post',
            data: $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-confirm').attr('disabled', true);
                $('#button-confirm').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/<?php echo $this->config->get("config_template"); ?>/images/waiting.png"/></span>');
            },
            complete: function () {
                $('#button-confirm').attr('disabled', false);
                $('.waiting').remove();
            },
            success: function (json) {
                $('.warning').remove();

                if (json['redirect']) {
                    window.location = json['redirect'];
                }

                if (json['error']) {
                    if (json['error']['warning']) {
                        _.toast(json['error']['warning'], 5000);
                    }
                } else {
                    checkoutComfirm();
                }
            }
        });
    });


    //更新支付信息
    function checkoutComfirm() {
        $.ajax({
            url: 'index.php?route=checkout/payment/update',
            dataType: 'json',
            success: function (json) {
                if (json['redirect']) {
                    window.location = json['redirect'];
                }

                if (json['output']) {
                    $('.checkout-block').html(json['output']);

                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError);
            }
        });

    }
//-->
</script>


<!-- s日期控件>
   <script src="assets/libs/mobiscroll/js/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
   <script src="assets/libs/mobiscroll/js/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>
   <script src="assets/libs/mobiscroll/js/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
   <script src="assets/libs/mobiscroll/js/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script-->
<!-- S 可根据自己喜好引入样式风格文件 -->
<!-- script src="m/mobiscroll/js/mobiscroll.android-ics-2.5.2.js" type="text/javascript"></script-->
<!-- E 可根据自己喜好引入样式风格文件 -->
<!-- E 日期控件 -->
<script src="assets/libs/mobiscroll-2.13.2/script/mobiscroll.2.13.2.js"></script>


<script>
    $(document).ready(function () {
        var aaa = $('#pickupdate').mobiscroll('destroy').mobiscroll({
            preset: 'select',
            group: true,
            width: 50,
            theme: 'android-holo light',
            groupLabel: '#showdate',
            mode: 'scroller',
            display: 'modal',
            animate: ''
        });
        $('#m-result').bind('click', function () {
            $.mobiscroll.instances.pickupdate.show();
        });

        checkoutComfirm();

    });

    function updateAdditionalDate() {
        console.log('date=' + $('#pickupdate').val());
        $.ajax({
            url: 'index.php?route=checkout/checkout/updateAdditionalDate',
            type: 'post',
            cache: false,
            data: 'date=' + $('#pickupdate').val(),
            dataType: 'text',
            success: function (json) {
                console.log(json);
            }
        });

    }
</script>
<script type="text/javascript" src="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/js35/confirm_order_popup.js"></script>
<script type="text/javascript" src="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/js35/common.js"></script>
<script src="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/js35/pageswitch.js"></script>
<?php echo $footer35; ?>