<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core_Controller {

	private static $instance;
	private $config = [];

	function __construct()
	{
		self::$instance =& $this;

		include("config.php");
		$this->config = $config;
	}

	function get_config($item)
	{
		if(!empty($this->config[$item]))
		{
			return $this->config[$item];
		} else {
			return null;
		}
	}

	function view($view, $data=[], $echo=true)
	{
		$view_path = __DIR__ . '/views/'.$view;
		if(file_exists($view_path)) {
			extract($data);
			include($view_path);
		} else {
			echo "<p>Not found view file => ".$view."</p>";
		}
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}

?>