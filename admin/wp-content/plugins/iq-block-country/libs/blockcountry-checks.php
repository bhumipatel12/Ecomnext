<?php


function iqblockcountry_check_ipaddress($iqbc_ip_address)
{
    if (!class_exists('GeoIP')) {
        include_once "geoip.inc";
    }
    
    if (get_option('blockcountry_geoapikey')) {
            $iqbc_country = iqblockcountry_retrieve_geoipapi($iqbc_ip_address);
    }
    elseif ((is_file(IQBCGEOIP2DBFILE))) {
        if (iqblockcountry_is_valid_ipv4($iqbc_ip_address) || iqblockcountry_is_valid_ipv6($iqbc_ip_address)) {
            if (class_exists('GeoIp2\\Database\\Reader')) {
                try {
                    $iqbc_blockreader = new GeoIp2\Database\Reader(IQBCGEOIP2DBFILE);
                    $iqbc_blockrecord = $iqbc_blockreader->country($iqbc_ip_address);
                    $iqbc_country = filter_var($iqbc_blockrecord->country->isoCode, FILTER_SANITIZE_STRING);
                }
                catch(Exception $iqbc_e) {
                    $iqbc_country = "FALSE";
                }
            }
        }
        
    }
    else { $iqbc_country = "Unknown"; 
    }
    
    if (empty($iqbc_country) || $iqbc_country == null ) {
        $iqbc_country = "Unknown";
    }
        
    return $iqbc_country;
}

/*
 * iQ Block Retrieve XML file for API blocking
 */
function iqblockcountry_retrieve_geoipapi($iqbc_ipaddress)
{
    if (iqblockcountry_is_valid_ipv4($iqbc_ipaddress) || iqblockcountry_is_valid_ipv6($iqbc_ipaddress)) { 

        $iqbc_url = GEOIPAPIURL;
        if (get_option('blockcountry_geoapilocation') == "US") {
              $iqbc_url = GEOIPAPIURLUS;
        }
        if (get_option('blockcountry_geoapilocation') == "US2") {
             $iqbc_url = GEOIPAPIURLUS2;
        }
        if (get_option('blockcountry_geoapilocation') == "US3") {
            $iqbc_url = GEOIPAPIURLUS3;
        }
        if (get_option('blockcountry_geoapilocation') == "EU2") {
            $iqbc_url = GEOIPAPIURLEU2;
        }
        if (get_option('blockcountry_geoapilocation') == "EU3") {
            $iqbc_url = GEOIPAPIURLEU3;
        }
        if (get_option('blockcountry_geoapilocation') == "EU4") {
            $iqbc_url = GEOIPAPIURLEU4;
        }
        if (get_option('blockcountry_geoapilocation') == "ASIA") {
            $iqbc_url = GEOIPAPIURLASIA;
        }

        $iqbc_result = wp_remote_post(
            $iqbc_url,
            array(
                'body' => array(
                    'api-key' => get_option('blockcountry_geoapikey'),
                    'ipaddress' => $iqbc_ipaddress
                 
                )
            )
        );    
        if (is_wp_error($iqbc_result) ) {
             return "Unknown";
        }
        elseif (200 == $iqbc_result['response']['code'] ) {
            $iqbc_body = $iqbc_result['body'];
              $iqbc_xml = new SimpleXmlElement($iqbc_body);
              // FIX: Check against countries
            if (isset($iqbc_xml->country)) {  return (string) $iqbc_xml->country; 
            }
            elseif (isset($iqbc_xml->error)) { 
                if (strpos($iqbc_xml->error, 'License expired on ') !== false) {
                      update_option('blockcountry_geoapikey', '');
                      return "Expired";
                }
            }
            else { return "Unknown"; 
            }
        }
        elseif (403 == $iqbc_result['response']['code'] ) {
             update_option('blockcountry_geoapikey', '');
        }
        else { return "Unknown";
        }
    }
    else 
    { return "Invalid"; 
    }
}



/**
 *  Check country against bad countries, allow list and block list
 **/
