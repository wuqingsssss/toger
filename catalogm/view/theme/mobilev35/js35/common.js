/**
 * @file ${FILE_NAME}. Created by PhpStorm.
 * @desc ${FILE_NAME}.
 *
 * @author yangjunbao
 * @since 15/10/29 上午10:30
 * @version 1.0.0
 */

/**
 * 公共库定义
 *
 * 命名空间: window._
 *  全局变量:
 *     {Zepto}      _.$body             body元素
 *     {jQuery}      _.$navbar           底部导航tab
 *     {string}     _.touchEnd          触摸结束事件名
 *     {string}     _.cssAnimateEnd     css动画播放结束事件名
 *  方法列表:
 *      static  {void}      _.go({string|int} [url])        链接跳转
 *      static  {void}      _.search({string} word)         搜索跳转
 *      static  {void}      _.addCart({string} foodId, {Function} [callback]) 添加购物车
 *      constructor Overlay     _.Overlay({Object} options)     浮层
 *      static  Overlay     _.alert({Object} options)    警告浮屠
 *      static  Overlay     _.confirm({Object} options)  确认浮屠
 *
 * 扩展Zepto
 *      $.cssAnimateOnce({string} type, {Function} [callback])  执行单次动画
 *      $.tipsBox({string} content, {string} [type])            浮屠提示
 */
