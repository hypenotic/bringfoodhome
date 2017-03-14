<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the sponsors Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Sponsors_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Sponsors_Widget extends WP_Widget {
	
	/**
	 * Sponsors Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Sponsors_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_sponsors', 'description' => __( 'Shows a section displaying sponsors by tier type created in the Sponsors custom post type.', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_sponsors', $widget_name . __( ' Sponsor List', 'dxef' ), $widget_ops );
		
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
		
		$sponsorstitle		= isset( $instance['sponsorstitle'] ) ? $instance['sponsorstitle'] : '';
		$sponsorssubtitle	= isset( $instance['sponsorssubtitle'] ) ? $instance['sponsorssubtitle'] : '';
		$sponsorsbuttontext	= isset( $instance['sponsorsbuttontext'] ) ? $instance['sponsorsbuttontext'] : '';
		$sponsorsbuttonlink	= isset( $instance['sponsorsbuttonlink'] ) ? $instance['sponsorsbuttonlink'] : '';
		
	    echo stripslashes( $args['before_widget'] );?>
	    
		<!-- SPONSORS -->
		<div id="tile_sponsors" class="container widget">
			
			<h2><?php echo stripslashes($sponsorstitle); ?></h2>
			<a href="<?php echo stripslashes( $sponsorsbuttonlink ); ?>" class="btn btn-primary btn-header pull-right hidden-xs">
				<?php echo stripslashes($sponsorsbuttontext); ?>
			</a>
			<h3><?php echo stripslashes($sponsorssubtitle); ?></h3>
			<br/><?php
			
			$categories	= get_terms( 'sponsor-tier' );
			
                        // tiers sorting
                        $new_tiers = array();
                        foreach ($categories as $tier) {
                            $tier->order = EF_Taxonomy_Helper::ef_get_term_meta('sponsor-tier-metas', $tier->term_id, 'sponsor_tier_order');
                            $new_tiers[$tier->order] = $tier;
                        }
                        ksort($new_tiers, SORT_NUMERIC);
                        $categories = $new_tiers;
                        // -------------

			foreach ( $categories as $category ) { ?>
				
				<h3 class="sponsor"><span><?php echo $category->name; ?></span></h3>
				<div class="sponsors sponsors-lg"><?php
					
					$sponsors_args	= array(
										'posts_per_page'	=> -1,
										'post_type'			=> 'sponsor',
										'tax_query'			=> array(
																	array(
																		'taxonomy'	=> 'sponsor-tier',
																		'field'		=> 'slug',
																		'terms'		=> array( $category->slug )
																	),
																)
									);
					
					$sponsors_chunks	= array_chunk( get_posts( $sponsors_args ), 3 );
					$sponsors_chunks	= apply_filters( 'multievent_filter_posts_ef_sponsors', $sponsors_chunks, $sponsors_args, $instance );
					
					if( !empty( $sponsors_chunks ) ) {
						
						foreach ( $sponsors_chunks as $chunk_key => $sponsors_chunk ) {?>
		
							<div class="item<?php if ($chunk_key == 0) echo ' active'; ?>"><?php
								if( !empty( $sponsors_chunk ) ) {
		
									foreach ( $sponsors_chunk as $sponsors ) {
									
										$link = get_post_meta( $sponsors->ID, 'sponsor_link', true );
		
										echo('<div class="sponsor">');
		
										if( $link ) {
										
											echo ("<a href='$link' title='" . $sponsors->post_title . "' target='_blank'>");
										}
		
										echo get_the_post_thumbnail( $sponsors->ID, 'full' );
		
										if( $link ) {
										
											echo ("</a>");
										}
		
										echo('</div>');
									}
								}?>
							</div><!-- .item --><?php 
						}
					}?>
				</div><!-- .sponsors .sponsors-lg --><?php
			}//end categories foreach loop?>
			
			<div class="text-center visible-xs">
				<a href="<?php echo stripslashes($sponsorsbuttonlink); ?>" class="btn btn-primary btn-header">
					<?php echo stripslashes($sponsorsbuttontext); ?>
				</a>
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
        $instance['sponsorstitle']		= strip_tags( $new_instance['sponsorstitle'] );
		$instance['sponsorssubtitle']	= strip_tags( $new_instance['sponsorssubtitle'] );
		$instance['sponsorsbuttontext']	= strip_tags( $new_instance['sponsorsbuttontext'] );
		$instance['sponsorsbuttonlink']	= strip_tags( $new_instance['sponsorsbuttonlink'] );
		
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
		
        $sponsorstitle		= isset( $instance['sponsorstitle'] ) ? $instance['sponsorstitle'] : '';
        $sponsorssubtitle	= isset( $instance['sponsorssubtitle'] ) ? $instance['sponsorssubtitle'] : '';
        $sponsorsbuttontext	= isset( $instance['sponsorsbuttontext'] ) ? $instance['sponsorsbuttontext'] : '';
        $sponsorsbuttonlink	= isset( $instance['sponsorsbuttonlink'] ) ? $instance['sponsorsbuttonlink'] : '';?>
        
		<em><?php _e('Title:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'sponsorstitle' );?>" value="<?php echo stripslashes($sponsorstitle); ?>"/>
		<br /><br />
		<em><?php _e('Subtitle:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'sponsorssubtitle' );?>" value="<?php echo stripslashes($sponsorssubtitle); ?>"/>
		<br /><br />
		<em><?php _e('Button text:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'sponsorsbuttontext' );?>" value="<?php echo stripslashes($sponsorsbuttontext); ?>"/>
		<br /><br />
		<em><?php _e('Button url:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'sponsorsbuttonlink' );?>" value="<?php echo stripslashes($sponsorsbuttonlink); ?>"/><?php 
		
	}
}

// Register Widget
register_widget( 'Ef_Sponsors_Widget' );