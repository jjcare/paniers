<?php
//# -*- coding: utf-8 -*-
require 'familyClass.php';

$thead = implode('</th><th>', explode (' ','<thead><tr><th>* No. Demandeur/Conjoint NIP No. Rue Appt Code Foyer Montant Tel-514 Enfants')) . '</th></tr></thead><tbody>';
$trie = (isset ($_REQUEST['trie']) and $_REQUEST['trie'] == 'nip') ? 'nip' : 'famno';
$family_set = $link->query ( "SELECT * FROM families ORDER BY " . $trie);
$families = array();
if (  $family_set->num_rows> 0 ) {
	while ($a_row =  $family_set->fetch_assoc() ) {
//		if (!$linux) { $a_row = array_map( "utf8_encode", $a_row);}
		$families[] = new Family ($a_row);
	}
}


// now get persons
for ( $index=0; $index < count($families); $index++ ) {
	$sql = "SELECT * FROM dependents ";
	$sql .= "LEFT JOIN person_category on dependents.relation = person_category.category ";
	$sql .= "where nip='".$families[$index]->nip . "' order by person_category.cid";
	$member_set = $link->query ($sql);
	if ( $member_set->num_rows  > 0 ) {
		while ($a_row = $member_set->fetch_assoc()  ) {
  //                  if (!$linux) { $a_row = array_map( "utf8_encode", $a_row); }
                    //print_r ($a_row); echo "<br />";
		    $families[$index]->addPerson($a_row);
		}
	}
}

if (count($families) > 0) {
	$montants = Array();
	$coupures = Array('100 $' => 0, '25 $' => 0, '10 $' => 0);
	$html = ''; $nips = ''; $gtotal = 0;
	foreach ($families as $F) {
		$html .= $F->showFamily() . ' ';
		$nips .= $F->nip.' ';
                $gtotal += $F->montant;
		// calculate the amounts given to each family
		if (array_key_exists($F->montant, $montants)) { 
			$montants[$F->montant] += 1;
		} else {
			$montants[$F->montant] = 1;
		}
		// find the certificat amounts needed for each family
		$coupures['100 $'] += intval ($F->montant / 100);
		$coupures['25 $'] += intval ($F->montant % 100 / 50) * 2;
		$coupures['10 $'] += intval ($F->montant % 100 % 50 / 10);
	}
	$montant_list = ''; $coupure_list = '';
	foreach ($montants as $montant=>$count) {
		$montant_list .= "<li>$montant $ : $count</li>\n";
	}
	foreach ($coupures as $coupure=>$count) {
		$coupure_list .= "$count de $coupure | ";
	}
	$html = implode ($html,  array('<table class="family">' . $thead ,  '</tbody></table>'));
	$html = "<div id='montants'><ul>\n" . $montant_list . "</ul><br />\n<span>$coupure_list</span><br />\n<b>Total cartes-cadeau : $gtotal $</b></div>" . $html;
	$html_nips =  '<input type="hidden" name="nips" id="nips" value="'.$nips.'" />';
} else {
	$html = "<h3>Aucune famille trouvée.</h3>";
}

?>