(function ($) {
    /**
     * @type {Object}
     *
     * the namespace for global library
     */
	   window._ = window._ || {}; 
	  // console.log('window',window);
	   
	   /**
	     * 全局变量
	     */
	    _.$body = $(document.body);
	    _.$header = $('#header');
	    _.$navbar = $('#navbar');
	    _.touchStart = 'touchstart MSPointerDown pointerdown';
	    _.touchMove = 'touchmove MSPointerMove pointermove';
	    _.touchEnd = 'touchend MSPointerUp pointerup';
	    _.onClick='click';
	    _.cssAnimateEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

	   
    /**
 *
     * @param {string} names
     * @param {string} [pr]
     * @param {{}} [obj]
     *
     * @returns {string}
     */
    _.fixedAttr = function (names, pr, obj) {
        obj = obj || window;
        var attrs = names.split(' '),
            i = 0;
        for (; i < attrs.length; i++) {
            if (obj.hasOwnProperty(pr + attrs[i])) {
                return attrs[i];
            }
        }
        return attrs[0];
    };
    _.fixedCssName = (function () {
        var mod = $('<div/>').css({display: 'none'}).appendTo($(document.body)).get(0).style,
            prs = ['', 'webkit', 'moz', 'ms', 'o', 'Webkit', 'Moz', 'Ms', 'O'],
            cachedNames = {};
        return function (name) {
            var i,
                upper;
            if (cachedNames[name]) {
                return cachedNames[name];
            }
            upper = name[0].toUpperCase() + name.substr(1);
            for (i in prs) {
                if (prs.hasOwnProperty(i)) {
                    if (mod.hasOwnProperty(prs[i] + name)) {
                        cachedNames[name] = prs[i] + name;
                        return prs[i] + name;
                    } else if (mod.hasOwnProperty(prs[i] + upper)) {
                        cachedNames[name] = prs[i] + upper;
                        return prs[i] + upper;
                    }
                }
            }
        }
    })();
    
       /**
     * 滚动选择组件
     */
    _.Scroller = (function () {
        var config = {
                columns: [{
                    id: '',
                    items: []
                }],
                defaultValue: [],
                upperLine: 2,
                downLine: 2,
                hide: true,
                showFocusBorder: true,
                onChange: null,
                title: '',
                confirmText: '确定',
                confirmCallback: null,
                cancelText: '取消',
                cancelCallback: null,
                position: 'bottom',
                speedUnit: 0.002,
                timeUnit: 0.005
            },
            unitHeight = 30,
            mod = $('<div/>').css({display: 'none'}).appendTo($(document.body)).get(0).style,
            fixedCssName = function (name) {
                var prs = ['', 'webkit', 'moz', 'ms', 'o', 'Webkit', 'Moz', 'Ms', 'O'],
                    i,
                    upper = name[0].toUpperCase() + name.substr(1);
                for (i in prs) {
                    if (prs.hasOwnProperty(i)) {
                        if (mod.hasOwnProperty(prs[i] + name)) {
                            return prs[i] + name;
                        } else if (mod.hasOwnProperty(prs[i] + upper)) {
                            return prs[i] + upper;
                        }
                    }
                }
            };

        /**
         * @param options
         * @constructor
         *
         * @param {{}}          options  各项配置
         * @param {{}}          options.columns  各列
         * @param {string}      options.columns[id][key].label 列展示值
         * @param {boolean}     options.columns[id][key].disabled 是否禁止选择
         * @param {string}      options.lineHeight 行高
         * @param {int}         options.fontSize 字体大小
         * @param {boolean}     options.showFocusBorder 中部行border
         * @param {Function}    options.onChange 滚动后回调
         * @param {string}      options.confirmText 确定按钮
         * @param {Function}    options.confirmCallback 确定回调
         * @param {string}      options.cancelText 取消按钮
         * @param {Function}    options.cancelCallback 取消回调
         * @param {string}      options.position    位置: 'center': 居中, 'bottom': 底部
         */
        function Scroller(options) {
            var i,
                j,
                column,
                item,
                disabled,
                columnHtml = '',
                overlay,
                status = {
                    scrolling: false,
                    startTime: 0,
                    startPos: {x: 0, y: 0},
                    prevPos: {x: 0, y: 0}
                },
                values = [],
                that = this,
                $content = $('<div/>').addClass('scroller-content'),
                $buttons = $('<div/>').addClass('scroller-buttons clearfix'),
                $columns = $('<div/>').addClass('scroller-columns');
            options = $.extend({}, config, options);
            for (i in options.columns) {
                if (options.columns.hasOwnProperty(i)) {
                    column = options.columns[i];
                    columnHtml += '<div class="scroller-column scroller-column-' + column.id +
                    '"><div class="scroller-hover upper"></div><div class="scroller-hover down"></div><div class="scroller-column-items">';
                    for (j in column.items) {
                        if (column.items.hasOwnProperty(j)) {
                            item = column.items[j];
                            options.defaultValue[i] = options.defaultValue[i] || item.value;
                            disabled = item.disabled ? ' disabled' : '';
                            // disabled += j == 2 ? ' col-red' : '';
                            columnHtml += '<div class="scroller-item' + disabled + '">' + item.label + '</div>';
                        }
                    }
                    columnHtml += '</div></div>';
                }
            }
            $buttons.append($('<div/>').addClass('pull-left scroller-cancel').html(options.cancelText))
                .append($('<div/>').addClass('pull-right scroller-confirm').html(options.confirmText));
            $buttons.append($('<div/>').addClass('scroller-title text-center').html(options.title));
            $columns.html(columnHtml);
            if (options.showFocusBorder) {
                $columns.append('<div class="scroller-focus-border"></div>');
            }
            $content.append($buttons).append($columns);
            overlay = new _.Overlay({
                content: $content,
                hide: options.hide,
                className: 'overlay-scroller ' + options.position
            });
            overlay.on(_.touchStart, '.scroller-column', function (e) {
                var $this = $(this),
                    $items = $this.find('.scroller-column-items');
                e.preventDefault();
                if (status.scrolling) return;
                status.startTime = +new Date;
                status.startPos = $.eventPos(e);
                status.prevPos = $.eventPos(e);
                $items.find('.col-red').removeClass('col-red');
            }).on(_.touchMove, '.scroller-column', function (e) {
                e.preventDefault();
                var prevY = status.prevPos.y,
                    $this = $(this),
                    pos = $.eventPos(e),
                    $items = $this.find('.scroller-column-items'),
                    top = parseInt($items.css('top'), 10);
                if (prevY) {
                    $items.css({
                        top: top + pos.y - prevY
                    });
                }
                status.prevPos = pos;
            }).on(_.touchEnd, '.scroller-column', function (e) {
                e.preventDefault();
                var time = +new Date,
                    pos = $.eventPos(e),
                    $this = $(this),
                    dur = time - status.startTime,
                    delta = pos.y - status.startPos.y,
                    $items = $this.find('.scroller-column-items'),
                    top = parseInt($items.css('top'), 10),
                    finalTop = top,
                    closet,
                    $item,
                    transTime = 0.1,
                    speed = delta / dur;
                if (dur > 300 || Math.abs(delta) < 10) {
                    finishScroll($this);
                } else {
                    finalTop = speed * Math.abs(speed) / options.speedUnit;
                    closet = closestItem($this, finalTop + top);
                    finalTop = (-closet[0] + 2) * unitHeight;
                    $item = finalTop[1];
                    transTime = Math.abs(finalTop - top) * options.timeUnit;
                    transTime = Math.min(transTime, 1);
                    $items.css(fixedCssName('transition'), 'all ' + transTime + 's ease-out');
                    $items.css('top', finalTop + 'px');
                    setTimeout(function () {
                        finishScroll($this);
                    }, transTime * 1e3);
                }
            }).on(_.touchEnd, '.scroller-confirm', function () {
                var values = [],
                    index,
                    $column,
                    $this;
                overlay._overlay.find('.scroller-item.col-red').each(function () {
                    $this = $(this);
                    index = $this.index();
                    $column = $this.closest('.scroller-column');
                    values.push(options.columns[$column.index()].items[index].value);
                });
                overlay.hide();
                options.confirmCallback && options.confirmCallback(values);
            }).on(_.touchEnd, '.scroller-cancel', function () {
                overlay.hide();
                options.cancelCallback && options.cancelCallback();
            });

            function closestItem($column, top) {
                var index = $column.index(),
                    $items = $column.find('.scroller-column-items'),
                    items = options.columns[index].items,
                    total = items.length,
                    pos = Math.round((unitHeight * 2 - top) / unitHeight),
                    i,
                    itemIndex = 0,
                    $item;
                if (pos < 0) pos = 0;
                if (pos >= total) pos = total - 1;
                for (i = 0; i < total; i++) {
                    if (pos + i < total && !items[pos + i].disabled) {
                        itemIndex = pos + i;
                        break;
                    }
                    if (pos - i >= 0 && !items[pos - i].disabled) {
                        itemIndex = pos - i;
                        break;
                    }
                }
                $item = $items.find('.scroller-item:nth-child(' + (itemIndex + 1) + ')');
                return [itemIndex, $item]
            }

            function finishScroll($column, repos) {
                var index = $column.index(),
                    $items = $column.find('.scroller-column-items'),
                    top = parseInt($items.css('top'), 10),
                    items = options.columns[index].items,
                    closest = closestItem($column, top),
                    itemIndex = closest[0],
                    $item = closest[1];
                $items.css(fixedCssName('transition'), 'all 0s ease-out');
                $items.css('top', ((-itemIndex + 2) * unitHeight) + 'px');
                $item.addClass('col-red').siblings('.col-red').removeClass('col-red');
                values[index] = items[index].value;
                options.onChange && options.onChange.call(that, options.columns[index].id, items[itemIndex].value, index, itemIndex);
            }

            this._content = overlay;
            this._options = options;
            this.closestItem = closestItem;
            this.finishScroll = finishScroll;
            this.init();
            this.values = function () {
                return values;
            };
        }

        Scroller.prototype = {
            init: function () {
                var that = this,
                    $item,
                    top,
                    $column;
                this._content._overlay.find('.scroller-column').each(function () {
                    $column = $(this);
                    $item = $column.find('.scroller-item:not(.disabled)').eq(0).addClass('col-red');
                    top = (2 - $item.index())*unitHeight;
                    $column.find('.scroller-column-items').css('top', top + 'px')
                });
            },
            // 禁用某些值, 只发生在其它列值可用的情况下
            // 所以不用考虑重新定位问题
            disable: function (column, indexes) {
                var $column = this._content._overlay.find('.scroller-column:nth-child(' + (column + 1) + ')'),
                    $items = $column.find('.scroller-column-items'),
                    closest,
                    i,
                    top = parseInt($items.css('top'), 10);
                indexes = Array.isArray(indexes) ? indexes : [indexes];
                for (i = 0; i < indexes.length; i++) {
                    this._options.columns[column].items[indexes[i]].disabled = true;
                    $items.find('.scroller-item:nth-child(' + (indexes[i] + 1) + ')').addClass('disabled');
                }
                return this;
            },
            enable: function (column, indexes) {
                var $column = this._content._overlay.find('.scroller-column:nth-child(' + (column + 1) + ')'),
                    $items = $column.find('.scroller-column-items'),
                    i;
                indexes = Array.isArray(indexes) ? indexes : [indexes];
                for (i = 0; i < indexes.length; i++) {
                    this._options.columns[column].items[indexes[i]].disabled = false;
                    $items.find('.scroller-item:nth-child(' + (indexes[i] + 1) + ')').removeClass('disabled');
                }
                return this;
            },
            show: function () {
                this._content.show()
            },
            hide: function () {
                this._content.hide()
            }
        };
        return Scroller;
    })()
    
  
   
    /**
     * 搜索接口
     *
     * @param {string} word
     *
     * TODO: finish this by RD give relative api
     */
    _.search = function (word) {
        location.href = '/search?wd=' + word;
    };

    /**
     * 添加购物车
     */
    _.addCart = function (foodId,code, num, callback) {
    	$.post('index.php?route=checkout/cart/update', {
            product_id: foodId,
            promotion_code:code,
            quantity: num
        }, function (json) {
              if (json['redirect']) {
				location = json['redirect'];
			  }
        
            if (json['success']) { // success
                var $dom = _.$navbar.find('.cart-num');
                $dom.html(+$dom.html() + parseInt(num)).cssAnimateOnce('pulse fast');
                callback && callback();
            }
        },'json');
    };
    
    /**
     * 加入购物车动画
     * @param product_id
     * @param total
     */
    _.addCartAnimation = function(obj) {
        var cart = _.$navbar.find('.cart-num');
 //       var imgtodrag = $(pro);<span class="col-red fz-14 bold">+1</span>
        if (obj) {
        	$('<span class="col-red fz-20">+1</span>').appendTo($('body'))
        	.css({
             //   'opacity': '1.0',
                    'position': 'absolute',
                    top: obj.offset().top,
                    left: obj.offset().left,
                    height:'25px',
        			width:'25px',
                    'z-index': '3000'
            })
                .appendTo($('body'))
                .animate({
                     'top': cart.offset().top,
                    'left': cart.offset().left,
                    //'width': '10px',
                    //'height': '10px'
            },   { duration:1000,
//            	   easing: 'easeInOutQuad',
            	   complete:  function (){
                      $(this).detach()
            	   }       
            });
        }
    };


    /**
     * 点赞
     */

    _.addFollow = function (foodId, callback){
        console.log(foodId);
    	$.ajax({
    		url: 'index.route=product/home/follow&product_id=' + foodId,
    		dataType: 'json',
    		success: function(data){//   	
    			console.log(data);
    			if(data['status']=='1'){
    				location.href="index.php?route=account/account";
    			}else if(data['status']=='2'){

    				 callback && callback();
	
    			}else{
    				alert(data['info']);
    			}
    		}
    	});
    	};
    
})(jQuery);


