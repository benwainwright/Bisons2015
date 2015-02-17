(function() {
    tinymce.create('tinymce.plugins.wpYoutube'), {
        
        init : function(ed, url) {
            var disabled = true;
            ed.addCommand('WP_Youtube', function() {
                if ( disabled )
                    return;
                ed.windowManager.open({
                    id : 'wp-youtube',
                    width: 480,
                    height: "auto", 
                    wpDialog : true,
                    title : 'test'
                }, {
                    plugin_url : url
                });
            });
            
            ed.addButton('youtube', {
                title : 'test2',
                cmd : 'WP_Youtube'
            });
            
            ed.onNodeChange.add(function(ed, cm, n, co) {
                disabled = co && n.nodeName != 'A';
            });
        },
            getInfo : function() {
                return {
                    longname : 'Wordpress Youtube dialog',
                    author : 'Ben Wainwright',
                    authorurl : '',
                    infourl : '',
                    version : '1.0'
                };
            }
        });
        tinymce.PluginManager.add('wpyoutube', tinymce.plugins.wpYoutube);
    })();