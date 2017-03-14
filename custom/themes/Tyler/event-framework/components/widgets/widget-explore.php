<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Event Description Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Explore_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Explore_Widget extends WP_Widget {
	
	/**
	 * Contact Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Explore_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_explore', 'description' => __( 'Shows a section displaying points of interest & maps', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_explore', $widget_name . __( ' Points of Interest', 'dxef' ), $widget_ops );
		
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
	
		$exploretitle	= isset( $instance['exploretitle'] ) ? $instance['exploretitle'] : '';
		$gmap_zoom		= isset( $instance['gmap_zoom'] ) ? $instance['gmap_zoom'] : '';
		$poi_groups		= get_terms( 'poi-group' );
	    $gmap_zoom		= ( is_numeric( $gmap_zoom ) ? $gmap_zoom : 13 );
		
		// Google Map Widget Srcipts
		wp_enqueue_script( 'ef-google-maps', 'http://maps.google.com/maps/api/js?sensor=true', false, false, true );
		wp_enqueue_script( 'ef-jquery-ui-map', EF_ASSETS_URL . '/js/jquery.ui.map.full.min.js', array( 'jquery' ), false, true );
			
		echo stripslashes($args['before_widget']);?>
		
	    <script type="text/javascript">
		    jQuery(function() {
				
		    	// Google Maps Zoom
				var gmap_zoom = <?php echo $gmap_zoom;?>;
		
		    	// Widget Explore Scripts
		        jQuery('#tile_explore .map').gmap({
		            scrollwheel: false,
		            zoomControl: true
		        }).bind('init', function(ev, map) {
		            if(pois && pois.length > 0){
		                for(i=0; i<pois.length; i++) {
		                    var cur_poi = pois[i];
		                    jQuery('#tile_explore .map').gmap('addMarker', {
		                        'position': cur_poi.poi_latitude + ',' + cur_poi.poi_longitude,
		                        'bounds': true,
		                        'icon' : new google.maps.MarkerImage(poi_marker),
		                        'text': cur_poi.poi_address
		                    }).click(function() {
		                        jQuery('#tile_explore .map').gmap('openInfoWindow', {
		                            'content': this.text
		                        }, this);
		                    });
		                    jQuery('#tile_explore .map').gmap('option', 'zoom', gmap_zoom);
		                }
		            }
		        });
		        
		        jQuery('#tile_explore .carousel-inner a[data-lat]').click(function(e){
		            e.preventDefault();
		            jQuery('#tile_explore .map').gmap('get','map').setOptions({
		                'center': new google.maps.LatLng(jQuery(this).attr('data-lat'),jQuery(this).attr('data-lng'))
		            });
		        });
		        
		        jQuery('#location-carousel').on('slid.bs.carousel', function () {
		            jQuery('#location-carousel .item.active .scrollable').jScrollPane();
		        });
		    });
	    </script>
	    
		<!-- LOCATION -->
		<div id="tile_explore" class="container widget">
		    <div class="row location">
		        <!-- Explore list -->
		        <div class="col-md-4">
		            <div class="explore carousel slide" data-ride="carousel" data-interval="false" id="location-carousel">
		                <!-- heading -->
		                <div class="heading">
		                    <a href="#location-carousel" data-slide="prev" class="pull-left"><i class="icon-angle-left"></i></a>
		                    <a href="#location-carousel" data-slide="next" class="pull-right"><i class="icon-angle-right"></i></a>
		                    <?php echo stripslashes($exploretitle); ?>
		                </div>
		                <!-- Wrapper for slides -->
		                <div class="carousel-inner"><?php
		                    
		                	$i = 0;
		                    foreach ( $poi_groups as $po_group ) {?>
		                        
		                    	<div class="item<?php if ( $i == 0 ) echo ' active'; ?>">
		                            <div class="scrollable">
		                                <div><?php
											$pois	= get_posts(
														array(
																'post_type'		=> 'poi',
																'posts_per_page'=> -1,
																'poi-group'		=> $po_group->slug,
																'orderby'		=> 'menu_order',
																'order'			=> 'ASC'
		                                 					)
		                                 				);
		                                 	echo $po_group->name;?>
											<ul><?php 
		                                        foreach( $pois as $poi ) {?>
													<li>
														<a href="#" data-lat="<?php echo get_post_meta($poi->ID, 'poi_latitude', true); ?>" data-lng="<?php echo get_post_meta($poi->ID, 'poi_longitude', true); ?>">
															<?php echo get_the_title($poi->ID); ?>
														</a>
		                                           	</li><?php
		                                        }?>
		                                    </ul>
		                                </div>
		                            </div>
		                        </div><?php
								
		                        $i++;
		                    }?>
		                </div>
		            </div>
		        </div>
		        <!-- MAP -->
		        <div class="col-md-8">
		            <div class="map">
		                <iframe style="width:100%;height: 100%; margin:0; padding:0; border: none" src="https://maps.google.sk/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Unicorn+Theatre,+Tooley+Street,+London,+United+Kingdom&amp;aq=0&amp;oq=unicorn+the&amp;sll=48.135866,17.115917&amp;sspn=0.334518,0.693512&amp;ie=UTF8&amp;hq=Unicorn+Theatre,&amp;hnear=Tooley+St,+London,+United+Kingdom&amp;t=m&amp;z=16&amp;iwloc=A&amp;output=embed"></iframe>
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
		$instance['exploretitle']	= strip_tags( $new_instance['exploretitle'] );
		$instance['gmap_zoom']		= strip_tags( $new_instance['gmap_zoom'] );
		
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
	
		$exploretitle	= isset( $instance['exploretitle'] ) ? $instance['exploretitle'] : '';
	    $gmap_zoom		= isset( $instance['gmap_zoom'] ) ? $instance['gmap_zoom'] : '';?>
	    
	    <em><?php _e('Widget Title:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'exploretitle' ); ?>" value="<?php echo $exploretitle; ?>"/>
	    <em><?php _e('Google Map Zoom:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'gmap_zoom' ); ?>" value="<?php echo $gmap_zoom; ?>" placeholder="From 1 to 21"/>
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" /><?php
		
	}
	
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
function tyler_home_pois_fields($fields) {
	
	global $wpdb;
	return $fields . ", $wpdb->postmeta.meta_value AS tyler_address, mt2.meta_value AS tyler_latitude, mt1.meta_value AS tyler_longitude";
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
function tyler_frontend_scripts() {?>
	
	<script type="text/javascript">
	    
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';<?php
	    
	    // Get Theme Options
		$ef_options = EF_Event_Options::get_theme_options();
		
		$color_scheme = empty( $ef_options['ef_color_palette'] ) ? 'basic' : $ef_options['ef_color_palette'];
		
		add_filter( 'posts_fields', 'tyler_home_pois_fields' );
		
		$pois = get_posts(
					array(
						'post_type'			=> 'poi',
						'posts_per_page'	=> -1,
						'suppress_filters'	=> false,
						'meta_query'		=> array(
												array(
													'key'		=> 'poi_address',
													'compare'	=> 'EXISTS',
												),
												array(
													'key'		=> 'poi_latitude',
													'compare'	=> 'EXISTS',
												),
												array(
													'key'		=> 'poi_longitude',
													'compare'	=> 'EXISTS',
												)
											)
					)
				);
		
		remove_filter('posts_fields', 'tyler_home_pois_fields');
		
	    $pois_arr	= array();
	    
	    foreach( $pois as $poi ) {
			$pois_arr[] = array(
				'poi_address'	=> sprintf('<strong>%s</strong><br/>%s', $poi->post_title, $poi->poi_address),
				'poi_latitude'	=> $poi->poi_latitude,
				'poi_longitude'	=> $poi->poi_longitude,
				'poi_title'		=> $poi->post_title
			);
	    }?>
	    
		var pois = <?php echo json_encode($pois_arr); ?>;
		var poi_marker = '<?php echo get_stylesheet_directory_uri(); ?>/images/schemes/<?php echo $color_scheme; ?>/icon-map-pointer.png';
		var contact_missingfield_error = <?php echo json_encode(__('Sorry! You\'ve entered an invalid email.', 'tyler')); ?>;
		var contact_wrongemail_error = <?php echo json_encode(__('This field must be filled out.', 'tyler')); ?>;
	</script><?php
}

//add action for frontend scripts
add_action( 'wp_head', 'tyler_frontend_scripts' );

//Register Widget
register_widget( 'Ef_Explore_Widget' );