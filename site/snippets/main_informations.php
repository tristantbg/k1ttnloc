<?php $mainInformations = $site->mainInformations()->toStructure(); ?>

<?php if($mainInformations->isNotEmpty()): ?>
<div id="main-informations">
	<?php foreach ($mainInformations as $key => $infoSection): ?>
		<div class="info-section">
			<?= $infoSection->content()->kt() ?>
		</div>
	<?php endforeach ?>
</div>
<?php endif ?>