<?php
/*
Plugin Name: rc-CSS
Plugin URI: http://www.rcarlier.fr/wordpress/rc_css.php
Description: Merge and optimize CSS
Version: 1.0
Author: Richard Carlier
Author URI: http://www.richardcarlier.com/
License: GPL2

*/

/*  Copyright 2012  Richard Carlier

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/




// Valeurs par défaut
add_option('rc_CSS_comments', '');

define ('rc_CSS_baseline', 'rc-CSS : Merge and optimize CSS');
define ('rc_CSS_copyright', 'http://www.richardcarlier.com/');


add_action('admin_menu', 'rc_CSS_init');
function rc_CSS_init () {
	add_theme_page('rc-CSS', rc_CSS_baseline, 'manage_options', 'rc_CSS_options', 'rc_CSS_options');
}

function rc_CSS_options () {
	if(isset($_POST['submit'])) :
		rc_CSS_valid() ;
	endif ;
	rc_CSS_formulaire() ;
}

function rc_CSS_valid () {
	$commentaire = $_POST["commentaire"] ;
	update_option('rc_CSS_comments', (string)$commentaire);
	?><div id="message" class="updated fade"><p><strong>Options sauvegardée</strong></p></div><?php 
}

function rc_CSS_formulaire () {
	$commentaire = get_option('rc_CSS_comments');

?><div class="wrap">

<form action="" method="post">
<div id="icon-options-general" class="icon32"><br /></div>
<h2><?php echo rc_CSS_baseline ;?></h2>
	<h3><label for="commentaire">Comments</label></h3>
	<p>
	/*<br />
	<textarea style="width:100%;height:200px;" name="commentaire" id="commentaire"><?php print $commentaire ;?></textarea><br />
	*/</p>
	<p class="submit">
	<input type="submit" name="submit" id="submit" class="button-primary" value="Update"  />
	</p>
	<pre>For example: <em>Theme Name: ...</em></pre>
</form>





		<h3>Installation</h3>
	<p>Upload <code>rc_css/</code> directory to the <code>/wp-content/plugins/</code> directory</p>

<p>Activate the plugin through the <code>Plugins</code> menu in WordPress</p>

<p>Place all your CSS in your theme directory (as usual) including style.css with Wordpress comments.</p>

<p>Generaly:</p>

<pre style="background-color:#EAEAEA">wp-content/themes/YOURTHEME/style.css
wp-content/themes/YOURTHEME/reset.css
wp-content/themes/YOURTHEME/print.css
wp-content/themes/YOURTHEME/mobile.css
wp-content/themes/YOURTHEME/imgs/background.png</pre>

<p>Create an empty file called rc_css_style.css in this directory (or copy the one included),
and put 0666 permission (PHP need to rewrite this file).</p>

<p>In your <code>header.php</code>, replace (for example):</p>

