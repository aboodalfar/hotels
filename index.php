<?php 

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/application/Config/bootstrap.php';

$CONFIG = Core\ProjectConfig::createInstance($FRAMEWORK_CONFIG);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = urldecode($uri);
ini_set("log_errors", 1);
ini_set("error_log", "error.log");
//FILTER REDIRECT... 
$route_info = Core\Route::process($uri, $FRAMEWORK_CONFIG['ROUTES']);
if(is_array($route_info)){
	list($controller, $action, $params) = $route_info;
}else{
	list($controller, $action, $params) = $route_info['error404'];
}

$controller_name = "Controller\\$controller";
if(class_exists($controller_name) === false){
    header("HTTP/1.0 404 Not Found");
    list($controller, $action, $params) = array('DefaultController', 'error404', array() );
    $controller_name = "Controller\\$controller";    
}

$controller_obj = new $controller_name;
call_user_func(array($controller_obj, $action), $params);
?>
