  <style>
    .text{
        margin: 0 auto;
        width:100%;
    }
    ul{margin: 0;padding: 0;}
    .title{text-align: center;}
    .text input{width:100%;border: 1px #00a99c solid;height:30px;line-height: 30px;}
     li{list-style: none;position: relative;margin:0px;}
     .btn{cursor: pointer; text-align: center; background-color:#00a99c;color:#fff;width:80px;height:24px;border-radius: 3px; padding:5px;float:right;}
     li a{color:#666;font-size:13px;}
     .title2{background-color:#efefef;color:#00a99c;padding: 0;margin: 0 ;height:25px;}
     
     .logdata ul li{display:block;white-space:nowrap; overflow:hidden;height:25px;line-height:35px;cursor: pointer;padding:3px;margin: 2px;}
     .logdata ul li.active{word-break: break-all;white-space: pre-wrap;width: 100%;height:100%;background-color:#EFEFEF;border: solid red 1px; }
     
    </style>
    <div>
     <ul>  
     <li class='text'>
      <input type="text" id="prefix" name="prefix" value="" placeholder="请输入版本号" class="requiredkeyword" />
        </li>
      <li>
           版本数：<input type="text" name="maxkeys" value="10" size='10' />
      <a class="btn btn-primary" onclick="checkoss('app_web/clear');" ><?php echo $button_clear;?></a>
      <a class="btn btn-primary" onclick="checkoss('app_web/build');" ><?php echo $button_build;?></a>
      <a class="btn btn-primary" onclick="checkoss('oss/find');" ><?php echo $button_done;?></a>
     
      </li>
      <li>
    </li>
      <li>
      <span class="error"></span>
     </li>
     </ul>
      <ul style="margin-top:30px;border: 1px #e5e5e5 solid;border-radius: 3px;position: relative;">
     <li class="title2">
         <span id="msg_keyword">查询结果</span>  <a class="btn btn-primary" onclick="$('#ossdata li').toggleClass('active');" >展开/收起</a>
     </li>
     <li>
       <div id="ossdata" class="logdata">
       </div>
     </li>
     </ul> 
</div>
<script type="text/javascript" src="http://www.qingniancaijun.com.cn/assets/js/jquery/jquery-1.7.2.min.js"></script>
<script>
$(function(){
	checkoss('oss/find');
});

function checkoss(action,marker,frontmarker)
{   var prefix = "update/data/app/web/"+$('#prefix').val();
    var bucket='web-pic'; 
    marker=marker||'';
    frontmarker=frontmarker||'';

if(!bucket )	$("#msg_keyword").html('<?php echo $error_no_loggers;?>');

	var url='index.php?route=tool/'+action;
	$("#ossdata").html('');
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
             'prefix':prefix, 
             'bucket':bucket, 
             'marker':marker,
             'frontmarker':frontmarker,
             'maxkeys':$('input[name="maxkeys"]').val(), 
             },
			 function(e) {           	 
		     console.log(e);
		 if(e.status==1)
			 {		 
		     	$("#msg_keyword").html(e.message);
		     	if(e.data)$("#msg_keyword").append('['+Object.getOwnPropertyNames(e.data).length+']');
		     	
		     	var appendstr='<ul>';

		     	if(e.frontmarker!='false')
	     			appendstr+='<li><span onclick="checkoss(\''+action+'\',\''+e.frontmarker+'\')">上一页...</span></li>';	     				
	     				
		     		 for(i in e.data)
		     		{
		     			appendstr+='<li>'+'['+i+']'+obj2string(e.data[i])+'</li>';
		     		}
		     		
		     		 if(e.nextmarker)
		     			appendstr+='<li><span onclick="checkoss(\''+action+'\',\''+e.nextmarker+'\',\''+e.marker+'\')">下一页...</span></li>';
		     		 
		     		appendstr+='</ul>';
		     	$("#ossdata").append(appendstr);
				$("#msg_keyword").css('color','green');
		     	$("#ossdata li").bind('dblclick',function(){
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