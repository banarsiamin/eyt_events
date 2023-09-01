<?php
   /*
   Plugin Name: Events Newsletter
   Plugin URI: https://p8ls.de
   description: a plugin to create [EventsNewsletter]
   Version: 1.0.0
   Author: Kartik
   Text Domain: ayt_events
   Author URI: https://p8ls.de
   License: GPL2
   */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if this file is accessed directly

define( 'AYTEV_VERSION', '1.0.0' );
define('AYTEVPATH',plugin_dir_path( __FILE__ ));
define('AYTEVBASENAME',dirname(plugin_basename(__FILE__)));
define('AYTEVURL',plugin_dir_url( __FILE__ ));
define( 'AYT_EVENTS', 'ayt_events' );

add_action('plugins_loaded', 'ayt_plugin_init'); 

function ayt_plugin_init() {
	load_plugin_textdomain( 'ayt_events', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}
function ayt_acf_init() {
    acf_init();
}
// add_action('acf/init', 'ayt_acf_init');


function ayt_events_menu(){    
	add_submenu_page('edit.php?post_type=event',
					__("Booked Events",'ayt_events'),
					__("Booked Events",'ayt_events'),
					'manage_options', 
					'ayt_events_booked',
					'ayt_events_booked_page');
}
add_action( 'admin_menu','ayt_events_menu' ); 
require_once AYTEVPATH.'ICS.php';

function ayt_events_booked_page() {
	require_once AYTEVPATH.'ayt_events_booked_page.php';
}

function ayt_events() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$ayt_events = $wpdb->prefix . 'ayt_events';
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$sql = "CREATE TABLE $ayt_events (
		`ayt_events_id` int(121) NOT NULL AUTO_INCREMENT,
		`ayt_events_name` varchar(255) NULL,
		`ayt_events_email` varchar(255) NULL,
		`ayt_events_title` varchar(255) NULL,
		`ayt_events_link` varchar(255) NULL,
		`ayt_events_data` varchar(255) NULL,
		`ayt_events_info` varchar(255) NULL,
		`ayt_events_type` varchar(255) NULL,
		`ayt_events_status` varchar(255) NULL,
		`ayt_events_date` varchar(255) NULL,
		PRIMARY KEY (`ayt_events_id`));";
	dbDelta($sql);
}
register_activation_hook( __FILE__, 'ayt_events' );
	
		
function ayt_eventsData() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$ayt_eventsData = $wpdb->prefix . 'ayt_eventsData';
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$sql = "CREATE TABLE $ayt_eventsData (
	`ayt_eventsData_id` int(121) NOT NULL AUTO_INCREMENT,
	`ayt_eventsData_user_ID` varchar(255) NULL,
	`ayt_eventsData_subcribe_ID` varchar(255) NULL,
	`ayt_eventsData_emailID` varchar(255) NULL,
	`ayt_eventsData_postID` varchar(255) NULL,
	`ayt_eventsData_postDATE` varchar(255) NULL,
    `ayt_eventsData_postTIME` varchar(255) NULL,
    `ayt_eventsData_address1` varchar(255) NULL,
    `ayt_eventsData_address2` varchar(255) NULL,
    `ayt_eventsData_street` varchar(255) NULL,
    `ayt_eventsData_city` varchar(255) NULL,
    `ayt_eventsData_zipcode` varchar(255) NULL,
	`ayt_eventsData_link` varchar(255) NULL,
    `ayt_eventsData_info` varchar(255) NULL,
    `ayt_eventsData_type` varchar(255) NULL,
	`ayt_eventsData_status` varchar(255) NULL,
	`ayt_eventsData_questions` TEXT NULL,
	`ayt_eventsData_questions1` varchar(255) NULL,
	`ayt_eventsData_questions2` varchar(255) DEFAULT 'No',
	`ayt_eventsData_questions3` varchar(255) NULL,
	`ayt_eventsData_profile4` varchar(255) DEFAULT 'Other',
	`ayt_eventsData_date` varchar(255) NULL,
	
	PRIMARY KEY (`ayt_eventsData_id`));";
	dbDelta($sql);
}
register_activation_hook( __FILE__, 'ayt_eventsData' );

add_action( 'init', function() {
	register_post_type( 'event', array(
		'labels' => array(
			'name' => __('Events','ayt_events'),
			'singular_name' => __('Event','ayt_events'),
			'menu_name' => __('Events','ayt_events'),
			'all_items' => __('All Events','ayt_events'),
			'edit_item' => __('Edit Event','ayt_events'),
			'view_item' => __('View Event','ayt_events'),
			'view_items' => __('View Events','ayt_events'),
			'add_new_item' => __('Add New Event','ayt_events'),
			'new_item' => __('New Event','ayt_events'),
			'parent_item_colon' => __('Parent Event:', 'ayt_events'),
			'search_items' => __('Search Events', 'ayt_events'),
			'not_found' => __('No events found', 'ayt_events'),
			'not_found_in_trash' => __('No events found in Trash', 'ayt_events'),
			'archives' => __('Event Archives', 'ayt_events'),
			'attributes' => __('Event Attributes', 'ayt_events'),
			'insert_into_item' => __('Insert into event', 'ayt_events'),
			'uploaded_to_this_item' => __('Uploaded to this event', 'ayt_events'),
			'filter_items_list' => __('Filter events list', 'ayt_events'),
			'filter_by_date' => __('Filter events by date', 'ayt_events'),
			'items_list_navigation' => __('Events list navigation', 'ayt_events'),
			'items_list' => __('Events list', 'ayt_events'),
			'item_published' => __('Event published.', 'ayt_events'),
			'item_published_privately' => __('Event published privately.', 'ayt_events'),
			'item_reverted_to_draft' => __('Event reverted to draft.', 'ayt_events'),
			'item_scheduled' => __('Event scheduled.', 'ayt_events'),
			'item_updated' => __('Event updated.', 'ayt_events'),
			'item_link' => __('Event Link', 'ayt_events'),
			'item_link_description' => __('A link to a event.', 'ayt_events'),
		),
		'public' => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'supports' => array(
			0 => 'title',
			1 => 'author',
			2 => 'editor',
			3 => 'excerpt',
			4 => 'page-attributes',
			5 => 'thumbnail',
			6 => 'custom-fields',
			7 => 'post-formats',
		),
		'taxonomies' => array(
			0 => 'event',
		),
		'delete_with_user' => false,
	));
} );

