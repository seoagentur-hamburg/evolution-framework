<?php
/**
 * Custom widgets for this theme.
 *
 * @package evolution
 */


/**
 * Recent Post Widget
 * This class is based on code from WordPress core.
 */
class Evolution_Widget_Recent_Posts extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_evolution_recent_posts', 'description' => esc_html__( 'Displays recent posts with featured images.', 'evolution' ));
		parent::__construct('evolution_recent_posts', esc_html__( 'Evolution Recent Posts', 'evolution' ), $widget_ops);
	}

	public function widget($args, $instance) {
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Posts', 'evolution' );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$r = new WP_Query( array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true
		) );
		if ($r->have_posts()) :
		?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li>
				<a href="<?php the_permalink(); ?>">
				<?php if ( has_post_thumbnail() ): ?>
                    <?php the_post_thumbnail('evolution-small'); ?>
				<?php endif; ?>
					<div class="recent-posts-text">
						<?php get_the_title() ? the_title() : the_ID(); ?>
						<?php if ( $show_date ) : ?>
						<span class="post-date"><?php echo get_the_date(); ?></span>
						<?php endif; ?>
					</div>
				</a>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();
		endif;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;

		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title:', 'evolution' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id('number') ); ?>"><?php esc_html_e( 'Number of posts to show:', 'evolution' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id('number') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id('show_date') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id('show_date') ); ?>"><?php esc_html_e( 'Display post date?', 'evolution' ); ?></label></p>
	<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Evolution_Widget_Recent_Posts" );' ) );





/**
 * Evolution Social Icons
 * @author Andreas Hecht
 * @version 1.0.2
 * @license GNU General Public License v2.0 (or later)
 * @license URI http://www.opensource.org/licenses/gpl-license.php
 * 
 * @based on Code from Nathan Rice
 * */

class Evolution_Social_Icons_Widget extends WP_Widget {

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $sizes;

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $profiles;