function iqblockcountry_check($iqbc_country,$iqbc_badcountries,$iqbc_ip_address)
{
    /* Set default blocked status and get all options */
    $iqbc_blocked = false; 
    $iqbc_blockedpage = get_option('blockcountry_blockpages');
    //$iqbc_blockedpages = get_option('blockcountry_pages');
    $iqbc_pagesbanlist = get_option('blockcountry_pages');
    if (!is_array($iqbc_pagesbanlist)) { $iqbc_pagesbanlist = array(); 
    }
    if (get_option('blockcountry_blockpages_inverse') == 'on') {
        $iqbc_pages = get_pages();
        $iqbc_all_pages = array();
        foreach ( $iqbc_pages as $iqbc_page ) {
            $iqbc_all_pages[$iqbc_page->ID] = $iqbc_page->ID; 
        }
        $iqbc_blockedpages = array_diff($iqbc_all_pages, $iqbc_pagesbanlist);
    } else {
        $iqbc_blockedpages = $iqbc_pagesbanlist;
    }

    $iqbc_blockedcategory = get_option('blockcountry_blockcategories');
    $iqbc_blocktags = get_option('blockcountry_blocktags');
    $iqbc_blockedposttypes = get_option('blockcountry_blockposttypes');
    $iqbc_blockedtag = get_option('blockcountry_blocktag');
    $iqbc_blockedfeed = get_option('blockcountry_blockfeed');
    $iqbc_postid = get_the_ID();

    global $iqbc_feblocklistip,$iqbc_feblocklistiprange4,$iqbc_feblocklistiprange6,$iqbc_feallowlistip,$iqbc_feallowlistiprange4,$iqbc_feallowlistiprange6;
    global $iqbc_beblocklistip,$iqbc_beblocklistiprange4,$iqbc_beblocklistiprange6,$iqbc_beallowlistip,$iqbc_beallowlistiprange4,$iqbc_beallowlistiprange6;
    
    $iqbc_backendbanlistip = unserialize(get_option('blockcountry_backendbanlistip'));
    $iqbc_blockredirect = get_option('blockcountry_redirect');
    
    /* Block if user is in a bad country from frontend or backend. Unblock may happen later */
    if (is_array($iqbc_badcountries) && in_array($iqbc_country, $iqbc_badcountries)) {
        $iqbc_blocked = true;
        global $iqbc_backendblocklistcheck;
        $iqbc_backendblocklistcheck = true;
    }

    global $blockcountry_is_login_page,$blockcountry_is_xmlrpc;
    

    /* Check if requested url is not login page. Else check against frontend allow list / block list. */
    if (!($blockcountry_is_login_page) && !(is_admin()) && !($blockcountry_is_xmlrpc)) {    
        if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feblocklistiprange4, $iqbc_feblocklistiprange6, $iqbc_feblocklistip)) {
            $iqbc_blocked = true;
        }
        if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feallowlistiprange4, $iqbc_feallowlistiprange6, $iqbc_feallowlistip)) {
              $iqbc_blocked = false;
        }
    }
    
    
    if ($blockcountry_is_login_page || is_admin() || $blockcountry_is_xmlrpc) {    
        if (is_array($iqbc_backendbanlistip) &&  in_array($iqbc_ip_address, $iqbc_backendbanlistip)) {
            $iqbc_blocked = true;
            global $iqbc_apiblocklist;
            $iqbc_apiblocklist = true;
        }
        if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_beblocklistiprange4, $iqbc_beblocklistiprange6, $iqbc_beblocklistip)) {
             $iqbc_blocked = true;
        }
        if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_beallowlistiprange4, $iqbc_beallowlistiprange6, $iqbc_beallowlistip)) {
                $iqbc_blocked = false;
        }
        if (iqblockcountry_is_adminajax() && get_option('blockcountry_adminajax')) {
            $iqbc_blocked = false;            
        }
    }

    if ($iqbc_blockedposttypes == "on") {
        $iqbc_blockedposttypes = get_option('blockcountry_posttypes');
        if (is_array($iqbc_blockedposttypes) && in_array(get_post_type($iqbc_postid), $iqbc_blockedposttypes) && ((is_array($iqbc_badcountries) && in_array($iqbc_country, $iqbc_badcountries) || (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feblocklistiprange4, $iqbc_feblocklistiprange6, $iqbc_feblocklistip))))) {
            $iqbc_blocked = true;
            if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feallowlistiprange4, $iqbc_feallowlistiprange6, $iqbc_feallowlistip)) {
                $iqbc_blocked = false;
            }
        }
        else
        {
            $iqbc_blocked = false;
        }
    }
    
    if (is_page() && $iqbc_blockedpage == "on") {
        $iqbc_post = get_post();
        if (is_page($iqbc_blockedpages) && !empty($iqbc_blockedpages) && ((is_array($iqbc_badcountries) && in_array($iqbc_country, $iqbc_badcountries) || (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feblocklistiprange4, $iqbc_feblocklistiprange6, $iqbc_feblocklistip))))) {
            $iqbc_blocked = true;
            if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feallowlistiprange4, $iqbc_feallowlistiprange6, $iqbc_feallowlistip)) {
                $iqbc_blocked = false;
            }
        }
        else
        {
            $iqbc_blocked = false;
        }
    }
    if (is_single() && $iqbc_blockedcategory == "on") {
        $iqbc_blockedcategories = get_option('blockcountry_categories');
        if (!is_array($iqbc_blockedcategories)) { $iqbc_blockedcategories = array(); 
        }
        $iqbc_post_categories = wp_get_post_categories($iqbc_postid);
        $iqbc_flagged = false;
        foreach ($iqbc_post_categories as $iqbc_key => $iqbc_value)
        {
            if (in_array($iqbc_value, $iqbc_blockedcategories)) {
                if (is_single() && ((is_array($iqbc_badcountries) && in_array($iqbc_country, $iqbc_badcountries) || (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feblocklistiprange4, $iqbc_feblocklistiprange6, $iqbc_feblocklistip))))) {
                    $iqbc_flagged = true;
                    if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feallowlistiprange4, $iqbc_feallowlistiprange6, $iqbc_feallowlistip)) {
                        $iqbc_flagged = false;
                    }
                }
            }            
        }
        if ($iqbc_flagged) { $iqbc_blocked = true; 
        } else { $iqbc_blocked = false; 
        }
    }

    if (is_single() && $iqbc_blocktags == "on") {
        $iqbc_previousblock = $iqbc_blocked;
        $iqbc_blockedtags = get_option('blockcountry_tags');
        if (!is_array($iqbc_blockedtags)) { $iqbc_blockedtags = array(); 
        }
        $iqbc_post_tags = get_the_tags($iqbc_postid);
        if (empty($iqbc_post_tags)) { $iqbc_post_tags = array();
        }
        $iqbc_flagged = false;
        foreach ($iqbc_post_tags as $iqbc_tag)
        {
            if (in_array($iqbc_tag->term_id, $iqbc_blockedtags)) {
                if (is_single() && ((is_array($iqbc_badcountries) && in_array($iqbc_country, $iqbc_badcountries) || (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feblocklistiprange4, $iqbc_feblocklistiprange6, $iqbc_feblocklistip))))) {
                    $iqbc_flagged = true;
                    if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feallowlistiprange4, $iqbc_feallowlistiprange6, $iqbc_feallowlistip)) {

                        $iqbc_flagged = false;
                    }
                }
            }            
        }
        if ($iqbc_flagged || $iqbc_previousblock == true) { $iqbc_blocked = true; 
        } else { $iqbc_blocked = false; 
        }
    }

    
    if (is_category() && $iqbc_blockedcategory == "on") {
        $iqbc_flagged = false;
        $iqbc_blockedcategories = get_option('blockcountry_categories');
        if (is_category($iqbc_blockedcategories) && ((is_array($iqbc_badcountries) && in_array($iqbc_country, $iqbc_badcountries) || (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feblocklistiprange4, $iqbc_feblocklistiprange6, $iqbc_feblocklistip))))) {
            $iqbc_flagged = true;
        }
        if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feallowlistiprange4, $iqbc_feallowlistiprange6, $iqbc_feallowlistip)) {

                        $iqbc_flagged = false;
        }
        if ($iqbc_flagged) { $iqbc_blocked = true; 
        } else { $iqbc_blocked = false; 
        }
    }

    
    if (is_tag() && $iqbc_blockedtag == "on") {
        $iqbc_flagged = false;
        if ((is_array($iqbc_badcountries) && in_array($iqbc_country, $iqbc_badcountries) || (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feblocklistiprange4, $iqbc_feblocklistiprange6, $iqbc_feblocklistip)))) {
            $iqbc_flagged = true;
        }
        if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feallowlistiprange4, $iqbc_feallowlistiprange6, $iqbc_feallowlistip)) {

                        $iqbc_flagged = false;
        }
        if ($iqbc_flagged) { $iqbc_blocked = true; 
        } else { $iqbc_blocked = false; 
        }
    }
    elseif (is_tag() && $iqbc_blockedtag == false) {
        $iqbc_blocked = false;
    }

    if (is_feed() && $iqbc_blockedfeed == "on") {
        $iqbc_flagged = false;
        if ((is_array($iqbc_badcountries) && in_array($iqbc_country, $iqbc_badcountries) || (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feblocklistiprange4, $iqbc_feblocklistiprange6, $iqbc_feblocklistip)))) {
            $iqbc_flagged = true;
        }
        if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feallowlistiprange4, $iqbc_feallowlistiprange6, $iqbc_feallowlistip)) {

                        $iqbc_flagged = false;
        }
        if ($iqbc_flagged) { $iqbc_blocked = true; 
        } else { $iqbc_blocked = false; 
        }
    }
    elseif (is_feed() && $iqbc_blockedfeed == false) {
        $iqbc_blocked = false;
    }
    
    if (is_home() && (get_option('blockcountry_blockhome')) == false && $iqbc_blockedcategory == "on") {
        $iqbc_blocked = false;
    }
    if (is_page($iqbc_blockredirect) && ($iqbc_blockredirect != 0) && !(empty($iqbc_blockredirect))) {
        $iqbc_blocked = false;
    }
    
    $iqbc_allowse = get_option('blockcountry_allowse');
    if (!$blockcountry_is_login_page && isset($_SERVER['HTTP_USER_AGENT']) && iqblockcountry_check_searchengine($_SERVER['HTTP_USER_AGENT'], $iqbc_allowse)) {
        $iqbc_blocked = false;
    }
    
    if (is_search() && (get_option('blockcountry_blocksearch')) == false) {
        $iqbc_blocked = false;
    }

    return $iqbc_blocked;
}

