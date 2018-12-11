<h1>Labels are in <em>labels.txt</em> - to be copied to fusion document.</h1>
<table class="labels">
	<tr>

	<?php 
	$index = 0;
	$recs = "NIP\tno\tmontant\tNOM\tprenom\tsexe\tadresse\ttelh\ttelalt\n"; 

	foreach ($families as $f) { 

		// etiquettes - 3 par rangée - nice on screen but not good for printing
		if ($index and $index % 3 == 0) echo '</tr><tr>';
		echo '<td>' . $f->getLabel() . '</td>';
		$recs = $recs . $f->getLabelRecord() . "\n";
		$index = $index + 1;
		
	} // foreach

	@file_put_contents ("labels.txt", $recs);  // records exported for merge

	?>
	</tr>
</table>
