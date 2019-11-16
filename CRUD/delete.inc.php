<? 
include('db.inc.php'); 
$nip = (int) $_GET['nip']; 
mysql_query("DELETE FROM `families` WHERE `nip` = '$nip' ") ; 
echo (mysql_affected_rows()) ? "Row deleted.<br /> " : "Nothing deleted.<br /> "; 
?> 

<a href='list.inc.php'>Back To Listing</a>