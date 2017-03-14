<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the connect Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Connect_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Connect_Widget extends WP_Widget {

	/**
	 * Speakers Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Connect_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_connect', 'description' => __( 'Shows a section displaying icons for the social media links filled out in the Customizer', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_connect', $widget_name . __( ' Social Media Links', 'dxef' ), $widget_ops );
		
	}
	
	/**
	 * Output of Widget Content
	 * 
	 * Handle to outputs the
	 * content of the widget
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {
		
		$connecttitle	= isset( $instance['connecttitle'] ) ? $instance['connecttitle'] : '';
		
		// Get Theme Options
		$ef_options		= EF_Event_Options::get_theme_options();
		
		$esc_url_protocols = array( 'http', 'https', 'feed' );
		
		echo stripslashes( $args['before_widget'] );?>
	    
		<!-- CONNECT -->
		<div id="tile_connect" class="container widget">
			<div class="connect">
				<div class="connect-inner">
					<span><?php echo stripslashes( $connecttitle ); ?></span>
					<div class="links"><?php
						
						if ( ! empty( $ef_options['ef_email'] ) && is_email( $ef_options['ef_email'] ) ) { ?>
							<a href="mailto:<?php echo $ef_options['ef_email']; ?>" title="Email"><i class="icon-envelope"></i></a><?php
						}
						if ( isset( $ef_options['ef_rss'] ) && $ef_options['ef_rss'] == true) { ?>
							<a href="<?php echo get_bloginfo('rss_url'); ?>" target="_blank" title="Rss"><i class="icon-rss"></i></a><?php
						}
						if ( ! empty( $ef_options['ef_facebook'] ) ) { ?>
							<a href="<?php echo esc_url( $ef_options['ef_facebook'], $esc_url_protocols ); ?>" target="_blank" title="Facebook"><i class="icon-facebook"></i></a><?php
						}
						if ( ! empty( $ef_options['ef_twitter'] ) ) { ?>
							<a href="<?php echo esc_url( $ef_options['ef_twitter'], $esc_url_protocols ); ?>" target="_blank" title="Twitter"><i class="icon-twitter"></i></a><?php 
						}
						if ( ! empty( $ef_options['ef_google_plus'] ) ) { ?>
							<a href="<?php echo esc_url( $ef_options['ef_google_plus'], $esc_url_protocols ); ?>" target="_blank" title="Google+"><i class="icon-googleplus"></i></a><?php
						}
						if ( ! empty( $ef_options['ef_instagram'] ) ) { ?>
							<a href="<?php echo esc_url( $ef_options['ef_instagram'], $esc_url_protocols ); ?>" target="_blank" title="Instagram"><i class="icon-instagram"></i></a><?php
						}
						if ( ! empty( $ef_options['ef_flickr'] ) ) { ?>
							<a href="<?php echo esc_url( $ef_options['ef_flickr'], $esc_url_protocols ); ?>" target="_blank" title="Flickr"><i class="icon-flickr"></i></a><?php
						}
						if ( ! empty( $ef_options['ef_linkedin'] ) ) {?>
							<a href="<?php echo esc_url( $ef_options['ef_linkedin'], $esc_url_protocols ); ?>" target="_blank" title="LinkedIn"><i class="icon-linkedin"></i></a><?php
						}
						if ( ! empty( $ef_options['ef_pinterest'] ) ) { ?>
							<a href="<?php echo esc_url( $ef_options['ef_pinterest'], $esc_url_protocols ); ?>" target="_blank" title="Pinterest"><i class="icon-pinterest"></i></a><?php
						}?>
	                </div>
	            </div>
	        </div>
	    </div><?php
	    
	    echo stripslashes( $args['after_widget'] );
		
	}

	/**
	 * Update Widget Setting
	 * 
	 * Handle to updates the widget control options
	 * for the particular instance of the widget
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		/* Set the instance to the new instance. */
		$instance = $new_instance;
		
		/* Input fields */
		$instance['connecttitle']	= strip_tags( $new_instance['connecttitle'] );
		
		return $instance;
		
	}
	
	/**
	 * Display Widget Form
	 * 
	 * Displays the widget
	 * form in the admin panel
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function form( $instance ) {
	
		$connecttitle	= isset( $instance['connecttitle'] ) ? $instance['connecttitle'] : '';?>
	    
		<em><?php _e( 'Title:', 'dxef' ); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'connecttitle' ); ?>" value="<?php echo stripslashes( $connecttitle ); ?>" />
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" /><?php
    
	}
}

// Register widget
register_widget( 'Ef_Connect_Widget' );