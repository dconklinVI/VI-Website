$(document).ready(function() {

	// Scroll past URL bar on iPhones
	/mobi/i.test(navigator.userAgent) && !location.hash && setTimeout(function () {
		if (!pageYOffset) window.scrollTo(0, 132);
	}, 1000);

	// Make icons work in a single click in Mobile Safari
	//$('.menu .icon').on('click', function(e) {
		//e.preventDefault();
		//console.log($(this).attr('href'));
		//window.location = $(this).attr('href');
	//});

	// Open external links in new Window/Tab
	$("a[href^='http']").not("[href*='" + window.location.host + "']").attr('target','_blank');
	$("a[href$='.pdf']").attr('target','_blank');

	// Toggle Menu Hide on Menu Item Click
	$('#menu a').click(function(){
		var jQuerycheckbox = $('#menu').find(':checkbox');
		if(jQuerycheckbox.prop('checked')) {
			jQuerycheckbox.prop('checked', !jQuerycheckbox[0].checked);
		}
	});

	// Add Link to Footer Blog Widget Title
	$("#footer .c33:nth-child(2) h3").html('<a href="/engage/the-vi-blog/">From the Blog</a>');

	// Style select fields
	$('#sidebar select').customSelect();

	// Add helper class to menus for dropdown arrow
	$('.sub-menu').parent().addClass('has-children');

	// Wrap Subpage nav icons
	$('.subpages li').each(function(){
		var classList = $(this).attr('class');
		$('a', this).addClass(classList).wrapInner('<span>');
		$(this).removeClass();
	});
	
	// Sharrre
	$('#social-facebook').sharrre({
		share: {
			facebook: true
		},
		enableHover: false,
		enableTracking: true,
		click: function(api, options){
			api.simulateClick();
			api.openPopup('facebook');
		}
	});
	$('#social-twitter').sharrre({
		share: {
			twitter: true
		},
		enableHover: false,
		enableTracking: true,
		buttons: { twitter: {via: 'thevibrand'}},
		click: function(api, options){
			api.simulateClick();
			api.openPopup('twitter');
		}
	});
	$('#social-googleplus').sharrre({
		share: {
			googlePlus: true
		},
		urlCurl: '/wp-content/themes/pilt/sharrre.php',
		enableHover: false,
		enableTracking: true,
		click: function(api, options){
			api.simulateClick();
			api.openPopup('googlePlus');
		}
	});
	$('#social-stumbleupon').sharrre({
		share: {
			stumbleupon: true
		},
		urlCurl: '/wp-content/themes/pilt/sharrre.php',
		enableHover: false,
		enableTracking: true,
		click: function(api, options){
			api.simulateClick();
			api.openPopup('stumbleupon');
		}
	});

	// Lightboxes
	$(".iframe").fancybox({
		type		: 'iframe',
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '95%',
		height		: '95%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'fade',
		closeEffect	: 'fade'
	});

	$(".fancybox").fancybox({
		type		: 'image',
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: true,
		title		: '',
		width		: '95%',
		height		: '95%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic'
	});

	$(".slide-desc .video").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: true,
		width		: '95%',
		height		: '95%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic',
		helpers : {
			media : {}
		}
	});

	$(".short-gallery a").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: true,
		width		: '95%',
		height		: '95%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic',
		helpers : {
			title : {
				type : 'inside'
			},
			media : {}
		}
	});

	// Add Styles to Comment With Active Reply
	if (document.getElementById('comment_parent')) {
		var commentnum = $("#comment_parent").val();
		$("#comment-"+commentnum+">div>.reply>.comment-reply-link").addClass("active-reply");
	}

	// Replace ReCaptcha images with black images
	if (document.getElementById('recaptcha_table')) {
		//$('#recaptcha_reload').prop('src', 'http://www.google.com/recaptcha/api/img/blackglass/refresh.gif');
	}

	// Footer Carousel Tabs
	if (document.getElementById('career-list')) {
		var n = $("#career-list .career-icons a").length;
		if (n>1) {
			$('#career-list .career-descriptions .desc').hide();
			$('#career-list .career-descriptions .desc:first').show();
			$('#career-list .career-icons a:first').addClass('active');

			$('#career-list .career-icons a').hover(function() {
				$('#career-list .career-icons a').removeClass('active');
				$(this).addClass('active');
				var currentTab = $(this).prop('rel');
				$('#career-list .career-descriptions .desc').hide();
				$('#'+currentTab).show();
				//$(currentTab+' .carousel').css('width', '200px');
				return false;
			});
		}
	}

	// Fade
	$('.fadeThis').append('<span class="hover"></span>').each(function () {
		var $span = $('> span.hover', this).css('opacity', 0);
		$(this).hover(function () {
			$span.stop().fadeTo(300, 1);
		}, function () {
			$span.stop().fadeTo(300, 0);
		});
	});

	$('.gray').each(function () {
		var $span = $('> div.hover', this).css('opacity', 0);
		$(this).hover(function () {
			$span.stop().fadeTo(300, 1);
		}, function () {
			$span.stop().fadeTo(300, 0);
		});
	});

	// Accordions
	$('.element').hide();
	$('.accordion').click(function(){
		var element = $(this).next('.element');
		element.slideToggle().addClass('toggled');
		$('.element:not(.toggled)').slideUp();
		element.removeClass('toggled');
	});

	// Smooth scroll to internal anchors
	$('a[href*=#]').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var jQuerytarget = $(this.hash);
			jQuerytarget = jQuerytarget.length && jQuerytarget || $('[name=' + this.hash.slice(1) +']');
			if (jQuerytarget.length) {
				var targetOffset = jQuerytarget.offset().top -80;
				$('html,body')
				.animate({scrollTop: targetOffset}, 1000);
				return false;
			}
		}
	});

	// Input value switcher
	$('.search').focus(function () {
		if ($(this).val() == 'SEARCH') {
			$(this).val('').css({'color' : '#1c252c'}); //active
		}
	});

	$('.search').blur(function () {
		if ($(this).val() === '') {
			$(this).val('SEARCH').css({'color' : '#a2a2a2'}); //inactive
		}
	});

});