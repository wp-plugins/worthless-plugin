<?php
/*
Plugin Name: Worthless Plugin
Plugin URI: http://meandmymac.net/plugins/worthless-plugin/
Description: A worthless plugin doing worthless things.
Author: Arnan de Gans
Version: 2.0
Author URI: http://meandmymac.net
*/ 

#---------------------------------------------------
# Load plugin and values
#---------------------------------------------------
register_activation_hook(__FILE__, 'worthless_activate');
register_deactivation_hook(__FILE__, 'worthless_deactivate');
worthless_check_config();

add_action('wp_footer', 'worthless_undertaker', 101);
add_action('admin_menu', 'worthless_dashboard');
add_action('wp_meta', 'worthless_meta');

if(isset($_POST['worthless_submit_options'])) {
	add_action('init', 'worthless_options_submit');
}

$worthless_config = get_option('worthless_config');
$worthless_tracker = get_option('worthless_tracker');

/*-------------------------------------------------------------
 Name:      worthless_dashboard

 Purpose:   Dashboard pages
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function worthless_dashboard() {
	add_submenu_page('options-general.php', 'Worthless Plugin', 'Worthless Plugin', 'manage_options', 'worthlessplugin', 'worthless_dashboard_options');
}

/*-------------------------------------------------------------
 Name:      worthless_options_page

 Purpose:   Admin options page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function worthless_dashboard_options() {
	$worthless_config = get_option('worthless_config');
	$worthless_tracker = get_option('worthless_tracker');
?>
	<div class="wrap">
	  	<h2>Worthless options</h2>
	  	<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
	    	<input type="hidden" name="worthless_submit_options" value="true" />

	    	<table class="form-table">
			<tr>
				<td colspan="4" bgcolor="#DDD">This page is for some number crunching. And one worthless setting!</td>
			</tr>
			<tr>
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
	    	</table>

	    	<h3>Statistics</h3>	    	
	
	    	<table class="form-table">
			<tr>
				<td width="33%">Required hits: <?php echo $worthless_config['event']; ?></td>
				<td width="33%">Current hits: <?php echo $worthless_config['current']; ?></td>
				<td width="34%">Events logged: <?php echo $worthless_config['logged']; ?></td>
			</tr>
			</table>

	    	<h3>Registration</h3>	    	
	
	    	<table class="form-table">
			<tr>
				<th scope="row" valign="top">Why</th>
				<td>For fun and as an experiment i would like to gather some information and develop a simple stats system for it. I would like to ask you to participate in this experiment. All it takes for you is to not opt-out. More information is found <a href="http://meandmymac.net/plugins/data-project/" title="http://meandmymac.net/plugins/data-project/ - New window" target="_blank">here</a>. Any questions can be directed to the <a href="http://forum.at.meandmymac.net/" title="http://forum.at.meandmymac.net/ - New window" target="_blank">forum</a>.</td>
				
			</tr>
			<tr>
				<th scope="row" valign="top">Participate</th>
				<td><input type="checkbox" name="worthless_register" <?php if($worthless_tracker['register'] == 'Y') { ?>checked="checked" <?php } ?> /> Allow Meandmymac.net to collect some data about the plugin usage and your blog.<br /><em>This includes your blog name, blog address, email address and a selection of triggered events as well as the name and version of this plugin.</em></td>
			</tr>
			<tr>
				<th scope="row" valign="top">Anonymously</th>
				<td><input type="checkbox" name="worthless_anonymous" <?php if($worthless_tracker['anonymous'] == 'Y') { ?>checked="checked" <?php } ?> /> Your blog name, blog address and email will not be send.</td>
			</tr>
			<tr>
				<th scope="row" valign="top">Agree</th>
				<td><strong>Upon activating the plugin you agree to the following:</strong>

				<br />- All gathered information, but not your email address, may be published or used in a statistical overview for reference purposes.
				<br />- You're free to opt-out or to make any to be gathered data anonymous at any time.
				<br />- All acquired information remains in my database and will not be sold, made public or otherwise spread to third parties.
				<br />- If you opt-out or go anonymous, all previously saved data will remain intact.
				<br />- Requests to remove your data or make everything you sent anonymous will not be granted unless there are pressing issues.
				<br />- Anonymously gathered data cannot be removed since it's anonymous.
				</td>
			</tr>
	    	</table>

			<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
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
	global $worthless_config, $worthless_tracker;
	
	$saved		= $worthless_config['saved'];
	$event		= $worthless_config['event'];
	$logged 	= $worthless_config['logged'];
	$current 	= $worthless_config['current'];

	$register 	= $worthless_tracker['register'];

	$option['saved'] = $saved; // Never changes at this point, only from the dashboard
	if($current >= $event) {
		// A number match! Make an event happen!
		$option['event'] 	= worthless_random_seed($saved);
		$option['logged'] 	= $logged+1;
		$option['current']	= 0;
		
		// Initiate a random event
		if($register == 'Y') { worthless_send_data('Event'); }
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
	
	$blogname = get_option('blogname');
	$email = get_option('admin_email');
	
	$subject = '[Worthless Action] This is a worthless notification';
	
	$message = "Hello,\r\n";
	$message .= "\r\n";
	$message .= "This worthless message is send to you from your blog '$blogname'.\r\n";
	$message .= "Your worthless quota has been reached $worthless_config[logged] times now and reset to a random worthless value!\r\n";
	$message .= "\r\n";
	$message .= "Have a nice day!\r\n";
	$message .= "http://meandmymac.net\r\n";
	
	wp_mail($email, $subject, $message);
}

/*-------------------------------------------------------------
 Name:      worthless_send_data

 Purpose:   Register events at meandmymac.net's database
 Receive:   $action
 Return:    -none-
-------------------------------------------------------------*/
function worthless_send_data($action) {
	$worthless_tracker = get_option('worthless_tracker');
	
	// Prepare data
	$date			= date('U');
	$plugin			= 'Worthless Plugin';
	$version		= '2.0';
	//$action -> pulled from function args
	
	// User choose anonymous?
	if($worthless_tracker['anonymous'] == 'Y') {
		$ident 		= 'Anonymous';
		$blogname 	= 'Anonymous';
		$blogurl	= 'Anonymous';
		$email		= 'Anonymous';
	} else {
		$ident 		= md5(get_option('siteurl'));
		$blogname	= get_option('blogname');
		$blogurl	= get_option('siteurl');
		$email		= get_option('admin_email');			
	}
	
	// Build array of data
	$post_data = array (
		'headers'	=> null,
		'body'		=> array(
			'ident'		=> $ident,
			'blogname' 	=> base64_encode($blogname),
			'blogurl'	=> base64_encode($blogurl),
			'email'		=> base64_encode($email),
			'date'		=> $date,
			'plugin'	=> $plugin,
			'version'	=> $version,
			'action'	=> $action,
		),
	);

	// Destination
	$url = 'http://stats.meandmymac.net/receiver.php';

	wp_remote_post($url, $post_data);
}

