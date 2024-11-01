(function() {

	tinymce.create('tinymce.plugins.Decoratr', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			ed.addCommand('mceDecoratr', decoratr_get_images);
			ed.addButton('btnDecoratr', {
				title : 'WP Decoratr',
				cmd : 'mceDecoratr',
				image : url + '/decoratr.gif'
			});
			ed.onInit.add(function() {
				ed.dom.loadCSS(url + '/style.css');
			});
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'WP Decoratr',
				author : 'iDope',
				authorurl : 'http://wordpresssupplies.com',
				infourl : 'http://wordpresssupplies.com/wordpress-plugins/decoratr/',
				version : "1.0"
			};
		},
		
		test : function () {
			alert('test');
		}
	});

	// Register plugin
	tinymce.PluginManager.add('Decoratr', tinymce.plugins.Decoratr);
})();
