<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Latest News Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Footer_Text_Columns_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Latest_News_Widget extends WP_Widget {

	/**
	 * Contact Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Latest_News_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_latest_news', 'description' => __( 'Shows a section displaying the latest four posts', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_latest_news', $widget_name . __( ' Latest News', 'dxef' ), $widget_ops );
		
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
		
		$newstitle = isset( $instance['newstitle'] ) ? $instance['newstitle'] : '';
	    $newssubtitle = isset( $instance['newssubtitle'] ) ? $instance['newssubtitle'] : '';
	    $newsviewalltext = isset( $instance['newsviewalltext'] ) ? $instance['newsviewalltext'] : '';
	    
	    $full_news_page = get_posts(array(
	        'post_type' => 'page',
	        'meta_key' => '_wp_page_template',
	        'meta_value' => 'index.php'
	            ));
	    $news_chunks = array_chunk(get_posts(array(
	                'posts_per_page' =>6,
	            )), 2);
	
	    echo stripslashes($args['before_widget']);
	    ?>
	    <!-- LATEST NEWS -->
	    <div id="tile_news" class="container widget">
	        <h2><?php echo stripslashes($newstitle); ?></h2>
			<?php
			$blog_category_id = get_cat_ID( 'Blog' );
			$categories = get_categories( array( 'type' => 'post' ) );
			
			if ( empty( $blog_category_id ) ) {
				$categories = get_categories( array( 'type' => 'post' ) );
				$blog_category_id = $categories[0];
			}

			// Get the URL of this category
			$blog_category_link = get_category_link( $blog_category_id );
			
			if ( ! empty( $blog_category_link ) ) { 
			?>
	            <a href="<?php echo $blog_category_link; ?>" class="btn btn-primary btn-header pull-right hidden-xs"><?php echo stripslashes($newsviewalltext); ?></a>
			<?php
			}
			?>
				
	        <h3><?php echo stripslashes($newssubtitle); ?></h3>
	        <div class="articles carousel slide" data-ride="carousel" data-interval="false" id="articles-carousel">
	            <!-- Indicators -->
	            <ol class="carousel-indicators">
	                <?php
	                for ($i = 0; $i < count($news_chunks); $i++) {
	                    ?>
	                    <li data-target="#articles-carousel" data-slide-to="<?php echo $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>
	                    <?php
	                }
	                ?>
	            </ol>
	            <!-- Wrapper for slides -->
	            <div class="carousel-inner">
	                <?php foreach ($news_chunks as $key => $news_chunk) { ?>
	                    <div class="item<?php if ($key == 0) echo ' active'; ?>">
	                        <?php
	                        foreach ($news_chunk as $news) {
	                            $news_date = strtotime($news->post_date);
	                            ?>
	                            <article>
	                                <div class="image">
	                                    <a href="<?php echo get_permalink($news->ID); ?>" class="text-fit">
	                                        <span class="date">
	                                            <span class="month"><?php echo date_i18n('M', $news_date); ?></span>
	                                            <span class="day"><?php echo date('d', $news_date); ?></span>
	                                            <span class="year"><?php echo date('Y', $news_date); ?></span>
	                                        </span>
	                                        <?php echo get_the_post_thumbnail( $news->ID, 'tyler-blog-home' ); ?>
	                                    </a>
	                                </div>
	                                <div class="post-content">
	                                    <a href="<?php echo get_permalink($news->ID); ?>" class="text-fit">
	                                        <strong class="heading"><?php echo get_the_title($news->ID); ?></strong>
	                                        <span class="perex">
	                                            <?php 
												$news_content = $news->post_excerpt;
												if ( empty( $news_content ) ) {
													$news_content = $news->post_content;
													$news_content = wp_trim_words( $news_content, 55 );
												}

												echo $news_content;
												?>
	                                        </span>
	                                    </a>
	                                </div>
	                            </article>
	                        <?php } ?>
	                    </div>
	                <?php } ?>
	            </div>
	        </div>
	        <?php if (!empty($newscategory)) { ?>
	            <div class="text-center visible-xs">
	                <a href="<?php echo get_category_link($newscategory); ?>" class="btn btn-primary btn-header"><?php echo stripslashes($newsviewalltext); ?></a>
	            </div>
	        <?php } ?>
	    </div>
	
	    <?php
	    echo stripslashes($args['after_widget']);
		
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
		$instance['newstitle']	= strip_tags( $new_instance['newstitle'] );
		$instance['newssubtitle']	= strip_tags( $new_instance['newssubtitle'] );
		$instance['newsviewalltext']	= strip_tags( $new_instance['newsviewalltext'] );
		
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
	
		$newstitle = isset( $instance['newstitle'] ) ? $instance['newstitle'] : '';
	    $newssubtitle = isset( $instance['newssubtitle'] ) ? $instance['newssubtitle'] : '';
	    $newsviewalltext = isset( $instance['newsviewalltext'] ) ? $instance['newsviewalltext'] : '';
	    
	    ?>
	    <em><?php _e('Title:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name=<?php echo $this->get_field_name( 'newstitle' ); ?>"" value="<?php echo stripslashes($newstitle); ?>" />
	    <br /><br />
	    <em><?php _e('Subtitle:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'newssubtitle' ); ?>" value="<?php echo stripslashes($newssubtitle); ?>" />
	    <br /><br />
	    <em><?php _e('"View all news" Text:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'newsviewalltext' ); ?>" value="<?php echo stripslashes($newsviewalltext); ?>" />
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" /><?php
	}
}

// Register Widget
register_widget( 'Ef_Latest_News_Widget' );