<pre style="background-color:#EAEAEA">&lt;link rel=&quot;stylesheet&quot; href=&quot;&lt;?php print get_bloginfo(&#039;template_directory&#039;); ?&gt;/reset.css&quot; type=&quot;text/css&quot; /&gt;
&lt;link rel=&quot;stylesheet&quot; href=&quot;&lt;?php print get_bloginfo(&#039;template_directory&#039;); ?&gt;/print.css&quot; type=&quot;text/css&quot; media=&quot;print&quot; /&gt;
&lt;link rel=&quot;stylesheet&quot; href=&quot;&lt;?php print get_bloginfo(&#039;template_directory&#039;); ?&gt;/mobile.css&quot; type=&quot;text/css&quot; media=&quot;screen and (max-width: 400px)&quot; /&gt;
&lt;link rel=&quot;stylesheet&quot; href=&quot;&lt;?php print get_bloginfo(&#039;template_directory&#039;); ?&gt;/style.css&quot; type=&quot;text/css&quot; media=&quot;all&quot; /&gt;</pre>

<p>by:</p>

<pre style="background-color:#EAEAEA">&lt;?php
    rc_CSS_enqueue_style( &quot;/reset.css&quot; );
    rc_CSS_enqueue_style( &quot;/print.css&quot; , &quot;print&quot;);
    rc_CSS_enqueue_style( &quot;/mobile.css&quot; , &quot;screen and (max-width: 400px)&quot;);
    rc_CSS_enqueue_style( &quot;/style.css&quot; );
    rc_CSS_echo_style( true );
?&gt;</pre>

<p>Order is important (for your CSS, as usual...). The function <code>rc_css_echo_style()</code> should be at the end.</p>

<p>Optionnaly, you can go to "Admin" &gt; "Appareance" &gt; "rc_CSS - Merge and optimize CSS" 
to specify comments that will be added at the top of css.</p>

<h4>Codex</h4>

<pre style="background-color:#EAEAEA"><strong>rc_css_enqueue_style( $css_filename , $css_media );</strong>
    $css_filename(string) : css filename, located in wp-content/themes/YOURTHEME/
    $css_media(string)(optional) : media query ( see <a href="http://www.w3.org/TR/css3-mediaqueries/" rel="nofollow">http://www.w3.org/TR/css3-mediaqueries/</a> ) or all by default

<strong>rc_css_echo_style( $generate )</strong>;
    $generate (boolean)(optional) : create file (1/True) or use existing file (0/False)
        - during development =&gt; true
        - for exploitation =&gt; false (or nothing)
        - if true, it&#039;s create a file
            wp-content/themes/YOURTHEME/rc_css_style.css</pre>
		  


</div>
<?php
}













$rc_CSS_src = array();
$media = array();

function rc_CSS_enqueue_style( $src , $media = "" ) {
	global $rc_CSS_src,  $rc_CSS_media;
	$rc_CSS_src[] = $src ;
	$rc_CSS_media[] = $media ;
	
}

function rc_CSS_echo_style( $generate = false ) {
	global $rc_CSS_src,  $rc_CSS_media;
	$rc_CSS_styles = '';
	$compilatedCSS = "/rc_css_style.css";

	if ($generate) {
	
		for ($i = 0; $i < sizeof( $rc_CSS_src ); $i++) {
			$src = $rc_CSS_src[$i];
			$media = $rc_CSS_media[$i];
			$source = get_theme_root().'/'. get_template() . $src ;
			$css = rc_CSS_clean_style( file_get_contents( $source ) ) ;
			
			if ($media <> "") { $rc_CSS_styles .= "@media ". $media ." { \n"; }
			$rc_CSS_styles .= $css ;
			if ($media <> "") { $rc_CSS_styles .= "} \n"; }
			
		}
	
		$rc_CSS_styles = "/* ". rc_CSS_baseline .' - by '. rc_CSS_copyright." */\n". $rc_CSS_styles;
	
	
		$commentaire = get_option('rc_CSS_comments');
		if ($commentaire != '') {
			$rc_CSS_styles = "/*\n". $commentaire ."\n*/\n". $rc_CSS_styles;
		}
	
	
		$filename = get_theme_root().'/'. get_template() . $compilatedCSS ;
		$filename = str_replace ('\\', '/', $filename );
		if ( file_exists($filename) and !is_writable($filename) ) {
			if (!chmod($filename, 0666)) {
				echo "FATAL ERROR: Cannot change the mode of file <code>$filename</code>";
				exit;
			};
		}
		if (is_writable($filename)) {
			if (!$handle = fopen($filename, 'w')) {
				echo "FATAL ERROR: Cannot open file <code>$filename</code>";
				exit;
			}
			if (fwrite($handle, $rc_CSS_styles) === FALSE) {
				echo "FATAL ERROR: Cannot write to file  <code>$filename</code>";
				exit;
			}
			fclose($handle);
		} else {
			echo "FATAL ERROR: The file <code>$filename</code> is not writable";
			exit;
		}
	}
	
	$destination = get_template_directory_uri() . $compilatedCSS ;
	echo "\t". '<link rel="stylesheet" href="'. $destination .'" type="text/css" />' ."\n";	
}

function rc_CSS_clean_style($css) {
	$spaces = array("\t", "\n", "\r");
	$css = str_replace($spaces, "", $css);
	$css = preg_replace('/\s\s+/', ' ', $css);
	$css = preg_replace('/(\/\*[\s\S]*?\*\/)/', '', $css) ;

	$nospaces = array('}', '{', ':', ';', ',');
	foreach($nospaces as $ns) {
		$css = str_replace(" ".$ns, $ns, $css);
		$css = str_replace($ns." ", $ns, $css);
	}

	$css = str_replace("}", "}\n", $css);
	$css = str_replace("\n ", "\n", $css);
	return $css;
}