	/**
	 * Constructor method.
	 *
	 * Set some global values and create widget.
	 */
	public function __construct() {

		/**
		 * Default widget option values.
		 */
		$this->defaults = apply_filters( 'evolution_social_default_styles', array(
			'title'					 => '',
			'new_window'			 => 0,
			'size'					 => 36,
			'border_radius'			 => 3,
			'icon_color'			 => '#ffffff',
			'icon_color_hover'		 => '#ffffff',
			'background_color'		 => '#cccccc',
			'background_color_hover' => '#c03d0c',
			'alignment'				 => 'alignleft',
			'dribbble'				 => '',
			'facebook'				 => '',
			'flickr'				 => '',
			'github'				 => '',
			'telegram'				 => '',
			'instagram'				 => '',
			'linkedin'				 => '',
            'xing'				 => '',
			'pinterest'				 => '',
			'rss'					 => '',
			'stumbleupon'			 => '',
			'twitter'				 => '',
			'vimeo'					 => '',
			'youtube'				 => '',
			'website'				 => '',
		) );

		/**
		 * Social profile choices.
		 */
		$this->profiles = apply_filters( 'evolution_social_default_profiles', array(

			'website' => array(
				'label'		  => __( 'Website URI', 'evolution' ),
				'pattern'	  => '<li class="social-website"><a href="%s" %s title="Website"><i class="fa fa-link" aria-hidden="true"></i></a></li>',
			),
			'facebook' => array(
				'label'		  => __( 'Facebook URI', 'evolution' ),
				'pattern'	  => '<li class="social-facebook"><a href="%s" %s title="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>',
			),
            'twitter' => array(
				'label'		  => __( 'Twitter URI', 'evolution' ),
				'pattern'	  => '<li class="social-twitter"><a href="%s" %s title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>',
			),
            'telegram' => array(
				'label'		  => __( 'Telegram URI', 'evolution' ),
				'pattern'	  => '<li class="social-telegram"><a href="%s" %s title="Telegram"><i class="fa fa-telegram" aria-hidden="true"></i></a></li>',
			),
			'flickr' => array(
				'label'		  => __( 'Flickr URI', 'evolution' ),
				'pattern'	  => '<li class="social-flickr"><a href="%s" %s title="Flickr"><i class="fa fa-flickr" aria-hidden="true"></i></a></li>',
			),
			'github' => array(
				'label'		  => __( 'GitHub URI', 'evolution' ),
				'pattern'	  => '<li class="social-github"><a href="%s" %s title="GitHub"><i class="fa fa-github" aria-hidden="true"></i></a></li>',
			),
			'instagram' => array(
				'label'		  => __( 'Instagram URI', 'evolution' ),
				'pattern'	  => '<li class="social-instagram"><a href="%s" %s title="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>',
			),
			'linkedin' => array(
				'label'		  => __( 'Linkedin URI', 'evolution' ),
				'pattern'	  => '<li class="social-linkedin"><a href="%s" %s title="LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>',
			),
			'pinterest' => array(
				'label'		  => __( 'Pinterest URI', 'evolution' ),
				'pattern'	  => '<li class="social-pinterest"><a href="%s" %s title="Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>',
			),
			'rss' => array(
				'label'		  => __( 'RSS URI', 'evolution' ),
				'pattern'	  => '<li class="social-rss"><a href="%s" %s title="RSS Feed"><i class="fa fa-rss" aria-hidden="true"></i></a></li>',
			),
			'stumbleupon' => array(
				'label'		  => __( 'StumbleUpon URI', 'evolution' ),
				'pattern'	  => '<li class="social-stumbleupon"><a href="%s" %s title="StumbleUpon"><i class="fa fa-stumbleupon" aria-hidden="true"></i></a></li>',
			),
            'dribbble' => array(
				'label'		  => __( 'Dribbble URI', 'evolution' ),
				'pattern'	  => '<li class="social-dribbble"><a href="%s" %s title="Dribbble"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>',
			),
			'vimeo' => array(
				'label'		  => __( 'Vimeo URI', 'evolution' ),
				'pattern'	  => '<li class="social-vimeo"><a href="%s" %s title="Vimeo"><i class="fa fa-vimeo" aria-hidden="true"></i></a></li>',
			),
			'youtube' => array(
				'label'		  => __( 'YouTube URI', 'evolution' ),
				'pattern'	  => '<li class="social-youtube"><a href="%s" %s title="YouTube"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>',
			),
            'xing' => array(
				'label'		  => __( 'Xing URI', 'evolution' ),
				'pattern'	  => '<li class="social-xing"><a href="%s" %s title="Xing"><i class="fa fa-xing" aria-hidden="true"></i></a></li>',
			),
		) );

		$widget_ops = array(
			'classname'	  => 'evolution-social-icons',
			'description' => __( 'Shows Social Media Follow Buttons in Header, Sidebar or Footer.', 'evolution' ),
		);

		$control_ops = array(
			'id_base' => 'evolution-social-icons',
		);

		parent::__construct( 'evolution-social-icons', __( 'Evolution Social Icons', 'evolution' ), $widget_ops, $control_ops );

		/** Enqueue icon font */
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css' ) );

		// Loading css inline styles only when widget in use
		if ( is_active_widget(false, false, $this->id_base, true) ) {

		/** Load CSS in <head> */
		add_action( 'wp_head', array( $this, 'css' ) );

	}

}
	/**
	 * Widget Form.
	 *
	 * Outputs the widget form that allows users to control the output of the widget.
	 *
	 */
	public function form( $instance ) {

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'evolution' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><label><input id="<?php echo $this->get_field_id( 'new_window' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'new_window' ); ?>" value="1" <?php checked( 1, $instance['new_window'] ); ?>/> <?php esc_html_e( 'Open links in new window?', 'evolution' ); ?></label></p>

		<p><label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Icon Size', 'evolution' ); ?>:</label> <input id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" type="text" value="<?php echo esc_attr( $instance['size'] ); ?>" size="3" />px</p>

		<p><label for="<?php echo $this->get_field_id( 'border_radius' ); ?>"><?php _e( 'Icon Border Radius:', 'evolution' ); ?></label> <input id="<?php echo $this->get_field_id( 'border_radius' ); ?>" name="<?php echo $this->get_field_name( 'border_radius' ); ?>" type="text" value="<?php echo esc_attr( $instance['border_radius'] ); ?>" size="3" />px</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Alignment', 'evolution' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
				<option value="alignleft" <?php selected( 'alignright', $instance['alignment'] ) ?>><?php _e( 'Align Left', 'evolution' ); ?></option>
				<option value="aligncenter" <?php selected( 'aligncenter', $instance['alignment'] ) ?>><?php _e( 'Align Center', 'evolution' ); ?></option>
				<option value="alignright" <?php selected( 'alignright', $instance['alignment'] ) ?>><?php _e( 'Align Right', 'evolution' ); ?></option>
			</select>
		</p>

		<hr style="background: #ccc; border: 0; height: 1px; margin: 20px 0;" />

		<p><label for="<?php echo $this->get_field_id( 'background_color' ); ?>"><?php _e( 'Icon Font Color:', 'evolution' ); ?></label> <input id="<?php echo $this->get_field_id( 'icon_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_color' ); ?>" type="text" value="<?php echo esc_attr( $instance['icon_color'] ); ?>" size="6" /></p>

		<p><label for="<?php echo $this->get_field_id( 'background_color_hover' ); ?>"><?php _e( 'Icon Font Hover Color:', 'evolution' ); ?></label> <input id="<?php echo $this->get_field_id( 'icon_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'icon_color_hover' ); ?>" type="text" value="<?php echo esc_attr( $instance['icon_color_hover'] ); ?>" size="6" /></p>

		<p><label for="<?php echo $this->get_field_id( 'background_color' ); ?>"><?php _e( 'Background Color:', 'evolution' ); ?></label> <input id="<?php echo $this->get_field_id( 'background_color' ); ?>" name="<?php echo $this->get_field_name( 'background_color' ); ?>" type="text" value="<?php echo esc_attr( $instance['background_color'] ); ?>" size="6" /></p>

		<p><label for="<?php echo $this->get_field_id( 'background_color_hover' ); ?>"><?php _e( 'Background Hover Color:', 'evolution' ); ?></label> <input id="<?php echo $this->get_field_id( 'background_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'background_color_hover' ); ?>" type="text" value="<?php echo esc_attr( $instance['background_color_hover'] ); ?>" size="6" /></p>

		<hr style="background: #ccc; border: 0; height: 1px; margin: 20px 0;" />

		<?php
		foreach ( (array) $this->profiles as $profile => $data ) {

			printf( '<p><label for="%s">%s:</label></p>', esc_attr( $this->get_field_id( $profile ) ), esc_attr( $data['label'] ) );
			printf( '<p><input type="text" id="%s" name="%s" value="%s" class="widefat" />', esc_attr( $this->get_field_id( $profile ) ), esc_attr( $this->get_field_name( $profile ) ), esc_url( $instance[$profile] ) );
			printf( '</p>' );

		}

	}
	/**
	 * Form validation and sanitization.
	 *
	 * Runs when you save the widget form. Allows you to validate or sanitize widget options before they are saved.
	 *
	 */
	public function update( $newinstance, $oldinstance ) {

		foreach ( $newinstance as $key => $value ) {

			/** Border radius and Icon size must not be empty, must be a digit */
			if ( ( 'border_radius' == $key || 'size' == $key ) && ( '' == $value || ! ctype_digit( $value ) ) ) {
				$newinstance[$key] = 0;
			}

			/** Validate hex code colors */
			elseif ( strpos( $key, '_color' ) && 0 == preg_match( '/^#(([a-fA-F0-9]{3}$)|([a-fA-F0-9]{6}$))/', $value ) ) {
				$newinstance[$key] = $oldinstance[$key];
			}

			/** Sanitize Profile URIs */
			elseif ( array_key_exists( $key, (array) $this->profiles ) ) {
				$newinstance[$key] = esc_url( $newinstance[$key] );
			}

		}

		return $newinstance;

	}

