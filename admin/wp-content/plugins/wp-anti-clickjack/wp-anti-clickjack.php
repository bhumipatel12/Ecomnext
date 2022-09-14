<?php
/**
 * Plugin Name: WP Anti-Clickjack
 * Plugin URI: https://drawne.com/wordpress-anti-clickjack-plugin/
 * Description: Plugin to prevent your site from being clickjacked by adding OWASP's legacy browser frame breaking script.
 * Version: 1.7.2
 * Text Domain: wp-anti-clickjack
 * Author: Andy Feliciotti
 * Author URI: https://drawne.com
 */

defined('ABSPATH') or die("you do not have access to this page!");
class wp_anticlickjack {

    public function __construct()
    {
		$sendxFrameOptionsHeader = apply_filters( 'wp_anti_clickjack_x_frame_options_header', true);
		
		if($sendxFrameOptionsHeader){
			add_action( 'send_headers', 'send_frame_options_header', 10, 0 );
		}
		
		add_action('admin_head', array($this,'include_anticlickjack_script'));
		add_action('wp_head', array($this,'include_anticlickjack_script'));
		add_action('login_head', array($this,'include_anticlickjack_script'));
	}

    public function include_anticlickjack_script() {

	    $displayAntiClickjack = apply_filters( 'wp_anti_clickjack', true);

		if ( is_customize_preview() ) {
			$displayAntiClickjack = false;
		}

		//Visual Composer
		if( !empty($_REQUEST['vc_editable']) ){
			if ( sanitize_text_field($_REQUEST['vc_editable']) === 'true' ) {
				$displayAntiClickjack = false;
			}
		}
		
		//Divi Page Editor
		if( !empty($_REQUEST['et_fb']) ){
			if ( sanitize_text_field($_REQUEST['et_fb']) === '1' ) {
				$displayAntiClickjack = false;
			}
		}
		
		//Cornerstone Editor
		if ( did_action('cs_before_preview_frame') ) {
			$displayAntiClickjack = false;
		}

		//Elementor
	    if( class_exists('\Elementor\Plugin') ){
		    if(\Elementor\Plugin::$instance->preview->is_preview_mode() || \Elementor\Plugin::$instance->editor->is_edit_mode()){
			    $displayAntiClickjack = false;
		    }
	    }

		//Thrive Editor
		if( !empty($_REQUEST['tve']) ){
		    if ( sanitize_text_field($_REQUEST['tve']) === 'true' ) {
			    $displayAntiClickjack = false;
			}
		}

		if( !empty($_REQUEST['action']) ){
		    if ( sanitize_text_field($_REQUEST['action']) === 'do-plugin-upgrade' || sanitize_text_field($_REQUEST['action']) === 'do-theme-upgrade' || sanitize_text_field($_REQUEST['action']) === 'update-selected' || sanitize_text_field($_REQUEST['action']) === 'update-selected-themes' ) {
			    $displayAntiClickjack = false;
			}
		}

		if( !empty($_SERVER["HTTP_REFERER"]) ){
		    if ( sanitize_text_field(parse_url($_SERVER["HTTP_REFERER"])['host'] !== parse_url(get_site_url())['host']) ) {
				$displayAntiClickjack = true;
			}
		}

	    if ( $displayAntiClickjack ) {
	        echo '<script language="javascript" type="text/javascript">
			 var style = document.createElement("style");
			 style.type = "text/css";
			 style.id = "antiClickjack";
			 if ("cssText" in style){
			   style.cssText = "body{display:none !important;}";
			 }else{
			   style.innerHTML = "body{display:none !important;}";
			}
			document.getElementsByTagName("head")[0].appendChild(style);

			if (top === self) {
			 var antiClickjack = document.getElementById("antiClickjack");
			 antiClickjack.parentNode.removeChild(antiClickjack);
			} else {
			 top.location = self.location;
			}
		  </script>';
		}
    }
}

$wp_anticlickjack = new wp_anticlickjack();
unset($wp_anticlickjack);
