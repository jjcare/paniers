<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
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
		<h1>Paniers de Noêl</h1>
		<h2>Société Saint-Vincent-de-Paul, Paroisse Saint Pascal Baylon</h2>
		<div id="form2">
			<?php echo "<table><tr><td>${_POST['nip']}</td><td>${_POST['number']}</td><td>${_POST['street']}</td><td>${_POST['appt']}</td><td>${_POST['code']}</td><td>${_POST['tel_h']}</td><td>${_POST['tel_alt']}</td></tr></table>";
			echo getDependents($_POST['nip']); ?>
			<form action="?" method="post">
				<div>
					<?php displayPersonForm(); ?>
					<input type="hidden" id="nip" value="<?php echo $_POST['nip']; ?>" />
				</div>
				<div><input type="submit" id="sub_personne" value="Ajouter Personne"/></div>
			</form>
		</div>
		<br /><br />
	</div>
</body>
</html>