=== Sullivan ===
Contributors: Anlino
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=anders%40andersnoren%2ese&lc=US&item_name=Free%20WordPress%20Themes%20from%20Anders%20Noren&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 4.5
Tested up to: 5.4
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tested up to: 5.7.1
Requires PHP: 5.4


== Installation ==

1. Upload the theme
2. Activate the theme

If you want to use the slideshow functionality, you also need to install and activate the Sullivan Compatibility Plugin. It's available for free on the WordPress.org plugin directory, or through the Plugins page in the WordPress dashboard.


== Slideshows ==

1. Install and activate the Sullivan Compatibility Plugin, if you haven't already.
2. Click Slideshows > Add New in the admin menu.
3. Fill in the fields, select a post thumbnail and select which of the slideshow locations you want the slide to be displayed in. The shop location is only visible if you have WooCommerce installed.
4. Save the slide.

The slide will now be displayed in the slideshow location you selected.


== Full Width Page Template ==

1. Create a new page, or edit an existing one.
2. Click the dropdown beneath "Template" in "Page Attributes", and select Full Width Template.


== Add a Post Gallery ==

1. Create a new post, or edit an existing one.
2. In the right hand column, in the box titled "Format", select "Gallery".
3. Select the very first line of the content of your post (either in the Visual Editor och the Text Editor – which one doesn't matter).
4. Click the button labeled "Add Media", directly above the content box.
5. In the lefthand panel navigation, click on "Create Gallery".
6. Select the images you want to include in your slideshow. Add captions if you want to display a descriptive text along with each slide.
7. When you have selected the images you want to include, click the "Create a new gallery" button in the lower right corner of the window.
8. Finally, click the "Insert gallery" button in the same corner of the window.
9. Publish the post. As long as you leave the gallery as the first element in your content, it will be displayed in place of the featured image for that post (and won't be displayed at the start of the post itself).


== Licenses ==

Archivo font
License: SIL Open Font License, 1.1, https://opensource.org/licenses/OFL-1.1
Source: https://fonts.google.com/specimen/Archivo

Charis SIL font
License: SIL Open Font License, 1.1, https://opensource.org/licenses/OFL-1.1
Source: https://software.sil.org/charis/

Images in screenshot.png from Pexels
License: Creative Commons Zero (CC0), https://creativecommons.org/publicdomain/zero/1.0/
Source: https://www.pexels.com/
- Slideshow image: https://www.pexels.com/photo/boutique-clothes-clothing-indoors-264554/
- Product image #1: https://www.pexels.com/photo/accessory-analog-analogue-band-277431/
- Product image #2: https://www.pexels.com/photo/brown-and-black-round-analog-watch-on-beige-rocks-220570/
- Product image #3: https://www.pexels.com/photo/dial-electronics-hands-indoors-277308/
- Product image #4: https://www.pexels.com/photo/round-silver-colored-analog-watch-beside-compass-691640/

FontAwesome Icons
License: SIL Open Font License, 1.1, https://opensource.org/licenses/OFL-1.1
Source: https://www.fontawesome.io

FontAwesome Code
License: MIT License, https://opensource.org/licenses/MIT
Source: https://www.fontawesome.io

Flexslider
License: GNU General Public License v2.0, https://github.com/woocommerce/FlexSlider/blob/master/LICENSE.md
Source: https://github.com/woocommerce/FlexSlider

Ion Icons
License: MIT License, https://opensource.org/licenses/MIT
Source: http://ionicons.com/


== Changelog ==

Version 1.29 (2021-05-05)
-------------------------
- Fixed the `sullivan_header_inner_opening` action being triggered twice in the header (thanks, @civgio).

Version 1.28 (2021-04-21)
-------------------------
- Fixed the display of featured images on the search results page for non-products.
- Disabled smooth scroll for product tabs due to it causing the window to scroll to the top of the page.
- Updated the "Tested up to" value.

Version 1.27 (2020-08-25)
-------------------------
- Fixed the footer widget markup structure being broken if the second widget area is empty and other widget areas are set.

Version 1.26 (2020-08-24)
-------------------------
- Fixed the Post Meta Customizer setting being broken in WordPress 5.5.

Version 1.25 (2020-08-21)
-------------------------
- Added "Tested up to" and "Requires PHP" to style.css and readme.txt.
- Changed the sanitize_callback parameter of the fallback image setting in the Customizer from esc_url_raw to absint, fixing the ID being stripped on save (thanks, @timvanheugten).
- Fixed the top/bottom margin of the first/last element in the entry content not being removed in some cases.

Version 1.24 (2020-04-11)
-------------------------
- Renamed the editor style files, and moved them to `/assets/css/`.
- Added social block styles.
- Fixed the "Update cart" button on the cart page not being visible when the coupon form is hidden.
- Increased color contrast by bumping the lightest gray color to #767676.
- Added base block margins, moved block styles to their own heading, cleaned up block styles a bit (not enough, never enough).

Version 1.23 (2020-04-02)
-------------------------
- Fixed Social Icons issue in mobile menu.
- Bumped FontAwesome to version 5.9.0, removed unneccessary font files, and added support for a lot more social icons. You might need to empty the cache to render this change correctly, depending on your setup.
- Updated WooCommerce version of result-count.php template override.
- Added widget ID output to register_sidebar calls.
- Improved the structure of the footer social menu in order to make it better at handling a lot of icons.

Version 1.22 (2019-07-17)
-------------------------
- Fixed intrinsicRatioEmbeds only targeting elements in `.post`, which missed embeds on pages
- Added theme URI to style.css
- Updated "Tested up to"
- Added theme tags
- Added skip link
- Don't show comments if the post is password protected
- Don't show the post thumbnail if the post is password protected
- Fixed font issues in the block editor styles
- Other minor tweaks to the block editor styles
- Updated `single-product/rating.php` with the latest WooCommerce version (v3.6.0)

Version 1.21 (2019-04-16)
-------------------------
- Fixed IE11 issues

Version 1.20 (2019-04-15)
-------------------------
- Added actions to the opening and closing of the header-inner element, to make it easier to add custom elements to the header
- Changed page-header to use min-height instead of height, to prevent content overflow
- Added margin to entry-content blockquote elements
- Added the theme version number to enqueues of theme files

Version 1.19 (2019-04-07)
-------------------------
- Added the new wp_body_open() function, along with a function_exists check

Version 1.18 (2019-03-29)
-------------------------
- Removed title and href attributes from article elements
- Better styling of elements in the checkout

Version 1.17 (2019-03-27)
-------------------------
- Made the header quicklinks filterable, and able to handle links with set URLs in addition to WooCommerce account page endpoints

Version 1.16 (2019-02-02)
-------------------------
- Improved styling of grouped products

Version 1.15 (2018-12-07)
-------------------------
- Fixed Gutenberg style changes required due to changes in the default block editor CSS and classes
- Fixed the Classic Block TinyMCE buttons being set to the wrong font

Version 1.14 (2018-11-30)
-------------------------
- Fixed Gutenberg editor styles font being overwritten

Version 1.13 (2018-11-11)
-------------------------
- Fixed a Customizer issue causing double output of the logo

Version 1.12 (2018-11-06)
-------------------------
- Fixed the calendar widget table rows having white background in the footer

Version 1.11 (2018-11-03)
-------------------------
- Updated with Gutenberg support
	- Gutenberg editor styles
	- Styling of Gutenberg blocks
	- Custom Sullivan Gutenberg palette
	- Custom Sullivan Gutenberg typography styles
- Added option to disable Google Fonts with a translateable string
- Updated theme description
- Fixed the footer social menu

Version 1.10 (2018-05-24)
-------------------------
- Improved styling of checkboxes in comment respond

Version 1.09 (2018-04-14)
-------------------------
- Removed old customizer control for adding slideshow slides
- Added demo link to the theme description

Version 1.08 (2018-04-08)
-------------------------
- Removed modifications of global $paged variable
- Removed partial refresh for blog_name and /_description, to keep 5.2 compatibility (by removing anonymous functions in the render_callback)
- Renamed ajax_search to be theme prefixed
- Added '/' to the home_url() arg
- Added missing wp_reset_postdata()
- Fixed escaping of placeholder value, get_search_query()
- Removed global variables in global.js
- Changed global.js function prefix from WP to sullivan
- Updated printf/sprintf calls with notes for translators detailing variable values
- Code cleanup based on Theme Sniffer WordPress-Extra results

Version 1.07 (2018-04-07)
-------------------------
- Version bump for the benefit of the WordPress theme uploader

Version 1.06 (2018-03-27)
-------------------------
- Updated licenses in readme
- Updated enqueue name for Flexslider
- Checked all files for unescaped variables

Version 1.05 (2018-03-26)
-------------------------
- PayPal Express checkout styling
- Bumbed version numbers in included WooCommerce files
- Added styling to links in entry-content
- Site nav current menu item styling
- Fixed capitalization of "Results x-xx of xx" in shop archive on mobile

Version 1.04 (2018-03-25)
-------------------------
- Added extra check before calling is_plugin_active on theme activation

Version 1.03 (2018-03-25)
-------------------------
- Added back customizer functionality removed in overzealous cleanup yesterday
- Removed more stuff related to the old slideshow solution in the javascript customizer files

Version 1.02 (2018-03-24)
-------------------------
- Added missing overflow: auto on mobile menu
- Fixed incorrect theme_location check in mobile menu
- Updated output of slideshows to use the new sullivan compat slideshow post type
- Show notice instructing the user to install the sullivan compat plugin on theme activation
- Updated page.php with conditionals to prevent it breaking when WC is not activated
- Replaced esc_attr with wp_kses_post in some cases
- Escaped text in footer, made theme credit fully translateable
- Added flexslider to readme licenses, change to non-minified version
- Updated Google Fonts font enqueuing method to match TwentySeventeen
- Added URLs to specific images used in screenshot.png
- Updated theme description and the readme with details about the compatibility plugin.

Version 1.01 (2018-01-22)
-------------------------
- Fixed incorrect filename for editor styles in functions.php

Version 1.00 (2018-01-20)
-------------------------