<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Contact Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Contact_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Contact_Widget extends WP_Widget {
	
	/**
	 * Contact Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Contact_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_contact', 'description' => __( 'Display a Contact Form', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_contact', $widget_name . __( ' Contact Form', 'dxef' ), $widget_ops );
		
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
		
		$contacttitle 			= isset( $instance['contacttitle'] ) ? $instance['contacttitle'] : '';
	    $contactsubtitle 		= isset( $instance['contactsubtitle'] ) ? $instance['contactsubtitle'] : '';
	    $contactsendtext 		= isset( $instance['contactsendtext'] ) ? $instance['contactsendtext'] : '';
	    $contactemail			= isset( $instance['contactemail'] ) ? $instance['contactemail'] : '';
	    $recaptchapublickey		= isset( $instance['recaptchapublickey'] ) ? $instance['recaptchapublickey'] : '';
	    $recaptchaprivatekey	= isset( $instance['recaptchaprivatekey'] ) ? $instance['recaptchaprivatekey'] : '';
	    
	    echo stripslashes( $args['before_widget'] );?>
	    
	    <!-- CONTACT US -->
	    <div id="tile_contact" class="container widget">
	        <h2><?php echo stripslashes($contacttitle); ?></h2>
	        <h3><?php echo stripslashes($contactsubtitle); ?></h3>
	        <form method="post" class="contact-us">
	            <div class="row">
	                <div class="col-sm-4">
	                    <input type="text" name="contactName" placeholder="<?php _e('Name', 'dxef'); ?>" />
	                    <input type="text" name="phone" placeholder="<?php _e('Number', 'dxef'); ?>" />
	                    <input type="text" name="email" placeholder="<?php _e('Email', 'dxef'); ?>" />
	                </div>
	                <div class="col-sm-8">
	                    <textarea name="comments" placeholder="<?php _e('Message', 'dxef'); ?>"></textarea>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-sm-6 col-md-4"><?php
	                    
	                	if( !empty( $recaptchapublickey ) && !empty( $recaptchaprivatekey ) ) {
	                        echo recaptcha_get_html($recaptchapublickey);
	                	}?>
	                </div>
					<div class="col-sm-6 col-md-8">
						<button type="submit" class="btn btn-secondary" style="width: 100%"><?php 
							echo stripslashes($contactsendtext);
						?></button>
					</div>
				</div>
				<input type="hidden" name="recaptcha_publickey" value="<?php echo $recaptchapublickey;?>" />
				<input type="hidden" name="recaptcha_privatekey" value="<?php echo $recaptchaprivatekey;?>" />
				<input type="hidden" name="contact_email" value="<?php echo $contactemail;?>" />
				
				<input type="hidden" name="action" value="send_contact_email" />
				<input type="hidden" name="submitted" id="submitted" value="true" />
			</form>
		</div>
		<style type="text/css">
			@media only screen and (max-width: 40.063em) {
				#recaptcha_area, #recaptcha_table {
					width: 290px !important;
				}
				.recaptchatable #recaptcha_image {
					width: 250px !important;
				}
			}
		</style><?php
	    
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
		$instance['contacttitle']		= strip_tags( $new_instance['contacttitle'] );
		$instance['contactsubtitle']	= strip_tags( $new_instance['contactsubtitle'] );
		$instance['contactsendtext']	= strip_tags( $new_instance['contactsendtext'] );
		$instance['contactemail']		= strip_tags( $new_instance['contactemail'] );
		$instance['recaptchapublickey']	= strip_tags( $new_instance['recaptchapublickey'] );
		$instance['recaptchaprivatekey']= strip_tags( $new_instance['recaptchaprivatekey'] );
		
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
		
		$contacttitle			= isset( $instance['contacttitle'] ) ? $instance['contacttitle'] : '';
		$contactsubtitle		= isset( $instance['contactsubtitle'] ) ? $instance['contactsubtitle'] : '';
		$contactsendtext		= isset( $instance['contactsendtext'] ) ? $instance['contactsendtext'] : '';
		$contactemail			= isset( $instance['contactemail'] ) ? $instance['contactemail'] : '';
		$recaptchapublickey		= isset( $instance['recaptchapublickey'] ) ? $instance['recaptchapublickey'] : '';
		$recaptchaprivatekey	= isset( $instance['recaptchaprivatekey'] ) ? $instance['recaptchaprivatekey'] : '';?>
		
	    <em><?php _e('Title:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'contacttitle' ); ?>" value="<?php echo stripslashes($contacttitle); ?>" />
	    <br /><br />
	    <em><?php _e('Subtitle:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'contactsubtitle' ); ?>" value="<?php echo stripslashes($contactsubtitle); ?>" />
	    <br /><br />
	    <em><?php _e('"Send message" Text:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'contactsendtext' ); ?>" value="<?php echo stripslashes($contactsendtext); ?>" />
	    <br /><br />
	    <em><?php _e('Email Address To Send Forms To:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'contactemail' ); ?>" value="<?php echo stripslashes($contactemail); ?>" />
	    <br /><br />
	    <em><?php _e('Recaptcha Public Key:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'recaptchapublickey' ); ?>" value="<?php echo stripslashes($recaptchapublickey); ?>" />
	    <br /><br />
	    <em><?php _e('Recaptcha Private Key:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'recaptchaprivatekey' ); ?>" value="<?php echo stripslashes($recaptchaprivatekey); ?>" />
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" /><?php
    	
	}
}

// Ajax code for send contect email
add_action('wp_ajax_nopriv_send_contact_email', 'ef_ajax_send_contact_email');
add_action('wp_ajax_send_contact_email', 'ef_ajax_send_contact_email');

/**
 * Ajax Code Contect Email
 * 
 * Handle to send contact
 * email functionality
 * 
 * @package Event Framework
 * @since 1.0.0
 */
function ef_ajax_send_contact_email() {
	
	$ret = array( 'sent' => false, 'error' => false, 'message' => '' );
	
	$recaptchapublickey		= isset( $_POST['recaptcha_publickey'] ) ? $_POST['recaptcha_publickey'] : '';
	$recaptchaprivatekey	= isset( $_POST['recaptcha_privatekey'] ) ? $_POST['recaptcha_privatekey'] : '';
	$contactemail			= isset( $_POST['contact_email'] ) ? $_POST['contact_email'] : '';
	
	if( !empty( $recaptchapublickey ) && !empty( $recaptchaprivatekey ) ) {
		
		$resp = recaptcha_check_answer( $recaptchaprivatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"] );
		
		// check reCaptcha
		if( !$resp || !$resp->is_valid ) {
			
			$ret['message'] = __('The reCAPTCHA wasn\'t entered correctly. Go back and try it again.!', 'dxef');
			$ret['error'] = true;
		}
	}
	
	// require a name from user
	if( trim( $_POST['contactName'] ) === '' ) {
		
		$ret['message']	= __( 'Forgot your name!', 'dxef' );
		$ret['error']	= true;
	} else {
		
		$name	= trim( $_POST['contactName'] );
	}
	
	// need valid email
	if( trim($_POST['email']) === '' ) {
		
		$ret['message'] = __('Forgot to enter in your e-mail address.', 'dxef');
		$ret['error'] = true;
	} else if ( !preg_match( "/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim( $_POST['email'] ) ) ) {
		
		$ret['message'] = __('You entered an invalid email address.', 'dxef');
		$ret['error'] = true;
	} else {
		
		$email = trim($_POST['email']);
	}
	
	$phone = trim( $_POST['phone'] );
	
	// we need at least some content
	if( trim( $_POST['comments'] ) === '' ) {
		
		$ret['message'] = __('You forgot to enter a message!', 'dxef');
		$ret['error'] = true;
	} else {
		
		if( function_exists( 'stripslashes' ) ) {
			
			$comments = stripslashes( trim($_POST['comments']) );
		} else {
			
			$comments = trim( $_POST['comments'] );
		}
	}

	// upon no failure errors let's email now!
	if ( !$ret['error'] ) {
		
		$subject	= __('Submitted message from ', 'dxef') . $name;
		$body		= __('Name:', 'dxef') . " $name \n\n" . __('Email:', 'dxef') . " $email \n\n " . __('Phone:', 'dxef') . " $phone \n\n" . __('Comments:', 'dxef') . " $comments";
		$headers	= 'From: ' . $contactemail . "\r\n" . 'Reply-To: ' . $email . "\r\n";
		
		try {
			
			wp_mail($contactemail, $subject, $body, $headers);
			$ret['sent']	= true;
			$ret['message']	= __('Your email was sent.', 'dxef');
			
		} catch (Exception $e) {
			
			$ret['message']	= __( 'Error submitting the form', 'dxef' );
			
		}
	}
	
	echo json_encode( $ret );
	die;
}

// Register widget
register_widget( 'Ef_Contact_Widget' );