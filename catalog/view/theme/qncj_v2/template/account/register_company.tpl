<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?>
    <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

 
  
  
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="register">
    
    <div class="mt">
        <ul class="tab">
            <li class="line"><a href="<?php echo $this->url->link('account/register/person'); ?>">个人用户</a></li>
            <li class="curr">机构用户</li>
        </ul>
        <div class="extra">
          <span>我已经注册，现在就&nbsp;【 <a href="<?php echo $this->url->link('account/login'); ?>" class="flk13">登录</a> 】</span>
        </div>
    </div>

    <div class="content">
      <p class="title">欢迎注册成为机构用户</p>
      <br>
      <table class="form">
        <tr><td colspan="2" style="background:#eee; height:30px;">账户信息</td></tr>
       <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" maxlength="100" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
      
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="<?php echo $password; ?>" maxlength="20" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_captcha; ?></td>
          <td><input type="text" name="captcha" value="<?php echo $captcha; ?>" class="input-slim" />
   			<img id="captcha" class="captcha" onclick="refreshCaptcha($(this).attr('id'),$(this).attr('src'));" title="<?php echo $text_refresh_captcha; ?>" src="index.php?route=information/contact/captcha" alt="" />
   			<br />
            <?php if ($error_captcha) { ?>
		    <span class="error"><?php echo $error_captcha; ?></span>
		    <?php } ?></td>
        </tr>

        <tr><td clospan="2" height="20"></td></tr>
        <tr><td colspan="2" style="background:#eee;height:30px;">联系人信息</td></tr>


        <tr>
          <td><span class="required">*</span> 联系人姓名</td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" maxlength="100" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> 所在部门</td>
          <td><input type="text" name="department" value="<?php echo $department; ?>" maxlength="100" />
            <?php if ($error_department) { ?>
            <span class="error"><?php echo $error_department; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> 固定电话</td>
          <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" maxlength="32" />
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> 手  机</td>
          <td><input type="text" name="mobile" value="<?php echo $mobile; ?>" maxlength="32" />
            <?php if ($error_mobile) { ?>
            <span class="error"><?php echo $error_mobile; ?></span>
            <?php } ?></td>
        </tr>

        <tr><td clospan="2" height="20"></td></tr>
        <tr><td colspan="2" style="background:#eee;height:30px;">公司信息</td></tr>

        <tr>
          <td><span class="required">*</span> 公司名称</td>
          <td><input type="text" name="company" value="<?php echo $company; ?>" maxlength="200" />
            <?php if ($error_company) { ?>
            <span class="error"><?php echo $error_company; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> 公司地址</td>
          <td><input type="text" name="company_address" value="<?php echo $company_address; ?>" maxlength="200" />
            <?php if ($error_company_address) { ?>
            <span class="error"><?php echo $error_company_address; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> 公司网址</td>
          <td><input type="text" name="website" value="<?php echo $website; ?>" maxlength="100" />
            <?php if ($error_website) { ?>
            <span class="error"><?php echo $error_website; ?></span>
            <?php } ?></td>
        </tr>



       <?php if ($text_agree) { ?>
         <tr>
          <td>&nbsp;</td>
          <td>
        <label>
    	<?php if ($agree) { ?>
        <input type="checkbox" name="agree" value="1" checked="checked" />
        <?php } else { ?>
        <input type="checkbox" name="agree" value="1" />
        <?php } ?>
        <?php echo $text_agree; ?>
        
        <?php if ($error_agree) { ?>
            <span class="error"><?php echo $error_agree; ?></span>
       <?php } ?>
        </td>
        </tr>
        <?php } ?>
        <tr>
          <td>&nbsp;</td>
          <td>
        <div class="left">
          <a onclick="$('#register').submit();" class="button highlight">
          <span><?php echo $button_register; ?></span></a>
        </div>
          </td>
        </tr>
  
      </table>
    </div>

   <input type="hidden" name="newsletter" value="1"  />
   <input type="hidden" name="invite_code" value="<?php echo $invitecode; ?>" />
  </form>
  <?php echo $content_bottom; ?></div>

<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript"><!--
$('.fancybox').fancybox({
	width: 560,
	height: 560,
	autoDimensions: false
});
//--></script>  
<?php echo $footer; ?>