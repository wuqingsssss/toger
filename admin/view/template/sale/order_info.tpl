<script>
    var app = app || {};
    app.back = history.back.bind(history);//排除冲突
</script>
<div class="box">
    <div class="heading">
        <h2><img src="view/image/order.png" alt=""/> <?php echo $heading_title; ?></h2>

        <div class="buttons" style="margin-bottom:10px;">
            <?php if (isset($ref) && $ref == "purchase") { ?>
                <!-- <button type="button" class="btn btn-danger">撤销</button>-->
            <?php } else { ?>
                <a onclick="location = '<?php echo $action; ?>';"
                        class="btn btn-primary"><?php echo $button_copy; ?></a>
                <a onclick="window.open('<?php echo $invoice; ?>');"
                        class="btn btn-primary"><?php echo $button_invoice; ?></a>
            <?php } ?>
            <?php if (empty($update)) { ?>
             <a onclick="location = '<?php echo $update; ?>';"
                class="btn btn-warning" style="display:none;"><span><?php echo $button_edit; ?></span></a>
            <?php } else { ?>
            <a onclick="location = '<?php echo $update; ?>';"
                class="btn btn-warning"><span><?php echo $button_edit; ?></span></a>
            <?php } ?>
             <a onclick="app.back()" class="btn btn-default"><?php echo $button_cancel; ?></a>
            
        </div>
    </div>
    <div class="content">
        <div class="vtabs"><a href="#tab-order"><?php echo $tab_order; ?></a>

            <a href="#tab-product"><?php echo $tab_product; ?></a>
            <a href="#tab-payment"><?php echo $tab_order_payment; ?></a>
            <a href="#tab-history"><?php echo $tab_order_history; ?></a>

            <a href="#tab-discount"><?php echo $tab_order_discount; ?></a>
        </div>

        <div id="tab-order" class="vtabs-content">
            <table class="form">
                <?php if ($order_status) { ?>
                    <tr class="highlight">
                        <td><?php echo $text_order_status; ?></td>
                        <td id="order-status"><h3><?php echo $order_status; ?>[<?php echo $payment_method; ?>]</h3></td>
                    </tr>
                <?php } ?>
                <tr class="highlight">
                    <td><?php echo $text_order_id; ?></td>
                    <td>#<?php echo $order_id; ?>
                        <?php if ($p_order_id) { ?>
                            - (子订单)
                        <?php } ?>
                    </td>
                </tr>
                <tr class="highlight">
                    <td><?php echo $text_invoice_no; ?></td>
                    <td><?php echo $invoice_no; ?></td>
                </tr>
                <tr class="highlight">
                    <td>预定时间</td>
                    <td><?php if($shipping_point_id>0) echo $pdate; else echo $shipping_time; ?></td>
                </tr>
				<tr class="highlight">
                    <td>订单类型</td>
                    <td><?php echo $type_arr[$order_type]?></td>
                </tr>
				<?php if($addition_info){?>
				<tr class="highlight">
                    <td>拼团ID</td>
                    <td>
						<?php echo $addition_info?>&nbsp;&nbsp;
						<a href="<?php echo $this->url->link('sale/group/group_info_list', 'token=' . $this->session->data['token'].'&cid='.$addition_info, 'SSL');?>">查看团信息</a>
					</td>
                </tr>
				<?php }?>
                <?php if ($shipping_method) { ?>
                 <tr class="highlight">
                        <td>物流</td>
                        <td><?php echo $shipping_code.$shipping_data; ?>单号：<?php echo $sp_order_id ;?></td>
                    </tr>
                    <tr class="highlight">
                        <td>配送方式</td>
                        <td><?php if($shipping_point_id) echo '自提：'.$shipping_method;else echo '宅配：'.$shipping_address_1; ?></td>
                    </tr>
                    <?php if(!$shipping_point_id&&$shipping_firstname){?>
                      <tr class="highlight">
                        <td>收货人</td>
                        <td><?php echo $shipping_firstname;?></td>
                      </tr>
                      <tr class="highlight">
                        <td>收货手机</td>
                        <td><?php echo $shipping_mobile;?></td>
                      </tr>
                      <tr class="highlight">
                        <td>收货地址</td>
                        <td><?php echo $shipping_address_1.$shipping_address_2;?></td>
                      </tr>
                  <?php }else{ ?>
                    <tr class="highlight">
                        <td>取菜码</td>
                        <td><?php echo $pickup_code; ?></td>
                    </tr> 
                     <?php } ?>
                <?php } ?> 
                <?php if ($customer) { ?>
                    <tr>
                        <td><?php echo $text_customer; ?></td>
                        <td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> </a></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td><?php echo $text_customer; ?></td>
                        <td><?php echo $firstname; ?> </td>
                    </tr>
                <?php } ?>
                <?php if ($customer_group) { ?>
                    <tr>
                        <td><?php echo $text_customer_group; ?></td>
                        <td><?php echo $customer_group; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?php echo $text_email; ?></td>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <td><?php echo $text_telephone; ?></td>
                    <td><?php echo $telephone; ?></td>
                </tr>
                <?php if ($fax) { ?>
                    <tr>
                        <td><?php echo $text_fax; ?></td>
                        <td><?php echo $fax; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?php echo $text_store_name; ?></td>
                    <td><?php echo $store_name; ?></td>
                </tr>
                   <tr>
                    <td>来源</td>
                    <td><?php echo $partner ;?></td>
                </tr>
                  <tr>
                        <td>第三方订单ID</td>
                        <td><?php echo $tp_order_id ;?>
   </td>
                    </tr>
                <tr>
                    <td><?php echo $text_store_url; ?></td>
                    <td><a onclick="window.open('<?php echo $store_url; ?>');"><u><?php echo $store_url; ?></u></a></td>
                </tr>
                <?php if (false) { ?>
                    <tr>
                        <td><?php echo $text_ip; ?></td>
                        <td><?php echo $ip; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?php echo $text_total; ?></td>
                    <td><?php echo $total; ?>
                        <?php if ($credit && $customer) { ?>
                            <?php if (!$credit_total) { ?>
                                <img src="view/image/add.png" alt="<?php echo $text_credit_add; ?>"
                                     title="<?php echo $text_credit_add; ?>" id="credit_add" class="icon"/>
                            <?php } else { ?>
                                <img src="view/image/delete.png" alt="<?php echo $text_credit_remove; ?>"
                                     title="<?php echo $text_credit_remove; ?>" id="credit_remove" class="icon"/>
                            <?php } ?>
                        <?php } ?></td>
                </tr>
                <?php if ($reward && $customer) { ?>
                    <tr>
                        <td><?php echo $text_reward; ?></td>
                        <td><?php echo $reward; ?>
                            <?php if (!$reward_total) { ?>
                                <img src="view/image/add.png" alt="<?php echo $text_reward_add; ?>"
                                     title="<?php echo $text_reward_add; ?>" id="reward_add" class="icon"/>
                            <?php } else { ?>
                                <img src="view/image/delete.png" alt="<?php echo $text_reward_remove; ?>"
                                     title="<?php echo $text_reward_remove; ?>" id="reward_remove" class="icon"/>
                            <?php } ?></td>
                    </tr>
                <?php } ?>

                <?php if ($comment) { ?>
                    <tr>
                        <td><?php echo $text_comment; ?></td>
                        <td><?php echo $comment; ?></td>
                    </tr>
                <?php } ?>
                <?php if ($affiliate) { ?>
                    <tr>
                        <td><?php echo $text_affiliate; ?></td>
                        <td>
                            <a href="<?php echo $affiliate; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $text_commission; ?></td>
                        <td><?php echo $commission; ?>
                            <?php if (!$commission_total) { ?>
                                <img src="view/image/add.png" alt="<?php echo $text_commission_add; ?>"
                                     title="<?php echo $text_commission_add; ?>" id="commission_add" class="icon"/>
                            <?php } else { ?>
                                <img src="view/image/delete.png" alt="<?php echo $text_commission_remove; ?>"
                                     title="<?php echo $text_commission_remove; ?>" id="commission_remove"
                                     class="icon"/>
                            <?php } ?></td>
                    </tr>
                <?php } ?>
                <?php if ($invoice_detail_status) { ?>
                    <tr>
                        <td><?php echo $text_invoice_type; ?></td>
                        <td><?php echo $invoice_type; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_invoice_head; ?></td>
                        <td><?php echo $invoice_head; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_invoice_name; ?></td>
                        <td><?php echo $invoice_name; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_invoice_content; ?></td>
                        <td><?php echo $invoice_content; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?php echo $text_date_added; ?></td>
                    <td><?php echo $date_added; ?></td>
                </tr>
                <tr>
                    <td><?php echo $text_date_modified; ?></td>
                    <td><?php echo $date_modified; ?></td>
                </tr>
            </table>
        </div>


        <div id="tab-product" class="vtabs-content">
            <table class="form">
                <tr class="highlight">
                    <?php if ($order_status) { ?>
                        <td class="left"><?php echo $text_order_status; ?> : <?php echo $order_status; ?></td>
                    <?php } ?>
                    <td class="left"><?php echo $text_order_id; ?>#<?php echo $order_id; ?>
                        <?php if ($p_order_id) { ?>
                            - (子订单)
                        <?php } ?>
                    </td>
                    <td class="left">预定日期 :<?php if($shipping_point_id>0) echo $pdate; else echo $shipping_time; ?></td>  
                    <td><?php if($shipping_point_id) echo '自提：'.$shipping_method;else echo '宅配：'.$shipping_address_1; ?></td>
                </tr>
            </table>
            <table id="product" class="list">
                <thead>
                <tr>
                    <td class="left"><?php echo $column_product; ?></td>
                    <td class="left"><?php echo $column_model; ?></td>
                    <td class="right"><?php echo $column_quantity; ?></td>
                    <td class="right"><?php echo $column_price; ?></td>
                    <td class="right"><?php echo $column_promotion; ?></td>
                    <td class="right"><?php echo $column_total; ?></td>
                </tr>
                </thead>
                <?php foreach ($products as $product) { ?>
                    <tbody id="product-row<?php echo $product['order_product_id']; ?>">
                    <tr>
                        <td class="left"><?php if ($product['product_id']) { ?>
                                <a href="<?php echo $product['href']; ?>"
                                   class="popup"><?php echo $product['name']; ?></a>
                            <?php } else { ?>
                                <?php echo $product['name']; ?>
                            <?php } ?>
                            <?php foreach ($product['option'] as $option) { ?>
                                <br/>
                                <?php if ($option['type'] != 'file') { ?>
                                    &nbsp;
                                    <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                                <?php } else { ?>
                                    &nbsp;
                                    <small> - <?php echo $option['name']; ?>: <a
                                            href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a>
                                    </small>
                                <?php } ?>
                            <?php } ?></td>
                        <td class="left"><?php echo $product['model']; ?></td>
                        <td class="right"><?php echo $product['quantity']; ?></td>
                        <td class="right"><?php echo $product['price']; ?></td>
                        <td class="right">
                            <?php if(!empty($product['promotion_code'])) {　?>
                                <?php echo $product['promotion_price']; ?>
                            <?php }?>
                        </td>
                        <td class="right"><?php echo $product['total']; ?></td>
                    </tr>
                    </tbody>
                <?php } ?>
            </table>

            <table id="total" class="list">
                <thead>
                <tr>
                    <td  class="right">
                        <?php if (!empty($p_order_id)) { ?>
                            <b>总单号： #<?php echo $p_order_id; ?></b>
                        <?php }else if(!empty($sub_orders)){ ?>

                            <b>子订单：</b>
                            <?php foreach ($sub_orders as $sub_order) { ?>
                                <b style="margin-left: 10px">#<?php echo $sub_order['order_id']; ?></b>
                            <?php } ?>
                        <?php } ?>
                    </td>
                    <td class="right">订单总计</td>
                </tr>
                </thead>

                <?php foreach ($totals as $totals) { ?>
                    <tbody id="totals">
                    <tr>
                        <td  class="right"><?php echo $totals['title']; ?>:</td>
                        <td class="right"><?php echo $totals['text']; ?></td>
                    </tr>
                    </tbody>
                <?php } ?>
            </table>
            <?php if ($downloads) { ?>
                <h3><?php echo $text_download; ?></h3>
                <table class="list">
                    <thead>
                    <tr>
                        <td class="left"><b><?php echo $column_download; ?></b></td>
                        <td class="left"><b><?php echo $column_filename; ?></b></td>
                        <td class="right"><b><?php echo $column_remaining; ?></b></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($downloads as $download) { ?>
                        <tr>
                            <td class="left"><?php echo $download['name']; ?></td>
                            <td class="left"><?php echo $download['filename']; ?></td>
                            <td class="right"><?php echo $download['remaining']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
         <div id="tab-payment" class="vtabs-content">
            <table class="list">
  <thead>
    <tr>    
      <td class="left"><b><?php echo $column_payment; ?></b></td>
      <td class="left"><b><?php echo $column_trade_no; ?></b></td>
      <td class="left"><b><?php echo $column_value; ?></b></td>
       <td class="left"><b><?php echo $column_date_added; ?></b></td>
       <td class="left"><b><?php echo $column_date_modified; ?></b></td>
      <td class="left"><b><?php echo $column_status; ?></b></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($payments) { ?>
    <?php foreach ($payments as $pay) { ?>
    <tr>
      <td class="left"><?php echo $pay['payment_code']; ?></td>
      <td class="left"><?php echo $pay['payment_trade_no']; ?></td>
      <td class="left"><?php echo $pay['value']; ?></td>
      <td class="left"><?php echo $pay['date_added']; ?></td>
      <td class="left"><?php echo $pay['date_modified']; ?></td>
      <td class="left"><?php echo $pay['status']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table> 
        </div>
        <div id="tab-history" class="vtabs-content">
           <div id="history"></div>
          
        </div>
        <div id="tab-discount" class="vtabs-content">
            <div id="discount-history"></div>
            <?php if (!$discount_status) { ?>
                <?php if ($this->user->permitOr(array('super_admin', 'sale_orders:update'))) { ?>
                    <form id="discount_form">
                        <table class="form">
                            <tr>
                                <td><?php echo $entry_discount; ?></td>
                                <td><input type="text" name="discount" value=""/></td>
                            </tr>
                            <tr>
                                <td class="top"><?php echo $entry_discount_comment; ?></td>
                                <td>
                                    <input type="text" name="comment" class="span6"/>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <a id="button-discount-history"
                                       class="btn"><span><?php echo $button_add_discount_hidtory; ?></span></a>
                                </td>
                            </tr>
                        </table>
                    </form>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
<script src="/assets/js/layer192/layer.js"></script>
<script type="text/javascript"><!--
function showstatusinputbox(){
	console.log($('.status_inputbox'),$('select[name="order_status_id"]').val());
	$('.status_inputbox').hide();
	$('#status_inputbox_'+$('select[name="order_status_id"]').val()).show();

}

    $('#reward_add').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#reward_add').fadeOut();

                    $('#reward_add').replaceWith('<img src="view/image/delete.png" alt="<?php echo $text_reward_remove; ?>" id="reward_remove" class="icon" />');

                    $('#reward_remove').fadeIn();
                }
            }
        });
    });

    $('#reward_remove').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#reward_remove').fadeOut();

                    $('#reward_remove').replaceWith('<img src="view/image/add.png" alt="<?php echo $text_reward_add; ?>" id="reward_add" class="icon" />');

                    $('#reward_add').fadeIn();
                }
            }
        });
    });

    $('#commission_add').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#commission_add').fadeOut();

                    $('#commission_add').replaceWith('<img src="view/image/delete.png" alt="<?php echo $text_commission_remove; ?>" id="commission_remove" class="icon" />');

                    $('#commission_remove').fadeIn();
                }
            }
        });
    });

    $('#commission_remove').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#commission_remove').fadeOut();

                    $('#commission_remove').replaceWith('<img src="view/image/add.png" alt="<?php echo $text_commission_add; ?>" id="commission_add" class="icon" />');

                    $('#commission_add').fadeIn();
                }
            }
        });
    });

    $('#credit_add').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/addcredit&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#credit_add').fadeOut();

                    $('#credit_add').replaceWith('<img src="view/image/delete.png" alt="<?php echo $text_credit_remove; ?>" id="credit_remove" class="icon" />');

                    $('#credit_remove').fadeIn();
                }
            }
        });
    });

    $('#credit_remove').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/removecredit&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#credit_remove').fadeOut();

                    $('#credit_remove').replaceWith('<img src="view/image/add.png" alt="<?php echo $text_credit_add; ?>" id="credit_add" class="icon" />');

                    $('#credit_add').fadeIn();
                }
            }
        });
    });

    $('#history .pagination a').live('click', function () {
        $('#history').load(this.href);

        return false;
    });

    $('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

    function history() {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'html',
            data:$("#form1").serialize(),
			beforeSend: function () {
                $('.success, .warning').remove();
                $('#button-history').attr('disabled', true);
                $('#history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
            },
            complete: function () {
                $('#button-history').attr('disabled', false);
                $('.attention').remove();
            },
            success: function (html) {
            	
            	
            //	layerouturl('https://mapi.alipay.com/gateway.do?service=refund_fastpay_by_platform_pwd&partner=2088311062628944&notify_url=http%3A%2F%2Ftest.qingniancaijun.com.cn%2Fadmin%2Fcontroller%2Fpayment%2Fnotify_url.php&seller_email=qncaijun%40qingniancaijun.com&refund_date=2015-09-11+17%3A11%3A53&batch_no=2015091115091100789&batch_num=1&detail_data=2015091100001000930005372101%5E0.01%5E%E5%85%A8%E9%A2%9D%E9%80%80%E6%AC%BE&_input_charset=utf-8&sign=85a48e036b40f19d65bd74cbe5e982d8&sign_type=MD5','平台退款确认');
            	
            	
                $('#history').html(html);

                $('textarea[name=\'comment\']').val('');

                $('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
            }
        });
    }

    //新增折扣优惠逻辑
    $('#button-discount-history').bind('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/discount&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'html',
            data: $('#discount_form').serialize(),
            beforeSend: function () {
                $('.success, .warning').remove();
                $('#button-discount-history').attr('disabled', true);
                $('#discount-history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
            },
            complete: function () {
                $('#button-discount-history').attr('disabled', false);
                $('.attention').remove();
            },
            success: function (html) {
                $('#discount-history').html(html);

                $('#button-history').forms[0].reset();
            }
        });
    });

    $('#discount-history .pagination a').live('click', function () {
        $('#discount-history').load(this.href);

        return false;
    });

    $('#discount-history').load('index.php?route=sale/order/discount&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
    
    function layerouturl(url,stitle)
    {
    	console.log('layerouturl:'+url+':'+stitle);
    	var top=$(document).scrollTop();
    	var height=$(window).height();
    	var height2=$(document).height();
    	layer.open({
            type: 2,
            //skin: 'layui-layer-lan',
            title: stitle,
            fix: false,
            shadeClose: true,
            maxmin: false,
            area: ['1000px','600px'],
            content: url,
            success:function()
            {    $('body').css('height', height2);
                  console.log(height2);
            	 $('body').css('overflow','hidden');
            	 
            	 /*$('body').bind("touchmove",function(e){  
                       // e.preventDefault();  
                    });  
            	 $ (window).scroll (function ()
            			    {
            			      $(this).scrollTop(top);
            			    });*/
            },
            end:function(){
            	
            	$('body').css('overflow','auto');
            	$('body').css('height', 'auto');
            	
             	$('.openbox').removeClass('openbox');
        	    $('#blackbox').removeClass('black');
        	 
            	/*$('body').unbind();
            	$ (window).unbind ('scroll');*/
            	getCart();
            	
            } 		
        });
    }
    
    //--></script>
<script type="text/javascript"><!--
    $('.vtabs a').tabs();
    //--></script>
