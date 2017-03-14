<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Latest Tweets Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Latest_Tweets_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Latest_Tweets_Widget extends WP_Widget {

	/**
	 * Contact Widget setup.
	 * 
	 * @package Latest Tweets
	 * @since 1.0.0
	 */
	function Ef_Latest_Tweets_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_twitter', 'description' => __( 'Shows a section displaying latest tweets', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_twitter', $widget_name . __( ' Latest Tweets', 'dxef' ), $widget_ops );
		
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
		
		global $twitter;
	    
		$twitterlinktext			= isset( $instance['twitterlinktext'] ) ? $instance['twitterlinktext'] : '';		
		$twitterhash				= isset( $instance['twitterhash'] ) ? $instance['twitterhash'] : '';
		$twitteraccesstoken			= isset( $instance['twitteraccesstoken'] ) ? $instance['twitteraccesstoken'] : '';
		$twitteraccesstokensecret	= isset( $instance['twitteraccesstokensecret'] ) ? $instance['twitteraccesstokensecret'] : '';
		$twitterconsumerkey			= isset( $instance['twitterconsumerkey'] ) ? $instance['twitterconsumerkey'] : '';
		$twitterconsumersecret		= isset( $instance['twitterconsumersecret'] ) ? $instance['twitterconsumersecret'] : '';		
			
	    $full_twitter_page = get_posts(array(
	        'post_type' => 'page',
	        'meta_key' => '_wp_page_template',
	        'meta_value' => 'twitter.php'
	            ));
	    $tweets = array();
	
	    if (isset($twitter) && !empty($twitterhash)) {
	        $url = 'https://api.twitter.com/1.1/search/tweets.json';
	        $getfield = "?q=#$twitterhash&count=4";
	        $requestMethod = 'GET';
	        $store = $twitter->setGetfield($getfield)
	                ->buildOauth($url, $requestMethod)
	                ->performRequest();
	        $tweets = json_decode($store);
	    }
	
	    echo stripslashes($args['before_widget']);
	    ?>
	    <!-- TWITTER -->
	    <div id="tile_twitter" class="container widget">
	        <div class="row twitter">
	            <div class="col-md-4">
	                <div class="view bg-twitter">
	                    <i class="icon-twitter-alt"></i>
	                    <div class="view-inner">
	                        <iframe id="twitter-widget-0" src="http://platform.twitter.com/widgets/tweet_button.1384994725.html#_=1385458781193&amp;button_hashtag=<?php echo $twitterhash; ?>&amp;count=none&amp;id=twitter-widget-0&amp;lang=en&amp;size=m&amp;type=hashtag" class="twitter-hashtag-button twitter-tweet-button twitter-hashtag-button twitter-count-none" title="Twitter Tweet Button" data-twttr-rendered="true" style="width: 118px; height: 20px; margin:0; padding:0; border:none;"></iframe>
	                        <?php if ($full_twitter_page && count($full_twitter_page) > 0) { ?>
	                            <a href="<?php echo get_permalink($full_twitter_page[0]->ID); ?>"><?php echo stripslashes($twitterlinktext); ?> <i class="icon-angle-right"></i></a>
	                        <?php } ?>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-8">
	                <div class="tweet featured">
	                    <?php
	                    if ( ! empty( $tweets ) &&  property_exists( $tweets, 'statuses' ) && count( $tweets->statuses ) > 0 ) {
	                        ?>
	                        <img class="avatar" src="<?php echo $tweets->statuses[0]->user->profile_image_url; ?>" alt="<?php echo $tweets->statuses[0]->user->name; ?>">
	                        <div class="text text-fit">
	                            <?php echo $tweets->statuses[0]->text; ?>
	                        </div>
	                        <div class="date">
	                            <?php echo getRelativeTime($tweets->statuses[0]->created_at); ?>
	                        </div>
	                    <?php } ?>
	                </div>
	            </div>
	        </div>
	
	        <div class="row twitter hidden-xs">
	            <?php
	            if ( ! empty( $tweets ) &&  property_exists( $tweets, 'statuses' ) && count( $tweets->statuses ) > 1) {
	                for ($i = 1; $i <= 3; $i++) {
	                    if (isset($tweets->statuses[$i])) {
	                        ?>
	                        <div class="col-sm-4">
	                            <div class="tweet bg-gray">
	                            	<img class="avatar" src="<?php echo $tweets->statuses[$i]->user->profile_image_url; ?>" alt="<?php echo $tweets->statuses[$i]->user->name; ?>">
	                                <div class="text text-fit">
	                                    <?php echo $tweets->statuses[$i]->text; ?>
	                                </div>
	                                <div class="date">
			                            <?php echo getRelativeTime($tweets->statuses[0]->created_at); ?>
	                                </div>
	                            </div>
	                        </div>
	                        <?php
	                    }
	                }
	            }
	            ?>
	        </div>
	    </div><?php
	    
	    echo stripslashes($args['after_widget']);
		
	}
	
	/**
	 * Update Widget Setting
	 * 
	 * Handle to updates the widget control options
	 * for the particular instance of the widget
	 * 
	 * @package Latest Tweets
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {

		if (isset($_POST['submitted'])) {
			update_option('ef_twitter_widget_twitterlinktext', isset( $new_instance['twitterlinktext'] ) ? $new_instance['twitterlinktext'] : '' );
			update_option('ef_twitter_widget_twitterhash', isset( $new_instance['twitterhash'] ) ? $new_instance['twitterhash'] : '' );
			update_option('ef_twitter_widget_accesstoken', isset( $new_instance['twitteraccesstoken'] ) ? $new_instance['twitteraccesstoken'] : '' );
			update_option('ef_twitter_widget_accesstokensecret', isset( $new_instance['twitteraccesstokensecret'] ) ? $new_instance['twitteraccesstokensecret'] : '' );
			update_option('ef_twitter_widget_consumerkey', isset( $new_instance['twitterconsumerkey'] ) ? $new_instance['twitterconsumerkey'] : '' );
			update_option('ef_twitter_widget_consumersecret', isset( $new_instance['twitterconsumersecret'] ) ? $new_instance['twitterconsumersecret'] : '' );
		}
		
		$instance = $old_instance;
		
		/* Set the instance to the new instance. */
		$instance = $new_instance;
		
		/* Input fields */
		$instance['twitterlinktext']			= strip_tags( $new_instance['twitterlinktext'] );
		$instance['twitterhash']				= strip_tags( $new_instance['twitterhash'] );
		$instance['twitteraccesstoken']			= strip_tags( $new_instance['twitteraccesstoken'] );
		$instance['twitteraccesstokensecret']	= strip_tags( $new_instance['twitteraccesstokensecret'] );
		$instance['twitterconsumerkey']			= strip_tags( $new_instance['twitterconsumerkey'] );
		$instance['twitterconsumersecret']		= strip_tags( $new_instance['twitterconsumersecret'] );
		
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
		
		$twitterlinktext			= isset( $instance['twitterlinktext'] ) ? $instance['twitterlinktext'] : '';		
		$twitterhash				= isset( $instance['twitterhash'] ) ? $instance['twitterhash'] : '';
		$twitteraccesstoken			= isset( $instance['twitteraccesstoken'] ) ? $instance['twitteraccesstoken'] : '';
		$twitteraccesstokensecret	= isset( $instance['twitteraccesstokensecret'] ) ? $instance['twitteraccesstokensecret'] : '';
		$twitterconsumerkey			= isset( $instance['twitterconsumerkey'] ) ? $instance['twitterconsumerkey'] : '';
		$twitterconsumersecret		= isset( $instance['twitterconsumersecret'] ) ? $instance['twitterconsumersecret'] : '';?>
		
	    <em><?php _e('Link Text:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'twitterlinktext' ); ?>" value="<?php echo stripslashes($twitterlinktext); ?>"/><br/>
	    <br /><br />
	    <em><?php _e('Event Hashtag Keyword:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'twitterhash' ); ?>" value="<?php echo stripslashes($twitterhash); ?>"/><br/>
	    <small><?php _e('(Leave out the #)', 'dxef'); ?></small>
	    <br /><br />
	    <em><?php _e('Access Token:', 'dxef'); ?></em><br />
	    <input type="text" class="twitteraccesstoken" name="<?php echo $this->get_field_name( 'twitteraccesstoken' ); ?>" value="<?php echo stripslashes($twitteraccesstoken); ?>"/>
	    <br /><br />
	    <em><?php _e('Access Token Secret:', 'dxef'); ?></em><br />
	    <input type="text" class="twitteraccesstokensecret" name="<?php echo $this->get_field_name( 'twitteraccesstokensecret' ); ?>" value="<?php echo stripslashes($twitteraccesstokensecret); ?>"/>
	    <br /><br />
	    <em><?php _e('Consumer Key:', 'dxef'); ?></em><br />
	    <input type="text" class="twitterconsumerkey" name="<?php echo $this->get_field_name( 'twitterconsumerkey' ); ?>" value="<?php echo stripslashes($twitterconsumerkey); ?>"/>
	    <br /><br />
	    <em><?php _e('Consumer Secret:', 'dxef'); ?></em><br />
	    <input type="text" class="twitterconsumersecret" name="<?php echo $this->get_field_name( 'twitterconsumersecret' ); ?>" value="<?php echo stripslashes($twitterconsumersecret); ?>"/>
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" /><?php
	}
}

// Register Widget
register_widget( 'Ef_Latest_Tweets_Widget' );