<?php
define('BASEPATH',TRUE);
require 'vendor/autoload.php';
require 'core.php';
require 'helper.php';

include 'controllers/Server.php';
include 'controllers/Broker.php';

$controller["server"] = new Server();
$controller["broker"] = new Broker();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$r->addRoute('GET', '/php-sso/', 'server/index');
	$r->addRoute('POST', '/php-sso/', 'server/index');
    //$r->addRoute('GET', '/php-sso/userinfo/{sid}', 'server/userinfo');

    $r->addRoute('GET', '/php-sso/broker', 'broker/index');
	$r->addRoute('GET', '/php-sso/broker/index', 'broker/index');
    $r->addRoute('GET', '/php-sso/broker/login', 'broker/login');
    $r->addRoute('POST', '/php-sso/broker/login', 'broker/login');
    $r->addRoute('GET', '/php-sso/broker/logout', 'broker/logout');
    // {id} must be a number (\d+)
    //$r->addRoute('GET', '/sso/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    //$r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
	//$r->addRoute('GET', '/get-route', 'get_handler');
	//$r->addRoute('POST', '/post-route', 'post_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
		echo $uri."<br>";
		echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
		echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars

		//echo $handler."<br>";
		$ex = explode('/',$handler);

		if(is_array($ex)) {
			$controller[$ex[0]]->$ex[1]();
		} else {
			$controller[$handler]->index();
		}

		//var_dump($vars);
        break;
}
