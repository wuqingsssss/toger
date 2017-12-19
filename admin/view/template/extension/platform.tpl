<?php if ($error) { ?>
  <div class="alert alert-error"><?php echo $error; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2> <?php echo $heading_title; ?></h2>
       <div class="buttons">
		<a onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary"><span><?php echo $button_insert; ?></span></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $default; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td class="left" width="30px" style="text-align: left;" >序号</td>
            <td class="left">平台名称</td>
            <td class="center">代号</td>
            <td class="left">key值</td>
            <td class="left">point_code值</td>
			<td class="center">状态</td>
            <td class="center"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($platform_list) { ?>
          <?php foreach ($platform_list as $k => $li) { ?>
	          <tr>
	             <td class="center"> 
				<?php echo $k+1?>
				</td>
				<td class="left"> <?php echo $li['name']; ?></td>
				<td class="center"><?php echo $li['code'] ?></td>
				<td class="left"><?php echo ($li['key'] == '' ) ? '暂无' : $li['key'] ;?></td>
				<td class="left"><?php echo $li['point_code']; ?></td>
				<td class="center"><?php echo ($li['status'] == 0) ? '<span style="color:red">停用</span>': '启用'?></td>
				<td class="center">
				<?php foreach($li['action'] as $act){?>
	              [ <a href="<?php echo $act['href']; ?>"><?php echo $act['text']?></a> ]
				<?php } ?>
				</td>
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
    </div>
  </div>
