<?php


$linux = strpos ($_SERVER['HTTP_USER_AGENT'],'Linux' );

if ($linux) {
	$link = new mysqli('localhost', 'shalom_jjc', '1cor13', 'shalom_cnd');
} else {
	$link = new mysqli('localhost', 'root', '', 'shalom_cnd');
}

if (!$link)
{
	$error = 'Unable to connect to the database server.';
	include 'error.html.php';
	exit();
}

mysqli_set_charset($link,"utf8");
//$link->set_charset("UTF-8");

// check for data tables

$sql = "SELECT * from dependents";
$ret = $link->query($sql);

if (!$ret) {

	// create the tables if not there

	$sql = "CREATE TABLE IF NOT EXISTS `dependents` (
		`pid` int(11) NOT NULL AUTO_INCREMENT,
		`relation` varchar(255) DEFAULT NULL,
		`nom` varchar(255) DEFAULT NULL,
		`prenom` varchar(255) DEFAULT NULL,
		`sexe` char(1) DEFAULT NULL,
		`age` varchar(10) DEFAULT NULL,
		`note` varchar(255) DEFAULT NULL,
		`nip` int(11) NOT NULL,
		PRIMARY KEY (`pid`),
		KEY `nip` (`nip`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

	$ret = $link->query ($sql);

	if (!$ret) {
		$error = 'Unable to create data tables.';
		include 'error.html.php';
		exit();
	}

	$sql = "CREATE TABLE IF NOT EXISTS `families` (
		`fnum` int(11) NOT NULL AUTO_INCREMENT,
		`famno` int(11) NOT NULL,
		`nip` int(11) NOT NULL,
		`number` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
		`street` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
		`appt` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
		`code` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
		`tel_h` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
		`tel_alt` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
		`note` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
		`foyer` varchar(15) DEFAULT NULL,
		`montant` int(11) DEFAULT NULL,
		`location` varchar(30) DEFAULT NULL,
		PRIMARY KEY (`nip`),
		UNIQUE KEY `fnum` (`fnum`,`nip`,`number`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

	$ret = $link->query ($sql);

	if (!$ret) {
		$error = 'Unable to create data tables.';
		include 'error.html.php';
		exit();
	}

	$sql = "CREATE TABLE IF NOT EXISTS `person_category` (
		`cid` int(4) NOT NULL,
		`category` enum('Demandeur','Conjoint','Enfant','Autre') COLLATE utf8_bin NOT NULL,
		UNIQUE KEY `cid` (`cid`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";

	$ret = $link->query ($sql);

	if (!$ret) {
		$error = 'Unable to create data tables.';
		include 'error.html.php';
		exit();
	}

	$sql = "INSERT INTO `person_category` (`cid`, `category`) VALUES
		(1, 'Demandeur'), (2, 'Conjoint'), (3, 'Enfant'), (4, 'Autre')";

	$ret = $link->query ($sql);

	if (!$ret) {
		$error = 'Unable to update data tables.';
		include 'error.html.php';
		exit();
	}

}


$groceries = Array();
$sql = "SELECT * from groceries";
$ret = $link->query($sql);

if (!$ret) {

	// create the table if not there

	$sql = "CREATE TABLE IF NOT EXISTS `groceries` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`foyer` int(11) NOT NULL,
		`item1` varchar(255) DEFAULT NULL,
                `item2` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY (`foyer`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

	$ret = $link->query ($sql);

	if (!$ret) {
		$error = 'Unable to create groceries table.';
		include 'error.html.php';
		exit();
	}
} else {

	if (  $ret->num_rows> 0 ) {  // get groceries for each foyer
            while ($a_row =  $ret->fetch_assoc() ) {
                if (!$linux){
                    $a_row = array_map( "utf8_encode", $a_row);
                }
                $groceries[(string) $a_row['foyer']] = $a_row;
            }
	}
}


// defang user input
foreach ($_POST as $key=>$val) {
	if ( is_array ( $_POST[$key] ) ) {
		for ($i = 0; $i < count($_POST[$key]); $i++)
			$_POST[$key][$i] = mysql_real_escape_string ($_POST[$key][$i]);
	} else {
		$_POST[$key] = mysql_real_escape_string ($val);
	}
}

function addFamily () {

	$sql = "INSERT INTO `families` ( `famno` ,`nip` ,  `foyer` ,  `montant` ,  `number` ,  `street` ,  `appt` ,  `code` ,  `tel_h` ,  `tel_alt` ,  `note` ) VALUES(  '{$_POST['famno']}' , '{$_POST['nip']}' ,  '{$_POST['foyer']}' ,  '{$_POST['montant']}' ,  '{$_POST['number']}' ,  '{$_POST['street']}' ,  '{$_POST['appt']}' ,  '{$_POST['code']}' ,  '{$_POST['tel_h']}' ,  '{$_POST['tel_alt']}',  '{$_POST['note']}' ) ";

	if (!$link->query($sql))
	{
		$error = 'Erreur en ajoutant famille : ' . mysql_error();
		include 'error.html.php';
		exit();
	}

// add family members
	$nip = (int) $_POST['nip'];
	$num = count ($_POST['prenom']);  // any field would do
	for ($i = 0; $i < $num; $i++) {
		if ($_POST['prenom'][$i] != "" ) {
			$sql = "INSERT INTO `dependents` ( `nip` ,  `relation` ,  `nom` ,  `prenom` ,  `sexe` ,  `age`  ) VALUES(  '{$_POST['nip']}' ,  '{$_POST['relation'][$i]}' ,  '{$_POST['nom'][$i]}' ,  '{$_POST['prenom'][$i]}' ,  '{$_POST['sexe'][$i]}' ,  '{$_POST['age'][$i]}' ) ";
			if (!$link->query($sql)) {
				$error = 'Erreur en ajoutant personne : ' . mysql_error();
				include 'error.html.php';
				exit();
			}
		}
	}


} // addFamily()

function deleteFamily( $nip) {

	$sql = "DELETE FROM `families` WHERE `nip` = '$nip' ";
	if (!$link->query($sql))
	{
		$error = 'Erreur en supprimant famille : ' . mysql_error($link);
		include 'error.html.php';
		exit();
	}
	// also delete family members
	$sql = "DELETE FROM `dependents` WHERE `nip` = '$nip' ";
	if (!$link->query($sql))
	{
		$error = 'Erreur en supprimant membres de la famille : ' . mysql_error($link);
		include 'error.html.php';
		exit();
	}

} // deleteFamily()
?>
