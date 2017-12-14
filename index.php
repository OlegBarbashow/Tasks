<?php
error_reporting(E_ALL);
function __autoload($fileName){
	if(file_exists(__DIR__.'/c/' .$fileName.'.php')){
		return include_once(__DIR__.'/c/' .$fileName.'.php');
	}
	elseif(file_exists(__DIR__.'/m/' . $fileName.'.php')){
		return include_once(__DIR__.'/m/' . $fileName.'.php');
	}
}

$control = null;

if(isset($_GET['c'])){
	$control = $_GET['c'];
}

switch($control){
	case 'edit':
		$controller = new C_Edit();
		break;
	case 'editor':
		$controller = new C_Editor();
		break;
	case 'new':
		$controller = new C_New();
		break;
	case 'view':
		$controller = new C_View();
		break;
	case 'login':
		$controller = new C_Authorization();
		break;
	default:
		$controller = new C_Index();
}

$controller->request();