/*
 * 
 * Does the real check of visitor IP against MaxMind database or the GeoAPI
 * 
 */
function iqblockcountry_CheckCountryBackEnd()
{
    $iqbc_ip_address = iqblockcountry_get_ipaddress();
    $iqbc_country = iqblockcountry_check_ipaddress($iqbc_ip_address);
    global $blockcountry_is_login_page,$blockcountry_is_xmlrpc;
    if (($blockcountry_is_login_page || is_admin() || $blockcountry_is_xmlrpc) && get_option('blockcountry_blockbackend') == 'on') { 
        $iqbc_banlist = get_option('blockcountry_backendbanlist');
        if (!is_array($iqbc_banlist)) { $iqbc_banlist = array(); 
        }
        if (get_option('blockcountry_backendbanlist_inverse') == 'on') {
            $iqbc_all_countries = array_keys(iqblockcountry_get_isocountries());
            $iqbc_badcountries = array_diff($iqbc_all_countries, $iqbc_banlist);
        } else {
            $iqbc_badcountries = $iqbc_banlist;
        }
    }

    $iqbc_blocklogin = get_option('blockcountry_blocklogin');
    if (((is_user_logged_in()) && ($iqbc_blocklogin != "on")) || (!(is_user_logged_in())) ) {            

        /* Check ip address against banlist, allow list and block list */
        if (iqblockcountry_check($iqbc_country, $iqbc_badcountries, $iqbc_ip_address)) {        
            if (($blockcountry_is_login_page || is_admin() || $blockcountry_is_xmlrpc) && get_option('blockcountry_blockbackend') == 'on') {
                $iqbc_blocked = get_option('blockcountry_backendnrblocks');
                if (empty($iqbc_blocked)) { $iqbc_blocked = 0; 
                }
                $iqbc_blocked++;
                update_option('blockcountry_backendnrblocks', $iqbc_blocked);
                global $iqbc_apiblocklist,$iqbc_backendblocklistcheck,$iqbc_debughandled;
                if (!get_option('blockcountry_logging')) {
                    if (!$iqbc_apiblocklist) {    
                        iqblockcountry_logging($iqbc_ip_address, $iqbc_country, "B");
                        iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'BB');
                    }
                    elseif ($iqbc_backendblocklistcheck && $iqbc_apiblocklist) {
                        iqblockcountry_logging($iqbc_ip_address, $iqbc_country, "T");
                        iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'TB');
                    }
                    else
                    {
                        iqblockcountry_logging($iqbc_ip_address, $iqbc_country, "A");   
                        iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'AB');
                    }
                }
            }
            else
                {
                $iqbc_blocked = get_option('blockcountry_frontendnrblocks');
                if (empty($iqbc_blocked)) { $iqbc_blocked = 0; 
                }
                $iqbc_blocked++;
                update_option('blockcountry_frontendnrblocks', $iqbc_blocked);
                if (!get_option('blockcountry_logging')) {
                    iqblockcountry_logging($iqbc_ip_address, $iqbc_country, "F");
                    iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'FB');
                }
            }
            
                
            $iqbc_blockmessage = get_option('blockcountry_blockmessage');
                $iqbc_blockredirect = esc_url_raw(get_option('blockcountry_redirect'));
                $iqbc_blockredirect_url = esc_url_raw(get_option('blockcountry_redirect_url'));
                $iqbc_header = sanitize_text_field(get_option('blockcountry_header'));
            if (!empty($iqbc_header) && ($iqbc_header)) {
                // Prevent as much as possible that this error message is cached:
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");
                header("Expires: Sat, 26 Jul 2021 05:00:00 GMT"); 
                                
                header('HTTP/1.1 403 Forbidden');
            }
            if (!empty($iqbc_blockredirect_url)) {
                header("Location: $iqbc_blockredirect_url");
            }
            elseif (!empty($iqbc_blockredirect) && $iqbc_blockredirect != 0) {
                $iqbc_redirecturl = get_permalink($iqbc_blockredirect);
                header("Location: $iqbc_redirecturl");
            }
                // Display block message
                print esc_html($iqbc_blockmessage);


            exit();
        }    
        else
        {
            iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'NB');
        }
    }
    else
    {
            iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'NB');
    }
}

