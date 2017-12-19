<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
<div class="buttons noprint" style="text-align:center;">
	<button onclick="window.print();" class="btn">打印发货单</button>
</div>
<?php 
$count=COUNT($orders);
foreach ($orders as $index => $order) { ?>
<div style="width:649px; <?php if($index < $count -1)  {?>page-break-after: always; <?php } ?>margin:0 auto;border:1px #ddd solid;padding:5px;margin-bottom:5px;">
  <h1><?php echo $order['store_name']; ?> <?php echo $text_invoice; ?> - #<?php echo $order['order_id']; ?></h1>
  <table class="store">
    <tr>
      <td><?php echo $order['store_name']; ?><br />
        <?php echo $order['store_address']; ?><br />
        <?php echo $text_telephone; ?> <?php echo $order['store_telephone']; ?><br />
        <?php if ($order['store_fax']) { ?>
        <?php echo $text_fax; ?> <?php echo $order['store_fax']; ?><br />
        <?php } ?>
        <?php echo $order['store_email']; ?><br />
        <?php echo $order['store_url']; ?></td>
      <td align="right" valign="top"><table>
      	 <tr>
            <td><b><?php echo $text_order_id; ?></b></td>
            <td>
            <?php if($order['p_order_id'])  {  ?>
            	 #<?php echo $order['order_id']; ?> -(子订单)
            <?php } else {?>
           		  #<?php echo $order['order_id']; ?>
            <?php } ?>
           </td>
          </tr>
          <tr>
            <td><b>取菜时间</b></td>
            <td><?php echo $order['pdate']; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $text_date_added; ?></b></td>
            <td><?php echo $order['date_added']; ?></td>
          </tr>
          <?php if ($order['invoice_no']) { ?>
          <tr>
            <td><b><?php echo $text_invoice_no; ?></b></td>
            <td><?php echo $order['invoice_no']; ?></td>
          </tr>
          <?php if ($order['invoice_date']) { ?>
          <tr>
            <td><b><?php echo $text_invoice_date; ?></b></td>
            <td><?php echo $order['invoice_date']; ?></td>
          </tr>
          <?php } ?>
          <?php } ?>
        </table>
       </td>
    </tr>
  </table>
   
   
   <table class="product">
    <tr class="heading">
      <td><b><?php echo $column_product; ?></b></td>
      <td><b><?php echo $column_model; ?></b></td>
      <td align="right"><b><?php echo $column_quantity; ?></b></td>
      <td align="right"><b><?php echo $column_price; ?></b></td>
      <td align="right"><b><?php echo $column_total; ?></b></td>
    </tr>
    <?php foreach ($order['product'] as $product) { ?>
    <tr>
      <td><?php echo $product['name']; ?>
        <?php foreach ($product['option'] as $option) { ?>
        <br />
        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
        <?php } ?></td>
      <td><?php echo $product['model']; ?></td>
      <td align="right"><?php echo $product['quantity']; ?></td>
      <td align="right"><?php echo $product['price']; ?></td>
      <td align="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
     <?php if(!$order['p_order_id'])  {  ?>
    <?php foreach ($order['total'] as $total) { ?>
	    <tr>
	      <td align="right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
	      <td align="right"><?php echo $total['text']; ?></td>
	    </tr>
    <?php } ?>
   <?php } ?>
  </table>
  <?php if($order['p_order_id'])  {  ?>
  	 <table class="product">
    		<tr class="heading">
            
               <td colspan="4" class="right">
               <?php if($order['p_order_id'])  {  ?>
              	 <b>总单号 : #<?php echo $order['p_order_id']; ?></b>
               <?php }  ?>
               </td>
              <td class="right">订单总计</td>
            </tr>
          
           <?php foreach ($order['sub_orders'] as $sub_order) { ?>
          <tbody >
            <tr>
              <td colspan="4" class="right"></td>
              <td class="right">#<?php echo $sub_order['order_id']; ?> - (子订单)</td>
            </tr>
          </tbody>
          <?php }?>
        <?php foreach ($order['total'] as $total) { ?>
         <tbody id="totals">
            <tr>
              <td colspan="4" class="right"><?php echo $total['title']; ?>:</td>
              <td class="right"><?php echo $total['text']; ?></td>
            </tr>
          </tbody>
          <?php } ?>
     </table>
 <?php } ?>
  <?php if ($order['comment']) { ?>
  <table class="comment">
    <tr class="heading">
      <td><b><?php echo $column_comment; ?></b></td>
    </tr>
    <tr>
      <td><?php echo $order['comment']; ?></td>
    </tr>
  </table>
  <?php } ?>

</div>

<?php } ?>
<div class="buttons noprint" style="text-align:center;">
	<button onclick="window.print();" class="btn">打印发货单</button>
</div>
</body>
</html>