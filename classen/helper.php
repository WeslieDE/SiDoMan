<?php
function clean($string)
{
    $string = str_replace("", "", $string);
    $string = str_replace("\r", "", $string);
    $string = str_replace("\n\n", "\n", $string);
    return preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $string);
}

function calcAPIKey($container)
{
    global $RUNTIME;

    return md5($RUNTIME['SYSTEMKEY'].md5(trim(ltrim($container['Names']['0'], '/'))));
}

if(!file_exists("./pages/systemkey.txt"))
{
	$randomKey	=	md5(rand(111111111, 999999999));
	file_put_contents("./pages/systemkey.txt", $randomKey);
}

$RUNTIME['SYSTEMKEY'] = file_get_contents("./pages/systemkey.txt");

?>