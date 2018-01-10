<?php if($featuredProjects): ?>
	<div id="featured">
		<?php foreach ($featuredProjects as $key => $project): ?>
			
			<?php $project = $project->toPage() ?>
			<?php if($project->featured()->isNotEmpty()): ?>

				<?php

				$featured = $project->featured()->toFile();
				$isVideo = $project->featuredvideo()->bool() && $project->featuredvideolink()->isNotEmpty() || $project->featuredvideo()->bool() && $project->featuredvideofile()->isNotEmpty();
				$title = $project->title()->html();
				if($project->artist()->isNotEmpty()) $title .= " × ".$project->artist()->html();

				?>

				<div class="featured-item <?= $project->category() ?>">

				<a 
				href="<?= $project->url() ?>" 
				<?php e($isVideo, 'class="video" ') ?>
				data-target="project">
					<div class="project-infos">
						<h3><?= $title ?></h3>
						<?= $project->text()->html() ?>
					</div>
						<?php if($isVideo): ?>
							<?php
							$source = $project->featuredvideolink();
							if ($project->featuredvideofile()->toFile()) $source = $project->featuredvideofile()->toFile()->url();
							?>
							<div class="project-video featured" 
							data-mp4="<?= $source ?>" 
							<?php if($webm = $project->featuredvideofilewebm()->toFile()): ?>
							data-webm="<?= $webm->url() ?>"
							<?php endif ?>
							></div>
						<?php endif ?>
						<div class="project-image featured">
							<img 
							class="lazyimg lazyload" 
							src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
							data-src="<?= thumb($featured, array('width' => 800))->url() ?>" 
							data-srcset="<?= thumb($featured, array('width' => 500))->url() ?> 500w, <?= thumb($featured, array('width' => 800))->url() ?> 800w, <?= thumb($featured, array('width' => 1000))->url() ?> 1000w" 
							data-sizes="auto" 
							data-optimumx="1.5" 
							alt="<?= $project->title()->html().' - © '.$site->title()->html() ?>" 
							width="100%" height="auto" />
						</div>
				</a>
					
				</div>

			<?php endif ?>

		<?php endforeach ?>
	</div>
<?php endif ?>
