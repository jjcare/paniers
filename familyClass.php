<?php
//# -*- coding: utf-8 -*-
$relations = array ( "Demandeur", "Conjoint", "Enfant", "Parent", "Autre");

class Family {

	const FAMBAR = '<ul class="navside"><li><input type="image" src="image/plus.png" name="action" value="ajouter" width="20" height="20" onclick="this.form.submit();" />
	</li><li><input type="image" src="image/write.png" name="action" value="modifier" width="20" height="20" onclick="this.form.submit();" /></li><li><input type="image" src="image/x.png" name="action" value="supprimer" width="20" height="20" onclick="this.form.submit();" /></li></ul>';
	// <input type="submit" name="action" class="btn" value="ajouter" /><input type="submit" name="action" class="btn" value="modifier" /><input type="submit" name="action" class="btn" value="supprimer" />
	const famnum = 11;

	function __construct ( $items) {  // can take arbitrary fields as input
		foreach ($items as $k=>$v) {
			$this->$k = $v;
		}
		$this->personnes = array();  // empty list to start
	}

	function addPerson ($person) {
		global $relations;
		if (! in_array ( $person['relation'], $relations ) ) {
			die ('Relation invalide.');
		 }
		$myperson = array();
		foreach ($person as $k=>$v) {
			$myperson[$k] = $v;
		}
		$this->personnes[] = $myperson;
		if ($person['relation'] == 'Demandeur') $this->demandeur = $myperson;
	}

	function showFamily () {  	// produce html-formatted object
		$control = '<form action="" method="post"><input type="hidden" name="nip" value="'.$this->nip.'" />';
		$applic = $this->getApplicant(). '<br /><em>' . $this->note . '</em>';
		$html = '<tr>'.$control.'<td>' . implode ('</td><td>', array(self::FAMBAR, $this->famno , $applic, $this->nip,$this->number,$this->street,$this->appt,$this->code,'<b>'.$this->foyer.'</b>',$this->montant.' $'));
		$html .= '</td><td>' . $this->tel_h . '<br /><em>' . $this->tel_alt  . '</em></td><td>';
		$html .= $this->getChildren() . '</td></form></tr>';
		return $html;
	}

	function getApplicant($anon = 'no') {
		global $relations;
		$pers = $this->personnes; $ben = ""; $con = "";
		
		for ($i = 0; $i < count($pers); $i++) {
			if ($pers[$i]['relation'] != "Enfant") {
				$nom = ($anon == 'no') ? strtoupper($pers[$i]['nom']) . ', '  : "";
				$id = '<tr class="'. $pers[$i]['relation'] .'"><td>' . $nom . $pers[$i]['prenom'] . '</td><td>' . $pers[$i]['sexe']. '</td></tr>';
				if ($pers[$i]['relation'] == "Demandeur") {
					$ben =  $id;
				} else if ($pers[$i]['relation'] == "Conjoint") {
					$con = $id . $con;
				} else {
					$con .= $id;
				}
			}
		}
		return '<table class="beneficiary">' . $ben . $con . '</table>';

	}

	function getBeneficiary() {
		global $relations;
		$pers = $this->personnes; $ben = ""; $con = "";
		for ($i = 0; $i < count($pers); $i++) {
			if ($pers[$i]['relation'] != "Enfant") {
				$id = '<tr class="'. $pers[$i]['relation'] .'"><td>' . strtoupper($pers[$i]['nom']) . ', ' . $pers[$i]['prenom'] . '</td><td>' . $pers[$i]['sexe']. '</td></tr>';
				if ($pers[$i]['relation'] == "Demandeur") {
					$ben =  $id;
				} else if ($pers[$i]['relation'] == "Conjoint") {
					$con = $id . $con;
				}
			}
		}
		return '<table class="beneficiary">' . $ben . $con . '</table>';

	}

	function getChildren() {
		$pers  = $this->personnes;
		usort( $pers , "r_comp_age");
		$children = "";
		for ($i=0; $i<count($pers); $i++) {
			if ($pers[$i]['relation'] == "Enfant") {
				if (strlen($children) == 0) {
					$children = '<table class="children">';
				}
				$children =  $children . '<tr class="child"><td>' . implode('</td><td>', array($pers[$i]['prenom'], $pers[$i]['sexe'], $pers[$i]['age'])) . '</td></tr>';
			}
		}
		if (strlen($children) > 0) {
			$children .= '</table>';
		}
		return $children;

	} // getChildren()
	
