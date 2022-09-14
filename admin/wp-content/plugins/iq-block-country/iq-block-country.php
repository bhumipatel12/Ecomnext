<?php   
/*
Plugin Name: iQ Block Country
Plugin URI: https://webence.nl/plugins/iq-block-country-the-wordpress-plugin-that-blocks-countries-for-you/
Version: 1.2.18
Author: Pascal
Author URI: https://webence.nl/
Description: Block visitors from visiting your website and backend website based on which country their IP address is from. The Maxmind GeoIP lite database is used for looking up from which country an ip address is from.
License: GPL2
Text Domain: iq-block-country
Domain Path: /lang
*/

/* This script uses GeoLite Country from MaxMind (http://www.maxmind.com) which is available under terms of GPL/LGPL */

/*  Copyright 2010-2022  Pascal  (email: pascal@webence.nl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * This software is dedicated to my one true love.
 * Luvya :)
 **/

/**
 * Try to make this plugin the first plugin that is loaded.
 * Because we output header info we don't want other plugins to send output first.
 **/
function iqblockcountry_this_plugin_first() 
{
    $iqbc_wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
    $iqbc_this_plugin = plugin_basename(trim($iqbc_wp_path_to_this_file));
    $iqbc_active_plugins = get_option('active_plugins');
    $iqbc_this_plugin_key = array_search($iqbc_this_plugin, $iqbc_active_plugins);
    if ($iqbc_this_plugin_key) { // if it's 0 it's the first plugin already, no need to continue
        array_splice($iqbc_active_plugins, $iqbc_this_plugin_key, 1);
        array_unshift($iqbc_active_plugins, $iqbc_this_plugin);
        update_option('active_plugins', $iqbc_active_plugins);
    }     
}


/**
 * Attempt on output buffering to protect against headers already send mistakes 
 **/
function iqblockcountry_buffer() 
{
    ob_start();
} 

/**
 * Attempt on output buffering to protect against headers already send mistakes 
 **/
function iqblockcountry_buffer_flush() 
{
    if (ob_get_contents()) { ob_end_flush();
    }
} 


/**
 * Localization
 **/
function iqblockcountry_localization()
{
    load_plugin_textdomain('iq-block-country', false, dirname(plugin_basename(__FILE__)) . '/lang');
}

 /*
  * Retrieves the IP address from the HTTP Headers
 */
