<?
// initialize variables
global $wpdb, $table_prefix;

// initialize wordpress wizardry
if(!isset($wpdb)){
	require_once('../../../../../wp-config.php');
	require_once('../../../../../wp-includes/wp-db.php');
}

require_once( 'igram-search-class.php');

$buffer = get_option( 'igram_query_buffer', '' );
$key = get_option( 'igram_client_id', '' );

if ( $key == '' ) {
	if( $test == true ) {
		echo "You have not entered a valid instagram client id key. Please follow the setup instructions to do so.";
	}
	exit;
}

$tablename = $wpdb->prefix.'postmeta';

$query = $_GET['q'];
$test = $_GET['t'];

// check the last time instagram was pinged for this query
$logtable = $wpdb->prefix.'igram_srchr_log';
$get_query_allowed_sql = $wpdb->get_results("SELECT logged FROM $logtable WHERE igram_query = '{$query}' AND logged > NOW() - INTERVAL $buffer MINUTE ORDER BY logged DESC LIMIT 1");

if ( count($get_query_allowed_sql) > 0  && $test != true) {
	// dont run the query. images are accurate up to five minutes.
	echo "Not allowed to run this query yet, please wait or adjust query interval.";
	exit;
} else {
	// create class instance for instagram
	$searchClass = new performSearch( $key );

	// need to do some shit here to see the user
	$igram_srchr_usernames_table_name = $wpdb->prefix . "igram_srchr_usernames";
	$get_user_id = $wpdb->get_results("SELECT igram_id, igram_username FROM $igram_srchr_usernames_table_name WHERE igram_username = '{$query}' LIMIT 1");
	if ( count($get_user_id) < 1) {
		// go get the user id from instagram
		$user = $searchClass->get_user( $query );

		$user_id = $user->id;
		$username = $user->username;

		$wpdb->query("INSERT INTO $igram_srchr_usernames_table_name (igram_id, igram_username) VALUES ('$user_id', '$username')");

		// make sure its not blank
		if($user_id == ""){
			if( $test == true ) {
				echo "You have not entered a valid instagram username. Please follow the setup instructions to do so.";
			}
			exit;
		}
	}else{
		foreach ( $get_user_id as $user ) {
			$user_id = $user->igram_id;
			break;
		}
	}

	// need to do some shit here once i have the user id
	$images = $searchClass->search( $user_id );

	// check that images were found
	if ( count( $images ) == 0 ) {
		if( $test == true ) {
			echo "The searches were performed successfully but no matching images were found. Please wait for more images or change your query.";
		}
		exit;
	} else {
		if( $test == true ) {
			echo "The search for " . $query . " was performed successfully. " .  count( $images ) . " images added: ";
		}

		foreach ( $images as $image ) {
			$igram_id = $image["igram_id"];
		    $igram_query = $image["igram_query"];
		    $igram_handle = $image["igram_handle"];
		    $igram_low_resolution = $image["igram_low_resolution"];
		    $igram_thumbnail = $image["igram_thumbnail"];
		    $igram_standard = $image["igram_standard"];
		    $igram_url = $image["igram_url"];
		    $igram_posted = date("Y-m-d H:i:s", $image["igram_posted"]);

		    $igram_srchr_table_name = $wpdb->prefix . "igram_srchr";
			$wpdb->query("INSERT INTO $igram_srchr_table_name (igram_id, igram_query, igram_handle, igram_low_resolution, igram_thumbnail, igram_standard, igram_posted) VALUES ('$igram_id', '$igram_query', '$igram_handle', '$igram_low_resolution', '$igram_thumbnail', '$igram_standard', '$igram_posted')");
		}

		$igram_srchr_log_table_name = $wpdb->prefix . "igram_srchr_log";
	}

	$wpdb->query("INSERT INTO $igram_srchr_log_table_name (igram_query) VALUES ('$query')");
}
?>