<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
<div id="comment-<?php comment_ID(); ?>">
    <div class="comment-author vcard">
        <?php echo get_avatar( $comment, 40 ); ?>
        <?php printf( __( '%s <span class="says">says:</span>', 'zizanie' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
    </div><!-- .comment-author .vcard -->
    <?php if ( $comment->comment_approved == '0' ) { ?>
        <em><?php _e( 'Your comment is awaiting moderation.', 'zizanie' ); ?></em>
        <br />
    <?php } ?>

    <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
        <?php
            /* translators: 1: date, 2: time */
            printf( __( '%1$s at %2$s', 'zizanie' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'zizanie' ), ' ' );
        ?>
    </div><!-- .comment-meta .commentmetadata -->

    <div class="comment-body"><?php comment_text(); ?></div>

    <div class="reply">
        <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?> - 
        <?php comment_quotereply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?> - 
        <a href=""><?=__('Report as inappropriate','zizanie')?></a>
    </div><!-- .reply -->
</div><!-- #comment-##  -->