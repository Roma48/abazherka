// IE checker
function gkIsIE() {
	var myNav = navigator.userAgent.toLowerCase();
	return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}
// transparent tabs color change
function gkTabEventTrigger(i, j, id) {
	if(jQuery('#' + id).parent().hasClass('transparent-tabs')) {
		var mod_wrapper = jQuery('#' + id);
		var tabs = mod_wrapper.find('.gkTabsItem');
		var css_class = 'dark-tabs';
		
		if(
			jQuery(tabs[i]).find('.box').first().hasClass('color-bg') ||
			jQuery(tabs[i]).find('.box').first().hasClass('dark-bg') ||
			jQuery(tabs[i]).find('.box').first().hasClass('parallax-bg')
		) {
			css_class = '';
		}
		
		if(css_class !== '') {
			mod_wrapper.find('.gkTabsNav').first().addClass('dark-tabs');
		} else {
			mod_wrapper.find('.gkTabsNav').first().removeClass('dark-tabs');
		}
	}
}
// jQuery Cookie plugin
jQuery.cookie = function (key, value, options) {
    // key and at least value given, set cookie...
    if (arguments.length > 1 && String(value) !== "[object Object]") {
        options = jQuery.extend({}, options);
        if (value === null || value === undefined) {
            options.expires = -1;
        }
        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        value = String(value);

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }
    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};
