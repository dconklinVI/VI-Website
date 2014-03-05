var jQT = new $.jQTouch({
	icon: '/apple-touch-icon-precomposed.png',
	icon4: '/apple-touch-icon-precomposed.png',
	addGlossToIcon: false,
	startupScreen: '/wp-content/themes/THEME/images/mobile-splash.png',
	statusBar: 'black-translucent',
	useTouchScroll: false,
	useFastTouch: false,
	preloadImages: []
});

function classyLinks() {
	hostname = new RegExp(location.host);
	$('a[href*="?"]').addClass("qs");
	$('a:not(.classy):not(.back)').each(function(){
		$(this).addClass("classy");
		if ($(this).hasClass("qs")) {
			qsSeparator = "&";
		} else {
			qsSeparator = "?";
		}
		if ($(this).attr('href')) {
			var url = $(this).attr("href");
			
			if(hostname.test(url)){
				// Local link, contains domain
				$(this).attr('href',url+qsSeparator+'mobilepage=1');
			}
			else if(url.slice(0, 1) == "/"){
				// Relative link
				$(this).attr('href',url+qsSeparator+'mobilepage=1');
			}
			else if(url.slice(0, 1) == "#"){
				// Internal anchor
			}
			else {
				// External link
				$(this).attr('target','_blank');
			}
		}
	});
}

$(document).ready(function() {
	classyLinks();

	$('body').on('pageInserted', function(e, data) {
	    classyLinks();
	});
});