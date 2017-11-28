<div id="projects"<?php e($isFeatured, ' class="featured-grid"', ' class="simple-grid"') ?>>
	<?php foreach ($projects as $key => $project): ?>

		<?php if($project->featured()->isNotEmpty()): ?>

			<?php $featured = $project->featured()->toFile() ?>
			<?php $isVideo = $project->featuredvideo()->bool() && $project->featuredvideolink()->isNotEmpty() || $project->featuredvideo()->bool() && $project->featuredvideofile()->isNotEmpty() ?>

			<div class="project-item <?= $project->category() ?>">

			<a 
			href="<?= $site->url().'/'.$page->autoid().'/'.$page->uid().'/'.$project->uid() ?>" 
			<?php e($isVideo, 'class="video" ') ?>
			data-target="project">
				<div class="project-infos">
					<?= $project->title()->html() ?>
					<br><?php e($project->artist()->isNotEmpty(), $project->artist()->html()) ?>
				</div>
					<?php if($isVideo): ?>
						<?php
						$source = $project->featuredvideolink();
						if ($project->featuredvideofile()->toFile()) $source = $project->featuredvideofile()->toFile()->url();
						?>
						<div class="project-video" 
						data-mp4="<?= $source ?>" 
						<?php if($webm = $project->featuredvideofilewebm()->toFile()): ?>
						data-webm="<?= $webm->url() ?>"
						<?php endif ?>
						></div>
					<?php endif ?>
					<div class="project-image">
						<img 
						class="lazyimg lazyload" 
						src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
						data-src="<?= thumb($featured, array('width' => 800))->url() ?>" 
						data-srcset="<?= thumb($featured, array('width' => 300))->url() ?> 300w, <?= thumb($featured, array('width' => 500))->url() ?> 500w, <?= thumb($featured, array('width' => 800))->url() ?> 800w, <?= thumb($featured, array('width' => 1000))->url() ?> 1000w" 
						data-sizes="auto" 
						data-optimumx="1.5" 
						alt="<?= $project->title()->html().' - © '.$site->title()->html() ?>" 
						width="100%" height="auto" />
					</div>
			</a>
				
			</div>

		<?php endif ?>

	<?php endforeach ?>
	<?php if($isFeatured){ $min = 6; } else { $min = 12; }  ?>
	<?php if($projects->count() < $min): ?>
		<?php for ($i=0; $i < $min - $projects->count(); $i++): ?>
			<div class="project-item blank"></div>
		<?php endfor ?>
	<?php endif ?>
</div>

<?php if($projects->pagination() && $projects->pagination()->hasPages()): ?>
<!-- pagination -->
<nav id="pagination">

  <?php if($projects->pagination()->hasNextPage()): ?>
  <a class="next" href="<?php echo $projects->pagination()->nextPageURL() ?>"><h2>Next</h2></a>
  <?php endif ?>

  <?php if($projects->pagination()->hasPrevPage()): ?>
  <a class="prev" href="<?php echo $projects->pagination()->prevPageURL() ?>"><h2>Previous</h2></a>
  <?php endif ?>

</nav>
<div class="ajax-loading"><h2 class="infinite-scroll-request">Loading</h2></div>
<?php endif ?>
