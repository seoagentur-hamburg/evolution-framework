<?php
/**
* The Evolution Framework
*
* WARNING: This file is part of the core Evolution Framework. DO NOT edit this file under any circumstances.
* Please do all modifications in the form of a child theme.
*
* This file handles the Evolution Framework Meta Generator Tag
*
* @package Evolution Framework\Functions\
* @author  Andreas Hecht
* @license GPL-2.0+
* @link    https://andreas-hecht.com/wordpress-themes/evolution-wordpress-framework/
*/



if ( ! function_exists( 'evolution_version' ) ) :
/**
 * Add Evolution Framework Meta Generator Tag
 * 
 * @since 1.0.0
 * 
 * @hooked wp_head()
 */
function evolution_version() {

    // ATTENTION: WordPress 3.4 deprecates some functions, thatswhy this test
    global $wp_version;
    if ( version_compare( $wp_version, '3.4a', '>=' ) ) {
        $theme_data = wp_get_theme();
    }
    else {
        $theme_data = wp_get_theme( get_stylesheet_directory_uri() . '/style.css' );
    }
    $theme_version = $theme_data['Version'];
    $evolution_framework_version = get_option( 'evolution_framework_version' );
    $themename =  $theme_data['Name'];
    echo "<!-- Framework & Child Theme Version -->\n";
    echo '<meta name="generator" content="'. $themename .' '. $theme_version .'" />' ."\n";
    echo '<meta name="generator" content="Evolution Framework' . $evolution_framework_version . '" />' ."\n";
    
}
endif;

remove_action( 'wp_head',  'wp_generator' );
add_action( 'wp_head', 'evolution_version', 5 ); 