add_action( 'init', function() {
	register_taxonomy( 'event', array(
		0 => 'event',
	), array(
		'labels' => array(
			'name' =>  __('Events category' ,'ayt_events'),
			'singular_name' =>  __('Event' ,'ayt_events'),
			'menu_name' => __('Events category' ,'ayt_events'),
			'all_items' =>  __('All Events' ,'ayt_events'),
			'edit_item' =>  __('Edit Event' ,'ayt_events'),
			'view_item' =>  __('View Event' ,'ayt_events'),
			'update_item' =>  __('Update Event' ,'ayt_events'),
			'add_new_item' =>  __('Add New Event' ,'ayt_events'),
			'new_item_name' =>  __('New Event Name' ,'ayt_events'),
			'parent_item' =>  __('Parent Event' ,'ayt_events'),
			'parent_item_colon' =>  __('Parent Event:' ,'ayt_events'),
			'search_items' =>  __('Search Events' ,'ayt_events'),
			'not_found' =>  __('No events found' ,'ayt_events'),
			'no_terms' =>  __('No events' ,'ayt_events'),
			'filter_by_item' =>  __('Filter by event' ,'ayt_events'),
			'items_list_navigation' =>  __('Events list navigation' ,'ayt_events'),
			'items_list' =>  __('Events list' ,'ayt_events'),
			'back_to_items' =>  __('← Go to events' ,'ayt_events'),
			'item_link' =>  __('Event Link' ,'ayt_events'),
			'item_link_description' =>  __('A link to a event' ,'ayt_events'),
		),
		'public' => true,
		'hierarchical' => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
	) );
} );




/******** Start Add custom field by ACF plugin */

add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key' => 'group_64d9d171d3943',
		'title' => __('Events TimeSlote' ,'ayt_events'),
		'fields' => array(
			array(
				'key' => 'field_64d9d567ca501',
				'label' => __('TimeSlote' ,'ayt_events'),
				'name' => 'timeslote',
				'aria-label' => '',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'layout' => 'table',
				'pagination' => 0,
				'min' => 0,
				'max' => 0,
				'collapsed' => '',
				'button_label' => 'Add Row',
				'rows_per_page' => 20,
				'sub_fields' => array(
					array(
						'key' => 'field_64ddca1d5f4e1',
						'label' => __('limitation' ,'ayt_events'),
						'name' => 'limitation',
						'aria-label' => '',
						'type' => 'number',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => 50,
						'min' => '',
						'max' => '',
						'placeholder' => '',
						'step' => '',
						'prepend' => '',
						'append' => '',
						'parent_repeater' => 'field_64d9d567ca501',
					),
					/*array(
						'key' => 'field_64e35fcb49966',
						'label' => __('Booked' ,'ayt_events'),
						'name' => 'booked',
						'aria-label' => '',
						'type' => 'number',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => 0,
						'min' => '',
						'max' => '',
						'placeholder' => '',
						'step' => '',
						'prepend' => '',
						'append' => '',
						'parent_repeater' => 'field_64d9d567ca501',
					),*/
					array(
						'key' => 'field_64ddca335f4e2',
						'label' => __('Date' ,'ayt_events'),
						'name' => 'date',
						'aria-label' => '',
						'type' => 'date_picker',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'display_format' => 'd.m.Y',
						'return_format' => 'd.m.Y',
						'first_day' => 1,
						'parent_repeater' => 'field_64d9d567ca501',
					),
					array(
						'key' => 'field_64ddca755f4e3',
						'label' => __('Time' ,'ayt_events'),
						'name' => 'time',
						'aria-label' => '',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '13:00 - 14:00',
						'maxlength' => '',
						'placeholder' => '13:00 - 14:00',
						'prepend' => '',
						'append' => '',
						'parent_repeater' => 'field_64d9d567ca501',
					),
					array(
						'key' => 'field_64ddfb89b661a',
						'label' => __('ID/Password','ayt_events'),
						'name' => 'idpassword',
						'aria-label' => '',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'maxlength' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'parent_repeater' => 'field_64d9d567ca501',
					),
					array(
						'key' => 'field_64ddfb4eb6619',
						'label' => __('Meeting URL','ayt_events'),
						'name' => 'meeting_url',
						'aria-label' => '',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'maxlength' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'parent_repeater' => 'field_64d9d567ca501',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'event',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 1,
	) );
} );


	


/****End Acf field*********** */





