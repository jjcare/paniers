<?php
//# -*- coding: utf-8 -*-
require 'familyClass.php';

$sql = "SELECT * FROM families WHERE nip='${_POST['nip']}'";
if (! $family_set = $link->query ( $sql )) die ("Cannot find family in database. ${_POST['nip']}\n$sql");

if ($a_row = mysql_fetch_assoc( $family_set) ) {
	//$a_row = array_map( "utf8_encode", $a_row);
	$family = new Family ($a_row);
} else die ("Family [${_POST['nip']}] not found in database.");

// now get persons
$sql = "SELECT * FROM dependents ";
$sql .= "LEFT JOIN person_category on dependents.relation = person_category.category ";
$sql .= "where nip='${_POST['nip']}' order by person_category.cid";
$member_set = $link->query ($sql);
if (mysql_num_rows ( $member_set ) > 0 ) {
	while ($a_row = mysql_fetch_assoc ($member_set) ) {
		//$a_row = array_map( "utf8_encode", $a_row);
		//print_r ($a_row); echo "<br />";
		$family->addPerson($a_row);
	}
}


?>