	/**
	 * Widget Output.
	 *
	 * Outputs the actual widget on the front-end based on the widget options the user selected.
	 *
	 */
	public function widget( $args, $instance ) {

		extract( $args );

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$before_widget = '<div class="widget fix evolution-social-icons">';
		$after_widget = '</div>';

		echo $before_widget;

			if ( ! empty( $instance['title'] ) )
				echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

			$output = '';

			$new_window = $instance['new_window'] ? 'target="_blank"' : '';

			$profiles = (array) $this->profiles;

			foreach ( $profiles as $profile => $data ) {
				if ( ! empty( $instance[$profile] ) )
					$output .= sprintf( $data['pattern'], esc_url( $instance[$profile] ), $new_window );
			}

			if ( $output )
				printf( '<ul class="%s">%s</ul>', $instance['alignment'], $output );

		echo $after_widget;

	}

	/**
	 * Custom CSS.
	 *
	 * Outputs custom CSS to control the look of the icons.
	 *
	 */

	public function css() {

		/** Pull widget settings, merge with defaults */
		$all_instances = $this->get_settings();
		$instance = wp_parse_args( $all_instances[$this->number], $this->defaults );

		$font_size = round( (int) $instance['size'] / 2 );
		$icon_padding = round ( (int) $font_size / 2 );

		/** The CSS to output */
		$css = '
		.evolution-social-icons ul li a,
		.evolution-social-icons ul li a:hover {
			background-color: ' . $instance['background_color'] . ' !important;
			-moz-border-radius: ' . $instance['border_radius'] . 'px;
			-webkit-border-radius: ' . $instance['border_radius'] . 'px;
			border-radius: ' . $instance['border_radius'] . 'px;
			color: ' . $instance['icon_color'] . ' !important;
			font-size: ' . $font_size . 'px;
			padding: ' . $icon_padding . 'px;
		}

		.evolution-social-icons ul li a:hover {
			background-color: ' . $instance['background_color_hover'] . ' !important;
			color: ' . $instance['icon_color_hover'] . ' !important;
		}';

		/** Minify a bit */
		$css = str_replace( "\t", '', $css );
		$css = str_replace( array( "\n", "\r" ), ' ', $css );

		/** Echo the CSS */
		echo '<style type="text/css" media="screen">' . $css . '</style>', "\n" ;
	}

}
/**
 * Widget Registration.
 *
 * Register Evolution Social Icons widget.
 *
 */
