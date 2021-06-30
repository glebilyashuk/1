=== LuckyWP Cookie Notice (GDPR) ===
Contributors: theluckywp
Donate link: https://theluckywp.com/product/cookie-notice-gdpr/
Tags: cookie, notice, GDPR, eu cookie law, cookie law
Requires at least: 4.7
Tested up to: 5.5
Stable tag: 1.2
Requires PHP: 5.6.20
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The plugin allows you to notify visitors about the use of cookies (necessary to comply with the GDPR in the EU).

== Description ==

The "LuckyWP Cookie Notice (GDPR)" plugin allows you to notify visitors about the use of cookies (necessary to comply with the GDPR in the EU).

Plugin will show a notice with button "Accept", button "Reject" (optionally) and link "Read more…" (optionally). After user click on "Accept” or "Reject" notice hidden. Support "Show Again" tab.

#### Features

* Customizable notice message and button labels
* Customizable template (bar or box)
* Customizable position
* Customizable appearance
* Responsive Web Design
* Support "Show Again" tab
* Option: scripts for users who have given consent
* Option: cookie expire
* Option: reload page after buttons "Accept" or "Reject" click
* Caching plugins support (WP Super Cache, W3 Total Cache, WP Fastest Cache, LiteSpeed Cache, WP Rocket, …)

#### For programmers

To get the cookie notice status in PHP use `lwpcng_cookies_accepted()` or `lwpcng_cookies_rejected()` functions.

== Installation ==

#### Installing from the WordPress control panel

1. Go to the page "Plugins > Add New".
2. Input the name "LuckyWP Cookie Notice (GDPR)" in the search field
3. Find the "LuckyWP Cookie Notice (GDPR)" plugin in the search result and click on the "Install Now" button, the installation process of plugin will begin.
4. Click "Activate" when the installation is complete.

#### Installing with the archive

1. Go to the page "Plugins > Add New" on the WordPress control panel
2. Click on the "Upload Plugin" button, the form to upload the archive will be opened.
3. Select the archive with the plugin and click "Install Now".
4. Click on the "Activate Plugin" button when the installation is complete.

#### Manual installation

1. Upload the folder `luckywp-cookie-notice-gdpr` to a directory with the plugin, usually it is `/wp-content/plugins/`.
2. Go to the page "Plugins > Add New" on the WordPress control panel
3. Find "LuckyWP Cookie Notice (GDPR)" in the plugins list and click "Activate".

### After activation

After the plugin is successfully installed the menu item "Cookie Notice (GDPR)" will appear in the menu "Settings" of the WordPress control panel.

== Screenshots ==

1. Notice Appearance
2. General Settings
3. Appearance Settings
4. Scripts Settings
5. Advanced Settings

== Changelog ==

= 1.2 — 2020-08-15 =
* Added field for scripts added after opening <body>.
* Minor refactoring.

= 1.1.1 — 2019-10-25 =
* Fixed: in some cases color of visited link "More" was overridden by theme.

= 1.1.0 — 2018-11-24 =
* Added caching plugins support.
* Added translation support via translate.wordpress.org.
* Bug fix.
