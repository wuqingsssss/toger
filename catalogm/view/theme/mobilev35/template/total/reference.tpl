    <!-- VIP码 START  -->
    <div id="reference" > 
        <div class="module-heading"> 
            <span class="order_t_title fm-18"><?php echo $module_title; ?>
                <span class="content_title"></span>
            </span>
            <div class= "o_right_arrow order_in_right icon icon-right-open-big col-green"></div>
            <!-- <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/right.png" class="order_in_right" style="margin-top: 0.3rem;"/>  -->       
        </div>   
        <div class="module-content" id="reference"> 
                <input type="text" name="reference" value="" maxlength="50" placeholder="请输入您的VIP码" class="o_notice_input"/>
                <a id="button_reference" class="button button-slim"><?php echo $button_reference;?></a>
        </div>
        
    </div>
     <!-- VIP码 END  -->
     
    <script type="text/javascript"><!--
        $('#button_reference').bind('click', function() {
            $.ajax({
                type: 'POST',
                url: 'index.php?route=total/reference/calculate',
                data: $('#reference :input'),
                dataType: 'json',
                beforeSend: function() {
                    $('.success, .warning').remove();
                    $('#button_reference').attr('disabled', true);
                    $('#button_reference').after('<span class="wait">&nbsp;<img src="catalogm/view/theme/default/image/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    $('#button_reference').attr('disabled', false);
                    $('.wait').remove();
                },
                success: function(json) {
                    if (json['error']){
                    	 _.toast(json['error'],3000);
                    }
    
                    if (json['success']) {
                    	 checkoutComfirm();
                 	           	     
                         $('#reference .module-heading').click();
                         _.toast(json['success'],2000); 
                    }
                }
            });
        });
        //--></script>