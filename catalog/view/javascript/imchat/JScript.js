$(document).ready(function() { 
                                           
       $("#siderIMchat_hiddenbar1").mouseover(function(){
           	$(this).css("display","none");
          	$("#siderIMchat_main1").css("display","block")
     	});
  
    $("#closeSiderIMchat1").click(function(){
           $("#siderIMchat_main1").css("display","none");
            $("#siderIMchat_hiddenbar1").css("display","block")
        });

    siderIMchatsetGoTop();  

    $(window).scroll(function(){ 
            
            siderIMchatsetGoTop();
       });                
});

function siderIMchatsetGoTop(){
   if($("#siderIMchat"))$("#siderIMchat").css("top",$(window).scrollTop()+300);  
                      
   $("#siderIMchat1").css("top",$(window).scrollTop() +300);
} 