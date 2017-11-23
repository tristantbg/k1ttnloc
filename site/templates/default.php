<?php snippet('header') ?>

<div id="page-content" class="page">
	
	<div class="column left" data-scroll>
		<div class="scroller">
			<?= page('error')->left()->kt() ?>
		</div>
	</div>
	
	<div class="separator"></div>

	<div class="column right" data-scroll>
		<div class="scroller">
			<?= page('error')->right()->kt() ?>
		</div>
	</div>

</div>

<?php snippet('footer') ?>