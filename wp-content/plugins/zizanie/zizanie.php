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
 
/*
* Template for comments and pingbacks.
*
* To override this walker in a child theme without modifying the comments template
* simply create your own zizanie_comment(), and that function will be used instead.
*
* Used as a callback by wp_list_comments() for displaying the comments.
*
* @since Zizanie 0.1
*/
if(!function_exists('zizanie_comment')) {
	
	function zizanie_comment( $comment, $args, $depth ) {
		
		$GLOBALS['comment'] = $comment;
		
		switch ( $comment->comment_type ) {
			
			case '' :
				?><li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<div id="comment-<?php comment_ID(); ?>">
                        <div class="comment-author vcard">
                            <?php echo get_avatar( $comment, 40 ); ?>
                            <?php printf( __( '%s <span class="says">says:</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
                        </div><!-- .comment-author .vcard -->
                        <?php if ( $comment->comment_approved == '0' ) { ?>
                            <em><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em>
                            <br />
                        <?php } ?>
        
                        <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                            <?php
                                /* translators: 1: date, 2: time */
                                printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
                            ?>
                        </div><!-- .comment-meta .commentmetadata -->
                
                        <div class="comment-body"><?php comment_text(); ?></div>
                
                        <div class="reply">
                            <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                        </div><!-- .reply -->
                    </div><!-- #comment-##  --><?php
			break;
			
			case 'pingback'  :
			case 'trackback' :
				?><li class="post pingback">
					<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'twentyten'), ' ' ); ?></p>
				<?php
			break;
			
		}
	}
	
}
 
 

?>