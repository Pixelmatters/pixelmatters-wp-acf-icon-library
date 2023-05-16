=== Pixelmatters - ACF PRO Icon Library ===
Contributors: pixelmatters
Tags: ACF, Icon Picker, Icon Library, WPGraphql, Pixelmatters
Donate link: N/A
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 7.2
Stable tag: 1.2.0
License: MIT
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds an ACF PRO Icon Library and Icon Picker that can be used within other ACF Field Groups

== Description ==
WordPress plugin that adds an ACF Icon Library and Icon Picker that can be used within other ACF Field Groups:
- Adds a WordPress BO page called \"Icon Library\" where you can add your Icons;
- Adds an ACF called \"Icon Picker\" where you can choose from the Icons added to the \"Icon Library\";
- Supports WPGraphQL and triggers a full build whenever an Icon is added, replaced or deleted;

== Installation ==
Via Composer
1. Add a line to your repositories array: `{ \"type\": \"git\", \"url\": \"https://github.com/Pixelmatters/pixelmatters-wp-acf-icon-library\" }`
2. Add a line to your require block: `\"pixelmatters/pixelmatters-wp-acf-icon-library\": \"main\"`
3. Run: composer update

Manually
1. Copy the `px-acf-icon-library` folder into your `wp-content/plugins` folder
2. Activate the Icon Library plugin via the plugins admin page
3. Add your Icons to the Icon Library options page
4. Create a new field via ACF and select the Icon Selector type

== Frequently Asked Questions ==
Does this require any plugins? Yes it does:
- [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/)
- [ACF - Image Aspect Ratio Crop](https://wordpress.org/plugins/acf-image-aspect-ratio-crop/)
- [WPGraphQL](https://wordpress.org/plugins/wp-graphql/)
- [WPGraphQL - ACF](https://github.com/wp-graphql/wp-graphql-acf)

== Screenshots ==
1. Icon library
2. Icon picker

== Changelog ==
= 1.2.0 =
* Autofill icon title with the uploaded image title

= 1.1.0 =
* Hides used images in Icon Library from WordPress Media Library

= 1.0.0 =
* First release from Pixelmatters


== Upgrade Notice ==
Please upgrade the plugin to get the latest fixes and features.