<? 
include('db.inc.php'); 
if (isset($_GET['nip']) ) { 
$nip = (int) $_GET['nip']; 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "UPDATE `families` SET  `fnum` =  '{$_POST['fnum']}' ,  `number` =  '{$_POST['number']}' ,  `street` =  '{$_POST['street']}' ,  `appt` =  '{$_POST['appt']}' ,  `code` =  '{$_POST['code']}' ,  `tel_h` =  '{$_POST['tel_h']}' ,  `tel_alt` =  '{$_POST['tel_alt']}' ,  `note` =  '{$_POST['note']}' ,  `foyer` =  '{$_POST['foyer']}' ,  `montant` =  '{$_POST['montant']}'   WHERE `nip` = '$nip' "; 
mysql_query($sql) or die(mysql_error()); 
echo (mysql_affected_rows()) ? "Edited row.<br />" : "Nothing changed. <br />"; 
echo "<a href='list.inc.php'>Back To Listing</a>"; 
} 
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `families` WHERE `nip` = '$nip' ")); 
?>

<form action='' method='POST'> 
<p><b>Fnum:</b><br /><input type='text' name='fnum' value='<?= stripslashes($row['fnum']) ?>' /> 
<p><b>Number:</b><br /><input type='text' name='number' value='<?= stripslashes($row['number']) ?>' /> 
<p><b>Street:</b><br /><input type='text' name='street' value='<?= stripslashes($row['street']) ?>' /> 
<p><b>Appt:</b><br /><input type='text' name='appt' value='<?= stripslashes($row['appt']) ?>' /> 
<p><b>Code:</b><br /><input type='text' name='code' value='<?= stripslashes($row['code']) ?>' /> 
<p><b>Tel H:</b><br /><input type='text' name='tel_h' value='<?= stripslashes($row['tel_h']) ?>' /> 
<p><b>Tel Alt:</b><br /><input type='text' name='tel_alt' value='<?= stripslashes($row['tel_alt']) ?>' /> 
<p><b>Note:</b><br /><input type='text' name='note' value='<?= stripslashes($row['note']) ?>' /> 
<p><b>Foyer:</b><br /><input type='text' name='foyer' value='<?= stripslashes($row['foyer']) ?>' /> 
<p><b>Montant:</b><br /><input type='text' name='montant' value='<?= stripslashes($row['montant']) ?>' /> 
<p><input type='submit' value='Edit Row' /><input type='hidden' value='1' name='submitted' /> 
</form> 
<? } ?> 
