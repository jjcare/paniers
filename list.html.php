<?php
//# -*- coding: utf-8 -*-
?><!DOCTYPE html>
<html lang="fr">
<head>
	<title>Paniers de Noël - Collège Notre-Dame</title>
	<meta charset="utf-8">
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
				<li><?php if (isset ($_REQUEST['trie']) and $_REQUEST['trie'] == 'nip') echo '<a href="?">Trie par adresse'; else echo '<a href="?trie=nip">Trie par nip'; ?></a></li>
				<li><a href="lists.php">Imprimer familles</a></li>
				<li><a href="lists.php?labels" alt="Produire labels.txt">Imprimer étiquettes</a></li>
				<li><a href="lists.php?certificates">Imprimer certificats</a></li>
				<li><a href="lists.php?affiches">Imprimer affiches</a></li>
				<li><a href="map.php?map">Carte</a></li>
			</ul>
		</div>
		<div id="list">
			<?php echo $html; ?>
		</div>
		<br /><br />
	</div>
</body>
</html>
