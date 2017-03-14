<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the speakers Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Speakers_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Speakers_Widget extends WP_Widget {
	
	/**
	 * Speakers Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Speakers_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_speakers', 'description' => __( 'Shows a section displaying speakers created in the Speakers custom post type.', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_speakers', $widget_name . __( ' Speaker List', 'dxef' ), $widget_ops );
		
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
		
		$speakerstitle			= isset( $instance['speakerstitle'] ) ? $instance['speakerstitle'] : '';
		$speakersubtitle		= isset( $instance['speakerssubtitle'] ) ? $instance['speakerssubtitle'] : '';
		$speakersviewprofiletext= isset( $instance['speakersviewprofiletext'] ) ? $instance['speakersviewprofiletext'] : '';
		$speakersviewalltext	= isset( $instance['speakersviewalltext'] ) ? $instance['speakersviewalltext'] : '';
		$speakerslist			= isset( $instance['speakerslist'] ) ? $instance['speakerslist'] : array();
		
		if( ! empty( $speakerslist ) ) {
			$speakerslist = array_filter( $speakerslist );
		}
		
		$speaker_args			= array(
										'post_type'		=> 'speaker',
										'posts_per_page'=> 9,
										'post__in'		=> $speakerslist,
										'orderby'		=> 'post__in'
									);
		
		$speakers_chunks	= array_chunk( get_posts( $speaker_args ), 3 );
		$speakers_chunks	= apply_filters( 'multievent_filter_posts_ef_speakers', $speakers_chunks, $speaker_args, $instance );
		
		$full_speakers_page = get_posts(
									array(
										'post_type'		=> 'page',
										'meta_key'		=> '_wp_page_template',
										'meta_value'	=> 'speakers.php'
									)
								);
		
	    echo stripslashes( $args['before_widget'] );
	    ?>
	    <!-- SPEAKERS -->
	    <div id="tile_speakers" class="container widget">
	        <h2><?php echo stripslashes($speakerstitle); ?></h2>
	        <?php if ($full_speakers_page && count($full_speakers_page) > 0) { ?>
	            <a href="<?php echo get_permalink($full_speakers_page[0]->ID); ?>" class="btn btn-primary btn-header pull-right hidden-xs"><?php echo stripslashes($speakersviewalltext); ?></a>
	        <?php } ?>
	        <h3><?php echo stripslashes($speakersubtitle); ?></h3>
	        <div class="speakers carousel slide" data-ride="carousel" data-interval="false" id="speakers-carousel">
	            <!-- Indicators -->
	            <ol class="carousel-indicators">
	                <?php
	                for ($i = 0; $i < ceil(count($speakerslist) / 3); $i++) {
	                    ?>
	                    <li data-target="#speakers-carousel" data-slide-to="<?php echo $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>
	                    <?php
	                }
	                ?>
	            </ol>
	            <div class="carousel-inner">
	                <?php foreach ($speakers_chunks as $key => $speakers_chunk) { ?>
	                    <div class="item<?php if ($key == 0) echo ' active'; ?>">
	                        <?php foreach ($speakers_chunk as $speaker) { ?>
	                            <div class="speaker<?php if (get_post_meta($speaker->ID, 'speaker_keynote', true) == 1) echo ' featured'; ?>">
	                                <a class="speaker-inner" href="<?php echo get_permalink($speaker->ID); ?>">
	                                    <span class="photo">
	                                        <?php echo get_the_post_thumbnail($speaker->ID, 'tyler-speaker'); ?>
	                                    </span>
	                                    <span class="name"><span class="text-fit"><?php echo get_the_title($speaker->ID); ?></span></span>
	                                    <span class="description"><?php echo $speaker->post_excerpt; ?></span>
	                                    <span class="view">
	                                        <?php echo stripslashes($speakersviewprofiletext); ?> <i class="icon-angle-right"></i>
	                                    </span>
	                                </a>
	                            </div>
	                        <?php } ?>
	                    </div>
	                <?php } ?>
	            </div>
	        </div>
	        <?php if ($full_speakers_page && count($full_speakers_page) > 0) { ?>
	            <div class="text-center visible-xs">
	                <a href="<?php echo get_permalink($full_speakers_page[0]->ID); ?>" class="btn btn-primary btn-header"><?php echo stripslashes($speakersviewalltext); ?></a>
	            </div>
	        <?php } ?>
	    </div>
	    <?php
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
        $instance['speakerstitle']				= strip_tags( $new_instance['speakerstitle'] );
		$instance['speakerssubtitle']			= strip_tags( $new_instance['speakerssubtitle'] );
		$instance['speakersviewprofiletext']	= strip_tags( $new_instance['speakersviewprofiletext'] );
		$instance['speakersviewalltext']		= strip_tags( $new_instance['speakersviewalltext'] );
		$instance['speakerslist']				= $new_instance['speakerslist'] ;
		
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
		
        $speakerstitle				= isset( $instance['speakerstitle'] ) ? $instance['speakerstitle'] : '';
        $speakerssubtitle			= isset( $instance['speakerssubtitle'] ) ? $instance['speakerssubtitle'] : '';
        $speakersviewprofiletext	= isset( $instance['speakersviewprofiletext'] ) ? $instance['speakersviewprofiletext'] : '';
        $speakersviewalltext		= isset( $instance['speakersviewalltext'] ) ? $instance['speakersviewalltext'] : '';
        
		?>
		
		<em><?php _e('Title:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'speakerstitle' ); ?>" value="<?php echo $speakerstitle; ?>"/>
		<br /><br />
		<em><?php _e('Subtitle:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'speakerssubtitle' ); ?>" value="<?php echo $speakerssubtitle; ?>"/>
		<br /><br />
		<em><?php _e('"View profile" Text:', 'dxef'); ?></em><br/>
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'speakersviewprofiletext' ); ?>" value="<?php echo $speakersviewprofiletext; ?>"/>
		<br /><br />
		<em><?php _e('"View all speakers" Text:', 'dxef'); ?></em><br/>
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'speakersviewalltext' ); ?>" value="<?php echo $speakersviewalltext; ?>"/>
		<br /><br />
		<em><?php _e('Speakers ID list:', 'dxef'); ?></em><br/>
		
		<style type="text/css">
			#speakers_list li {display:inline;}
			#speakers_list li input {width:30%;}
			#speakers_list li.separator {display:block;margin:3px 0;}
		</style>
		
		<ul id="speakers_list"><?php
			for ($i = 0; $i < 9; $i++) {
				if ($i == 0 || $i % 3 == 0) { ?>
					<li class="separator"><?php echo __( 'Row', 'dxef' ); ?> <?php echo $i / 3 + 1; ?></li><?php
				} ?>
				<li><input type="text" name="<?php echo $this->get_field_name( 'speakerslist' ); ?>[<?php echo $i;?>]" value="<?php if( isset( $instance['speakerslist'][$i] ) ) echo $instance['speakerslist'][$i];?>"/></li> <?php
			}?>
		</ul><?php 
	}
}

// Register Widget
register_widget( 'Ef_Speakers_Widget' );