if(isset($_GET['cids'])){
	ayt_EventsNewsletter();
	die;
  }
  add_shortcode('EventsNewsletter', 'ayt_EventsNewsletter');
  function ayt_EventsNewsletter( $atts=array() ) {
		
		  ob_start();
		  $args = array(
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post_type'  => 'event',
			'order'=>'DESC',
			'orderby' => 'date',
			'ignore_sticky_posts' => true ,
		  );
		  
		  if(isset($_GET['ami'])){
			echo"<PRE>";print_r($atts);echo"</PRE>";
			echo "<pre>";print_r($args);echo"</pre>";
		  }
		  
		$query = new WP_Query($args);
  
	if ($query->have_posts()){ ?>
		<div class=" event-list ayt-event-style-grid " style="display: flex;column-gap: 12px;padding: 8px;">
				<?php
				while ($query->have_posts()){
				  $query->the_post();
				  global $post;
				  $comment_count=get_comments_number();
				  $author_name = get_the_author();
				  $post_id =  $query->post->ID;
				  $term_obj_list = get_the_terms( $post_id, 'category' );
				  $plc = get_home_url()."/wp-includes/images/media/interactive.png";
				  if(has_post_thumbnail()){
						$plc = wp_get_attachment_url( get_post_thumbnail_id() );
					}
					$rows = get_field('timeslote',$post_id);
				  ?>
					<article id="post-<?php echo $post_id;?>" class="post-item ayt_events post-<?php echo $post_id;?> post type-post status-publish ">
						<div class="post-container">
							<div class="post-thumbnail">
								<div class="sh-ratio">
									<div class="sh-ratio-container">
									<a href="<?php echo get_permalink(); ?>" ><div class="sh-ratio-content" ><img src="<?php echo $plc; ?>" style="width: 206px;" /></a></div>
									</div>
								</div>
							</div>
							<div class="post-content-container">
								<h2 data-id="<?php echo $post_id;?>"><a href="<?php echo get_permalink(); ?>" ><?php echo get_the_title(); ?></a> </h2>
								<div class="post-meta">
									<span class="post-auhor-date"><?php echo get_the_date( 'F d, Y', get_the_ID() );?></span>
								</div>
								<div class="post-content"><?php echo get_the_excerpt(); ?></div>
								<button class="btn btn-lg btn-success" data-toggle="modal" data-target="#ayt_events_<?php echo $post_id;?>"><?php echo __('Book Now' ,'ayt_events');?></button>
							</div>
						</div>
					  <!-- basic modal -->
						<div class="modal fade" id="ayt_events_<?php echo $post_id;?>" tabindex="-1" role="dialog" aria-labelledby="ayt_events_<?php echo $post_id;?>" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title" id="myModalLabel"><?php echo get_the_title(); ?></h4>
										<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
										</button> -->
									</div>
									<?php echo do_shortcode( "[EventsNewsletterForm postid=$post_id]" );?>
								</div>
							</div>
						</div>
					</article>
				<?php }
				  wp_reset_postdata();
				  ?>
			</div>	
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
			<script  src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
			<?php	
	}
  
	return ob_get_clean();
  }


function ayt_footer_ajax_event() {
	?>
	<script type="text/javascript">
		/* add your js code here */
		function ayt_get_events(){
			var nonce = 'nonce';
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : "<?php echo admin_url( 'admin-ajax.php' );?>",
				data : {action: "ayt_events_data",nonce: nonce},
				success: function(response) {}
			});
		}

		function ayt_get_events_dateTime(postID,postDATE=''){
			var postID = postID;
			var postDATE = postDATE;
			var nonce = 'nonce';
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : "<?php echo admin_url( 'admin-ajax.php' );?>",
				data : {action: "ayt_events_data",nonce: nonce,postID:postID,postDATE:postDATE},
				success: function(response) {
					jQuery("#ayt_time_"+postID).html(response.html)
				}
			});
		}

		function ayt_emailID_check(postID,emailID=''){
			
			var postID = postID;
			var emailID = emailID;
			var nonce = 'nonce';

			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var checkEML = regex.test(emailID);

			var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
			if (!filter.test(emailID)) {
				jQuery('#ayt_booknow_'+postID).prop( "disabled", true );
			} else {
				jQuery.ajax({
					type : "post",
					dataType : "json",
					url : "<?php echo admin_url( 'admin-ajax.php' );?>",
					data : {action: "ayt_emailID_check",nonce: nonce,postID:postID,emailID:emailID},
					success: function(response) {
						if(response.html=='yes'){
							jQuery('#ayt_booknow_'+postID).prop( "disabled", false );
							jQuery("#ayt_emailID_check"+postID).html("<?php echo __('Email match verify successfully' ,'ayt_events');?>").css('color','green');
						}else{
							jQuery('#ayt_booknow_'+postID).prop( "disabled", true );
							jQuery("#ayt_emailID_check"+postID).html("<?php echo __('The email ID does not match.' ,'ayt_events');?>").css('color','red');
						}				
					}
				});
			}

		}


		function ayt_event_save(postID,event=''){
			// event.preventDefault();
			jQuery("#ayt_save_msg_"+postID).html("").css('color','green');

			var postID = postID;
			var emailID = jQuery('#ayt_email_'+postID).val();
			var postDATE = jQuery('#ayt_date_'+postID).val();
			var postTIME = jQuery('#ayt_time_'+postID).val();
			
			var ayt_eventsData_address1 = jQuery('#ayt_eventsData_address1'+postID).val();
			var ayt_eventsData_address2 = jQuery('#ayt_eventsData_address2'+postID).val();
			var ayt_eventsData_street = jQuery('#ayt_eventsData_street_'+postID).val();
			var ayt_eventsData_city = jQuery('#ayt_eventsData_city_'+postID).val();
			var ayt_eventsData_zipcode = jQuery('#ayt_eventsData_zipcode_'+postID).val();
			var ayt_eventsData_questions1 = jQuery('#ayt_eventsData_questions1_'+postID).val();
			var ayt_eventsData_questions2 = jQuery('#ayt_eventsData_questions2_'+postID).val();
			var ayt_eventsData_questions3 = jQuery('#ayt_eventsData_questions3_'+postID).val();

			var nonce = 'nonce';
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : "<?php echo admin_url( 'admin-ajax.php' );?>",
				data : {
						action: "ayt_event_save",
						nonce: nonce,
						postID:postID,
						emailID:emailID,
						postDATE:postDATE,
						postTIME:postTIME,
						ayt_eventsData_address1:ayt_eventsData_address1,
						ayt_eventsData_address2:ayt_eventsData_address2,
						ayt_eventsData_street:ayt_eventsData_street,
						ayt_eventsData_city:ayt_eventsData_city,
						ayt_eventsData_questions1:ayt_eventsData_questions1,
						ayt_eventsData_questions2:ayt_eventsData_questions2,
						ayt_eventsData_questions3:ayt_eventsData_questions3,
						ayt_eventsData_zipcode:ayt_eventsData_zipcode
					},
				success: function(response) {
					if(response.html=='yes'){
						// jQuery('#ayt_booknow_'+postID).prop( "disabled", false );
						jQuery("#ayt_save_msg_"+postID).html("<?php echo __('Event booked successfully' ,'ayt_events');?>").css('color','green');
					}else if(response.html=='empty'){
						jQuery("#ayt_save_msg_"+postID).html("<?php echo __('Please enter valid details' ,'ayt_events');?>").css('color','red');

					}else if(response.html=='email'){
						jQuery("#ayt_save_msg_"+postID).html("<?php echo __('The email ID does not match.' ,'ayt_events');?>").css('color','red');

					}else{
						// jQuery('#ayt_booknow_'+postID).prop( "disabled", true );
						jQuery("#ayt_save_msg_"+postID).html("<?php echo __('You have already booked this Event data updated' ,'ayt_events');?>").css('color','red');
					}				
				}
			});
		}
		
	</script>
	<?php
}
add_action( 'wp_footer', 'ayt_footer_ajax_event' );


