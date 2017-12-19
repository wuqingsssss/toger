<?php if($products){?>
<?php if(!$ajax){?>
<div class="module with-bottom" id="m-today">
    <div class="text-center fz-18">今日推荐</div>
    <div class="banner banner-default with-dot-border static" id="today-banner">
<?php }?>
    <div id="ctrl" class="ctrl">
 <?php $first=true;$i=0; foreach ($products as $product){$i++; ?>
<span style="width:<?php echo 96/count($products);?>%;display:block;height:4px;padding:0;float:left;boder:none;border-radius: 0;" class="<?php if($first) echo ' hov';$first=false; ?>"><?php echo $product['name']; ?></span>
 <?php } ?> 
</div>
        <ul>
        <?php  $first=true;
         foreach ($products as  $product) {  ?>
            <li<?php if(!$first) echo ' class="hidden"';$first=false; ?>>
                <div class="clearfix" style="width:100%;">
                    <a href="<?php echo $product['href']; ?>" class="img-wrapper" style="border-radius:50%;max-width:100px;"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"/></a>
                    <div class="content inline-block">
                        <div class="title fz-14"><?php echo $product['name']; ?></div>                       
                  <?php if ($product['price']) { ?>     
                 <?php if (!empty($product['promotion']['promotion_code'])) { ?>
                 <div class="price col-red"><span class="fz-19"><?php echo $product['promotion']['promotion_price']; ?></span><span class="fz-14">元/份</span></div>
                     <?php } else { ?>
                     <div class="price col-red"><span class="fz-19"><?php echo $product['price'];?></span><span class="fz-14">元/份</span></div>
                      <?php } ?>
                 <?php } ?><span data-id="<?php echo $product['product_id']; ?>" data-code="<?php echo $product[promotion][promotion_code];?>" class="btn-img btn-add-cart in-banner"></span>
                    </div>
                </div>
            </li>
             <?php } ?>          
        </ul>
    </div>
    <?php if(!$ajax){?>
</div>
	 <script type="text/javascript">
			$(function(){
				
				$.ajax({
		    		url: 'index.php?route=module/product_featured'
		    		<?php foreach($setting as $key=>$item){ ?>		
		    		+'&<?php echo "$key=$item";?>'<?php }?>
		    		,
		    		dataType: 'json',
		    		success: function(data) {
		    			//console.log(data);	    
                        $("#today-banner").html(data.output);
		    			$("#today-banner").touchSlider({
							flexible:true,
						    speed :200,
						    view:1,
							auto:false,
							delay:3000,
							paging : $("#m-today .ctrl span"),
							counter : function (e){
								$("#m-today .ctrl span").removeClass("hov").eq(e.current).addClass("hov");
									}
					});
		    			  $('#today-banner .btn-add-cart').bind('click', function () {
		    		            var $this = $(this);
		    		            _.addCart($this.data('id'),$this.data('code'),1, function () {
		    		 //           	_.addCartAnimation($this);
		    		                $this.tipsBox('<span class="col-red fz-14 bold">+1</span>');
		    		            });
		    		        });
		    			
		    			
		    		}
		    	});		
				
			
			});
</script> 
<?php }?>
<?php  } ?>