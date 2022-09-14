<?php

/* Check if the Geo Database exists or if GeoIP API key is entered otherwise display notification */
if (!is_file(IQBCGEOIP2DBFILE) && (!get_option('blockcountry_geoapikey'))) {
    add_action('admin_notices', 'iq_missing_db_notice');
}

/* check if caching plugins are active, if so display notice */
if (iqblockcountry_is_caching_active()) {
    add_action('admin_notices', 'iq_cachingisactive_notice');
}

/**
 * Display missing database notification.
 **/
function iq_missing_db_notice()
{
    if (!is_file(IQBCGEOIP2DBFILE) ) {
        ?> 
        <div class="notice notice-error">
            <h3>iQ Block Country</h3>
            <p><?php esc_html_e('The MaxMind GeoIP2 database does not exist. Please download this file manually or if you wish to use the GeoIP API get an API key from: ', 'iq-block-country'); ?> <a href="https://webence.nl/geoip-api/" target="_blank">https://webence.nl/geoip-api/</a></p>
        <p><?php esc_html_e("Please download the database (GeoLite2-Country.tar.gz) from: ", 'iq-block-country'); ?>
               <?php esc_html_e("If you do not have an account at Maxmind yet for the Geolite2 database sign up for a free account at:", 'iq-block-country'); ?>
               <?php esc_html_e("<a href=\"" . IQBCMAXMINDURL . "\" target=\"_blank\">" . IQBCMAXMINDURL . "</a> "); ?>
               <?php esc_html_e("unzip the file and afterwards upload the GeoLite2-Country.mmdb file to the following location: ", 'iq-block-country'); ?>
                    <b><?php esc_html_e(IQBCGEOIP2DBFILE); ?></b></p>
                   
        <p><?php esc_html_e('For more detailed instructions take a look at the documentation..', 'iq-block-country'); ?></p>
                   
        </div>        
        <?php
    }
}



/*
 * Display missing database notification.
 */
function iq_cachingisactive_notice()
{
    ?> 
        <div class="notice notice-warning is-dismissible">
            <h3>iQ Block Country</h3>
            <p><?php esc_html_e('A caching plugin appears to be active on your WordPress installation.', 'iq-block-country'); ?></p>
            <p><?php esc_html_e('Caching plugins do not always cooperate nicely together with the iQ Block Country plugin which may lead to non blocked visitors getting a cached banned message or page.', 'iq-block-country'); ?></p>
            <p><?php esc_html_e('For more information visit the following page:', 'iq-block-country'); ?> <a target="_blank"href="https://www.webence.nl/questions/iq-block-country-and-caching-plugins/">https://www.webence.nl/questions/iq-block-country-and-caching-plugins/</a></p>
        </div>        
    <?php
}



/*
 * Display missing database notification.
 */
function iq_old_db_notice()
{
    ?> 
        <div class="notice notice-warning">
            <h3>iQ Block Country</h3>
            <p><?php esc_html_e('The MaxMind GeoIP database is older than 3 months. Please update this file manually or if you wish to use the GeoIP API get an API key from: ', 'iq-block-country'); ?><a href="https://webence.nl/geoip-api/" target="_blank">https://webence.nl/geoip-api/</a></p>
        <p><?php esc_html_e("Please download the database (GeoLite2-Country.tar.gz) from MaxMind. ", 'iq-block-country'); ?>
                   <?php esc_html_e("If you do not have an account at Maxmind yet for the Geolite2 database sign up for a free account at:", 'iq-block-country'); ?>
                   <?php esc_html_e("<a href=\"" . IQBCMAXMINDURL . "\" target=\"_blank\">" . IQBCMAXMINDURL . "</a> "); ?>
                   <?php esc_html_e("unzip the file and afterwards upload it to the following location: ", 'iq-block-country'); ?>
                    <b><?php esc_html_e(IQBCGEOIP2DBFILE); ?></b></p>
                   
        <p><?php esc_html_e('For more detailed instructions take a look at the documentation..', 'iq-block-country'); ?></p>
                   
        </div>        
    <?php
}


/*
 * Create the wp-admin menu for iQ Block Country
 */
function iqblockcountry_create_menu() 
{
    //create new menu option in the settings department
    add_submenu_page('options-general.php', 'iQ Block Country', 'iQ Block Country', 'administrator', __FILE__, 'iqblockcountry_settings_page');
    //call register settings function
    add_action('admin_init', 'iqblockcountry_register_mysettings');
}

/*
 * Register all settings.
 */
function iqblockcountry_register_mysettings() 
{
    //register our settings
    register_setting('iqblockcountry-settings-group', 'blockcountry_blockmessage');
        register_setting('iqblockcountry-settings-group', 'blockcountry_redirect');
        register_setting('iqblockcountry-settings-group', 'blockcountry_redirect_url', 'iqblockcountry_is_valid_url');
        register_setting('iqblockcountry-settings-group', 'blockcountry_header');
        register_setting('iqblockcountry-settings-group', 'blockcountry_buffer');
        register_setting('iqblockcountry-settings-group', 'blockcountry_tracking');
        register_setting('iqblockcountry-settings-group', 'blockcountry_nrstatistics');
        register_setting('iqblockcountry-settings-group', 'blockcountry_daysstatistics');
        register_setting('iqblockcountry-settings-group', 'blockcountry_lookupstatistics');
        register_setting('iqblockcountry-settings-group', 'blockcountry_geoapikey', 'iqblockcountry_check_geoapikey');
        register_setting('iqblockcountry-settings-group', 'blockcountry_geoapilocation');
        register_setting('iqblockcountry-settings-group', 'blockcountry_apikey', 'iqblockcountry_check_adminapikey');
        register_setting('iqblockcountry-settings-group', 'blockcountry_debuglogging');
        register_setting('iqblockcountry-settings-group', 'blockcountry_accessibility');
        register_setting('iqblockcountry-settings-group', 'blockcountry_ipoverride');        
        register_setting('iqblockcountry-settings-group', 'blockcountry_logging');
        register_setting('iqblockcountry-settings-group', 'blockcountry_adminajax');
    register_setting('iqblockcountry-settings-group-backend', 'blockcountry_blockbackend');
    register_setting('iqblockcountry-settings-group-backend', 'blockcountry_backendbanlist');
        register_setting('iqblockcountry-settings-group-backend', 'blockcountry_backendbanlist_inverse');
    register_setting('iqblockcountry-settings-group-backend', 'blockcountry_backendblacklist', 'iqblockcountry_validate_ip');
    register_setting('iqblockcountry-settings-group-backend', 'blockcountry_backendwhitelist', 'iqblockcountry_validate_ip');
    register_setting('iqblockcountry-settings-group-frontend', 'blockcountry_banlist');
        register_setting('iqblockcountry-settings-group-frontend', 'blockcountry_banlist_inverse');
    register_setting('iqblockcountry-settings-group-frontend', 'blockcountry_frontendblacklist', 'iqblockcountry_validate_ip');
    register_setting('iqblockcountry-settings-group-frontend', 'blockcountry_frontendwhitelist', 'iqblockcountry_validate_ip');
    register_setting('iqblockcountry-settings-group-frontend', 'blockcountry_blocklogin');
    register_setting('iqblockcountry-settings-group-frontend', 'blockcountry_blocksearch');
    register_setting('iqblockcountry-settings-group-frontend', 'blockcountry_blockfrontend');
    register_setting('iqblockcountry-settings-group-frontend', 'blockcountry_blocktag');
    register_setting('iqblockcountry-settings-group-frontend', 'blockcountry_blockfeed');
        register_setting('iqblockcountry-settings-group-pages', 'blockcountry_blockpages');
        register_setting('iqblockcountry-settings-group-pages', 'blockcountry_blockpages_inverse');
        register_setting('iqblockcountry-settings-group-pages', 'blockcountry_pages');
        register_setting('iqblockcountry-settings-group-posttypes', 'blockcountry_blockposttypes');
        register_setting('iqblockcountry-settings-group-posttypes', 'blockcountry_posttypes');
        register_setting('iqblockcountry-settings-group-cat', 'blockcountry_blockcategories');
        register_setting('iqblockcountry-settings-group-cat', 'blockcountry_categories');
        register_setting('iqblockcountry-settings-group-cat', 'blockcountry_blockhome');
        register_setting('iqblockcountry-settings-group-tags', 'blockcountry_blocktags');
        register_setting('iqblockcountry-settings-group-tags', 'blockcountry_tags');
        register_setting('iqblockcountry-settings-group-se', 'blockcountry_allowse');
}

/**
 * Retrieve an array of all the options the plugin uses. It can't use only one due to limitations of the options API.
 *
 * @return array of options.
 */
function iqblockcountry_get_options_arr()
{
        $iqbc_optarr = array( 'blockcountry_banlist','blockcountry_banlist_inverse', 'blockcountry_backendbanlist','blockcountry_backendbanlist_inverse',
            'blockcountry_backendblacklist','blockcountry_backendwhitelist','blockcountry_frontendblacklist','blockcountry_frontendwhitelist',
            'blockcountry_blockmessage','blockcountry_blocklogin','blockcountry_blockfrontend','blockcountry_blockbackend','blockcountry_header',
            'blockcountry_blockpages','blockcountry_blockpages_inverse','blockcountry_pages','blockcountry_blockcategories','blockcountry_categories','blockcountry_tracking',
            'blockcountry_blockhome','blockcountry_nrstatistics','blockcountry_daysstatistics','blockcountry_lookupstatistics','blockcountry_geoapikey',
            'blockcountry_geoapilocation','blockcountry_apikey','blockcountry_redirect','blockcountry_redirect_url','blockcountry_allowse',
            'blockcountry_debuglogging','blockcountry_buffer','blockcountry_accessibility','blockcountry_ipoverride','blockcountry_logging','blockcountry_blockposttypes',
            'blockcountry_posttypes','blockcountry_blocksearch','blockcountry_adminajax','blockcountry_blocktag','blockcountry_blockfeed','blockcountry_blocktags','blockcountry_tags');
        return apply_filters('iqblockcountry_options', $iqbc_optarr);
}


/*
 * Set default values when activating this plugin.
 */
function iqblockcountry_set_defaults() 
{
        update_option('blockcountry_version', IQDBVERSION);
        $iqbc_countrylist = iqblockcountry_get_isocountries();
        $iqbc_ip_address = iqblockcountry_get_ipaddress();
        $usercountry = iqblockcountry_check_ipaddress($iqbc_ip_address);
        
        $iqbc_server_addr = "";
        if( isset( $_SERVER['SERVER_ADDR']) && rest_is_ip_address( $_SERVER['SERVER_ADDR'] ) ) {
            $iqbc_server_addr = filter_var($_SERVER['SERVER_ADDR'],FILTER_VALIDATE_IP);
        } elseif( array_key_exists('LOCAL_ADDR', $_SERVER) && rest_is_ip_address( $_SERVER['LOCAL_ADDR'] ) ) {
            $iqbc_server_addr = filter_var($_SERVER['LOCAL_ADDR'],FILTER_VALIDATE_IP);
}
        
       
    if (get_option('blockcountry_blockfrontend') === false) { update_option('blockcountry_blockfrontend', 'on'); 
    }
    if (get_option('blockcountry_blockfeed') === false) { update_option('blockcountry_blockfeed', 'on'); 
    }
    if (get_option('blockcountry_backendnrblocks') === false) { update_option('blockcountry_backendnrblocks', 0); 
    }
    if (get_option('blockcountry_frontendnrblocks') === false) { update_option('blockcountry_frontendnrblocks', 0); 
    }
    if (get_option('blockcountry_header') === false) { update_option('blockcountry_header', 'on'); 
    }
    if (get_option('blockcountry_nrstatistics') === false) { update_option('blockcountry_nrstatistics', 15); 
    }
    if (null === get_option('blockcountry_daysstatistics', null) ) { update_option('blockcountry_daysstatistics', 30); 
    }
    if (get_option('blockcountry_backendwhitelist') === false || (get_option('blockcountry_backendwhitelist') == "")) { update_option('blockcountry_backendwhitelist', $iqbc_ip_address . ";"); 
    }
        $iqbc_tmpbackendallowlist = get_option('blockcountry_backendwhitelist');
        $iqbc_ippos = strpos($iqbc_tmpbackendallowlist, $iqbc_server_addr);
    if ($iqbc_ippos === false) {
        $iqbc_tmpbackendallowlist .= $iqbc_server_addr . ";";
        update_option('blockcountry_backendwhitelist', $iqbc_tmpbackendallowlist);
    }
    if (get_option('blockcountry_frontendwhitelist') === false || (get_option('blockcountry_frontendwhitelist') == "")) { update_option('blockcountry_frontendwhitelist', $iqbc_server_addr . ";"); 
    }        
        iqblockcountry_install_db();       
    if (get_option('blockcountry_banlist_inverse') === false) { update_option('blockcountry_banlist_inverse', 'off'); 
    }
    if (get_option('blockcountry_backendbanlist_inverse') === false) { update_option('blockcountry_backendbanlist_inverse', 'off'); 
    }
    if (get_option('blockcountry_ipoverride') === false) { update_option('blockcountry_ipoverride', 'NONE'); 
    }
}


