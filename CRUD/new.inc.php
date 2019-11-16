<? 
include('db.inc.php'); 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "INSERT INTO `families` ( `fnum` ,  `number` ,  `street` ,  `appt` ,  `code` ,  `tel_h` ,  `tel_alt` ,  `note` ,  `foyer` ,  `montant`  ) VALUES(  '{$_POST['fnum']}' ,  '{$_POST['number']}' ,  '{$_POST['street']}' ,  '{$_POST['appt']}' ,  '{$_POST['code']}' ,  '{$_POST['tel_h']}' ,  '{$_POST['tel_alt']}' ,  '{$_POST['note']}' ,  '{$_POST['foyer']}' ,  '{$_POST['montant']}'  ) "; 
mysql_query($sql) or die(mysql_error()); 
echo "Added row.<br />"; 
echo "<a href='list.inc.php'>Back To Listing</a>"; 
} 
?>

<form action='' method='POST'> 
<p><b>Fnum:</b><br /><input type='text' name='fnum'/> 
<p><b>Number:</b><br /><input type='text' name='number'/> 
<p><b>Street:</b><br /><input type='text' name='street'/> 
<p><b>Appt:</b><br /><input type='text' name='appt'/> 
<p><b>Code:</b><br /><input type='text' name='code'/> 
<p><b>Tel H:</b><br /><input type='text' name='tel_h'/> 
<p><b>Tel Alt:</b><br /><input type='text' name='tel_alt'/> 
<p><b>Note:</b><br /><input type='text' name='note'/> 
<p><b>Foyer:</b><br /><input type='text' name='foyer'/> 
<p><b>Montant:</b><br /><input type='text' name='montant'/> 
<p><input type='submit' value='Add Row' /><input type='hidden' value='1' name='submitted' /> 
</form> 
