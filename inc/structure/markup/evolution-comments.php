<?php
/**
* The Evolution Framework
*
* WARNING: This file is part of the core Evolution Framework. DO NOT edit this file under any circumstances.
* Please do all modifications in the form of a child theme.
*
* This file handles the comments actions
* 
* @see comments.php
*
* @package Evolution Framework\Markup\
* @author  Andreas Hecht
* @license GPL-2.0+
* @link    https://andreas-hecht.com/wordpress-themes/evolution-wordpress-framework/
*/

add_action( 'evolution_do_comments', 'evolution_comments_top_markup', 5 );
add_action( 'evolution_do_comments', 'evolution_have_comments', 10 );
add_action( 'evolution_do_comments', 'evolution_comments_closed', 30 );
add_action( 'evolution_do_comments', 'evolution_comment_form', 40 );
add_action( 'evolution_do_comments', 'evolution_comments_bottom_markup', 80 );

add_action( 'evolution_comment_nav', 'evolution_comment_navigation', 5 );
add_action( 'evolution_comment_nav', 'evolution_comment_list', 10 );
add_action( 'evolution_comment_nav', 'evolution_comment_nav_markup', 15 );


if ( ! function_exists( 'evolution_comments_top_markup' ) ) :
/**
 * Outputs the opening markup for the comments section
 * 
 * @hooked into evolution_comments() action
 * 
 * @since 1.0.0
 */
function evolution_comments_top_markup() {

    echo '<div id="comments" class="comments-area"> ';
}
endif;




if ( ! function_exists( 'evolution_have_comments' ) ) :
/**
 * Executes the have comments checkup
 * 
 * @hooked evolution_comment_navigation() - 5
 * @hooked evolution_comment_list() - 10
 * @hooked evolution_comment_nav_markup() - 15
 *
 * @since  1.0.0
 * @return  void
 */
function evolution_have_comments() {
    
if ( have_comments() ) : // Do we have comments?

    echo '<h4 class="comments-title">';

    comments_number( '', esc_html__( '1 Comment', 'evolution' ), esc_html__( '% Comments', 'evolution' ) );
 
    echo '</h4>';
/**
 * Functions hooked into evolution_comment_nav() action
 *
 * @hooked  evolution_comment_navigation() - 5
 * @hooked  evolution_comment_list() - 10
 * @hooked  evolution_comment_nav_markup() - 15
 */   
do_action( 'evolution_comment_nav' );

endif; // Check for have_comments(). 
}
endif;
    
    




if ( ! function_exists( 'evolution_comment_navigation' ) ) :
/**
 * Outputs the comment navigation
 * 
 * @hooked into evolution_comment_nav() action
 * 
 * @since 1.0.0
 */
function evolution_comment_navigation() {
    
if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through?

    echo '<nav id="comment-nav-above" class="comment-navigation">', "\n", '<h3 class="screen-reader-text">';
    
    esc_html_e( 'Comment navigation', 'evolution' );
    
    echo '</h3>', "\n", '<div class="nav-links">', "\n", '<div class="nav-previous">';
    
    previous_comments_link( esc_html__( '&larr; Older Comments', 'evolution' ) );
        
    echo '</div>', "\n", '<div class="nav-next">';
            
    next_comments_link( esc_html__( 'Newer Comments &rarr;', 'evolution' ) );
    
    echo '</div>', "\n", '</div><!-- .nav-links -->', "\n", '</nav><!-- #comment-nav-above -->';

endif; // Check for comment navigation. 
}
endif;






if ( ! function_exists( 'evolution_comment_list' ) ) :
/**
 * Outputs the comment list
 * 
 * @hooked into evolution_comment_nav() action
 * 
 * @since 1.0.0
 */
function evolution_comment_list() {
   
    echo '<ol class="comment-list">';

        wp_list_comments( array(
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 100,
        ) );

echo '</ol><!-- .comment-list -->';
}
endif;






if ( ! function_exists( 'evolution_comment_nav_markup' ) ) :
/**
 * Outputs the comment navigation - older/newer comments
 * 
 * @hooked into evolution_comment_nav() action
 * 
 * @since 1.0.0
 */
function evolution_comment_nav_markup() {
    
if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through?

    echo '<nav id="comment-nav-below" class="comment-navigation">', "\n", '<h3 class="screen-reader-text">';
    
        esc_html_e( 'Comment navigation', 'evolution' );
    
    echo '</h3>', "\n", '<div class="nav-links">', "\n", '<div class="nav-previous">',
    
        previous_comments_link( esc_html__( '&larr; Older Comments', 'evolution' ) );
        
    echo '</div><div class="nav-next">';
        
        next_comments_link( esc_html__( 'Newer Comments &rarr;', 'evolution' ) );
    
   echo '</div>', "\n", '</div><!-- .nav-links -->', "\n", '</nav><!-- #comment-nav-below -->';

endif; // Check for comment navigation.
}
endif;






if ( ! function_exists( 'evolution_comments_closed' ) ) :
/**
 * Outputs a message, if comments are closed
 * 
 * @hooked into evolution_comments() action
 * 
 * @since 1.0.0
 */
function evolution_comments_closed() {

    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :

    echo '<p class="no-comments">';
    
   esc_html_e( 'Sorry, Comments are closed.', 'evolution' );
    
    echo '</p>'; 
    
 endif;
}
endif;






if ( ! function_exists( 'evolution_comment_form' ) ) :
/**
 * Outputs the custom comment form
 * 
 * @hooked into evolution_comments() action
 * 
 * @since 1.0.0
 */
function evolution_comment_form() {
    
    global $post;

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    comment_form(
        array(
            'comment_field' => '<textarea id="comment" class="plain buffer" name="comment" rows="7" placeholder="' . esc_attr( __( 'Please be kind. Thank You!', 'evolution' ) ) . '" aria-required="true"></textarea>',
            'fields' => array(
                'author' => '<div class="form-areas"><input id="author" class="author" name="author" type="text" placeholder="' . esc_attr( __( 'Your Name', 'evolution' ) ) . '" value="' . esc_attr( $commenter[ 'comment_author' ] ) . '" ' . $aria_req . '>',
                'email' => '<input id="email" class="email" name="email" type="text" placeholder="' . esc_attr( __( 'your@email.com', 'evolution' ) ) . '" value="' . esc_attr( $commenter[ 'comment_author_email' ] ) . '" ' . $aria_req . '>',
                'url' => '<input id="url" class="url" name="url" type="text" placeholder="' . esc_attr( __( 'Your Website', 'evolution' ) ) . '" value="' . esc_url( $commenter[ 'comment_author_url' ] ) . '"></div>'
            )
        ),
        $post->ID
    );
}
endif;






if ( ! function_exists( 'evolution_comments_bottom_markup' ) ) :
/**
 * Outputs the closing markup of the comments section
 * 
 * @hooked into evolution_comments() action
 * 
 * @since 1.0.0
 */
function evolution_comments_bottom_markup() {

    echo '</div><!-- #comments --> ';
}
endif;