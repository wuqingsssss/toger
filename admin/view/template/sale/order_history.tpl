<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list">
  <thead>
    <tr>
      <td class="left"><b><?php echo $column_date_added; ?></b></td>
      <td class="left"><b><?php echo $column_comment; ?></b></td>
      <td class="left"><b><?php echo $column_status; ?></b></td>
      <td class="left"><b><?php echo $column_notify; ?></b></td>
      <td class="left"><b><?php echo $column_operator; ?></b></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <tr>
      <td class="left"><?php echo $history['date_added']; ?></td>
      <td class="left"><?php echo $history['comment']; ?></td>
      <td class="left"><?php echo $history['status']; ?></td>
      <td class="left"><?php echo $history['notify']; ?></td>
      <td class="left"><?php echo $history['operator']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<?php if ($histories) { ?>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } ?>

<?php if(isset($express)&&$express){?>
<div class="express" style="padding:5px;margin-bottom:10px;background-color: #EFEFEF;">
<p><?php echo $text_express;?> :<a href="<?php echo $express_website;?>"><b><?php echo $express;?></b></a> , <?php echo $text_express_no;?> :<b><?php echo $express_no;?></b></p>
</div>
<?php } ?>

<?php if(!isset($this->request->get['readonly'])){?>
 <form id="form1">
            <input type="hidden" name="express_no" value=""/>
            <input type="hidden" name="express" value=""/>
            <?php if ($this->user->permitOr(array('super_admin', 'sale_orders:update'))) { ?>
                <table class="form">
                    <tr>
                        <td style="vertical-align: top;"><?php echo $entry_order_status; ?></td>
                        <td>
                        <select name="order_status_id" onchange="showstatusinputbox();">
                                <?php foreach ($order_statuses as $order_statuses) { ?>
                                    <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
                                        <option value="<?php echo $order_statuses['order_status_id']; ?>"
                                                selected="selected"><?php echo $order_statuses['name']; ?></option>
                                    <?php } else { ?>
                                        <option
                                            value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                      <?php if($refunds){?>
                      <table id="status_inputbox_11" class="list status_inputbox<?php if($order_status_id!=11) echo ' hide';?>">
						<tr>
								<td>支付方式</td>
								<td>退款金额</td>
								<td>退款方式</td>
							</tr>
							<?php foreach ($refunds as $pay) { ?>
							  <tr>
								<td><?php echo $pay[payment_code1];?>：<?php echo $pay[value1];?>¥
								</td>
								<td><?php echo $pay[value];?>[<?php echo $pay[reason];?>]</td>
								<td>
                                    <?php echo $pay[payment_code].($pay[payment_account]?'|'.$pay[payment_account].'|'.$pay[payment_name]:'').'|'.EnumOrderRefundStatus::getOrderRefundStatus($pay['status']).($pay['comment']?'|'.$pay['comment'].$refunderrors[$pay['payment_code']][$pay['comment']]:'');?>
								</td>
							</tr>
							<?php } ?>
                            </table>
                        <?php } ?>
						<table id="status_inputbox_13" class="list status_inputbox<?php if($order_status_id!=13) echo ' hide';?>">
						<tr>
								<td>支付方式</td>
								<td>退款金额</td>
								<td>退款方式</td>
							</tr>
							<?php foreach ($refunds as $pay) { ?>
							  <tr>
								<td><?php echo $pay[payment_code1];?>：<?php echo $pay[value1];?>¥
								</td>
								<td><?php echo $pay[value];?>[<?php echo $pay[reason];?>]</td>
								<td>
                                    <?php echo $pay[payment_code].($pay[payment_account]?'|'.$pay[payment_account].'|'.$pay[payment_name]:'').'|'.EnumOrderRefundStatus::getOrderRefundStatus($pay['status']).($pay['comment']?'|'.$pay['comment'].$refunderrors[$pay['payment_code']][$pay['comment']]:'');?>
								</td>
							</tr>
							<?php } ?>
							 <?php
							 if($payments){
							  $payii=0; foreach ($payments as $pay) { ?>
							<tr>
								<td><input type="checkbox" name='payment_refund[<?php echo $payii;?>][checked]' value='1' ><?php echo $pay[payment_code];?>：<?php echo $pay[value];?>¥
								    <input type="hidden" name="payment_refund[<?php echo $payii;?>][order_payment_id]" value="<?php echo $pay[order_payment_id];?>" />
								</td>
								<td><input type="text" name="payment_refund[<?php echo $payii;?>][value]" style="width: 50px;"
									value="<?php echo $pay[value];?>" /></td>
								<td>
								 <?php if($pay[payment_code]!='balance'){?>
								  <input type="radio" name="payment_refund[<?php echo $payii;?>][payment_code]" value="balance" checked="checked" />退到储值<br/>
								 <?php }?>
								 <?php if($pay[payment_code]=='platform'||$pay[payment_code]=='alipay'){?>
									<input type="radio" name="payment_refund[<?php echo $payii;?>][payment_code]" value="alipay"/>退到支付宝:<input
									type="text" name="payment_refund[<?php echo $payii;?>][payment_account]" value="" />真实姓名:<input
									type="text" name="payment_refund[<?php echo $payii;?>][payment_name]" style="width: 100px;" value="" /><br/>
								 <?php }
                                 if($pay[payment_code]!='platform'){ ?>
								  <input type="radio" name="payment_refund[<?php echo $payii;?>][payment_code]" value="returnback" checked="checked" />原路返回
								  <input type="hidden" name="payment_refund[<?php echo $payii;?>][payment_code1]" value="<?php echo $pay[payment_code];?>" />
								<?php }?>
								</td></tr>
							<?php $payii++; } ?>
							<tr><td>退款原因：</td><td colspan="2" ><input type="text" name="payment_refund_reason" value="" placeholder="请填写退款原因" /></td></tr>
							<?php }?>
					      
						</table>  </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_notify; ?></td>
                        <td><input type="checkbox" name="notify" value="1"/></td>
                    </tr>
					<tr>
                        <td>是否退优惠卷</td>
                        <td><input type="checkbox" name="return"/></td>
                    </tr>
                    <tr>
                        <td>订单处理备注</td>
                        <td><textarea name="comment" cols="40" rows="8" style="width: 99%"></textarea>

                            <div style="margin-top: 10px; text-align: left;"><a onclick="history();" id="button-history"
                                                                                class="btn"><span><?php echo $button_add_history; ?></span></a>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php } ?>
            </form>  <?php } ?>