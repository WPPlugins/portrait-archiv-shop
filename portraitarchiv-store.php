<?php

/*
Plugin Name: Portrait-Archiv.com Photostore
Plugin URI: http://wordpress.org/plugins/portrait-archiv-shop/
Description: Der Portrait-Archiv.com Photostore stellt dem Benutzer die Moeglichkeit zur einfachen Integration eines Online Foto Nachbestellsystems zur Verfuegung
Version: 2.3
Author: Thomas Schiffler
Author URI: http://www.Portrait-Service.com/
*/

 // Session wird benötigt
 function is_session_started() {
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
 }

 // Example
 if ( is_session_started() === FALSE ) session_start();
 
 // weitere Referenzen einbinden
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-configure.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-classes.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-connector.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-functions.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-adminarea.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-template-functions.php';

 // Initialisierung und Anlegen der Optionen
 function pawps_plugin_activate() {
 	if ( ! current_user_can( 'activate_plugins' ) )
 		return;
 	
 	add_option(PAWPS_OPTION_HASHKEY, pawps_generateLocalHash());
 	add_option(PAWPS_OPTION_HASHKEY_REMOTE);
 	add_option(PAWPS_OPTION_USERID);
 	add_option(PAWPS_LAST_UPDATE_SHOOTINGS);
 	add_option(PAWPS_DB_VERSION, 0);
 	add_option(PAWPS_DEBUG, 0);
 	add_option(PAWPS_SYSTEMCOUNTRY, 'DE');

 	// Anzeige-Konfiguration
 	add_option(PAWPS_TEMPLATE_NAME, 'default');
 	add_option(PAWPS_DISPLAY_ROWS, 3);
 	add_option(PAWPS_DISPLAY_COLS, 4);
 	
 	// Create Database
 	require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-setup.php';
 	pawps_setupDatabase();
 	
 	// Schedule Update
 	wp_schedule_event(time(), 'every_5_minutes', 'pawps_refresh_hook');
 }
 
 // Deaktivierung und Aufräumen
 function pawps_plugin_deactivate() {
 	if ( ! current_user_can( 'activate_plugins' ) )
 		return;
 	
 	// Schedule deaktivieren
 	wp_clear_scheduled_hook('pawps_refresh_hook');
 }
 
  function pawps_insertContent($pawps_content) {
 	if (strlen($pawps_content) > 0) {		
 		
 		// Eventliste
 		if (strpos($pawps_content, "[pawps_publicList]") > -1) {
 			ob_start();
 			pawps_publicEventList();
 			$displayContent = ob_get_contents();
 			ob_end_clean();
 			
 			$pawps_content = str_replace("[pawps_publicList]", $displayContent, $pawps_content);
 		}
 		
 		// Einzelevent
 		if ((strpos($pawps_content, "[pawps_galerie]") > -1) && (strpos($pawps_content, "[/pawps_galerie]") > -1)) {
 			$shootingId = substr($pawps_content, strpos($pawps_content, "[pawps_galerie]") + strlen("[pawps_galerie]"));
 			$shootingId = substr($shootingId, 0, strpos($shootingId, "[/pawps_galerie]"));
 			if (is_numeric($shootingId)) {
 				ob_start();
 				pawps_showPublicEvent($shootingId);
 				$displayContent = ob_get_contents();
 				ob_end_clean();
 				
 				$pawps_content = str_replace("[pawps_galerie]" . $shootingId . "[/pawps_galerie]", 
 						$displayContent, $pawps_content);
 			}
 		}
 		
 		// einfache Passworteingabe
 		if (strpos($pawps_content, "[pawps_password]") > -1) {
 			ob_start();
 			pawps_shootingByCode();
 			$displayContent = ob_get_contents();
 			ob_end_clean();
 			
 			$pawps_content = str_replace("[pawps_password]", $displayContent, $pawps_content);
 		} 		
 		
 		// Gallericode
 		if (strpos($pawps_content, "[pawps_galeriecode]") > -1) {
 			ob_start();
 			pawps_shootingByGalleriecode();
 			$displayContent = ob_get_contents();
 			ob_end_clean();
 				
 			$pawps_content = str_replace("[pawps_galeriecode]", $displayContent, $pawps_content);
 		}
 	}

 	return $pawps_content;
 	
 }
 
 function pawps_register_stylesheet() {
 	$userTemplate = get_option(PAWPS_TEMPLATE_NAME);
 	if (strpos($userTemplate, PAWPS_USERTPL_START) > -1) {
 		// Benutzertemplate
 		wp_register_style('portrait-archiv-shop', pawps_getUsertemplateDirUrl() . $userTemplate . '/style.css');
 	} else {
 		wp_register_style('portrait-archiv-shop', plugins_url('portrait-archiv-shop/templates/' . get_option(PAWPS_TEMPLATE_NAME) . '/style.css'));
 	}
 	
 	wp_enqueue_style('portrait-archiv-shop');
 }
 
 function pawps_add_my_tc_button() {
 	global $typenow;
 	// check user permissions
 	if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
 		return;
 	}
 	// verify the post type
 	if(!in_array( $typenow, array( 'post', 'page')))
 		return;
 	// check if WYSIWYG is enabled
 	if ( get_user_option('rich_editing') == 'true') {
 		add_filter("mce_external_plugins", "pawps_add_tinymce_plugin");
 		add_filter('mce_buttons', 'pawps_register_my_tc_button');
 	}
 }
 
 function pawps_add_tinymce_plugin($plugin_array) {
 	$plugin_array['pawps_tc_button'] = plugins_url( '/editor_button.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
 	return $plugin_array;
 }
 
 function pawps_register_my_tc_button($buttons) {
 	array_push($buttons, "pawps_tc_button");
 	return $buttons;
 }
 
 function pawps_tinymce_addlistcontentinjs() {
 	?>
 	
 		<script type="text/javascript">
	 		var pawpsTmpGalerielistElements= [];
	 		<?php 

		$galerieList = pawps_getShootingList();
		if (isset($galerieList)) {
			foreach ($galerieList as $aktuellesShooting) {
				echo "var d = {};";
				echo "d['text'] = '" . $aktuellesShooting->title . "';";
				echo "d['value'] = '" . $aktuellesShooting->id . "';";
				echo "pawpsTmpGalerielistElements.push(d);";
			}
		}
	 		
	 		?>
 		</script>
 	
 	<?php 
 }
 
 function papws_tinymce_css() {
 	wp_enqueue_style('pawps-tinymce', plugins_url('/style.css', __FILE__));
 }
 
 function pawps_add_every_5_minutes( $schedules ) {
 
 	$schedules['every_5_minutes'] = array(
 			'interval'  => 60 * 5,
 			'display'   => __( 'Every 5 Minutes', 'textdomain' )
 	);
 	 
 	return $schedules;
 }
 add_filter( 'cron_schedules', 'pawps_add_every_5_minutes' );
 
 // Die aktivieren/deaktivieren Funktionen registrieren
 add_action('activate_' . plugin_basename(__FILE__),   'pawps_plugin_activate');
 add_action('deactivate_' . plugin_basename(__FILE__), 'pawps_plugin_deactivate');
 
 // eigentliche Funktionen registrieren einbauen
 add_action('wp_enqueue_scripts', 'pawps_register_stylesheet');
 add_action('admin_enqueue_scripts', 'pawps_register_stylesheet');
 add_action('admin_enqueue_scripts', 'papws_tinymce_css');
 add_filter('the_content', 'pawps_insertContent');
 
 // Regelmäßigen Refresh aktivieren
 add_action('pawps_refresh_hook', 'pawps_refresh_cron');

 // Menü hinzufügen
 add_action('admin_menu', 'pawps_admin_menu' );
 
 // Editor Button
 add_action('admin_head', 'pawps_tinymce_addlistcontentinjs');
 add_action('admin_head', 'pawps_add_my_tc_button');
 
?>