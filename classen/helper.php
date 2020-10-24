<?php
function clean($string)
{
    $string = str_replace("", "", $string);
    $string = str_replace("\r", "", $string);
    $string = str_replace("\n\n", "\n", $string);
    return preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $string);
}

?>