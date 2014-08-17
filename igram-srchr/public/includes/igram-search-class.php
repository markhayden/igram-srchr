<?
class performSearch {

	private static $client_id;

	public function __construct( $client_id ) {
		self::$client_id = $client_id;
	}

	/**
	* CURL Get
	*/
	private static function hitInstagram($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/**
	* Get User ID
	*/
	public function get_user($username){
		$client = self::$client_id;

		$result = self::hitInstagram("https://api.instagram.com/v1/users/search?q={$username}&client_id={$client}");
		$result = json_decode($result);

		// loop response to find the actual match because instagram sucks
		foreach ($result->data as $post) {
			if($post->username == $username){
				return $post;
			}
		}
	}

	/**
	* Search
	*/
	public function search($user_id){
		$client = self::$client_id;

		$result = self::hitInstagram("https://api.instagram.com/v1/users/{$user_id}/media/recent/?client_id={$client}");
		$result = json_decode($result);

		$output = array();
		foreach ($result->data as $images) {
			array_push(
				$output,
				array(
					"igram_id" => $images->id,
					"igram_query" => $user_id,
					"igram_handle" => $images->user->username,
					"igram_low_resolution" => $images->images->low_resolution->url,
					"igram_thumbnail" => $images->images->thumbnail->url,
					"igram_standard" => $images->images->standard_resolution->url,
					"igram_posted" => $images->created_time
				)
			);
		}

		return $output;
	}
}
?>