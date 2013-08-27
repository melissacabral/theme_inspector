<?php 
/*
Plugin Name: Theme Helper
Plugin URI: http://wordpress.melissacabral.com/
Description:  displays useful technical information on pages and posts to aid in developing Wordpress themes. Use In Conjunction with the WP Template Hierarchy Document
Author: Melissa Cabral
Version: 1.0
Author URI: http://melissacabral.com/
*/

add_action('wp_head', 'rad_help_head');
add_action('wp_footer', 'rad_help_footer');

//Added to Fetch the template file being used
add_filter( 'template_include', 'var_template_include', 1000 );
function var_template_include( $t ){
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
}

function get_current_template( $echo = false ) {
    if( !isset( $GLOBALS['current_theme_template'] ) )
        return false;
    if( $echo )
        echo $GLOBALS['current_theme_template'];
    else
        return $GLOBALS['current_theme_template'];
}

//stuff to do in the <head>
function rad_help_head() {
	
	if (is_user_logged_in()) {?>
<style type="text/css">
#adminwidget {
	font-family:"Trebuchet MS";
	color:black !important;
	background:#fff;
	font-size:12px;
	bottom:10px;
	right:5px;
	position:fixed;
	border:1px dashed #ccc;
	margin:5px;
	height:1em;
	overflow:hidden;
	width:100px;
	filter:alpha(opacity=40);
	-moz-opacity:0.3;
	-khtml-opacity: 0.3;
	opacity: 0.3;
	border-radius:10px;
	text-align:left;
	min-height:30px;
	min-width:150px;
	z-index: 9999999 !important;
}
#adminwidget:hover {
	height:auto;
	width:auto;
	filter:alpha(opacity=100);
	-moz-opacity:1;
	-khtml-opacity:1;
	opacity:1;
}
#adminwidget p {
	margin:0;
	line-height:1.2em;
	
}
#adminwidget > *{
	padding:6px;
}
#adminwidget p:first-child {
	color:#00c;
	margin:0 0 .6em 0;
	text-align:center;
	background-color:#EEE;
	padding:6px;
	font-size:16px;
}
#adminwidget a {
	color:#0085d5;
}
</style>
<?php
	}else{
		?>
<style type="text/css">
#adminwidget_login {
	display:none;
}
</style>
<?php 
	}
}

function adminhelper_currenturl() {
	$pageURL = 'http';
	if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}


function rad_help_footer() {
	if (is_user_logged_in()) {
		global $post;
		if( isset($post) ){
			$post_id = $post->ID;
		}else{
			$post_id = 'no post defined';
		}
		
		$output = "";
		$output .= '
<div id="adminwidget">
	<p class="center">Theme Helper</p>
	<ul>
	<li>ID: <strong>'.$post_id.'</strong></li>
	<li>Content Type: <strong>';
		
		if (is_front_page()) { $output .= "Front Page"; }
		if (is_home()) { $output .= "Home (blog)"; }
		if (is_single()) { $output .= "Single Post "; }
		if (is_page() && !is_front_page()) { $output .= "Page "; }
		if (is_category()) { $output .= "Category "; }
		if (is_tag()) { $output .= "Tag "; }
		if (is_tax()) { $output .= "Taxonomy "; }
		if (is_author()) { $output .= "Author "; }
		
		if (is_archive()) { $output .= "Archive "; }
		if (is_date()) { $output .= " - Date "; }
		if (is_year()) { $output .= " (year) "; }
		if (is_month()) { $output .= " (monthly) "; }
		if (is_day()) { $output .= " (daily) "; }
		if (is_time()) { $output .= " (time) "; }
		
		if (is_search()) { $output .= "Search "; }
		if (is_404()) { $output .= "404 "; }
		if (is_paged()) { $output .= " (Paged) "; }

	$output .= '</strong></li>';
		
		
	
	$output .= '<li>True Condition(s): <strong>';
		
		if (is_front_page()) { $output .= "is_front_page(), "; }
		if (is_home()) { $output .= "is_home()"; }
		if (is_single()) { $output .= "is_single(), "; }
		if (is_page()) { $output .= "is_page(), "; }
		if (is_singular()) { $output .= "is_singular() "; }
		if (is_category()) { $output .= "is_category(), "; }
		if (is_tag()) { $output .= "is_tag(), "; }
		if (is_tax()) { $output .= "is_tax(), "; }
		if (is_author()) { $output .= "is_author(), "; }
		if (is_post_type_archive()){ $output .= "is_post_type_archive(), "; }
		if (is_archive()) { $output .= "is_archive() "; }
		if (is_attachment() ){ $output .= "is_attachment() "; }
		if (is_date()) { $output .= ", is_date() "; }
		if (is_year()) { $output .= ", is_year() "; }
		if (is_month()) { $output .= ", is_month() "; }
		if (is_day()) { $output .= ", is_day() "; }
		if (is_time()) { $output .= ", is_time() "; }
		
		if (is_search()) { $output .= "is_search() "; }
		if (is_404()) { $output .= "is_404() "; }
		if (is_paged()) { $output .= ", is_paged() "; }
	$output .= '</strong></li>';
	$output .= '</strong><li>Post Type Slug: <strong>';
		
		if (get_post_type()) { $output .= get_post_type(); }
	$output .= '</strong></li>';
	
		
		
		if (isset($post->ID) && is_page() && get_post_meta($post->ID,'_wp_page_template',true)) {
				
				$output .= '<li>Template: <strong>'.get_post_meta($post->ID,'_wp_page_template',true).'</strong></li>
			<li>Order: <strong>'.$post->menu_order.'</strong></li>';
		}
		
		$output .= '<li style="color:#b65b02;font-weight:bold">File Loaded: <strong>'.get_current_template().'</strong></li>';
		$output .= '</ul><br />';
	
	
	$output .= '
		<p>
			<a href="'.admin_url().'" class="strong">Admin Panel</a> / 
			<a href="'.wp_logout_url(adminhelper_currenturl()).'">Logout</a>
		</p>
</div>
';

	}
	echo $output;
}



