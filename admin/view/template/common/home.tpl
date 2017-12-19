<div class="box" style="padding-top:10px;">
	   <?php if ($error_install) { ?>
	  <div class="alert alert-error"><?php echo $error_install; ?><a class="close" data-dismiss="alert">×</a></div>
	  <?php } ?>
	  <?php if ($error_image) { ?>
	  <div class="alert alert-error"><?php echo $error_image; ?><a class="close" data-dismiss="alert">×</a></div>
	  <?php } ?>
	  <?php if ($error_image_cache) { ?>
	  <div class="alert alert-error"><?php echo $error_image_cache; ?><a class="close" data-dismiss="alert">×</a></div>
	  <?php } ?>
	  <?php if ($error_cache) { ?>
	  <div class="alert alert-error"><?php echo $error_cache; ?><a class="close" data-dismiss="alert">×</a></div>
	  <?php } ?>
	  <?php if ($error_download) { ?>
	  <div class="alert alert-error"><?php echo $error_download; ?><a class="close" data-dismiss="alert">×</a></div>
	  <?php } ?>
	  <?php if ($error_logs) { ?>
	  <div class="alert alert-error"><?php echo $error_logs; ?><a class="close" data-dismiss="alert">×</a></div>
	  <?php } ?>
    <div class="content">
 	<div class="row">
 	<?php if($shortcuts){
 foreach($shortcuts as $group=>$list){
?>
 	    <div class="span6">
         <div class="dashboard-heading"><?php echo ${'text_'.$group}; ?></div>
        <div class="dashboard-content">
<?php foreach( $list as $key=>$lnks){?>
          <dl>
       			<dt><?php echo ${'text_'.$key}; ?></dt>
       			<?php foreach( $lnks as $key2=>$item){?>
       			<dd><a href="<?php echo $item; ?>"><?php echo ${'text_'.$key2}; ?></a></dd>
       			 <?php }?>
        	</dl>
        	<?php }?>

        </div>
      </div>
<?php
}}?>
   </div>
   
      <div class="latest">
        <div class="dashboard-heading"><dd><?php foreach($periods as $p){ ?>
                <?php echo $p['title'].'-'.$p['name2'].'['.$p['productsNum'].']';?> 
                 <?php } ?>
                  <?php echo $text_total_customer; ?>[<?php echo $total_customer; ?>] 
                  <?php echo $text_total_customer_approval; ?>[<?php echo $total_customer_approval; ?>] 
                  <?php echo $text_total_review_approval; ?>[<?php echo $total_review_approval; ?>] </dd>
                 </div>
                  
      <div id="tabs" class="htabs">
       <?php foreach($total_sales as $key=> $total_sale){?>
        <a href="#tab-<?php echo $key;?>"><?php echo date('y-m-d',strtotime($key));?></a>
        <?php }?>
        <a href="#tab-m">本月概况</a>
        <a href="#placeholder">统计图表</a>
      </div>           
       <?php foreach($total_sales as $key=> $total_sale){?>       
        <div id="tab-<?php echo $key;?>" class="dashboard-content">
          <table class="list">
           <thead>
             <tr>
              <td>订单统计</td>
              <td><?php echo $text_total_sale; ?></td>
               <td><?php echo $text_total_order; ?></td>
               <td><?php echo $text_pre_sale; ?></td>
            </tr></thead>
            <tbody>
           <?php foreach($total_sale as $row){?>
            <tr>
                <td style="WORD-WRAP: break-word">
               <?php  echo $row['partner'];?></td>
              <td style="WORD-WRAP: break-word"><?php echo $row['total_format']; ?></td>
               <td><?php echo $row['order_num'];?></td>
              <td><?php echo $row['pre_sale_format'];?></td>
            </tr> <?php }?>
            </tbody>
            
            <!-- tr>
              <td><?php echo $text_total_affiliate; ?></td>
              <td><?php echo $total_affiliate; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_affiliate_approval; ?></td>
              <td><?php echo $total_affiliate_approval; ?></td>
            </tr-->
          </table>
        </div>
         <?php }?>
          <div id="tab-m" class="dashboard-content">
           <table class="list">
           <thead>
             <tr>
              <td>月度概况</td>
                <td><?php echo $text_total_sale; ?></td>
                <td><?php echo $text_total_order; ?></td>
                <td><?php echo $text_pre_sale; ?></td>
            </tr>
           </thead>
           <tbody><?php foreach($total_sale_year as $row){?>
            <tr>
               <td style="WORD-WRAP: break-word"><?php  echo $row['partner'];?></td>
               <td style="WORD-WRAP: break-word"><?php echo $row['total_format'];?></td>
               <td><?php echo $row['order_num'];?></td>
               <td><?php echo $row['pre_sale_format'];?></td>
            </tr> <?php }?>
          </tbody>
           </table>
         </div>
         <div id="placeholder" class="dashboard-content" style="width:90%;padding-left:100px;border:none;">
         
         </div>
      </div>
    </div>
  </div>
<script language="javascript" type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script>
<script>
$('#tabs a').tabs();

$(function () {
    var data=[],obj=[],datemin,datemax;

    <?php
    		$first=true;
    		foreach($total_sales as $key=> $total_sale){
       if($first) 
    	   echo("datemax=".((strtotime($key)+86400)*1000).";");
       $first=false; ?>
    	
    <?php foreach($total_sale as $key2=> $row){?>

     obj["<?php echo $row['partner'] ;?>"]=obj["<?php echo $row['partner'] ;?>"]||{
    	label: "<?php echo $row[partner];?>",
    	data : []
    }
    obj["<?php echo $row['partner'] ;?>"].data.push([<?php echo strtotime($key)*1000;?>,<?php echo $row['total'];?>]);
    <?php } ?>
    <?php echo ('datemin=' .(strtotime($key)*1000).';');}  ?> 

    for(i in obj)
    	data.push(obj[i]);

    //console.log(data);

        var options = {
            lines: { show: true ,line2x:0},
            points: { show: true,line2x:0 },
            xaxis: { mode: "time",timeformat:"%y-%m-%d",min:datemin,max:datemax},
            grid:{color: "#000",borderWidth:1,hoverable: true,line2x:60},
            yaxis: {
                position: "right" // or "right"
            },
            legend: {
                noColumns: 10
            }
        };

        var placeholder = $("#placeholder");
        
        $.plot(placeholder, data, options);
        
        var previousPoint = null;
             placeholder.bind("plothover", function (event, pos, item) {
            	 
           
            	 
           // $("#x").text($.plot.formatDate(item.datapoint[0].toFixed(2), "%y-%m-%d",null));
            $("#y").text(pos.y.toFixed(2));

                if (item) {
                 	//console.log(item);
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;
                        
                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                            y = item.datapoint[1].toFixed(2);
                        
                        showTooltip(item.pageX, item.pageY,
                                    item.series.label + ":" + y);
                    }
                }
                else {
                    $("#tooltip").remove();
                    previousPoint = null;            
                }
        });
        
        function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css( {
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5,
                border: '1px solid #fdd',
                padding: '2px',
                'background-color': '#fee',
                opacity: 0.80
            }).appendTo("body").fadeIn(200);
        }

    });

</script>