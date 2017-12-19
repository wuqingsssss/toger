// JavaScript Document

/*jdTab*/
(function($)
{$.fn.antScroll=function(option,callback)
{var obj=this;
    var navH = obj.offset().top;
	var navL=obj.offset().left;
	
	//¹ö¶¯ÌõÊÂ¼þ
	$(window).scroll(function(){
		//»ñÈ¡¹ö¶¯ÌõµÄ»¬¶¯¾àÀë
		var scroH = $(this).scrollTop();
		//¹ö¶¯ÌõµÄ»¬¶¯¾àÀë´óÓÚµÈÓÚ¶¨Î»ÔªËØ¾àÀëä¯ÀÀÆ÷¶¥²¿µÄ¾àÀë£¬¾Í¹Ì¶¨£¬·´Ö®¾Í²»¹Ì¶¨
		if(scroH>=navH){
			obj.css({"position":"fixed","top":0,"width":obj.width()});//navL
		}else if(scroH<navH){
			obj.css({"position":"static"});
		}
		//console.log(scroH==navH);
	})
	
}
})(jQuery);

(function($){$.extend({
     href:function(rehref, type)
{
alert(rehref);
  window.location.href=rehref; 
  document.location.href=rehref;
}});              
})
(jQuery);

/*hoverForIE6*/
(function($){
$.fn.hoverForIE6=function(option){
var s=$.extend({
current:"hover"},option||{});
var obj=this;
$.each(this,function(){
$(this).bind("mouseover",function(){
$(this).addClass(s.current);}).bind("mouseleave  mouseout",function(){
	$(this).removeClass(s.current);
})})}
$.extend(
		 $.browser,
		 {client:function(){return{width:document.documentElement.clientWidth,height:document.documentElement.clientHeight,bodyWidth:document.body.clientWidth,bodyHeight:document.body.clientHeight};},
scroll:function(){return{width:document.documentElement.scrollWidth,height:document.documentElement.scrollHeight,bodyWidth:document.body.scrollWidth,bodyHeight:document.body.scrollHeight,left:document.documentElement.scrollLeft,top:document.documentElement.scrollTop};},
screen:function(){return{width:window.screen.width,height:window.screen.height};},
isIE6:$.browser.msie&&$.browser.version==6,
isMinW:function(val){return Math.min($.browser.client().bodyWidth,$.browser.client().width)<=val;},
isMinH:function(val){return $.browser.client().height<=val;}});
}
)
(jQuery);
/*jdHover*/
(function($){
$.fn.antHover=function(option){
var s=$.extend({content:".content",itemTag:"li",cbox:".cbox",csshover:"pchover",lefthover:200,tophover:0,pa:false},option||{});

var contentItem=$(this).find(s.content);

contentItem.each(function(n){
var pcchild=$(this).find("span");
var pcbox=$(this).find(s.cbox);
if(pcbox.length>0) {
pcbox.hide();
	$(this).bind(
	"mouseover",
	function(){
pcchild.eq(1).hide();
$(this).addClass(s.csshover);
pcbox.show();
pcbox.css({'left':(s.pa?0:$(this).offset().left+s.lefthover),'top':s.pa?0:$(this).offset().top+s.tophover}); 
	return;
	})
	.bind("mouseleave",
	function(){
	pcbox.hide();
	pcchild.eq(1).show();
	$(this).removeClass(s.csshover);	
	return;})
	}
	});
	}
	
}
)
(jQuery);
/*jdTab*/
(function($)
{$.fn.antTab=function(option,callback)
{
	if(typeof option=="function")
			  {callback=option;option={};
			  };
			  
var s=$.extend({type:"static",auto:false,source:"data",event:"mouseover",currClass:"curr",tab:".tab",content:".tabcon",itemTag:"li",stay:5000,delay:100,fadeIn:0,fadeOut:0,mainTimer:null,subTimer:null,index:0,sshowall:false},option||{});
var tabItem=$(this).find(s.tab).find(s.itemTag),contentItem=$(this).find(s.content);
//alert(s.itemTag+tabItem.length+s.content+contentItem.length);
if(contentItem.length==0)contentItem=$(s.content);//

if(tabItem.length!=contentItem.length) return false;//
	
	var reg=s.source.toLowerCase().match(/http:\/\/|\d|\.aspx|\.ascx|\.asp|\.php|\.html\.htm|.shtml|.js|\W/g);
	
	var init=function(n,tag)
	{
	
	s.subTimer=setTimeout(
	             function()
				 {
	               hide();
	               if(tag)
	               {s.index++;
	               if(s.index>=tabItem.length)s.index=0;
	               }
	               else
	               {
	                s.index=n;
	               };
	             s.type=(tabItem.eq(s.index).attr(s.source)!=null)?"dynamic":"static";
	             show();
	             }
	             ,s.delay
				 );
	};
	
	var autoSwitch=function()
	{
	s.mainTimer=setInterval(function(){init(s.index,true);},s.stay);
	return;
	}
	;
	
	var show=function()
	         {
		      tabItem.eq(s.index).addClass(s.currClass);
		
              
			  if(s.sshowall&&s.index==0)
			       {contentItem.show(); }
				   else
				   {
					   contentItem.hide(s.fadeOut);	 
					     if(s.fadeIn>0)
			  {
	                   contentItem.eq(s.index).fadeOut(1);   
	                   contentItem.eq(s.index).fadeIn(s.fadeIn);
	           }
			   else
			  {
					   contentItem.eq(s.index).show();
			   }
				   }
		    
			  
			   switch(s.type)
			  {
	             default:
	             case "static":
	                 var source="";
	                 break;
	             case "dynamic":
	                 var source=(reg==null)?tabItem.eq(s.index).attr(s.source):s.source;tabItem.eq(s.index).removeAttr(s.source);
	                 break;
	           }; 
			   if(callback){callback(source,contentItem.eq(s.index),s.index);};
			   };
	var hide=function(){
		tabItem.eq(s.index).removeClass(s.currClass);

	    contentItem.eq(s.index).hide(s.fadeOut);
	};
	
tabItem.removeClass(s.currClass);



show();

	tabItem.each(function(n)
						  {
	                          $(this).bind(
	                                    s.event,
	                                       function(){
											     clearTimeout(s.subTimer);clearInterval(s.mainTimer);
	                                            init(n,false);
												return false;
												})
	                                  .bind("mouseleave",
	                                        function(){
												if(s.auto){clearInterval(s.mainTimer);autoSwitch();}
												else
												{return;}
												})
	                        }
				);
	
	contentItem.each(function(n)
							  {
								  $(this).bind("mouseover",
	                                       function(){
											     clearTimeout(s.subTimer);clearInterval(s.mainTimer);
	                                            //init(n,false);
												return false;
												})
								  .bind("mouseleave",
	                                        function(){
												if(s.auto){clearInterval(s.mainTimer);autoSwitch();}
												else
												{return;}
												})
								  
							  }	
							  );
	
	if(s.type=="dynamic"){init(s.index,false);};
	if(s.auto){clearInterval(s.mainTimer);autoSwitch();}
	
	}})(jQuery);
	
	
