/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('imagechooser');

	tinymce.create('tinymce.plugins.ImageChooserPlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceImageChooser');
			ed.addCommand('mceImageChooser', function() {

                var dialog = window.open('/_adminui/image_chooser','imagechooser','location=0,menubar=0,width=480, height=600');
                $(dialog.document).ready(function() {
                    // export function to dialog.
                    window.appendImage = dialog.appendImage = function(image_path,attrs) {
                        // get the tinyMCE content box, append text ...
                        var img = '<img src="' + image_path + '"/>';
                        ed.execCommand('mceInsertContent', false, img );

                        // show fadeIn.
                        setTimeout(function() {
                            dialog.close();
                        },1000);
                    };
                });
			});

			// Register imagechooser button
			ed.addButton('imagechooser', {
				title : 'imagechooser.desc',
				cmd : 'mceImageChooser',
				image : url + '/img/icon_photo_upload_16px.gif'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
            /*
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('imagechooser', n.nodeName == 'IMG');
			});
            */
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'ImageChooser plugin',
				author : 'Some author',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/imagechooser',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('imagechooser', tinymce.plugins.ImageChooserPlugin);
})();
