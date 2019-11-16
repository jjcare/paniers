<?php
$link = mysql_connect('localhost', 'shalom_jjc', '1cor13');
if (!$link)
{
	$output = 'Unable to connect to the database server.';
	include 'output.html.php';
	exit();
}

if (!mysql_set_charset($link, 'utf8'))
{
	$output = 'Unable to set database connection encoding.';
	include 'output.html.php';
	exit();
}

if (!mysql_select_db($link, 'shalom_001'))
{
	$output = 'Unable to locate the joke database.';
	include 'output.html.php';
	exit();
}

$output = 'Database connection established.';
include 'output.html.php';
?>
