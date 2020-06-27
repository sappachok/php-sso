<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function get_instance() {
	return Core_Controller::get_instance();
}

function base_url($url) {
	$base_url = get_instance()->get_config('base_url');
	return $base_url."/".$url;
}

?>