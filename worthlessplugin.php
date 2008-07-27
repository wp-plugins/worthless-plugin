<?php
/*
Plugin Name: Worthless Plugin
Plugin URI: http://meandmymac.net/plugins/worthless-plugin/
Description: A worthless plugin doing worthless things.
Author: Arnan de Gans
Version: 1.0.1
Author URI: http://meandmymac.net
*/ 

#---------------------------------------------------
# Load plugin and values
#---------------------------------------------------
add_action('wp_footer', 'worthless_undertaker', 101);
add_action('admin_menu', 'worthless_menu_pages');
add_action('wp_meta', 'worthless_meta');

if(isset($_POST['worthless_submit_options'])) {
	add_action('init', 'worthless_options_submit');
}
worthless_check_config();
$worthless_config 	= get_option('worthless_config');

/*-------------------------------------------------------------
 Name:      worthless_menu_pages

 Purpose:   Dashboard pages
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function worthless_menu_pages() {
	add_submenu_page('options-general.php', 'Worthless Plugin', 'Worthless Plugin', 'manage_options', 'worthlessplugin', 'worthless_options_page');
}

/*-------------------------------------------------------------
 Name:      worthless_options_page

 Purpose:   Admin options page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function worthless_options_page() {
	$worthless_config = get_option('worthless_config');
?>
	<div class="wrap">
	  	<h2>Worthless Actions options</h2>
	  	<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
	    	<input type="hidden" name="worthless_submit_options" value="true" />
			<?php wp_nonce_field('update-options'); ?>

	    	<table class="form-table">
			<tr valign="top">
				<td colspan="4" bgcolor="#DDD">This page is for some number crunching. And one worthless setting!</td>
			</tr>
			<tr valign="top">
				<th scope="row">Busy?</th>
		        <td colspan="3">
		        <select name="worthless_saved">
		     	   	<option value="10000" <?php if($worthless_config['saved'] == "10000") { echo 'selected'; } ?>>10000 or more</option>
			        <option value="5000" <?php if($worthless_config['saved'] == "5000") { echo 'selected'; } ?>>5000-9999</option>
			        <option value="3000" <?php if($worthless_config['saved'] == "3000") { echo 'selected'; } ?>>3000-4999</option>
			        <option value="1500" <?php if($worthless_config['saved'] == "1500") { echo 'selected'; } ?>>1500-2999</option>
			        <option value="1000" <?php if($worthless_config['saved'] == "1000") { echo 'selected'; } ?>>1000-1499</option>
			        <option value="500" <?php if($worthless_config['saved'] == "500") { echo 'selected'; } ?>>500-999</option>
			        <option value="300" <?php if($worthless_config['saved'] == "300") { echo 'selected'; } ?>>300-499 (default)</option>
			        <option value="100" <?php if($worthless_config['saved'] == "100") { echo 'selected'; } ?>>100-299</option>
			        <option value="1" <?php if($worthless_config['saved'] == "1") { echo 'selected'; } ?>>1-99</option>
				</select> visits on an average day!</td>
			</tr>
			<tr valign="top">
				<th scope="row">Statistics</th>
				<td width="33%">Required hits: <?php echo $worthless_config['event']; ?></td>
				<td width="33%">Current hits: <?php echo $worthless_config['current']; ?></td>
				<td width="34%">Events logged: <?php echo $worthless_config['logged']; ?></td>
			</tr>
			</table>
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
<?php
}	

/* -------------------------------------------------------------
 Name:      worthless_undertaker

 Purpose:   Add some spice to the website by running semi random
 			quota doing absolutely nothing worth mentioning. 
 Receive:   -none-
 Return:	-none-
------------------------------------------------------------- */
function worthless_undertaker() {
	global $worthless_config;
	
	$saved		= $worthless_config['saved'];
	$event		= $worthless_config['event'];
	$logged 	= $worthless_config['logged'];
	$current 	= $worthless_config['current'];

	$option['saved'] = $saved; // Never changes at this point, only from the dashboard
	if($current >= $event) {
		// A number match! Make an event happen!
		$option['event'] 	= worthless_random_seed($saved);
		$option['logged'] 	= $logged+1;
		$option['current']	= 0;
		
		// Initiate a random event
		worthless_random_event($option['logged'], $option['event']);
		
	} else {
		// Nothing to see, count a new hit
		$option['event'] 	= $event;
		$option['logged'] 	= $logged;
		$option['current'] 	= $current+1; 
	}

	update_option('worthless_config', $option);
	
}

/*-------------------------------------------------------------
 Name:      worthless_meta

 Purpose:   Make it happen!
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function worthless_meta() {
	echo "<li>I'm using <a href=\"http://meandmymac.net/plugins/worthless-plugin/\" target=\"_blank\" title=\"Worthless Plugin\">Worthless Plugin</a></li>\n";
}


/*-------------------------------------------------------------
 Name:      worthless_random_event

 Purpose:   Make it happen!
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function worthless_random_event($logged, $event) {
	global $worthless_config;
	
	$email = get_option('admin_email');
	
	$subject = '[Worthless Action] This is a worthless notification';
	
	$message = "Hello,\r\n";
	$message .= "\r\n";
	$message .= "This worthless message is send to you from your blog '".get_option('blogname')."'.\r\n";
	$message .= "Your worthless quota has been reached and reset to a random worthless value!\r\n";
	$message .= "\r\n";
	$message .= "Have a nice day!\r\n";
	$message .= "http://meandmymac.net\r\n";
	
	wp_mail($email, $subject, $message);
}

/*-------------------------------------------------------------
 Name:      worthless_random_seed

 Purpose:   Generate a random seed!
 Receive:   $seed
 Return:    $random_seed
-------------------------------------------------------------*/
function worthless_random_seed($seed) {
	global $worthless_config;
	
	if($seed == 10000) $random_seed = $seed/2+rand(10000,15000)-$seed;
	if($seed == 5000) $random_seed = $seed/2+rand(5000,9999)-$seed;
	if($seed == 3000) $random_seed = $seed/2+rand(3000,4999)-$seed;
	if($seed == 1500) $random_seed = $seed/2+rand(1500,2999)-$seed;
	if($seed == 1000) $random_seed = $seed/2+rand(1000,1499)-$seed;
	if($seed == 500) $random_seed = $seed/2+rand(500,999)-$seed;
	if($seed == 300) $random_seed = $seed/2+rand(300,499)-$seed;
	if($seed == 100) $random_seed = $seed/2+rand(100,299)-$seed;
	if($seed == 1) $random_seed = $seed/2+rand(1,100)-$seed;

	$random_seed = floor($random_seed)*2;

	return $random_seed;
}

/*-------------------------------------------------------------
 Name:      worthless_check_config

 Purpose:   Create or update the options
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function worthless_check_config() {
	// Configuration
	if ( !$option = get_option('worthless_config') ) {
		// Default Options
		$option['saved'] 					= '300';
		$option['event'] 					= '300';
		$option['logged'] 					= '0';
		$option['current'] 					= '0';
		update_option('worthless_config', $option);
	}
}

/*-------------------------------------------------------------
 Name:      worthless_options_submit

 Purpose:   Save options
 Receive:   $_POST
 Return:    -none-
-------------------------------------------------------------*/
function worthless_options_submit() {
	$buffer = get_option('worthless_config');
	
	$seed = $_POST['worthless_saved'];
	
	//options page
	$option['saved']	 			= $seed;
	$option['event']	 			= worthless_random_seed($seed);
	$option['logged']	 			= $buffer['logged'];
	$option['current']	 			= $buffer['current'];
	update_option('worthless_config', $option);
}
?>