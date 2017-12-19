<?php if(!$ajax){?>
<div class="m-season module with-bottom" id="m-season<?php echo $setting['pid'];?>">
<?php }?>
<?php if($promotion&&$products){?>
    <div class="text-center fz-18"><a href="<?php echo $promotion['href'];?>" title="<?php echo $promotion['title'];?>"><?php echo $promotion['share_title'];?></a></div>
    <div class="text-center fz-12"><a href="<?php echo $promotion['href'];?>" title="<?php echo $promotion['title'];?>"><?php echo $promotion['share_desc'];?></a></div>
    <div class="horizontal-scroll">
        <ul class="wrapper">
        <?php foreach($products as $product){?>
            <li class="item">
                <a href="<?php echo $product['href'];?>" class="img-wrapper"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"/></a>
                <div class="title text-overflow fz-12"><?php echo $product['name'];?></div>
                <?php if ($product['price']) { ?>
                <div class="prices">
                 <?php if (!empty($product['promotion']['promotion_code'])) { ?>
                    <div class="price fz-18 col-red"><?php echo $product['promotion']['promotion_price']; ?>&nbsp;&nbsp;</div>
                    <div class="price fz-12 text-delete"><?php echo $product['price'];?></div>
                     <?php } else { ?>
                      <div class="price fz-16 col-red"><?php echo $product['price'];?></div>
                      <?php } ?>
                </div>
                 <?php } ?>
                
            </li>
            <?php }?>
        </ul>
    </div><?php }?>
    <?php if(!$ajax){?>
</div>
<script type="text/javascript">
			$(function(){
				
				$.ajax({
		    		url: 'index.php?route=module/promotion_featured'
		    		<?php foreach($setting as $key=>$item){ ?>		
		    		+'&<?php echo "$key=$item";?>'<?php }?>
		    		,
		    		dataType: 'json',
		    		success: function(data) {
		    			//console.log(data);	    
                        $("#m-season<?php echo $setting['pid'];?>").html(data.output);

		    		}
		    	});		
				
			
			});
</script> 
<?php }?>