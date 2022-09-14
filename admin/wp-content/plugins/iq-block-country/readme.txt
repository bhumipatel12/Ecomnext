=== iQ Block Country ===
Contributors: iqpascal
Donate link: https://webence.nl/plugins/donate
Tags: spam, block, country, comments, ban, geo, geo blocking, geo ip, block country, block countries, ban countries, ban country, allow list, block list, security
Requires at least: 3.5.2
Tested up to: 6.0.1
Stable tag: 1.2.18
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 7.0

Allow or disallow visitors from certain countries accessing (parts of) your website


== Description ==

iQ Block Country is a plugin that allows you to limit access to your website content. You can either allow or disallow visitors from defined countries to (parts of) your content.

For instance if you have content that should be restricted to a limited set of countries you can do so.
If you want to block rogue countries that cause issues like for instance hack attempts, spamming of your comments etc you can block them as well.

Do you want secure your WordPress Admin backend site to only your country? Entirely possible! You can even block all countries and only allow your ip address.

And even if you block a country you can still allow certain visitors by putting their ip address on the allow list just like you can allow a country but put ip addresses on the block list from that country.

You can show blocked visitors a message which you can style by using CSS or you can redirect them to a page within your WordPress site. Or you can redirect the visitors to an external website.

You can (dis)allow visitors to blog articles, blog categories or pages or all content.

Stop visitors from doing harmful things on your WordPress site or limit the countries that can access your blog. Add an additional layer of security to your WordPress site.

This plugin uses the GeoLite database from Maxmind. It has a 99.5% accuracy so that is pretty good for a free database. If you need higher accuracy you can buy a license from MaxMind directly.
If you cannot or do not want to download the GeoIP database from Maxmind you can use the GeoIP API website available on https://webence.nl/geoip-api/

If you want to use the GeoLite database from Maxmind you will have to download the GeoIP database from MaxMind directly and upload it to your site.
The Wordpress license does not allow this plugin to download the MaxMind Geo database for you.

Please be aware that although this plugin can help you greatly with reducing the number of 'bad' visitors on your website it is not fool proof and those who really want to visit your site may find a away.
This is not a security issue but a simple fact of today. Nobody can guarantee you 100% security as it is a constant battle between the good guys and the bad guys.

If you are sure your webhosting or yourself does not use any form of caching or proxying we recommend setting the "Override IP information" on the Home tab to REMOTE_ADDR 

Do you need help with this plugin? Please email support@webence.nl.

= GDPR Information =

This plugin stores data about your visitors in your local WordPress database. The number of days this data is stores can be configured on the settings page. You can also disable logging any data.

Data which is stored of blocked visitors:

- IP Address
- Date and time of the visit
- URL that was requested
- Country of the IP address
- If the block happened on your backend or your frontend

Data which is stored on non blocked visitors:

 - Nothing

If you allow tracking (yeah if you do!) you share some information with us. This is only the IP address of a blocked request on your backend. No other information is send and only the IP address is logged on our systems to gather how many times that IP address have attempted to login to a backend. We do not log which site was visited or which URL just only the IP address So we cannot lead an ip address back to a specific website or user. If an IP address is not blocked again within a month we will remove the IP address from the list.

If you use the GeoIP API service you send the IP address of your visitor to one of our servers. This IP Address is however in no way stored at our servers and only used to convert it to a country id.

= Using this plugin with a caching plugin =

 Please note that many of the caching plugins are not compatible with this plugin. The nature of caching is that a dynamically build web page is cached into a static page.
 If a visitor is blocked this plugin sends header data where it supplies info that the page should not be cached. Many plugins however disregard this info and cache the page or the redirect. Resulting in valid visitors receiving a message that they are blocked. This is not a malfunction of this plugin.

Disclaimer: No guarantees are made but after some light testing the following caching plugins seem to work: Comet Cache, WP Super Cache
Plugins that do NOT work: W3 Total Cache, Hyper cache, WPRocket

== Installation ==

1. Unzip the archive and put the `iq-block-country` folder into your plugins folder (/wp-content/plugins/).
2. Create an account at https://www.maxmind.com/en/geolite2/signup
3. Login to your account.
4. Download the GeoIP2 Country database (name is GeoLite2-Country.tar.gz) from your account.
5. Unzip the GeoIP2 database and upload the GeoLite2-Country.mmdb file to your upload dir usually /wp-content/uploads/GeoLite2-Country.mmdb
6. If you do not want to or cannot download the MaxMind GeoIP database you can use the GeoIP API.
7. Activate the plugin through the 'Plugins' menu in WordPress
8. Go to the settings page and choose which countries you want to ban. Use the ctrl key to select multiple countries