/**
 * 页面初始化
 *
 * 1. 所有.banner.banner-default类的元素进行轮播处理
 * 2. 键盘弹起时隐藏底部导航条
 * 3. 顶部搜索框处理
 * 4. 顶部消息按钮处理
 * 5. 添加到
 */
(function () {
	window.config=window.config||{};
	config.web_host=  '';//'http:\/\/localhost\/qncj';//http:\/\/www.qingniancaijun.com.cn
	config.local_host='';
	config.waiting='<img class="icon-spin animate-spin" style="width:1rem;" src="catalogm/view/theme/mobilev35/images/waiting.png"/>';


	
    $(document).ready(function () {
        // banner module
        // TODO: add swipe support
      $.fn.unslider && $('.banner.banner-default').each(function () {
            var $this = $(this);
            $this.unslider({
                autoplay:!$this.hasClass('static'),
                speed: 500,
                delay: 3000,
                dots: !$this.hasClass('no-dot'),
                swipe: !$this.hasClass('no-swipe'),
                fluid: true
            });
        });
     
        

        // 输入框弹起
        _.$body.on('focus', 'input[type=text], input[type=number], input[type=password], textarea', function () {
            _.$navbar.hide();
        }).on('blur', 'input[type=text], input[type=number], input[type=password], textarea', function () {
            _.$navbar.show();
        });

        // header
        // search module
        var $searchInputWrapper = _.$header.find('.search-input').hide(),
            $searchInput = $searchInputWrapper.find('input'),
            status = 0;
        _.$header.find('.search').on('click', function () {
            if (status === 0) {
                $searchInputWrapper.show().siblings().hide();
                $searchInput.focus();
                status = 1;
            } else {
                $searchInputWrapper.hide().siblings().show();
                status = 0;
            }
        });
        $searchInput.on('keyup', function (e) {
            if (e.keyCode === 13) {
                _.search(this.value);
            }
        });
        // message, TODO
        _.$header.find('.message').on(_.touchEnd, function () {

        });

        // add cart
 /*       _.$body.on(_.touchEnd, '.btn-add-cart', function () {
            var $this = $(this);
            _.addCart($this.data('id'),$this.data('code'),1, function () {
                $this.tipsBox('<span class="col-red fz-14 bold">+1</span>');
            });
        });
  */
        $('.btn-add-cart').bind('click', function () {
            var $this = $(this);
            _.addCart($this.data('id'),$this.data('code'),1, function () {
 //           	_.addCartAnimation($this);
                $this.tipsBox('<span class="col-red fz-14 bold">+1</span>');
            });
        });
    });
})(jQuery);