<?php
//# -*- coding: utf-8 -*-
session_start();
//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', 'On');

include_once 'includes/magicquotes.inc.php';
include_once 'includes/helpers.inc.php';
include_once 'includes/db.inc.php';
//include_once 'staticmap.php';

include 'getFamilies.php';
$annee = date ( 'Y', time() );
$cndlocation = "45.493415,-73.620506";

?><!DOCTYPE html>
<html>
<head>
	<title>Paniers de Noël - Collège Notre-Dame</title>
	<meta charset="utf8">
<?php
$mapUrl = "http://maps.google.com/maps/api/staticmap?size=480x480&sensor=false";
$openmapUrl = "http://open.mapquestapi.com/staticmap/v4/getmap?key=gc6PFJn0kE9Etcg78XviilafHjbyTc4e&zoom=15&size=650,700&imagetype=png&type=map&bestfit=%1\$s,%2\$s&pois=green_1,%1\$s|red_1%2\$s&session=%3\$s";
$openmapDirectionReq = "https://www.mapquestapi.com/directions/v2/route?key=gc6PFJn0kE9Etcg78XviilafHjbyTc4e&from=%s&to=%s&outFormat=json&ambiguities=ignore&routeType=shortest&doReverseGeocode=false&enhancedNarrative=false&avoidTimedConditions=false";

if ( isset($_REQUEST['labels']) ) { ?>
	<link rel="stylesheet" type="text/css" href="labels.css" />
<?php } else { ?>
	<link rel="stylesheet" type="text/css" href="new.css" />
<?php } ?>
</head>
<body>
	<div id="container">
		<div id="list">
			<?php
				if (isset($_REQUEST['certificates'])) {
				
					include("views/certificate.php");
					
				} else if (isset($_REQUEST['checklists'])) {
				
					include("views/checklist.php");
					
				} else if ( isset($_REQUEST['labels'])) {
				
					include("views/label.php");
					
				} else if ( isset($_REQUEST['affiches'])) {
				
					include ("views/affiche.php");
					
				} else {   // fiches de famille (pour foyers)
					include ("views/fiche.php");
				}
				
			?>
		</div>
	</div>
</body>
</html>
