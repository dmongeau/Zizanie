<?php
/*
Plugin Name: Zizanie
Plugin URI: http://zizanie.ca
Description: Comments plugin
Version: 0.1
Author: Name Of The Plugin Author
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
*/

/**************************
*
* Constants
*
***************************/
define("ZIZ_DEBUG",true);
define("ZIZ_PATH",dirname(__FILE__).'/');
define("TEMPLATES_PATH",ZIZ_PATH.'templates/');
define("IMAGES_PATH",ZIZ_PATH.'images/');
define("LANGUAGES_PATH",ZIZ_PATH.'languages/');

//Turn on errors for debugging
if(ZIZ_DEBUG) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

/**************************
*
* Includes
*
***************************/
require ZIZ_PATH.'includes/functions.php';



/**************************
*
* Plugin init
*
***************************/
add_action('wp', 'ziz_init');
function ziz_init() {
	
	//Load languages directory
	load_plugin_textdomain('zizanie',LANGUAGES_PATH);
	
	//Add Javascript files
	wp_enqueue_script('jquery');
	wp_enqueue_script('jqueryui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js');
	wp_enqueue_script('app.js', '/wp-content/plugins/zizanie/js/app.js', array('jquery'), '1.0');
	wp_enqueue_script('ckeditor', '/wp-content/plugins/zizanie/ckeditor/ckeditor.js');
	wp_enqueue_script('ckeditor_jquery', '/wp-content/plugins/zizanie/ckeditor/adapters/jquery.js',array('jquery'));
	
	//Add CSS files
	wp_enqueue_style('styles.css', '/wp-content/plugins/zizanie/css/styles.css');
	wp_enqueue_style('jqueryui.css', '/wp-content/plugins/zizanie/css/jqueryui.css');
	
}


/**************************
*
* Comments system
*
***************************/

add_filter ('comment_form_defaults', 'ziz_comment_form_defaults');
function ziz_comment_form_defaults($defaults) {
	
	$defaults['fields']['rage'] = 	'<p class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label>' .
		            				'<div class="slider"></div></p>';
	$defaults['comment_field'] = '<div class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><div class="zizanie-comment-editor"></div></div>';
	
	return $defaults;
	
}


add_action ('comment_post', 'ziz_comment_post', 1);
function ziz_comment_post($comment_id) {
	
	$zizanie = array();
	add_comment_meta($comment_id, 'zizanie', serialize($zizanie), true);
	
}



add_filter('comments_template', 'ziz_comments_template');
function ziz_comments_template($value) {
	global $post;
	global $comments;

	if ( !( is_singular() && ( have_comments() || 'open' == $post->comment_status ) ) ) {
		return;
	}
	
	return TEMPLATES_PATH . '/comments.php';
	
}
 
 
 function get_comment_zizanie($comment_ID) {
	$zizanie = get_comment_meta($comment_ID,"zizanie");
	if(!isset($zizanie) || empty($zizanie) || (isset($zizanie[0]) && empty($zizanie[0]))) return array();
	else return unserialize($zizanie[0]);
 }

	
function ziz_comment( $comment, $args, $depth ) {
	
	$GLOBALS['comment'] = $comment;
		
    $zizanie = get_comment_zizanie(get_comment_ID());
	
	switch ( $comment->comment_type ) {
		
		case 'pingback'  :
		case 'trackback' :
			?><li class="post pingback">
				<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'twentyten'), ' ' ); ?></p>
			<?php
		break;
		
		default:
		
			include TEMPLATES_PATH.'comment.php';
		break;
		
	}
}


/**************************
*
* Administration Panel
*
***************************/

// create custom plugin settings menu
add_action('admin_menu', 'ziz_create_admin_menu');
function ziz_create_admin_menu() {

	//create new top-level menu
	add_menu_page('Zizanie settings page', 'Zizanie', 'administrator', __FILE__, 'ziz_settings_page',plugins_url('/images/bubble_16.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'ziz_register_settings' );
}



function ziz_register_settings() {
	//register our settings
	register_setting( 'ziz-settings-group', 'new_option_name' );
	register_setting( 'ziz-settings-group', 'some_other_option' );
	register_setting( 'ziz-settings-group', 'option_etc' );
}

function ziz_settings_page() {
	
	add_meta_box("zizanie_settings", __('Settings', 'zizanie'), "ziz_settings_box", "zizanie");
	?>
	<div class="wrap">
    <div id="icon-edit-comments" class="icon32"><br /></div> 
	<h2><?=__('Zizanie settings','zizanie')?></h2>
    <form method="post" action="options.php">
        <div id="poststuff" class="metabox-holder has-right-sidebar">
            
            <?php settings_fields( 'ziz-settings-group' ); ?>
            <?php do_meta_boxes('zizanie','advanced',null); ?>
            
            <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </div>
    </form>
	</div>
	<?php
	
	
	
 }
 
 function ziz_settings_box() {
	 
	 ?><table class="form-table">
        <tr valign="top">
        <th scope="row">New Option Name</th>
        <td><input type="text" name="new_option_name" value="<?php echo get_option('new_option_name'); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Some Other Option</th>
        <td><input type="text" name="some_other_option" value="<?php echo get_option('some_other_option'); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Options, Etc.</th>
        <td><input type="text" name="option_etc" value="<?php echo get_option('option_etc'); ?>" /></td>
        </tr>
    </table><?php
	
 }
 
 
//Create box with extra fields in the edit comment page
add_action('add_meta_boxes_comment', 'ziz_add_meta_boxes_comment');
function ziz_add_meta_boxes_comment($comment) {
	
	add_meta_box("zizanie_comment_meta", __('Zizanie fields', 'zizanie'), "ziz_comment_meta_box", "zizanie");
	do_meta_boxes('zizanie','advanced',$comment);
	
}

//Render html in extra fields box
function ziz_comment_meta_box($comment) {
	echo print_r(get_comment_meta($comment->comment_ID,"ziz_test"),true);
}
 
