<?php
//# -*- coding: utf-8 -*-
// helper functions


function html($text)
{
	return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function htmlout($text)
{
	echo html($text);
}

// Utility function to save typing - for input into mysql dbase
function defang ($link, $input) {
	return mysqli_real_escape_string( $link, $input);
}

// utility function to convert dates with French day and month names
function convertDate ( $day = '', $part = 'weekday')
{
	if ($day == '') $day = time();
	$darray = getdate($day);
	if (!in_array($part, $darray)) return false;
	
	switch ($part)
	{
		case 'weekday':
			$convJour = explode( ' ', 'dimanche lundi mardi mercredi jeudi vendredi samedi dimanche');
			$ret = $convJour [ $darray['wday']];
			break;
		case 'month':
			$convMois = explode(' ', 'dummy janvier février mars avril mai juin juillet août septembre octobre novembre décembre');
			$ret = $convMois [ $darray['mon']];
			break;
		default:
			$ret = $darray[$part];
	}
	
	return $ret;
}
function convertAccents ($string) {
    // utility function to normalize strings, ignoring iconv nonsense
    $from = array("À","É","È","Ô","Ç","à","é","è","ô","ç");
    $to   = array("A","E","E","O","C","a","e","e","o","c");
    return str_replace($from, $to, $string);
}

?>
