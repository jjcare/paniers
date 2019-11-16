<?php
//# -*- coding: utf-8 -*-
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
	<title>Paniers de Noël - Collège Notre-Dame</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="svdp.css" />
	<script type="text/javascript" src="prototype.js"></script>
	<script type="text/javascript" src="svdp.js"></script>
</head>
<body>
	<div id="container">
		<h1>Paniers de Noël</h1>
		<h2>Société Saint-Vincent-de-Paul, Paroisse Saint Pascal Baylon</h2>
		<div id="navbar">
			<ul>
				<li><a href="?addfamily">Ajouter famille</a></li>
				<li><a href="">Liste des familles</a></li>
			</ul>
		</div>
		<div id="modform">
			<form action="?" method="post" id="famform">
				<?php displayFamilyModForm($family); ?>
				<input type="submit" id="sub_family" name="action" value="Modifier" />
			</form>
		</div>
		<br /><br />
	</div>
</body>
</html>