function ayt_events_data() {
	$postID=isset($_REQUEST['postID'])?$_REQUEST['postID']:'';
	$postDATE=isset($_REQUEST['postDATE'])?$_REQUEST['postDATE']:'';
	$postTIME=isset($_REQUEST['postTIME'])?$_REQUEST['postTIME']:'';
	$option ='';
	if(!empty($postID)){
		$args = array(
			'post_type' => 'event',
			'p' => $postID,   // id of the post you want to query
		);
		$query = new WP_Query($args);
		if ($query->have_posts()){ 
			$Select_lable = __('-Select-' ,'ayt_events');
			$option .="<option value=''>$Select_lable</option>";
			while ($query->have_posts()){
				$query->the_post();
				global $post;
				$post_id =  $query->post->ID;
				$rows = get_field('timeslote');
				if( $rows ) {
					foreach( $rows as $row ) {
						
						$date = isset($row['date'])?$row['date']:'';
						$time = isset($row['time'])?$row['time']:'';
						if(!empty($postID) && empty($postDATE)){
							$option .="<option value='$date'>$date</option>";
						}else if(!empty($postID) && !empty($postDATE) && ($date==$postDATE)){
							$option .="<option value='$time'>$time</option>";
						}
						
					}
				}
			}
		}
	  }else{
			$args = array(
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post_type'  => 'event',
			'order'=>'DESC',
			'orderby' => 'date',
			'ignore_sticky_posts' => true ,
			);
		  $query = new WP_Query($args);
		  if ($query->have_posts()){ 
			$Select_lable = __('-Select event-' ,'ayt_events');
			$option .="<option value=''>$Select_lable</option>";
			  while ($query->have_posts()){
				  $query->the_post();
				  global $post;
				  $post_id =  $query->post->ID;
				  $get_the_title =  get_the_title();
				  $option .="<option value='$post_id'>$get_the_title</option>";
			  }
		  }
	  }

	wp_reset_query();
    echo json_encode(array('html' =>$option));
	die;
}

add_action( 'wp_ajax_nopriv_ayt_events_data', 'ayt_events_data' );
add_action( 'wp_ajax_ayt_events_data', 'ayt_events_data' );


function ayt_emailID_check(){
	$postID=isset($_REQUEST['postID'])?$_REQUEST['postID']:'';
	$postDATE=isset($_REQUEST['postDATE'])?$_REQUEST['postDATE']:'';
	$postTIME=isset($_REQUEST['postTIME'])?$_REQUEST['postTIME']:'';
	$emailID=isset($_REQUEST['emailID'])?$_REQUEST['emailID']:'';
	global $wpdb;
	$ayt_events = $wpdb->prefix . 'ayt_events';
	$option ='';
	if(!empty($postID)){
		$existsEmail = $wpdb->get_results( "SELECT * FROM $ayt_events WHERE `ayt_events_email`='$emailID'");
		if ( !empty($existsEmail)) {
			$option ='yes';
		}else{
			$option ='no';
		}
	  }

	wp_reset_query();
    echo json_encode(array('html' =>$option));
	die;
}
add_action( 'wp_ajax_nopriv_ayt_emailID_check', 'ayt_emailID_check' );
add_action( 'wp_ajax_ayt_emailID_check', 'ayt_emailID_check' );

add_action( 'elementor_pro/forms/new_record',  'thewpchannel_elementor_form_create_new_user' , 10, 2 );
function thewpchannel_elementor_form_create_new_user($record,$ajax_handler){
    $form_name = $record->get_form_settings('form_name');
    //Check that the form is the "create new user form" if not - stop and return;
	$form_id = isset($_REQUEST['form_id'])?$_REQUEST['form_id']:'';
    if ('Neues Formular' == $form_name || '22b92b7'==$form_id) {
		$form_data = $record->get_formatted_data();
		sendLoginDetail();
	}
}
function sendLoginDetail(){
	global $wpdb;
	global $wp;
	$users = $wpdb->prefix . 'users';
	$ayt_events = $wpdb->prefix . 'ayt_events';
	$ayt_eventsData = $wpdb->prefix . 'ayt_eventsData';
	$_aytDATA = isset($_REQUEST['form_fields'])?$_REQUEST['form_fields']:'';
	$first_name = isset($_aytDATA['firstname'])?$_aytDATA['firstname']:'';
	$last_name = isset($_aytDATA['name'])?$_aytDATA['name']:'';
	$fullName = $first_name.' '.$last_name;
	$getEmail  = isset($_GET['email'])?$_GET['email']:'kartikii_121@yopmail.com';
	$email = isset($_aytDATA['email'])?$_aytDATA['email']:$getEmail;
	$ayt_events_data = isset($_aytDATA['field_c29080d'])?$_aytDATA['field_c29080d']:'';
	$username=$email; $password = $email;
	$exists = $wpdb->get_results( "SELECT * FROM $users WHERE `user_email`='$email'");
	if ( empty($exists)) {
		$user = wp_create_user($username,$password,$email);
		wp_update_user(array("ID"=>$user,"first_name"=>$first_name,"last_name"=>$last_name)); // Update the user with the first name and last name
	}
	$existsEmail = $wpdb->get_results( "SELECT * FROM $ayt_events WHERE `ayt_events_email`='$email'");
	if ( !empty($existsEmail)) {
		$wpdb->query("UPDATE $ayt_events SET `ayt_events_name`='$fullName',`ayt_events_data`='$ayt_events_data'	WHERE `ayt_events_email`='$email'");
		$cemail='yes';
	}else{
		$cemail='no';

		$wpdb->insert($ayt_events, array(
			'ayt_events_email' => $email,
			'ayt_events_name' => $fullName,
			'ayt_events_data' => $ayt_events_data,
			'ayt_events_date' =>date('Y-m-d h:i:s'),
		));
	}
	// echo "<PRE>$cemail";print_r($_REQUEST);
	// print_r($existsEmail);
	// die;
	return true;
}



