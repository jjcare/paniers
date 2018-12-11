<?php foreach ($families as $f) { ?>
<div class="c_famdiv">
	<h1>Paniers de Noël</h1>
	<?php
	if ($f->foyer) echo "<h3>(Foyer: <strong>".$f->foyer."</strong>)</h3>"; else echo "<h3>&nbsp;</h3>"; ?>
	<div class="c_famille"><?php echo $f->famno;?></div>
	<div class="c_nip">NIP : <?php echo $f->nip;?></div><br>
	<h3>Certificats : <strong><?php echo $f->montant;?> $</strong></h3>
	<h3>(aussi: denrées non périssables et cadeaux)</h3><br>
	<table class="members">
		<thead>
			<tr><th>Parent(s)</th><th>Enfant(s)</th></tr></thead>
		<tbody>
			<tr>
				<td><?php echo $f->getApplicant();?></td>
				<td><?php echo $f->getChildren();?></td>
			</tr>
		</tbody>
	</table><br>
	<?php
		// prepare a map image filename from the address. Convert accents to ascii
		$address = convertAccents($f->getAddress());
		$parts = explode(" ", $address);
		if ($parts[count($parts)-1][0] == "#") {  // ignore apartment number
			unset($parts[count($parts)-1]);
		}
		$map = "maps/" . join("",$parts) . ".png";	
	?>
	<div class="c_address"><strong>Adresse : </strong><?php echo $f->getAddress() ." - " . $f->code;?> </div>
	<?php if ($f->note) echo "<div class=\"c_note\"><strong>Note : </strong><em>".$f->note."</em></div>"; ?>
	<div class="c_telephone"><strong>Téléphone : </strong><?php echo $f->getTelephone();?></div>
	<p class="c_signature">Signature du bénéficiaire : ________________________________</p>
	<br><br>
	<?php
		if (file_exists($map)) {
		echo '<hr><center><img src="' . $map . '" height="500px"></center>';
		} else {  // produce the map (this is the old api - todo: update to v5)
		echo "<hr><center><img src=\"http://open.mapquestapi.com/staticmap/v4/getmap?key=gc6PFJn0kE9Etcg78XviilafHjbyTc4e&zoom=15&size=500,500&imagetype=png&type=map&mcenter=" . $f->getLocation() . "&center=" .$f->getLocation(). "\" height=\"500\" /></center>";
		}
	?>
</div>
<?php } //foreach
?>