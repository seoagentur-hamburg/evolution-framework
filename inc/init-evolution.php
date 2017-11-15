<?php
 /**
* The Evolution Framework
*
* WARNING: This file is part of the core Evolution Framework. DO NOT edit this file under any circumstances.
* Please do all modifications in the form of a child theme.
*
* Loading all parts of Evolution Framework
*
* @package Evolution\Functions\
* @author  Andreas Hecht
* @license GPL-2.0+
* @link    https://andreas-hecht.com/wordpress-themes/evolution
*/


/**
* Defining the Evolution Framework location constants
*
* @since 1.0.0
*/
define( 'PARENT_DIR', get_template_directory() );
define( 'CHILD_DIR', get_stylesheet_directory() );
define( 'EVOLUTION_INC_DIR', PARENT_DIR . '/inc' );
define( 'EVOLUTION_FUNCTIONS_DIR', EVOLUTION_INC_DIR . '/functions' );
define( 'EVOLUTION_HELPER_DIR', EVOLUTION_FUNCTIONS_DIR . '/helper' );
define( 'EVOLUTION_SETUP_DIR', EVOLUTION_FUNCTIONS_DIR . '/setup' );
define( 'EVOLUTION_WOOCOMMERCE_DIR', EVOLUTION_INC_DIR . '/woocommerce' );
define( 'EVOLUTION_ADMIN_DIR', EVOLUTION_INC_DIR . '/admin' );
define( 'EVOLUTION_WIDGET_DIR', EVOLUTION_INC_DIR . '/widgets' );
define( 'EVOLUTION_STRUCTURE_DIR', EVOLUTION_INC_DIR . '/structure' );
define( 'EVOLUTION_MARKUP_DIR', EVOLUTION_STRUCTURE_DIR . '/markup' );
define( 'EVOLUTION_COMPATIBILITY_DIR', EVOLUTION_INC_DIR . '/compatibility' );


/**
* Loading all parts of the Evolution Framework
*
* @since 1.0.0
*/ 

// Loads the main theme setup
require_once( EVOLUTION_SETUP_DIR . '/setup.php' );

// Loads the theme scripts and styles
require_once( EVOLUTION_HELPER_DIR . '/script-loader.php' );
// Defines the widget areas
require_once( EVOLUTION_HELPER_DIR . '/widgets-init.php' );
// Loads some helper functions
require_once( EVOLUTION_HELPER_DIR . '/helper.php' );


// Loads some frontend relevated functions
require_once( EVOLUTION_FUNCTIONS_DIR . '/frontend-functions.php' );


// Loads custom woocommerce compatibility and functions
require_once( EVOLUTION_WOOCOMMERCE_DIR . '/woocommerce-functions.php' );


// Loads the custom evolution widgets
require_once( EVOLUTION_WIDGET_DIR . '/evolution-widgets.php' );


// Loads customizer options file
require_once( EVOLUTION_ADMIN_DIR . '/customizer.php' );
// Loads the Evolution Version Meta Tag Generator
require_once( EVOLUTION_ADMIN_DIR . '/evolution-version.php' );


// Load Jetpack compatibility file
require_once( EVOLUTION_COMPATIBILITY_DIR . '/jetpack.php' );


// Loads the evolution loops and all the hooks
require_once( EVOLUTION_STRUCTURE_DIR . '/evolution-loops.php' );
require_once( EVOLUTION_STRUCTURE_DIR . '/evolution-hooks.php' );
// Loads the content parts used inside the loops
require_once( EVOLUTION_STRUCTURE_DIR . '/evolution-loop-content.php' );


// Loads all markup used in the template files
require_once( EVOLUTION_MARKUP_DIR . '/evolution-header.php' );
require_once( EVOLUTION_MARKUP_DIR . '/evolution-comments.php' );
require_once( EVOLUTION_MARKUP_DIR . '/evolution-footer.php' );
require_once( EVOLUTION_MARKUP_DIR . '/evolution-sidebars.php' );