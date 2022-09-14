<?php

/*
 * Check of an IP address is a valid IPv4 address
 */
function iqblockcountry_is_valid_ipv4($iqbc_ipv4) 
{
    if(filter_var($iqbc_ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
        return false;
    }

    return true;
}

/*
 * Check of an IP address is a valid IPv6 address
 */
function iqblockcountry_is_valid_ipv6($iqbc_ipv6) 
{
    if(filter_var($iqbc_ipv6, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
        return false;
    }

    return true;
}

function iqblockcountry_is_valid_ipv4_cidr($iqbc_ip)
{
    if (preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/([0-9]|[1-2][0-9]|3[0-2]))$/', $iqbc_ip)) {
        return true;
    }
    else
    {
        return false;
    }
}

function iqblockcountry_is_valid_ipv6_cidr($iqbc_ip)
{
    if (preg_match('/^s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]d|1dd|[1-9]?d)(.(25[0-5]|2[0-4]d|1dd|[1-9]?d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]d|1dd|[1-9]?d)(.(25[0-5]|2[0-4]d|1dd|[1-9]?d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]d|1dd|[1-9]?d)(.(25[0-5]|2[0-4]d|1dd|[1-9]?d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]d|1dd|[1-9]?d)(.(25[0-5]|2[0-4]d|1dd|[1-9]?d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]d|1dd|[1-9]?d)(.(25[0-5]|2[0-4]d|1dd|[1-9]?d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]d|1dd|[1-9]?d)(.(25[0-5]|2[0-4]d|1dd|[1-9]?d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]d|1dd|[1-9]?d)(.(25[0-5]|2[0-4]d|1dd|[1-9]?d)){3}))|:)))(%.+)?s*(\/([0-9]|[1-9][0-9]|1[0-1][0-9]|12[0-8]))?$/', $iqbc_ip)) {
        return true;
    }
    else
    {
        return false;
    }
}


/*
 * Check of given url is a valid url
 */
function iqblockcountry_is_valid_url($iqbc_input) 
{
    if(filter_var($iqbc_input, FILTER_VALIDATE_URL) === false) {
        return "";   
    }
    else
    {
        return $iqbc_input;
    }
    
}


 /*
  * Sanitize callback. Check if supplied IP address list is valid IPv4 or IPv6
  */
function iqblockcountry_validate_ip($iqbc_input)
{
    $iqbc_validips = "";
    if (preg_match('/;/', $iqbc_input)) {
        $iqbc_arr = explode(";", $iqbc_input);
        foreach ($iqbc_arr as $iqbc_value) {
            if (iqblockcountry_is_valid_ipv4($iqbc_value) || iqblockcountry_is_valid_ipv6($iqbc_value)  || iqblockcountry_is_valid_ipv4_cidr($iqbc_value) || iqblockcountry_is_valid_ipv6_cidr($iqbc_value)) {
                $iqbc_validips .= $iqbc_value . ";";
            }
            
        }
    }
    else
    {
        if (iqblockcountry_is_valid_ipv4($iqbc_input) || iqblockcountry_is_valid_ipv6($iqbc_input) || iqblockcountry_is_valid_ipv4_cidr($iqbc_input) || iqblockcountry_is_valid_ipv6_cidr($iqbc_input)) {
            $iqbc_validips = $iqbc_input . ";";
        }
    }
    return $iqbc_validips;
    
}

/*
 * Check if GeoIP API key is correct.
 */
function iqblockcountry_check_geoapikey($iqbc_input)
{
    // Check first if API key is empty....
    if (!empty($iqbc_input)) {    
    
        $iqbc_license = filter_var($iqbc_input, FILTER_SANITIZE_STRING);
        $iqbc_url = GEOIPAPICHECKURL;
        $iqbc_result = wp_remote_post(
            $iqbc_url,
            array(
                'body' => array(
                    'api-key' => $iqbc_license
                )
            )
        );    
        $iqbc_message = "";
        $iqbc_type = "updated";
        if (is_wp_error($iqbc_result) ) {
            return false;
        }

        elseif (200 == $iqbc_result['response']['code'] ) {
            $iqbc_body = $iqbc_result['body'];
            $iqbc_xml = new SimpleXmlElement($iqbc_body);
            if ($iqbc_xml->check != "Ok") {
                $iqbc_message =  esc_html('The GeoIP API key is incorrect. Please update the key.', 'iq-block-country');
                $iqbc_type = "error";
                $iqbc_input = false;
            }
            else 
            {
                iqblockcountry_find_geoip_location();   
                $iqbc_message =  esc_html('Setting saved.', 'iq-block-country');
                $iqbc_type = "updated";
            }
        }
        else
        {
            $iqbc_input = false;
        }
        add_settings_error('iqblockcountry_geoipapi_error', esc_attr('settings_updated'), $iqbc_message, $iqbc_type);
        return $iqbc_license;
    }
    return "";
}

/*
 * Check if GeoIP API key is correct.
 */
function iqblockcountry_check_adminapikey($iqbc_input)
{
    
    // Check first if API key is empty....
    if (!empty($iqbc_input)) {    

        $iqbc_license = filter_var($iqbc_input, FILTER_SANITIZE_STRING);

        $iqbc_url = ADMINAPICHECKURL;
    
        $iqbc_result = wp_remote_post(
            $iqbc_url,
            array(
                'body' => array(
                    'api-key' => $iqbc_license
               )
            )
        );    
        $iqbc_message = "";
        $iqbc_type = "updated";
        if (is_wp_error($iqbc_result) ) {
            return false;
        }
        elseif (200 == $iqbc_result['response']['code'] ) {
            $iqbc_body = $iqbc_result['body'];
            $iqbc_xml = new SimpleXmlElement($iqbc_body);
            if ($iqbc_xml->check != "Ok") {
                $iqbc_message =  esc_html('The Admin Block API key is incorrect. Please update the key.', 'iq-block-country');
                $iqbc_type = "error";
                $iqbc_input = false;
            }
            else 
            {
                $iqbc_message =  esc_html('Setting saved.', 'iq-block-country');
                $iqbc_type = "updated";
            }
        }
        else
        {
            $iqbc_input = false;
        }
        add_settings_error('iqblockcountry_adminapi_error', esc_attr('settings_updated'), $iqbc_message, $iqbc_type);
        return $iqbc_license;
    }
    return "";
}


/*
 * Check if GeoIP API key is correct.
 */
function iqblockcountry_get_licensedate_geoapikey($iqbc_input)
{

    // Check first if API key is empty....
    if (!empty($iqbc_input)) {    

        $iqbc_license = filter_var($iqbc_input, FILTER_SANITIZE_STRING);

        $iqbc_url = GEOIPAPICHECKURL;
    
        $iqbc_result = wp_remote_post(
            $iqbc_url,
            array(
                'body' => array(
                    'api-key' => $iqbc_license
                )
            )
        );  
        if (is_wp_error($iqbc_result) ) {
            return "";
        }
        elseif (200 == $iqbc_result['response']['code'] ) {
            $iqbc_body = $iqbc_result['body'];
            $iqbc_xml = new SimpleXmlElement($iqbc_body);
            if ($iqbc_xml->check == "Ok") {
                return $iqbc_xml->LicenseDate;
            }
        }
        else
        {
            return "";
        }
    }
    return "";
}

/*
 * Check usage of GeoIP API
 */
function iqblockcountry_get_usage_geoapikey($iqbc_apikey)
{

    // Check first if API key is empty....
    if (!empty($iqbc_apikey)) {    
    
        $iqbc_url = GEOIPAPICHECKUSAGEURL;
    
        $iqbc_result = wp_remote_post(
            $iqbc_url,
            array(
                'body' => array(
                    'api-key' => $iqbc_apikey
                )
            )
        );  
        if (is_wp_error($iqbc_result) ) {
            return "";
        }
        elseif (200 == $iqbc_result['response']['code'] ) {
            $iqbc_body = $iqbc_result['body'];
            $iqbc_xml = new SimpleXmlElement($iqbc_body);
            if ($iqbc_xml->check == "Ok") {
                return $iqbc_xml->requests;
            }
        }
        else
        {
            return "";
        }
    }
    return "";
}


/**
 * Check if a given ip is in a network
 *
 * @param  string $iqbc_ip IP to check in IPV4 format eg. 127.0.0.1
 * @param  string $range   IP/CIDR netmask eg. 127.0.0.0/24, also 127.0.0.1 is accepted and /32 assumed
 * @return boolean true if the ip is in this range / false if not.
 */
function iqblockcountry_ip_in_ipv4_range( $iqbc_ip, $iqbc_range )
{
    if (strpos($iqbc_range, '/') == false ) {
        $iqbc_range .= '/32';
    }
    // $iqbc_range is in IP/CIDR format eg 127.0.0.1/24
    list( $iqbc_range, $iqbc_netmask ) = explode('/', $iqbc_range, 2);
    $iqbc_range_decimal = ip2long($iqbc_range);
    $iqbc_ip_decimal = ip2long($iqbc_ip);
    $iqbc_wildcard_decimal = pow(2, ( 32 - $iqbc_netmask )) - 1;
    $iqbc_netmask_decimal = ~ $iqbc_wildcard_decimal;
    return ( ( $iqbc_ip_decimal & $iqbc_netmask_decimal ) == ( $iqbc_range_decimal & $iqbc_netmask_decimal ) );
}

function iqblockcountry_ip2long6($iqbc_ip)
{
    if (substr_count($iqbc_ip, '::')) { 
        $iqbc_ip = str_replace('::', str_repeat(':0000', 8 - substr_count($iqbc_ip, ':')) . ':', $iqbc_ip); 
    } 
        
    $iqbc_ip = explode(':', $iqbc_ip);
    $iqbc_r_ip = ''; 
    foreach ($iqbc_ip as $iqbc_v) {
        $iqbc_r_ip .= str_pad(base_convert($iqbc_v, 16, 2), 16, 0, STR_PAD_LEFT); 
    } 
        
    return base_convert($iqbc_r_ip, 2, 10); 
} 
// Get the ipv6 full format and return it as a decimal value.
function iqblockcountry_get_ipv6_full($iqbc_ip)
{
    $iqbc_pieces = explode("/", $iqbc_ip, 2);
    $iqbc_left_piece = $iqbc_pieces[0];
    $iqbc_right_piece = $iqbc_pieces[1];
    // Extract out the main IP pieces
    $iqbc_ip_pieces = explode("::", $iqbc_left_piece, 2);
    $iqbc_main_ip_piece = $iqbc_ip_pieces[0];
    $iqbc_last_ip_piece = $iqbc_ip_pieces[1];
    // Pad out the shorthand entries.
    $iqbc_main_ip_pieces = explode(":", $iqbc_main_ip_piece);
    foreach($iqbc_main_ip_pieces as $iqbc_key=>$iqbc_val) {
        $iqbc_main_ip_pieces[$iqbc_key] = str_pad($iqbc_main_ip_pieces[$iqbc_key], 4, "0", STR_PAD_LEFT);
    }
    // Check to see if the last IP block (part after ::) is set
    $iqbc_last_piece = "";
    $iqbc_size = count($iqbc_main_ip_pieces);
    if (trim($iqbc_last_ip_piece) != "") {
        $iqbc_last_piece = str_pad($iqbc_last_ip_piece, 4, "0", STR_PAD_LEFT);
    
        // Build the full form of the IPV6 address considering the last IP block set
        for ($iqbc_i = $iqbc_size; $iqbc_i < 7; $iqbc_i++) {
            $iqbc_main_ip_pieces[$iqbc_i] = "0000";
        }
        $iqbc_main_ip_pieces[7] = $iqbc_last_piece;
    }
    else {
        // Build the full form of the IPV6 address
        for ($iqbc_i = $iqbc_size; $iqbc_i < 8; $iqbc_i++) {
            $iqbc_main_ip_pieces[$iqbc_i] = "0000";
        }        
    }
    
    // Rebuild the final long form IPV6 address
    $iqbc_final_ip = implode(":", $iqbc_main_ip_pieces);
    return iqblockcountry_ip2long6($iqbc_final_ip);
}
// Determine whether the IPV6 address is within range.
// $iqbc_ip is the IPV6 address in decimal format to check if its within the IP range created by the cloudflare IPV6 address, $range_ip. 
// $iqbc_ip and $range_ip are converted to full IPV6 format.
// Returns true if the IPV6 address, $iqbc_ip,  is within the range from $range_ip.  False otherwise.
function iqblockcountry_ipv6_in_range($iqbc_ip, $range_ip)
{
    $iqbc_pieces = explode("/", $range_ip, 2);
    $iqbc_left_piece = $iqbc_pieces[0];
    $iqbc_right_piece = $iqbc_pieces[1];
    // Extract out the main IP pieces
    $iqbc_ip_pieces = explode("::", $iqbc_left_piece, 2);
    $iqbc_main_ip_piece = $iqbc_ip_pieces[0];
    $iqbc_last_ip_piece = $iqbc_ip_pieces[1];
    // Pad out the shorthand entries.
    $iqbc_main_ip_pieces = explode(":", $iqbc_main_ip_piece);
    foreach($iqbc_main_ip_pieces as $iqbc_key=>$iqbc_val) {
        $iqbc_main_ip_pieces[$iqbc_key] = str_pad($iqbc_main_ip_pieces[$iqbc_key], 4, "0", STR_PAD_LEFT);
    }
    // Create the first and last pieces that will denote the IPV6 range.
    $iqbc_first = $iqbc_main_ip_pieces;
    $iqbc_last = $iqbc_main_ip_pieces;
    // Check to see if the last IP block (part after ::) is set
    $iqbc_last_piece = "";
    $iqbc_size = count($iqbc_main_ip_pieces);
    if (trim($iqbc_last_ip_piece) != "") {
        $iqbc_last_piece = str_pad($iqbc_last_ip_piece, 4, "0", STR_PAD_LEFT);
    
        // Build the full form of the IPV6 address considering the last IP block set
        for ($iqbc_i = $iqbc_size; $iqbc_i < 7; $iqbc_i++) {
            $iqbc_first[$iqbc_i] = "0000";
            $iqbc_last[$iqbc_i] = "ffff";
        }
        $iqbc_main_ip_pieces[7] = $iqbc_last_piece;
    }
    else {
        // Build the full form of the IPV6 address
        for ($iqbc_i = $iqbc_size; $iqbc_i < 8; $iqbc_i++) {
            $iqbc_first[$iqbc_i] = "0000";
            $iqbc_last[$iqbc_i] = "ffff";
        }        
    }
    // Rebuild the final long form IPV6 address
    $iqbc_first = iqblockcountry_ip2long6(implode(":", $iqbc_first));
    $iqbc_last = iqblockcountry_ip2long6(implode(":", $iqbc_last));
    $iqbc_in_range = ($iqbc_ip >= $iqbc_first && $iqbc_ip <= $iqbc_last);
    return $iqbc_in_range;
}


function iqblockcountry_validate_ip_in_list($iqbc_ipaddress,$iqbc_ipv4list,$iqbc_ipv6list,$iqbc_iplist)
{
    
    $iqbc_match = false;
    if (iqblockcountry_is_valid_ipv4($iqbc_ipaddress) && is_array($iqbc_ipv4list)) {
        foreach ($iqbc_ipv4list AS $iqbc_iprange)
        {
            if (iqblockcountry_ip_in_ipv4_range($iqbc_ipaddress, $iqbc_iprange)) {
                $iqbc_match = true;
            }
        }
    }
    elseif (iqblockcountry_is_valid_ipv6($iqbc_ipaddress) && is_array($iqbc_ipv6list)) {
        foreach ($iqbc_ipv6list AS $iqbc_iprange)
        {
            $iqbc_ipaddress6 = iqblockcountry_get_ipv6_full($iqbc_ipaddress);
            if (iqblockcountry_ipv6_in_range($iqbc_ipaddress6, $iqbc_iprange)) {
                $iqbc_match = true;
            }
        }
     
    }
    if ((iqblockcountry_is_valid_ipv4($iqbc_ipaddress) || iqblockcountry_is_valid_ipv6($iqbc_ipaddress)) && is_array($iqbc_iplist)) {
        if (in_array($iqbc_ipaddress, $iqbc_iplist)) {
            $iqbc_match = true;
        }
    }
    return $iqbc_match;
}
