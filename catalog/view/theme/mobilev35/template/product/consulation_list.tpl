<?php foreach($lists as $result) {?>
<div class="item">
	<div class="user">
		<span class="u-name">网　　友：<?php echo $result['customer_name']; ?></span>
		<!--<span class="u-level" name="jd_44783cf563f1b"></span>-->
		<span class="u-level"><font style="color:"><?php echo $result['customer_group']; ?></font></span>
		<span class="date-ask"><?php echo $result['date_added']; ?></span>
	</div>        
	<dl class="ask">
		<dt><b></b>咨询内容：</dt>
		<dd><div class="content-ask"><?php echo $result['content']; ?></div></dd>
	</dl>
	<dl class="answer">
		<dt><b></b>京东回复：</dt>
		<dd>
		<div class="content-answer"><?php echo $result['reply']; ?></div>
		<div class="date-answer"><?php echo $result['date_modified']; ?></div>
		</dd>
	</dl>
</div>
<?php } ?>
<?php if($lists) {?>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } ?>

<script type="text/javascript">
$('#consult .pagination a').click(function(event){
		$(this).parents('.tab_content').load($(this).attr('href'));
		
		event.preventDefault();
	}
);
</script>