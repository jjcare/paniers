<?php foreach ($families as $f) { ?>
<div>
	<h1>Paniers de Noël <sub><i><?php echo $annee;?></i></sub></h1>
	<div class="foyerno">
		Foyer <?php echo ($f->foyer == '') ? '____' : $f->foyer;?>
		<?php $foyer = substr($f->foyer,0,3); ?>
	</div>
	<div>
		<h2>Chaque élève apporte :</h2>
	</div>
	<div class="section">
		<?php 
		$groc = preg_replace("/(\d+) /", "$1&nbsp;", 
		  $groceries[$foyer]['item1']. ( ($groceries[$foyer]['item2']) ? '<br>' . $groceries[$foyer]['item2'] : '' ));
		?> 
		<div class="groceries"><?php echo $groc;?></div>
	</div>
	<div class="section">
		<div class="familypics">
			<?php echo $f->getParentPics() . $f->getChildrenPics();?>
		</div>
	</div>
	<div class="famno">
		Famille no. <?php echo $f->famno;?>
	</div>
</div>

<?php } // foreach
?>