function iqblockcountry_get_ipaddress() 
{
    global $iqbc_ip_address;

    $iqbc_server_address = "";
    if(isset($_SERVER['SERVER_ADDR']) && (rest_is_ip_address($_SERVER['SERVER_ADDR']))) { $iqbc_server_address = filter_var($_SERVER['SERVER_ADDR'],FILTER_VALIDATE_IP); } 
    elseif(array_key_exists('LOCAL_ADDR', $_SERVER) && (rest_is_ip_address($_SERVER['LOCAL_ADDR']))) { $iqbc_server_address = filter_var($_SERVER['LOCAL_ADDR'],FILTER_VALIDATE_IP); }
    
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && rest_is_ip_address($_SERVER['HTTP_CF_CONNECTING_IP'])) { $iqbc_ip_address = filter_var($_SERVER['HTTP_CF_CONNECTING_IP'],FILTER_VALIDATE_IP); }
    elseif (isset($_SERVER['HTTP_X_REAL_IP']) && rest_is_ip_address($_SERVER['HTTP_X_REAL_IP'])) { $iqbc_ip_address = filter_var($_SERVER['HTTP_X_REAL_IP'],FILTER_VALIDATE_IP); } 
    elseif (isset($_SERVER['HTTP_X_SUCURI_CLIENTIP']) && rest_is_ip_address($_SERVER['HTTP_X_SUCURI_CLIENTIP'])) { $iqbc_ip_address = filter_var($_SERVER['HTTP_X_SUCURI_CLIENTIP'],FILTER_VALIDATE_IP); }
    elseif (isset($_SERVER['HTTP_INCAP_CLIENT_IP']) && rest_is_ip_address($_SERVER['HTTP_INCAP_CLIENT_IP'])) { $iqbc_ip_address = filter_var($_SERVER['HTTP_INCAP_CLIENT_IP'],FILTER_VALIDATE_IP); }
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && rest_is_ip_address($_SERVER['HTTP_X_FORWARDED_FOR'])) { $iqbc_ip_address = filter_var($_SERVER['HTTP_X_FORWARDED_FOR'],FILTER_VALIDATE_IP); } 
    elseif (isset($_SERVER['HTTP_X_FORWARDED']) && rest_is_ip_address($_SERVER['HTTP_X_FORWARDED'])) { $iqbc_ip_address = filter_var($_SERVER['HTTP_X_FORWARDED'],FILTER_VALIDATE_IP); }
    elseif (isset($_SERVER['HTTP_CLIENT_IP']) && rest_is_ip_address($_SERVER['HTTP_CLIENT_IP'])) { $iqbc_ip_address = filter_var($_SERVER['HTTP_CLIENT_IP'],FILTER_VALIDATE_IP); }
    elseif (isset($_SERVER['HTTP_FORWARDED']) && rest_is_ip_address($_SERVER['HTTP_FORWARDED'])) { $iqbc_ip_address = filter_var($_SERVER['HTTP_FORWARDED'],FILTER_VALIDATE_IP); }
    elseif (isset($_SERVER['REMOTE_ADDR']) && rest_is_ip_address($_SERVER['REMOTE_ADDR'])) { $iqbc_ip_address = filter_var($_SERVER['REMOTE_ADDR'],FILTER_VALIDATE_IP); }

    $iqbc_ipoverride = get_option('blockcountry_ipoverride');
    if (isset($iqbc_ipoverride) && (!empty($iqbc_ipoverride) && ($iqbc_ipoverride != "NONE") )) {
        if (isset($_SERVER[$iqbc_ipoverride]) && !empty($_SERVER[$iqbc_ipoverride])) {
            if (iqblockcountry_is_valid_ipv4($_SERVER[$iqbc_ipoverride]) || iqblockcountry_is_valid_ipv6($_SERVER[$iqbc_ipoverride])) { $iqbc_ip_address = filter_var($_SERVER[$iqbc_ipoverride],FILTER_VALIDATE_IP);}
        }
    }
     
    // Get first ip if ip_address contains multiple addresses
    $iqbc_ips = explode(',', $iqbc_ip_address);

    if (iqblockcountry_is_valid_ipv4(trim($iqbc_ips[0])) || iqblockcountry_is_valid_ipv6(trim($iqbc_ips[0]))) {
        $iqbc_ip_address = filter_var($iqbc_ips[0],FILTER_VALIDATE_IP);
    }
    if ($iqbc_ip_address == $iqbc_server_address) {
        if (isset($_SERVER['REMOTE_ADDR']) && rest_is_ip_address($_SERVER['REMOTE_ADDR'])  ) { $iqbc_ip_address = filter_var($_SERVER['REMOTE_ADDR'],FILTER_VALIDATE_IP); }
        else { $iqbc_ip_address = "0.0.0.0"; }

    }
    return $iqbc_ip_address;
}


function iqblockcountry_upgrade()
{
    /* Check if update is necessary */
    $iqbc_dbversion = get_option('blockcountry_version');
    update_option('blockcountry_version', IQDBVERSION);

    if ($iqbc_dbversion != "" && version_compare($iqbc_dbversion, "1.2.10", '<') ) {
        $iqbc_server_addr = "";
        if( isset( $_SERVER['SERVER_ADDR']) && rest_is_ip_address( $_SERVER['SERVER_ADDR'] ) ) {
            $iqbc_server_addr = filter_var($_SERVER['SERVER_ADDR'],FILTER_VALIDATE_IP);
        } elseif( array_key_exists('LOCAL_ADDR', $_SERVER) && rest_is_ip_address( $_SERVER['LOCAL_ADDR'] ) ) {
            $iqbc_server_addr = filter_var($_SERVER['LOCAL_ADDR'],FILTER_VALIDATE_IP);
}
        
        if (get_option('blockcountry_backendwhitelist') === false || (get_option('blockcountry_backendwhitelist') == "")) { update_option('blockcountry_backendwhitelist', $iqbc_server_addr . ";"); 
        }
        else
        {
            $iqbc_tmpbackendallowlist = get_option('blockcountry_backendwhitelist');
            $iqbc_ippos = strpos($iqbc_tmpbackendallowlist, $iqbc_server_addr);
            if ($iqbc_ippos === false) {
                $iqbc_tmpbackendallowlist .= $iqbc_server_addr . ";";
                update_option('blockcountry_backendwhitelist', $iqbc_tmpbackendallowlist);
            }
        }
        
    }
    iqblockcountry_update_db_check();
   
}