/*jdMarquee*/
(function($)
{$.fn.antMarquee=function(option,callback)
{
if(typeof option=="function")
{callback=option;option={};};
var s=$.extend({deriction:"up",speed:10,auto:false,width:null,height:null,step:1,control:false,_front:null,_back:null,_stop:null,_continue:null,wrapstyle:"",stay:5000,delay:20,dom:"div>ul>li".split(">"),mainTimer:null,subTimer:null,tag:false,convert:false,btn:null,disabled:"disabled",pos:{ojbect:null,clone:null}},option||{});
var object=this.find(s.dom[1]);
var subObject=this.find(s.dom[2]);
var clone;

if(s.deriction=="up"||s.deriction=="down")
{var height=object.eq(0).outerHeight();
var step=s.step*subObject.eq(0).outerHeight();
object.css({width:s.width+"px",overflow:"hidden"});};
if(s.deriction=="left"||s.deriction=="right")
{var width=subObject.length*subObject.eq(0).outerWidth();
object.css({width:width+"px",overflow:"hidden"});
var step=s.step*subObject.eq(0).outerWidth();};

var init=function()
{var wrap="<div style='position:relative;overflow:hidden;z-index:1;width:"+s.width+"px;height:"+s.height+"px;"+s.wrapstyle+"'></div>";
object.css({position:"absolute",left:0,top:0}).wrap(wrap);
s.pos.object=0;clone=object.clone();object.after(clone);
switch(s.deriction){default:case "up":object.css({marginLeft:0,marginTop:0});
clone.css({marginLeft:0,marginTop:height+"px"});
s.pos.clone=height;break;case "down":object.css({marginLeft:0,marginTop:0});
clone.css({marginLeft:0,marginTop:-height+"px"});
s.pos.clone=-height;break;case "left":
object.css({marginTop:0,marginLeft:0});
clone.css({marginTop:0,marginLeft:width+"px"});
s.pos.clone=width;break;case "right":
object.css({marginTop:0,marginLeft:0});
clone.css({marginTop:0,marginLeft:-width+"px"});
s.pos.clone=-width;break;};
if(s.auto){initMainTimer();
object.hover(function(){clear(s.mainTimer);clear(s.subTimer);},function(){initMainTimer();initSubTimer();});
clone.hover(function(){clear(s.mainTimer);clear(s.subTimer);},function(){initMainTimer();initSubTimer();});};
if(callback){callback();};if(s.control){initControls();}};
var initMainTimer=function(delay){clear(s.mainTimer);s.stay=delay?delay:s.stay;s.mainTimer=setInterval(function(){initSubTimer()},s.stay);};
var initSubTimer=function(){clear(s.subTimer);s.subTimer=setInterval(function(){roll()},s.delay);};
var clear=function(timer){if(timer!=null){clearInterval(timer);}};
var disControl=function(A){if(A){$(s._front).unbind("click");$(s._back).unbind("click");
$(s._stop).unbind("click");
$(s._continue).unbind("click");}else{initControls();}};
var initControls=function(){
if(s._front!=null){$(s._front).click(function(){$(s._front).addClass(s.disabled);
disControl(true);clear(s.mainTimer);s.convert=true;s.btn="front";if(!s.auto){s.tag=true;};
convert();});};if(s._back!=null){$(s._back).click(function(){$(s._back).addClass(s.disabled);
disControl(true);clear(s.mainTimer);s.convert=true;s.btn="back";if(!s.auto){s.tag=true;};convert();});};
if(s._stop!=null){$(s._stop).click(function(){clear(s.mainTimer);});};
if(s._continue!=null){$(s._continue).click(function(){initMainTimer();});}};
var convert=function(){if(s.tag&&s.convert){s.convert=false;if(s.btn=="front"){if(s.deriction=="down"){s.deriction="up";};
if(s.deriction=="right"){s.deriction="left";}};if(s.btn=="back"){if(s.deriction=="up"){s.deriction="down";};
if(s.deriction=="left"){s.deriction="right";}};if(s.auto){initMainTimer();}else{initMainTimer(4*s.delay);}}};
var setPos=function(y1,y2,x){if(x){clear(s.subTimer);s.pos.object=y1;s.pos.clone=y2;s.tag=true;}else{s.tag=false;};
if(s.tag){if(s.convert){convert();}else{if(!s.auto){clear(s.mainTimer);}}};if(s.deriction=="up"||s.deriction=="down"){object.css({marginTop:y1+"px"});clone.css({marginTop:y2+"px"});};
if(s.deriction=="left"||s.deriction=="right"){object.css({marginLeft:y1+"px"});clone.css({marginLeft:y2+"px"});}};
var roll=function(){
var y_object=(s.deriction=="up"||s.deriction=="down")?parseInt(object.get(0).style.marginTop):parseInt(object.get(0).style.marginLeft);var y_clone=(s.deriction=="up"||s.deriction=="down")?parseInt(clone.get(0).style.marginTop):parseInt(clone.get(0).style.marginLeft);
var y_add=Math.max(Math.abs(y_object-s.pos.object),Math.abs(y_clone-s.pos.clone));
var y_ceil=Math.ceil((step-y_add)/s.speed);switch(s.deriction){case "up":if(y_add==step){setPos(y_object,y_clone,true);
$(s._front).removeClass(s.disabled);disControl(false);}else{if(y_object<=-height){y_object=y_clone+height;s.pos.object=y_object;};
if(y_clone<=-height){y_clone=y_object+height;s.pos.clone=y_clone;};setPos((y_object-y_ceil),(y_clone-y_ceil));};break;case "down":
if(y_add==step){setPos(y_object,y_clone,true);$(s._back).removeClass(s.disabled);disControl(false);}
else{if(y_object>=height){y_object=y_clone-height;s.pos.object=y_object;};
if(y_clone>=height){y_clone=y_object-height;s.pos.clone=y_clone;};
setPos((y_object+y_ceil),(y_clone+y_ceil));};break;case "left":
if(y_add==step){setPos(y_object,y_clone,true);
$(s._front).removeClass(s.disabled);disControl(false);}
else{if(y_object<=-width){y_object=y_clone+width;s.pos.object=y_object;};
if(y_clone<=-width){y_clone=y_object+width;s.pos.clone=y_clone;};setPos((y_object-y_ceil),(y_clone-y_ceil));};
break;case "right":if(y_add==step){setPos(y_object,y_clone,true);
$(s._back).removeClass(s.disabled);disControl(false);}else{if(y_object>=width){y_object=y_clone-width;s.pos.object=y_object;};
if(y_clone>=width){y_clone=y_object-width;s.pos.clone=y_clone;};setPos((y_object+y_ceil),(y_clone+y_ceil));};break;}};
if(s.deriction=="up"||s.deriction=="down"){if(height>=s.height&&height>=s.step){init();}};
if(s.deriction=="left"||s.deriction=="right"){if(width>=s.width&&width>=s.step){init();}}}})(jQuery);

