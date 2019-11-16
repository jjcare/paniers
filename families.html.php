<?php
//# -*- coding: utf-8 -*-
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
	<title>Paniers de Noël - Collège Notre-Dame</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="paniers.css" />
</head>
<body>
	<div id="container">
		<div id="list">
			<?php foreach ($families as $f) {
						echo "<div class=\"famdiv\"><h1>Paniers de Noêl</h1><p class=\"famille\">Famille no. ".$f->famno."</p>";
						echo "<p>Foyer: <strong>".$f->foyer."</strong></p>";
						echo "<p>Objectif en argent: <strong>".$f->montant." $</strong></p>";
						echo "<p>(aussi: denrées non périssables, jouets propres et vêtements chauds)</p>";
						echo "<table class=\"members\"><thead><tr><th>Parent(s)</th><th>Enfant(s)</th></tr></thead><tbody>";
						echo "<tr><td>".$f->getApplicant()."</td><td>".$f->getChildren()."</td></tr></tbody></table>";
						echo "<p>Voici votre famille adoptée pour les paniers de Noël. Les dons <em>en argent</em> devraient être rendus au Shalom pour le 17 décembre. Les familles vous remercient. Bonne campagne!</p>";
						echo "<p>John Carey</p><p>Shalom</p></div>";
					}
			?>
		</div>
		<br /><br />
	</div>
</body>
</html>