README.txt
==========
The Mobile Tools module provides Drupal developers with some tools to assist in making adjustments to your site based on the visitor's device.

INSTALLATION
============

**NOTE**
If you have previously installed the 7.x-2.0-unstable, an older dev release or upgrading from the 6.x branch your site settings will not migrate over. Be sure to fully uninstall mobile tools before upgrading to this version.


This module has the following dependencies:

- [PURL](http://drupal.org/project/purl)
- [CTools](http://drupal.org/project/ctools)

It can also take advantage of the following modules if available:

- [Spaces](http://drupal.org/project/spaces)
- [BrowsCap](http://drupal.org/project/browscap)
- [Panels](http://drupal.org/project/panels)
- [Context](http://drupal.org/project/context)
- [Context HTTP Headers](http://drupal.org/project/context_http_headers)
- [ThemeKey](http://drupal.org/project/themekey)

To install the module and all dependencies from the module administration panel.

Then you can begin configuration by going to admin/config/system/mobile-tools

You can enable any of the defined configuration presets, modify them or create your own.

USAGE
=====

Mobile Tools works with the concept of "device groups". A device group is any grouping of conditions or requirements for a given type of device or set of devices. Each defined device group can cause Drupal to display a different theme, override site variables, display different views displays among other things.

For example, you can define a set of device groups as follows:

- desktop
- tablet
- phone

Each can be triggered in various ways. The most common way is by a custom URL pattern. You could trigger each device group's settings by using the following URL structure in your configuration:

- example.com
- touch.example.com
- m.example.com


Subdomains aren't your only options. URL handling in Mobile Tools is powered by the [PURL](http://drupal.org/project/purl) module. This means you can active a device group using a different domain, path, path pair and more. You can even define your own PURL processor if you so choose. For more information on creating PURL processors, see the README.txt in the [PURL](http://drupal.org/project/purl) module.

Advanced Usage
==============

Automatic Redirection
---------------------
If you want to direct your users to the appropriate site automatically based on their detected device group, you have two modes you can use

1. First visit redirect
2. Automatic redirect

The first will redirect the user as their session is initiated. The device group is determined and then a one-time redirection will occur to send the user to the appropriate URL. If the user then decides to explicitly choose which device group site to visit, then the redirection won't interfere.

The second forces the user into their device group regardless of their choice. There is no means of overriding the automatic redirection.

Device/Capability Detection
---------------------------

Another way of triggering a device group is by using device detection. You can go by the user-agent headers in the request object and use [Browscap](http://drupal.org/project/browscap) to determine the type of device. You could have a set of device groups as follows:

- iOS
- Android
- Gecko

Assign the set of detection rules to your device group and Browscap will trigger the group accordingly. Other modules can also implement their own triggers for device detection if they so choose. See mobile_tools.api.php for more details.

SETTINGS AND OPTIONS
====================

Each device group has three key functions:

1. Allow a theme override
2. Trigger contextual changes
3. Allow a site configuration override

The first is handled using the [ThemeKey](http://drupal.org/project/themekey) module. Each device group can trigger a defined rule in ThemeKey to switch the theme for the incoming request.

The second uses a variety of modules to enable the changes. Most notably are [Context](http://drupal.org/project/context) and [CTools](http://drupal.org/project/ctools). Context allows for regions, blocks, page variables and a range of other values to be changed based on a set of conditions/reactions. Mobile Tools defines a context condition for active device groups. It also includes a CTools access plugin which can be used with modules such as [Panels](http://drupal.org/project/panels) to change layouts, displayed content and [Views](http://drupal.org/project/views) displays once again based on active device groups.

The third is handled using the [Spaces](http://drupal.org/project/spaces) module. Spaces allows you to override Drupal objects giving custom configurations for each Space. Each device group is it's own Space and therefore will load configuration options in the following order

1. custom
2. preset
3. sitewide

For example, when variable_get('site_name'); is called in a Space, it will first look at the spaces_overrides table to see if there is a value set for 'site_name'. Next, it will check any defined values in the Space preset for this value. Finally, it will load the normal value from the default Drupal table. For more information, see the README.txt in the [Spaces](http://drupal.org/project/spaces) module.

CACHING
=======

Doesn't work yet ;-)
@TODO rewrite any required caching mechanisms
@TODO write about caching here

MAINTAINER
=============
Mathew Winstone (minorOffense)
http://twitter.com/mathewwinstone
http://coldfrontlabs.ca