/*jqueryzoom&&jcarousel*/
(function($){
$.fn.jqueryzoom=function(options){
var settings={
xzoom:320,
yzoom:320,
offset:10,
position:"right",
lens:1,
preload:1};
if(options){
$.extend(settings,options);}
var noalt='';
$(this).hover(function(){
var imageLeft=$(this).offset().left;
var imageTop=$(this).offset().top;
var imageWidth=$(this).children('img').get(0).offsetWidth;
var imageHeight=$(this).children('img').get(0).offsetHeight;
noalt=$(this).children("img").attr("alt");
var bigimage=$(this).children("img").attr("jqimg");
$(this).children("img").attr("alt",'');
if($("div.zoomdiv").get().length==0){
$(this).after("<div class='zoomdiv'><img class='bigimg' src='"+bigimage+"'/></div>");
$(this).append("<div class='jqZoomPup'>&nbsp;</div>");}
if(settings.position=="right"){
if(imageLeft+imageWidth+settings.offset+settings.xzoom>screen.width){
leftpos=imageLeft-settings.offset-settings.xzoom;}else{
leftpos=imageLeft+imageWidth+settings.offset;}}else{
leftpos=imageLeft-settings.xzoom-settings.offset;
if(leftpos<0){
leftpos=imageLeft+imageWidth+settings.offset;}}
$("div.zoomdiv").css({top:imageTop,left:leftpos});//imageTopleftpos
$("div.zoomdiv").width(settings.xzoom);
$("div.zoomdiv").height(settings.yzoom);
$("div.zoomdiv").show();
if(!settings.lens){
$(this).css('cursor','crosshair');}
$(document.body).mousemove(function(e){
mouse=new MouseEvent(e);
var bigwidth=$(".bigimg").get(0).offsetWidth;
var bigheight=$(".bigimg").get(0).offsetHeight;
var scaley='x';
var scalex='y';
if(isNaN(scalex)|isNaN(scaley)){
var scalex=(bigwidth/imageWidth);
var scaley=(bigheight/imageHeight);
$("div.jqZoomPup").width((settings.xzoom)/(scalex*1));
$("div.jqZoomPup").height((settings.yzoom)/(scaley*1));
if(settings.lens){
$("div.jqZoomPup").css('visibility','visible');}}
xpos=mouse.x-$("div.jqZoomPup").width()/2-imageLeft;
ypos=mouse.y-$("div.jqZoomPup").height()/2-imageTop;
if(settings.lens){
xpos=(mouse.x-$("div.jqZoomPup").width()/2 < imageLeft ) ? 0 : (mouse.x + $("div.jqZoomPup").width()/2>imageWidth+imageLeft)?(imageWidth-$("div.jqZoomPup").width()-2):xpos;
ypos=(mouse.y-$("div.jqZoomPup").height()/2 < imageTop ) ? 0 : (mouse.y + $("div.jqZoomPup").height()/2>imageHeight+imageTop)?(imageHeight-$("div.jqZoomPup").height()-2):ypos;}
if(settings.lens){
$("div.jqZoomPup").css({top:ypos,left:xpos});}
scrolly=ypos;
$("div.zoomdiv").get(0).scrollTop=scrolly*scaley;
scrollx=xpos;
$("div.zoomdiv").get(0).scrollLeft=(scrollx)*scalex;});},function(){
$(this).children("img").attr("alt",noalt);
$(document.body).unbind("mousemove");
if(settings.lens){
$("div.jqZoomPup").remove();}
$("div.zoomdiv").remove();});
count=0;
if(settings.preload){
$('body').append("<div style='display:none;' class='jqPreload"+count+"'>360buy</div>");
$(this).each(function(){
var imagetopreload=$(this).children("img").attr("jqimg");
var content=jQuery('div.jqPreload'+count+'').html();
jQuery('div.jqPreload'+count+'').html(content+'<img src=\"'+imagetopreload+'\">');});}}})(jQuery);

