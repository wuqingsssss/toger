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