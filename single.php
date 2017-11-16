<?php
/**
 * The Evolution Framework.
 * 
 * Template: single.php
 *
 * WARNING: This file is part of the core Evolution Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Evolution\Templates\
 * @author  Andreas Hecht
 * @license GPL-2.0+
 * @link https://andreas-hecht.com/wordpress-themes/evolution-wordpress-framework/
 */

get_header();

// This hook is free for your actions
do_action( 'evolution_before_single' );

/**
 * Functions hooked in to 'evolution_do_single' action
 * 
 * @see /inc/structure/evolution-loops.php
 * @see /inc/structure/evolution-hooks.php
 * @see /inc/structure/evolution-loop-content.php
 * 
 * @hooked  evolution_top_markup - 5
 * @hooked  evolution_before_single_loop
 * @hooked evolution_do_single_loop - 10
 * @hooked  evolution_after_single_loop
 * @hooked  evolution_bottom_markup - 30
 */
do_action( 'evolution_do_single' );

get_sidebar();

get_footer();