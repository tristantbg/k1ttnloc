<?php snippet('header') ?>

<div id="page-content" class="page">
	
	<?php if ($site->user()): ?>

		<?php snippet('projects_grid', array('isFeatured' => false, 'projects' => $projects, 'page' => $page)) ?>
		
	<?php else: ?>

	<div class="default-message">
		
		<div>
			<?= $site->defaultMessage()->kt() ?>
		</div>

	</div>

	<?php endif ?>

</div>

<?php snippet('footer') ?>