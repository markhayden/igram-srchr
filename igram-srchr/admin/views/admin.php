<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   igram_srchr
 * @author    Mark Hayden <hi@markhayden.me>
 * @license   GPL-2.0+
 * @link      https://github.com/markhayden/igram-srchr
 * @copyright 2014 Mark Hayden
 */
?>

<?
	if ( $_POST ) {
		$client_id = $_POST['client_id'];
		$api_secret = $_POST['api_secret'];
		$number_of_images_to_save = $_POST['igram_number_of_images_to_save'];
		$post_types = $_POST['post_types'];
		$query_buffer = $_POST['query_buffer'];

		if ( $client_id !== '' && $api_secret !== '' && $number_of_images_to_save !== '' && $post_types !== '' && $query_buffer !== '' ){
			update_option( "igram_client_id", $client_id );
			update_option( "igram_number_of_images_to_save", $number_of_images_to_save );
			update_option( "igram_post_types", $post_types );
			update_option( "igram_query_buffer", $query_buffer );
			$saved = true;
		} else {
			$saved = false;
		}
	}
?>
<div class="wrap">

	<? if ( $saved === true ) { ?>
	<div class="the-day-is-mine">
		Well would you look at that. Everything saved successfully!
	</div>
	<? } ?>

	<? if ( $saved === false ) { ?>
	<div class="you-borked-the-internet">
		Well crap. Something borked. Try again maybe?
	</div>
	<? } ?>

	<form method="post" action="">
		<h2 class="igram-h2"><?php echo esc_html( get_admin_page_title() ); ?></h2>

		<h3 class="igram-h3">Granting Plugin Access to Instagram</h3>
		<p class="igram-p">
			For the igram srchr plugin to work properly you must grant it access to Instagram's api via an application. To do this follow these steps to obtain an Client ID from Instagram.
			<ol>
				<li>Visit: <a href="http://instagram.com/developer/" target="blank">Instagram Developer Center</a></li>
				<li>Log in with any Instagram account. This will not grant access to any account information, you just have to be a Instagram user to create an app.</li>
				<li>Visit: <a href="http://instagram.com/developer/clients/manage/" target="blank">Instagram Client Management Area</a></li>
				<li>Click the "Register a New Client" button at the top right.</li>
				<li>Fill in the required fields. This information is not accessible from the plugin but is useful in debugging and managing the app. OAuth redirect_url can be the same as your "Website". All defaults can be left as is.</li>
				<li>You will now see a list of access credentials including your Client ID, Client Secret, etc. Copy and paste your Client ID in the field below.</li>
			</ol>
		</p>
		<table class="igram-table">
			<tr>
				<td class="igram-right"><label for="client_id">Client ID: </label></td>
				<td><input name="client_id" type="text" value="<?php echo get_option( 'igram_client_id' ); ?>"></td>
			</tr>
		</table>

		<h3 class="igram-h3">Automated Search Settings</h3>
		<p class="igram-p">For each query performed we can control how many images are returned. The smaller the number the quicker queries / processing is performed. Ideally this will be the amount of images you would like to display.</p>

		<br/>
		<table>
			<tr>
				<td class="igram-right">How many images should we save for each query? </td>
				<td><input name="igram_number_of_images_to_save" type="text" onkeyup="this.value=this.value.replace(/[^\d]/,'')" value="<?php echo get_option( 'igram_number_of_images_to_save' ); ?>"></td>
			</tr>
			<tr>
				<td class="igram-right">Post types (separate with comma, no spaces): </td>
				<td><input name="post_types" type="text" value="<?php echo get_option( 'igram_post_types' ); ?>"> <span>default: post,page</span></td>
			</tr>
			<tr>
				<td class="igram-right">Pull in new images every </td>
				<td><input name="query_buffer" type="text" onkeyup="this.value=this.value.replace(/[^\d]/,'')" value="<?php echo get_option( 'igram_query_buffer' ); ?>"> <span> minutes</span></td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>

	<div>
		<h2>Plugin Testing</h2>
		<p>Use the button below <span class="red">AFTER SAVING</span> to test that everything is working properly. The test should return a success message with an image count if working properly.</p>
		<button id="igramClickTest" class="button button-red">Perform Test</button>
		<p id="igramTestOutpt">Running tests. Please wait...</p>
	</div>
</div>
