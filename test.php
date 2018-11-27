<?php
$a = array ( array( 'age'=>'5', 'nom'=>'alex'), array( 'age'=>'8', 'nom'=>'mary'), array( 'age'=>'3½', 'nom'=>'alex') );

function cmp ( $a, $b ) {  // function to determine reverse-order age
			$age_a = (int) ($a['age']) / ((strpos($a['age'], 'mos')) ? 12 : 1);
			$age_b = (int) ($b['age']) / ((strpos($b['age'], 'mos')) ? 12 : 1);
			if ($age_a == $age_b) {
				return 0;
			}
			return ($age_a < $age_b) ? -1 : 1;
			}
usort ($a, "cmp" );

print_r ($a);

phpinfo();
?>