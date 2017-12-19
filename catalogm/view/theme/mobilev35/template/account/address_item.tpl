<?php if($address['location']){?>
    <div class="address" data-id="<?php echo $address['address_id'];?>">
        <div class="poi hidden">{"lng":"<?php echo $address['location']['lng'];?>","lat":"<?php echo $address['location']['lat'];?>"}</div>
        <div class="shipping_data hidden"><?php echo $address['shipping_data'];?></div>
        <div class="shipping_code hidden"><?php echo $address['shipping_code'];?></div>
        <div class="content">
             <div class="info clearfix">                     
                <span class="name pull-left"><?php echo $address['firstname'];?></span>
                <span class="mobile pull-right"><?php echo $address['mobile'];?></span>
             </div>
             <div class="detail">收货地址：<font class="address_1"><?php echo $address['address_1'];?></font><font class="address_2"><?php echo $address['address_2'];?></font></div>
         </div>  
         <div class="buttons clearfix">
                <div class="radio checked"><b></b></div>
                <a href="javascript:" class="modify-address"><span><?php echo $button_modify;?></span></a>
                <a href="javascript:" class="delete-address"><span><?php echo $button_delete;?></span></a>                 
         </div>         
    </div>    
<?php }?>