== Frequently Asked Questions ==

= How come that I still see visitors from countries that I blocked in Statpress or other statistics software? =

It’s true that you might see hits from countries that you have blocked in your statistics software. 

This however does not mean this plugin does not work, it just means somebody tried to access a certain page or pages and that that fact is logged.

If you are worried this plugin does not work you could try to block your own country or your own ip address and afterwards visit your frontend website and see if it actually works. Also if you have access to the logfiles of the webserver that hosts your website  you can see that these visitors are actually denied with a HTTP error 403.

= How come I still see visitors being blocked from other security plugins? =

Other wordpress plugins handle the visitors also. They might run before iQ Block Country or they might run after iQ Block Country runs.

This however does not mean this plugin does not work, it just means somebody tried to access a certain page, post or your backend and another plugin also handled the request.

If you are worried this plugin does not work you could try to block your own country or your own ip address and afterwards visit your frontend website and see if it actually works. Also if you have access to the logfiles of the webserver that hosts your website  you can see that these visitors are actually denied with a HTTP error 403.

= This plugin does not work, I blocked a country and still see visitors! =

Well, this plugin does in fact work but is limited to the data MaxMind provides. Also in your statistics software or logfiles you probably will see log entries from countries that you have blocked. See the "How come I still see visitors..." FAQ for that.

If you think you have a visitor from a country you have blocked lookup that specific IP address on the tools tab and see which country MaxMind thinks it is. If this is not the same country you may wish to block the country that MaxMind thinks it is.

= Whoops I made a whoops and blocked my own country from visiting the backend. Now I cannot login... HELP! =

I am afraid this can only be solved by editing your MySQL database,directly editing the rows in the wp_options table. You can use a tool like PHPMyAdmin for that.

If you don't know how to do this please ask your hosting provider if they can help, or ask me if I can help you out!

= Why do you not make something that can override that it blocks my country from the backend. =

Well, if you can use a manual override so can the people that want to 'visit' your backend. 

This plugin is meant to keep people out. Perhaps you keep a key to your house somewhere hidden in your garden but this plugin does not have a key somewhere hidden... So if you locked yourself out you need to call a locksmith (or pick the lock yourself of course!)

= How can I style the banned message? =

You can style the message by using CSS in the textbox. You are also able to include images, so you could visualize that people are banned from your site.

You can also provide a link to another page explaining why they might be banned. Only culprit is that it cannot be a page on the same domain name as people would be banned from that page as well.

You can use for instance:

<style type="text/css">
  body {
    color: red;
    background-color: #ffffff; }
    h1 {
    font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif }
  </style>

<h1>Go away!</h1>

you basicly can use everything as within a normal HTML page. Including images for instance.

= Help! My IP address is not detected properly =

The iQ Block Country plugin does it's best to detect the real IP address of your visitors even if they try to hide it. However sometimes it fails in doing so and in that case you might get an different IP address for your users.
For instance your webhosting IP address.

You can overrule this process if it does not work properly in your case. Go to the Tools tab there is a section IP Address Information. It will show all server headers that contain IP information, see which header has your own IP address.

On the home tab you can set this header where it says 'Override IP information'.

You should only use this if the automatic IP detection fails otherwise please have it set to "No override"

= Does this plugin work with caching? =

In some circumstances: No

The plugin does it best to prevent caching of the "You are blocked" message. However most caching software can be forced to cache anyway. You may or may not be able to control the behavior of the caching method.

The plugin does it bests to avoid caching but under circumstances the message does get cached.
Either change the behavior of your caching software or disable the plugin.

If you want to block visitors from the frontend using a caching mechanism is not recommended.

= How can I select multiple countries at once? =

You can press the CTRL key and select several countries.

Perhaps also a handy function is that you can type in a part of the name of the country!

You can select/deselect all countries by selecting "(de)select all countries..."

If you just want to allow some countries you can also use the 'Block all countries except those selected below' function by selecting the countries you want to allow and select invert this selection.

= How can I get a new version of the GeoIP database? =

You can download the database(s) directly from MaxMind and upload them to your website.

1. Create an account at https://www.maxmind.com/en/geolite2/signup
2. Login to your account.
1. Download the GeoIP2 Country database (name is GeoLite2-Country.tar.gz) from your account.
2. Unzip the GeoIP2 database and upload the GeoLite2-Country.mmdb file to your upload dir usually /wp-content/uploads/GeoLite2-Country.mmdb

Maxmind updates the GeoLite database every month.

= I get "Cannot modify header information - headers already sent" errors =

This is possible if another plugin or your template sends out header information before this plugin does. You can deactivate and reactivate this plugin, it will try to load as the first plugin upon activation.

If this does not help you out deselect "Send headers when user is blocked". This will no longer send headers but only display the block message. This however will mess up your website if you use caching software for your website.
This also does not work if you use a page or url redirect as that relies on sending headers for redirecting the visitor to another page or URL.

= What data get sends to you when I select "Allow tracking"? =

If you select this option each hour the plugin checks if it has new data to send back to the central server. 

This data consists of each IP address that has tried to login to your backend and how many attempts were made since the last check.

If storing or sharing an IP address is illegal in your country do not select this feature.

= The laws in my country do not allow storing IP addresses as it is personal information. =

You can select the option on the home tab "Do not log IP addresses" to stop iQ Block Country from logging IP addresses. This will however also break the statistics.

= I have moved my WordPress site to another host. Now iQ Block Country cannot find the GeoIP databases anymore =

Somewhere in your WordPress database there is a wp_options table. In the wp_options table is an option_name called 'upload_path'.

There probably is an (old) path set as option_value. If you know your way around MySQL (via PHPMyAdmin for instance) you can empty the option_value.
This should fix your problem.

Please note that your wp_options table may be called differently depending on your installation choices.

= Jetpack does not work anymore with your plugin! =

Jetpack uses xmlrpc.php to communicate with your site. xmlrpc.php is considered as a backend url and therefore blocked if needed.

You can allow Jetpack by selecting "Jetpack by wordpress.com" as a search engine on the services tab.

= Why is the GeoLite database not downloaded anymore ? =

The Wordpress guys have contacted me that the license of the MaxMind GeoLite database and the Wordpress license conflicted. So it was no longer
allowed to include the GeoLite database or provide an automatic download or download button. Instead users should download the database themselves
and upload them to the website.

Wordpress could be held liable for any license issue. So that is why the auto download en update was removed from this plugin.

= I only want to block certain posts with a specific tag =

As the basic rule is to block all and every post you have to configure this in a special way:

- Select the countries you want to block on the frontend tab
- Select the option "Block visitors from visiting the frontend of your website" on the frontend tab
- Select the option "Do you want to block individual categories" on the categories tab.
- Do not select any categories (unless you want to of course)
- Select "Do you want to block individual tags" on the tags tab.
- Select any tag you want to block.

= Is the new GeoIP2 database format supported? = 

Yes since v1.2.0 the new GeoIP2 Country database is supported.

== GeoIP API ==

For your convenience we offer a GeoIP API service. This API is not mandatory to use as you can always use the free MaxMind GeoIP Database.

If you do not want or can't go through the hassle of updating your MaxMind GeoIP database we provide an API service to convert the IP address of your visitors to a country.

If you decide to purchase an GeoIP API Key via https://webence.nl/geoip-api/ you'll get an eMail with your API Key (License Key). 
Once you enter this key in your iQ Block Country settings your license key will be validated at our API service and a the nearest API server to you will be chosen. To do this your website will contact all API servers once to request
an empty file.

Once you use the API service the IP address of your visitors and your API key are send to one of the API servers and converted to a country. The plugin checks if the visitor should be blocked based on that country or not.

What is logged on our end?
* Upon validation of your license key your request will be logged in our webserver logs. (This will be the IP address of your webserver).
* Upon checking an IP address of your visitor this IP address is only used to convert it to the country it belongs to and is not logged. We have no way to link a visitors IP address to your website.
  What is logged is your API Key and the Website URL making the request.

If you decide to purchase the GeoIP API key your PayPal account will be charged by PayPal on a yearly basis. If you want to cancel your subscription you can cancel the subscription at the PayPal website.
If no payments are made by PayPal your API key will automatically expire.

Privacy policy regarding this service specific can be found here: https://webence.nl/wp-content/uploads/2022/06/Privacy-Policy-Webence-API.pdf

== MaxMind Database Usage ==

This plugin uses the Free version of the MaxMind GeoIP2 Country Database. You can also use the paid version but will have to make sure it is uploaded to the same location with the filename of Free database.

MaxMind Terms of Use: https://www.maxmind.com/en/terms-of-use
MaxMind Privacy Policy: https://www.maxmind.com/en/privacy-policy

== Admin Block API ==

For some extra protection we offer the Admin Block API Key. This contains a list of known IP addresses that have visited various WordPress backends in the past month and were blocked.

If you decide to purchase an Admin Block API Key via https://webence.nl/admin-block-api/ you'll get an eMail with your API Key (License Key). 

If you decide to purchase an Admin Block API Key all visitors of your backend will be matched against this list and even if the visitor is from a country that is not blocked they will be blocked if the IP address is on the Admin Block List.

What is logged on our end?
* Upon validation of your license key your request will be logged in our webserver logs. (This will be the IP address of your webserver).
* Upon retrieving the updated blocklist (multiple times a day) this request is logged in our webserver logs (This will be the IP address of your webserver).

Privacy policy regarding this service specific can be found here: https://webence.nl/wp-content/uploads/2022/06/Privacy-Policy-Webence-API.pdf


== Changelog ==

= 1.2.18 =

* Fix: Small errors

= 1.2.17 =

* Fix: Other small error

= 1.2.16 =

* Fix: DBVERSION error

= 1.2.15 =
 * Change: A lot of internal code changes to make it more in line of WordPress Best Practices
 * Change: Added Privacy Policy of GeoIP API / Admin API Key
 * Change: Added Terms of Use / Privacy Policy Of MaxMind

= 1.2.14 =

* Unreleased version

= 1.2.13 =

 * Change: Altered import/export function to make it more secure
 * New: Added "Reset Counters" button to reset your total number of frontend / backend blocks.

= 1.2.12 =

* Change: Added/Changed some services.
* Bugfix: Security issue fixed which could only be abused by people with administrator rights.

= 1.2.11 =

* Change: Minor UI fix
* Change: Added some more checking if the visitors IP is an actual IPv4 or IPv6 address
* Change: Updated README

= 1.2.10 =

* Change: Changed whitelist/blacklist to allow list / block list.
* Change: Removed Paris as GeoIP location
* Change: Added website server address to allow list of the backend to ensure certain WordPress functions keep working if you block the country your website is hosted from the backend.

= 1.2.9 =

* Change: Some codefixes applied

= 1.2.8 =

* Bugfix: Checking the IP address on the tools tab gave wrong information about countries being blocked if the "block countries below" function was used. This had no effect on the actual denying of visitors.
* New: Added GeoIP API usage (not realtime) of the current month to the tools tab as beta function.

= 1.2.7 =

* Change: Typo in San Francisco (Thanks to Shizart)
* New: Added France as GeoIP location
* Change: Update to text description due to MaxMind update to Geo2Lite database policy
* Bugfix: No empty location anymore (Thanks to Stonehenge Creations)

= 1.2.6 =

* Change: Added better support to detect if mbstring is available for usage.

= 1.2.5 =

* New: Mediapartners-Google service added
* Change: Changed webserver ip detection a bit
* New: You can unblock feed pages on the frontend configuration tab for the people who want to block visitors but want to allow access to the (RSS) feeds

= 1.2.4 =

* Change: Changed webserver ip detection a bit

= 1.2.3 =

* Change: Changed inverse option to that you have to select between 'Block countries selected below' or 'Block all countries except those selected below' as inverse option caused some confusion.
* New: Added 'inverse' function to the pages selection as well. So you can now select the pages you want to block or select the pages you do not want to have blocked and block all other pages.
* New: Added override function for IP detection.
* Change: Cutoff for long urls on the statistics page.

= 1.2.2 =

* New: Added MOZ as service.
* New: Added SEMrush as service.
* New: Added SEOkicks as service.
* New: Added EU2 and EU3 servers for GeoIP API
* New: Added support for WPS Hide Login
* Change: Deleted Asia server due to bad performance
* Change: Altered behavior of flushing the buffer

= 1.2.1 =

* New: Added Link Checker (https://validator.w3.org/checklink) as service.
* New: Added Dead Link Checker as a service.
* New: Added Broken Link Check as a service.
* New: Added Pingdom as a service
* Change: Adjusted loading chosen library (Credits to Uzzal)
* Change: Display error when only the legacy GeoIP database exists and not the new GeoIP2 version

= 1.2.0 =

* New: Added support for GeoIP2 country database
* New: Added Pinterest as service

== Upgrade Notice ==

= 1.1.19 =

This plugin no longer downloads the MaxMind database. You have to download manually or use the GeoIP API.