/**
 * Main plugin works.
 **/
$iqbc_upload_dir = wp_upload_dir();
define("CHOSENJS", plugins_url('/js/chosen.jquery.js', __FILE__));
define("CHOSENCSS", plugins_url('/chosen.css', __FILE__));
define("CHOSENCUSTOM", plugins_url('/js/chosen.custom.js', __FILE__));
define("IQBCMAXMINDURL", "https://dev.maxmind.com/geoip/geoip2/geolite2/");
define("IQBCGEOIP2DBFILE", $iqbc_upload_dir['basedir'] . "/GeoLite2-Country.mmdb");
define("IQBCTRACKINGURL", "https://tracking.webence.nl/iq-block-country-tracking.php");
define("IQBCBANLISTRETRIEVEURL", "https://eu.adminblock.webence.nl/iq-block-country-retrieve.php");
define("GEOIPAPIURL", "https://eu.geoip.webence.nl/geoipapi.php");
define("GEOIPAPIURLEU2", "https://eu2.geoip.webence.nl/geoipapi.php");
define("GEOIPAPIURLEU3", "https://eu3.geoip.webence.nl/geoipapi.php");
define("GEOIPAPIURLEU4", "https://eu4.geoip.webence.nl/geoipapi.php");
define("GEOIPAPIURLUS", "https://us.geoip.webence.nl/geoipapi.php");
define("GEOIPAPIURLUS2", "https://us2.geoip.webence.nl/geoipapi.php");
define("GEOIPAPIURLUS3", "https://us3.geoip.webence.nl/geoipapi.php");
define("GEOIPAPIURLASIA", "https://asia.geoip.webence.nl/geoipapi.php");
define("GEOIPAPICHECKURL", "https://eu.geoip.webence.nl/geoipapi-keycheck.php");
define("GEOIPAPICHECKUSAGEURL", "https://eu.geoip.webence.nl/geoipapi-usage.php");
define("ADMINAPICHECKURL", "https://tracking.webence.nl/adminapi-keycheck.php");
define("IQVERSION", "1.2.15");
define("IQDBVERSION", "122");
define("IQBCPLUGINPATH", plugin_dir_path(__FILE__)); 



/*
 * Include libraries
 */
require_once 'libs/blockcountry-geoip.php';
require_once 'libs/blockcountry-checks.php';
require_once 'libs/blockcountry-settings.php';
require_once 'libs/blockcountry-validation.php';
require_once 'libs/blockcountry-logging.php';
require_once 'libs/blockcountry-tracking.php';
require_once 'libs/blockcountry-search-engines.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

global $iqbc_apiblocklist;
$iqbc_apiblocklist = false;
$iqbc_backendblocklistcheck = false;

$blockcountry_is_login_page = iqblockcountry_is_login_page();
$blockcountry_is_xmlrpc = iqblockcountry_is_xmlrpc();

register_activation_hook(__file__, 'iqblockcountry_this_plugin_first');
register_activation_hook(__file__, 'iqblockcountry_set_defaults');
register_uninstall_hook(__file__, 'iqblockcountry_uninstall');

 // Check if upgrade is necessary
 iqblockcountry_upgrade();
  
 /* Clean logging database */
iqblockcountry_clean_db();
iqblockcountry_get_blockallowlist(); 
 
