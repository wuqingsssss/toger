<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/template/promotion/rush/xianshi.css" rel="stylesheet"/>
<style>
       html {
       font-size:62.5%;
       } 
        .col-gray{
    		color: #A0A0A0;
    	}
    	.not-available {
			background: url(<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/template/promotion/rush/cart-2.png) center center no-repeat;
			width: 40px;
			height: 40px;
			border-radius: 50%;
			overflow: hidden;
			background-size: 100% auto;
		}
		i.icon-word {
			display: inline-block;
			vertical-align: middle;
			line-height: 18px;
			width: 0;
			margin-right: 0px;
		}
		i.time {
			background: url(<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/template/promotion/rush/time.png) no-repeat;
			width: 1.5rem;
			height: 1.2rem;
			background-size: 80%;
		}
		.icon-hour{
			background: #A0A0A0;
			color: #FFFFFF;
			padding: 2px 5px;
			font-size: 1rem;
			display: inline-block;
			vertical-align: middle;
			margin-right: 0px 3px;
		}
		.xianshi-colon{	
			padding: 2px 5px;
			font-size: 1rem;
			display: inline-block;
			vertical-align: middle;
		}
		.bag-col{
			background: #e44c0b;
		}
		.icon_end{
			padding: 0px 5px;
			font-size: 1rem;
			display: inline-block;
			vertical-align: middle;
		}
		.sell_out{
			width: 6rem;
			height: 6rem;
			z-index: 900;
			position: absolute;
			left: 50%;
			margin-left: -4rem;
			top: 50%;
			margin-top: -4rem;
			border-radius: 50%;
background-color: #2C2929;
filter: alpha(opacity=60);
opacity: 0.6;
padding: 1rem 1rem;
line-height: 6rem;
		}
		.sell_info{font-size: 2rem;
		color: white;
		filter: alpha(opacity=100);
opacity: 1;
		}
		
		.pic1{width:100px;height:100px;float: left;position: relative;}
    </style>

<!-- 公共头开始 -->
<?php echo $content_top; ?>
<!-- 公共头结束 -->
<div class="module" id="m-foods">
  <?php
		 foreach( $progroups as $key=> $group){ ?>
    <ul class="foods bg-body">
		 <?php echo $key;?>
		<?php foreach( $group as $key=> $product){ 
		$rush=$product['promotions']['PROMOTION_RUSH'];
		?>
        <li data-remain="250" class="<?php if($rush['status']=='1') echo 'col-red';else echo 'col-gray';?>">
          <div class="pic1"><a href="<?php echo $product['href']; ?>" class="img-wrapper pull-left">
          <span class="sell_out<?php if($rush['status']=='1') echo ' hidden';?>"><font class="sell_info"><?php echo $rush['status_name']; ?></font></span>
            <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['sub_title']; ?>"/></a>
            </div>
            <div class="content">
                <div class="title fz-16 text-overflow<?php if($rush['status']=='1') echo ' col-black';?>"><?php echo $product['name']; ?></div>
                <?php if($rush['status']=='-1'){?>
                <div class="timer1 activity icon_end">
                    <i class="icon-word time"></i>
                    <span class="icon-hour bag-col"><?php echo date('m-d H:i:s',strtotime($rush['date_start']));?>开始</span> 
                </div>
                <?php }else{ ?>
                <div class="<?php if($rush['status']!='3') echo 'timer';?> activity" data-start-time="<?php echo strtotime($rush['date_start'])*1000;?>" data-end-time="<?php echo strtotime($rush['date_end'])*1000;?>">
                    <i class="icon-word time"></i>
                    <span class="icon-hour bag-col">00</span>
                    <span class="xianshi-colon">:</span>
                    <span class="icon-hour bag-col">00</span>
                    <span class="xianshi-colon">:</span>
                    <span class="icon-hour bag-col">00</span>
                </div>
                <?php }?>
                <div class="prices">
                    <span class="price fz-18"><?php echo $this->currency->format($rush['price']);?></span>
                    <span class="price col-gray fz-12 text-delete"><?php echo $product['price'];?></span>
                </div>
                <?php if(($rush['status']=='-1'&&$rush['quantity']||$rush['status']=='3')){?><div class="quantity col-red  fz-12 col-gray">共<?php echo $rush['quantity'];?>份</div><?php }?>
                <div class="add-cart"><span data-id="<?php echo $product['product_id']; ?>" data-code="<?php echo $product[promotion][promotion_code];?>" class="btn-img<?php if($rush['status']=='1') echo ' round btn-add-cart in-list';else echo ' not-available';?>"></span></div>
            </div>
        </li> 
        <?php }?>
        </ul>
        <?php }?>
</div><script type="text/javascript">

$(function () {
    var now = +new Date(),
        $mStatus = $('#m-foods').find('li .content .timer'),
        $mFoods = $('#m-foods');

       $mStatus.each(function(n){
    	         console.log('n' , n);
    	         //$_this=$(this);
    	        $(this).countDown('<i class="icon-word time"></i>\
                        <span class="icon-hour bag-col">{hh}</span>\
                        <span class="xianshi-colon">:</span>\
                        <span class="icon-hour bag-col">{mm}</span>\
                        <span class="xianshi-colon">:</span>\
                        <span class="icon-hour bag-col">{ss}</span>',countDown_callback);
        });
       
       function  countDown_callback($_this,e){
    	  // $_this=$(_this);
    	   $_parent=$_this.parent().parent();
      	 
	          if(e==1){//未开始-->已开始
	        	    $_parent.removeClass('col-gray').addClass('col-red').find('.sell_out').hide().find('.sell_info').html('');
	        		$_parent.find('.btn-img').removeClass('not-available').addClass('btn-add-cart in-list').bind();	
	        		/* 初始化动作 */
	        		 $('.btn-add-cart').bind('click', function () {
	        	            var $this = $(this);
	        	            _.addCart($this.data('id'),$this.data('code'),1, function () {
	        	                $this.tipsBox('<span class="col-red fz-14 bold">+1</span>');
	        	            });
	        	        });
	        		
	        		$_this.show();
	        		$_parent.find('.timer1').remove();
	        		$_parent.find('.quantity').remove();
	        		$_parent.find('.title').addClass('col-black');
	        		$_this.countDown('<i class="icon-word time"></i>\
	                        <span class="icon-hour bag-col">{hh}</span>\
	                        <span class="xianshi-colon">:</span>\
	                        <span class="icon-hour bag-col">{mm}</span>\
	                        <span class="xianshi-colon">:</span>\
	                        <span class="icon-hour bag-col">{ss}</span>',countDown_callback);
	        	}
	          else
	        	{//已结束
	        	    console.log('$_this,e',$_this,e);
	        		$_parent.addClass('col-gray').removeClass('col-red').find('.sell_out').show().find('.sell_info').html('已结束');
	        		$_parent.find('.btn-img').addClass('not-available').removeClass('btn-add-cart in-list').unbind();	
	        		$_parent.find('.title').removeClass('col-black');
	        		$_this.find('span').removeClass('bag-col');
	        		}
	    	     console.log('n,e' , e);
    	   
       }
       
        
        //$mFoods.find('.add-cart').hide();

        //$mFoods.find('[data-remain="0"]').find('.add-cart').hide();

});

</script>
<?php echo $this->getChild('module/sharebtn',array('btn_hide'=>'#none'));?>
<?php echo $this->getChild('module/navbar');?>
<script src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/common.js"></script>
<?php echo $footer35; ?>