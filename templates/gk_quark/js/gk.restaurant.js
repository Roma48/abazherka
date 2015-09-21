(function($) {
	$(document).ready(function() {
		// Form validation
		$('.gkReservationForm').each(function(i, set) {
			set = $(set);
			set.each(function(i, form) {
				form = $(form);
				var inputs = form.find('.required');
				var submit = form.find('.submit');
				
				inputs.each(function(i, input) {
					input = $(input);
					input.focus(function() {
						if(input.hasClass('invalid-input')) {
							input.removeClass('invalid-input');
						}
					});
				});
				
				submit.click(function(e) {
					e.preventDefault();
					e.stopPropagation();
					
					var valid = true;
					
					inputs.each(function(i, input) {
						input = $(input);
						if(input.val() === '') {
							valid = false;
							input.addClass('invalid-input');
						}
					});
					
					if(valid) {
						submit.parents('form').submit();
					}
				});
			});
		});
	});
	
	$(window).load(function() {
		// Video bg
		$('.video-bg').each(function(i, wrap) {
			wrap = $(wrap);
			var vid = wrap.find('video');
			wrap.css('background-image', 'url(' + vid.attr('poster') + ')');
			vid.attr('poster', '');
			vid.css('width', '');
			var container_h = wrap.outerHeight();
			
			if(vid.outerHeight() < container_h) {
				var w = wrap.outerWidth();
				vid.css('height', container_h);
				
				if(vid.outerWidth() < wrap.outerWidth()) {
					vid.css('height', 'auto');
					vid.css('width', '100%');
				}
			}
		});
				
		$(window).resize(function() {
			$('.video-bg').each(function(i, wrap) {
				wrap = $(wrap);
				var vid = wrap.find('video');
				var container_h = wrap.outerHeight();
				
				if(vid.outerHeight() < container_h) {
					var w = wrap.outerWidth();
					vid.css('height', container_h);
					
					if(vid.outerWidth() < wrap.outerWidth()) {
						vid.css('height', 'auto');
						vid.css('width', '100%');
					}
				}
			});
		});
	});
})(jQuery);