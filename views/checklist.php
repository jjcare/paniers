<?php 
// Checklist pages - for overprinting or copying to Word
foreach ($families as $f) {

?>

<h2 class="jump">Famille <?php echo $f->famno; ?></h2><br><br>

<?php if ($f->foyer) echo "<div class=\"check_foyer\">Foyer: <strong>".$f->foyer."</strong></div>"; else echo "<h3>&nbsp;</h3>"; ?>

<table class="members">
	<thead><tr><th>Parent(s)</th><th>Enfant(s)</th></tr></thead>
	<tbody>
		<tr>
			<td><?php echo $f->getApplicant();?></td>
			<td><?php echo $f->getChildren();?></td>
		</tr>
	</tbody>
</table>
<br>
<?php } //foreach
?>