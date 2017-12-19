<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h2 ><?php echo $heading_title; ?></h2>
  </div>
  <div class="content">
    <form action="<?php echo $action_order; ?>" method="post" enctype="multipart/form-data" id="form-order">
      <table class="form">
      	<tr>
      		<td colspan="2"><h3>订单数据修改工具</h3></td>
      	</tr>
        <tr>
          <td class="span1"><span class="required">*</span> 原始订单ID<span class="help">默认要被更改的订单ID</span></td>
          <td>
         	<input type="text" value="<?php echo $origin_order_id; ?>" name="origin_order_id" />
         	
         	<?php if (isset($error_origin_order_id)) { ?>
                <span class="error"><?php echo $error_origin_order_id; ?></span>
              <?php } ?>
         </td>
        </tr>
        <tr>
          <td class="span1"><span class="required">*</span> 新订单ID <span class="help">订单ID生成规则：<br /> 例如：14年03月10日下单，则订单前六位为140310。<br /> 如果订单ID重复时不会更改</span></td>
          <td>
         	<input type="text" value="<?php echo $new_order_id; ?>" name="new_order_id" />
         	<?php if (isset($error_new_order_id)) { ?>
                <span class="error"><?php echo $error_new_order_id; ?></span>
              <?php } ?>
         </td>
        </tr>
        
        <tr>
          <td><span class="required">*</span> 下单时间 <span class="help">精确到秒 </span></td>
          <td>
         	<input type="text" value="<?php echo $date_added; ?>" name="date_added" class="datetime" />
         	<?php if (isset($error_date_added)) { ?>
                <span class="error"><?php echo $error_date_added; ?></span>
              <?php } ?>
         </td>
        </tr>
        <tr>
          <td><span class="required">*</span> 最后更新时间 <span class="help">精确到秒 </span></td>
          <td>
         	<input type="text" value="<?php echo $date_modified; ?>" name="date_modified" class="datetime" />
         	<?php if (isset($error_date_modified)) { ?>
                <span class="error"><?php echo $error_date_modified; ?></span>
              <?php } ?>
         </td>
        </tr>
        <tr>
          <td><span class="required">*</span> 取单时间 <span class="help">精确到日期</span></td>
          <td>
         	<input type="text" value="<?php echo $pdate; ?>" name="pdate" class="date" />
         	<?php if (isset($error_pdate)) { ?>
                <span class="error"><?php echo $error_pdate; ?></span>
              <?php } ?>
         </td>
        </tr>
        <tr>
          <td></td>
          <td>
         <input type="submit" value="修改订单" class="btn" />
         </td>
        </tr>
      </table>
    </form>

    <form action="<?php echo $action_customer; ?>" method="post" enctype="multipart/form-data" id="form-customer">
      <table class="form">
      	<tr>
      		<td colspan="2"><h3>客户数据修改工具</h3></td>
      	</tr>
        <tr>
          <td class="span1">客户注册邮箱</td>
          <td>
         	<input type="text" value="<?php echo $customer_email; ?>" name="customer_email">
         	<?php if (isset($error_customer_email)) { ?>
                <span class="error"><?php echo $error_customer_email; ?></span>
            <?php } ?> 
         </td>
        </tr>
        <tr>
          <td>客户注册时间</td>
          <td>
         	<input type="text" value="<?php echo $customer_date_added; ?>" name="customer_date_added" class="datetime" />
         	<?php if (isset($error_customer_date_added)) { ?>
                <span class="error"><?php echo $error_customer_date_added; ?></span>
              <?php } ?>
         </td>
        </tr>
        <tr>
          <td>客户最后登录时间</td>
          <td>
         	<input type="text" value="<?php echo $customer_date_modified; ?>" name="customer_date_modified" class="datetime" />
         	<?php if (isset($error_customer_date_modified)) { ?>
                <span class="error"><?php echo $error_customer_date_modified; ?></span>
              <?php } ?>
         </td>
        </tr>
        <tr>
          <td></td>
          <td>
         <input type="submit" value="修改客户数据" class="btn" />
         </td>
        </tr>
      </table>
    </form>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/i18n/jquery-ui-i18n.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});

$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	showSecond: true,
	timeFormat: 'hh:mm:ss'
});

$('.time').timepicker({timeFormat: 'h:m'});

//--></script>