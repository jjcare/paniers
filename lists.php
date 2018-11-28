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
if ( isset($_REQUEST['labels']) ) {
	$index = 0;
	$recs = "NIP\tno\tmontant\tNOM\tprenom\tsexe\tadresse\ttelh\ttelalt\n"; ?>
	<link rel="stylesheet" type="text/css" href="labels.css" />
<?php } else { ?>
	<link rel="stylesheet" type="text/css" href="new.css" />
<?php } ?>
</head>
<body>
	<div id="container">
		<div id="list">
		<?php if ( isset($_REQUEST['labels']) ) {
				echo '<h1>Labels are in <em>labels.txt</em> - to be copied to fusion document.</h1>';
				echo '<table class="labels"><tr>';
			}

				foreach ($families as $f) {
				
					if (isset($_REQUEST['certificates'])) {
					
						echo "\n<div class=\"c_famdiv\"><h1>Paniers de Noël</h1>";
						if ($f->foyer) echo "<h3>(Foyer: <strong>".$f->foyer."</strong>)</h3>"; else echo "<h3>&nbsp;</h3>";
						echo "<div class=\"c_famille\">".$f->famno."</div>";
						echo "<div class=\"c_nip\">NIP : ".$f->nip."</div><br />";
						echo "<h3>Certificats : <strong>".$f->montant." $</strong></h3>";
						echo "<h3>(aussi: denrées non périssables et cadeaux)</h3><br />";
						echo "<table class=\"members\"><thead><tr><th>Parent(s)</th><th>Enfant(s)</th></tr></thead><tbody>";
						echo "<tr><td>".$f->getApplicant()."</td><td>".$f->getChildren()."</td></tr></tbody></table><br />";
						// prepare a map image filename from the address. Convert accents to ascii
                                                $address = convertAccents($f->getAddress());
						$parts = explode(" ", $address);
						if ($parts[count($parts)-1][0] == "#") {
							unset($parts[count($parts)-1]);
						}
						$map = "maps/" . join("",$parts) . ".png";	
						echo "<div class=\"c_address\"><strong>Adresse : </strong>".$f->getAddress()." - " . $f->code . "</div>";
						if ($f->note) echo "<div class=\"c_note\"><strong>Note : </strong><em>".$f->note."</em></div>";
						echo "<div class=\"c_telephone\"><strong>Téléphone : </strong>".$f->getTelephone()."</div>";
						echo "<p class=\"c_signature\">Signature du bénéficiaire : ________________________________</p>";
						echo "<br><br>";
						//echo "<hr><center><img src='staticmap.php?zoom=15&size=500,500&imagetype=png&maptype=mapnik&center=". $f->getLocation() . "&markers=" . $f->getLocation() . ",blues' height=\"640\" /></center><br /></div>";
						 
						if (file_exists($map)) {
							echo '<hr><center><img src="' . $map . '" height="500px"></center>';
						} else {
						echo "<hr><center><img src=\"http://open.mapquestapi.com/staticmap/v4/getmap?key=gc6PFJn0kE9Etcg78XviilafHjbyTc4e&zoom=15&size=500,500&imagetype=png&type=map&mcenter=" . $f->getLocation() . "&center=" .$f->getLocation(). "\" height=\"500\" /></center>";
						}
                                                echo "<br>$map</br>";
						echo "</div>";
						//echo "<hr><center><img src=\"http://maps.google.com/maps/api/staticmap?zoom=15&size=640x640&scale=2&center=" . $f->getLocation() . "&sensor=false&markers=label:A%7C45.4937045,-73.6203322&markers=label:B%7C".$f->getLocation()."\" height=\"640\" /></center><br /></div>";
						//$x = 1000000; while ($x) $x--;  // pour ne pas dépasser la limite de vitesse Google
						
					} else if ( isset($_REQUEST['labels'])) {
					
						// etiquettes - 3 par rangée
						if ($index and $index % 3 == 0) echo '</tr><tr>';
						echo '<td>' . $f->getLabel() . '</td>';
						$recs = $recs . $f->getLabelRecord() . "\n";
						$index = $index + 1;


					} else if ( isset($_REQUEST['affiches'])) {
					
						echo "<div class=\"c_famnobig\">".$f->famno."</div><br /><br />";
                                                $pfoyer =  ($f->foyer == '') ? '______________' : $f->foyer; 
						echo "<h2>Foyer: <strong>".$pfoyer."</strong></h2>"; 
						echo "<table class=\"members\"> <thead><tr><th>Parent(s) </th><th>Enfant(s) </th></tr></thead><tbody>";
						echo "<tr><td>".$f->getApplicant()."</td><td>".$f->getChildren()."</td></tr></tbody></table><br />";
						if ($f->note) echo "<div class=\"c_note\"><strong>Note : </strong><em>".$f->note."</em></div>";
						
                                        } else {   // fiches de famille (pour foyers)
?>					
    <div>
    <h1>Paniers de Noël <sub><i><?php echo $annee;?></i></sub></h1>
    <div class="foyerno">Foyer <?php echo ($f->foyer == '') ? '____' : $f->foyer;
      $foyer = substr($f->foyer,0,3);
      ?></div>
        <div><h2>Chaque élève apporte :</h2></div>
        <div class="section">
        <?php $groc = preg_replace("/(\d+) /", "$1&nbsp;", $groceries[$foyer]['item1']. ( ($groceries[$foyer]['item2']) ? '<br>' . $groceries[$foyer]['item2'] : '' ));?> 
        <div class="groceries"><?php echo $groc;?></div>
            <div class="argent"><small>&ge;</small> 8 <small>$</small></div>
        </div>
        <div class="section">
            <div class="familypics">
              <?php echo $f->getParentPics() . $f->getChildrenPics();?>
            </div>
        </div>
        <div class="famno">Famille no. <?php echo $f->famno;?></div>
    </div>
</div>
<?php                               }
				} // foreach
				
				if ( isset($_REQUEST['labels']) ) {  // output label information for Word merge
					echo '</tr></table>';
					@file_put_contents ("labels.txt", $recs);
				}
			?>
		</div>
	</div>
</body>
</html>