function MouseEvent(e){
this.x=e.pageX;
this.y=e.pageY;}

(function($){$.extend({
      closeWindown:function()
              {
		       $("#windownbg").remove();
		       $("#windown-box").fadeOut("slow",function(){$(this).remove();});
		      }
})})(jQuery);

/*jdDivBox*/
(function($){
		 
$.fn.antDivBox=function(option){	
var s=$.extend({event:"mouseover",title:"",type:"text",content:"",width:"250",height:"250",drag:true,time:0,showbg:true,showtitle:true,cssName:"",showWindown:true,templateSrc:""},option||{});
	$(this).bind(
	s.event,
	function(){
		showBox();	
	return;
	});	
	if(s.event=="mouseover"){
	$(this).bind("mouseleave",
	function(){	
	$.closeWindown();
	return;});
		
	}
var showBox=function(){	
	//alert(s.content);	
   $("#windown-box").remove(); //ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿?
	var width = s.width>= 950?this.width=950:this.width=s.width;	    //ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ó´°¿Ú¿ï¿½ï¿½
	var height = s.height>= 527?this.height=527:this.height=s.height;  //ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ó´°¿Ú¸ß¶ï¿?
	if(s.showWindown) {
		var simpleWindown_html = new String;
			simpleWindown_html = "<div id=\"windownbg\" style=\"height:"+$(document).height()+"px;filter:alpha(opacity=0);opacity:0;z-index: 999901\"></div>";
			simpleWindown_html += "<div id=\"windown-box\">";
			simpleWindown_html += "<div id=\"windown-title\"><h2></h2><span id=\"windown-close\">ï¿½Ø±ï¿½</span></div>";
			simpleWindown_html += "<div id=\"windown-content-border\"><div id=\"windown-content\"></div></div>"; 
			simpleWindown_html += "</div>";
			$("body").append(simpleWindown_html);
			show = false;
	}

	switch(s.type) {
		case "text":
		$("#windown-content").html(s.content);
		break;
		case "id":
		$("#windown-content").html($(s.content).html());
		break;
		case "img":
		$("#windown-content").ajaxStart(function() {
			$(this).html("<img src='"+s.templateSrc+"images/loading.gif' class='loading' />");
		});
		$.ajax({
			error:function(){
				$("#windown-content").html("<p class='windown-error'>ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ý³ï¿½ï¿½ï¿½...</p>");
			},
			success:function(html){
				$("#windown-content").html("<img src="+s.content+" alt='' />");
			}
		});
		break;
		case "url":
		var content_array=s.content.split("?");
		//$("#windown-content").ajaxStart(function(){
		//	$(this).html("<img src='"+s.templateSrc+"images/loading.gif' class='loading' />");
		//});
		var atime=s.time;
		s.time="";
		$.ajax({
			type:content_array[0],
			url:content_array[1],
			data:content_array[2],
			cache:false,
			error:function(){
				$("#windown-content").html("<p class='windown-error'>ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ý³ï¿½ï¿½ï¿½...</p>");
			},
			success:function(html){
				$("#windown-content").html(html);
				if( atime != "")setTimeout($.closeWindown,atime);
			}
		});
		break;
		case "iframe":
		$("#windown-content").ajaxStart(function(){
			$(this).html("<img src='"+s.templateSrc+"images/loading.gif' class='loading' />");
		});
		$.ajax({
			error:function(){
				$("#windown-content").html("<p class='windown-error'>ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ý³ï¿½ï¿½ï¿½...</p>");
			},
			success:function(html){
				$("#windown-content").html("<iframe src=\""+s.content+"\" width=\"100%\" height=\""+parseInt(height)+"px"+"\" scrolling=\"auto\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\"></iframe>");
			}
		});
	}
	
	
	if(s.showtitle){$("#windown-title h2").html(s.title);}
	else
	{$("#windown-title").hide();		
		}
	
	if(s.showbg) {$("#windownbg").show();}else {$("#windownbg").remove();};

	$("#windownbg").animate({opacity:"0.5"},"normal");//ï¿½ï¿½ï¿½ï¿½Í¸ï¿½ï¿½ï¿½ï¿½	alert(s.showbg);
	$("#windown-box").show();
	if( height >= 527 ) {
		$("#windown-title").css({width:(parseInt(width)+22)+"px"});
		$("#windown-content").css({width:(parseInt(width)+17)+"px",height:height+"px"});
	}else {
		$("#windown-title").css({width:(parseInt(width)+10)+"px"});
		$("#windown-content").css({width:width+"px",height:height+"px"});
	}
	var	cw = document.documentElement.clientWidth,ch = document.documentElement.clientHeight,est = document.documentElement.scrollTop; 
	var _version = $.browser.version;
	if ( _version == 6.0 ) {
		$("#windown-box").css({left:"50%",top:(parseInt((ch)/2)+est)+"px",marginTop: -((parseInt(height)+53)/2)+"px",marginLeft:-((parseInt(width)+32)/2)+"px",zIndex: "999999"});
	}else {
		$("#windown-box").css({left:"50%",top:"50%",marginTop:-((parseInt(height)+53)/2)+"px",marginLeft:-((parseInt(width)+32)/2)+"px",zIndex: "999999"});
	};
	var Drag_ID = document.getElementById("windown-box"),DragHead = document.getElementById("windown-title");
		
	var moveX = 0,moveY = 0,moveTop,moveLeft = 0,moveable = false;
		if ( _version == 6.0 ) {
			moveTop = est;
		}else {
			moveTop = 0;
		}
	var	sw = Drag_ID.scrollWidth,sh = Drag_ID.scrollHeight;
		DragHead.onmouseover = function(e) {
			if(s.drag){DragHead.style.cursor = "move";}else{DragHead.style.cursor = "default";}
		};
		DragHead.onmousedown = function(e) {
		if(s.drag){moveable = true;}else{moveable = false;}
		e = window.event?window.event:e;
		var ol = Drag_ID.offsetLeft, ot = Drag_ID.offsetTop-moveTop;
		moveX = e.clientX-ol;
		moveY = e.clientY-ot;
		document.onmousemove = function(e) {
				if (moveable) {
				e = window.event?window.event:e;
				var x = e.clientX - moveX;
				var y = e.clientY - moveY;
					if ( x > 0 &&( x + sw < cw) && y > 0 && (y + sh < ch) ) {
						Drag_ID.style.left = x + "px";
						Drag_ID.style.top = parseInt(y+moveTop) + "px";
						Drag_ID.style.margin = "auto";
						}
					}
				}
		document.onmouseup = function () {moveable = false;};
		Drag_ID.onselectstart = function(e){return false;}
	}
	$("#windown-content").attr("class","windown-"+s.cssName);	
	
	if( s.time == "" || typeof(s.time) == "undefined"||s.time==0) {
		$("#windown-close").click(function() {
			$("#windownbg").remove();
			$("#windown-box").fadeOut("slow",function(){$(this).remove();});
		});
	}else { 
	
		setTimeout($.closeWindown,s.time);
	}
}

//var closeWindown = function() {
//		$("#windownbg").remove();
//		$("#windown-box").fadeOut("slow",function(){$(this).remove();});
//	}
	}	
}
)
(jQuery);


