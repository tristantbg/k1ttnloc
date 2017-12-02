<?php snippet('header') ?>

<div id="page-content" class="project">
	
	<div id="project-content">
		
		<div class="slider">

		<div id="mouse-nav"></div>
		
		<?php foreach ($images as $key => $image): ?>
		
			<?php if($image = $image->toFile()): ?>
			<?php $isVideo = $image->videofile()->isNotEmpty() || $image->videolink()->isNotEmpty() || $image->videoexternal()->isNotEmpty() ?>
		
			<div class="slide" 
			<?php if($image->caption()->isNotEmpty()): ?>
			data-caption="<?= $image->caption()->kt()->escape() ?>"
			<?php endif ?>
			data-media="<?= e($isVideo, 'video', 'image') ?>"
			>
			
			<?php if($isVideo): ?>
				<div class="content video">
					<?php 
					$poster = thumb($image, array('width' => 1500))->url();

					if ($image->videostream()->isNotEmpty() || $image->videoexternal()->isNotEmpty() || $image->videofile()->isNotEmpty()) {
						$video  = '<video class="media js-player"';
						$video .= ' poster="'.$poster.'"';
						if ($image->videostream()->isNotEmpty()) {
							$video .= ' data-stream="'.$image->videostream().'"';
						}
						$video .= ' width="100%" height="100%" controls="false" loop>';
						if ($image->videoexternal()->isNotEmpty()) {
							$video .= '<source src=' . $image->videoexternal() . ' type="video/mp4">';
						} else if ($image->videofile()->isNotEmpty()){
							$video .= '<source src=' . $image->videofile()->toFile()->url() . ' type="video/mp4">';
						}
						$video .= '</video>';
						echo $video;
					} 
					else {
						$url = $image->videolink();
						if ($image->vendor() == "youtube") {
							echo '<div class="media js-player" data-type="youtube" data-video-id="' . $url  . '"></div>';
						} else {
							echo '<div class="media js-player" data-type="vimeo" data-video-id="' . $url  . '"></div>';
						}
					}
					?>
				</div>
			<?php else: ?>
				<div class="content">
					<img class="media lazyimg" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-flickity-lazyload="<?= thumb($image, array('height' => 1500))->url() ?>" alt="<?= $title.' - © '.$site->title()->html() ?>" height="100%" width="auto" />
					<noscript>
						<img src="<?= thumb($image, array('height' => 1500))->url() ?>" alt="<?= $title.' - © '.$site->title()->html() ?>" height="100%" width="auto" />
					</noscript>
				</div>
			<?php endif ?>
		
			</div>
		
			<?php endif ?>
		
		<?php endforeach ?>
		
		</div>
		
		<div id="bottom-bar">
			<a data-target="back">Back</a>
			<div class="project-infos">
				<div id="slide-number">1/<?= $images->count() ?></div>
				<div class="project-title">
					<h1><?= $pageTitle ?></h1>
				</div>
				<?php if($additionalText): ?>
				<div class="project-description additional">
					<?= $additionalText ?>
				</div>
				<?php endif ?>
			</div>
			<a event-target="more"><?php //e($page->text()->isNotEmpty(), 'Infos') ?></a>
		</div>

	</div>

</div>

<?php snippet('footer') ?>