function ayt_event_save(){
	$ayt_eventsData_postID   	= isset($_REQUEST['postID'])?$_REQUEST['postID']:'';
	$ayt_eventsData_postDATE 	= isset($_REQUEST['postDATE'])?$_REQUEST['postDATE']:'';
	$ayt_eventsData_postTIME 	= isset($_REQUEST['postTIME'])?$_REQUEST['postTIME']:'';
	$ayt_eventsData_emailID  	= isset($_REQUEST['emailID'])?$_REQUEST['emailID']:'';
	$ayt_eventsData_address1  	= isset($_REQUEST['ayt_eventsData_address1'])?$_REQUEST['ayt_eventsData_address1']:'';
	$ayt_eventsData_address2  	= isset($_REQUEST['ayt_eventsData_address2'])?$_REQUEST['ayt_eventsData_address2']:'';
	$ayt_eventsData_street 		= isset($_REQUEST['ayt_eventsData_street'])?$_REQUEST['ayt_eventsData_street']:'';
	$ayt_eventsData_city 		= isset($_REQUEST['ayt_eventsData_city'])?$_REQUEST['ayt_eventsData_city']:'';
	$ayt_eventsData_zipcode  	= isset($_REQUEST['ayt_eventsData_zipcode'])?$_REQUEST['ayt_eventsData_zipcode']:'';
	$ayt_eventsData_questions1  = isset($_REQUEST['ayt_eventsData_questions1'])?$_REQUEST['ayt_eventsData_questions1']:'';
	$ayt_eventsData_questions2  = isset($_REQUEST['ayt_eventsData_questions2'])?$_REQUEST['ayt_eventsData_questions2']:'';
	$ayt_eventsData_questions3  = isset($_REQUEST['ayt_eventsData_questions3'])?$_REQUEST['ayt_eventsData_questions3']:'';


	global $wpdb;
	$ayt_events = $wpdb->prefix . 'ayt_events';
	$ayt_eventsData = $wpdb->prefix . 'ayt_eventsData';
	$newsletter 	= $wpdb->prefix . 'newsletter';
	$newsletter_emails 	= $wpdb->prefix . 'newsletter_emails';


	if(!empty($ayt_eventsData_postID) && !empty($ayt_eventsData_emailID) && !empty($ayt_eventsData_postDATE) && !empty($ayt_eventsData_postTIME)){
		$existsEmailvalidetion = $wpdb->get_results( "SELECT * FROM $ayt_events WHERE `ayt_events_email`='$ayt_eventsData_emailID'");
		if ( !empty($existsEmailvalidetion)) {
			$$ayt_eventsData_profile4 = isset($existsEmailvalidetion[0]->ayt_events_data)?$existsEmailvalidetion[0]->ayt_events_data:'Other';
			$wpdb->query("UPDATE $newsletter SET 
						`profile_1`='$ayt_eventsData_street',
						`profile_2`='$ayt_eventsData_city',
						`profile_3`='$ayt_eventsData_zipcode',
						`profile_4`='$ayt_eventsData_profile4',
						`profile_5`='$ayt_eventsData_postID',
						`profile_6`='$ayt_eventsData_postDATE',
						`profile_7`='$ayt_eventsData_postTIME',
						`profile_8`='$ayt_eventsData_questions1',
						`profile_9`='$ayt_eventsData_questions2',
						`profile_10`='$ayt_eventsData_questions3',
						`profile_11`='$ayt_eventsData_address1',
						`profile_12`='$ayt_eventsData_address2',
						`list_2`='1' WHERE `email`='$ayt_eventsData_emailID'");
			$existsEvent_or_not = $wpdb->get_results( "SELECT * FROM $ayt_eventsData WHERE `ayt_eventsData_emailID`='$ayt_eventsData_emailID' AND `ayt_eventsData_postDATE`='$ayt_eventsData_postDATE' AND `ayt_eventsData_postID`='$ayt_eventsData_postID' AND `ayt_eventsData_postTIME`='$ayt_eventsData_postTIME'");
			$u_info 	= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $newsletter WHERE `email`='$ayt_eventsData_emailID'" ) );
			$emailT 	= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $newsletter_emails WHERE id = 4" ) );
			$to 		= $ayt_eventsData_emailID;
			$subject 	= $emailT->subject;
			$body 		= $emailT->message;
			$admin_email = get_option('admin_email');
			$site_title = get_bloginfo( 'name' );
			$name  	  	= isset($u_info->message)?$u_info->message:'demo';
			$surname  	= isset($u_info->surname)?$u_info->surname:'demo';
			$find 		= array("{name}","{surname}","{event-date}","{timeslot}");
			$replace 	= array($name,$surname,$ayt_eventsData_postDATE,$ayt_eventsData_postTIME);
			$msg 		= str_replace($find,$replace,$body);
			$myDT['description']=$subject;
			$myDT['location']="";
			$myDT['name']=$name;
			$times = explode("-",$ayt_eventsData_postTIME);
			$sTime = isset($times[0])?$times[0]:'12:00';
			$eTime = isset($times[1])?$times[1]:'13:00';
			$sDate = date("Y-m-d H:i:s", strtotime("$ayt_eventsData_postDATE $sTime"));
			$eDate = date("Y-m-d H:i:s", strtotime("$ayt_eventsData_postDATE $eTime"));
			$myDT['start']=$sDate;
			$myDT['dtstart']=$sDate;
			$myDT['dtend']= $eDate;
			$myDT['summary']=$subject;
			$myDT['url']='';
			$uploads = wp_upload_dir();
			$path = $uploads['path'];
			$ics = new WP_ICS($myDT);
				
				if (empty($existsEvent_or_not)) {
					$option ='yes';
					// $value_booked = array('field_64d9d567ca501' => array(	array('field_64e35fcb49966' => '10',)),);
					// update_sub_field($group_key, $value_booked, $ayt_eventsData_postID);
					file_put_contents( $path . '/dein-termin.ics', $ics->to_string() );
					$attachments = array( $path . '/dein-termin.ics' );
					$headers = array(
						'Content-Type: text/html; charset=UTF-8',
						"From: $site_title <$admin_email>"
					);
					wp_mail( $to, $subject, $msg, $headers, $attachments );			
					$wpdb->query("INSERT INTO $ayt_eventsData 
							(
								`ayt_eventsData_postID`,
								`ayt_eventsData_postDATE`,
								`ayt_eventsData_postTIME`,
								`ayt_eventsData_emailID`,
								`ayt_eventsData_address1`,
								`ayt_eventsData_address2`,
								`ayt_eventsData_street`,
								`ayt_eventsData_city`,
								`ayt_eventsData_zipcode`,
								`ayt_eventsData_questions1`,
								`ayt_eventsData_questions2`,
								`ayt_eventsData_questions3`,
								`ayt_eventsData_profile4`
								) VALUES (
								'$ayt_eventsData_postID',
								'$ayt_eventsData_postDATE',
								'$ayt_eventsData_postTIME',
								'$ayt_eventsData_emailID',
								'$ayt_eventsData_address1',
								'$ayt_eventsData_address2',
								'$ayt_eventsData_street',
								'$ayt_eventsData_city',
								'$ayt_eventsData_zipcode',
								'$ayt_eventsData_questions1',
								'$ayt_eventsData_questions2',
								'$ayt_eventsData_questions3',
								'$ayt_eventsData_profile4'
							)");
			}else{
				$wpdb->query("UPDATE $ayt_eventsData SET 
							`ayt_eventsData_address1`='$ayt_eventsData_address1',
							`ayt_eventsData_address2`='$ayt_eventsData_address2',
							`ayt_eventsData_street`='$ayt_eventsData_street',
							`ayt_eventsData_city`='$ayt_eventsData_city',
							`ayt_eventsData_zipcode`='$ayt_eventsData_zipcode',
							`ayt_eventsData_questions1`='$ayt_eventsData_questions1',
							`ayt_eventsData_questions2`='$ayt_eventsData_questions2',
							`ayt_eventsData_questions3`='$ayt_eventsData_questions3',
							`ayt_eventsData_profile4`='$ayt_eventsData_profile4'
							WHERE `ayt_eventsData_postID`='$ayt_eventsData_postID' AND 
							`ayt_eventsData_emailID`='$ayt_eventsData_emailID' AND 
							`ayt_eventsData_postDATE`='$ayt_eventsData_postDATE' AND 
							`ayt_eventsData_postTIME`='$ayt_eventsData_postTIME'"
							);
				$option ='no';
			}
		}else{
			$option ='email';
		}
	}else{
		$option ='empty';
	}
	wp_reset_query();
    echo json_encode(array('html' =>$option));
	die;
}
add_action( 'wp_ajax_nopriv_ayt_event_save', 'ayt_event_save' );
add_action( 'wp_ajax_ayt_event_save', 'ayt_event_save' );


function ayt_event_single_page( $content ) {
	

	$custom_content = $content;
	if ( is_single() && 'event' == get_post_type() ) {}

	if ( is_singular( 'event' ) ) {
		$custom_content .="<style>main#content {max-width: 1200px;margin: 2em auto;--widgets-spacing: 20px;}h1.entry-title {margin-left: 0 !important;}</style>";
        $custom_content .= do_shortcode( '[EventsNewsletterForm page=1]' );
	}
	return $custom_content;
}
add_filter( 'the_content', 'ayt_event_single_page' );

add_shortcode('EventsNewsletterForm', 'ayt_event_news_form');
function ayt_event_news_form($atts= array(), $content = null ) {
    $atts = shortcode_atts( array('postid' =>'','page' =>'list' ), $atts ,'EventsNewsletterForm');	
	ob_start();
	$page = !empty($atts['page'])?$atts['page']:'list';
	$postid = !empty($atts['postid'])?$atts['postid']:get_the_ID();
	$post_id = !empty($atts['postid'])?$atts['postid']:get_the_ID();
	$rows = get_field('timeslote',$post_id);

	$lang = get_bloginfo("language"); 
	$q3 ='What is your favorite color?';
	$q2 = 'Have you ever owned a Google Pixel?';
	$q1 ='What is your favorite food';
	if ($lang == 'de-DE'){
		$q1 ='Was ist dein Lieblingsessen';
		$q2 ='Hattest du schon einmal ein Google Pixel?';
		$q3 ='Was ist deine Lieblingsfarbe?';
	}
	?>

	<form id="EventsNewsletterForm" class="EventsNewsletterForm">
		<style>.ayt_star {color: red;font-size: 25px;position: absolute;}</style>
		<div class="modal-body">
			<div class="form-group">
				<label for="ayt_email_<?php echo $post_id;?>"><?php echo __('Email address' ,'ayt_events');?> <span class="ayt_star">*</span></label>
				<input type="email" class="form-control" id="ayt_email_<?php echo $post_id;?>" aria-describedby="emailHelp" placeholder="<?php echo __('Enter email' ,'ayt_events');?>" onkeyup="ayt_emailID_check('<?php echo $post_id;?>',this.value);" onkeypress="ayt_emailID_check('<?php echo $post_id;?>',this.value);" required  pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}">
				<small id="ayt_emailID_check<?php echo $post_id;?>" class="form-text text-muted"><?php echo __('Please enter only registered subscribed email id.' ,'ayt_events');?></small>
			</div>
			<div class="form-group">
				<label for="ayt_date_<?php echo $post_id;?>"><?php echo __('Select Date' ,'ayt_events');?><span class="ayt_star">*</span></label>
				<select class="form-control" id="ayt_date_<?php echo $post_id;?>" onchange="ayt_get_events_dateTime('<?php echo $post_id;?>',this.value)">
				<option value=""><?php echo __('-Select Date-' ,'ayt_events');?></option>
				<?php 
				if( $rows ) {
					foreach( $rows as $key=>$row ) {
						$date = isset($row['date'])?$row['date']:'';
						echo "<option value='$date'>$date</option>";
					}
				}
				?>
				</select>
			</div>
			<div class="form-group">
				<label for="ayt_time_<?php echo $post_id;?>"><?php echo __('Select Time' ,'ayt_events');?><span class="ayt_star">*</span></label>
				<select class="form-control" id="ayt_time_<?php echo $post_id;?>">
				<option value=""><?php echo __('-Select time-' ,'ayt_events');?></option>
				</select>
			</div>
			<div class="form-group">
			<h6 title="<?php echo get_the_title( $post_id ); ?>"><?php echo __('If you want to receive a Pixel Launch Show Kit, please enter your address data here now* *Only while stocks last.' ,'ayt_events');?></h6>
				<label for="ayt_eventsData_address1<?php echo $post_id;?>"><?php echo __('Adresszeile 1' ,'ayt_events');?></label>
				<input type="text" class="form-control" id="ayt_eventsData_address1<?php echo $post_id;?>" placeholder="<?php echo __('Adresszeile 1' ,'ayt_events');?>" require>
			</div>

			<div class="form-group">
				<label for="ayt_eventsData_address2<?php echo $post_id;?>"><?php echo __('Adresszeile 2' ,'ayt_events');?></label>
				<input type="text" class="form-control" id="ayt_eventsData_address2<?php echo $post_id;?>" placeholder="<?php echo __('Adresszeile 2' ,'ayt_events');?>" require>
			</div>

			<div class="form-group">
				<label for="ayt_eventsData_street_<?php echo $post_id;?>"><?php echo __('Street + number' ,'ayt_events');?></label>
				<input type="text" class="form-control" id="ayt_eventsData_street_<?php echo $post_id;?>" placeholder="<?php echo __('Enter Street' ,'ayt_events');?>" require>
			</div>

			<div class="form-group">
				<label for="ayt_eventsData_zipcode_<?php echo $post_id;?>"><?php echo __('Zip code' ,'ayt_events');?></label>
				<input type="text" class="form-control" id="ayt_eventsData_zipcode_<?php echo $post_id;?>" placeholder="<?php echo __('Enter Zipcode' ,'ayt_events');?>" require>
			</div>

			<div class="form-group">
				<label for="ayt_eventsData_city_<?php echo $post_id;?>"><?php echo __('City' ,'ayt_events');?></label>
				<input type="text" class="form-control" id="ayt_eventsData_city_<?php echo $post_id;?>" placeholder="<?php echo __('Enter City' ,'ayt_events');?>" require>
			</div>		

			<div class="form-group">
				<label for="ayt_eventsData_questions1_<?php echo $post_id;?>"><?php echo __($q1 ,'ayt_events');?></label>
				<input type="text" class="form-control" id="ayt_eventsData_questions1_<?php echo $post_id;?>" placeholder="<?php echo __($q1 ,'ayt_events');?>" require>
			</div>

			<div class="form-group">
				<label for="ayt_eventsData_questions2_<?php echo $post_id;?>"><?php echo __($q2 ,'ayt_events');?></label>
				<select class="form-control" id="ayt_eventsData_questions2_<?php echo $post_id;?>">
					<!-- <option value=""><?php echo __('-Select-' ,'ayt_events');?></option> -->
					<option value=""><?php echo __('-Select-' ,'ayt_events');?></option>
					<option value="Yes"><?php echo __('Yes' ,'ayt_events');?></option>
					<option value="No"><?php echo __('No' ,'ayt_events');?></option>
				</select>
			</div>

			<div class="form-group">
				<label for="ayt_eventsData_questions3_<?php echo $post_id;?>"><?php echo __($q3 ,'ayt_events');?></label>
				<select class="form-control" id="ayt_eventsData_questions3_<?php echo $post_id;?>">
					<option value=""><?php echo __('-Select-' ,'ayt_events');?></option>
					<option value="Blue"><?php echo __('Blue' ,'ayt_events');?></option>
					<option value="Red"><?php echo __('Red' ,'ayt_events');?></option>
					<option value="White"><?php echo __('White' ,'ayt_events');?></option>
					<option value="Black"><?php echo __('Black' ,'ayt_events');?></option>
				</select>
			</div>

			

			
		</div>
		<div class="modal-footer">
			<?php if(($page!='1')){?>
			<button type="<?php echo ($page=='list')?'button':'reset';?>" class="btn btn-danger" data-dismiss="modal"><?php echo ($page=='list')?'Close':'cancel';?></button>
			
			<?php }else{?>
				<style>
					.EventsNewsletterForm .form-group{margin-bottom: 10px;}
					.ayt_button_wi{width: 100%;margin-top: 15px;}
				</style>
			<?php
			}?>
			<button type="button"  class="btn btn-primary ayt_button_wi" id="ayt_booknow_<?php echo $post_id;?>" onclick="ayt_event_save('<?php echo $post_id;?>',this)"><?php echo __('Book Now' ,'ayt_events');?></button>
		</div>
		<div id="ayt_save_msg_<?php echo $post_id;?>" style="color: green;text-align: center;padding-bottom: 5px;font-size: 21px;"></div>
	</form>
	<?php 
	return ob_get_clean();	
}

/*


if(isset($_REQUEST['cron'])){
	cronJob_email();
}
function cronJob_email(){
	global $wpdb;
	$postmeta	 				= $wpdb->prefix . 'postmeta';
	$ayt_events 				= $wpdb->prefix . 'ayt_events';
	$ayt_eventsData 			= $wpdb->prefix . 'ayt_eventsData';
	$newsletter 				= $wpdb->prefix . 'newsletter';
	$newsletter_emails 			= $wpdb->prefix . 'newsletter_emails';
	$days_w = isset($_REQUEST['date'])?$_REQUEST['date']:'';
	if(!empty($days_w)){
		$days_w = isset($_REQUEST['date'])?$_REQUEST['date']:'';
	}else{
		$days_w = date('Y-m-d',strtotime("+2 days"));
	}
	$status = isset($_REQUEST['status'])?$_REQUEST['status']:'';
	$event_id = isset($_REQUEST['event_id'])?$_REQUEST['event_id']:'';

	if(!empty($event_id)){
		$sql ="SELECT * FROM `$ayt_eventsData` WHERE `ayt_eventsData_id`='$event_id' ";
	}else if(!empty($status)){
		$sql ="SELECT * FROM `$ayt_eventsData` WHERE `ayt_eventsData_postDATE`='$days_w' ";
	}else{
		$sql ="SELECT * FROM `$ayt_eventsData` WHERE `ayt_eventsData_postDATE`='$days_w' AND `ayt_eventsData_status` IS NULL";
	}
	$existsEvent_or_not = $wpdb->get_results( $sql );
	if (!empty($existsEvent_or_not)) {
		foreach($existsEvent_or_not as $evkey =>$eventDT){
			$ayt_eventsData_postID   	= isset($eventDT->ayt_eventsData_postID)?$eventDT->ayt_eventsData_postID:'';
			$ayt_eventsData_postDATE 	= isset($eventDT->ayt_eventsData_postDATE)?$eventDT->ayt_eventsData_postDATE:'';
			$ayt_eventsData_postTIME 	= isset($eventDT->ayt_eventsData_postTIME)?$eventDT->ayt_eventsData_postTIME:'';
			$ayt_eventsData_emailID  	= isset($eventDT->ayt_eventsData_emailID)?$eventDT->ayt_eventsData_emailID:'';
			$ayt_eventsData_street 		= isset($eventDT->ayt_eventsData_street)?$eventDT->ayt_eventsData_street:'';
			$ayt_eventsData_city 		= isset($eventDT->ayt_eventsData_city)?$eventDT->ayt_eventsData_city:'';
			$ayt_eventsData_zipcode  	= isset($eventDT->ayt_eventsData_zipcode)?$eventDT->ayt_eventsData_zipcode:'';
			$ayt_eventsData_id  		= isset($eventDT->ayt_eventsData_id)?$eventDT->ayt_eventsData_id:'';
			$u_info 					= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $newsletter WHERE `email`='$ayt_eventsData_emailID'" ) );
			$emailT 					= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $newsletter_emails WHERE id = 5" ) );
			$ayt_event_url 				= '';//site_url();
			$ayt_event_id_password 		= "";
			$ayt_rows 					= get_field('timeslote',$ayt_eventsData_postID);
			if( !empty($ayt_rows) && is_array($ayt_rows) ) {
				foreach( $ayt_rows as $key=> $row ) {
					$date = isset($row['date'])?$row['date']:'';
					$time = isset($row['time'])?$row['time']:'';
					if(($date==$ayt_eventsData_postDATE) && ($time==$ayt_eventsData_postTIME)){
							$ayt_event_url = isset($row['meeting_url'])?$row['meeting_url']:'';
							$ayt_event_id_password = isset($row['idpassword'])?$row['idpassword']:'';
						}
				}
			}
			$to 						= $ayt_eventsData_emailID;
			$subject 					= $emailT->subject;
			$body 						= $emailT->message;
			$admin_email 				= get_option('admin_email');
			$site_title 				= get_bloginfo( 'name' );
			$name  	  					= isset($u_info->message)?$u_info->message:'demo';
			$surname  					= isset($u_info->surname)?$u_info->surname:'demo';
			$find 						= array("{name}","{surname}","{event-date}","{timeslot}","{event-url}","{event-id-password}");
			$replace 					= array($name,$surname,$ayt_eventsData_postDATE,$ayt_eventsData_postTIME,$ayt_event_url,$ayt_event_id_password);
			$msg 						= str_replace($find,$replace,$body);
			
			$times = explode("-",$ayt_eventsData_postTIME);
			$sTime = isset($times[0])?$times[0]:'12:00';
			$sDate = date("Y-m-d H:i:s", strtotime("$ayt_eventsData_postDATE $sTime"));
			$eTime = isset($times[1])?$times[1]:'13:00';
			$eDate = date("Y-m-d H:i:s", strtotime("$ayt_eventsData_postDATE $eTime"));
			$myDT['description']=$subject;
			$myDT['location']="";
			$myDT['name']=$name;
			$myDT['start']=$sDate;
			$myDT['dtstart']=$sDate;
			$myDT['dtend']= $eDate;
			$myDT['summary']=$subject;
			$myDT['url']=$ayt_event_url;
			$myDT['id_pwd']=$ayt_event_id_password;
			$myDT['email']=$ayt_eventsData_emailID;
			$uploads = wp_upload_dir();
			$path = $uploads['path'];
			$ics = new WP_ICS($myDT);
			file_put_contents( $path . '/dein-termin.ics', $ics->to_string() );
			$attachments = array( $path . '/dein-termin.ics' );
			$headers = array('Content-Type: text/html; charset=UTF-8',"From: $admin_email");
			if(!empty($ayt_event_url)){
				wp_mail( $to, $subject, $msg, $headers, $attachments );			
			}
			$wpdb->query("UPDATE $ayt_eventsData SET `ayt_eventsData_status`='1' WHERE `ayt_eventsData_id`='$ayt_eventsData_id'");
		}
	}
}

*/