//
jQuery(document).ready(function() {
	// Set tabs color if necessary
	jQuery('.gkTabsGK5').each(function(el, i) {
		el = jQuery(el);
		if(el.parent().hasClass('transparent-tabs')) {
			var css_class = 'dark-tabs';
			if(
				el.first('.gkTabsItem').find('.box').first().hasClass('color-bg') ||
				el.first('.gkTabsItem').find('.box').first().hasClass('dark-bg') ||
				el.first('.gkTabsItem').find('.box').first().hasClass('parallax-bg')
			) {
				css_class = '';
			}
			
			if(css_class !== '') {
				el.find('.gkTabsNav').first().addClass(css_class);
			}
		}
	});
	
	// Logo switching
	if(jQuery('#gkLogo').find('img').length) {
		var logo_img = jQuery('#gkLogo').find('img').first();
		
		if(logo_img.attr('data-dark') && jQuery(document.body).hasClass('dark-bg')) {
			logo_img.attr('src', logo_img.attr('data-dark'));
		}
	}
	
	// smooth anchor scrolling
	jQuery('a[href*="#"]').on('click', function (e) {
        e.preventDefault();
        if(this.hash !== '') {
            var target = jQuery(this.hash);
            
            if(this.hash !== '' && this.href.replace(this.hash, '') == window.location.href.replace(window.location.hash, '')) {    
                if(target.length && this.hash !== '#') {
                    jQuery('html, body').stop().animate({
                        'scrollTop': target.offset().top
                    }, 1000, 'swing', function () {
                        if(this.hash !== '#') {
                            window.location.hash = target.selector;
                        }
                    });
                } else if(this.hash !== '' && this.href.replace(this.hash, '') !== '') {
                	window.location.href = this.href;
                }
            } else if(this.hash !== '' && this.href.replace(this.hash, '') !== '') {
            	window.location.href = this.href;
            }
        }
    });
    
	// K2 font-size switcher fix
	if(jQuery('#fontIncrease').length > 0 && jQuery('.itemIntroText').length > 0) {
		jQuery('#fontIncrease').click(function() {
			jQuery('.itemIntroText').attr('class', 'itemIntroText largerFontSize');
		});
		
		jQuery('#fontDecrease').click( function() {
			jQuery('.itemIntroText').attr('class', 'itemIntroText smallerFontSize');
		});
	}
	
	// login popup
	if(jQuery('a.gk-login-popup').length && jQuery('#gk-login-popup').length) {
		var popup = jQuery('#gk-login-popup');
		var overlay = jQuery('#gk-login-popup-overlay');
		var close = jQuery('#gk-login-popup-close');
		
		jQuery('a.gk-login-popup').click(function(e) {
			e.preventDefault();
			
			overlay.css('display', 'block');
			popup.css('display', 'block');
			
			setTimeout(function() {
				overlay.addClass('gk-active');
				popup.addClass('gk-active');
			}, 50);
		});
		
		jQuery(overlay).add(close).click(function() {
			overlay.removeClass('gk-active');
			popup.removeClass('gk-active');
			
			setTimeout(function() {
				overlay.css('display', 'none');
				popup.css('display', 'none');
			}, 650);
		});
	}
	
	// Testimonials
	var testimonials = jQuery('.gk-testimonials');
	
	if(testimonials.length > 0) {
		testimonials.each(function(i, wrapper) {
			wrapper = jQuery(wrapper);
			wrapper.attr('data-block', 'false');
			var amount = wrapper.data('amount');
			var testimonial_pagination = jQuery('<ul>', { class: 'gk-testimonials-pagination' });
			var quotes = wrapper.find('blockquote');
			var current_page = 0;
			var sliding_wrapper = wrapper.find('div div');
			
			for(var j = 0; j < amount; j++) {
				var titem = '<li' + (j === 0 ? ' class="active"' : '') + '>' + (j+1) + '</li>';
				testimonial_pagination.html(testimonial_pagination.html() + titem);
			}

			testimonial_pagination.appendTo(wrapper);
			var pages = testimonial_pagination.find('li');
			// hide quotes
			quotes.each(function(i, quote) {
				if(i > 0) {
					jQuery(quote).addClass('hidden');
				}
			});
			// navigation
			pages.each(function(i, page) {
				page = jQuery(page);
				page.click(function() {
					jQuery(quotes[current_page]).addClass('hidden');
					current_page = i;
					jQuery(quotes[current_page]).removeClass('hidden');
					pages.removeClass('active');
					jQuery(pages[current_page]).addClass('active');
					sliding_wrapper.css('margin-left', -1 * (current_page * 100) + "%");
					wrapper.attr('data-block', 'true');	
				});
			});
			
			// auto-animation
			setTimeout(function() {
				testimonials_auto_animate();
			}, 3000);
			
			function testimonials_auto_animate() {
				if(wrapper.attr('data-block') == 'false') {
					jQuery(quotes[current_page]).addClass('hidden');
					current_page = current_page + 1;
				
					if(current_page >= pages.length) {
						current_page = 0;
					}
				
					jQuery(quotes[current_page]).removeClass('hidden');
					pages.removeClass('active');
					jQuery(pages[current_page]).addClass('active');
					sliding_wrapper.css('margin-left', -1 * (current_page * 100) + "%");
				} else {
					wrapper.attr('data-block', 'false');
				}
				
				setTimeout(function() {
					testimonials_auto_animate();
				}, 5000);
			}
		});
	}

	// Scroll effects 
	var frontpage_header = jQuery('#gkHeader');
	var header_mod = jQuery('#gkHeaderMod');
	var frontpage_img = jQuery('#gkHeaderMod .parallax-img');
	
	if(
		frontpage_header &&
		frontpage_img && 
		jQuery(window).width() > 720
	) {
		jQuery(window).scroll(function() {	
			var win_scroll = jQuery(window).scrollTop();
			var header_height = frontpage_header.height();
			
			if(win_scroll < header_height) {
				animate_header(win_scroll, header_height);	
			}
		});
		
		var animate_header = function(win_scroll, header_height) {
			var progress = (win_scroll / header_height);
			frontpage_img.css('top', 50 + (50 * progress) + '%');
		};
	}
	
	if(
		frontpage_header &&
		frontpage_img
	) {
		var adjust_header = function() {
			var mod_h = header_mod.outerHeight();
			var img_h = frontpage_img.outerHeight();
			var mod_w = header_mod.outerWidth();
			var img_w = frontpage_img.outerWidth();
			
			if(img_h < mod_h) {
				frontpage_img.attr('class', 'parallax-img gk-vertical');
			} else if(img_w < mod_w) {
				frontpage_img.attr('class', 'parallax-img gk-horizontal');
			}
		};
		
		adjust_header();
		
		jQuery(window).load(function() {
			adjust_header();
		});
		
		jQuery(window).resize(function() {
			adjust_header();
		});
	}	

	// Menu text hiding
	var menu_text = jQuery('#gk-mobile-menu-text');
	var menu_text_w = menu_text.outerWidth() + "px";
	var menu_wrap = menu_text.parent();
	var main_nav = jQuery('#gkHeaderNav');
	menu_text.css('width', menu_text_w);
	var win = jQuery(window);
	
	if(!main_nav.hasClass('gk-fixed')) {
		function menu_scroll() {
			var y = win.scrollTop();
			
			menu_wrap.css('top', (y >= 0 ? y : 0) + "px");
			
			if(y <= 50) {
				if(main_nav.hasClass('inactive')) {
					main_nav.removeClass('inactive');
				}
			} else {
				if(!main_nav.hasClass('inactive')) {
					main_nav.addClass('inactive');
				}
			}
		}
		
		jQuery(window).scroll(menu_scroll);	
	}
	
	if(jQuery('body').hasClass('dark-bg')) {
		var header = false;
		if(jQuery('.blog-page').length) {
			header = jQuery('.blog-page').children('.header');
		} else if(jQuery('.single-page').length) {
			header = jQuery('.single-page').children('.header');
		} else if(jQuery('#gkHeaderMod').length) {
			header = jQuery('#gkHeaderMod');
		}
		
		win.scroll(header_menu_scroll);
	}
	
	function header_menu_scroll() {
		var h = header ? header.outerHeight() : 0;
		var y = win.scrollTop();
		
		if(y <= h) {
			if(main_nav.hasClass('show-dark')) {
				main_nav.removeClass('show-dark');
			}
		} else {
			if(!main_nav.hasClass('show-dark')) {
				main_nav.addClass('show-dark');
			}
		}
	}
	
	// Category header scroll effect	
	if(jQuery('.blog-page > .header > img').length) {
		var header = jQuery('.blog-page').children('.header');
		var img = header.children('img');
		
		win.scroll(header_scroll);
	}
	
	// Article header scroll effect	
	if(jQuery('.single-page > .header > img').length) {
		var header = jQuery('.single-page').children('.header');
		var img = header.children('img');
		
		win.scroll(header_scroll);
	}
	
	function header_scroll() {	
		var h = header.outerHeight();
		var y = win.scrollTop();
		if(y <= h) {
			var progress = (y / h);
			img.css('top', 50 + (50 * progress) + '%');
		}
	}
	
	// Mouse icon animation
	if(jQuery('.mouse-icon').length) {
		var icons = jQuery('.mouse-icon');
		
		setTimeout(function() {
			mouse_icon_animation()
		}, 1000);
		
		function mouse_icon_animation() {
			icons.addClass('animate');
			
			setTimeout(mouse_icon_remove, 160);
			setTimeout(mouse_icon_add, 320);
			setTimeout(mouse_icon_remove, 480);
			setTimeout(mouse_icon_animation, 2500);
		}	
		
		function mouse_icon_add() {
			icons.addClass('animate');
		}
		
		function mouse_icon_remove() {
			icons.removeClass('animate');
		}
	}
	
	// Social icons
	if(jQuery('.gk-social-icons').length) {
		var social_icons = jQuery('.gk-social-icons');
		social_icons.click(function() {
			var item = jQuery(this);
			
			if(!item.attr('data-click-block') || item.attr('data-click-block') == '') {
				if(item.hasClass('clicked')) {
					item.removeClass('show');
					setTimeout(function() {
						item.removeClass('clicked');
						item.attr('data-click-block', '');
					}, 350);		
				} else {
					item.addClass('clicked');
					item.attr('data-click-block', 'true');
					
					setTimeout(function() {
						item.addClass('show');
					}, 50);
					
					setTimeout(function() {
						item.attr('data-click-block', '');
					}, 300);
				}
			}
		});
	}
	
	// Video link
	if(jQuery('.gk-video-link').length) {
		jQuery('.gk-video-link').click(function(e) {
			e.preventDefault();
			var link = jQuery(this);
			
			var popup = jQuery('<div id="gk-video-overlay"><a href="#close">&times;</a><iframe src="'+link.attr('data-url')+'" width="'+link.attr('data-width')+'" height="'+link.attr('data-height')+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>');
			
			link.parent().append(popup);
			var video_element = popup.find('iframe');
			setTimeout(function() {
				video_element.addClass('loaded');
			}, 1000);
	
			popup.addClass('show');
			
			setTimeout(function() {
				popup.addClass('open');
			}, 50);
			
			popup.find('a[href="#close"]').click(function(e) {
				e.preventDefault();
				
				popup.removeClass('open');
				
				setTimeout(function() {
					popup.removeClass('show');
					popup.remove();
				}, 350);
			});
		});
	}
	
	// Fix for the containers width
	if(jQuery('#gkBg').outerHeight() < jQuery(window).outerHeight()) {
		jQuery('#gkBg').css('min-height', jQuery(window).outerHeight() + "px");
	}
	
	jQuery(window).resize(function() {
		if(jQuery('#gkBg').outerHeight() < jQuery(window).outerHeight()) {
			jQuery('#gkBg').css('min-height', jQuery(window).outerHeight() + "px");
		}
	});
	
	// PhotoSwipe script
	var initPhotoSwipeFromDOM = function(gallerySelector) {
	    // parse slide data (url, title, size ...) from DOM elements 
	    // (children of gallerySelector)
	    var parseThumbnailElements = function(el) {
	        var thumbElements = jQuery(el).find('a'),
	            numNodes = thumbElements.length,
	            items = [],
	            figureEl,
	            linkEl,
	            size,
	            item;
	
	        thumbElements.each(function(i, link) {
	            link = jQuery(link);
	            size = link.attr('data-size').split('x');
	
	            // create slide object
	            item = {
	                src: link.attr('href'),
	                w: parseInt(size[0], 10),
	                h: parseInt(size[1], 10)
	            };
	
	            if(link.attr('data-title') || link.attr('data-desc')) {
	                item.title = '';
	                
	                if(link.attr('data-title')) {
	                	item.title += '<h3>' + link.attr('data-title') + '</h3>';
	                }
	                
	                if(link.attr('data-desc')) {
	                	item.title += '<span>' + link.attr('data-desc') + '</span>';
	                }
	            }
	
	            item.msrc = link.find('img').first().attr('src');
	            item.el = link; // save link to element for getThumbBoundsFn
	            // Detect the data-order attribute
	            if(
	            	jQuery(window).outerWidth() > jQuery(document.body).attr('data-mobile-width') &&
	            	link.attr('data-order')
	            ) {
	            	items[parseInt(link.attr('data-order'), 10) - 1] = item;
	            } else {
	            	items.push(item);
	            }
	        });
	
	        return items;
	    };
	
	    // find nearest parent element
	    var closest = function closest(el, fn) {
	        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
	    };
	
	    // triggers when user clicks on thumbnail
	    var onThumbnailsClick = function(e) {
	        e.preventDefault();	
	        // find root element of slide
	        var clickedListItem = closest(e.target, function(el) {
	            return (el.tagName && el.tagName.toUpperCase() === 'A');
	        });
	
	        if(!clickedListItem) {
	            return;
	        }
	
	        // find index of clicked item by looping through all child nodes
	        // alternatively, you may define index via data- attribute
	        var clickedGallery = jQuery(clickedListItem).parents('.gk-gallery'),
	            childNodes = clickedGallery.find('a'),
	            numChildNodes = childNodes.length,
	            nodeIndex = 0,
	            index;
	
	        for (var i = 0; i < numChildNodes; i++) {
	            if(childNodes[i] === clickedListItem) {
	                index = nodeIndex;
	                break;
	            }
	            nodeIndex++;
	        }

			if(
				jQuery(window).outerWidth() > jQuery(document.body).attr('data-mobile-width') &&
				clickedListItem.hasAttribute('data-order')
			) {
				index = parseInt(clickedListItem.getAttribute('data-order'), 10) - 1;
			}

	        if(index >= 0) {
	            // open PhotoSwipe if valid index found
	            openPhotoSwipe( index, clickedGallery );
	        }
	        return false;
	    };
	
	    // parse picture index and gallery index from URL (#&pid=1&gid=2)
	    var photoswipeParseHash = function() {
	        var hash = window.location.hash.substring(1),
	        params = {};
	
	        if(hash.length < 5) {
	            return params;
	        }
	
	        var vars = hash.split('&');
	        for (var i = 0; i < vars.length; i++) {
	            if(!vars[i]) {
	                continue;
	            }
	            var pair = vars[i].split('=');  
	            if(pair.length < 2) {
	                continue;
	            }           
	            params[pair[0]] = pair[1];
	        }
	
	        if(params.gid) {
	            params.gid = parseInt(params.gid, 10);
	        }
	
	        if(!params.hasOwnProperty('pid')) {
	            return params;
	        }
	        params.pid = parseInt(params.pid, 10);
	        return params;
	    };
	
	    var openPhotoSwipe = function(index, galleryElement, disableAnimation) {
	        var pswpElement = document.querySelectorAll('.pswp')[0],
	            gallery,
	            options,
	            items;
	
	        items = parseThumbnailElements(galleryElement);
	
	        // define options (if needed)
	        options = {
	            index: index,
	
	            // define gallery index (for URL)
	            galleryUID: jQuery(galleryElement).attr('data-pswp-uid'),
	
	            getThumbBoundsFn: function(index) {
	                // See Options -> getThumbBoundsFn section of documentation for more info
	                var thumbnail = items[index].el.find('img').first(), // find thumbnail
	                    rect = {
	                    	"left": thumbnail.offset().left, 
	                    	"top": thumbnail.offset().top,
	                    	"width": thumbnail.outerWidth()
	                    }; 
	                return {x:rect.left, y:rect.top, w:rect.width};
	            }
	
	        };
	
	        if(disableAnimation) {
	            options.showAnimationDuration = 0;
	        }
	
	        // Pass data to PhotoSwipe and initialize it
	        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
	        gallery.init();
	    };
	
	    // loop through all gallery elements and bind events
	    var galleryElements = jQuery(gallerySelector);
	
	    for(var i = 0, l = galleryElements.length; i < l; i++) {
	        galleryElements[i].setAttribute('data-pswp-uid', i+1);
	        galleryElements[i].onclick = onThumbnailsClick;
	    }
	
	    // Parse URL and open gallery if it contains #&pid=3&gid=1
	    var hashData = photoswipeParseHash();
	    if(hashData.pid > 0 && hashData.gid > 0) {
	        openPhotoSwipe( hashData.pid - 1 ,  galleryElements[ hashData.gid - 1 ], true );
	    }
	};
	
	// execute above function if the gallery elements exists
	if(jQuery('.gk-gallery').length) {
		initPhotoSwipeFromDOM(jQuery('.gk-gallery'));
	}
	
	// Newsletter popup
	var newsletter_submit = jQuery('#gk-newsletter-submit');
	
	if(newsletter_submit.length) {
		newsletter_submit.click(function(e) {

			var captchaField = grecaptcha.getResponse();
				if(captchaField.length < 1){
					e.preventDefault();
					return false;
				} else {
					jQuery.cookie('gk-newsletter-popup', 1, { expires: 30, path: '/' });
				}
		});
		
		jQuery('#gk-newsletter-popup-close').click(function(e) {
			e.preventDefault();
			
			jQuery.cookie('gk-newsletter-popup', 1, { expires: 30, path: '/' });
			jQuery('#gk-newsletter-popup').addClass('hidden-popup');
		});
	}
});

// Newsletter display
jQuery(window).load(function() {
	var newsletter = jQuery('#gk-newsletter-popup');
	
	if(newsletter.length) {
		switch (newsletter.attr('data-display')) {
		  	case 'direct':
				setTimeout(function() {
					newsletter.removeClass('hidden-popup');
				}, 1000);
				break;
			case 'after_time':
				setTimeout(function() {
					newsletter.removeClass('hidden-popup');
				}, newsletter.attr('data-time') * 1000);
				break;
			case 'after_scroll': 
				var win = jQuery(window);
				var destination = newsletter.attr('data-scroll');
				
				var newsletter_scroll = function() {
					if(win.scrollTop() >= destination) {
						newsletter.removeClass('hidden-popup');
						jQuery(window).off('scroll', newsletter_scroll);
					}
				};
				
				jQuery(window).on('scroll', newsletter_scroll);
				break;
		}
	}
});

// New parallax engine
jQuery(document).ready(function() {
	if(jQuery(document.body).attr('data-parallax') == 'true' && jQuery(window).width() > 640) {
		var parallax_containers = [];
		var parallax_heights = [];
		var parallax_tops = [];
		var window_h = jQuery(window).outerHeight();
		var parallax_progress = [];
		var parallax_layers = [];
		
		jQuery('.parallax-bg').each(function(i, parallax_wrap) {
			parallax_wrap = jQuery(parallax_wrap);
			// check the wrapper
			if(!parallax_wrap.children('.box-wrap').length) {
				parallax_wrap.html('<div class="box-wrap">' + parallax_wrap.html() + '</div>');
			}
			// don't add position: relative to the tabs content
			if(!parallax_wrap.parent().hasClass('gkTabsItem')) {
				parallax_wrap.parent().css('position', 'relative');
			}
			
			var content = parallax_wrap.children('.box-wrap');
			var parallax_layer = jQuery('<div class="parallax-bg-layer"></div>');
			parallax_layer.css('background-image', parallax_wrap.css('background-image'));
			parallax_wrap.css({
				'background-image': '',
				'z-index': '1'
			});
			parallax_layer.css({
				'width': '100%',
				'height': '130%',
				'position': 'absolute',
				'z-index': '0',
				'top': '0',
				'background-size': 'cover'
			});
			parallax_layer.appendTo(parallax_wrap.parent());
			parallax_containers.push(parallax_wrap);
		});
		
		jQuery(parallax_containers).each(function(i, container) {
			container = jQuery(container);
			
			parallax_heights.push(container.outerHeight());
			parallax_tops.push(container.offset().top);
			parallax_layers.push(jQuery(container.parent().find('.parallax-bg-layer')));
		});
		
		jQuery(window).resize(function() {
			parallax_heights = [];
			parallax_tops = [];
			
			jQuery(parallax_containers).each(function(i, container) {
				container = jQuery(container);
				parallax_heights.push(container.outerHeight());
				parallax_tops.push(container.offset().top);
			});
		});
		
		jQuery(window).scroll(function() {
			var scroll = jQuery(document).scrollTop();
			
			jQuery(parallax_tops).each(function(i, top) {
				if(
					scroll >= top - window_h && 
					scroll <= top + parallax_heights[i]
				) {
					var progress = (scroll - (top + window_h)) / (top + parallax_heights[i]);
					jQuery(parallax_layers[i]).css('top', parallax_heights[i] * progress + 'px');
				}
			});
		});
		
		var scroll = jQuery(document).scrollTop();
		
		jQuery(parallax_tops).each(function(i, top) {
			if(
				scroll >= top - window_h && 
				scroll <= top + parallax_heights[i]
			) {
				var progress = (scroll - (top + window_h)) / (top + parallax_heights[i]);
				jQuery(parallax_layers[i]).css('top', parallax_heights[i] * progress + 'px');
			}
		});
	}
});

// Page preloader
var ignore_onbeforeunload = false;

jQuery(document).ready(function() {
	jQuery('a').on('click',function(){
		if(jQuery(this).attr('href').substr(0,7) === 'mailto:') {
	    	ignore_onbeforeunload = true;
	    } else {
	    	ignore_onbeforeunload = false;
	    }
	});
});

jQuery(window).load(function() {
	if(jQuery('#gk-page-preloader').length > 0) {
		var preloader = jQuery('#gk-page-preloader');
		setTimeout(function() {
			preloader.addClass('gk-to-hide');
			
			setTimeout(function() {
				preloader.addClass('gk-hidden');
			}, 500);
		}, 500);
	}
});

jQuery(window).on('beforeunload', function() {
	if(!ignore_onbeforeunload && jQuery('#gk-page-preloader').length > 0) {
		var preloader = jQuery('#gk-page-preloader');
		preloader.removeClass('gk-hidden');
		
		setTimeout(function() {
			preloader.removeClass('gk-to-hide');
		}, 25);
	}
});
