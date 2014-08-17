<?
class igramSrchFormattr {
	private static $wpdb;

	public function __construct() {
		require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-settings.php' );
		require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

		if (!self::$wpdb) {
		    self::$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
		} else {
		    self::$wpdb;
		}
	}

	public function igram_srch_format( $atts, $content="", $obj ) {
		// set the default date format
		if ( isset( $atts['date'] )) {
			$date_format = $atts['date'];
		} else {
			$date_format = "M j";
		}
		// create array of potential information
		$replacables = array( 'igram_id', 'igram_query', 'igram_handle', 'igram_thumbnail', 'igram_standard', 'igram_low_resolution', 'igram_posted', 'logged' );

		// replace template with values
		foreach ( $replacables as $value ) {
			$query = "{{".$value."}}";
			$drop = $obj->{$value};

			// check if the value is a date. if so, format.
			if ( $value == 'igram_posted' || $value == 'logged' ) {
				$phpdate = strtotime( $drop );
				$drop = date( $date_format, $phpdate );
			}

			$content = str_replace( $query, $drop, $content );
		}

		echo urldecode($content);
		// echo htmlentities($content, ENT_QUOTES);
		// return $content;
	}

	public function igram_srch_func( $wpdb, $table_prefix, $atts, $content="" ) {
		// prepare output variable
		$output = "";

		// check the post for a limit override
		if ( isset($atts['limit']) ) {
			$limit = $atts['limit'];
		} else {
			$limit = 1;
		}

		// get the table name regardless of prefix
		$get_table_name = self::$wpdb->get_results( 'Show tables LIKE "%igram_srchr%"' );
		$table_name = reset($get_table_name{0});

		// get the existing images
		$igram_query_raw = get_post_meta( get_the_ID(), 'igram_search_query');
		$igram_query = $igram_query_raw[0];


		$results = self::$wpdb->get_results( 'SELECT * FROM '.$table_name.' WHERE igram_handle = "'.$igram_query.'" ORDER BY igram_id DESC LIMIT ' . $limit );

		// replace template with values
		foreach ( $results as $value ) {
			$output .= self::igram_srch_format( $atts, $content, $value );
		}

		return $output;
	}
}
?>