add_action( 'widgets_init', create_function( '', 'return register_widget( "Evolution_Social_Icons_Widget" );' ) );





/**
 * Twitter Timeline Widget
 * 
 * @since 1.0.0
 */ 
class Evolution_Twitter_Timeline_Widget extends WP_Widget {

    /**
 * Register widget with WordPress.
 */
    public function __construct() {
        parent::__construct(
            'evolution_twitter_timeline', // Base ID
            'Evolution Twitter Timeline Widget', // Name
            array( 'description' => __( 'This widget display the new and shiny Twitter Timeline from your twitter account.', 'evolution' ), ) // Args
        );

        // Registers Script with WordPress ( to wp_footer(); )
        wp_register_script( 'widgets', '//platform.twitter.com/widgets.js','','', true );

        // Adding the javascript only if widget in use
        if ( is_active_widget( false, false, $this->id_base, true ) ) {

            wp_enqueue_script('widgets');

        }
    }	

    /**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
    public function widget($args, $instance) {  	

        //global $app_id;
        extract( $args );

        $title 		                    = apply_filters('widget_title', $instance['title']);
        $width		                 = $instance['width'];
        $height		                 = $instance['height'];
        $twitter_name	      = $instance['twitter_name'];
        $link_color	               = $instance['link_color'];
        $theme_color	       = $instance['theme_color'];

        echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;

        echo '<a class="twitter-timeline" href="https://twitter.com/'.$twitter_name.'" data-width="'.$width.'" data-height="'.$height.'" data-theme="'.$theme_color.'" data-link-color="'.$link_color.'" >Tweets von @"'.$twitter_name.'"</a>' ;

        echo $after_widget;

    } 


    /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
    public function update( $new_instance, $old_instance ) {

        $instance = array();
        $instance['title'] 		             = strip_tags( $new_instance['title'] );
        $instance['width'] 		          = strip_tags( $new_instance['width'] );
        $instance['height'] 		      = strip_tags($new_instance['height'] );
        $instance['twitter_name']  = strip_tags($new_instance['twitter_name'] );
        $instance['link_color']		    = strip_tags($new_instance['link_color'] );
        $instance['theme_color']	= strip_tags($new_instance['theme_color'] );

        return $instance;

    }


    /**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
    public function form($instance) {

        /**
    	 * Set Default Value for widget form
    	 */   	
        $default_value	=	array("title"=> "Follow me on Twitter", "width" => "340", "height" => "400", "twitter_name" => "AndreasHecht_HH",  "link_color" => "#f96e5b", "theme_color" => "light" );
        $instance		=	wp_parse_args((array)$instance,$default_value);