(function($) {   
    function toIntegersAtLease(n) 
    // Format integers to have at least two digits.
    {    
        return n < 10 ? '0' + n : n;
    }

    Date.prototype.toJSON = function(date)
    // Yes, it polutes the Date namespace, but we'll allow it here, as
    // it's damned usefull.
    {
        return this.getUTCFullYear()   + '-' +
             toIntegersAtLease(this.getUTCMonth()) + '-' +
             toIntegersAtLease(this.getUTCDate());
    };

    var escapeable = /["\\\x00-\x1f\x7f-\x9f]/g;
    var meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        };
        
    $.quoteString = function(string)
    // Places quotes around a string, inteligently.
    // If the string contains no control characters, no quote characters, and no
    // backslash characters, then we can safely slap some quotes around it.
    // Otherwise we must also replace the offending characters with safe escape
    // sequences.
    {
        if (escapeable.test(string))
        {
            return '"' + string.replace(escapeable, function (a) 
            {
                var c = meta[a];
                if (typeof c === 'string') {
                    return c;
                }
                c = a.charCodeAt();
                return '\\u00' + Math.floor(c / 16).toString(16) + (c % 16).toString(16);
            }) + '"';
        }
        return '"' + string + '"';
    };
    
    $.toJSON = function(o)
    {
        var type = typeof(o);
		
        if (type == "undefined")
            return "undefined";
        else if (type == "number" || type == "boolean")
            return o + "";
        else if (o === null)
            return "null";
        
        // Is it a string?
        if (type == "string") 
        {
            return $.quoteString(o);
        }
        
        // Does it have a .toJSON function?
        if (type == "object" && typeof o.toJSON == "function") 
            return o.toJSON();
        
        // Is it an array?
        if (type != "function" && typeof(o.length) == "number") 
        {
            var ret = [];
            for (var i = 0; i < o.length; i++) {
                ret.push( $.toJSON(o[i]) );
            }
            return "[" + ret.join(",") + "]";
        }
        
        // If it's a function, we have to warn somebody!
        if (type == "function") {
            throw new TypeError("Unable to convert object of type 'function' to json.");
        }
        
        // It's probably an object, then.
        var ret = [];
        for (var k in o) {
            var name;
            type = typeof(k);
            
            if (type == "number")
                name = '"' + k + '"';
            else if (type == "string")
                name = $.quoteString(k);
            else
                continue;  //skip non-string or number keys
            
            var val = $.toJSON(o[k]);
            if (typeof(val) != "string") {
                // skip non-serializable values
                continue;
            }
            
            ret.push(name + ":" + val);
        }
        return "{" + ret.join(",") + "}";
    };
    
    $.compactJSON = function(o)
    {
        return $.toJSON(o, true);
    };
    
    $.evalJSON = function(src)
    // Evals JSON that we know to be safe.
    {
        return eval("(" + src + ")");
    };
    
    $.secureEvalJSON = function(src)
    // Evals JSON in a way that is *more* secure.
    {
        var filtered = src;
        filtered = filtered.replace(/\\["\\\/bfnrtu]/g, '@');
        filtered = filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
        filtered = filtered.replace(/(?:^|:|,)(?:\s*\[)+/g, '');
        
        if (/^[\],:{}\s]*$/.test(filtered))
            return eval("(" + src + ")");
        else
            throw new SyntaxError("Error parsing JSON, source is not valid.");
    };
})(jQuery);

(function($) {
     $.fn.ping = function(options) {
         var opts = $.extend({}, $.fn.ping.defaults, options);
         var stime = new Date().getTime(); 
        return this.each(function() {
             var ping, requestTime, responseTime ;
             var target = $(this);
             function ping(){
                 $.ajax({url: opts.getUrl(target.html())+'/'+ Math.random() + '.html',  //ÉèÖÃÒ»¸ö¿ÕµÄajaxÇëÇó
                    type: opts.type,
                     dataType: 'html',
                     timeout: opts.timeout,
                     beforeSend : function() {
                         requestTime = new Date().getTime();
                     },
                     complete : function() {
                         responseTime = new Date().getTime();
                         ping = Math.abs(requestTime - responseTime);
                         $('#'+target.html().replace('.','_')).text(ping + opts.unit);
                         //target.text(ping + opts.unit);
                     }
                 });
             }
             var etime = new Date().getTime();
             if(Math.abs(stime - etime)<opts.timeout){
                 ping();  //ÎÞÂÛÈçºÎ¶¼ÒªÖ´ÐÐÒ»´Î
                opts.interval != 0 && setInterval(ping,opts.interval * 1000);
             }
         });
     };
     $.fn.ping.defaults = {
         type: 'GET',
         timeout: 10000,
         interval: 3,
         unit: 'ms',
         isUrl:function(url){    //ÑéÖ¤urlÊÇ·ñÓÐÐ§
           var strReg = "^((https|http)?://)?" 
            + "(([0-9]{1,3}.){3}[0-9]{1,3}" // 
            + "|" // 
            + "([0-9a-z_!~*'()-]+.)*" // 
            + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]." // 
            + "[a-z]{2,6})" // 
            + "(:[0-9]{1,4})?" // 
            + "((/?)|" // 
            + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$"; 
            var re=new RegExp(strReg); 
            return re.test(url);
            },
         getUrl:function(url){    //±£Ö¤url´øhttp://
             var strReg="^((https|http)?://){1}"
             var re=new RegExp(strReg); 
            return re.test(url)?url:"http://"+url;
         }
     };
 })(jQuery);