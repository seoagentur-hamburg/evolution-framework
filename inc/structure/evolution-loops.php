<?php
 /**
* The Evolution Framework
*
* WARNING: This file is part of the core Evolution Framework. DO NOT edit this file under any circumstances.
* Please do all modifications in the form of a child theme.
*
* This file handles all loops
* @see /inc/structure/evolution-hooks.php
* @see /inc/structure/evolution-loop-content.php
*
* @package Evolution Framework\Structure\
* @author  Andreas Hecht
* @license GPL-2.0+
* @link    https://andreas-hecht.com/wordpress-themes/evolution
*/


if ( ! function_exists( 'evolution_top_markup' ) ) :
/**
 * Displays the markup before loops
 * 
 * @hooked evolution_do_main() - 5
 * @hooked evolution_do_page() - 5
 * @hooked evolution_do_single() - 5
 * @hooked evolution_do_search() - 5
 * @hooked evolution_do_404() - 5
 *
 * @since  1.0.0
 * @return  void
 */
function evolution_top_markup() {

echo '<div id="primary" class="content-area">', "\n", '<main id="main" class="site-main">';  
}
endif;




if ( ! function_exists( 'evolution_bottom_markup' ) ) :
/**
 * Displays the markup after loops
 * 
 * @hooked evolution_do_main() - 30
 * @hooked evolution_do_page() - 30
 * @hooked evolution_do_single() - 30
 * @hooked evolution_do_search() - 30
 * @hooked evolution_do_404() - 30
 *
 * @since  1.0.0
 * @return  void
 */
function evolution_bottom_markup() {

    echo '</main><!-- #main -->', "\n", '</div><!-- #primary -->';
}
endif;




if ( ! function_exists( 'evolution_do_page_loop' ) ) :
/**
 * Outputs the page loop
 * 
 * @hooked evolution_do_page()
 * 
 * @see /inc/structure/evolution-hooks.php
 * @see /inc/structure/evolution-loop-content.php
 * 
 * @see page.php
 *
 * @since  1.0.0
 * @return  void
 */
function evolution_do_page_loop() {

        do_action( 'evolution_before_page_loop' );

          while ( have_posts() ) : the_post();

                 // Load content part inside the loop
                    do_action( 'loop_page_content' );

            do_action( 'evolution_before_page_comments' );

        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
        comments_template();
        endif;

      endwhile; // End of the loop.
    
     do_action( 'evolution_after_page_loop' );
 }
endif;




if ( ! function_exists( 'evolution_do_main_loop' ) ) :
/**
 * Outputs the main loop
 * 
 * @hooked evolution_do_main()
 * 
 * @see /inc/structure/evolution-hooks.php
 * @see /inc/structure/evolution-loop-content.php
 * 
 * @see index.php
 *
 * @since  1.0.0
 * @return  void
 */
function evolution_do_main_loop() {

        do_action( 'evolution_before_main_loop' );

        if ( have_posts() ) : 

        /* Start the Loop */
        while ( have_posts() ) : the_post();
    
        echo '<div class="post-full post-full-summary">';
    
        printf( '<article class="%s">', implode( ' ', get_post_class() ) );
    
        if ( get_theme_mod( 'post_display_layout' ) == 'full' ) :

            // Load full content part inside the loop
                do_action( 'loop_full_content' );
    
            else :
    
            // Load excerpt content part inside the loop
                do_action( 'loop_summary_content' );
        
            endif;
    
        echo '</article><!-- #post-## -->', "\n", '</div><!-- .post-full -->'; 

        endwhile;

        do_action( 'evolution_after_main_loop' );

        the_posts_pagination( array(
            'prev_text' => esc_html__( '&laquo; Previous', 'evolution' ),
            'next_text' => esc_html__( 'Next &raquo;', 'evolution' ),
        ) );

        else :

        evolution_content_none();

        endif; }
endif;




if ( ! function_exists( 'evolution_do_single_loop' ) ) :
/**
 * Outputs the loop on single content
 * 
 * @hooked evolution_do_single()
 * 
 * @see /inc/structure/evolution-hooks.php
 * @see /inc/structure/evolution-loop-content.php
 * 
 * @see single.php
 *
 * @since  1.0.0
 * @return  void
 */
function evolution_do_single_loop() {
    
    do_action( 'evolution_before_single_loop' );

    while ( have_posts() ) : the_post();

    // Load content part inside the loop
        do_action( 'loop_single_content' );
    
    do_action( 'evolution_before_comments' );

    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
    comments_template();
    endif;
    
    do_action( 'evolution_after_comments' );

    endwhile; // End of the loop.

do_action( 'evolution_after_single_loop' ); 
}
endif;



if ( ! function_exists( 'evolution_do_archive_loop' ) ) :
/**
 * Outputs the archive loop
 * 
 * @hooked evolution_do_archive()
 * 
 * @see /inc/structure/evolution-hooks.php
 * @see /inc/structure/evolution-loop-content.php
 * 
 * @see archive.php
 *
 * @since  1.0.0
 * @return  void
 */
function evolution_do_archive_loop() {

if ( have_posts() ) : 

echo '<header class="page-header">';

    the_archive_title( '<h1 class="page-title">', '</h1>' );
    the_archive_description( '<div class="taxonomy-description">', '</div>' );

echo'</header><!-- .page-header -->';
    
    while ( have_posts() ) : the_post();

        // Load excerpt content part inside the loop
            do_action( 'loop_summary_content' );

    endwhile;

    the_posts_pagination( array(
    'prev_text' => esc_html__( '&laquo; Previous', 'evolution' ),
    'next_text' => esc_html__( 'Next &raquo;', 'evolution' ),
    ) );

    else :
    
    // Display the none content part, if no content there
    evolution_content_none();

    endif;   
}
endif;




if ( ! function_exists( 'evolution_do_search_loop' ) ) :
/**
 * Outputs the loops for searches
 * 
 * @hooked evolution_do_search()
 * 
 * @see /inc/structure/evolution-hooks.php
 * @see /inc/structure/evolution-loop-content.php
 * 
 * @see search.php
 *
 * @since  1.0.0
 * @return  void
 */
function evolution_do_search_loop() {

    do_action( 'evolution_before_search_loop' );

    if ( have_posts() ) :

    echo '<header class="page-header">', "\n", '<h1 class="page-title">';
    
    printf( esc_html__( 'Your Search Results for: %s', 'evolution' ), '<mark>' . get_search_query() . '</mark>' );
    
    echo '</h1>', "\n", '</header><!-- .page-header -->';

        /* Start the Loop */ 
        while ( have_posts() ) : the_post();

            do_action( 'loop_search_content' );

        endwhile;

        do_action( 'evolution_after_search_loop' );

    // Previous/next page navigation.
    the_posts_pagination( array(
        'prev_text'          => __( 'Previous page', 'evolution' ),
        'next_text'          => __( 'Next page', 'evolution' ),
        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'evolution' ) . ' </span>',
    ) );

else :

    evolution_content_none();

endif;
}
endif;
