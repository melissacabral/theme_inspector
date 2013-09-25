<?php 
/*
Plugin Name: Theme Helper
Plugin URI: http://wordpress.melissacabral.com/
Description:  displays useful technical information on pages and posts to aid in developing Wordpress themes. Use In Conjunction with the WP Template Hierarchy Document
Author: Melissa Cabral
Version: 2.0
Author URI: http://melissacabral.com/
*/

/**
 * Display Theme Helper as Toolbar (Admin Bar) Item
 * @since ver 2.0
 */
add_action( 'admin_bar_menu', 'mmc_toolbar_link', 999 );
function mmc_toolbar_link( $wp_admin_bar ) {
	if(!is_admin()){
		$html = mmc_generate_output();

		$args = array(
			'id'    => 'theme-helper',
			'title' => 'Theme Helper',
			'parent' => 'top-secondary',		
			'meta'  => array( 
				'class' => 'theme-helper',
				'html' => $html,
				),
			);
		$wp_admin_bar->add_node( $args );
	}
}
/**
 * function to generate output HTML
 * @since 1.0
 */
function mmc_generate_output(){		
	//make sure user is logged in
	if (is_user_logged_in()) {
		global $post;
		//begin Table Output
		ob_start();
		?>
			<div id="theme-helper-toolbar">
			<table>
				<tr>
					<th>Content Type:</th>
					<td><?php echo adminhelper_content_type()?></td>
				</tr>
				<?php if(is_singular()){ ?>
				<tr>
					<th>Post ID:</th>
					<td><?php echo adminhelper_post_id()?></td>
				</tr>
				<?php }	?>
				<tr>
					<th>True Condition(s):</th>
					<td><?php echo adminhelper_true_conditions()?></td>
				</tr>
				<?php if( !is_404() && ! is_search() ){ ?>
				<tr>
					<th>Post Type:</th>
					<td><?php if ( get_post_type() ) { echo  get_post_type(); } ?></td>
				</tr>
				<?php } ?>
				<?php 
				if( is_category() ){ ?>
				<tr>
					<th>Taxonomy:</th>
					<td><?php 
					echo 'category'; 
					echo ' > ';
					single_cat_title(); ?>
					</td>
				</tr>
				<?php }				
				elseif( is_tax() ){?>
				<tr>
					<th>Taxonomy:</th>
					<td><?php 
					echo get_query_var( 'taxonomy' ); 
					echo ' > ';
					single_cat_title(); ?>
					</td>
				</tr>
				<?php }				
				elseif( is_tag() ){?>
				<tr>
					<th>Taxonomy:</th>
					<td><?php 
					echo 'tag'; 
					echo ' > ';
					single_cat_title(); ?>
					</td>
				</tr>
				<?php } //end if taxonomy/category ?>
				<?php
				if (isset($post->ID) && is_page() && get_post_meta($post->ID,'_wp_page_template',true) != 'default') {?>
				<tr>
					<th>Custom Template:</th>
					<td><?php echo get_post_meta($post->ID,'_wp_page_template',true) ?></td>
				</tr>
				<tr>
					<th>Order:</th>
					<td><?php echo $post->menu_order ?></td>
				</tr>
				<?php 
				}	?>
				<tr class="file-loaded">
					<th>File Loaded:</th>
					<td><?php echo adminhelper_get_current_template() ?></td>
				</tr>
				<tr class="credits">
					<td colspan="2">Theme Helper by <a href="https://github.com/melissacabral/theme_helper">Melissa Cabral.</td>
				</tr>
				<tr class="credits usewith">
					<td colspan="2">Use with <a href="http://wptutsplus.s3.amazonaws.com/090_WPCheatSheets/WP_CheatSheet_TemplateMap.jpg">Hierarchy Diagram</a></td>
				</tr>
			</table>
		</div><!-- End theme Helper-->
	<?php 	
	return ob_get_clean();			
	} //end is user logged in
}

/**
 * Display the theme helper at the bottom of the page
 * @since 1.0
 */
//Added to Fetch the template file being used
add_filter( 'template_include', 'var_template_include', 1000 );
function var_template_include( $t ){
	$GLOBALS['current_theme_template'] = basename($t);
	return $t;
}

/**
 * helper functions
 */
function adminhelper_content_type(){
	global $post;
	$output = '';
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
	return $output;
}

function adminhelper_post_id(){
	global $post;
	if( isset($post) ){
		$post_id = $post->ID;
	}else{
		$post_id = 'no post defined';
	}
	return $post_id;
}
function adminhelper_true_conditions(){
	global $post;
	$conditions = array();	
	$output = '';
	$count = 0;
	if (is_front_page()) { $conditions[] = "is_front_page()"; }
	if (is_home()) { $conditions[] = "is_home()"; }
	if (is_attachment() ){ $conditions[] = "is_attachment()"; }
	if (is_single()) { $conditions[] = "is_single()"; }
	if (is_page()) { $conditions[] = "is_page()"; }
	if (is_singular()) { $conditions[] = "is_singular() "; }
	if (is_category()) { $conditions[] = "is_category()"; }
	if (is_tag()) { $conditions[] = "is_tag()"; }
	if (is_tax()) { $conditions[] = "is_tax()"; }
	if (is_author()) { $conditions[] = "is_author()"; }
	if (is_post_type_archive()){ $conditions[] = "is_post_type_archive()"; }
	if (is_date()) { $conditions[] = "is_date()"; }
	if (is_year()) { $conditions[] = "is_year()"; }
	if (is_month()) { $conditions[] = " is_month()"; }
	if (is_day()) { $conditions[] = " is_day()"; }
	if (is_time()) { $conditions[] = " is_time()"; }	
	if (is_archive()) { $conditions[] = " is_archive() "; }	
	if (is_search()) { $conditions[] = "is_search() "; }
	if (is_404()) { $conditions[] = "is_404() "; }
	if (is_paged()) { $conditions[] = "is_paged() "; }

	foreach($conditions as $condition){
		if($count == 0)
			$output.= '<span class="first condition">'.$condition.'</span>';
		else
			$output.= '<span class="condition">, '.$condition.'</span>';	
		$count ++;
	}	
	return $output;
}


function adminhelper_get_current_template(  ) {
	if( !isset( $GLOBALS['current_theme_template'] ) ){
		return false;
	}
	return $GLOBALS['current_theme_template'];
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

/**
 * Enqueue the stylesheet
 * @since ver 2.0
 */
add_action('wp_enqueue_scripts', 'adminhelper_enqueue_stylesheet');
function adminhelper_enqueue_stylesheet(){
	$src = plugins_url( 'theme-helper.css', __FILE__ );
	wp_register_style( 'themehelper-style', $src, '', '', 'screen' );
	wp_enqueue_style( 'themehelper-style');
}