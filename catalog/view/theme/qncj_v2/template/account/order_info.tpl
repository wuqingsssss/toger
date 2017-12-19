<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if($payment!=''){?>
  <div class="right" style="overflow:hidden; margin-bottom:20px; ">
  		<a href="<?php echo $this->url->link('checkout/success/payment','order_id='.$order_id);?>" class="button fr"><span>付款</span></a>
  </div>
  <?php } ?>

  <table class="list">
  <thead>
      <tr>
        <td class="left" colspan="2"><?php echo $text_order_status;?></td>
      </tr>
    </thead>
     <tbody>
     	<tr>
            <td colspan="2">
                <div class="order_status">
                    <div class="status status-<?php echo $order_status_id ?>">
                        <ul>
                            <li class="i1"><?php echo $text_submit_order;?></li>
                            <li class="i2"><?php echo $text_payment_success; ?></li>

                            <?php if($order_status_id!='13' && $order_status_id!='11'){ ?>
                                <li class="i4"><?php echo $text_wait_receipt;?></li>
                                <li class="i5"><?php echo $text_order_finish;?></li>
                            <?php }else{ ?>
                                <li class="i4"><?php echo $text_refunding;?></li>
                                <li class="i5"><?php echo $text_refunded;?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </td>
     	</tr>
     </tbody>
  </table>
  <table class="list">
    <thead>
      <tr>
        <td class="left" colspan="2"><?php echo $text_order_detail; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if($p_order_id!='') { ?>
      <tr>
        <td class="left" style="width: 50%;">
          <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
          <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
          <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?>
        </td>
        <td class="left">
          <?php if ($shipping_method) { ?>
          <b>取菜时间</b> <?php echo $pdate; ?><br />
          <b>取菜地点</b> <?php echo $shipping_method; ?>
          <?php } ?></td>
      </tr>
      <?php }else {?>
      <tr>
        <td class="left" style="width: 50%;">
          <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
          <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
           <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?>
          </td>
        <td class="left">
          <?php if ($shipping_point_id>0) { ?>
          <b>取菜时间：</b> <?php echo $pdate; ?> <br />
          <b>取菜码：</b> <?php echo $pickup_code; ?> <br />
          <b>电话:</b> <?php echo $pointinfo['telephone']; ?>  <br />
          <b>取菜地址:</b> <?php echo $pointinfo['name'].$pointinfo['address']; ?> 
          <?php }
else
{ ?>
          <b>配送时间:</b><?php echo $shipping_time; ?><br />  
          <b>收货人:</b> <?php echo $shipping_firstname; ?>  <br />
          <b>电话:</b> <?php echo $shipping_mobile; ?>  <br />
          <b>收货地址:</b> <?php echo $shipping_address_1.$shipping_address_2; ?>

<?php }?>
          
          </td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  <?php if($p_order_id!='') {?>
   <?php foreach($groups as $key => $products2) { ?>
   <?php { ?>
	<div class="checkout-heading">
	<label>取菜时间：</label><b><?php echo $key; ?></b>
	<label>取菜码：</label><b><?php echo $pickup_code; ?></b>
	</div>
	<?php } ?>
  	<table class="list">
      <thead>
        <tr>
          <td class="tc" style="width:45%"><?php echo $column_name; ?></td>
          <td class="tc" style="width:15%"><?php echo $column_model; ?></td>
          <td class="tc" style="width:15%"><?php echo $column_price; ?></td>
          <td class="tc" style="width:10%"><?php echo $column_quantity; ?></td>
          <td class="tc" style="width:15%"><?php echo $column_total; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products2 as $product) { ?>
        <tr>
          <td class="tc">
           <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>" target="_blank"><?php echo $product['name']; ?></a>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td class="tc"><?php echo $product['model']; ?>
          </td>
          <td class="tc"><?php echo $product['price']; ?></td>
          <td class="tc"><?php echo $product['quantity']; ?></td>

          <td class="tc"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
     <?php } ?>

  <?php }else {?>
  <?php foreach($groups as $key => $products2) {?>
   <?php if($key) {?>
	<div class="checkout-heading">
	<label>取菜时间：</label><b><?php echo $key; ?></b>
	<label>取菜码：</label><b><?php echo $pickup_code; ?></b>
	</div>
	<?php } ?>
  	<table class="list">
      <thead>
        <tr>
          <td class="tc" style="width:45%"><?php echo $column_name; ?></td>
          <td class="tc" style="width:15%"><?php echo $column_model; ?></td>
          <td class="tc" style="width:15%"><?php echo $column_price; ?></td>
          <td class="tc" style="width:10%"><?php echo $column_quantity; ?></td>
          <td class="tc" style="width:15%"><?php echo $column_total; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products2 as $product) { ?>
        <tr>
          <td class="tc">
          	<a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>" target="_blank"><?php echo $product['name']; ?></a>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td class="tc"><?php echo $product['model']; ?>
          </td>
          <td class="tc"><?php echo $product['price']; ?></td>
          <td class="tc"><?php echo $product['quantity']; ?></td>

          <td class="tc"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
      </tbody>

    </table>
    <?php }?>
   <?php } ?>

    <br/>
    <table class="list">
      <thead>
        <tr>
          <td class="tr" colspan="4">总订单：</td>
          <td class="tc"><?php echo $column_total; ?></td>
        </tr>
      </thead>

         <tfoot>
        <?php foreach ($totals as $total) { ?>
	        <tr>
	          <td colspan="4" class="tr"><b><?php echo $total['title']; ?>：</b></td>
	          <td class="tc"><?php echo $total['text']; ?></td>
	        </tr>
        <?php } ?>
      </tfoot>

    </table>
  <?php if ($comment) { ?>
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $text_comment; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="left"><?php echo $comment; ?></td>
      </tr>
    </tbody>
  </table>
  <?php } ?>
  <?php if ($histories) { ?>
  <h2><?php echo $text_history; ?></h2>
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $column_date_added; ?></td>
        <td class="left"><?php echo $column_status; ?></td>
        <td class="left"><?php echo $column_comment; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($histories as $history) { ?>
      <tr>
        <td class="left"><?php echo $history['date_added']; ?></td>
        <td class="left"><?php echo $history['status']; ?></td>
        <td class="left"><?php echo $history['comment']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?> 