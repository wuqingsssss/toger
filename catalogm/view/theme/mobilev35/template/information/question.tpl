<?php echo $headersimple; ?>
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/common.css" rel="stylesheet"/>
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/uc.css" rel="stylesheet"/>
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/pullToRefresh.css" rel="stylesheet"/>
<script src="<?php echo HTTP_CATALOG . $tplpath; ?>js35/iscroll.js"></script>
<script src="<?php echo HTTP_CATALOG . $tplpath; ?>js35/pullToRefresh.mini.js"></script>
<!-- 公共头开始 -->
<?php
echo $this->getChild('module/navtop', array('navtop' => array(
				'left' => "<a class='return' href='{$this->url->link('account/account')}'></a>",
				'center' => '<a class="locate fz-18" >常见问题</a>',
				'right' => ''
	)));
?>
<div id="uc_body" style="height: 100%">
	<div id="wrapper" class="uc-body col-gray">
	    <ul>
		<?php foreach ($question_list as $key => $list) { ?>
		<?php if($list){?>
		<?php foreach ($list as $qa){?>
			
				<li class="qa col-gray">
	                <div class="qa_item">
	                    <span class="fz-14 "><?php echo $qa['description'] ?></span>
						<span class="pull-right  arrow-down"></span> 
	                </div>   
	                <div class="msg fz-13 hidden">
	                   <?php echo $qa['answer'] ?>
	                </div>
				</li>		
		<?php } ?>
		<?php } ?>
		<?php }?>
		</ul>  
	</div>

	<div class="text-center uc-foot col-gray fz-13">
	
<!-- 	<span class="col-red" onclick="getMore($(this))">点击查看更多》</span> -->	
	</div>

</div>



<!--<?php echo $this->getChild('module/navbar'); ?>-->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG . $tplpath; ?>js35/question.js"></script>