        $title		              = esc_attr($instance['title']);
        $width		           = esc_attr($instance['width']);
        $height		           = esc_attr($instance['height']);
        $twitter_name	= esc_attr($instance['twitter_name']);
        $link_color	         = esc_attr($instance['link_color']);
        $theme_color	 = esc_attr($instance['theme_color']);
?>
<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'evolution'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e( 'Choose the width of the timeline:', 'evolution' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e( 'Choose the height of the timeline:', 'evolution' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('twitter_name'); ?>"><?php _e('Your twitter name:', 'evolution'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('twitter_name'); ?>" name="<?php echo $this->get_field_name('twitter_name'); ?>" type="text" value="<?php echo $twitter_name; ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('link_color'); ?>"><?php _e('Link color:', 'evolution'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('link_color'); ?>" name="<?php echo $this->get_field_name('link_color'); ?>" type="text" value="<?php echo $link_color; ?>" />
</p> 

<p>
    <label for="<?php echo $this->get_field_id('theme_color'); ?>"><?php _e('Choose a theme color (light or dark):', 'evolution'); ?></label>
    <select name="<?php echo $this->get_field_name('theme_color'); ?>" id="<?php echo $this->get_field_id('theme_color'); ?>" class="widefat">
        <option value="light"<?php selected( $instance['theme_color'], 'light' ); ?>><?php _e('Light', 'evolution'); ?></option>
        <option value="dark"<?php selected( $instance['theme_color'], 'dark' ); ?>><?php _e('Dark', 'evolution'); ?></option>
    </select>
</p> 

<?php
    }

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Evolution_Twitter_Timeline_Widget" );' ) );








/**
 * Profile Widget
 * This class is based on code from WordPress core.
 */
class Evolution_Widget_Profile extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_evolution_profile', 'description' => esc_html__( 'Displays a profile with a photo and social media links.', 'evolution' ));
        parent::__construct('evolution_profile', esc_html__( 'Evolution Profile', 'evolution' ), $widget_ops);
    }

    public function widget( $args, $instance ) {
        $title = empty( $instance['title'] ) ? '' : $instance['title'];
        $profile = empty( $instance['profile'] ) ? '' : $instance['profile'];
        $name = empty( $instance['name'] ) ? '' : $instance['name'];
        $text = empty( $instance['text'] ) ? '' : $instance['text'];
        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>
<div class="profilewidget">
    <?php if ( $profile ): ?>
    <div class="profilewidget-profile"><img src="<?php echo esc_attr( $profile ); ?>" alt="<?php echo esc_attr( $name ); ?>" /></div>
    <?php endif; ?>
    <div class="profilewidget-meta">
        <div class="profilewidget-name"><strong><?php echo esc_html( $name ); ?></strong></div>
        <?php
        $url      = get_the_author_meta( 'url' );                                   
        $social_1 = get_the_author_meta( 'facebook' );
        $social_2  = get_the_author_meta( 'twitter' );
        $social_3     = get_the_author_meta( 'feedurl' );
        $social_4    = get_the_author_meta( 'instagram' );
        $social_5   = get_the_author_meta( 'linkedin' );
        $social_6   = get_the_author_meta( 'xing' );
        $social_7   = get_the_author_meta( 'youtube' );
        $social_8   = get_the_author_meta( 'github' ); 
        $social_9   = get_the_author_meta( 'vimeo' );
        $social_10   = get_the_author_meta( 'flickr' );
        $social_11   = get_the_author_meta( 'pinterest' );                          
        ?>
        <?php if ( $url || $social_1 || $social_2 || $social_3 || $social_4 || $social_5 || $social_6 || $social_7 || $social_8 || $social_9 || $social_10 || $social_11 ): ?>
        <div class="social-icons">
            <ul class="social-link clearfix">
                <?php if( $url ) : ?><li><a href="<?php echo esc_url( $url ); ?>" target="_blank" title="Website"><i class="fa fa-link" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_1 ) : ?><li><a href="<?php echo esc_url( $social_1 ); ?>" target="_blank" title="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_2 ) : ?><li><a href="<?php echo esc_url( $social_2 ); ?>" target="_blank" title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_3 ) : ?><li><a href="<?php echo esc_url( $social_3 ); ?>" target="_blank" title="RSS Feed"><i class="fa fa-rss" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_4 ) : ?><li><a href="<?php echo esc_url( $social_4 ); ?>" target="_blank" title="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_5 ) : ?><li><a href="<?php echo esc_url( $social_5 ); ?>" target="_blank" title="LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_6 ) : ?><li><a href="<?php echo esc_url( $social_6 ); ?>" target="_blank" title="Xing"><i class="fa fa-xing" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_7 ) : ?><li><a href="<?php echo esc_url( $social_7 ); ?>" target="_blank" title="Youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_8 ) : ?><li><a href="<?php echo esc_url( $social_8 ); ?>" target="_blank" title="GitHub"><i class="fa fa-github" aria-hidden="true"></i></a></li><?php endif; ?>			
                <?php if( $social_9 ) : ?><li><a href="<?php echo esc_url( $social_9 ); ?>" target="_blank" title="Vimeo"><i class="fa fa-vimeo" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_10 ) : ?><li><a href="<?php echo esc_url( $social_10 ); ?>" target="_blank" title="Flickr"><i class="fa fa-flickr" aria-hidden="true"></i></a></li><?php endif; ?>
                <?php if( $social_11 ) : ?><li><a href="<?php echo esc_url( $social_11 ); ?>" target="_blank" title="Pinterest"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li><?php endif; ?>
            </ul><!-- .author-profile-link -->
        </div>
        <div class="clear"></div>
        <?php endif; ?>
    </div>
    <div class="profilewidget-text"><p><?php echo wp_kses_post( $text ); ?></p></div>
