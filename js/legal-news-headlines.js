/**
 * legal-news-headlines.js - Javascript for the widget.
 *
 * @package Legal News Headlines
 */

jQuery(document).ready(function($) {
    // $() will work as an alias for jQuery() inside of this function

	/*
	* vertical news ticker
	* Tadas Juozapaitis ( kasp3rito [eta] gmail (dot) com )
	* http://www.jugbit.com/jquery-vticker-vertical-news-ticker/
	*/
	(function($){
	$.fn.vTicker = function(options) {
		var defaults = {
			speed: 700,
			pause: 4000,
			showItems: 3,
			animation: '',
			mousePause: true,
			isPaused: false,
			direction: 'up',
			height: 0
		};

		var options = $.extend(defaults, options);

		moveUp = function(obj2, height, options){
			if(options.isPaused)
				return;
			
			var obj = obj2.children('ul');
			
	    	var clone = obj.children('li:first').clone(true);
			
			if(options.height > 0)
			{
				height = obj.children('li:first').height();
			}		
			
	    	obj.animate({top: '-=' + height + 'px'}, options.speed, function() {
	        	$(this).children('li:first').remove();
	        	$(this).css('top', '0px');
	        });
			
			if(options.animation == 'fade')
			{
				obj.children('li:first').fadeOut(options.speed);
				if(options.height == 0)
				{
				obj.children('li:eq(' + options.showItems + ')').hide().fadeIn(options.speed).show();
				}
			}

	    	clone.appendTo(obj);
		};
		
		moveDown = function(obj2, height, options){
			if(options.isPaused)
				return;
			
			var obj = obj2.children('ul');
			
	    	var clone = obj.children('li:last').clone(true);
			
			if(options.height > 0)
			{
				height = obj.children('li:first').height();
			}
			
			obj.css('top', '-' + height + 'px')
				.prepend(clone);
				
	    	obj.animate({top: 0}, options.speed, function() {
	        	$(this).children('li:last').remove();
	        });
			
			if(options.animation == 'fade')
			{
				if(options.height == 0)
				{
					obj.children('li:eq(' + options.showItems + ')').fadeOut(options.speed);
				}
				obj.children('li:first').hide().fadeIn(options.speed).show();
			}
		};
		
		return this.each(function() {
			var obj = $(this);
			var maxHeight = 0;

			obj.css({overflow: 'hidden', position: 'relative'})
				.children('ul').css({position: 'absolute', margin: 0, padding: 0})
				.children('li').css({margin: 0, padding: 0});

			if(options.height == 0)
			{
				obj.children('ul').children('li').each(function(){
					if($(this).height() > maxHeight)
					{
						maxHeight = $(this).height();
					}
				});

				obj.children('ul').children('li').each(function(){
					$(this).height(maxHeight);
				});

				obj.height(maxHeight * options.showItems);
			}
			else
			{
				obj.height(options.height);
			}
			
	    	var interval = setInterval(function(){ 
				if(options.direction == 'up')
				{ 
					moveUp(obj, maxHeight, options); 
				}
				else
				{ 
					moveDown(obj, maxHeight, options); 
				} 
			}, options.pause);
			
			if(options.mousePause)
			{
				obj.bind("mouseenter",function(){
					options.isPaused = true;
				}).bind("mouseleave",function(){
					options.isPaused = false;
				});
			}
		});
	};
	})(jQuery);

	// find our widgets and apply the scroller addition, above...
	// we force the height setting to be the current height of the box. using other settings 
	// is possible, but it adds spaces between list items in order to make them regular
	$('div.legal_news_headlines_list_container').each(function() {
		$(this).vTicker({height: $(this).height()});
	});

});