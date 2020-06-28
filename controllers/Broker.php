<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Jasny\SSO\NotAttachedException;
use Jasny\SSO\Exception as SsoException;

class Broker extends Core_Controller {

	public $sso_server = "";
	public $sso_broker_id = "";
	public $sso_broker_secret = "";

	function __construct()
	{
		parent::__construct();
		$this->sso_server = $this->get_config("sso_server");
		$this->sso_broker_id = $this->get_config("sso_broker_id");
		$this->sso_broker_secret = $this->get_config("sso_broker_secret");
	}

	function index()
	{
		if (isset($_GET['sso_error'])) {
			header("Location: error.php?sso_error=" . $_GET['sso_error'], true, 307);
			exit;
		}

		$broker = new Jasny\SSO\Broker($this->sso_server, $this->sso_broker_id, $this->sso_broker_secret);
		$broker->attach(true);

		try {
			$user = $broker->getUserInfo();
			$data["user"] = $user;
		} catch (NotAttachedException $e) {
			header('Location: ' . $_SERVER['REQUEST_URI']);
			exit;
		} catch (SsoException $e) {
			header("Location: error.php?sso_error=" . $e->getMessage(), true, 307);
		}

		if (!$user) {
			header("Location: broker/login", true, 307);
			exit;
		}

		$data["broker"] = $broker;
		$this->view("broker/index.php", $data);
	}

	function login()
	{
		$broker = new Jasny\SSO\Broker($this->sso_server, $this->sso_broker_id, $this->sso_broker_secret);
		$broker->attach(true);

		//var_dump($broker);
		//echo $_POST['username'];
		//echo $_POST['password'];
		//$result = $broker->login($_POST['username'], $_POST['password']);
		//var_dump($result);
		try {
			if (!empty($_GET['logout'])) {
				$broker->logout();
			} elseif ($broker->getUserInfo() || ($_SERVER['REQUEST_METHOD'] == 'POST' && $broker->login($_POST['username'], $_POST['password']))) {
				header("Location: index", true, 303);
				exit;
			}

			if ($_SERVER['REQUEST_METHOD'] == 'POST') $errmsg = "Login failed";
		} catch (NotAttachedException $e) {
			header('Location: ' . $_SERVER['REQUEST_URI']);
			exit;
		} catch (Jasny\SSO\Exception $e) {
			$errmsg = $e->getMessage();
		}

		$data["broker"] = $broker;
		$this->view("broker/login.php", $data);
	}

	function logout()
	{
		$broker = new Jasny\SSO\Broker($this->sso_server, $this->sso_broker_id, $this->sso_broker_secret);
		$broker->attach(true);

		try {
			$broker->logout();

			header("Location: login", true, 307);
			exit;
		} catch (Jasny\SSO\Exception $e) {
			$errmsg = $e->getMessage();
		}

	}

	function userinfo($uid, $fname)
	{
		echo "uid => ".$uid."<br>";
		echo "fname => ".$fname."<br>";
	}
}
