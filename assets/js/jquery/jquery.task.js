(function($) {
	
	if (!$) {
		return console.warn('task needs jQuery');
	}

	var rqlist = [],s;

	$.task = {
		monitor : function(option) {
			option = option || {};
			 s = $.extend({
				'autorun' : true,'ajax' : true
			}, option);

			var i = setInterval(function(l) {
				l = 1;
			var	DD = new Date - D;
				if (DD > 950)
					l++;
				if (DD > 900)
					l++;
				if (DD > 850)
					l++;
				if (DD > 800)
					l++;
				if (DD > 750)
					l++;
				if (DD > 700)
					l++;
				if (DD > 650)
					l++;
				if (DD > 600)
					l++;
				if (DD > 550)
					l++;
				// console.log('l',l,D);

				if (l == 1 && s.autorun) {
					$.task.rq.run($.task.rq.readone());
				} else {
					console.log('时间 繁忙度 反应时间', D.toLocaleString(), l,DD,rqlist);
				}

				D = new Date;
			}, 500), D = new Date;
			
			if(s.ajax)
			{
				/* 重新写ajax 加入监控序列*/
				var rqajax=$.ajax;
				$.ajax=function(settings){
					$.task.rq.add(function(){
						if(settings.success){
						var success=settings.success;
						settings.success=function(ds){
							//console.log('success',success,settings);
							$.task.rq.add(function(){success(ds);});
						};
						}
						rqajax(settings);
						
					});	
					
					
				};
			}
			
			
		},
		rq : {
			add : function(f) {
				rqlist.push(f);
			},
			readone : function() {
				if (rqlist.length > 0)
					return [ rqlist.shift() ];
				else
					return false;
			},
			read : function(n) {
				if (rqlist.length > 0) {
					if (rqlist.length > n) {
						var res = [];
						for (var i = 0; i < n; i++)
							res.push(rqlist.shift());
						return res;
					} else {
						return rqlist;

					}
				} else
					return false;
			},
			run : function(fs) {
				//console.log('fs', fs);// fs[0]();
				fs = fs || $.task.rq.readone();
				for ( var i in fs) {
					//fs[i]();
					setTimeout(fs[i], 5);
				}
			}

		}
	};
	

	/*
	var ajax=$.ajax;
	$.ajax=function(data){
		s.autorun=false;
		$.task.rq.add(function(){
			var success=data.success;
			data.success=function(ds){
				
				success(ds);
				$.task.rq.run($.task.rq.readone());
				
			};
			
			ajax(data);
			
		});	
		
	};
	*/
	
})(jQuery);