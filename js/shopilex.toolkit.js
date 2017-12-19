$(document).ready(function(){
    $(".scrollbox").each(function(i) {
    	$(this).attr('id', 'scrollbox_' + i);
    	
		sbox = '#' + $(this).attr('id');
		html='<span><a onclick="$(\'' + sbox + ' :checkbox\').attr(\'checked\', true);"><u>全选</u></a>'
		+' / '
		+'<a onclick="$(\'' + sbox + ' :checkbox\').attr(\'checked\', false);"><u>取消全选</u></a>'
		+' / '
		+'<a onclick="$(\'' + sbox + ' :checkbox\').each(function(){$(this).attr(\'checked\',(!$(this).attr(\'checked\')));});"><u>反选</u></a>'
		+'</span>';
    	$(this).after(html);
	});
    
    $('a.popup').click(function(event){event.preventDefault();window.open($(this).attr('href'),'_blank'); });
    
    $('a.more').click(function(event){event.preventDefault();window.open($(this).attr('href'),'_blank'); });
});

function display(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.product-list > div').each(function(index, element) {
			html  = '<div class="right">';
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
//			html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
			html += '</div>';			
			
			html += '<div class="left">';
			
			var image = $(element).find('.image').html();
			
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
						
			html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
			//html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
				
			html += '</div>';

						
			$(element).html(html);
		});		
		
		$('.display .grid').removeClass('active');
		$('.display .list').addClass('active');
		
		$.cookie('display', 'list'); 
	} else {
		$('.product-list').attr('class', 'product-grid');
		
		$('.product-grid > div').each(function(index, element) {
			html = '<div class="product-box">';
			
			var image = $(element).find('.image').html();
			
			if (image != null) {
				html += '<div class="image">' + image + '</div>';
			}
			
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}	
					
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
						
			html += '<div class="operate">';
			html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
//			html += '<div class="compare">' + $(element).find('.compare').html() + '</div>';
			html += '</div>';
			html += '</div>';
			$(element).html(html);
		});	
					
		$('.display .grid').addClass('active');
		$('.display .list').removeClass('active');
		
		$.cookie('display', 'grid');
	}
}

$(document).ready(function(){
	$('.display .grid').click(function(){ display('grid');});

	$('.display .list').click(function(){ display('list'); });
	
	if($('.product-grid').length > 0){
		view = $.cookie('display');
		
		if (view=='list') {
			display(view);
		}
	}
});