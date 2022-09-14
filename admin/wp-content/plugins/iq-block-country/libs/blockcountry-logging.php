<?php

function iqblockcountry_install_db()
{
    global $wpdb;

    $iqbc_table_name = $wpdb->prefix . "iqblock_logging";
     
    $iqbc_sql = "CREATE TABLE $iqbc_table_name (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  datetime datetime NOT NULL,
  ipaddress tinytext NOT NULL,
  country tinytext NOT NULL,
  url varchar(250) DEFAULT '/' NOT NULL,
  banned enum('F','B','A','T') NOT NULL,
  UNIQUE KEY id (id),
  KEY `datetime` (`datetime`)
);";

    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($iqbc_sql);
}


function iqblockcountry_uninstall_db()
{
    global $wpdb;

    $iqbc_table_name = $wpdb->prefix . "iqblock_logging";
      
    $iqbc_sql = "DROP TABLE IF EXISTS `$iqbc_table_name`;"; 

    $wpdb->query($iqbc_sql);
   
    delete_option("blockcountry_dbversion");
}

function iqblockcountry_clean_db()
{
    global $wpdb;
   
    $iqbc_nrdays = get_option('blockcountry_daysstatistics');
    if (empty($iqbc_nrdays)) { $iqbc_nrdays = 30; 
    }

    $iqbc_table_name = $wpdb->prefix . "iqblock_logging";
    $iqbc_sql = $wpdb->prepare("DELETE FROM " . $iqbc_table_name . " WHERE `datetime` < DATE_SUB(NOW(), INTERVAL %d DAY);",$iqbc_nrdays);

    $wpdb->query($iqbc_sql);
   
}


function iqblockcountry_update_db_check()
{
    if (get_site_option('blockcountry_dbversion') != IQDBVERSION) {
        iqblockcountry_install_db();
        update_option("blockcountry_dbversion", IQDBVERSION);
    }
}

function iqblockcountry_install_loggingdb()
{
    global $wpdb;

    $iqbc_table_name = $wpdb->prefix . "iqblock_debug_logging";
     
    $iqbc_sql = "CREATE TABLE $iqbc_table_name (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  datetime datetime NOT NULL,
  ipaddress tinytext NOT NULL,
  type tinytext NOT NULL,
  country tinytext NOT NULL,
  url varchar(250) DEFAULT '/' NOT NULL,
  banned enum('NH','NB','FB','BB','AB','TB') NOT NULL,
  PRIMARY KEY id (id)
);";

    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($iqbc_sql);
}

function iqblockcountry_uninstall_loggingdb()
{
    global $wpdb;

    $iqbc_table_name = $wpdb->prefix . "iqblock_debug_logging";
      
    $iqbc_sql = "DROP TABLE IF EXISTS `$iqbc_table_name`;"; 

    $wpdb->query($iqbc_sql);
   
    delete_option("blockcountry_dbversion2");
}

function iqblockcountry_clean_loggingdb()
{
    global $wpdb;

    $iqbc_table_name = $wpdb->prefix . "iqblock_debug_logging";
    $iqbc_sql = "DELETE FROM " . $iqbc_table_name . " WHERE `datetime` < DATE_SUB(NOW(), INTERVAL 14 DAY);";
    //   $iqbc_sql = "DELETE FROM " . $iqbc_table_name . " WHERE DATE_SUB(CURDATE(),INTERVAL 14 DAY) >= datetime;";
    $wpdb->query($iqbc_sql);
}

/*
 * Schedule debug logging if this option was set in the admin panel
 */
function iqblockcountry_blockcountry_debuglogging($iqbc_old_value, $iqbc_new_value)
{
    if ($iqbc_old_value !== $iqbc_new_value) {
        if ($iqbc_new_value == '') {
            iqblockcountry_uninstall_loggingdb();
        }
        elseif (!empty($iqbc_new_value)) {
            iqblockcountry_install_loggingdb();
        }
    }
}


function iqblockcountry_logging($iqbc_ipaddress,$iqbc_country,$iqbc_banned)
{
    global $wpdb;

    $iqbc_urlRequested = (isset($_SERVER["REQUEST_URI"]) ?  esc_url_raw($_SERVER["REQUEST_URI"]) : '/' );

    $iqbc_table_name = $wpdb->prefix . "iqblock_logging";
    $wpdb->insert($iqbc_table_name, array ('datetime' => current_time('mysql'), 'ipaddress' => $iqbc_ipaddress, 'country' => $iqbc_country, 'banned' => $iqbc_banned,'url' => $iqbc_urlRequested));
}

function iqblockcountry_debug_logging($iqbc_ipaddress,$iqbc_country,$iqbc_banned)
{
    if (get_option('blockcountry_debuglogging')) {
        global $wpdb;

        $iqbc_urlRequested = (isset($_SERVER["REQUEST_URI"]) ?  esc_url_raw($_SERVER["REQUEST_URI"]) : '/' );
        $iqbc_type = "POST";

        $iqbc_table_name = $wpdb->prefix . "iqblock_debug_logging";
        $wpdb->insert($iqbc_table_name, array ('datetime' => current_time('mysql'), 'ipaddress' => $iqbc_ipaddress, 'type' => $iqbc_type, 'country' => $iqbc_country, 'banned' => $iqbc_banned,'url' => $iqbc_urlRequested));
    }
}