/*-------------------------------------------------------------
 Name:      worthless_activate

 Purpose:   Activation script
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function worthless_activate() {
	worthless_send_data('Activate');
}

/*-------------------------------------------------------------
 Name:      worthless_deactivate

 Purpose:   Deactivation script
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function worthless_deactivate() {
	worthless_send_data('Deactivate');
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
	if ( !$tracker = get_option('worthless_tracker') ) {
		$tracker['register']				= 'Y';
		$tracker['anonymous']				= 'N';
		update_option('worthless_tracker', $tracker);
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
	$buffer2 = get_option('worthless_tracker');

	
	$seed 		= $_POST['worthless_saved'];
	$register 	= $_POST['worthless_register'];
	$anonymous 	= $_POST['worthless_anonymous'];
	
	if(isset($register)) $register = 'Y';			
		else $register = 'N';
		
	if(isset($anonymous)) $anonymous = 'Y';			
		else $anonymous = 'N';
		
	$option['saved']	 			= $seed;
	$option['event']	 			= worthless_random_seed($seed);
	$option['logged']	 			= $buffer['logged'];
	$option['current']	 			= $buffer['current'];
	update_option('worthless_config', $option);

	$tracker['register']			= $register;
	$tracker['anonymous']			= $anonymous;
	if($tracker['register'] == 'N' AND $buffer2['register'] == 'Y') { worthless_send_data('Opt-out'); }
	update_option('worthless_tracker', $tracker);
}
?>