<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <div class="buttons">
      <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary"><span><?php echo $button_insert; ?></span></a>
      <a onclick="$('form').submit();" class="btn btn-danger"><span><?php echo $button_delete; ?></span></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'id.title') { ?>
                <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                <?php } ?></td>
                <td><?php echo $column_code; ?></td>
               <td><?php echo $column_status; ?></td>
              <td class="right"><?php if ($sort == 'i.sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($notes) { ?>
            <?php foreach ($notes as $note) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($note['selected']) { ?>
               		<?php if ($note['used']) { ?>
                 	<img src="view/image/ico/ico_no.jpeg" width="15px"  title="<?php echo $text_used;?>" /> 
        			<?php } else { ?>
        			 <input type="checkbox" name="selected[]" value="<?php echo $note['note_id']; ?>" checked="checke"/>
        			<?php } ?>
                <?php } else { ?>
                <?php if ($note['used']) { ?>
              	  <img src="view/image/ico/ico_no.jpeg" width="15px" title="<?php echo $text_used;?>" /> 
		     	<?php } else { ?>
			      	<input type="checkbox" name="selected[]" value="<?php echo $note['note_id']; ?>"  />
			    <?php } ?>
                <?php } ?></td>
              <td class="left"><?php echo $note['title']; ?></td>
              <td class="left"><?php echo $note['code']; ?></td>
              <td><?php echo $note['status']; ?></td>
              <td class="right"><?php echo $note['sort_order']; ?></td>
              <td class="right"><?php foreach ($note['action'] as $class => $action) { ?>
                [ <a href="<?php echo $action['href']; ?>" class="<?php echo $class; ?>" ><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>