<?php
/**
 * The template for displaying Comments.
 *
 * @package Zizanie
 * @since Zizanie 0.1
 */
?><div id="comments" class="ziz-comments"><?php

	if(post_password_required()) {
	
		?><p class="nopassword"><?php
		
			_e( 'This post is password protected. Enter the password to view any comments.', 'zizanie' );
			
		?></p></div><!-- #comments --><?php
		
		return;
	}
	
	if(have_comments()) {
		
		?><h3 id="comments-title"><?php
		
			printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'zizanie' ),
			number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
			
		?></h3><?php
		
        
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // Are there comments to navigate through?
		
			?><div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'zizanie' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'zizanie' ) ); ?></div>
			</div><!-- .navigation --><?php
            
		} // check for comment navigation
		
		
		?><ol class="commentlist"><?php

			/******************
			* Display comment
			*******************/
			wp_list_comments( array( 'callback' => 'ziz_comment' ) );
			
		?></ol><?php
		
        
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // Are there comments to navigate through?
		
			?><div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'zizanie' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'zizanie' ) ); ?></div>
			</div><!-- .navigation --><?php
            
		} // check for comment navigation 
		
		
	} else { // or, if we don't have comments:

		/* If there are no comments and comments are closed,
		 * let's leave a little note, shall we?
		 */
		if ( ! comments_open() ) {
		
			?><p class="nocomments"><?php
			
				_e( 'Comments are closed.', 'zizanie' );
				
			?></p><?php
		} // end ! comments_open()
	 
	} // end have_comments()
	
	 
	comment_form();
	
	  
?>
<p id="zizanie-credits" align="right">
<?=__('Comments powered by Zizanie','zizanie')?>
</p>

</div><!-- #comments -->