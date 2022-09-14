<?php

/*
 * Schedule tracking if this option was set in the admin panel
 */
function iqblockcountry_schedule_tracking($iqbc_old_value, $iqbc_new_value)
{
    $iqbc_current_schedule = wp_next_scheduled('blockcountry_tracking');
    if ($iqbc_old_value !== $iqbc_new_value) {
        if ($iqbc_new_value == '') {
            wp_clear_scheduled_hook('blockcountry_tracking');
        }
        elseif ($iqbc_new_value == 'on' && $iqbc_current_schedule == false) {
            wp_schedule_event(time(), 'hourly', 'blockcountry_tracking');
        }
    }
}


/*
 * iQ Block send Tracking
 */
function iqblockcountry_tracking()
{
    if (get_option("blockcountry_tracking") == "on") {    
        $iqbc_lasttracked = get_option("blockcountry_lasttrack");
        global $wpdb;

        $iqbc_table_name = $wpdb->prefix . "iqblock_logging";
        $iqbc_content = array();
        if (!empty($iqbc_lasttracked)) {
            $iqbc_query = $wpdb->prepare("SELECT id,ipaddress,count(ipaddress) as countip FROM $iqbc_table_name WHERE banned=\"B\" and id > %d GROUP BY ipaddress ORDER BY id",$iqbc_lasttracked);
        }
        else
        {
            $iqbc_query = "SELECT id,ipaddress,count(ipaddress) as countip FROM $iqbc_table_name WHERE banned=\"B\" GROUP BY ipaddress ORDER BY id";
        }
        foreach ($wpdb->get_results($iqbc_query) as $iqbc_row)
        {
            $iqbc_newcontent = array('ipaddress' => $iqbc_row->ipaddress,'count' => $iqbc_row->countip);
            array_push($iqbc_content, $iqbc_newcontent);
            $iqbc_id = $iqbc_row->id;
        }
        
        if (!empty($iqbc_content)) {
            $iqbc_response = wp_remote_post(
                IQBCTRACKINGURL,
                array(
                'body' => $iqbc_content
                    )
            );

            if (isset($iqbc_id)) { update_option('blockcountry_lasttrack', $iqbc_id); 
            }
        }
    }
}



/*
 * iQ Block Retrieve XML file for API blocking
 */
function iqblockcountry_tracking_retrieve_xml()
{
    $iqbc_url = IQBCBANLISTRETRIEVEURL;
    
    $iqbc_result = wp_remote_post(
        $iqbc_url,
        array(
                'body' => array(
                    'api-key' => get_option('blockcountry_apikey') 
                 
                )
            )
    );    
    
    if (is_wp_error($iqbc_result) ) {
        return false;
    }
    elseif (200 == $iqbc_result['response']['code'] ) {
        $iqbc_body = $iqbc_result['body'];
        $iqbc_xml = new SimpleXmlElement($iqbc_body);
        $iqbc_banlist = array();
        $iqbc_i=0;
        foreach ($iqbc_xml->banlist->ipaddress AS $iqbc_ip)
        {
            if (filter_var($iqbc_ip,FILTER_VALIDATE_IP))
            {
                array_push($iqbc_banlist, sprintf('%s', $iqbc_ip));
                $iqbc_i++;
            }
        }    
        update_option('blockcountry_backendbanlistip', serialize($iqbc_banlist));
    }
    
    
}

/*
 * Schedule retrieving banlist.
 */
function iqblockcountry_schedule_retrieving($iqbc_old_value, $iqbc_new_value)
{
    $iqbc_current_schedule = wp_next_scheduled('blockcountry_retrievebanlist');
    if ($iqbc_old_value !== $iqbc_new_value) {
        if ($iqbc_new_value == '') {
            wp_clear_scheduled_hook('blockcountry_retrievebanlist');
        }
        elseif (!empty($iqbc_new_value) && $iqbc_current_schedule == false) {
            wp_schedule_event(time(), 'twicedaily', 'blockcountry_retrievebanlist');
        }
    }
}
