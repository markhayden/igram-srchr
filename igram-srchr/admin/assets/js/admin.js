(function ( $ ) {
	"use strict";

	$(function () {
		function testQuery() {
			$.get( window.location.protocol + '//' + window.location.host + '/wp-content/plugins/igram-srchr/public/includes/igram-search-endpoint.php?q=snoopdogg&t=true', function( data ) {
				$( "#igramTestOutpt" ).html( data );
				$( "#igramTestOutpt" ).fadeIn();
				$("#igramClickTest").fadeIn();
			});
		}

		$('#igramClickTest').click( function() {
			$("#igramClickTest").fadeOut();
			$( "#igramTestOutpt" ).fadeIn();
			testQuery();
		});

	});

}(jQuery));