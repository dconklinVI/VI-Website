$(document).ready(function() {
	
	// Open links in parent frame
	$("a").attr('target','_parent');
	
	// Open external links in new Window/Tab
	$("a[href^='http:']").not("[href*='" + window.location.host + "']").attr('target','_blank');
	
	// Accordions
	$('.element').hide();
	$('.accordion').click(function(){
		var element = $(this).next('.element');
		element.slideToggle().addClass('toggled');
		$('.element:not(.toggled)').slideUp();
		element.removeClass('toggled');
	});
	
	// Sliding Doors Button Fader Like a Total Baller VERSION TWO POINT OH
	$('.tcx_button').each(function () {
		$(this).wrapInner('<span>');
		$('span', this).addClass('text');
		$(this).prepend('<span class="cap"></span><span class="hover"><span class="cap"></span></span>');
		$('.hover', this).css('opacity', 0);
		
		$(this).hover(function () {
			$('.hover', this).stop().fadeTo(200, 1);
		}, function () {
			$('.hover', this).stop().fadeTo(200, 0);
		});
	});
	
});