<?php
date_default_timezone_set("Europe/Berlin");
header('Strict-Transport-Security: max-age=657000');
error_reporting(E_ALL);
session_start();

include_once("classen/HTML.php");
include_once("classen/GoogleAuthenticator.php");

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

	include "./pages/login.php";
?>