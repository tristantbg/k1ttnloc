<div id="projects" class="simple-grid">
	<?php foreach ($images as $key => $image): ?>

		<?php if($featured = $image->toFile()): ?>

			<div class="project-item">

			<a href="#<?= $key + 1 ?>" data-slide="<?= $key + 1 ?>">
					<div class="project-image">
						<img 
						class="lazyimg lazyload" 
						src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
						data-src="<?= thumb($featured, array('width' => 800))->url() ?>" 
						data-srcset="<?= thumb($featured, array('width' => 300))->url() ?> 300w, <?= thumb($featured, array('width' => 500))->url() ?> 500w, <?= thumb($featured, array('width' => 800))->url() ?> 800w, <?= thumb($featured, array('width' => 1000))->url() ?> 1000w" 
						data-sizes="auto" 
						data-optimumx="1.5" 
						width="100%" height="auto" />
					</div>
			</a>
				
			</div>

		<?php endif ?>

	<?php endforeach ?>
	<?php if($images->count() < 12): ?>
		<?php for ($i=0; $i < 12 - $images->count(); $i++): ?>
			<div class="project-item blank"></div>
		<?php endfor ?>
	<?php endif ?>
</div>