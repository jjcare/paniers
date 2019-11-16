<? 
include('db.inc.php'); 
echo "<table border=1 >"; 
echo "<tr>"; 
echo "<td><b>Fnum</b></td>"; 
echo "<td><b>Nip</b></td>"; 
echo "<td><b>Number</b></td>"; 
echo "<td><b>Street</b></td>"; 
echo "<td><b>Appt</b></td>"; 
echo "<td><b>Code</b></td>"; 
echo "<td><b>Tel H</b></td>"; 
echo "<td><b>Tel Alt</b></td>"; 
echo "<td><b>Note</b></td>"; 
echo "<td><b>Foyer</b></td>"; 
echo "<td><b>Montant</b></td>"; 
echo "</tr>"; 
$result = mysql_query("SELECT * FROM `families`") or trigger_error(mysql_error()); 
while($row = mysql_fetch_array($result)){ 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); } 
echo "<tr>";  
echo "<td valign='top'>" . nl2br( $row['fnum']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['nip']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['number']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['street']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['appt']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['code']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['tel_h']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['tel_alt']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['note']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['foyer']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['montant']) . "</td>";  
echo "<td valign='top'><a href=edit.inc.php?nip={$row['nip']}>Edit</a></td><td><a href=delete.inc.php?nip={$row['nip']}>Delete</a></td> "; 
echo "</tr>"; 
} 
echo "</table>"; 
echo "<a href=new.inc.php>New Row</a>"; 
?>