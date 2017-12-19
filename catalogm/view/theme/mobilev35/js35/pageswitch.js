var PageSwitch = function() {
	var _this=this;
	ptmain='body';
    children='div.pg-page',
	    $main = $(ptmain),
		$pages = $main.children( children),
		pagesCount = $pages.length,
		current = 0;
		
    _this.init= function() {
		$pages.each( function() {
			var $page = $( this );
			$page.hide();
		} );

		$pages.eq( current ).show();
    };
	
	// 全局函数
	_this.switchTo = function ( index ) {			
		// 没变化
		if( current == index)
		{
			return this;
		}
		
		$pages.eq( index ).show();
		document.title = $pages.eq( index ).find('#header').attr('pg-name');
		$pages.eq( current).hide();
		
		current = index;
	};


	_this.init();

	return this;

};