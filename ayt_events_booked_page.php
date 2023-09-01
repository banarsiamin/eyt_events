<?php 

global $wpdb;
$ayt_events = $wpdb->prefix . 'ayt_events';
$ayt_eventsData = $wpdb->prefix . 'ayt_eventsData';
global $current_user; 
$current_role = $current_user->roles[0];
$hom_url =  home_url();

 ?>
<div id="icon-themes" class="icon32"><br></div>
<h2 class="nav-tab-wrapper">Booked events</h2>
<div class="wrapmycustomdiv" style="min-height:35px;">
<style>div#example_wrapper, div#example1_wrapper {
    margin-top: 30px;
    padding: 0 15px;
}</style>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' type='text/css' media='all' />
<link rel='stylesheet' href='https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css' media='all' />
<script type='text/javascript' src='https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js'></script>
<?php
$args = array('numberposts' => -1,'post_type'=> 'event','order'=> 'ASC','post_status' => 'publish');
$user_article = get_posts( $args );
$sql = "SELECT * FROM `$ayt_events`  LEFT JOIN `$ayt_eventsData`  ON `$ayt_eventsData`.`ayt_eventsData_emailID`=`$ayt_events`.`ayt_events_email` ORDER BY `$ayt_eventsData`.`ayt_eventsData_postDATE` AND `$ayt_eventsData`.`ayt_eventsData_postTIME` DESC";
$eventsRes = $wpdb->get_results($sql);

?>
<table id="example" class="table table-striped table-bordered" style="width:100%;">
    <thead>
        <tr>
            <th>Email</th>
            <th>Address</th>
            <th>Event Title</th>
            <th>Date/time</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($eventsRes as $evets){ 
        // echo "<PRE>";print_r($evets);echo "</PRE>";
    	$ayt_eventsData_postID      = isset($evets->ayt_eventsData_postID)?$evets->ayt_eventsData_postID:'';
        $ayt_eventsData_postDATE    = isset($evets->ayt_eventsData_postDATE)?$evets->ayt_eventsData_postDATE:'';
        $ayt_eventsData_postTIME    = isset($evets->ayt_eventsData_postTIME)?$evets->ayt_eventsData_postTIME:'';
        $ayt_events_email           = isset($evets->ayt_events_email)?$evets->ayt_events_email:'';
        $ayt_eventsData_street 		= isset($evets->ayt_eventsData_street)?$evets->ayt_eventsData_street:'';
        $ayt_eventsData_city 		= isset($evets->ayt_eventsData_city)?$evets->ayt_eventsData_city:'';
        $ayt_eventsData_zipcode  	= isset($evets->ayt_eventsData_zipcode)?$evets->ayt_eventsData_zipcode:'';
        
    ?>            
    <tr>
        <td><?php echo $ayt_events_email; ?></td>
        <td><?php echo $ayt_eventsData_street; ?> <?php echo $ayt_eventsData_city; ?> <?php echo $ayt_eventsData_zipcode; ?></td>
        <td><a href="<?php echo $hom_url.'/wp-admin/post.php?post='.$user_art->ID.'&action=edit&classic-editor=1'; ?>"><?php echo get_the_title($ayt_eventsData_postID); ?></a></td>
        <td><?php echo $ayt_eventsData_postDATE; ?> <?php echo $ayt_eventsData_postTIME; ?></td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<script>
    jQuery(document).ready(function() {
    jQuery('#example').DataTable({"order": [[ 3, "desc" ]],});
} );
</script>

</div>