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
                        <?php if($q_value['question_type']=='array'){
    $tmp_array=json_decode($q_value['question_value'],true);?><select name="question_value_<?php echo $q_value['id']?>">
                            <?php foreach($tmp_array as $a_key=>$a_value) { ?>
                            <option value="<?php echo $a_key?>"><?php echo $a_value;?></option>
                            <?php }?>
                        </select>
                        <?php }?>
               
                        <?php if($q_value['question_type']=='array_radio'){
    $tmp_array=json_decode($q_value['question_value'],true);?>
                        <?php foreach($tmp_array as $a_key=>$a_value) { ?>
                        <label> &nbsp&nbsp<input type="radio" name="question_value_<?php echo $q_value['id']?>" value="<?php echo $a_key?>"/><?php echo  $a_value; ?></label>
                        <?php }?>
                        <?php }?>
             
                        <?php if($q_value['question_type']=='input'){ ?>
                        <input type="text"  placeholder="年-月" id="birday"  name="question_value_<?php echo $q_value['id']?>" />
                        <?php }?>

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