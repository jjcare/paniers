<?php foreach ($families as $f) { ?>

<div class="c_famnobig"><?php echo $f->famno;?></div><br><br>
<?php
$pfoyer =  ($f->foyer == '') ? '______________' : $f->foyer; 
?>
<h2>Foyer: <strong><?php echo $pfoyer;?></strong></h2>
<table class="members">
	<thead>
		<tr><th>Parent(s) </th><th>Enfant(s) </th></tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo $f->getApplicant();?></td>
			<td><?php echo $f->getChildren();?></td>
		</tr>
	</tbody>
</table>
<br>
<?php
	if ($f->note) echo "<div class=\"c_note\"><strong>Note : </strong><em>".$f->note."</em></div>";
?>

<?php } //foreach
?>
