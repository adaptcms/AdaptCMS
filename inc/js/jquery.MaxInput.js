(function($) {
	
	$.fn.maxinput = function(options) {
		var opts = $.extend({}, $.fn.maxinput.defaults, options);
		return this.each(function() {
			$this = $(this);
			var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
			$.fn.limit(o,$this);			
		});
	};
	$.fn.maxinput.defaults = {
		limit 		: 140,
		position 	: 'topright',
		showtext  	: false,
		message     : 'characters left'
	};
	$.fn.limit = function(o,obj){
			if(!$('.jMax-text',obj).length){
				var _jMaxtext		= $(document.createElement('div')).addClass('jMax-text');
				_jMaxtext.html('<span>'+o.limit+'</span>');		
				
				if(o.position == 'topright')
					_jMaxtext.css('float','right');
				else	
					_jMaxtext.css('float','left');
				
				var _jMaxtextarea 	= '<input type="hidden"><br /><br />';		
				
				var _jMaxsubmit	= $(document.createElement('div')).addClass('submit').css('display','none');
				var _jMaxinput		= $(document.createElement('input')).attr('type','submit').attr('disabled','true').addClass('disabled').val('update');		
				
				_jMaxsubmit.append(_jMaxinput);
				
				if(o.position == 'bottomleft')
					obj.append(_jMaxtextarea).append(_jMaxtext).append(_jMaxsubmit);
				else
					obj.append(_jMaxtext).append(_jMaxtextarea).append(_jMaxsubmit);
				
				_jMaxinput.click(function(e){
					alert(jQuery.trim(_jMaxtextarea.val()));
				})
				if(o.showtext)
					$(document.createElement('span')).html(' '+o.message).insertAfter(_jMaxtext.find('span:first'));
					
			}
			var currlength = $('textarea',obj).val().length ;
			$('.jMax-text span:first',obj).html(o.limit - currlength);
			if((currlength > 0)&&(currlength <= o.limit))
				$('input',obj).removeAttr('disabled').removeClass('disabled').addClass('enabled');
			else
				$('input',obj).attr('disabled','true').removeClass('enabled').addClass('disabled');
			$('textarea',obj).one('keydown',function(){
				var d = function() { obj.maxinput(o) };
				timeout = setTimeout(d,1);
			});
	}
})(jQuery);