function iqblockcountry_uninstall() //deletes all the database entries that the plugin has created
{
        iqblockcountry_uninstall_db();
        iqblockcountry_uninstall_loggingdb();
        delete_option('blockcountry_banlist');
        delete_option('blockcountry_banlist_inverse');
    delete_option('blockcountry_backendbanlist');
        delete_option('blockcountry_backendbanlist_inverse');
    delete_option('blockcountry_backendblacklist');
    delete_option('blockcountry_backendwhitelist');
    delete_option('blockcountry_frontendblacklist');
    delete_option('blockcountry_frontendwhitelist');
    delete_option('blockcountry_blockmessage');
    delete_option('blockcountry_backendnrblocks');
    delete_option('blockcountry_frontendnrblocks');
    delete_option('blockcountry_blocklogin');
    delete_option('blockcountry_blockfrontend');
    delete_option('blockcountry_blockbackend');
        delete_option('blockcountry_version');
        delete_option('blockcountry_header');
        delete_option('blockcountry_blockpages');    
        delete_option('blockcountry_blockpages_inverse');
        delete_option('blockcountry_pages');
        delete_option('blockcountry_blockcategories');
        delete_option('blockcountry_categories');
        delete_option('blockcountry_lasttrack');
        delete_option('blockcountry_tracking');
        delete_option('blockcountry_blockhome');
        delete_option('blockcountry_backendbanlistip');
        delete_option('blockcountry_nrstastistics');
        delete_option('blockcountry_daysstatistics');
        delete_option('blockcountry_lookupstatistics');
        delete_option('blockcountry_geoapikey');
        delete_option('blockcountry_geoapilocation');
        delete_option('blockcountry_apikey');
        delete_option('blockcountry_redirect');
        delete_option('blockcountry_redirect_url');
        delete_option('blockcountry_allowse');
        delete_option('blockcountry_debuglogging');
        delete_option('blockcountry_buffer');
        delete_option('blockcountry_accessibility');
        delete_option('blockcountry_ipoverride');
        delete_option('blockcountry_logging');
        delete_option('blockcountry_blockposttypes');
        delete_option('blockcountry_posttypes');
        delete_option('blockcountry_blocksearch');
        delete_option('blockcountry_adminajax');
        delete_option('blockcountry_blocktag');
        delete_option('blockcountry_blocktags');
        delete_option('blockcountry_blockfeed');
        delete_option('blockcountry_tags');
}



