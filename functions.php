<?php
// Add to functions.php
// Workaround to import (product) images WordPress Woocommerce: Error: A valid URL was not provided.
function wpspeedexpert_allow_unsafe_urls($args, $url) {
 	$args['reject_unsafe_urls'] = false;
 	return $args;
 }
 add_filter('http_request_args', 'wpspeedexpert_allow_unsafe_urls', 20, 2 );
