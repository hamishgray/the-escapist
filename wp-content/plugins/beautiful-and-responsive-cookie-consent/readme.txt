=== Beautiful Cookie Consent Plugin - WordPress Plugin ===
Tags: cookie consent, cookie banner, itp 2.1, eu cookie law, gdpr cookie, banner, gdpr, dsgvo, cookie, responsive, cookie consent banner, insites, osano, cookieconsent
Requires at least: 4.0
Donate link: https://www.paypal.me/nikelschubert/6.00EUR
Tested up to: 5.3
Requires PHP: 5.2.17
License: GPLv3

== Description ==
An easy way to get a beautiful GDPR Cookie Consent Banner. Customize it to match your compliance requirements and website layout.
**Important** 
Most Cookie Banner plugins set javascript cookies. In Safari and Firefox these cookies have a limited lifetime of seven days, independent of the setting.
Consequence: Your user have to opt in every seven days again. And sees the banner every seven days. Which is kind of annoying.
With this Plugin the consent cookie stays save and is not limited in lifetime. If you set the option.

If you want to save more cookies, check out this plugin: https://wordpress.org/plugins/itp-cookie-saver/.

This really beautiful plugin wraps the solution provided by osano (https://www.osano.com/cookieconsent/download/) into a wordpress plugin.
This Version uses 3.1.0 from osano. It is optimized for performance and for a low database impact.

This Banner is super responsive and is highly customizable.
**Features**
1. Fully responsive.
2. Make consent cookie a backend cookie to prevent automatic deletion which many browsers do for javascript/frontend cookies.
3. reload after acceptance
4. Show banner until user accepts cookies
5. Users can easily change cookie settings: choose between an extra tab or with a shortcode.
6. Fully customizable colors and text.
7. It is for all kind of compliance: opt-out, opt-in, info only
8. choose between different themes
9. choose the position on your website
10. choose cookie name, duration, domain.
11. cookie setting management via shortcode: [cc_revoke_settings_link_nsc_bar]
.... and there are a lot more.

== Screenshots ==

1. Admin area with banner example
2. Admin area with banner example
3. Admin area with banner example
4. Admin area with banner example
5. Admin area with banner example
6. Admin area with banner example
7. Admin area with banner example
8. Mobile example

== Installation ==
Just install this plugin and go to Settings > Cookie Consent Banner to change the default config and to activate the banner.

== Changelog ==

= 1.7.1 =
* bug fixes

= 1.7 =
* added feature "Ask until acceptance": show banner until user consents.

= 1.6 =
* added "reload after banner close" for more accurate tracking after tracking opt in.

= 1.5 =
* added a field to configure the text of "Cookie Policy" tab.
* "Cookie Policy" tab can now be deactivated
* users can manage their cookie settings now independently from "Cookie Policy" tab: you can add the link easily easily by shortcode: [cc_revoke_settings_link_nsc_bar].
* bit of clean up of admin area

= 1.4.1 =
* Bug Fix: sometimes the Banner was behind the content.
* Bug Fix: minor Performance Issue.

= 1.4 =
* New Feature: cookie banner is now configurable with a form and not only with a json string. If you want to, you can still use the json.
* a lot stuff under the hood.

= 1.3 =

* New Feature: Added prevention for ITP 2.1 cookie deletion. Safari and Firefox limit the lifetime of a cookie which is set by javascript to seven days.
Most cookie banner plugins set javascript cookies, that means that your returning user will see every seven days your banner and have to opt in again.
* Improvement: Cookie in preview modus only deleted if you visit the settings page of that plugin, not admin in general.

= 1.2 =

* Improvement: When visiting an admin page, consent cookie is only deleted, if "preview banner" setting is activated.

= 1.1.2 =

* minor bug fixes.

= 1.1.1 =

* fixes for PHP 5.4

= 1.1 =

New Features

* you can activate or deactivate the banner now.
* you see a preview in the plugin settings area now.

= 1.0.5 =

* Improvement: Updated to cookie consent library 3.1. Now dismissOn... functions are working.

= 1.0.4 =

* FIX: Now Really: with an update of this plugin the configuration of the cookie banner is not overwritten anymore.

= 1.0.3 =

* FIX: small fixes and improvements, e.g. admin errors will only be displayed on admin page of this plugin.
* Improvement: added Icon for this plugin.

= 1.0.2 =

* FIX: with an update of this plugin the configuration of the cookie banner is not overwritten anymore.

= 1.0.1 =

* Improvement: added compatibility in readme.txt

= 1.0 =

* First Version of this lightweight Plugin. More to come!
