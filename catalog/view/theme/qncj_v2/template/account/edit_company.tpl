<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if (isset($error_warning) && $error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if (isset($success) && $success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="edit">
    <div class="content">
      <table class="form">
      	 <tr>
        	<td colspan="2"><strong>帐户信息</strong></td>
        </tr>
      	<tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" readonly="true" value="<?php echo $email; ?>" class="span4" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
        	<td colspan="2"><strong>联系人信息</strong></td>
        </tr>
        <tr>
          <td><span class="required">*</span> 联系人姓名</td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" class="span4" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> 所在部门</td>
          <td><input type="text" name="department" value="<?php echo $department; ?>" maxlength="100" class="span4" />
            <?php if ($error_department) { ?>
            <span class="error"><?php echo $error_department; ?></span>
            <?php } ?></td>
        </tr>
       <tr>
          <td><span class="required">*</span> 固定电话</td>
          <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" maxlength="50" class="span4" />
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
       <tr>
          <td><span class="required">*</span> 手机</td>
          <td><input type="text" name="mobile" value="<?php echo $mobile; ?>" maxlength="50" class="span4" />
            <?php if ($error_mobile) { ?>
            <span class="error"><?php echo $error_mobile; ?></span>
            <?php } ?></td>
        </tr>
        <tr style="display:none;">
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" name="fax" value="<?php echo $fax; ?>" class="span4" /></td>
        </tr>
        <tr>
        	<td colspan="2"><strong>公司信息</strong></td>
        </tr>
        <tr>
          <td><span class="required">*</span> 公司名称</td>
          <td><input type="text" name="company" value="<?php echo $company; ?>" class="span4" maxlength="200" />
            <?php if ($error_company) { ?>
            <span class="error"><?php echo $error_company; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> 公司地址</td>
          <td><input type="text" name="company_address" value="<?php echo $company_address; ?>" maxlength="200" class="span4" />
            <?php if ($error_company_address) { ?>
            <span class="error"><?php echo $error_company_address; ?></span>
            <?php } ?></td>
        </tr>
       <tr>
          <td><span class="required">*</span> 公司网址</td>
          <td><input type="text" name="website" value="<?php echo $website; ?>" maxlength="100"  class="span4"/>
            <?php if ($error_website) { ?>
            <span class="error"><?php echo $error_website; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
        	 <td>&nbsp;</td>
        	 <td>
        	 	<div class="left"><a onclick="$('#edit').submit();" class="button"><span><?php echo $button_continue; ?></span></a></div>
        	 </td>
        </tr>
      </table>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>