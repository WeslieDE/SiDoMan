<?php
date_default_timezone_set("Europe/Berlin");
header('Strict-Transport-Security: max-age=657000');
error_reporting(E_ALL);
session_start();

$RUNTIME = array();

include_once("classen/HTML.php");
include_once("classen/helper.php");
include_once("classen/docker.php");

$_SESSION['CONATINER'] = "Game.OpenSimulator.Twix";
$_SESSION['LOGIN'] = "true";

if(isset($_REQUEST['logout']))
	if($_REQUEST['logout'] == '1')
		$_SESSION = array();

if(isset($_SESSION['LOGIN']))
	if($_SESSION['LOGIN'] == 'true')
	{
		if(!isset($_REQUEST['page']))
			$_REQUEST['page'] = 'dashboard';

		if(file_exists("./pages/".$_REQUEST['page'].".php")){
			if($_REQUEST['page'] == str_replace("/"," ",$_REQUEST['page']) and $_REQUEST['page'] == str_replace("\\"," ",$_REQUEST['page']) and $_REQUEST['page'] == str_replace(".."," ",$_REQUEST['page'])){
					include "./pages/".$_REQUEST['page'].".php";
			}else{
				include "./pages/error.php";
			}
		}else{
			include "./pages/error.php";
		}

		die();
	}

	if(file_exists("./pages/login.php")){
		include "./pages/login.php";
	}else{
		include "./pages/error.php";
	}

?>