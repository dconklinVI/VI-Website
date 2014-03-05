$(document).ready(function() {

// AJAX Document Search
	if (document.getElementById('docSearch')) {
		
		var ajaxResponder = "/wp-testbed/responder";
		
		$('#docSearch').keyup(function (e) {
			e.preventDefault();
	        var theQuery = $('#docSearch').val();
			$.post(ajaxResponder, { theQuery: theQuery },
			function(data) {
				displayList(data);
			}, "html");
		});
		
		function displayList(data) {
			$('#content').html(data);
			var d= 0;
			$('#content .post').css('display', 'none').each(function() {
				$(this).delay(d).fadeIn(1000);
				d += 100;
			});
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
		}
		
		$('#document-categories li a').click(function (e) {
			e.preventDefault();
			$.post(ajaxResponder, { categoryData: this.id },
			function(data) {
				displayList(data);
			}, "html");
		});
		
		$('#document-tags li a').click(function (e) {
			e.preventDefault();
			$.post(ajaxResponder, { tagData: this.id },
			function(data) {
				displayList(data);
			}, "html");
		});
	}
	
});