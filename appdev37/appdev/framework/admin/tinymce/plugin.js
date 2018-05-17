tinymce.PluginManager.add('shortcodepanel', function(editor, url) {

    // Register the command to be invoked by using tinyMCE.activeEditor.execCommand('mceInsertContent');
    editor.addCommand('mceshortcodepanel', function() {
        editor.windowManager.open({
            title: 'Choose Shortcode',
            file : url + '/window_page.php',
            width : 360 + editor.getLang('shortcodepanel.delta_width', 0),
            height : 210 + editor.getLang('shortcodepanel.delta_height', 0),
            inline : 1
        }, {
            plugin_url : url, // Plugin absolute URL
            some_custom_arg : 'custom arg' // Custom argument
        });
    });

    // Register the shortcode button on the editor panel
    editor.addButton('shortcodepanel', {
        text: false,
        title : 'Add Theme Shortcode',
        cmd : 'mceshortcodepanel',
        image : url + '/shortcode.png',
        onPostRender: function() {
            var ctrl = this;

            editor.on('NodeChange', function(e) {
                ctrl.active(e.element.nodeName == 'IMG');
            });
        }
    });
});
