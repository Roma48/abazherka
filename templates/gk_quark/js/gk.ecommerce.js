(function($) {
	// Header resize
	var gk_quark_header_resize = function() {
		if($(document.body).hasClass('fullscreen')) {
			var header = $('#gkHeaderMod');
			
			if(header.length) {
				header.css('min-height', $(window).height() + "px");
				header.css('max-height', $(window).height() + "px");
			}
		}
	}
	
	// Footer resize
	var gk_quark_footer_resize = function() {
		var footer = $('#gkFooter');
		
		if(footer.length) {
			var window_h = $(window).height();
			var backup_h = footer.css('min-height');
			footer.css('min-height', '0');
			
			var footer_h = footer.offset().top + footer.outerHeight();
			
			if(backup_h) {
				footer.css('min-height', backup_h);
			}
			
			if(window_h > footer_h) {
				footer.css('min-height', (window_h - footer.offset().top) + "px");
			} else {
				footer.css('min-height', '0');
			}
		}
	};
	
	// Calling functions for the resize
	$(document).ready(function() {
		gk_quark_footer_resize();
		gk_quark_header_resize();	
	});
	
	$(window).on('load resize', function() {
		gk_quark_footer_resize();
		gk_quark_header_resize();
		
		if($('#gkBg').outerHeight() < $(window).outerHeight()) {
			$('#gkBg').css('min-height', $(window).outerHeight() + "px");
		}	
	});
	
	// News Show Pro GK5 quark-gallery suffix
	$(document).ready(function() {
		var quark_galleries = $(document).find('.quark-gallery');
		
		if(quark_galleries.length) {
			quark_galleries.each(function(i, mod) {
				mod = $(mod);
				var wrap = mod.find('.nspArts').first();
				wrap.addClass('no-hover');
				
				wrap.mouseenter(function() {
					wrap.removeClass('no-hover');	
				});
				
				wrap.mouseleave(function() {
					wrap.addClass('no-hover');	
				});
			});	
		}
	});
	
	// Big photo buttons
	$(document).ready(function() {
		var big_photo = $('.big-photo');
		var svg_preloader = '<svg version="1.1" viewBox="0 0 92 92" class="gk-circle-loader"><circle class="gk-circle-loader-path" cx="46" cy="46" r="43"></circle><circle class="gk-circle-loader-bar" cx="46" cy="46" r="43"></circle></svg>';
		
		big_photo.each(function(i, photo) {
			$(photo).parent().parent().parent().css('overflow', 'visible');
			
			
		});
	
		var big_photo_buttons = $('.big-photo-button');
	
		if(big_photo_buttons.length) {
			var opened = false;
			
			big_photo_buttons.each(function(i, btn) {
				btn = $(btn);
				
				btn.append(svg_preloader);
				
				btn.click(function(e) {
					e.stopPropagation();
					var popup = btn.find('.big-photo-button-popup');
					var progress_bar = btn.find('.gk-circle-loader').first();
					
					if(btn.hasClass('clicked')) {	
						popup.removeClass('opened');
						
						setTimeout(function() {
							btn.removeClass('clicked');
							btn.removeClass('loaded');	
							progress_bar[0].getElementsByTagName('circle')[1].setAttribute('class', 'gk-circle-loader-bar');
						}, 300);
						
						opened = false;
					} else {
						btn.addClass('clicked');
					
						setTimeout(function() {
							progress_bar[0].getElementsByTagName('circle')[1].setAttribute('class', 'gk-circle-loader-bar loading');
						}, 300);
						
						setTimeout(function() {
							btn.addClass('loaded');
							
							setTimeout(function() {
								popup.addClass('opened');
								progress_bar.removeClass('loading');
							}, 50);
							
							if(opened !== false) {
								$(big_photo_buttons[opened]).trigger('click');
							}
							
							opened = i;
						}, 1000);
					}
				});
			});
			
			$(document.body).click(function() {
				if(opened !== false) {
					$(big_photo_buttons[opened]).trigger('click');
				}
			});
		}
	});
		
	// Cart popup
	$(document).ready(function() {
		if($('#gkPopupCart').length > 0) { 
			var btn = $('#gk-cart-bt');
			popup_cart = $('#gkPopupCar');
			popup_cart.css('display', 'block');
			popup_cart_h = popup_cart.find('.gkPopupWrap').height();
			popup_cart.css({'opacity': 0, 'margin-top': -150 }); 
			var wait_for_results = true;
			var wait = false;
			var opened_popup = false;
			
			btn.click(function(e) {
				e.preventDefault();
				e.stopPropagation();        
				
				if(!wait) {
					$.ajax({
						url: $GK_URL + 'index.php?tmpl=cart&tkn=' + Math.floor((Math.random() * 100000000) + 1),
						beforeSend: function() {
							btn.addClass('loading');
							wait = true;
						},
						complete: function() {
							var timer = setInterval(function() {
								if(!wait_for_results) {
									popup_cart.css('display', 'block');
									popup_cart.css('margin-top', 0);
									popup_cart.animate({'opacity': 1}, 500);
									opened_popup = 'cart';
									wait_for_results = true;
									wait = false;
									clearInterval(timer);
									btn.removeClass('loading');
								}
							},200);
						},
						success: function(data,textStatus) {
							$('#gkAjaxCart').html(data);
							popup_cart.css('display', 'block');
							popup_cart.css('margin-top', 0);
							popup_cart.animate({'opacity': 1}, 500);
							wait_for_results = false;
							wait = false;
						}
					});
				}
			});
			
			if(btn.length > 0) {
				var gkCartDataRequest = function() {
					jQuery.ajax({
						url: $GK_URL + 'index.php?tmpl=json&tkn=' + Math.floor((Math.random() * 100000000) + 1),
						success: function(data,textStatus) {
							if(parseInt(data, 10) > 0) {
								btn.addClass('full-cart');	
							} else {
								btn.removeClass('full-cart');
							}
						}
					});
				} 
				
				gkCartDataRequest();
				
				$("body").on("updateVirtueMartCartModule", function(e) {
					gkCartDataRequest();
				});
				
				cursor_inside_popup_cart = false;
			
				$('#gkPopupCart').mouseenter(function() {
					cursor_inside_popup_cart = true;	
				});
			
				$('#gkPopupCart').mouseleave(function() {
					cursor_inside_popup_cart = false;	
				});
			
				$('body').click(function() {
					if(opened_popup == 'cart' && !cursor_inside_popup_cart) {
						popup_cart.animate({ 'opacity': 0}, 500);
						opened_popup = false;
						setTimeout(function() {
							popup_cart.css('display', 'none');
						}, 500);
					}
				});
			}
		}
	});
})(jQuery);