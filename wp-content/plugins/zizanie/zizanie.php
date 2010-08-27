<?php
/*
Plugin Name: Zizanie
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Comments plugin
Version: 0.1
Author: Name Of The Plugin Author
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
*/

ini_set('display_errors', 1);
error_reporting(E_ALL);

define("TEMPLATES_PATH",dirname(__FILE__).'/templates/');


function ziz_comments_template($value) {
	global $post;
	global $comments;

	if ( !( is_singular() && ( have_comments() || 'open' == $post->comment_status ) ) ) {
		return;
	}

	// TODO: If a disqus-comments.php is found in the current template's
	// path, use that instead of the default bundled comments.php
	//return TEMPLATEPATH . '/disqus-comments.php';
	return TEMPLATES_PATH . '/comments.php';
}

add_filter('comments_template', 'ziz_comments_template');


// create custom plugin settings menu
add_action('admin_menu', 'ziz_create_admin_menu');

function ziz_create_admin_menu() {

	//create new top-level menu
	add_menu_page('Zizanie settings page', 'Zizanie', 'administrator', __FILE__, 'ziz_settings_page',plugins_url('/images/icon.png', __FILE__));

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
?>
<div class="wrap">
<h2>Your Plugin Name</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'baw-settings-group' ); ?>
    <table class="form-table">
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
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php
 }

?>