</div>
<?php
        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['profile'] = strip_tags($new_instance['profile']);
        $instance['name'] = strip_tags($new_instance['name']);
        if ( current_user_can('unfiltered_html') )
            $instance['text'] =  $new_instance['text'];
        else
            $instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'profile' => '', 'name' => '', 'text' => '', 'social_1' => '', 'social_2' => '', 'social_3' => '', 'social_4' => '', 'social_5' => '', 'social_6' => '', 'social_7' => '' ) );
        $title = strip_tags($instance['title']);
        $profile = strip_tags($instance['profile']);
        $name = strip_tags($instance['name']);
        $text = $instance['text'];
?>
<p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title:', 'evolution' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

<p><label for="<?php echo esc_attr( $this->get_field_id('profile') ); ?>"><?php esc_html_e( 'Profile Image URL:', 'evolution' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('profile') ); ?>" name="<?php echo esc_attr( $this->get_field_name('profile') ); ?>" type="text" value="<?php echo esc_attr( $profile ); ?>" /></p>

<p><label for="<?php echo esc_attr( $this->get_field_id('name') ); ?>"><?php esc_html_e( 'Name:', 'evolution' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('name') ); ?>" name="<?php echo esc_attr( $this->get_field_name('name') ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" /></p>

<textarea class="widefat" rows="8" cols="20" id="<?php echo esc_attr( $this->get_field_id('text') ); ?>" name="<?php echo esc_attr( $this->get_field_name('text') ); ?>"><?php echo esc_textarea( $text ); ?></textarea>
<?php
    }
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Evolution_Widget_Profile" );' ) );






/**
 * Popular Post Widget
 * This class is based on code from WordPress core.
 */
class Evolution_Widget_Popular_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_evolution_popular_posts', 'description' => esc_html__( 'Displays the most popular posts by comments count with featured images.', 'evolution' ));
        parent::__construct('evolution_popular_posts', esc_html__( 'Evolution Popular Posts', 'evolution' ), $widget_ops);
    }

    public function widget($args, $instance) {
        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Popular Posts', 'evolution' );
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;
        $r = new WP_Query( array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
            'orderby' => 'comment_count'
        ) );
        if ($r->have_posts()) :
?>
<?php echo $args['before_widget']; ?>
<?php if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>
<ul class="popular-posts-wrap">
    <?php while ( $r->have_posts() ) : $r->the_post(); ?>
    <li class="popular-item">
        <a href="<?php the_permalink(); ?>">
            <?php if ( has_post_thumbnail() ): ?>
            <div class="thumbnail">
                <?php the_post_thumbnail('evolution-medium'); ?>
            </div>
            <?php endif; ?>
            <div class="popular-posts-text entry-header">
                <div class="popular-posts-table">
                    <div class="popular-posts-inner">
                        <h4 class="popular-posts-header">
                            <?php get_the_title() ? the_title() : the_ID(); ?>
                        </h4>
                        <span class="popular-comments">
                            <?php comments_number( esc_html__( '0 Comments', 'evolution' ), esc_html__( '1 Comment', 'evolution' ), esc_html__( '% Comments', 'evolution' ) ); ?>
                        </span>
                    </div>
                </div>
            </div>
        </a>
    </li>
    <?php endwhile; ?>
</ul>
<?php echo $args['after_widget']; ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();
        endif;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];

        return $instance;
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
<p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title:', 'evolution' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

<p><label for="<?php echo esc_attr( $this->get_field_id('number') ); ?>"><?php esc_html_e( 'Number of posts to show:', 'evolution' ); ?></label>
    <input id="<?php echo esc_attr( $this->get_field_id('number') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>

<?php
    }
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Evolution_Widget_Popular_Posts" );' ) );