	function getParentPics() {
		$pers = $this->personnes;
		$adults = ""; $ben = ""; $con = "";
		for ($i=0; $i < count($pers); $i++) {
			if ($pers[$i]['relation'] != "Enfant") {
				$icon = ($pers[$i]['sexe'] == 'F') ? 'woman' : 'man';
				$fmt = '<div class="person"><div><p>%s</p></div><img height="200px" src="image/%s.png"></div>';
				$relation = $pers[$i]['relation'];
				$person = sprintf($fmt, $pers[$i]['prenom'], $icon);
				if ($relation == 'Demandeur') { 
					$ben = $person; 
				} else if ($relation == 'Conjoint') { 
					$con = $person; 
				} else { 
					$adults = $adults . $person; 
				}
			}
				}
		return $ben . $con. '<div class="vbar"><img src="image/vertical-line.png"></div>'. $adults;
                    
                        
                    
	}


	function getChildrenPics() {
		$pers = $this->personnes;
		usort( $pers, "r_comp_age");
		$children = "";
		for ($i=0; $i < count($pers); $i++) {
			if ($pers[$i]['relation'] == "Enfant") {
				$childage = $this->getAgeStr($pers[$i]['age']);
				$gender = ($pers[$i]['sexe'] == 'M') ? 'garçon' : 'fille';
				
				if (strpos($childage, 'ans')) {
					$size = 70 + (5 * $pers[$i]['age']);
					$icon = ($gender == 'fille') ? 'girl' : 'boy';
				} else {
					$size = 70;
					$icon = 'baby';
				}
				$fmt = '<div class="person"><div><p>%s<br><em>%s<br>%s</em></p></div><img height="%dpx" src="image/%s.png"></div>';
				$children .=  sprintf($fmt, $pers[$i]['prenom'], $gender, $childage, $size, $icon);
			}
		}
		return $children; 
	}

	function getAgeStr($age) {
		// parse age to normalize
		if (strpos($age,'an') or strpos($age,'mois')) {
			return $age;
		}
		$qualifier = (intval($age) > 1) ? ' ans' : ' mois';
		if (intval($age) == 1) { $qualifier = ' an'; }
		$ageval = intval($age);
		if ($ageval == 0) {
			$ageval = strpos("012¼45½78¾", $age[0]);
		}
		return strval($ageval) . $qualifier;
	}
		
	function getAddress() {
		$ret = $this->number. ' ' . $this->street;
		if (strlen($this->appt) > 0 and $this->appt != 'null') { $ret = $ret . ' #' . $this->appt; }
		return $ret;
	} // getAddress()
	
	function getLocation() {
		return $this->location;
	} // getLocation()

	function getTelephone() {
		return $this->formatTelephone($this->tel_h) . '<br />' . $this->formatTelephone($this->tel_alt);
	} // getTelephone()
	
	function formatTelephone($tel)  { // * preceding phone number indicates area code 438
		return ($tel[0] == '*') ? '438-'.substr($tel,1) : ( strlen($tel) < 10) ? '514-'.$tel : $tel; 
	} // formatTelephone()

	function getLabel() {
		$ret = '<table class="famlabel"><tr><td>'.$this->famno. '</td><td class="right">'.$this->montant.'</td></tr>';
		$ret = $ret . '<tr><td>'.$this->getBeneficiary(). '</td></tr>';
		$ret = $ret . '<tr><td><strong>' . $this->getAddress(). '</strong></td><td class="right">NIP :' . $this->nip . '</td></tr>';
		$ret = $ret . '<tr><td>' . $this->tel_h . '<br />' . $this->tel_alt . '</td><td></td></tr>';
		$ret = $ret . '</table>';
		return $ret;

	} // getLabel()

	function getLabelRecord() {
		return implode ("\t", array ( $this->nip, $this->famno, $this->montant, $this->demandeur['nom'], $this->demandeur['prenom'], $this->demandeur['sexe'], $this->getAddress(), $this->tel_h, $this->tel_alt));
	} // getLabelRecord()

}   // class Family

// function to determine reverse-order age
function r_comp_age ( $a, $b ) {
	$age_a = (int) ($a['age']) / ((strpos($a['age'], 'mos')) ? 12 : 1);
	$age_b = (int) ($b['age']) / ((strpos($b['age'], 'mos')) ? 12 : 1);
	if ($age_a == $age_b) {
		return 0;
	}
	return ($age_a > $age_b) ? -1 : 1;
	}



?>
