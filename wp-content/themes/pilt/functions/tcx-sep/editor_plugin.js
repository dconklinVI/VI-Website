(function() {
	tinymce.create('tinymce.plugins.tcxsep', {
		init : function(ed, url) {
			ed.addButton('tcxsep', {
				title : 'Insert Separator',
				image : url+'/../../images/spritesheet.png',
				onclick : function() {
					ed.execCommand('mceInsertContent', false, '[sep]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "TCX Separator Shortcode",
				author : 'Dan Bookman',
				authorurl : 'http://www.danbookman.com/',
				infourl : 'http://www.danbookman.com/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('tcxsep', tinymce.plugins.tcxsep);
})();