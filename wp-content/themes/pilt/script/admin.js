jQuery(document).ready(function(jQuery) {

	// JS Compat notice
	jQuery('#js-compat').hide();

	// Slideshow Page Switcher
	jQuery("#tcx-slideshow-select").change(function() {
		window.location = jQuery("#tcx-slideshow-select option:selected").val();
	});

	// Bind Upload/Edit buttons to WP3.5 Media Library
	jQuery('.tcx-slideshow-upload-button').live('click', function() {
		activeSlide = jQuery(this).parent();
		var send_attachment_bkp = wp.media.editor.send.attachment;

		wp.media.editor.send.attachment = function(props, attachment) {
			//console.log(attachment);
			if (attachment.sizes.tcx_slideshow_cropped) {
				activeSlide.find('.thumbnail').attr('src', attachment.sizes.tcx_slideshow_cropped.url).css('max-width','200px');
				activeSlide.find('.thumbnail-url').attr('value', attachment.sizes.tcx_slideshow_cropped.url);
			} else if (attachment.sizes.thumbnail) {
				// Image was too small to get a 200px thumbnail
				activeSlide.find('.thumbnail').attr('src', attachment.sizes.thumbnail.url).css('max-width','200px');
				activeSlide.find('.thumbnail-url').attr('value', attachment.sizes.thumbnail.url);
			} else {
				// Image was too small to get WP thumb, use full size
				activeSlide.find('.thumbnail').attr('src', attachment.sizes.full.url).css('max-width','200px');
				activeSlide.find('.thumbnail-url').attr('value', attachment.sizes.full.url);
			}
			activeSlide.find('.full-image').attr('value', attachment.url);
			wp.media.editor.send.attachment = send_attachment_bkp;
		};

		wp.media.editor.open();
		return false;
	});

	// Bind New Slide button
	jQuery('#add_new_slide').click(function(e) {
		e.preventDefault();
		top_id = Number(jQuery('#top_id').attr('value'));
		top_id++;
		jQuery('#top_id').attr('value', top_id);
		var currentSlideshowID = jQuery('#slideshow_id').attr('value');
		jQuery('#slideshow-config').append(newSlide(top_id, currentSlideshowID));
		linkFieldToggle();

		// Expand the new slide
		var titleField = jQuery(this).parent().find('#order_'+top_id+' .title-field');
		jQuery(this).parent().find('#order_'+top_id).toggleClass('expanded', 300, function(){
			jQuery(this).find('label').slideToggle();
			jQuery(this).find('.tcx-slideshow-upload-button, .delete').fadeToggle();
		});
		titleField.prop("disabled",!titleField.prop("disabled"));
	});
	function newSlide(newID, currentSlideshowID) {
		newslide = '<li id="order_' + newID + '" class="menu-item-handle tcx-sortable"><div class="expander"></div><div class="gripper"></div><img class="thumbnail new" src="" alt="" /><input name="tcx_slideshow_' + currentSlideshowID + '[slides]['+ newID +'][id]" class="ready" type="hidden" value="' + newID + '" /><input name="tcx_slideshow_' + currentSlideshowID + '[slides]['+ newID +'][thumbnail]" class="ready thumbnail-url" type="hidden" value="" /><input name="tcx_slideshow_' + currentSlideshowID + '[slides]['+ newID +'][full_image]" class="ready full-image" type="hidden" value="" /><a href="#" class="button-secondary tcx-slideshow-upload-button" style="display: none">Upload/Edit Image</a><div class="delete" style="display: none"><a href="#" class="submitdelete deletion">Delete Slide</a> <div class="deletehider"><a href="#" class="confirmdelete">Confirm</a></div></div><div class="tcx-slide-fields"><label for="slide_title" style="display: none"> Title</label> <input type="text" class="form-field title-field title-display" value="New Slide" disabled="disabled" /><br/><input name="tcx_slideshow_' + currentSlideshowID + '[slides]['+ newID +'][title]" class="ready title-submit" type="hidden" value="New Slide" /><label for="slide_description" style="display: none"> Description</label> <textarea rows="4" cols="40" name="tcx_slideshow_' + currentSlideshowID + '[slides]['+ newID +'][description]" class="ready tcx-slide-description"></textarea><br/><div class="link-fields"><label for="slide_link" style="display: none"> Slide Link<span class="lightbox-option"><input type="checkbox" name="tcx_slideshow_' + currentSlideshowID + '[inactive_slides]['+ newID +'][lightbox]" value="on"> Lightbox</span></label> <input type="text" name="tcx_slideshow_' + currentSlideshowID + '[slides]['+ newID +'][link]" class="form-field link-field link" value="" /></div></div></li>';
		return newslide;
	}

	// Move title text to submittable input
	jQuery('#tcx-slideshow .title-display').live('change', function() {
		activeSlide = jQuery(this).parent();
		activeSlide.find('.title-submit').attr('value',jQuery(this).attr('value'));
	});

	// Delete Slides
	jQuery('#tcx-slideshow .tcx-sortable .submitdelete').live('click', function(e) {
		e.preventDefault();
		jQuery(this).html('Delete Slide?');
		activeSlide = jQuery(this).parent();
		activeSlide.find('.confirmdelete').fadeIn();
		activeSlide.find('.deletehider').animate({textIndent:'0px'});
	});
	jQuery('#tcx-slideshow .confirmdelete').live('click', function(e) {
		e.preventDefault();
		activeSlide = jQuery(this).closest('.tcx-sortable');
		activeSlide.fadeOut(function(){activeSlide.remove();});
	});

	// Delete Slideshow
	jQuery('#tcx-slideshow .slideshow-delete').click(function(e) {
		e.preventDefault();
		jQuery(this).html('Delete?');
		jQuery('.confirm-slideshow-delete').fadeIn();
		jQuery('.slideshow-deletehider').animate({textIndent:'0px'});
	});

	// Disable Share Bar fields When Share Bar is not enabled
	if (!jQuery('#share_bar').attr('checked')) {
		jQuery('#social-config li').toggleClass('disabled');
		jQuery('#social-config input').fadeOut();
	}
	jQuery('#share_bar').change(function(){
		jQuery('#social-config li').toggleClass('disabled');
		jQuery('#social-config input').fadeToggle();
	});

	// Initialize sortables for Share Bar
	jQuery('#social-config').sortable({
		connectWith: ".all-slides",
		handle: ".gripper",
		placeholder: 'ui-state-highlight',
		stop: function() {
			var order = jQuery('#social-config').sortable('serialize');
			jQuery('#social_order').attr('value', order);
		}
	});

	// Initialize sortables for Slideshow config
	jQuery('#slideshow-config, #inactive-slides').sortable({
		connectWith: ".all-slides",
		handle: ".gripper",
		placeholder: 'ui-state-highlight',
		receive: function() {
			//alert(jQuery(this).attr('id'));
			if (jQuery(this).attr('id') == "inactive-slides") {
				jQuery(this).find('.ready').each(function () {
					attname = jQuery(this).attr('name');
					jQuery(this).attr('name', attname.replace('[slides]','[inactive_slides]'));
				});
			} else {
				jQuery(this).find('.ready').each(function () {
					attname = jQuery(this).attr('name');
					jQuery(this).attr('name', attname.replace('[inactive_slides]','[slides]'));
				});
			}
		}
	});

	// Create expander actions for individual slides
	jQuery('#tcx-slideshow .tcx-sortable label, .tcx-slideshow-upload-button, .tcx-sortable .delete, .tcx-sortable .confirmdelete, .confirm-slideshow-delete, .tcx-slideshow-subsection, #carousel-options-button').hide();
	jQuery('#tcx-slideshow .expander').live('click', function() {
		var titleField = jQuery(this).parent().find('.title-field');
		jQuery(this).parent().toggleClass('expanded', 300, function(){
			jQuery(this).find('label').slideToggle();
			jQuery(this).find('.tcx-slideshow-upload-button, .delete').fadeToggle();
		});
		titleField.prop("disabled",!titleField.prop("disabled"));
	});

	// Expander for slideshow sub-option panels
	jQuery('.tcx-slideshow-subsection-expander').click(function() {
		jQuery(this).next('.tcx-slideshow-subsection').slideToggle();
	});
	jQuery("#tcx-slideshow-transition").change(function() {
		carouselOptions();
	});
	function carouselOptions() {
		if (jQuery('#tcx-slideshow-transition').val() == 'carousel') {
			jQuery('#carousel-options-button').slideDown();
		} else {
			jQuery('#carousel-options-button, #carousel-options-panel').slideUp();
		}
	}
	carouselOptions();

	// Logic for Responsive/Fixed size selector
	jQuery("#tcx-slideshow-mode").change(function() {
		responsiveFieldSelector();
	});
	function responsiveFieldSelector() {
		if (jQuery('#tcx-slideshow-mode').val() == 'responsive') {
			jQuery('#fixed-width-fields').hide();
			jQuery('#max-size-fields').show();

		} else {
			jQuery('#fixed-width-fields').show();
			jQuery('#max-size-fields').hide();
		}
	}
	responsiveFieldSelector();

	// Logic for Custom Slide Links Selector
	jQuery("#tcx-slideshow-slidelinks").change(function() {
		linkFieldToggle();
	});
	function linkFieldToggle() {
		if (jQuery('#tcx-slideshow-slidelinks').val() == 'custom') {
			jQuery('.tcx-sortable .link-fields').slideDown();

		} else {
			jQuery('.tcx-sortable .link-fields').slideUp();
		}
	}
	linkFieldToggle();
	
	// TCX Columns dialogue
	jQuery('#add-columns span').live('click', function() {
		var thisDelta = jQuery(this).attr("id").substring(1);
		newPoints = jQuery('#points').val() - thisDelta;
		newCol = parseInt(jQuery('#currentCol').val(),10) + 1;
		ticker = jQuery('#ticker').val();
		if(newPoints < 0) {
			evaluateButtons();
		} else {
			jQuery('#columns').append('<input type="hidden" name="n'+ticker+'" id="t'+ticker+'" value="'+jQuery(this).attr("id")+'"/>');
			jQuery('#colpreview').append('<div id="t'+ticker+'" class="'+jQuery(this).attr("id")+'"></div>');
			jQuery('#points').val(newPoints);
			jQuery('#currentCol').val(newCol);
			incrementTicker();
			evaluateButtons();
		}
	});
	jQuery('#colpreview div').live('click', function() {
		var thisDelta = parseInt(jQuery(this).attr("class").substring(1),10);
		var newPoints = parseInt(jQuery('#points').val(),10) + thisDelta;
		newCol = parseInt(jQuery('#currentCol').val(),10) - 1;
		jQuery('#columns #'+jQuery(this).attr("id")).remove();
		jQuery(this).remove();
		jQuery('#points').val(newPoints);
		jQuery('#currentCol').val(newCol);
		incrementTicker();
		evaluateButtons();
	});
	function evaluateButtons() {
		jQuery('#add-columns span').each(function () {
			var pointValue = parseInt(jQuery(this).attr("id").substring(1),10);
			if (pointValue > jQuery('#points').val()) {
				jQuery(this).addClass('inactive');
			} else {
				jQuery(this).removeClass('inactive');
			}
		});
	}
	function incrementTicker() {
		jQuery('#ticker').val(parseInt(jQuery('#ticker').val(),10) + 1);
		return false;
	}
});