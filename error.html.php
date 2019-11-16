<?php
//# -*- coding: utf-8 -*-
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang=fr>
<head>
	<title>Paniers de Noël - Collège Notre-Dame</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="svdp.css" />
	<script type="text/javascript" src="prototype.js"></script>
	<script type="text/javascript" src="svdp.js"></script>
</head>
	<body>
	<div id="container">
		<h1>Paniers de Noêl</h1>
		<h2>Société Saint-Vincent-de-Paul, Paroisse Saint Pascal Baylon</h2>
		<div id="navbar">
			<ul>
				<li><a href="?addfamily">Ajouter famille</a></li>
				<li><a href="">Liste des familles</a></li>
				<li><?php if (isset ($_REQUEST['trie']) and $_REQUEST['trie'] == 'nip') echo '<a href="?">Trie par adresse'; else echo '<a href="?trie=nip">Trie par nip'; ?></a></li>
			</ul>
		</div>
		<div>
			<h1>Erreur</h1>
			<h2>
				<?php echo $error; ?>
			</h2>
		</div>
	</div>
	</body>
</html>