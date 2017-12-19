  <style>
    .text{
        margin: 0 auto;
        width:100%;
    }
    .hid{display: none;}
    ul{margin: 0;padding: 0;}
     .tbody li{list-style: none;position: relative;margin:0px;float: none;
  clear: both;}
   .tbody li input,.tbody li label{float:left;}

    .text input{border: 1px #00a99c solid;height:20px;line-height: 20px;float:left;}
    .text label{height:25px;line-height: 25px;float:left;}
    .text .requiredkeyword{width:50%;}
    
    .logger_text{width:300px;}
    .pagesize{width:30px;}
    .date{width:100px;}
   
     .btn{cursor: pointer; text-align: center; background-color:#00a99c;color:#fff;width:80px;height:20px;line-height: 20px;border-radius: 3px;float:left;margin-left: 5px;}
     .right{position: absolute;right:1px;top:0px;z-index: 10000;}
     
     li a{color:#666;font-size:13px;}
     .title2{background-color:#efefef;color:#00a99c;padding: 0;margin: 0 ;height:25px;}
     
     .logdata ul li{display:block;white-space:nowrap; overflow:hidden;height:25px;line-height:35px;cursor: pointer;padding:3px;margin: 2px;}
     .logdata ul li.active{word-break: break-all;white-space: pre-wrap;width: 100%;height:100%;background-color:#EFEFEF;border: solid red 1px; }
     
    </style>
    <div class="tbody"><form>
     <ul>
      <li class='text'>
      <input type="text" id="keyword" name="keyword" value="" placeholder="请输入要查询的关键词" class="requiredkeyword" />
           <label>时间</label>
       <input type="text" name="date_start" value="<?php echo date('Y-m-d');?>" class="date" />
       <input type="text"  name="date_end" value="<?php echo date('Y-m-d');?>" class="date" />
    <label>条数</label><input type="text" name="pagesize" value="10" size='2' class="pagesize" /> 
     <a class="btn btn-primary" onclick="checkweb_log();" ><?php echo $button_done;?></a>
           </li>
     <li>
    
      <label>[服务器:</label><input type="radio" name="server" checked="checked" value="WEB" /><label>网站</label>
             <input type="radio" name="server" value="API" /><label>API]</label>
 
      <label> [类型:</label>
        <input type="radio" name="source" class="server WEB" checked="checked" value="www" /><label class="server WEB">www</label>
        <input type="radio" name="source" class="server API hid" value="api" /> <label class="server API hid">api</label>
        <input type="radio" name="source" class="server WEB" value="nginx" /> <label class="server WEB">nginx</label>
        <input type="radio" name="source" class="server WEB API" value="php-fpm" /> <label class="server WEB API">phpfastcgi</label>
        <!-- input type="radio" name="source" class="server WEB API" value="unison" /> <label class="server WEB API">unison</label>
        <input type="radio" name="source" class="server WEB API" value="syslogng" /> <label class="server WEB API">syslogng</label-->
        <label>]</label>
      </li>
      <li><label>文件：</label>
      <input type="checkbox" name="logger[]" checked="checked" class="source www api" value="log_log" /><label class="source www api">log</label>
      <input type="checkbox" name="logger[]" value="log_order_log" class="source www" /><label class="source www">order_log</label>
      <input type="checkbox" name="logger[]" value="log_payment_log" class="source www" /><label class="source www">payment_log</label> 
      <input type="checkbox" name="logger[]" value="log_sql_log" class="source www" /><label class="source www">sql_log</label>
      <input type="checkbox" name="logger[]" value="log_admin_log" class="source www" /><label class="source www">admin_log</label> 
      
       <input type="checkbox" name="logger[]" class="source nginx php-fpm hid" value="access_log" /><label class="source nginx php-fpm hid">access_log</label>
       <input type="checkbox" name="logger[]" class="source nginx php-fpm hid" value="error_log" /><label class="source nginx php-fpm hid">error_log</label>
       
      <input type="checkbox" name="logger[]" value="" class="source unison syslogng hid" /><label class="source unison syslogng hid">默认</label>
      <label>||</label>
      <input type="text" class="logger_text" name="logger_text" placeholder="请输入需要查询的日志文件，多个以 ','分开" />
       </li>
      <li>
      <span class="error"></span>
     </li>
     </ul>
      <ul style="margin-top:30px;border: 1px #e5e5e5 solid;border-radius: 3px;position: relative;"><a class="btn btn-primary right" onclick="$('#logdata li').toggleClass('active');" >展开/收起</a>
     <li id="msg_keyword" class="title2">
          查询结果 
     </li>
     <li>
       <div id="logdata" class="logdata">
       </div>
     </li>
     </ul> </form>
</div>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/i18n/jquery-ui-i18n.js"></script>
<script type="text/javascript">
$(function(){
	$('.date').datepicker({
		dateFormat: 'yy-mm-dd'
	});

	$('input[name="logger_text"]').bind('copy cut paste click',function(){
		 $('input[name="logger[]"]').attr('checked',false);
		
	});
	
	$('input[name="server"]').bind('click',function(){
			$('.server').hide().attr('checked',false);
           if($(this).attr('checked')){
				$('.server.'+($(this).val())).show().eq(0).attr('checked',true).click();
             }
	});
	
	$('input[name="source"]').bind('click',function(){
		$('.source').hide().attr('checked',false);
       if($(this).attr('checked')){
			$('.source.'+($(this).val())).show().eq(0).attr('checked',true);
         }
	});

	$('input[name="logger[]"]').bind('click',function(){
		$('input[name="logger[]"]:checked').each(function(){
		$('input[name="logger_text"]').val('');
		    }); 
		
	});
});
function checkweb_log(page)
{   
	page=page||0;
	var keyword =$('#keyword').val();
    var logger_text=$('input[name="logger_text"]').val(); 
    var loggers=[];
	 $('input[name="logger[]"]:checked').each(function(){
	    	loggers.push($(this).val());
	    }); 
    var fileds=[]; 
    $('input[name="fileds"]:checked').each(function(){
    	fileds.push($(this).val());
    }); 
    
    var server= $('input[name="server"]:checked').val()||'WEB';
    var source= $('input[name="source"]:checked').val()||'www';
    var date_start=$('input[name="date_start"]').val()||"<?php echo date('Y-m-d');?>";
    var date_end=$('input[name="date_end"]').val()||"<?php echo date('Y-m-d');?>";
    
if(loggers.length==0 )	$("#msg_keyword").html('<?php echo $error_no_loggers;?>');
//if(fileds.length==0 )	$("#msg_keyword").html('<?php echo $error_no_fileds;?>');
	var url='index.php?route=tool/sys_log/find';//
	if(page==0)$("#logdata").html('');
	$("#msg_keyword").html('<img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?>');

		$.ajaxSetup({
            error: function (x, e) {
               console.log(x, e);
               $("#msg_keyword").html('<li>'+ JSON.stringify(x)+'</li>'); 
                return false;
            }
        });
		
	    $.getJSON(url,
			 {
             'keyword':keyword, 
             'server':server,
             'source':source,
             'page':page,
             'loggers':loggers.join(","),
             'logger_text':logger_text,
             'date_start':date_start, 
             'date_end':date_end, 
             //'fileds':fileds.join(","),
             //'level':$('select[name="level"]').val(), 
             'size':$('input[name="pagesize"]').val(), 
             },
			 function(e) {           	 
		     console.log(e);
		 if(e.status==1)
			 {		 
		     	$("#msg_keyword").html(e.message);
		     	var appendstr='';
		     	 for(logger in e.data)
		     	{  appendstr+='<ul id="'+e.page+'">';
		     	if(page==0)appendstr+=logger;
	     			appendstr+='<li><span>第'+e.page+'页</span></li>';	 
		     	
		     		 for(i in e.data[logger])
		     		{
		     			appendstr+='<li>'+obj2string(e.data[logger][i])+'</li>';
		     		}

		     		 if(e.nextpage)
			     			appendstr+='<li><span onclick="checkweb_log(\''+e.nextpage+'\');$(this).parent().remove();">更多...</span></li>';
			     			
		     		appendstr+='</ul>';
		     	}
		     	$("#logdata").append(appendstr);
				$("#msg_keyword").css('color','green');
		     	$("#logdata li").bind('dblclick',function(){
		     		$(this).toggleClass('active');	     		
		     	});
			 }
		 else
			 {
			 
		          $("#msg_keyword").html(e.message);
		          $("#msg_keyword").css('color','red');
			 }
           
    });    	

}

$('#keyword').keydown(function (e) {
    if (e.keyCode == 13) {
    	checkweb_log();
    }
});
		
function obj2string(o){ 
    var r=[]; 
    var keyword=$('#keyword').val();

    if(typeof o=="string"){ 
    	
    	 //console.log("string"+o);
    	 o=o.replace(/([\t])/g,"\\$1").replace(/(\!)+/g,"\\\!");//.replace(/(\\\")/g,"\"").replace(/(\r\n|\n|\r)+/g,"<br/>")
       if(keyword)
    	 o=o.replace(new RegExp("("+keyword+")","g"),"<font color='red'>$1</font>");
    	 
    	 return "\""+o+"\""; 
    } 
    if(typeof o=="object"){ 

        if(!o.sort){ 
            for(var i in o){ 
                r.push("<font color=blue>"+i+"</font>:"+obj2string(o[i])); 
            } 
            if(!!document.all&&!/^\n?function\s*toString\(\)\s*\{\n?\s*\[native code\]\n?\s*\}\n?\s*$/.test(o.toString)){ 
                r.push("toString:"+o.toString.toString()); 
            } 
            r="{"+r.join()+"}"; 
        }else{ 
            for(var i=0;i<o.length;i++){ 
                r.push(obj2string(o[i])) 
            } 
            r="["+r.join()+"]"; 
        }  
        return r; 
    } 
    return o.toString(); 
} 
</script>