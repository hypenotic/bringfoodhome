<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Session Schedule Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Schedule_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Schedule_Widget extends WP_Widget {
	
	/**
	 * Schedule Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Schedule_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_schedule', 'description' => __( 'Displays Event Schedule widget.', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_schedule', $widget_name . __( ' Event Schedule', 'dxef' ), $widget_ops );
		
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
		
		$scheduletitle			= isset( $instance['scheduletitle'] ) ? $instance['scheduletitle'] : '';
		$schedulesubtitle		= isset( $instance['schedulesubtitle'] ) ? $instance['schedulesubtitle'] : '';
		$schedulemoreinfotext	= isset( $instance['schedulemoreinfotext'] ) ? $instance['schedulemoreinfotext'] : '';
		$scheduleviewfulltext	= isset( $instance['scheduleviewfulltext'] ) ? $instance['scheduleviewfulltext'] : '';
		
		$schedule_args	= array(
								'post_type'			=> 'session',
								'posts_per_page'	=> 9,
								'meta_key'			=> 'session_home',
								'meta_value'		=> 1,
								'orderby'			=> 'menu_order',
								'order'				=> 'ASC',
								//'suppress_filters'	=> false
							);
		
		add_filter( 'posts_where', array( $this, 'ef_home_schedule_where' ) );
		
		$schedule_chunks	= array_chunk( get_posts( $schedule_args ), 3 );
		$schedule_chunks	= apply_filters( 'multievent_filter_posts_ef_schedule', $schedule_chunks, $schedule_args, $instance );
		
		remove_filter( 'posts_where', array( $this, 'ef_home_schedule_where' ) );
		
		$full_schedule_page = get_posts( 
									array(
										'post_type'		=> 'page',
										'meta_key'		=> '_wp_page_template',
										'meta_value'	=> 'schedule.php'
									)
								);
		
	    echo stripslashes( $args['before_widget'] );?>
	    
		<!-- SESSIONS -->
		<div id="tile_schedule" class="container widget">
			<h2><?php echo stripslashes($scheduletitle); ?></h2><?php 
			
			if ($full_schedule_page && count($full_schedule_page) > 0) { ?>
				
				<a href="<?php echo get_permalink($full_schedule_page[0]->ID);?>" class="btn btn-primary btn-header pull-right hidden-xs">
					<?php echo stripslashes($scheduleviewfulltext); ?>
				</a><?php 
				
			}?>
			
			<h3><?php echo stripslashes($schedulesubtitle); ?></h3>
			<div class="sessions carousel slide" data-ride="carousel" data-interval="false" id="sessions-carousel">
				<ol class="carousel-indicators"><?php 
					
					for ($i = 0; $i < count($schedule_chunks); $i++) { ?>
						<li data-target="#sessions-carousel" data-slide-to="<?php echo $i; ?>" <?php if($i == 0) echo 'class="active"'; ?>></li><?php 
					}?>
				</ol>
				<div class="carousel-inner"><?php 
					
					foreach ($schedule_chunks as $key => $schedule_chunk) { ?>
						
						<div class="item<?php if ($key == 0) echo ' active'; ?>"><?php
							
							foreach ($schedule_chunk as $session) {
								
								$locations = wp_get_post_terms( $session->ID, 'session-location' );
								if ($locations && count($locations) > 0) {
									
									$location = $locations[0];
								} else {
									
									$location = '';
								}
								
								$tracks = wp_get_post_terms( $session->ID, 'session-track', array( 'fields' => 'ids', 'count' => 1 ) );
								if ( $tracks && count( $tracks ) > 0 ) {
									
									$track = $tracks[0];
									$color = EF_Taxonomy_Helper::ef_get_term_meta( 'session-track-metas', $track, 'session_track_color' );
								} else {
									
									$track = '';
									$color = '';
								}
								
								$session_date = get_post_meta( $session->ID, 'session_date', true );
								$date = '';
								if ( ! empty( $session_date ) ) {
									
									$date = date_i18n( get_option( 'date_format' ), $session_date );
								}
								
								$time = get_post_meta( $session->ID, 'session_time', true );
								$end_time = get_post_meta( $session->ID, 'session_end_time', true );
								if ( ! empty( $time ) ) {
									
									$time_parts = explode( ':', $time );
									
									if ( count( $time_parts ) == 2 ) {
										$time = date( get_option( 'time_format' ), mktime( $time_parts[0], $time_parts[1], 0 ) );
									}
								}
		
								if ( ! empty( $end_time ) ) {
									
									$time_parts = explode( ':', $end_time );
									if ( count( $time_parts ) == 2 ) {
										
										$end_time = date( get_option( 'time_format' ), mktime( $time_parts[0], $time_parts[1], 0 ) );
									}
								}
								
								$speakers_list = get_post_meta( $session->ID, 'session_speakers_list', true );
								if( $speakers_list && count( $speakers_list ) > 0 ) {
									
									$speakers_list = array_slice( $speakers_list, 0, 8 );
								}?>
								
								<div class="session">
									<a href="<?php echo get_permalink($session->ID); ?>" class="session-inner">
										<span class="title" <?php if (isset($color)) echo " style='color:$color;'"; ?>>
											<span class="text-fit"><?php echo get_the_title($session->ID); ?></span>
										</span>
										<span class="desc">
											<?php _e('Location:', 'dxef'); ?> <strong><?php if (isset($location->name)) echo $location->name; else echo $location; ?></strong>
										</span><?php 
										
										if ( ! empty( $date ) ) { ?>
											
											<span class="desc">
												<?php _e('Date:', 'dxef'); ?> <strong><?php echo $date ?></strong>
											</span><?php 
										}
										
										if ( ! empty( $date ) ) { ?>
											<span class="desc">
												<?php _e('Time:', 'dxef'); ?> <strong><?php echo $time; ?> - <?php echo $end_time; ?></strong>
											</span><?php 
										}?>
										
										<span class="speakers-thumbs"><?php
											
											if( $speakers_list && count( $speakers_list ) > 0 ) {
												foreach ($speakers_list as $speaker_id) { ?>
													<span class="speaker <?php if (get_post_meta($speaker_id, 'speaker_keynote', true) == 1) echo ' featured'; ?>"><?php 
														echo get_the_post_thumbnail($speaker_id, 'post-thumbnail', array('title' => get_the_title($speaker_id))); ?>
													</span><?php 
												}
											}?>
										</span><!-- .speakers-thumbs -->
										<span class="more">
											<?php echo stripslashes($schedulemoreinfotext); ?> <i class="icon-angle-right"></i>
										</span>
									</a><!-- a.session-inner -->
								</div><!-- .session --><?php 
							}?>
						</div><!-- .item --><?php 
					}?>
				</div><!-- .carousel-inner -->
			</div><!-- .sessions .carousel .slide --><?php 
			
			if ( $full_schedule_page && count($full_schedule_page) > 0) { ?>
				
				<div class="text-center visible-xs">
					<a href="<?php echo get_permalink($full_schedule_page[0]->ID); ?>" class="btn btn-primary btn-header">
						<?php echo stripslashes($scheduleviewfulltext); ?>
					</a>
				</div><?php
			}?>
		</div><!-- .container .widget --><?php
	    
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
        $instance['scheduletitle']			= strip_tags( $new_instance['scheduletitle'] );
		$instance['schedulesubtitle']		= strip_tags( $new_instance['schedulesubtitle'] );
		$instance['schedulemoreinfotext']	= $new_instance['schedulemoreinfotext'];
		$instance['scheduleviewfulltext']	= strip_tags( $new_instance['scheduleviewfulltext'] );
		
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
		
        $scheduletitle			= isset( $instance['scheduletitle'] ) ? $instance['scheduletitle'] : '';
        $schedulesubtitle		= isset( $instance['schedulesubtitle'] ) ? $instance['schedulesubtitle'] : '';
        $schedulemoreinfotext	= isset( $instance['schedulemoreinfotext'] ) ? $instance['schedulemoreinfotext'] : '';
        $scheduleviewfulltext	= isset( $instance['scheduleviewfulltext'] ) ? $instance['scheduleviewfulltext'] : '';?>
        
		<em><?php _e('Title:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'scheduletitle' );?>" value="<?php echo stripslashes($scheduletitle); ?>" />
		<br /><br />
		<em><?php _e('Subtitle:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'schedulesubtitle' );?>" value="<?php echo stripslashes($schedulesubtitle); ?>" />
		<br /><br />
		<br /><br />
		<em><?php _e('"More info" Text:', 'dxef'); ?></em><br/>
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'schedulemoreinfotext' );?>" value="<?php echo esc_html($schedulemoreinfotext); ?>"/>
		<br /><br />
		<em><?php _e('"View full schedule" Button Text:', 'dxef'); ?></em><br/>
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'scheduleviewfulltext' );?>" value="<?php echo stripslashes($scheduleviewfulltext); ?>"/><?php 
		
	}
	
	/**
	 * Order Filter Of Schedule
	 * 
	 * Handle to filter for ordering
	 * sheduling event
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function ef_home_schedule_where( $where ) {
		
		return $where . ' AND menu_order > 0';
	}
}

// Register Widget
register_widget( 'Ef_Schedule_Widget' );