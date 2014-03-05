(function() {
	tinymce.create('tinymce.plugins.tcxbutton', {
		init : function(ed, url) {
			ed.addButton('tcxbutton', {
				title : 'Insert Button',
				image : url+'/../../images/spritesheet.png',
				onclick : function() {
					var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
					W = W - 80;
					H = H - 84;
					tb_show( 'Insert a Button', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=tcxbutton-form' );
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "TCX Button Shortcode",
				author : 'Dan Bookman',
				authorurl : 'http://www.danbookman.com/',
				infourl : 'http://www.danbookman.com/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('tcxbutton', tinymce.plugins.tcxbutton);
	
	jQuery(function(){
		var form = jQuery('<div id="tcxbutton-form"><table id="tcxbutton-table" class="form-table">\
			<tr>\
				<th><label for="tcxbutton-title">Title</label></th>\
				<td><input type="text" id="tcxbutton-title" name="title" value="" /><br />\
				<small>specify the text to appear on the button.</small></td>\
			</tr>\
			<tr>\
				<th><label for="tcxbutton-link">Link</label></th>\
				<td><input type="text" id="tcxbutton-link" name="link" value="" /><br />\
				<small>specify URL to which the button will link.</small>\
			</tr>\
			<tr>\
				<th><label for="tcxbutton-color">Color</label></th>\
				<td><select name="color" id="tcxbutton-color">\
					<option value="">White</option>\
					<option value="gray">Gray</option>\
				</select><br />\
				<small>specify the button\'s background color.</small></td>\
			</tr>\
			<tr>\
				<th><label for="tcxbutton-behavior">Behavior</label></th>\
				<td><select name="behavior" id="tcxbutton-behavior">\
					<option value="">Default</option>\
					<option value="new">New Window/Tab</option>\
					<option value="lightbox">Lightbox</option>\
				</select><br />\
				<small>specify whether the button opens in a new tab or a lightbox.</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="tcxbutton-submit" class="button-primary" value="Insert Button" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		form.find('#tcxbutton-submit').click(function(){
			var options = { 
				'title'      : '',
				'link'       : '',
				'color'		 : '',
				'behavior'   : ''
				};
			var shortcode = '[button';
			
			for( var index in options) {
				var value = table.find('#tcxbutton-' + index).val();
				
				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += ']';
			
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			tb_remove();
		});
	});
	
})();