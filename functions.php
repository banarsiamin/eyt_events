<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );



if(isset($_REQUEST['cron'])){
	echo"<h3>Cron job is running.....</h3>";
	cronJob_email();
	cronJob_email_before_one_day();
	die;
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
		$days_w = date('Y-m-d',strtotime("+7 days"));
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
	//echo $sql;
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

function cronJob_email_before_one_day(){
	global $wpdb;
	$postmeta	 				= $wpdb->prefix . 'postmeta';
	$ayt_events 				= $wpdb->prefix . 'ayt_events';
	$ayt_eventsData 			= $wpdb->prefix . 'ayt_eventsData';
	$newsletter 				= $wpdb->prefix . 'newsletter';
	$newsletter_emails 			= $wpdb->prefix . 'newsletter_emails';
	$days_w = date('Y-m-d',strtotime("+1 days"));
	$status = isset($_REQUEST['status'])?$_REQUEST['status']:'';
	$event_id = isset($_REQUEST['event_id'])?$_REQUEST['event_id']:'';

	if(!empty($event_id)){
		$sql ="SELECT * FROM `$ayt_eventsData` WHERE `ayt_eventsData_id`='$event_id' ";
	}else if(!empty($status)){
		$sql ="SELECT * FROM `$ayt_eventsData` WHERE `ayt_eventsData_postDATE`='$days_w' ";
	}else{
		$sql ="SELECT * FROM `$ayt_eventsData` WHERE `ayt_eventsData_postDATE`='$days_w'";
	}
	//echo $sql;
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
			$emailT 					= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $newsletter_emails WHERE id = 9" ) );
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
		}
	}
}
