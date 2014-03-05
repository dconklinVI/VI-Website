(function() {
	tinymce.create('tinymce.plugins.tcxcolumns', {
		init : function(ed, url) {
			ed.addButton('tcxcolumns', {
				title : 'Insert Columns',
				image : url+'/../../images/spritesheet.png',
				onclick : function() {
					var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
					W = W - 80;
					H = H - 84;
					tb_show( 'Insert Columns', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=tcxcolumns-form' );
					if (jQuery.browser.msie) {
						ed.execCommand('mceInsertContent', false, '<span id="caret_pos_holder"></span>');
					}
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "TCX Column Shortcode",
				author : 'Dan Bookman',
				authorurl : 'http://www.danbookman.com/',
				infourl : 'http://www.danbookman.com/',
				version : "1.1"
			};
		}
	});
	tinymce.PluginManager.add('tcxcolumns', tinymce.plugins.tcxcolumns);
	
	jQuery(function(){
		var form = jQuery('<div id="tcxcolumns-form"><table id="tcxcolumns-table" class="form-table">\
			<tr>\
				<th>Add Columns</th>\
				<td>\
					<input type="hidden" id="points" value="100">\
					<input type="hidden" id="currentCol" value="1">\
					<input type="hidden" id="ticker" value="1">\
					<div id="add-columns">\
						<span id="c75">3/4 Column</span>\
						<span id="c50">1/2 Column</span>\
						<span id="c25" style="margin-bottom: 5px;">1/4 Column</span>\
						<span id="c66">2/3 Column</span>\
						<span id="c33">1/3 Column</span>\
						<span id="c20">1/5 Column</span>\
					</div>\
				</td>\
			</tr>\
			<tr>\
				<th>Preview</th>\
				<td>\
					<div id="colpreview"></div>\
				</td>\
			</tr>\
			<tr>\
				<td id="columns"></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="tcxcolumns-submit" class="button-primary" value="Insert Columns" name="submit" />\
		</p>\
		</div>');
		
		form.appendTo('body').hide();
		form.find('#tcxcolumns-submit').click(function(){			
			var IDs = jQuery("#columns :input").serializeArray();
			var shortcode = "";
			
			for (var index in IDs) {
				shortcode += '<div class="' + IDs[index].value + '">Column '+(parseInt(index,10)+1)+'</div>';
			}
				shortcode += '&nbsp;';
			tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.dom.select('span#caret_pos_holder')[0]); // IE hack for position
			
			tinyMCE.activeEditor.execCommand('mceInsertContent', false, shortcode);
			
			tinyMCE.activeEditor.dom.remove(tinyMCE.activeEditor.dom.select('span#caret_pos_holder')[0]); // Remove the hack
			tb_remove();
		});
	});
})();