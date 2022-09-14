<?php

    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

    function WPTime_preloader_settings() {
        add_plugins_page( 'Preloader Settings', 'Preloader', 'manage_options', 'WPTime_preloader_settings', 'WPTime_preloader_settings_page');
    }
    add_action( 'admin_menu', 'WPTime_preloader_settings' );
    
    function WPTime_preloader_register_settings() {
        register_setting( 'WPTime_preloader_register_setting', 'wptpreloader_bg_color' );
        register_setting( 'WPTime_preloader_register_setting', 'wptpreloader_image' );
        register_setting( 'WPTime_preloader_register_setting', 'wptpreloader_screen' );
    }
    add_action( 'admin_init', 'WPTime_preloader_register_settings' );
        
    function WPTime_preloader_settings_page(){ // settings page function
        if( get_option('wptpreloader_bg_color') ){
            $background_color = get_option('wptpreloader_bg_color');
        }else{
            $background_color = '#FFFFFF';
        }
        
        if( get_option('wptpreloader_image') ){
            $preloader_image = get_option('wptpreloader_image');
        }else{
            $preloader_image = plugins_url( '/images/preloader.GIF', __FILE__ );
        }

        $get_theme = wp_get_theme();
        $theme_name = strtolower( $get_theme->get('Name') );
        $remove_d = str_replace(" ", "-", $theme_name);
        $get_theme_name = rtrim($remove_d, "-");

        if( is_ssl() ){
            $header_file_url = admin_url("theme-editor.php?file=header.php&theme=$get_theme_name", "https");
        }else{
            $header_file_url = admin_url("theme-editor.php?file=header.php&theme=$get_theme_name", "http");
        }

        $preloader_element = esc_html('now after <body> insert Preloader HTML element: <div id="wptime-plugin-preloader"></div>');
        ?>
            <div class="wrap">
                <h2>Preloader Settings</h2>
                
                <?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ){ ?>
                    <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
                        <p><strong>Settings saved.</strong></p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                    </div>
                <?php } ?>
                
                <form method="post" action="options.php">
                    <?php settings_fields( 'WPTime_preloader_register_setting' ); ?>
                    
                    <table class="form-table">
                        <tbody>
                        
                            <tr>
                                <th scope="row"><label for="wptpreloader_bg_color">Background Color</label></th>
                                <td>
                                    <input class="regular-text" name="wptpreloader_bg_color" type="text" id="wptpreloader_bg_color" value="<?php echo esc_attr( $background_color ); ?>">
                                    <p class="description">Enter background color code, default color is white #FFFFFF.</p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><label for="wptpreloader_image">Preloader Image</label></th>
                                <td>
                                    <input class="regular-text" name="wptpreloader_image" type="text" id="wptpreloader_image" value="<?php echo esc_attr( $preloader_image ); ?>">
                                    <p class="description"><?php echo apply_filters('wpt_thepreloader_image_size_remove_128px', 'Enter preloader image link, image size must be 128x128 (will be retina ready).'); ?> <a href="https://icons8.com/preloaders/" target="_blank">Get FREE Preloader Image</a>.</p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="wptpreloader_image_width">Preloader Image Width</label></th>
                                <td>
                                    <?php
                                        $image_width_input = 'With the Image Size Extension.';
                                        $image_width_input .= '<p class="description">This option will appear here after purchasing the Image Size Extension. Purchase the <a href="http://wp-plugins.in/PreloaderImageSizeExtension" target="_blank">Image Size Extension</a>.</p>';
                                        echo apply_filters('wpt_thepreloader_image_size_width_input', $image_width_input);
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="wptpreloader_image_height">Preloader Image Height</label></th>
                                <td>
                                    <?php
                                        $image_height_input = 'With the Image Size Extension.';
                                        $image_height_input .= '<p class="description">This option will appear here after purchasing the Image Size Extension. Purchase the <a href="http://wp-plugins.in/PreloaderImageSizeExtension" target="_blank">Image Size Extension</a>.</p>';
                                        echo apply_filters('wpt_thepreloader_image_size_height_input', $image_height_input);
                                    ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">Display Preloader</th>
                                <td>
                                    <?php
                                        $display_preloader = get_option( 'wptpreloader_screen' );
                                        
                                    ?>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span>Display Preloader</span></legend>
                                        <label title="Display Preloader in full website like home page, posts, pages, categories, tags, attachment, etc..">
                                            <input type="radio" name="wptpreloader_screen" value="full" <?php checked( $display_preloader, 'full' ); ?>>In The Entire Website.
                                        </label>
                                        <br>
                                        <label title="Display Preloader in home page">
                                            <input type="radio" name="wptpreloader_screen" value="homepage" <?php checked( $display_preloader, 'homepage' ); ?>>In Home Page only.
                                        </label>
                                        <br>
                                        <label title="Display Preloader in front page">
                                            <input type="radio" name="wptpreloader_screen" value="frontpage" <?php checked( $display_preloader, 'frontpage' ); ?>>In Front Page only.
                                        </label>
                                        <br>
                                        <label title="Display Preloader in posts only">
                                            <input type="radio" name="wptpreloader_screen" value="posts" <?php checked( $display_preloader, 'posts' ); ?>>In Posts only.
                                        </label>
                                        <br>
                                        <label title="Display Preloader in pages only">
                                            <input type="radio" name="wptpreloader_screen" value="pages" <?php checked( $display_preloader, 'pages' ); ?>>In Pages only.
                                        </label>
                                        <br>
                                        <label title="Display Preloader in categories only">
                                            <input type="radio" name="wptpreloader_screen" value="cats" <?php checked( $display_preloader, 'cats' ); ?>>In Categories only.
                                        </label>
                                        <br>
                                        <label title="Display Preloader in tags only">
                                            <input type="radio" name="wptpreloader_screen" value="tags" <?php checked( $display_preloader, 'tags' ); ?>>In Tags only.
                                        </label>
                                        <br>
                                        <label title="Display Preloader in attachment only">
                                            <input type="radio" name="wptpreloader_screen" value="attachment" <?php checked( $display_preloader, 'attachment' ); ?>>In Attachment only.
                                        </label>
                                        <br>
                                        <label title="Display Preloader in 404 error page">
                                            <input type="radio" name="wptpreloader_screen" value="404error" <?php checked( $display_preloader, '404error' ); ?>>In 404 Error Page only.
                                        </label>
                                        <br>
                                        <label title="Display Preloader in WooCommerce page">
                                            <?php
                                                if( function_exists('is_woocommerce') ){
                                                    ?>
                                                        <input type="radio" name="wptpreloader_screen" value="woocommerce" <?php checked( $display_preloader, 'woocommerce' ); ?>>In WooCommerce only (shop page, product page, checkout page, etc).
                                                    <?php
                                                }else{
                                                    ?>
                                                        <input disabled type="radio" name="wptpreloader_woo" value="disabled">In WooCommerce only (shop page, product page, checkout page, etc).<br><span style="font-style: italic; color:#666; font-size:14px;">This option will be available after activation WooCommerce plugin.</span>
                                                    <?php
                                                }
                                            ?>
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label>Preloader Element</label></th>
                                <td>
                                    <p class="description">Open <a target="_blank" href="<?php echo $header_file_url; ?>">header.php</a> file for your theme, <?php echo $preloader_element; ?></p>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    
                    <p class="submit"><input id="submit" class="button button-primary" type="submit" name="submit" value="Save Changes"></p>
                </form>
                
                <div class="tool-box">
                    <h3 class="title">Recommended Links</h3>
                    <ul>
                        <li>Get collection of 88 Premium WordPress themes with a lot of features and premium support for $80 only! <a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=24967&tid1=preloader_plugin_st&url=35248" target="_blank">Get it now</a>.</li>
                        <li>Best SSD Web Hosting for $3.95/Monthly only! <a href="https://www.siteground.com/go/preloader_plugin_st" target="_blank">Get it now</a>.</li>
                    </ul>
                    <p><strong>See also:</strong></p>
                    <p><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=24967&tid1=preloader_plugin_sb&url=35248" target="_blank"><img style="max-width: 100% !important;" src="<?php echo plugins_url( '/banner/et.jpg', __FILE__ ); ?>"></a></p>
                    <p><a href="https://www.bluehost.com/track/wptime/preloader-plugin-s" target="_blank"><img style="max-width: 100% !important;" src="<?php echo plugins_url( '/banner/bh.png', __FILE__ ); ?>"></a></p>
                    <p style="color: #888 !important; font-size: 12px !important;">Why do you see <strong>"Recommended Links"</strong> and banners in this plugin?<br>We offer you free professional plugins for free (e.g. The Preloader plugin), so you'll see <strong>"Recommended Links"</strong> and banners, which is the only support source.</p>
                </div>
                
            </div>
        <?php
    } // settings page function