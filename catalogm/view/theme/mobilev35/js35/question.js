$('.qa_item').live('click', function() {
	if ($(this).hasClass('active')) {
		$(this).removeClass('active');
	} else {
		$(this).addClass('active');
	}

	$(this).parent().find('.msg').slideToggle('fast');
	Resize();
});

$(function () {
    var page = 2,
        last = false;

//加载更多
    getMore = function (obj) {
        if (last) {
            _.alert('木有啦，别点啦');
            return;
        }
        
       	$.ajax({
    		url: 'index.php?route=information/question/update',
    		type: 'post',
    		data: "page=" + page.toString(),
    		dataType: 'json',
    		beforeSend: function() {
    		},
    		complete: function() {
    		},
    		success: function(json) {		    
			   if (json['code']) {
	                //var append_obj = obj.parent().prev();
	                //append_obj.append(json['output']);
				    if(obj){
		                obj.append(json['output']);
		                page = page + 1;
				    }
	            } else {
	                _.alert(json['msg']);
	                last = true;
	            }
    		}
    	}); 
    };
});


refresher.init({
	id:"wrapper",//<------------------------------------------------------------------------------------┐
	pullDownAction:Refresh,                                                            
	pullUpAction:Load 																			
});			

function Resize(){
	setTimeout(function () {	
		var el, li, i;																		
		el =document.querySelector("#wrapper ul");
		wrapper.refresh();
	}, 1000);
}

function Refresh() {																
	setTimeout(function () {	// <-- Simulate network congestion, remove setTimeout from production!
		var $obj = $('#wrapper ul');
		getMore( $obj);
		var el, li, i;																		
		el =document.querySelector("#wrapper ul");					
		//这里写你的刷新代码				
		document.getElementById("wrapper").querySelector(".pullDownIcon").style.display="none";		
		document.getElementById("wrapper").querySelector(".pullDownLabel").innerHTML='<div class="successIcon"></div>刷新成功';																					 
		setTimeout(function () {
			wrapper.refresh();
			document.getElementById("wrapper").querySelector(".pullDownLabel").innerHTML="";								
			},1000);//模拟qq下拉刷新显示成功效果
		/****remember to refresh after  action completed！ ---yourId.refresh(); ----| ****/
	}, 2000);
}


function Load() {
	setTimeout(function () {// <-- Simulate network congestion, remove setTimeout from production!
		var el, li, i;
		el =document.querySelector("#wrapper ul");
//		for (i=0; i<2; i++) {
//			li = document.createElement('li');
//			li.innerHTML="<img src='img/game8.png'><div class='game-info'><h1>华仔超神战记</h1><p>9万次下载     89.18M</p><p>秒杀虚拟摇杆，砸烂手机键盘</p></div><button>下载</button>"
//			el.appendChild(li, el.childNodes[0]);
//		}
		var $obj = $('#wrapper ul');
		getMore( $obj);
		wrapper.refresh();/****remember to refresh after action completed！！！   ---id.refresh(); --- ****/
	},3000);	
}