/*
 * 
 * Does the real check of visitor IP against MaxMind database or the GeoAPI FrontEnd
 * 
 */
function iqblockcountry_CheckCountryFrontEnd()
{
    $iqbc_ip_address = iqblockcountry_get_ipaddress();
    $iqbc_country = iqblockcountry_check_ipaddress($iqbc_ip_address);
        $iqbc_banlist = get_option('blockcountry_banlist');
    if (!is_array($iqbc_banlist)) { $iqbc_banlist = array(); 
    }
    if (get_option('blockcountry_banlist_inverse') == 'on') {
        $iqbc_all_countries = array_keys(iqblockcountry_get_isocountries());
        $iqbc_badcountries = array_diff($iqbc_all_countries, $iqbc_banlist);
    } else {
        $iqbc_badcountries = $iqbc_banlist;
    }

    $iqbc_blocklogin = get_option('blockcountry_blocklogin');
    if (((is_user_logged_in()) && ($iqbc_blocklogin != "on")) || (!(is_user_logged_in())) ) {            

        /* Check ip address against banlist, allow list and block list */
        if (iqblockcountry_check($iqbc_country, $iqbc_badcountries, $iqbc_ip_address)) {       
                    $iqbc_blocked = get_option('blockcountry_frontendnrblocks');
            if (empty($iqbc_blocked)) { $iqbc_blocked = 0; 
            }
                    $iqbc_blocked++;
                    update_option('blockcountry_frontendnrblocks', $iqbc_blocked);
            if (!get_option('blockcountry_logging')) {
                iqblockcountry_logging($iqbc_ip_address, $iqbc_country, "F");
                iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'FB');
            }
            
                
            $iqbc_blockmessage = get_option('blockcountry_blockmessage');
                $iqbc_blockredirect = esc_url(get_option('blockcountry_redirect'));
                $iqbc_blockredirect_url = esc_url(get_option('blockcountry_redirect_url'));
                $iqbc_header = get_option('blockcountry_header');
            if (!empty($iqbc_header) && ($iqbc_header)) {
                // Prevent as much as possible that this error message is cached:
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");
                header("Expires: Sat, 26 Jul 2012 05:00:00 GMT"); 
                                
                header('HTTP/1.1 403 Forbidden');
            }
            if (!empty($iqbc_blockredirect_url)) {
                header("Location: $iqbc_blockredirect_url");
            }
            elseif (!empty($iqbc_blockredirect) && $iqbc_blockredirect != 0) {
                $iqbc_redirecturl = get_permalink($iqbc_blockredirect);
                header("Location: $iqbc_redirecturl");
            }
                // Display block message
                print esc_html($iqbc_blockmessage);

            exit();
        }    
        else
        {
            iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'NB');
        }
    }
    else
    {
            iqblockcountry_debug_logging($iqbc_ip_address, $iqbc_country, 'NB');
    }
}


