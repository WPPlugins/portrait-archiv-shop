(function() {
    tinymce.PluginManager.add('pawps_tc_button', function( editor, url ) {
        editor.addButton( 'pawps_tc_button', {
        	title: 'Portrait-Archiv Galerieeinbindung',
            icon: 'icon pawps-icon',
            type: 'menubutton',
            menu: [
					{
					    text: 'einzelne Galerie einbinden',
					    onclick: function() {
					        editor.windowManager.open( {
					            title: 'Einzelne Galerie in Page einbinden',
					            body: [{
					                type: 'listbox',
					                name: 'galerieid',
					                label: 'ID der Galerie',
					                'values': getListboxValues()
					            }],
					            onsubmit: function( e ) {
					                editor.insertContent( '[pawps_galerie]' + e.data.galerieid + '[/pawps_galerie]');
					            }
					        });
					    }
					},
                   {
                       text: 'öffentlichte Galerieliste',
                       onclick: function() {
                           editor.insertContent('[pawps_publicList]');
                       }
                   },
                   {
                       text: 'Eingabe eines Galeriecodes',
                       onclick: function() {
                           editor.insertContent('[pawps_galeriecode]');
                       }
                   },
                   {
                       text: 'Eingabe eines Kennwortes',
                       onclick: function() {
                           editor.insertContent('[pawps_password]');
                       }
                   }
              ]
        });
    });
    
    function getListboxValues() {
    	return pawpsTmpGalerielistElements;
    }
})();

