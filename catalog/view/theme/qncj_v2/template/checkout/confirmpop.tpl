<style>
	 li{
	 	list-style-type:none;
	 }
	 
	 li.list{
		list-style-type:squre;
		list-style-position:inside;
		margin-top:5px;
		padding-left:10px;
	 	font-size:14px;
		color:#444444;
		}
    
    #comment_2{
        padding:20px;

    }

    #comment_2 p{
        background-color:#D2D2D2;
        font-size: 18px;
        margin: 10px 0;
        line-height: 25px; height:25px;
    }

    #comment_2 #question_comment_2{
        margin: 0 auto;

    }
    
    #comment_2 .button{
        background-color: red;
        margin:10px;
        border-color: red;
    }

    .question-item {
    	padding-left:10px;
    	padding-top:5px;
    	pading-bottom:10px;
    	font-size:12px;
        border-bottom: dashed 1px #DDD;
    }
</style>
<div id="comment_2" style=" ">
    <div class="cart-heading" style="text-align: center; width: 100%;height:30px;line-height:30px;color: #fff;"><p><?php echo $heading_title; ?></p></div>
    <div class="cart-content" id="question_comment_2">
        <ul>
        <?php foreach($question_info  as $q_key  => $q_value) { ?>
            <li class="list">
                <span><?php echo $q_value['question_title'];?>:</span>
                <div class="question-item">     
                </div>
            </li>
        <?php }?>

        </ul>
        <br /><br />
        <div align=center>
        <a id="button-question" class="button" onclick='testabc()'><span><?php echo $button_comment; ?></span></a><a id='button-question2' class='button' onclick='layer.closeAll()'><span>有便宜不占</span></a>
        </div>
    </div>
</div>