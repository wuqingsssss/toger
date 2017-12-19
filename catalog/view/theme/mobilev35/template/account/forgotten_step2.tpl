<?php echo $header; ?>
<div id="header" class="bar bar-header bar-positive">
    <h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content">
    <div id="login-panel" class="card">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
            <input type="hidden" name="mobile" value="<?php echo $mobile; ?>" class="span4" />
            <table class="form">
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_password; ?><br />
                        <input type="password" name="password" value="<?php echo $password; ?>" class="span4" />
                        <?php if ($error_password) { ?>
                        <br />
                        <span class="error"><?php echo $error_password; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_confirm; ?><br />
                        <input type="password" name="confirm" value="<?php echo $confirm; ?>" class="span4" />
                        <?php if ($error_confirm) { ?>
                        <br />
                        <span class="error"><?php echo $error_confirm; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><input type="submit" class="button button-block button-positive" value="<?php echo $button_save; ?>" /></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php echo $footer; ?>