<?php
/**
 * The Evolution Framework.
 * 
 * Template: searchform.php
 *
 * WARNING: This file is part of the core Evolution Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Evolution\Template\
 * @author  Andreas Hecht
 * @license GPL-2.0+
 * @link https://andreas-hecht.com/wordpress-themes/evolution-wordpress-framework/
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="pre-input"><i class="fa fa-search" aria-hidden="true"></i></span>
		<input type="search" class="search-field plain buffer" placeholder="<?php _e( 'Search...', 'evolution' ) ?>" value="" name="s" title="<?php _e( 'Search...', 'evolution' ) ?>">
	</label>
</form>