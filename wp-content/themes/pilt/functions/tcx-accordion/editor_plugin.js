(function() {
	tinymce.create('tinymce.plugins.tcxaccordion', {
		init : function(ed, url) {
			ed.addButton('tcxaccordion', {
				title : 'Insert Accordion',
				image : url+'/../../images/spritesheet.png',
				onclick : function() {
					ed.execCommand('mceInsertContent', false, '<div class="accordion">Title</div>\n<div class="element">Hidden Text</div>\n &nbsp;');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "TCX Accordion Shortcode",
				author : 'Dan Bookman',
				authorurl : 'http://www.danbookman.com/',
				infourl : 'http://www.danbookman.com/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('tcxaccordion', tinymce.plugins.tcxaccordion);
})();