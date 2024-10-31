=== rc-CSS ===
Contributors: richnou
Tags: css, merge stylesheet, optimize stylesheet, media query, responsive
Requires at least: 3.4
Tested up to: 3.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: http://afsep.fr/faire-don
Stable tag: 1.0

Your several stylesheets will be merged into a single compressed file, to optimize loading times for your users.


== Description ==

Rather than having several calls to several CSS, it is better for your visitors
to have only one stylesheet. This speeds up load times, allows a more efficient cache management.

But for developing the CSS, it is often preferable to manage multiple
specific files, specialized (one for reset, one for print version, another for small screens, etc..)

The purpose of this plugin is to allow you to work with various files, but merge them into 
one single file when the browser need it.

It can be generate each time (during development) or use a cache-file when finished.

It will optimize the CSS by removing all unnecessary characters that increase the 
CSS (tabs, spaces, line breaks ...).

All comments will be deleted - but you can insert a few lines  at the top, to specify such a 
copyright ...

== Installation ==

Upload `rc_css/` directory to the `/wp-content/plugins/` directory

Activate the plugin through the `Plugins` menu in WordPress

Place all your CSS in your theme directory (as usual) including style.css with Wordpress comments.

Generaly:
`wp-content/themes/YOURTHEME/style.css
wp-content/themes/YOURTHEME/reset.css
wp-content/themes/YOURTHEME/print.css
wp-content/themes/YOURTHEME/mobile.css
wp-content/themes/YOURTHEME/imgs/background.png`

Create an empty file called rc_css_style.css in this directory (or copy the one included),
and put 0666 permission (PHP need to rewrite this file).

In your `header.php`, replace (for example):
`<link rel="stylesheet" href="<?php print get_bloginfo('template_directory'); ?>/reset.css" type="text/css" />
<link rel="stylesheet" href="<?php print get_bloginfo('template_directory'); ?>/print.css" type="text/css" media="print" />
<link rel="stylesheet" href="<?php print get_bloginfo('template_directory'); ?>/mobile.css" type="text/css" media="screen and (max-width: 400px)" />
<link rel="stylesheet" href="<?php print get_bloginfo('template_directory'); ?>/style.css" type="text/css" media="all" />`

by:
`<?php
	rc_CSS_enqueue_style( "/reset.css" );
	rc_CSS_enqueue_style( "/print.css" , "print");
	rc_CSS_enqueue_style( "/mobile.css" , "screen and (max-width: 400px)");
	rc_CSS_enqueue_style( "/style.css" );
	rc_CSS_echo_style( true );
?>`

Order is important (for your CSS, as usual...). The function `rc_css_echo_style()` should be at the end.

Optionnaly, you can go to "Admin" > "Appareance" > "rc_CSS - Merge and optimize CSS" 
to specify comments that will be added at the top of css.


= Codex =

`
rc_css_enqueue_style( $css_filename , $css_media );
	$css_filename(string) : css filename, located in wp-content/themes/YOURTHEME/
	$css_media(string)(optional) : media query ( see http://www.w3.org/TR/css3-mediaqueries/ ) or all by default

rc_css_echo_style( $generate );
	$generate (boolean)(optional) : create file (1/True) or use existing file (0/False)
		- during development => true
		- for exploitation => false (or nothing)
		- if true, it's create a file
			wp-content/themes/YOURTHEME/rc_css_style.css
`


== Frequently Asked Questions ==

FATAL ERROR ?

* Verify than your rc_css_style.css is located in you theme directory.
* Verify the chmod of it (0666 - need to be writable).
	


== Screenshots ==

1. header.php, before/after using rc_css plugin
2. example (excerpt) of `rc_css generated.css` stylesheet (no indentation, no comment, @media...)
3. option screen...



== Other Notes ==

= Credits =

Author: [Richard Carlier](http://www.richardcarlier.com/)

Plugin URI: [rc_CSS](http://www.rcarlier.fr/wordpress/rc_css.php)

Thanks: Mathieu Bouillot (The idea of this plugin came after discussion with him ...)

Donate: Not for me, but for [French Multiple Sclerosis Society](http://afsep.fr/faire-don), or equivalent...



== Changelog ==

1.0 - This is version 1...


== Upgrade Notice ==

No upgrade yet.