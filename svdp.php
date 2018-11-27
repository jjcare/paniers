<?php
//# -*- coding: utf-8 -*-
session_start();
// do server side stuff here
//  Create - add a new family and persons
//  Retreive - return all family info
//  Update - modify family info
//  Delete - remove family and persons

@require_once ('pie.php');

// ( $_SESSION['idx'] == session_id() ) or die('Bad session.');

// get the data sent into a useable format
if ( isset ($_GET['data']) ) {
	$item = json_decode ($_GET['data'] );
}

switch ( $_GET['action'] ) {

	case 'show':
	case 'display':  // get household list - families
		$family_set = $link->query ( "SELECT * FROM families" );
		$num_rows = mysql_num_rows ($family_set);
		$return_set = array();
		if ( $num_rows == 0 ) break;
		while ($a_row = mysql_fetch_assoc( $family_set) ) {
			$a_row = array_map( "utf8_encode", $a_row);
			$return_set[] = $a_row;
		}

		// now get persons
		for ( $index=0; $index < count($return_set); $index++ ) {
			$member_set = $link->query ("SELECT * FROM dependents where nip=".$return_set[$index]['nip']);
			if (mysql_num_rows ( $member_set) > 0 ) {
				$return_set[$index]['personnes'] = array();
				while ($a_row = mysql_fetch_assoc ($member_set) ) {
					$a_row = array_map( "utf8_encode", $a_row);  // print_r( $a_row);
					$return_set[$index]['personnes'][] = $a_row;
				}
			}
		}
		break;

	case 'add':
		// detect whether family or dependent is to be added

		// add family


		// add dependent - first must be beneficiary
		$return_set = 'OK';
		break;

	case 'update':
	case 'modify':
		// detect wheter family or dependent

		// modify family

		// modify dependent
		$return_set = 'OK';
		break;

	case 'delete':
		// detect, then delete.
		$return_set = 'OK';
		break;

	default:
		break;
}

// prepare $return_set for sending to client  -  for now, just sent it.

print  "/*-secure-".json_encode($return_set)."*/";


?>