/**
 * Check if xmlrpc.php is hit.
 *
 * @return bool
 */
function iqblockcountry_is_xmlrpc()
{
    return defined('XMLRPC_REQUEST') && XMLRPC_REQUEST;
}

/*
 * Check for active caching plugins
 */
function iqblockcountry_is_caching_active()
{
    $iqbc_found = false;


    include_once ABSPATH . 'wp-admin/includes/plugin.php'; 
    if (is_plugin_active('w3-total-cache/w3-total-cache.php') ) {
        $iqbc_found = true;
    } 
    if (is_plugin_active('hyper-cache/plugin.php') ) {
        $iqbc_found = true;
    } 

    if (get_option('blockcountry_blockfrontend') == false) {
        $iqbc_found = false;
    }
    
    return $iqbc_found;
}

/*
 * Check if page is the login page
 */
function iqblockcountry_is_login_page()
{
    $iqbc_found = false;
    $iqbc_pos2 = false;
   
    include_once ABSPATH . 'wp-admin/includes/plugin.php'; 
    if (is_plugin_active('all-in-one-wp-security-and-firewall/wp-security.php') ) {
        $iqbc_aio = get_option('aio_wp_security_configs');
        if (!empty($iqbc_aio) && !(empty($iqbc_aio['aiowps_login_page_slug']))) {
            $iqbc_pos2 = strpos($_SERVER['REQUEST_URI'], $iqbc_aio['aiowps_login_page_slug']); 
        }
    } 
 
    if (is_plugin_active('lockdown-wp-admin/lockdown-wp-admin.php') ) {
        $iqbc_ld = get_option('ld_login_base');
        if (!empty($iqbc_ld)) {
            $iqbc_pos2 = strpos($_SERVER['REQUEST_URI'], $iqbc_ld); 
        }
    } 
    
    if (is_plugin_active('wp-simple-firewall/icwp-wpsf.php') ) {
        $iqbc_wpsf = get_option('icwp_wpsf_loginprotect_options');
        if (!empty($iqbc_wpsf['rename_wplogin_path'])) {
            $iqbc_pos2 = strpos($_SERVER['REQUEST_URI'], $iqbc_wpsf['rename_wplogin_path']); 
        }
    } 

    if (is_plugin_active('rename-wp-login/rename-wp-login.php') ) {
        $iqbc_rwpl = get_option('rwl_page');
        if (!empty($iqbc_rwpl)) {
            $iqbc_pos2 = strpos($_SERVER['REQUEST_URI'], $iqbc_rwpl); 
        }
    } 
    
    if (is_plugin_active('wps-hide-login/wps-hide-login.php') ) {
        $iqbc_whlpage = get_option('whl_page');
        if (!empty($iqbc_whlpage)) {
            $iqbc_pos2 = strpos($_SERVER['REQUEST_URI'], $iqbc_whlpage); 
        }
    } 
    
    
   
    $iqbc_pos = strpos($_SERVER['REQUEST_URI'], 'wp-login');
    if ($iqbc_pos !== false) { $iqbc_found = true; 
    }
    elseif ($iqbc_pos2 !== false) { $iqbc_found = true; 
    }
    
    return $iqbc_found;
}


/*
 * Check if page is within wp-admin page
 */
function iqblockcountry_is_admin()
{
    $iqbc_found = false;
   
 
    $iqbc_pos = strpos($_SERVER['REQUEST_URI'], '/wp-admin/');
    if ($iqbc_pos !== false) { $iqbc_found = true; 
    }
    
    return $iqbc_found;
}

/*
 * Check if page is within admin-ajax url.
 */
function iqblockcountry_is_adminajax()
{
    $iqbc_found = false;
   
 
    $iqbc_pos = strpos($_SERVER['REQUEST_URI'], '/wp-admin/admin-ajax.php');
    if ($iqbc_pos !== false) { $iqbc_found = true; 
    }
    
    return $iqbc_found;
}