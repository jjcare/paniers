<?php
//# -*- coding: utf-8 -*-
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] .includes/magicquotes.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] .includes/helpers.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] .includes/db.inc.php';

include 'getFamilies.php';
?><!DOCTYPE html>
<html lang="fr">
<head>
	<title>Paniers de Noël - Collège Notre-Dame</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 
	<style type="text/css">   
		html { height: 100% }   
		body { height: 100%; margin: 0px; padding: 0px }   
		#map_canvas { height: 100% } 
	</style> 
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"> 
	</script> 
	<script type="text/javascript">   
		function initialize() {     
			var latlng = new google.maps.LatLng(45.491793299999998,-73.621926400000007);     
			alert (latlng);
			var myOptions = {       
				zoom: 14,       
				center: latlng,       
				mapTypeId: google.maps.MapTypeId.ROADMAP     
				};     
			var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);   
			
			var marker = new google.maps.Marker ({       
				position: latlng,       
				title:"Collège Notre-Dame",
				});
			
			marker.setMap (map);
		} 
		
	</script> 
<?php
if ( ! isset($_REQUEST['map']) ) {
	die();
}
$labels = explode ( " ", "A B C D E F G H I J K L M N O P Q R S T U V W X Y Z 0 1 2 3 4 5 6 7 8 9 X X X X X X X X X X X X X X");
$mapUrl = "<img src=\"http://maps.google.com/maps/api/staticmap?size=480x480&zoom=15&sensor=false";

?>
</head>
<body onload="initialize()">
	<div id="map_canvas" style="width:100%; height:100%">
		
	</div>
</body>
</html>