function iqblockcountry_settings_tools()
{
    ?>
        <h3><?php esc_html_e('Check which country belongs to an IP Address according to the current database.', 'iq-block-country'); ?></h3>
   
    <form name="ipcheck" action="#ipcheck" method="post">
        <input type="hidden" name="iqbc_action" value="iqbc_ipcheck" />
        <input name="iqbc_ipcheck_nonce" type="hidden" value="<?php echo wp_create_nonce('iqbc_ipcheck_nonce'); ?>" />
        <?php esc_html_e('IP Address to check:', 'iq-block-country'); ?> <input type="text" name="iqbc_ipaddress" lenth="50" />
    <?php 
        global $iqbc_feblocklistip,$iqbc_feblocklistiprange4,$iqbc_feblocklistiprange6,$iqbc_feallowlistip,$iqbc_feallowlistiprange4,$iqbc_feallowlistiprange6;
        global $iqbc_beblocklistip,$iqbc_beblocklistiprange4,$iqbc_beblocklistiprange6,$iqbc_beallowlistip,$iqbc_beallowlistiprange4,$iqbc_beallowlistiprange6;


    if (isset($_POST['iqbc_action']) && $_POST[ 'iqbc_action' ] == 'iqbc_ipcheck') {
        if (!isset($_POST['iqbc_ipcheck_nonce'])) { die("Failed security check.");
        }
        if (!wp_verify_nonce($_POST['iqbc_ipcheck_nonce'], 'iqbc_ipcheck_nonce')) { die("Is this a CSRF attempts?");
        }
        if (isset($_POST['iqbc_ipaddress']) && !empty($_POST['iqbc_ipaddress'])) {
            if (iqblockcountry_is_valid_ipv4($_POST['iqbc_ipaddress']) || iqblockcountry_is_valid_ipv6($_POST['iqbc_ipaddress'])) {
                if (filter_var($_POST['iqbc_ipaddress'], FILTER_VALIDATE_IP)) { $iqbc_ip_address = $_POST['iqbc_ipaddress']; 
                }
                    $iqbc_country = iqblockcountry_check_ipaddress($iqbc_ip_address);
                    $iqbc_countrylist = iqblockcountry_get_isocountries();
                if ($iqbc_country == "Unknown" || $iqbc_country == "ipv6" || $iqbc_country == "" || $iqbc_country == "FALSE") {
                    echo "<p>" .  esc_html('No country for', 'iq-block-country') . ' ' . esc_html($iqbc_ip_address) . ' ' .  esc_html('could be found. Or', 'iq-block-country') . ' ' . esc_html($iqbc_ip_address) . ' ' .  esc_html('is not a valid IPv4 or IPv6 IP address', 'iq-block-country'); 
                    echo "</p>";
                }
                else {
                    $iqbc_displaycountry = $iqbc_countrylist[$iqbc_country];
                    echo "<p>" .  esc_html('IP Adress', 'iq-block-country') . ' ' . esc_html($iqbc_ip_address) . ' ' .  esc_html('belongs to', 'iq-block-country') . ' ' . esc_html($iqbc_displaycountry) . ".</p>";
                    $iqbc_haystack = get_option('blockcountry_banlist');
                    if (!is_array($iqbc_haystack)) { $iqbc_haystack = array(); 
                    }
                    $iqbc_inverse = get_option('blockcountry_banlist_inverse');
                    if ($iqbc_inverse == "on") {
                        if (is_array($iqbc_haystack) && !in_array($iqbc_country, $iqbc_haystack)) {
                            esc_html_e('This country is not permitted to visit the frontend of this website.', 'iq-block-country');
                            echo "<br />";
                        }
                    } else {                           
                        if (is_array($iqbc_haystack) && in_array($iqbc_country, $iqbc_haystack)) {
                            esc_html_e('This country is not permitted to visit the frontend of this website.', 'iq-block-country');
                            echo "<br />";
                        }
                    }
                        $iqbc_inverse = get_option('blockcountry_backendbanlist_inverse');
                        $iqbc_haystack = get_option('blockcountry_backendbanlist');
                    if (!is_array($iqbc_haystack)) { $iqbc_haystack = array(); 
                    }
                    if ($iqbc_inverse == "on") {
                        if (is_array($iqbc_haystack) && !in_array($iqbc_country, $iqbc_haystack)) {
                            esc_html_e('This country is not permitted to visit the backend of this website.', 'iq-block-country');
                            echo "<br />";
                        }
                    }
                    else
                        {    
                        if (is_array($iqbc_haystack) && in_array($iqbc_country, $iqbc_haystack)) {
                            esc_html_e('This country is not permitted to visit the backend of this website.', 'iq-block-country');
                            echo "<br />";
                        }
                    }
                        $iqbc_backendbanlistip = unserialize(get_option('blockcountry_backendbanlistip'));
                    if (is_array($iqbc_backendbanlistip) &&  in_array($iqbc_ip_address, $iqbc_backendbanlistip)) {
                        esc_html_e('This IP address is present in the block list.', 'iq-block-country');
                    }

                }
                if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feblocklistiprange4, $iqbc_feblocklistiprange6, $iqbc_feblocklistip)) {
                    esc_html_e('This IP address is present in the frontend block list.', 'iq-block-country');
                    echo "<br />";
                }
                if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_feallowlistiprange4, $iqbc_feallowlistiprange6, $iqbc_feallowlistip)) {
                    esc_html_e('This IP address is present in the frontend allow list.', 'iq-block-country');
                    echo "<br />";
                }
                if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_beblocklistiprange4, $iqbc_beblocklistiprange6, $iqbc_beblocklistip)) {
                    esc_html_e('This IP address is present in the backend block list.', 'iq-block-country');
                    echo "<br />";
                }
                if (iqblockcountry_validate_ip_in_list($iqbc_ip_address, $iqbc_beallowlistiprange4, $iqbc_beallowlistiprange6, $iqbc_beblocklistip)) {
                    esc_html_e('This IP address is present in the backend allow list.', 'iq-block-country');
                    echo "<br />";
                }

            }
        }    
    }
        echo '<div class="submit"><input type="submit" name="test" value="' .  esc_html('Check IP address', 'iq-block-country') . '" /></div>';
        wp_nonce_field('iqblockcountry');

    ?>        
        </form>

        <hr />

        <h3><?php esc_html_e('Database information', 'iq-block-country'); ?></h3>
        <?php
        
        $iqbc_format = get_option('date_format') . ' ' . get_option('time_format');
        if (!get_option('blockcountry_geoapikey')) {        
            if (is_file(IQBCGEOIP2DBFILE)) {
            
                esc_html_e("GeoIP2 database exists. File date: ", 'iq-block-country');
                $iqbc_filedate = filemtime(IQBCGEOIP2DBFILE);
                echo esc_html(date($iqbc_format, $iqbc_filedate));
                echo " ";
                $iqbc_3months = time() - 3 * 31 * 86400;
                if ($iqbc_filedate < $iqbc_3months) { 
                    esc_html_e("Database is older than 3 months... Please update...", 'iq-block-country');
                }  
            }
            else
            {
                esc_html_e("GeoIP2 database does not exist.", 'iq-block-country');
            }
            echo "<br />";
        }
        if (get_option('blockcountry_geoapikey')) {
            $iqbc_licensedate = strtotime(iqblockcountry_get_licensedate_geoapikey(get_option('blockcountry_geoapikey')));
            esc_html_e("Your GeoIP API key is valid till: ", 'iq-block-country');
            esc_html_e(date($iqbc_format, $iqbc_licensedate));
            echo "<br />";
            $iqbc_usagerequests = iqblockcountry_get_usage_geoapikey(get_option('blockcountry_geoapikey'));
            esc_html_e("Your GeoIP API usage this month: ", 'iq-block-country');
            esc_html_e($iqbc_usagerequests);
        }
        ?>
    <br />
    <br />

    <h3><?php esc_html_e('Reset Counters', 'iq-block-country'); ?></h3>
        
            <div class="wrap">  
            <div id="icon-tools" class="icon32"><br /></div>  
            <p><?php esc_html_e('When you click on the Reset Counter button the counters of total Frontend & Backend blocks will be set to 0.', 'iq-block-country'); ?></p>  
            
            <?php                     $iqbc_blocked = get_option('blockcountry_backendnrblocks'); ?>
            <p><?php esc_html_e(number_format($iqbc_blocked)); ?> <?php esc_html_e('visitors blocked from the backend.', 'iq-block-country'); ?></p>
            <?php                     $iqbc_blocked = get_option('blockcountry_frontendnrblocks'); ?>
            <p><?php esc_html_e(number_format($iqbc_blocked)); ?> <?php esc_html_e('visitors blocked from the frontend.', 'iq-block-country'); ?></p>
            
            <form method='post'>  
                <p class="submit">  
                    <?php wp_nonce_field('iqblockresetcounter'); ?>  
                    <input type='submit' name='iqbc_resetcounter' value='<?php esc_html_e('Reset Counter', 'iq-block-country'); ?>'/>  
                </p>  
            </form>  
        </div>  
        <?php
        if ((isset($_POST['iqbc_resetcounter'])) && (check_admin_referer('iqblockresetcounter'))) {
            update_option('blockcountry_backendnrblocks', 0);
            update_option('blockcountry_frontendnrblocks', 0);
            esc_html_e("Counters reset", 'iq-block-country');
        }
        ?>
        <h3><?php esc_html_e('Active plugins', 'iq-block-country'); ?></h3>
        <?php
                       
        $iqbc_plugins = get_plugins();
        $iqbc_plugins_string = '';
        
        echo '<table class="widefat">';
        echo '<thead><tr><th>' .  esc_html('Plugin name', 'iq-block-country') . '</th><th>' .  esc_html('Version', 'iq-block-country') . '</th><th>' .  esc_html('URL', 'iq-block-country') . '</th></tr></thead>';
        
        foreach( array_keys($iqbc_plugins) as $iqbc_key ) {
            if (is_plugin_active($iqbc_key) ) {
                $iqbc_plugin =& $iqbc_plugins[$iqbc_key];
                echo "<tbody><tr>";
                    echo '<td>' . esc_html($iqbc_plugin['Name']) . '</td>';
                    echo '<td>' . esc_html($iqbc_plugin['Version']) . '</td>';
                    echo '<td>' . esc_url($iqbc_plugin['PluginURI']) . '</td>';
                echo "</tr></tbody>";
            }
        }
        echo '</table>';
        //echo $iqbc_plugins_string;
        global $wpdb;
        
        $iqbc_disabled_functions = @ini_get('disable_functions');

        if ($iqbc_disabled_functions == '' || $iqbc_disabled_functions === false ) {
                        $iqbc_disabled_functions = '<i>(' .  esc_html('none', 'iq-block-country') . ')</i>';
        }

        $iqbc_disabled_functions = str_replace(', ', ',', $iqbc_disabled_functions); // Normalize spaces or lack of spaces between disabled functions.
        $iqbc_disabled_functions_array = explode(',', $iqbc_disabled_functions);

        $iqbc_php_uid =  esc_html('unavailable', 'iq-block-country');
        $iqbc_php_user =  esc_html('unavailable', 'iq-block-country');


        ?>
        <h3><?php esc_html_e('File System Information', 'iq-block-country'); ?></h3>

        <table class="widefat">
        <tbody><tr><td><?php esc_html_e('Website url', 'iq-block-country'); ?>: <strong><?php echo esc_url(get_site_url()); ?></strong></td></tr></tbody>
        <tbody><tr><td><?php esc_html_e('Document Root Path', 'iq-block-country'); ?>: <strong><?php echo esc_html(filter_var($_SERVER['DOCUMENT_ROOT'], FILTER_SANITIZE_STRING)); ?></strong></td></tr></tbody>
        </table>

        
        <h3><?php esc_html_e('Database Information', 'iq-block-country'); ?></h3>
        <table class="widefat">
        <tbody><tr><td><?php esc_html_e('MySQL Database Version', 'iq-block-country'); ?>: <?php $iqbc_sqlversion = $wpdb->get_var("SELECT VERSION() AS version"); ?><strong><?php echo esc_html($iqbc_sqlversion); ?></strong></td></tr></tbody>
        <tbody><tr><td><?php esc_html_e('MySQL Client Version', 'iq-block-country'); ?>: <strong><?php echo esc_html($wpdb->db_version()); ?></strong></td></tr></tbody>
        <tbody><tr><td><?php esc_html_e('Database Host', 'iq-block-country'); ?>: <strong><?php echo esc_html(DB_HOST); ?></strong></td></tr></tbody>
        <?php $iqbc_mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
        if (is_array($iqbc_mysqlinfo) ) {
                $iqbc_sql_mode = $iqbc_mysqlinfo[0]->Value;
        }
        if (empty($iqbc_sql_mode) ) {
                $iqbc_sql_mode =  esc_html('Not Set', 'iq-block-country');
        } else {
                $iqbc_sql_mode =  esc_html('Off', 'iq-block-country');
        }
        ?>
        <tbody><tr><td><?php esc_html_e('SQL Mode', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_sql_mode); ?></strong></td></tr></tbody>
        </table>
        
        
        <h3><?php esc_html_e('Server Information', 'iq-block-country'); ?></h3>
        
        <table class="widefat">

                <tbody><tr><td><?php esc_html_e('Server Type', 'iq-block-country'); ?>: <strong><?php echo esc_html(filter_var(filter_var($_SERVER['SERVER_SOFTWARE'], FILTER_SANITIZE_STRING))); ?></strong></td></tr></tbody>
                <tbody><tr><td><?php esc_html_e('Operating System', 'iq-block-country'); ?>: <strong><?php echo esc_html(PHP_OS); ?></strong></td></tr></tbody>
                <tbody><tr><td><?php esc_html_e('Browser Compression Supported', 'iq-block-country'); ?>: 
                        <strong><?php echo esc_html(filter_var($_SERVER['HTTP_ACCEPT_ENCODING'], FILTER_SANITIZE_STRING)); ?></strong></td></tr></tbody>
                <?php

                if (is_callable('posix_geteuid') && ( false === in_array('posix_geteuid', $iqbc_disabled_functions_array) ) ) {

                        $iqbc_php_uid = @posix_geteuid();

                    if (is_callable('posix_getpwuid') && ( false === in_array('posix_getpwuid', $iqbc_disabled_functions_array) ) ) {

                            $iqbc_php_user = @posix_getpwuid($iqbc_php_uid);
                            $iqbc_php_user = $iqbc_php_user['name'];

                    }
                }

                $iqbc_php_gid =  esc_html('undefined', 'iq-block-country');

                if (is_callable('posix_getegid') && ( false === in_array('posix_getegid', $iqbc_disabled_functions_array) ) ) {
                        $iqbc_php_gid = @posix_getegid();
                }

                ?>
                <tbody><tr><td><?php esc_html_e('PHP Process User (UID:GID)', 'iq-block-country'); ?>: 
                        <strong><?php echo esc_html($iqbc_php_user) . ' (' . esc_html($iqbc_php_uid) . ':' . esc_html($iqbc_php_gid) . ')'; ?></strong></td></tr></tbody>        
        </table>

        
               <h3><?php esc_html_e('PHP Information', 'iq-block-country'); ?></h3>
        
        <table class="widefat">

            
            <tbody><tr><td><?php esc_html_e('PHP Version', 'iq-block-country'); ?>: <strong><?php echo esc_html(PHP_VERSION); ?></strong></td></tr></tbody>
            <tbody><tr><td><?php esc_html_e('PHP Memory Usage', 'iq-block-country'); ?>: <strong><?php echo esc_html(round(memory_get_usage() / 1024 / 1024, 2)) .  esc_html(' MB', 'iq-block-country'); ?></strong></td></tr></tbody>
                
                <?php
                if (ini_get('memory_limit') ) {
                        $iqbc_memory_limit = filter_var(ini_get('memory_limit'), FILTER_SANITIZE_STRING);
                } else {
                        $iqbc_memory_limit =  esc_html('N/A', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP Memory Limit', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_memory_limit); ?></strong></td></tr></tbody>
                <?php
                if (ini_get('upload_max_filesize') ) {
                        $iqbc_upload_max = filter_var(ini_get('upload_max_filesize'), FILTER_SANITIZE_STRING);
                } else {
                        $iqbc_upload_max =  esc_html('N/A', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP Max Upload Size', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_upload_max); ?></strong></td></tr></tbody>
                <?php
                if (ini_get('post_max_size') ) {
                        $iqbc_post_max = filter_var(ini_get('post_max_size'), FILTER_SANITIZE_STRING);
                } else {
                        $iqbc_post_max =  esc_html('N/A', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP Max Post Size', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_post_max); ?></strong></td></tr></tbody>
                <?php
                if (ini_get('allow_url_fopen') ) {
                        $iqbc_allow_url_fopen =  esc_html('On', 'iq-block-country');
                } else {
                        $iqbc_allow_url_fopen =  esc_html('Off', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP Allow URL fopen', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_allow_url_fopen); ?></strong></td></tr></tbody>
                <?php
                if (ini_get('allow_url_include') ) {
                        $iqbc_allow_url_include =  esc_html('On', 'iq-block-country');
                } else {
                        $iqbc_allow_url_include =  esc_html('Off', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP Allow URL Include'); ?>: <strong><?php echo esc_html($iqbc_allow_url_include); ?></strong></td></tr></tbody>
                <?php
                if (ini_get('display_errors') ) {
                        $iqbc_display_errors =  esc_html('On', 'iq-block-country');
                } else {
                        $iqbc_display_errors =  esc_html('Off', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP Display Errors', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_display_errors); ?></strong></td></tr></tbody>
                <?php
                if (ini_get('display_startup_errors') ) {
                        $iqbc_display_startup_errors =  esc_html('On', 'iq-block-country');
                } else {
                        $iqbc_display_startup_errors =  esc_html('Off', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP Display Startup Errors', 'iq-block-country'); ?>:
                        <strong><?php echo esc_html($iqbc_display_startup_errors); ?></strong></td></tr></tbody>
                <?php
                if (ini_get('expose_php') ) {
                        $iqbc_expose_php =  esc_html('On', 'iq-block-country');
                } else {
                        $iqbc_expose_php =  esc_html('Off', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP Expose PHP', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_expose_php); ?></strong></td></tr></tbody>
                <?php
                if (ini_get('max_execution_time') ) {
                        $iqbc_max_execute = filter_var(ini_get('max_execution_time'));
                } else {
                        $iqbc_max_execute =  esc_html('N/A', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP Max Script Execution Time'); ?>:
                        <strong><?php echo esc_html($iqbc_max_execute); ?> <?php esc_html_e('Seconds'); ?></strong></td></tr></tbody>
                <?php
                if (ini_get('open_basedir') ) {
                        $iqbc_open_basedir =  esc_html('On', 'iq-block-country');
                } else {
                        $iqbc_open_basedir =  esc_html('Off', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP open_basedir', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_open_basedir); ?></strong></td></tr></tbody>
                <?php
                if (is_callable('xml_parser_create') ) {
                        $iqbc_xml =  esc_html('Yes', 'iq-block-country');
                } else {
                        $iqbc_xml =  esc_html('No', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP XML Support', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_xml); ?></strong></td></tr></tbody>
                <?php
                if (is_callable('iptcparse') ) {
                        $iqbc_iptc =  esc_html('Yes', 'iq-block-country');
                } else {
                        $iqbc_iptc =  esc_html('No', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('PHP IPTC Support', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_iptc); ?></strong></td></tr></tbody>
                <?php $iqbc_disabled_functions = str_replace(',', ', ', $iqbc_disabled_functions); // Normalize spaces or lack of spaces between disabled functions. ?>
                <tbody><tr><td><?php esc_html_e('Disabled PHP Functions', 'iq-block-country'); ?>: <strong><?php echo esc_html($iqbc_disabled_functions); ?></strong></td></tr></tbody>
        
        
        </table>
               

        
               <h3><?php esc_html_e('Wordpress info', 'iq-block-country'); ?></h3>
        
        <table class="widefat">
                <?php
                if (is_multisite() ) {
                        $iqbc_multSite =  esc_html('is enabled', 'iq-block-country');
                } else {
                        $iqbc_multSite =  esc_html('is disabled', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e(' Multisite', 'iq-block-country'); ?> <strong><?php echo esc_html($iqbc_multSite); ?></strong></td></tr></tbody>
                <?php
                if (get_option('permalink_structure') != '' ) {
                        $iqbc_permalink_structure =  esc_html('are enabled', 'iq-block-country');
                } else {
                        $iqbc_permalink_structure =  esc_html('are disabled', 'iq-block-country');
                }
                ?>
                <tbody><tr><td><?php esc_html_e('Permalinks', 'iq-block-country'); ?>
                        <strong> <?php echo esc_html($iqbc_permalink_structure); ?></strong></td></tr></tbody>
                <tbody><tr><td><?php esc_html_e('Document Root Path', 'iq-block-country'); ?>: <strong><?php echo esc_html(WP_CONTENT_DIR) ?></strong></td></tr></tbody>
        </table>

        <br />
        <br />
        <h3><?php esc_html_e('IP Address information', 'iq-block-country'); ?></h3>
        
    <?php    
    echo "<br />HTTP_CF_CONNECTING_IP: ";
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && !empty($_SERVER['HTTP_CF_CONNECTING_IP']) ) {
        echo esc_html($_SERVER['HTTP_CF_CONNECTING_IP']);
    }
    else { esc_html_e('Not set', 'iq-block-country'); 
    }
    echo "<br />HTTP_X_SUCURI_CLIENTIP: ";
    if (isset($_SERVER['HTTP_X_SUCURI_CLIENTIP']) && !empty($_SERVER['HTTP_X_SUCURI_CLIENTIP']) ) {
        echo esc_html($_SERVER['HTTP_X_SUCURI_CLIENTIP']);
    }
    else { esc_html_e('Not set', 'iq-block-country'); 
    }
    echo "<br />HTTP_INCAP_CLIENT_IP: ";
    if (isset($_SERVER['HTTP_INCAP_CLIENT_IP']) && !empty($_SERVER['HTTP_INCAP_CLIENT_IP']) ) {
        echo esc_html($_SERVER['HTTP_INCAP_CLIENT_IP']);
    }
    else { esc_html_e('Not set', 'iq-block-country'); 
    }
    echo "<br />HTTP_X_FORWARDED_FOR: ";
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
        echo esc_html($_SERVER['HTTP_X_FORWARDED_FOR']);
    } 
    else { esc_html_e('Not set', 'iq-block-country'); 
    }
    echo "<br />HTTP_X_FORWARDED: ";
    if (isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED']) ) {
        echo esc_html($_SERVER['HTTP_X_FORWARDED']);
    }
    else { esc_html_e('Not set', 'iq-block-country'); 
    }
    echo "<br />HTTP_CLIENT_IP: ";
    if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) ) {
        echo esc_html($_SERVER['HTTP_CLIENT_IP']);
    }
    else { esc_html_e('Not set', 'iq-block-country'); 
    }
    echo "<br />HTTP_X_REAL_IP: ";
    if (isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP']) ) {
        echo esc_html($_SERVER['HTTP_X_REAL_IP']);
    } 
    else { esc_html_e('Not set', 'iq-block-country'); 
    }
    echo "<br />HTTP_FORWARDED: ";
    if (isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED']) ) {
        echo esc_html($_SERVER['HTTP_FORWARDED']);
    }
    else { esc_html_e('Not set', 'iq-block-country'); 
    }
    echo "<br />REMOTE_ADDR: ";
    if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) ) {
        echo esc_html($_SERVER['REMOTE_ADDR']);
    }
    else { esc_html_e('Not set', 'iq-block-country'); 
    }

    ?>
               
    <?php
}

/*
 * Function: Import/Export settings
 */
function iqblockcountry_settings_importexport()
{
    $iqbc_dir = wp_upload_dir();
    if (!isset($_POST['iqbc_export']) && !isset($_POST['iqbc_import'])) {  
        ?>  
        <div class="wrap">  
            <div id="icon-tools" class="icon32"><br /></div>  
            <h2><?php esc_html_e('Export', 'iq-block-country'); ?></h2>  
            <p><?php esc_html_e('When you click on Backup all settings button a backup of the iQ Block Country configuration will be created.', 'iq-block-country'); ?></p>  
            <p><?php esc_html_e('After exporting, you can either use the backup file to restore your settings on this site again or copy the settings to another WordPress site.', 'iq-block-country'); ?></p>  
            <form method='post'>  
                <p class="submit">  
                    <?php wp_nonce_field('iqblockexport'); ?>  
                    <input type='submit' name='iqbc_export' value='<?php esc_html_e('Backup all settings', 'iq-block-country'); ?>'/>  
                </p>  
            </form>  
        </div>  

        <div class="wrap">  
        <div id="icon-tools" class="icon32"><br /></div>  
        <h2><?php esc_html_e('Import', 'iq-block-country'); ?></h2>  
        <p><?php esc_html_e('Click the browse button and choose a zip file that you exported before.', 'iq-block-country'); ?></p>  
        <p><?php esc_html_e('Press Restore settings button, and let WordPress do the magic for you.', 'iq-block-country'); ?></p>  
        <form method='post' enctype='multipart/form-data'>  
            <p class="submit">  
                <?php wp_nonce_field('iqblockimport'); ?>  
                <input type='file' name='iqbc_import' />  
                <input type='submit' name='iqbc_import' value='<?php esc_html_e('Restore settings', 'iq-block-country'); ?>'/>  
            </p>  
        </form>  
        </div>
        <?php  
    }  
    elseif (isset($_POST['iqbc_export'])) {  
  
        $iqbc_date = date("d-m-Y");  
        $iqbc_randstr=rand();
        $iqbc_resultrand = sha1($iqbc_randstr);        
        $iqbc_json_name = "iqblockcountry-".$iqbc_date . "-" . $iqbc_resultrand; // Filename will be generated with random string.  
  
        $iqbc_optarr = iqblockcountry_get_options_arr();
        foreach ( $iqbc_optarr as $iqbc_options ) {
            $iqbc_value = get_option($iqbc_options);  
            $need_options[$iqbc_options] = $iqbc_value;  
        }  
       
        $iqbc_json_file = json_encode($need_options); // Encode data into json data  
  
        if (!$iqbc_handle = fopen($iqbc_dir['path'] . '/' . 'iqblockcountry.ini', 'w') ) {
                        wp_die( esc_html("Something went wrong exporting this file", 'iq-block-country'));
        }

        if (!fwrite($iqbc_handle, $iqbc_json_file) ) {
                        wp_die( esc_html("Something went wrong exporting this file", 'iq-block-country'));
        }

        fclose($iqbc_handle);

        chdir($iqbc_dir['path']);
        $iqbc_zipfiles = array('iqblockcountry.ini');
        $iqbc_zipfilename = $iqbc_dir['path'] . '/' . $iqbc_json_name . '-iqblockcountry.zip';
        $iqbc_zip = new ZipArchive;
        $iqbc_zip->open($iqbc_zipfilename, ZipArchive::CREATE);
        foreach ($iqbc_zipfiles as $iqbc_file) {
            $iqbc_zip->addFile($iqbc_file);
        }
        $iqbc_zip->close();
        unlink($iqbc_dir['path'] . '/iqblockcountry.ini');

        $iqbc_url = $iqbc_dir['url'] . '/' . $iqbc_json_name . '-iqblockcountry.zip';
        $iqbc_content = "<div class='notice notice-success'><p>" .  esc_html("Exporting settings...", 'iq-block-country') . "</p></div>";
        
        ?>
        <script>
            document.location = "<?php echo esc_url_raw($iqbc_url); ?>"
        </script>
        <?php
    }  
    elseif (isset($_POST['iqbc_import'])) { 
        $iqbc_optarr = iqblockcountry_get_options_arr();
        if (isset($_FILES['iqbc_import']) && check_admin_referer('iqblockimport')) {  
            if (($_FILES['iqbc_import']['error'] > 0) && ($_FILES['type'] == "application/x-zip-compressed")) {  
                    wp_die( esc_html("Something went wrong importing this file", 'iq-block-country'));  
            }  
            else 
            {
                    $iqbc_zip = new ZipArchive;
                    $iqbc_res = $iqbc_zip->open($_FILES['iqbc_import']['tmp_name']);
                if ($iqbc_res === true) {
                    $iqbc_zip->extractTo($iqbc_dir['path'], 'iqblockcountry.ini');
                    $iqbc_zip->close();
                } else {
                    wp_die( esc_html("Something went wrong importing this file", 'iq-block-country'));  
                }
                if (file_exists($iqbc_dir['path'] . '/iqblockcountry.ini')) {
                    $encode_options = file_get_contents($iqbc_dir['path'] . '/iqblockcountry.ini');  
                    $iqbc_options = json_decode($encode_options, true);  
                    foreach ($iqbc_options as $iqbc_key => $iqbc_value) {  
                        if (in_array($iqbc_key, $iqbc_optarr)) { 
                            update_option($iqbc_key, $iqbc_value);  
                        }
                    }
                    unlink($iqbc_dir['path'] . '/iqblockcountry.ini');

                    echo "<div class='notice notice-success'><p>" .  esc_html("All options are restored successfully.", 'iq-block-country') . "</p></div>";
                }
                else {
                    wp_die( esc_html("ZIP File did not contain any settings", 'iq-block-country'));  
                }            
            } 
        }
        else {
                          wp_die( esc_html("Something went wrong importing this file", 'iq-block-country'));  
        }            
    } 
    else { wp_die( esc_html("No correct import or export option given.", 'iq-block-country')); 
    }

}

/*
 * Function: Page settings
 */
function iqblockcountry_settings_pages()
{
    ?>
    <h3><?php esc_html_e('Select which pages are blocked.', 'iq-block-country'); ?></h3>
    <form method="post" action="options.php">
    <?php
    settings_fields('iqblockcountry-settings-group-pages');
    ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">            
    <tr valign="top">
        <th width="30%"><?php esc_html_e('Do you want to block individual pages:', 'iq-block-country'); ?><br />
        <?php esc_html_e('If you do not select this option all pages will be blocked.', 'iq-block-country'); ?></th>
    <td width="70%">
    <input type="checkbox" name="blockcountry_blockpages" value="on" <?php checked('on', get_option('blockcountry_blockpages'), true); ?> />     
    </td></tr>
    
    
    <tr valign="top">
    <th width="30%"><?php esc_html_e('Block pages selected below:', 'iq-block-country'); ?><br />
    <?php esc_html_e('Block all pages except those selected below', 'iq-block-country'); ?></th>
    <td width="70%">
        <input type="radio" name="blockcountry_blockpages_inverse" value="off" <?php checked('off', get_option('blockcountry_blockpages_inverse'), true); ?> <?php checked(false, get_option('blockcountry_blockpages_inverse'), true); ?>  /><br />
        <input type="radio" name="blockcountry_blockpages_inverse" value="on" <?php checked('on', get_option('blockcountry_blockpages_inverse'), true); ?> />
    </td></tr>

    <tr valign="top">
    <th width="30%"><?php esc_html_e('Select pages you want to block:', 'iq-block-country'); ?></th>
    <td width="70%">
     
     <ul>
    <?php
        $iqbc_selectedpages = get_option('blockcountry_pages'); 
        $iqbc_pages = get_pages(); 
        $iqbc_selected = "";
    foreach ( $iqbc_pages as $iqbc_page ) {
        if (is_array($iqbc_selectedpages)) {
            if (in_array($iqbc_page->ID, $iqbc_selectedpages) ) {
                    $iqbc_selected = " checked=\"checked\"";
            } else {
                    $iqbc_selected = "";
            }
        }
        echo "<li><input type=\"checkbox\" " . esc_html($iqbc_selected) . " name=\"blockcountry_pages[]\" value=\"" . esc_html($iqbc_page->ID) . "\" id=\"" . esc_html($iqbc_page->post_title) . "\" /> <label for=\"" . esc_html($iqbc_page->post_title) . "\">" . esc_html($iqbc_page->post_title) . "</label></li>";     
    }
    ?>
    </td></tr>
    <tr><td></td><td>
    <p class="submit"><input type="submit" class="button-primary"
    value="<?php esc_html_e('Save Changes', 'iq-block-country')?>" /></p>
    </td></tr>    
    </table>    
    </form>

    <?php
}    

/*
 * Function: Categories settings
 */
function iqblockcountry_settings_categories()
{
    ?>
    <h3><?php esc_html_e('Select which categories are blocked.', 'iq-block-country'); ?></h3>
    <form method="post" action="options.php">
    <?php
    settings_fields('iqblockcountry-settings-group-cat');
    ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">            
    <tr valign="top">
        <th width="30%"><?php esc_html_e('Do you want to block individual categories:', 'iq-block-country'); ?><br />
        <?php esc_html_e('If you do not select this option all blog articles will be blocked.', 'iq-block-country'); ?></th>
    <td width="70%">
    <input type="checkbox" name="blockcountry_blockcategories" value="on" <?php checked('on', get_option('blockcountry_blockcategories'), true); ?> />     
    </td></tr>
    <tr valign="top">
        <th width="30%"><?php esc_html_e('Do you want to block the homepage:', 'iq-block-country'); ?><br />
        <?php esc_html_e('If you do not select this option visitors will not be blocked from your homepage regardless of the categories you select.', 'iq-block-country'); ?></th>
    <td width="70%">
    <input type="checkbox" name="blockcountry_blockhome" value="on" <?php checked('on', get_option('blockcountry_blockhome'), true); ?> />     
    </td></tr>
    <tr valign="top">
    <th width="30%"><?php esc_html_e('Select categories you want to block:', 'iq-block-country'); ?></th>
    <td width="70%">
     
     <ul>
    <?php
        $iqbc_selectedcategories = get_option('blockcountry_categories'); 
        $iqbc_categories = get_categories(array("hide_empty"=>0));
        $iqbc_selected = "";
    foreach ( $iqbc_categories as $iqbc_category ) {
        if (is_array($iqbc_selectedcategories)) {
            if (in_array($iqbc_category->term_id, $iqbc_selectedcategories) ) {
                    $iqbc_selected = " checked=\"checked\"";
            } else {
                    $iqbc_selected = "";
            }
        }
        echo "<li><input type=\"checkbox\" " . esc_html($iqbc_selected) . " name=\"blockcountry_categories[]\" value=\"" . esc_html($iqbc_category->term_id) . "\" id=\"" . esc_html($iqbc_category->name) . "\" /> <label for=\"" . esc_html($iqbc_category->name) . "\">" . esc_html($iqbc_category->name) . "</label></li>";     
    }
    ?>
    </td></tr>
    <tr><td></td><td>
    <p class="submit"><input type="submit" class="button-primary"
    value="<?php esc_html_e('Save Changes', 'iq-block-country')?>" /></p>
    </td></tr>    
    </table>    
    </form>

    <?php
}    

/*
 * Function: Categories settings
 */
function iqblockcountry_settings_tags()
{
    ?>
    <h3><?php esc_html_e('Select which tags are blocked.', 'iq-block-country'); ?></h3>
    <form method="post" action="options.php">
    <?php
    settings_fields('iqblockcountry-settings-group-tags');
    ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">            
    <tr valign="top">
        <th width="30%"><?php esc_html_e('Do you want to block individual tags:', 'iq-block-country'); ?><br />
        <?php esc_html_e('If you do not select this option all blog articles will be blocked.', 'iq-block-country'); ?></th>
    <td width="70%">
    <input type="checkbox" name="blockcountry_blocktags" value="on" <?php checked('on', get_option('blockcountry_blocktags'), true); ?> />     
    </td></tr>
    <tr valign="top">
    <th width="30%"><?php esc_html_e('Select tags you want to block:', 'iq-block-country'); ?></th>
    <td width="70%">
     
     <ul>
    <?php
        $iqbc_selectedtags = get_option('blockcountry_tags'); 
        $iqbc_tags = get_tags(array("hide_empty"=>0));
        $iqbc_selected = "";
    foreach ( $iqbc_tags as $iqbc_tag ) {
        if (is_array($iqbc_selectedtags)) {
            if (in_array($iqbc_tag->term_id, $iqbc_selectedtags) ) {
                    $iqbc_selected = " checked=\"checked\"";
            } else {
                    $iqbc_selected = "";
            }
        }
        echo "<li><input type=\"checkbox\" " . esc_html($iqbc_selected) . " name=\"blockcountry_tags[]\" value=\"" . esc_html($iqbc_tag->term_id) . "\" id=\"" . esc_html($iqbc_tag->name) . "\" /> <label for=\"" . esc_html($iqbc_tag->name) . "\">" . esc_html($iqbc_tag->name) . "</label></li>";     
    }
    ?>
    </td></tr>
    <tr><td></td><td>
    <p class="submit"><input type="submit" class="button-primary"
    value="<?php esc_html_e('Save Changes', 'iq-block-country')?>" /></p>
    </td></tr>    
    </table>    
    </form>

    <?php
}    


/*
 * Function: Custom post type settings
 */
function iqblockcountry_settings_posttypes()
{
    ?>
    <h3><?php esc_html_e('Select which post types are blocked.', 'iq-block-country'); ?></h3>
    <form method="post" action="options.php">
    <?php
    settings_fields('iqblockcountry-settings-group-posttypes');
    ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">            
    <tr valign="top">
        <th width="30%"><?php esc_html_e('Do you want to block individual post types:', 'iq-block-country'); ?><br />
    <td width="70%">
    <input type="checkbox" name="blockcountry_blockposttypes" value="on" <?php checked('on', get_option('blockcountry_blockposttypes'), true); ?> />     
    </td></tr>
    <tr valign="top">
    <th width="30%"><?php esc_html_e('Select post types you want to block:', 'iq-block-country'); ?></th>
    <td width="70%">
     
     <ul>
    <?php
        $iqbc_post_types = get_post_types('', 'names'); 
        $iqbc_selectedposttypes = get_option('blockcountry_posttypes');
        $iqbc_selected = "";
    foreach ( $iqbc_post_types as $iqbc_post_type ) {
        if (is_array($iqbc_selectedposttypes)) {
            if (in_array($iqbc_post_type, $iqbc_selectedposttypes) ) {
                    $iqbc_selected = " checked=\"checked\"";
            } else {
                    $iqbc_selected = "";
            }
        }
        echo "<li><input type=\"checkbox\" " . esc_html($iqbc_selected) . " name=\"blockcountry_posttypes[]\" value=\"" . esc_html($iqbc_post_type) . "\" id=\"" . esc_html($iqbc_post_type) . "\" /> <label for=\"" . esc_html($iqbc_post_type) . "\">" . esc_html($iqbc_post_type) . "</label></li>";     
    }
    ?>
    </td></tr>
    <tr><td></td><td>
    <p class="submit"><input type="submit" class="button-primary"
    value="<?php esc_html_e('Save Changes', 'iq-block-country')?>" /></p>
    </td></tr>    
    </table>    
    </form>

    <?php
}    



/*
 * Function: Services settings
 */
function iqblockcountry_settings_services()
{
    ?>
    <h3><?php esc_html_e('Select which services are allowed.', 'iq-block-country'); ?></h3>
    <form method="post" action="options.php">
    <?php
    settings_fields('iqblockcountry-settings-group-se');
    ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">            
    <tr valign="top">
        <th width="30%"><?php esc_html_e('Select which services you want to allow:', 'iq-block-country'); ?><br />
        <?php esc_html_e('This will allow a service like for instance a search engine to your site despite if you blocked the country.', 'iq-block-country'); ?><br />
        <?php esc_html_e('Please note the "Search Engine Visibility" should not be selected in ', 'iq-block-country'); ?><a href="/wp-admin/options-reading.php"><?php esc_html_e('reading settings.', 'iq-block-country'); ?></a>
        </th>
    <td width="70%">
     
     <ul>
    <?php
        global $iqbc_searchengines;
        $iqbc_selectedse = get_option('blockcountry_allowse'); 
        $iqbc_selected = "";
    foreach ( $iqbc_searchengines AS $iqbc_se => $iqbc_seua ) {
        if (is_array($iqbc_selectedse)) {
            if (in_array($iqbc_se, $iqbc_selectedse) ) {
                    $iqbc_selected = " checked=\"checked\"";
            } else {
                    $iqbc_selected = "";
            }
        } 
        echo "<li><input type=\"checkbox\" " . esc_html($iqbc_selected) . " name=\"blockcountry_allowse[]\" value=\"" . esc_html($iqbc_se) . "\" id=\"" . esc_html($iqbc_se) . "\" /> <label for=\"" . esc_html($iqbc_se) . "\">" . esc_html($iqbc_se) . "</label></li>";     
    }
    ?>
    </td></tr>
    <tr><td></td><td>
    <p class="submit"><input type="submit" class="button-primary"
    value="<?php esc_html_e('Save Changes', 'iq-block-country')?>" /></p>
    </td></tr>    
    </table>    
    </form>

    <?php
}    


/*
 * Settings frontend
 */
function iqblockcountry_settings_frontend()
{
    ?>
<h3><?php esc_html_e('Frontend options', 'iq-block-country'); ?></h3>
       
<form method="post" action="options.php">
    <?php
    settings_fields('iqblockcountry-settings-group-frontend');
    if (!class_exists('GeoIP')) {
        include_once "geoip.inc";
    }
    if (class_exists('GeoIP')) {
            $iqbc_countrylist = iqblockcountry_get_isocountries();

            $iqbc_ip_address = iqblockcountry_get_ipaddress();
            $iqbc_country = iqblockcountry_check_ipaddress($iqbc_ip_address);
        if ($iqbc_country == "Unknown" || $iqbc_country == "ipv6" || $iqbc_country == "" || $iqbc_country == "FALSE") { $iqbc_displaycountry = "Unknown"; 
        }
        else { $iqbc_displaycountry = $iqbc_countrylist[$iqbc_country]; 
        }
            
        ?>

   

            <table class="form-table" cellspacing="2" cellpadding="5" width="100%">            

            <tr valign="top">
            <th width="30%"><?php esc_html_e('Block visitors from visiting the frontend of your website:', 'iq-block-country'); ?></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_blockfrontend" <?php checked('on', get_option('blockcountry_blockfrontend'), true); ?> />
            </td></tr>
                
            <tr valign="top">
            <th width="30%"><?php esc_html_e('Do not block visitors that are logged in from visiting frontend website:', 'iq-block-country'); ?></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_blocklogin" <?php checked('on', get_option('blockcountry_blocklogin'), true); ?> />
            </td></tr>

            <tr valign="top">
            <th width="30%"><?php esc_html_e('Block visitors from using the search function of your website:', 'iq-block-country'); ?></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_blocksearch" <?php checked('on', get_option('blockcountry_blocksearch'), true); ?> />
            </td></tr>

            <tr valign="top">
                <th width="30%"><?php esc_html_e('Block countries selected below:', 'iq-block-country'); ?><br />
                <?php esc_html_e('Block all countries except those selected below', 'iq-block-country'); ?></th>
            <td width="70%">
                <input type="radio" name="blockcountry_banlist_inverse" value="off" <?php checked('off', get_option('blockcountry_banlist_inverse'), true); ?> <?php checked(false, get_option('blockcountry_banlist_inverse'), true); ?>  /><br />
                <input type="radio" name="blockcountry_banlist_inverse" value="on" <?php checked('on', get_option('blockcountry_banlist_inverse'), true); ?> />
            </td></tr>
            
            <tr valign="top">
        <th scope="row" width="30%"><?php esc_html_e('Select the countries:', 'iq-block-country'); ?><br />
        <?php esc_html_e('Use the CTRL key to select multiple countries', 'iq-block-country'); ?></th>
        <td width="70%">
                    
        <?php
        $iqbc_selected = "";
        $iqbc_haystack = get_option('blockcountry_banlist');

        if (get_option('blockcountry_accessibility')) {
            echo "<ul>";
            foreach ( $iqbc_countrylist as $iqbc_key => $iqbc_value ) {
                if (is_array($iqbc_haystack) && in_array($iqbc_key, $iqbc_haystack)) {
                                        $iqbc_selected = " checked=\"checked\"";
                } else {
                        $iqbc_selected = "";
                }
                echo "<li><input type=\"checkbox\" " . esc_html($iqbc_selected) . " name=\"blockcountry_banlist[]\" value=\"" . esc_html($iqbc_key) . "\"  \"/> <label for=\"" . esc_html($iqbc_value) . "\">" . esc_html($iqbc_value) . "</label></li>";     
            }
            echo "</ul>";
        }
        else 
        {
            ?>  


                    <select data-placeholder="Choose a country..." class="chosen" name="blockcountry_banlist[]" multiple="true" style="width:600px;">
                    <optgroup label="(de)select all countries">
                <?php   
                foreach ( $iqbc_countrylist as $iqbc_key => $iqbc_value ) {
                    print "<option value=\"". esc_html($iqbc_key) ."\"";
                    if (is_array($iqbc_haystack) && in_array($iqbc_key, $iqbc_haystack)) {
                        print " selected=\"selected\" ";
                    }
                            print ">". esc_html($iqbc_value) ."</option>\n";
                }   
                        echo "</optgroup>";
                        echo "                     </select>";
        }

        ?>
                </td></tr>
            <tr valign="top">
                <th width="30%"><?php esc_html_e('Block tag pages:', 'iq-block-country'); ?><br />
                <?php esc_html_e('If you select this option tag pages will be blocked.', 'iq-block-country')?></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_blocktag" <?php checked('on', get_option('blockcountry_blocktag'), true); ?> />
            </td></tr>
            
            <tr valign="top">
                <th width="30%"><?php esc_html_e('Block feed:', 'iq-block-country'); ?><br />
                <?php esc_html_e('If you select this option feed pages will be blocked.', 'iq-block-country')?></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_blockfeed" <?php checked('on', get_option('blockcountry_blockfeed'), true); ?> />
            </td></tr>
            
            
            
            <tr valign="top">
                <th width="30%"><?php esc_html_e('Frontend allow list IPv4 and/or IPv6 addresses:', 'iq-block-country'); ?><br /><?php esc_html_e('Use a semicolon (;) to separate IP addresses', 'iq-block-country'); ?><br /><?php esc_html_e('This field accepts single IP addresses as well as ranges in CIDR format.', 'iq-block-country'); ?></th>
            <td width="70%">
            <?php
            $iqbc_frontendallowlist = get_option('blockcountry_frontendwhitelist');
            ?>
                <textarea cols="70" rows="5" name="blockcountry_frontendwhitelist"><?php echo esc_html($iqbc_frontendallowlist); ?></textarea>
            </td></tr>
            <tr valign="top">
                <th width="30%"><?php esc_html_e('Frontend block list IPv4 and/or IPv6 addresses:', 'iq-block-country'); ?><br /><?php esc_html_e('Use a semicolon (;) to separate IP addresses', 'iq-block-country'); ?><br /><?php esc_html_e('This field accepts single IP addresses as well as ranges in CIDR format.', 'iq-block-country'); ?></th>
            <td width="70%">
            <?php
            $iqbc_frontendblocklist = get_option('blockcountry_frontendblacklist');
            ?>
                <textarea cols="70" rows="5" name="blockcountry_frontendblacklist"><?php echo esc_html($iqbc_frontendblocklist); ?></textarea>
            </td></tr>
        <tr><td></td><td>
                        <p class="submit"><input type="submit" class="button-primary"
                value="<?php esc_html_e('Save Changes', 'iq-block-country')?>" /></p>
        </td></tr>    
        </table>    
        </form>
        <?php
    }
    else
        {
        print "<p>You are missing the GeoIP class. Perhaps geoip.inc is missing?</p>";    
    }
       
}


/*
 * Settings backend.
 */
function iqblockcountry_settings_backend()
{
    ?>
<h3><?php esc_html_e('Backend Options', 'iq-block-country'); ?></h3>
        
<form method="post" action="options.php">
    <?php
    settings_fields('iqblockcountry-settings-group-backend');
    if (!class_exists('GeoIP')) {
        include_once "geoip.inc";
    }
    if (class_exists('GeoIP')) {
        
            $iqbc_countrylist = iqblockcountry_get_isocountries();

            $iqbc_ip_address = iqblockcountry_get_ipaddress();
            $iqbc_country = iqblockcountry_check_ipaddress($iqbc_ip_address);
        if ($iqbc_country == "Unknown" || $iqbc_country == "ipv6" || $iqbc_country == "" || $iqbc_country == "FALSE") { $iqbc_displaycountry = "Unknown"; 
        }
        else { $iqbc_displaycountry = $iqbc_countrylist[$iqbc_country]; 
        }
            
            
        ?>

            <table class="form-table" cellspacing="2" cellpadding="5" width="100%">            
            <tr valign="top">
            <th width="30%"><?php esc_html_e('Block visitors from visiting the backend (administrator) of your website:', 'iq-block-country'); ?></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_blockbackend" <?php checked('on', get_option('blockcountry_blockbackend'), true); ?> />
            </td></tr>    

            <tr>
                <th width="30%"></th>
                <th width="70%">
                   <?php esc_html_e('Your IP address is', 'iq-block-country'); ?> <i><?php echo $iqbc_ip_address ?></i>. <?php esc_html_e('The country that is listed for this IP address is', 'iq-block-country'); ?> <em><?php echo $iqbc_displaycountry ?></em>.<br />  
                      <?php esc_html_e('Do NOT set the \'Block visitors from visiting the backend (administrator) of your website\' and also select', 'iq-block-country'); ?> <?php echo $iqbc_displaycountry ?> <?php esc_html_e('below.', 'iq-block-country'); ?><br /> 
                      <?php echo "<strong>" .  esc_html_e('You will NOT be able to login the next time if you DO block your own country from visiting the backend.', 'iq-block-country') . "</strong>"; ?>
                </th>
            </tr>
            </td></tr>
            <tr valign="top">
                <th width="30%"><?php esc_html_e('Block countries selected below:', 'iq-block-country'); ?><br />
                <?php esc_html_e('Block all countries except those selected below', 'iq-block-country'); ?></th>
            <td width="70%">
                <input type="radio" name="blockcountry_backendbanlist_inverse" value="off" <?php checked('off', get_option('blockcountry_backendbanlist_inverse'), true); ?> <?php checked(false, get_option('blockcountry_backendbanlist_inverse'), true); ?> /><br />
                <input type="radio" name="blockcountry_backendbanlist_inverse" value="on" <?php checked('on', get_option('blockcountry_backendbanlist_inverse'), true); ?> />
            </td></tr>
            
            <tr valign="top">
        <th scope="row" width="30%"><?php esc_html_e('Select the countries:', 'iq-block-country'); ?><br />
        <?php esc_html_e('Use the CTRL key to select multiple countries', 'iq-block-country'); ?></th>
        <td width="70%">
        
                    <?php
                    $iqbc_selected = "";
                    $iqbc_haystack = get_option('blockcountry_backendbanlist');       

                    if (get_option('blockcountry_accessibility')) {
                              echo "<ul>";
                        foreach ( $iqbc_countrylist as $iqbc_key => $iqbc_value ) {
                            if (is_array($iqbc_haystack) && in_array($iqbc_key, $iqbc_haystack)) {
                                        $iqbc_selected = " checked=\"checked\"";
                            } else {
                                 $iqbc_selected = "";
                            }
                            echo "<li><input type=\"checkbox\" " . $iqbc_selected . " name=\"blockcountry_backendbanlist[]\" value=\"" . esc_html($iqbc_key) . "\"  \"/> <label for=\"" . esc_html($iqbc_value) . "\">" . esc_html($iqbc_value) . "</label></li>";     
                        }
                        echo "</ul>";
                    }
                    else 
                    {
                        ?>      <select class="chosen" data-placeholder="Choose a country..." name="blockcountry_backendbanlist[]" multiple="true" style="width:600px;">
                        <optgroup label="(de)select all countries">

                        <?php   
                        foreach ( $iqbc_countrylist as $iqbc_key => $iqbc_value ) {
                              print "<option value=\"". esc_html($iqbc_key) . "\"";
                            if (is_array($iqbc_haystack) && in_array($iqbc_key, $iqbc_haystack)) {
                                print " selected=\"selected\" ";
                            }
                            print ">". esc_html($iqbc_value) . "</option>\n";
                        }   
                        echo "</optgroup>";
                        echo "                     </select>";
                    }
                    ?>

                </td></tr>
                               
            <tr valign="top">
                <th width="30%"><?php esc_html_e('Backend allow list IPv4 and/or IPv6 addresses:', 'iq-block-country'); ?><br /><?php esc_html_e('Use a semicolon (;) to separate IP addresses', 'iq-block-country'); ?><br /><?php esc_html_e('This field accepts single IP addresses as well as ranges in CIDR format.', 'iq-block-country'); ?></th>
            <td width="70%">
            <?php
            $iqbc_backendallowlist = get_option('blockcountry_backendwhitelist');
            ?>
                <textarea cols="70" rows="5" name="blockcountry_backendwhitelist"><?php echo esc_html($iqbc_backendallowlist); ?></textarea>
            </td></tr>
            <tr valign="top">
                <th width="30%"><?php esc_html_e('Backend block list IPv4 and/or IPv6 addresses:', 'iq-block-country'); ?><br /><?php esc_html_e('Use a semicolon (;) to separate IP addresses', 'iq-block-country'); ?><br /><?php esc_html_e('This field accepts single IP addresses as well as ranges in CIDR format.', 'iq-block-country'); ?></th>
            <td width="70%">
            <?php
            $iqbc_backendblocklist = get_option('blockcountry_backendblacklist');
            ?>
                <textarea cols="70" rows="5" name="blockcountry_backendblacklist"><?php echo esc_html($iqbc_backendblocklist); ?></textarea>
            </td></tr>
        <tr><td></td><td>
                        <p class="submit"><input type="submit" class="button-primary"
                value="<?php esc_html_e('Save Changes', 'iq-block-country')?>" /></p>
        </td></tr>    
        </table>    
        </form>
        <?php
    }
    else
        {
        print "<p>You are missing the GeoIP class. Perhaps geoip.inc is missing?</p>";    
    }

}


                
/*
 * Settings home
 */
function iqblockcountry_settings_home()
{

    /* Check if the Geo Database exists or if GeoIP API key is entered otherwise display notification */
    if (is_file(IQBCGEOIP2DBFILE) && (!get_option('blockcountry_geoapikey'))) {
        $iqbc_filedate = filemtime(IQBCGEOIP2DBFILE);
        $iqbc_3months = time() - 3 * 31 * 86400;
        if ($iqbc_filedate < $iqbc_3months) { 
            iq_old_db_notice();
        }  
    }
    
    
    ?>
<h3><?php esc_html_e('Overall statistics since start', 'iq-block-country'); ?></h3>

    <?php                     $iqbc_blocked = get_option('blockcountry_backendnrblocks'); ?>
<p><?php echo esc_html(number_format($iqbc_blocked)); ?> <?php esc_html_e('visitors blocked from the backend.', 'iq-block-country'); ?></p>
    <?php                     $iqbc_blocked = get_option('blockcountry_frontendnrblocks'); ?>
<p><?php echo esc_html(number_format($iqbc_blocked)); ?> <?php esc_html_e('visitors blocked from the frontend.', 'iq-block-country'); ?></p>

<form method="post" action="options.php">
    <?php
    settings_fields('iqblockcountry-settings-group');
    if (!class_exists('GeoIP')) {
        include_once "geoip.inc";
    }
    if (class_exists('GeoIP')) {
            $iqbc_countrylist = iqblockcountry_get_isocountries();
        ?>


            <hr>
            <h3><?php esc_html_e('Block type', 'iq-block-country'); ?></h3>
            <em>
            <?php esc_html_e('You should choose one of the 3 block options below. This wil either show a block message, redirect to an internal page or redirect to an external page.', 'iq-block-country'); ?>
            </em>
            <table class="form-table" cellspacing="2" cellpadding="5" width="100%">            

            <tr valign="top">
            <th width="30%"><?php esc_html_e('Message to display when people are blocked:', 'iq-block-country'); ?></th>
            <td width="70%">
            <?php
            $iqbc_blockmessage = get_option('blockcountry_blockmessage');
            if (empty($iqbc_blockmessage)) { $iqbc_blockmessage = "Forbidden - Visitors from your country are not permitted to browse this site."; 
            }
            ?>
                <textarea cols="100" rows="3" name="blockcountry_blockmessage"><?php echo esc_html($iqbc_blockmessage); ?></textarea>
            </td></tr>
            
            
            <tr valign="top">
            <th width="30%"><?php esc_html_e('Page to redirect to:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('If you select a page here blocked visitors will be redirected to this page instead of displaying above block message.', 'iq-block-country'); ?></em></th>
</th>
            <td width="70%">
                    <select class="chosen" name="blockcountry_redirect" style="width:400px;">
                    <?php
                    $iqbc_haystack = get_option('blockcountry_redirect');
                        echo "<option value=\"0\">".  esc_html("Choose a page...", 'iq-block-country') . "</option>";
                        $iqbc_pages = get_pages(); 
                    foreach ( $iqbc_pages as $iqbc_page ) {
                        print "<option value " . esc_html($iqbc_page->ID) . "\"";
                        if ($iqbc_page->ID == $iqbc_haystack) { 

                            print " selected=\"selected\" ";
                        }
                        print ">" . esc_html($iqbc_page->post_title) . "</option>\n";
                    }   
                    ?>
                     </select>
            </td></tr>

            <tr valign="top">
            <th width="30%"><?php esc_html_e('URL to redirect to:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('If you enter a URL here blocked visitors will be redirected to this URL instead of displaying above block message or redirected to a local page.', 'iq-block-country'); ?></em>
            </th>
            <td width="70%">
                  <input type="text" style="width:100%" name="blockcountry_redirect_url" value="<?php echo get_option('blockcountry_redirect_url');?>">
            </td></tr>
            </table>
            <hr>
            <h3><?php esc_html_e('General settings', 'iq-block-country'); ?></h3>
            
            <table class="form-table" cellspacing="2" cellpadding="5" width="100%">            
            <tr valign="top">
            <th width="30%"><?php esc_html_e('Send headers when user is blocked:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('Under normal circumstances you should keep this selected! Only if you have "Cannot modify header information - headers already sent" errors or if you know what you are doing uncheck this.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_header" <?php checked('on', get_option('blockcountry_header'), true); ?> />
            </td></tr>

            <tr valign="top">
            <th width="30%"><?php esc_html_e('Buffer output?:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('You can use this option to buffer all output. This can be helpful in case you have "headers already sent" issues.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_buffer" <?php checked('on', get_option('blockcountry_buffer'), true); ?> />
            </td></tr>
            
           <tr valign="top">
            <th width="30%"><?php esc_html_e('Do not log IP addresses:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('Check this box if the laws in your country do not permit you to log IP addresses or if you do not want to log the ip addresses.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_logging" <?php checked('on', get_option('blockcountry_logging'), true); ?> />
            </td></tr>

           <tr valign="top">
            <th width="30%"><?php esc_html_e('Do not block admin-ajax.php:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('Check this box if you use a plugin that uses admin-ajax.php.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_adminajax" <?php checked('on', get_option('blockcountry_adminajax'), true); ?> />
            </td></tr>
             
            
            
            
            <tr valign="top">
            <th width="30%"><?php esc_html_e('Number of rows on logging tab:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('How many rows do you want to display on each column on the logging tab.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                   <?php
                    $iqbc_nrrows = get_option('blockcountry_nrstatistics'); ?>
                <select name="blockcountry_nrstatistics">
                    <option <?php selected($iqbc_nrrows, 10); ?> value="10">10</option>
                    <option <?php selected($iqbc_nrrows, 15); ?> value="15">15</option>
                    <option <?php selected($iqbc_nrrows, 20); ?> value="20">20</option>
                    <option <?php selected($iqbc_nrrows, 25); ?> value="25">25</option>
                    <option <?php selected($iqbc_nrrows, 30); ?> value="30">30</option>
                    <option <?php selected($iqbc_nrrows, 45); ?> value="45">45</option>
                </select>
            </td></tr>

            <tr valign="top">
            <th width="30%"><?php esc_html_e('Number of days to keep logging:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('How many days do you want to keep the logging used for the logging tab.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                   <?php
                    $iqbc_nrdays = get_option('blockcountry_daysstatistics'); ?>
                <select name="blockcountry_daysstatistics">
                    <option <?php selected($iqbc_nrdays, 7); ?> value="7">7</option>
                    <option <?php selected($iqbc_nrdays, 14); ?> value="14">14</option>
                    <option <?php selected($iqbc_nrdays, 21); ?> value="21">21</option>
                    <option <?php selected($iqbc_nrdays, 30); ?> value="30">30</option>
                    <option <?php selected($iqbc_nrdays, 60); ?> value="60">60</option>
                    <option <?php selected($iqbc_nrdays, 90); ?> value="90">90</option>
                </select>
            </td></tr>

           <tr valign="top">
            <th width="30%"><?php esc_html_e('Do not lookup hosts on the logging tab:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('On some hosting environments looking up hosts may slow down the logging tab.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_lookupstatistics" <?php checked('on', get_option('blockcountry_lookupstatistics'), true); ?> />
            </td></tr>
            
            <tr valign="top">
            <th width="30%"><?php esc_html_e('Allow tracking:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('This sends only the IP address and the number of attempts this ip address tried to login to your backend and was blocked doing so to a central server. No other data is being send. This helps us to get a better picture of rogue countries.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_tracking" <?php checked('on', get_option('blockcountry_tracking'), true); ?> />
            </td></tr>

            <tr valign="top">
            <th width="30%"><?php esc_html_e('GeoIP API Key:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('If for some reason you cannot or do not want to download the MaxMind GeoIP databases you will need an API key for the GeoIP api. You can get an API key from: ', 'iq-block-country'); ?> <a href="https://webence.nl/geoip-api/" target="_blank">https://webence.nl/geoip-api/</a></em></th>
            </th>
            <td width="70%">
                <input type="text" size="25" name="blockcountry_geoapikey" value="<?php echo get_option('blockcountry_geoapikey');?>">
            </td></tr>
            
            
            <tr valign="top">
            <th width="30%"><?php esc_html_e('GeoIP API Key Server Location:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('Choose a location closest to your own location.', 'iq-block-country'); ?>
            </th>
            <td width="70%">
                
                <input type="radio" name="blockcountry_geoapilocation" value="EU" <?php checked('EU', get_option('blockcountry_geoapilocation'), true); ?>> Europe (Netherlands)<br />
                <input type="radio" name="blockcountry_geoapilocation" value="EU3" <?php checked('EU3', get_option('blockcountry_geoapilocation'), true); ?>> Europe (Netherlands)<br />
                <input type="radio" name="blockcountry_geoapilocation" value="US" <?php checked('US', get_option('blockcountry_geoapilocation'), true); ?>> United States - New York<br />
                <input type="radio" name="blockcountry_geoapilocation" value="US2" <?php checked('US2', get_option('blockcountry_geoapilocation'), true); ?>> United States - San Francisco<br />
                <input type="radio" name="blockcountry_geoapilocation" value="US3" <?php checked('US3', get_option('blockcountry_geoapilocation'), true); ?>> United States - Miami<br />

            </td></tr>
            <tr valign="top">
            <th width="30%"><?php esc_html_e('Admin block API Key:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('For additional security you can protect your backend from known IP addresses who have made hack attempts at other WordPress sites. You can get more information and an API key from: ', 'iq-block-country'); ?> <a href="https://webence.nl/admin-block-api/" target="_blank">https://webence.nl/admin-block-api/</a></em></th>
            </th>
            <td width="70%">
                <input type="text" size="25" name="blockcountry_apikey" value="<?php echo get_option('blockcountry_apikey');?>">
            </td></tr>


            <tr valign="top">
            <th width="30%"><?php esc_html_e('Accessibility options:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('Set this option if you cannot use the default country selection box.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_accessibility" <?php checked('on', get_option('blockcountry_accessibility'), true); ?> />
            </td></tr>

            <tr valign="top">
            <th width="30%"><?php esc_html_e('Override IP information:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('This option allows you to override how iQ Block Country gets the real IP of your visitors.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                   <?php
                    $iqbc_ipoverride = get_option('blockcountry_ipoverride'); ?>
                <select name="blockcountry_ipoverride">
                    <option <?php selected($iqbc_ipoverride, "NONE"); ?> value="NONE">No override</option>
                    <option <?php selected($iqbc_ipoverride, "REMOTE_ADDR"); ?> value="REMOTE_ADDR">REMOTE_ADDR</option>
                    <option <?php selected($iqbc_ipoverride, "HTTP_FORWARDED"); ?> value="HTTP_FORWARDED">HTTP_FORWARDED</option>
                    <option <?php selected($iqbc_ipoverride, "HTTP_X_REAL_IP"); ?> value="HTTP_X_REAL_IP">HTTP_X_REAL_IP</option>
                    <option <?php selected($iqbc_ipoverride, "HTTP_CLIENT_IP"); ?> value="HTTP_CLIENT_IP">HTTP_CLIENT_IP</option>
                    <option <?php selected($iqbc_ipoverride, "HTTP_X_FORWARDED"); ?> value="HTTP_X_FORWARDED">HTTP_X_FORWARDED</option>
                    <option <?php selected($iqbc_ipoverride, "HTTP_X_FORWARDED_FOR"); ?> value="HTTP_X_FORWARDED_FOR">HTTP_X_FORWARDED_FOR</option>
                    <option <?php selected($iqbc_ipoverride, "HTTP_INCAP_CLIENT_IP"); ?> value="HTTP_INCAP_CLIENT_IP">HTTP_X_FORWARDED</option>
                    <option <?php selected($iqbc_ipoverride, "HTTP_X_SUCURI_CLIENTIP"); ?> value="HTTP_X_SUCURI_CLIENTIP">HTTP_X_SUCURI_CLIENTIP</option>
                    <option <?php selected($iqbc_ipoverride, "HTTP_CF_CONNECTING_IP"); ?> value="HTTP_CF_CONNECTING_IP">HTTP_CF_CONNECTING_IP</option>
                </select>
            </td></tr>
            
            <tr valign="top">
            <th width="30%"><?php esc_html_e('Log all visits:', 'iq-block-country'); ?><br />
                <em><?php esc_html_e('This logs all visits despite if they are blocked or not. This is only for debugging purposes.', 'iq-block-country'); ?></em></th>
            <td width="70%">
                <input type="checkbox" name="blockcountry_debuglogging" <?php checked('on', get_option('blockcountry_debuglogging'), true); ?> />
            </td></tr>
            
            
            <tr><td></td><td>
                        <p class="submit"><input type="submit" class="button-primary"
                value="<?php esc_html_e('Save Changes', 'iq-block-country')?>" /></p>
        </td></tr>    
        </table>    
        </form>
        <?php
    }
    else
        {
        print "<p>You are missing the GeoIP class. Perhaps geoip.inc is missing?</p>";    
    }
}

/*
 * Function: Display logging
 */
function iqblockcountry_settings_logging()
{    
    ?>
   <h3><?php esc_html_e('Last blocked visits', 'iq-block-country'); ?></h3>
    <?php
    if (!get_option('blockcountry_logging')) {
   
   
        global $wpdb;

        $iqbc_table_name = $wpdb->prefix . "iqblock_logging";
        $iqbc_format = get_option('date_format') . ' ' . get_option('time_format');
        $iqbc_nrrows = get_option('blockcountry_nrstatistics');
        $iqbc_lookupstats = get_option('blockcountry_lookupstatistics');
        if ($iqbc_nrrows == "") { $iqbc_nrrows = 15;
        };
        $iqbc_countrylist = iqblockcountry_get_isocountries();
        echo '<table class="widefat">';
        echo '<thead><tr><th>' .  esc_html('Date / Time', 'iq-block-country') . '</th><th>' .  esc_html('IP Address', 'iq-block-country') . '</th><th>' .  esc_html('Hostname', 'iq-block-country') . '</th><th>' .  esc_html('URL', 'iq-block-country') . '</th><th>' .  esc_html('Country', 'iq-block-country') . '</th><th>' .  esc_html('Frontend/Backend', 'iq-block-country') . '</th></tr></thead>';
        $iqbcsql = $wpdb->prepare("SELECT * FROM $iqbc_table_name ORDER BY datetime DESC LIMIT %d",$iqbc_nrrows);
        foreach ($wpdb->get_results("$iqbcsql") as $iqbc_row)
        {
               $iqbc_countryimage = "icons/" . strtolower($iqbc_row->country) . ".png";
               $iqbc_countryurl = '<img src="' . plugins_url($iqbc_countryimage, dirname(__FILE__)) . '" > ';
               echo "<tbody><tr><td>";
               $iqbc_datetime = strtotime($iqbc_row->datetime);
               $iqbc_mysqldate = date($iqbc_format, $iqbc_datetime);
            if ($iqbc_lookupstats) {
                if (extension_loaded('mbstring')) {
                    echo $iqbc_mysqldate . '</td><td>' . esc_html($iqbc_row->ipaddress) . '</td><td>' . esc_html($iqbc_row->ipaddress) . 'S</td><td>' . esc_url(mb_strimwidth($iqbc_row->url, 0, 75, '...')) . '</td><td>' . $iqbc_countryurl . esc_html($iqbc_countrylist[$iqbc_row->country]) . '<td>';
                }
                else
                {
                    echo $iqbc_mysqldate . '</td><td>' . esc_html($iqbc_row->ipaddress) . '</td><td>' . esc_html($iqbc_row->ipaddress) . 'S</td><td>' . esc_url($iqbc_row->url) . '</td><td>' . $iqbc_countryurl . esc_html($iqbc_countrylist[$iqbc_row->country]) . '<td>';
               
                }
            }
            else
            {
                if (extension_loaded('mbstring')) {
                    echo $iqbc_mysqldate . '</td><td>' . esc_html($iqbc_row->ipaddress) . '</td><td>' . esc_html($iqbc_row->ipaddress) . '</td><td>' . esc_url(mb_strimwidth($iqbc_row->url, 0, 75, '...')) . '</td><td>' . $iqbc_countryurl . $iqbc_countrylist[$iqbc_row->country] . '<td>';
                }
                else {
                    echo $iqbc_mysqldate . '</td><td>' . esc_html($iqbc_row->ipaddress) . '</td><td>' . esc_html(gethostbyaddr($iqbc_row->ipaddress)) . '</td><td>' . esc_url($iqbc_row->url) . '</td><td>' . $iqbc_countryurl . $iqbc_countrylist[$iqbc_row->country] . '<td>';
                }
            }
            if ($iqbc_row->banned == "F") { esc_html_e('Frontend', 'iq-block-country'); 
            } elseif ($iqbc_row->banned == "A") { esc_html_e('Backend banlist', 'iq-block-country'); 
            } elseif ($iqbc_row->banned == "T") { esc_html_e('Backend & Backend banlist', 'iq-block-country'); 
            } else { esc_html_e('Backend', 'iq-block-country'); 
            }
            echo "</td></tr></tbody>";
        }
        echo '</table>';
   
   
        echo '<hr>';
        echo '<h3>' .  esc_html('Top countries that are blocked', 'iq-block-country') . '</h3>';
        echo '<table class="widefat">';
        echo '<thead><tr><th>' .  esc_html('Country', 'iq-block-country') . '</th><th>' .  esc_html('# of blocked attempts', 'iq-block-country') . '</th></tr></thead>';

        $iqbcsql = $wpdb->prepare("SELECT count(country) AS count,country FROM $iqbc_table_name GROUP BY country ORDER BY count(country) DESC LIMIT %d",$iqbc_nrrows);
        foreach ($wpdb->get_results("$iqbcsql") as $iqbc_row)
        {
              $iqbc_countryimage = "icons/" . strtolower($iqbc_row->country) . ".png";
              $iqbc_countryurl = '<img src="' . plugins_url($iqbc_countryimage, dirname(__FILE__)) . '" > ';
              echo "<tbody><tr><td>" . $iqbc_countryurl . esc_html($iqbc_countrylist[$iqbc_row->country]) . "</td><td>" . esc_html($iqbc_row->count) . "</td></tr></tbody>";
        }
        echo '</table>';
   
        echo '<hr>';
        echo '<h3>' .  esc_html('Top hosts that are blocked', 'iq-block-country') . '</h3>';
        echo '<table class="widefat">';
        echo '<thead><tr><th>' .  esc_html('IP Address', 'iq-block-country') . '</th><th>' .  esc_html('Hostname', 'iq-block-country') . '</th><th>' .  esc_html('# of blocked attempts', 'iq-block-country') . '</th></tr></thead>';

        $iqbcsql = $wpdb->prepare("SELECT count(ipaddress) AS count,ipaddress FROM $iqbc_table_name GROUP BY ipaddress ORDER BY count(ipaddress) DESC LIMIT %d",$iqbc_nrrows);
        foreach ($wpdb->get_results("$iqbcsql") as $iqbc_row)
        {
            if ($iqbc_lookupstats) {
                echo "<tbody><tr><td>" . esc_html($iqbc_row->ipaddress) . "</td><td>" . esc_html($iqbc_row->ipaddress) . "</td><td>" . esc_html($iqbc_row->count) . "</td></tr></tbody>";
            }
            else 
            {
                echo "<tbody><tr><td>" . esc_html($iqbc_row->ipaddress) . "</td><td>" . esc_html(gethostbyaddr($iqbc_row->ipaddress)) . "</td><td>" . esc_html($iqbc_row->count) . "</td></tr></tbody>";
          
            }
           
            
        }
        echo '</table>';

        echo '<hr>';
        echo '<h3>' .  esc_html('Top URLs that are blocked', 'iq-block-country') . '</h3>';
        echo '<table class="widefat">';
        echo '<thead><tr><th>' .  esc_html('URL', 'iq-block-country') . '</th><th>' .   esc_html('# of blocked attempts', 'iq-block-country') .  '</th></tr></thead>';

        $iqbcsql = $wpdb->prepare("SELECT count(url) AS count,url FROM $iqbc_table_name GROUP BY url ORDER BY count(url) DESC LIMIT %d",$iqbc_nrrows);
        foreach ($wpdb->get_results($iqbcsql) as $iqbc_row)
        {
            echo "<tbody><tr><td>" . esc_url($iqbc_row->url) . "</td><td>" . esc_html($iqbc_row->count) . "</td></tr></tbody>";
        }
        echo '</table>';
   
        ?>
   <form name="cleardatabase" action="#" method="post">
        <input type="hidden" name="iqbc_action" value="iqbc_cleardatabase" />
        <input name="iqbc_cleardatabase_nonce" type="hidden" value="<?php echo wp_create_nonce('iqbc_cleardatabase_nonce'); ?>" />

        <?php
        echo '<div class="submit"><input type="submit" name="test" value="' .  esc_html('Clear database', 'iq-block-country') . '" /></div>';
        wp_nonce_field('iqblockcountry');

        if (isset($_POST['iqbc_action']) && $_POST[ 'iqbc_action' ] == 'iqbc_cleardatabase') {
            if (!isset($_POST['iqbc_cleardatabase_nonce'])) { die("Failed security check.");
            }
            if (!wp_verify_nonce($_POST['iqbc_cleardatabase_nonce'], 'iqbc_cleardatabase_nonce')) { die("Is this a CSRF attempt?");
            }
            global $wpdb;
            $iqbc_table_name = $wpdb->prefix . "iqblock_logging";
            $iqbc_sql = "TRUNCATE " . $iqbc_table_name . ";";
            $wpdb->query($iqbc_sql);
            $iqbc_sql = "ALTER TABLE ". $iqbc_table_name . " AUTO_INCREMENT = 1;";
            $wpdb->query($iqbc_sql);
            echo "Cleared database";

        }

        ?>
        </form>
        
    <form name="csvoutput" action="#" method="post">
        <input type="hidden" name="iqbc_action" value="iqbc_csvoutput" />
        <input name="iqbc_csv_nonce" type="hidden" value="<?php echo wp_create_nonce('iqbc_csv_nonce'); ?>" />
        <?php
        echo '<div class="submit"><input type="submit" name="submit" value="' .  esc_html('Download as CSV file', 'iq-block-country') . '" /></div>';
        wp_nonce_field('iqbc_iqblockcountrycsv');
        echo '</form>';
    }
    else
    {
        echo "<hr><h3>";
        esc_html_e('You are not logging any information. Please uncheck the option \'Do not log IP addresses\' if this is not what you want.', 'iq-block-country');
        echo "<hr></h3>";
    }
}


/*
 * Create the settings page.
 */
function iqblockcountry_settings_page()
{
    
    
    if(isset($_GET[ 'tab' ]) ) {  
        $iqbc_active_tab = sanitize_text_field($_GET[ 'tab' ]);
        }
    else
            {
        $iqbc_active_tab = 'home';
    }
    ?>  
          
        <h2 class="nav-tab-wrapper">  
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=home" class="nav-tab <?php echo $iqbc_active_tab == 'home' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Home', 'iq-block-country'); ?></a>  
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=frontend" class="nav-tab <?php echo $iqbc_active_tab == 'frontend' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Frontend', 'iq-block-country'); ?></a>  
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=backend" class="nav-tab <?php echo $iqbc_active_tab == 'backend' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Backend', 'iq-block-country'); ?></a>  
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=pages" class="nav-tab <?php echo $iqbc_active_tab == 'pages' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Pages', 'iq-block-country'); ?></a>  
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=categories" class="nav-tab <?php echo $iqbc_active_tab == 'categories' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Categories', 'iq-block-country'); ?></a>  
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=tags" class="nav-tab <?php echo $iqbc_active_tab == 'tags' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Tags', 'iq-block-country'); ?></a>            
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=posttypes" class="nav-tab <?php echo $iqbc_active_tab == 'posttypes' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Post types', 'iq-block-country'); ?></a>
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=services" class="nav-tab <?php echo $iqbc_active_tab == 'services' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Services', 'iq-block-country'); ?></a>  
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=tools" class="nav-tab <?php echo $iqbc_active_tab == 'tools' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Tools', 'iq-block-country'); ?></a>  
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=logging" class="nav-tab <?php echo $iqbc_active_tab == 'logging' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Logging', 'iq-block-country'); ?></a>  
            <a href="?page=iq-block-country/libs/blockcountry-settings.php&tab=export" class="nav-tab <?php echo $iqbc_active_tab == 'export' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Import/Export', 'iq-block-country'); ?></a>  
        </h2>  
  
    
        <div class="wrap">
<h2>iQ Block Country</h2>

        <hr />
        <?php
        if ($iqbc_active_tab == "frontend") { 
            iqblockcountry_settings_frontend();
        }
        elseif ($iqbc_active_tab == "backend") { 
            iqblockcountry_settings_backend();
        }
        elseif ($iqbc_active_tab == "tools") { 
            iqblockcountry_settings_tools();
        }
        elseif ($iqbc_active_tab == "logging") {    
            iqblockcountry_settings_logging();
        }
        elseif ($iqbc_active_tab == "pages") {    
            iqblockcountry_settings_pages();
        }
        elseif ($iqbc_active_tab == "categories") {    
            iqblockcountry_settings_categories();
        }
        elseif ($iqbc_active_tab == "tags") {    
            iqblockcountry_settings_tags();
        }
        elseif ($iqbc_active_tab == "posttypes") {    
            iqblockcountry_settings_posttypes();
        }
        elseif ($iqbc_active_tab == "services") {    
            iqblockcountry_settings_services();
        }
        elseif ($iqbc_active_tab == "export") {    
            iqblockcountry_settings_importexport();
        }
        else
        {
             iqblockcountry_settings_home();
        }
        
        ?>
        
        <p>If you need assistance with this plugin please send an email to <a href="mailto:support@webence.nl">support@webence.nl</a></p>
        
        <p>This product uses GeoIP2 data created by MaxMind, available from <a href="http://www.maxmind.com/">http://www.maxmind.com/</a>.</p>

        <p>If you like this plugin please link back to <a href="https://webence.nl/">webence.nl</a>! :-) and support the development of the plugin. 
            See <a href="https://webence.nl/plugins/donate/">Plugin donation page</a></p>

        <?php
    
}


/*
 *  Check which GeoIP API location is cloest
 */
function iqblockcountry_find_geoip_location() 
{
    if (function_exists('curl_init')) {  
        $iqbc_curl = curl_init();
        curl_setopt_array(
            $iqbc_curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://us.geoip.webence.nl/test',
            CURLOPT_USERAGENT => 'iQ Block Country US location test/' . get_bloginfo('wpurl')
            )
        );
        $iqbc_resp = curl_exec($iqbc_curl);
        $iqbc_infous = curl_getinfo($iqbc_curl);
        curl_close($iqbc_curl);

        $iqbc_curl = curl_init();
        curl_setopt_array(
            $iqbc_curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://us2.geoip.webence.nl/test',
            CURLOPT_USERAGENT => 'iQ Block Country US2 location test/'  . get_bloginfo('wpurl')
            )
        );
        $iqbc_resp = curl_exec($iqbc_curl);
        $iqbc_infous2 = curl_getinfo($iqbc_curl);
        curl_close($iqbc_curl);

        $iqbc_curl = curl_init();
        curl_setopt_array(
            $iqbc_curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://us3.geoip.webence.nl/test',
            CURLOPT_USERAGENT => 'iQ Block Country US3 location test/'  . get_bloginfo('wpurl')
            )
        );
        $iqbc_resp = curl_exec($iqbc_curl);
        $iqbc_infous3 = curl_getinfo($iqbc_curl);
        curl_close($iqbc_curl);

        //    $iqbc_curl = curl_init();
        //    curl_setopt_array($iqbc_curl, array(
        //        CURLOPT_RETURNTRANSFER => 1,
        //        CURLOPT_URL => 'https://asia.geoip.webence.nl/test',
        //        CURLOPT_USERAGENT => 'iQ Block Country Asia location test/'  . get_bloginfo('wpurl')
        //    ));
        //    $iqbc_resp = curl_exec($iqbc_curl);
        //    $infoasia = curl_getinfo($iqbc_curl);
        //    curl_close($iqbc_curl);
    
        $iqbc_curl = curl_init();
        curl_setopt_array(
            $iqbc_curl, array(
            CURLOPT_URL => 'https://eu.geoip.webence.nl/test',
            CURLOPT_USERAGENT => 'iQ Block Country EU location test/'  . get_bloginfo('wpurl')
            )
        );
        $iqbc_resp = curl_exec($iqbc_curl);
        $iqbc_infoeu = curl_getinfo($iqbc_curl);
        curl_close($iqbc_curl);

        $iqbc_curl = curl_init();
        curl_setopt_array(
            $iqbc_curl, array(
            CURLOPT_URL => 'https://eu3.geoip.webence.nl/test',
            CURLOPT_USERAGENT => 'iQ Block Country EU3 location test/'  . get_bloginfo('wpurl')
            )
        );
        $iqbc_resp = curl_exec($iqbc_curl);
        $iqbc_infoeu3 = curl_getinfo($iqbc_curl);
        curl_close($iqbc_curl);
    
    
        $iqbc_fastestsite = min($iqbc_infoeu['total_time'], $iqbc_infoeu3['total_time'], $iqbc_infous['total_time'], $iqbc_infous2['total_time'], $iqbc_infous3['total_time']);
    
        if ($iqbc_infous['total_time'] == $iqbc_fastestsite) {
            update_option('blockcountry_geoapilocation', 'US');
        }
        elseif ($iqbc_infous2['total_time'] == $iqbc_fastestsite) {
            update_option('blockcountry_geoapilocation', 'US2');
        }
        elseif ($iqbc_infous3['total_time'] == $iqbc_fastestsite) {
            update_option('blockcountry_geoapilocation', 'US3');
        }
        //    elseif ($infoasia['total_time'] == $iqbc_fastestsite)
        //    {
        //        update_option('blockcountry_geoapilocation','ASIA');
        //    }
        elseif ($iqbc_infoeu3['total_time'] == $iqbc_fastestsite) {
            update_option('blockcountry_geoapilocation', 'EU3');
        }
        else
        {
            update_option('blockcountry_geoapilocation', 'EU');
        }
    }
}

/*
 * Get different lists of block and allow list
 */
function iqblockcountry_get_blockallowlist()
{
    $iqbc_frontendblocklistip = array();   $iqbc_frontendblocklist = get_option('blockcountry_frontendblacklist');
    $iqbc_frontendallowlistip = array();   $iqbc_frontendallowlist = get_option('blockcountry_frontendwhitelist');
    $iqbc_backendblocklistip = array();    $iqbc_backendblocklist = get_option('blockcountry_backendblacklist');
    $iqbc_backendallowlistip = array();    $iqbc_backendallowlist = get_option('blockcountry_backendwhitelist');

   
    $iqbc_feblocklistip = array();
    $iqbc_feblocklistiprange4 = array();
    $iqbc_feblocklistiprange6 = array();
    $iqbc_feallowlistip = array();
    $iqbc_feallowlistiprange4 = array();
    $iqbc_feallowlistiprange6 = array();
    global $iqbc_feblocklistip,$iqbc_feblocklistiprange4,$iqbc_feblocklistiprange6,$iqbc_feallowlistip,$iqbc_feallowlistiprange4,$iqbc_feallowlistiprange6;
    
    $iqbc_beblocklistip = array();
    $iqbc_beblocklistiprange4 = array();
    $iqbc_beblocklistiprange6 = array();
    $iqbc_beallowlistip = array();
    $iqbc_beallowlistiprange4 = array();
    $iqbc_beallowlistiprange6 = array();
    global $iqbc_beblocklistip,$iqbc_beblocklistiprange4,$iqbc_beblocklistiprange6,$iqbc_beallowlistip,$iqbc_beallowlistiprange4,$iqbc_beallowlistiprange6;
    
   
    if (preg_match('/;/', $iqbc_frontendblocklist)) {
        $iqbc_frontendblocklistip = explode(";", $iqbc_frontendblocklist);
        foreach ($iqbc_frontendblocklistip AS $iqbc_ip)
        {
            if (iqblockcountry_is_valid_ipv4($iqbc_ip) || iqblockcountry_is_valid_ipv6($iqbc_ip)) { $iqbc_feblocklistip[] = $iqbc_ip; 
            }
            elseif (iqblockcountry_is_valid_ipv4_cidr($iqbc_ip)) { $iqbc_feblocklistiprange4[] = $iqbc_ip; 
            }
            elseif (iqblockcountry_is_valid_ipv6_cidr($iqbc_ip)) { $iqbc_feblocklistiprange6[] = $iqbc_ip; 
            }
        }
    }
    if (preg_match('/;/', $iqbc_frontendallowlist)) {
        $iqbc_frontendallowlistip = explode(";", $iqbc_frontendallowlist);
        foreach ($iqbc_frontendallowlistip AS $iqbc_ip)
        {
            if (iqblockcountry_is_valid_ipv4($iqbc_ip) || iqblockcountry_is_valid_ipv6($iqbc_ip)) { $iqbc_feallowlistip[] = $iqbc_ip; 
            }
            elseif (iqblockcountry_is_valid_ipv4_cidr($iqbc_ip)) { $iqbc_feallowlistiprange4[] = $iqbc_ip; 
            }
            elseif (iqblockcountry_is_valid_ipv6_cidr($iqbc_ip)) { $iqbc_feallowlistiprange6[] = $iqbc_ip; 
            }
        }
    }
    if (preg_match('/;/', $iqbc_backendblocklist)) {
        $iqbc_backendblocklistip = explode(";", $iqbc_backendblocklist);
        foreach ($iqbc_backendblocklistip AS $iqbc_ip)
        {
            if (iqblockcountry_is_valid_ipv4($iqbc_ip) || iqblockcountry_is_valid_ipv6($iqbc_ip)) { $iqbc_beblocklistip[] = $iqbc_ip; 
            }
            elseif (iqblockcountry_is_valid_ipv4_cidr($iqbc_ip)) { $iqbc_beblocklistiprange4[] = $iqbc_ip; 
            }
            elseif (iqblockcountry_is_valid_ipv6_cidr($iqbc_ip)) { $iqbc_beblocklistiprange6[] = $iqbc_ip; 
            }
        }
    }
    if (preg_match('/;/', $iqbc_backendallowlist)) {
        $iqbc_backendallowlistip = explode(";", $iqbc_backendallowlist);
        foreach ($iqbc_backendallowlistip AS $iqbc_ip)
        {
            if (iqblockcountry_is_valid_ipv4($iqbc_ip) || iqblockcountry_is_valid_ipv6($iqbc_ip)) { $iqbc_beallowlistip[] = $iqbc_ip; 
            }
            elseif (iqblockcountry_is_valid_ipv4_cidr($iqbc_ip)) { $iqbc_beallowlistiprange4[] = $iqbc_ip; 
            }
            elseif (iqblockcountry_is_valid_ipv6_cidr($iqbc_ip)) { $iqbc_beallowlistiprange6[] = $iqbc_ip; 
            }
        }
    }
    
}