if (isset($_POST['iqbc_action'])) {       

    $iqbc_iqaction = sanitize_text_field($_POST['iqbc_action']);
    if ($iqbc_iqaction == 'iqbc_csvoutput') {
        if(!function_exists('is_user_logged_in')) {
            include ABSPATH . "wp-includes/pluggable.php"; 
        }
    
        if (is_user_logged_in() && is_admin() && check_admin_referer('iqbc_iqblockcountrycsv')) {
            global $wpdb;
            $iqbc_output = "";
            $iqbc_table_name = $wpdb->prefix . "iqblock_logging";
            $iqbc_format = get_option('date_format') . ' ' . get_option('time_format');
            $iqbc_sql = "DELETE FROM " . $iqbc_table_name . " WHERE `datetime` < DATE_SUB(NOW(), INTERVAL 14 DAY);";

            foreach ($wpdb->get_results("SELECT * FROM $iqbc_table_name ORDER BY datetime ASC") as $iqbc_row)
            {
                $iqbc_datetime = strtotime($iqbc_row->datetime);
                $iqbc_mysqldate = date($iqbc_format, $iqbc_datetime);
                $iqbc_output .= '"' . esc_html($iqbc_mysqldate) . '"' . ';"' . esc_html($iqbc_row->ipaddress) . '";"' . esc_url($iqbc_row->url) . '"'. "\n";
            }
            $iqbc_iqtempvalue = preg_replace("/[^A-Za-z0-9]/", "", get_bloginfo());
            $iqbc_filename = $iqbc_iqtempvalue . "-iqblockcountry-logging-export.csv";
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=$iqbc_filename");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $iqbc_output;
            exit();
        }
    }
}

    $iqbc_ip_address = iqblockcountry_get_ipaddress();
    $iqbc_country = iqblockcountry_check_ipaddress($iqbc_ip_address);
    iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, '');

    
function iqbc_add_my_scripts()
{
    $iqbc_iqscreen = get_current_screen();
    if ($iqbc_iqscreen->id == 'settings_page_iq-block-country/libs/blockcountry-settings' ) {
        // Scripts
        wp_enqueue_script('chosen', CHOSENJS, array( 'jquery' ), false, true);
        wp_enqueue_script('custom', CHOSENCUSTOM, array( 'jquery', 'chosen' ), false, true);
        wp_enqueue_style( 'chosenstylecss', CHOSENCSS );
    } 
}

add_action('admin_enqueue_scripts', 'iqbc_add_my_scripts');    
   

  /*
 * Check first if users want to block the backend.
 */
if (($blockcountry_is_login_page || is_admin() || $blockcountry_is_xmlrpc) && get_option('blockcountry_blockbackend') == 'on') {
    add_action('init', 'iqblockcountry_checkCountryBackEnd', 1);
}
elseif ((!$blockcountry_is_login_page && !is_admin() && !$blockcountry_is_xmlrpc) && get_option('blockcountry_blockfrontend') == 'on') {
    add_action('wp', 'iqblockcountry_checkCountryFrontEnd', 1);
} else {
    $iqbc_ip_address = iqblockcountry_get_ipaddress();
    $iqbc_country = iqblockcountry_check_ipaddress($iqbc_ip_address);
    iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'NH');

}

add_action('admin_init', 'iqblockcountry_localization');
add_action('admin_menu', 'iqblockcountry_create_menu');
add_filter('update_option_blockcountry_tracking', 'iqblockcountry_schedule_tracking', 10, 2);
add_filter('add_option_blockcountry_tracking', 'iqblockcountry_schedule_tracking', 10, 2);
add_filter('update_option_blockcountry_apikey', 'iqblockcountry_schedule_retrieving', 10, 2);
add_filter('add_option_blockcountry_apikey', 'iqblockcountry_schedule_retrieving', 10, 2);

add_filter('update_option_blockcountry_debuglogging', 'iqblockcountry_blockcountry_debuglogging', 10, 2);
add_filter('add_option_blockcountry_debuglogging', 'iqblockcountry_blockcountry_debuglogging', 10, 2);
add_action('blockcountry_tracking', 'iqblockcountry_tracking');
add_action('blockcountry_retrievebanlist',  'iqblockcountry_tracking_retrieve_xml');
if (get_option('blockcountry_buffer') == "on") {
    add_action('init', 'iqblockcountry_buffer', 1);
    add_action('shutdown', 'iqblockcountry_buffer_flush');
}


?>