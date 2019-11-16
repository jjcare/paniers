<?php
//# -*- coding: utf-8 -*-
session_start();

include_once 'includes/magicquotes.inc.php';
include_once 'includes/helpers.inc.php';
include_once 'includes/db.inc.php';

if ( isset ($_REQUEST['action'] )  or isset($_REQUEST['addfamily']) ) {  // we have a submit request...

// case 1: Request for adding a new family

	if (isset($_REQUEST['addfamily']) or $_REQUEST['action'] == 'ajouter' ) {
		include 'getFamilies.php';  // need nip list
		include 'form.html.php';
		exit();
	}

// case 2: New family info has been submitted  -

	if ( $_REQUEST['action'] == 'Ajouter' ) {
		addFamily();
		include 'getFamilies.php';
		include  'list.html.php';
		exit();
	}

// case 3: Modify existing family - present form

	if ( $_REQUEST['action'] == 'modifier' ) {
		include 'getFamily.php';
		include 'modform.html.php';
		exit();
	} // modify

// case 4: modify request sent - do update (delete old, add new)

	if ( $_REQUEST['action'] == 'Modifier' ) {
		deleteFamily( (int) $_POST['nip'] );
		addFamily();
		include 'getFamilies.php';
		include  'list.html.php';
		exit();
	}

// case 5: delete a family

	if ( $_REQUEST['action'] == "supprimer" ) {
		deleteFamily( (int) $_POST['nip'] );
		header('Location: .');
		exit();
	}
}

//  default action: get list of families

include 'getFamilies.php';
include  'list.html.php';

?>
