$.fn.tabs = function(s) {
	var selector = this;
	s=s||{};
	
	s=$.extend({donefirst:true,callback:false},s);
	this.each(function(n) {
		var obj = $(this); 
		
		$(obj.attr('href')).hide();
		
		$(obj).click(function() {
			$(selector).removeClass('selected');
			$(obj.attr('href')).removeClass('selected');
			$(selector).each(function(i, element) {
				$($(element).attr('href')).hide();
				
			});
			
			$(this).addClass('selected');
			
			$($(this).attr('href')).show();
			if(s.callback)s.callback(n);
			return false;
		});
	});

	$(this).show();
	
	if (s.donefirst)$(this).first().click();
};