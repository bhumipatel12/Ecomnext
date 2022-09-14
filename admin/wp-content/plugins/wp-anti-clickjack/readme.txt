=== WP Anti-Clickjack ===
Contributors: someguy9
Donate link: https://github.com/someguy9/wp-anti-clickjack
Tags: anti click jacking, security, Browser Frame Breaking Script, clickjacking
Requires at least: 4.5.0
Tested up to: 5.9
Stable tag: 1.7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds an HTTP header and OWASP's legacy browser frame breaking script to prevent clickjacking.

== Description ==

Prevent your site from being clickjacked with this plugin that includes the X-Frame-Options SAMEORIGIN and a modified version of OWASP's legacy browser frame breaking script. The OWASP's legacy browser frame breaking script is modified to work in browsers without Javascript (as well as browsers with Javascript). This additional script prevents other sites from putting your site in an iFrame for security reasons.

You can read more about clickjacking defense on [OWASP](https://cheatsheetseries.owasp.org/cheatsheets/Clickjacking_Defense_Cheat_Sheet.html)

= Additional Details =

If you'd like to disable the clickjacking script on a page you can use this filter in your theme's functions.php file.

`add_filter('wp_anti_clickjack', '__return_false' );`

If you'd like to disable the clickjacking X-Frame-Options HTTP header you can use this filter in your theme's functions.php file.

`add_filter('wp_anti_clickjack_x_frame_options_header', '__return_false' );`


== Installation ==

To install this plugin:

1. Download the plugin
2. Upload the plugin to the wp-content/plugins directory,
3. Go to "plugins" in your WordPress admin, then click activate.

== Frequently Asked Questions ==


== Changelog ==

= 1.7.2 =
* Added support for Divi builder

= 1.7.1 =
* Tested up to WordPress 5.9

= 1.7.0 =
* Added HTTP header X-Frame-Options: SAMEORIGIN to further prevent clickjacking

= 1.6.5 =
* Tested up to WordPress 5.8

= 1.6.4 =
* Tested up to WordPress 5.7

= 1.6.3 =
* Support for Cornerstone Page Builder

= 1.6.2 =
* Support for WPBakery Page Builder

= 1.6.1 =
* Tested up to WordPress 5.6

= 1.6.0 =
* Added filter to disable the anti-clickjack script when needed
* Tested up to WordPress 5.5

= 1.5.4 =
* Increase WordPress supported version to 5.4

= 1.5.3 =
* Increase WordPress supported version to 5.3

= 1.5.2 =
* Bug fix for PHP warning

= 1.5.1 =
* Increase WordPress supported version to 5.2.2

= 1.5.0 =
* Bug fix when updating plugins/themes
* Support for Thrive editor

= 1.4.0 =
* Tested up to 4.8.9 and fixed conflicts with Elementor (if you are having an issue with a specific page builder please contact me)

= 1.3.0 =
* Tested up to 4.8.0

= 1.2.0 =
* Tweaked to add anti-clickjacking script to the admin pages

= 1.1.1 =
* Tested up to 4.7.2

= 1.1 =
* Bug fix causing Customizer.php to refresh constantly

= 1.0 =
* Initial Release
