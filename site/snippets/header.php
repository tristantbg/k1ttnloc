<!DOCTYPE html>
<html lang="en" class="no-js">
<head>

	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="dns-prefetch" href="//www.google-analytics.com">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="canonical" href="<?php echo html($page->url()) ?>" />
	<?php if($page->isHomepage()): ?>
		<title><?= $site->title()->html() ?></title>
	<?php else: ?>
		<title><?= $pageTitle ?> | <?= $site->title()->html() ?></title>
	<?php endif ?>
	<?php if($page->isHomepage()): ?>
		<meta name="description" content="<?= $site->description()->html() ?>">
	<?php else: ?>
		<meta name="DC.Title" content="<?= $page->title()->html() ?>" />
		<?php if(isset($description)): ?>
			<meta name="description" content="<?= $description ?>">
			<meta name="DC.Description" content="<?= $description ?>"/ >
			<meta property="og:description" content="<?= $description ?>" />
		<?php elseif(!$page->text()->empty()): ?>
			<meta name="description" content="">
			<meta name="DC.Description" content=""/ >
			<meta property="og:description" content="" />
		<?php else: ?>
			<meta name="description" content="">
			<meta name="DC.Description" content=""/ >
			<meta property="og:description" content="" />
		<?php endif ?>
	<?php endif ?>
	<meta name="robots" content="index,follow" />
	<meta name="keywords" content="<?= $site->keywords()->html() ?>">
	<?php if($page->isHomepage()): ?>
		<meta itemprop="name" content="<?= $site->title()->html() ?>">
		<meta property="og:title" content="<?= $site->title()->html() ?>" />
	<?php else: ?>
		<meta itemprop="name" content="<?= $pageTitle ?> | <?= $site->title()->html() ?>">
		<meta property="og:title" content="<?= $pageTitle ?> | <?= $site->title()->html() ?>" />
	<?php endif ?>
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?= html($page->url()) ?>" />
	<?php if($page->content()->name() == "project"): ?>
		<?php if (!$page->featured()->empty()): ?>
			<?php $ogimage = $page->featured()->toFile()->width(1200) ?>
			<meta property="og:image" content="<?= $ogimage->url() ?>"/>
			<meta property="og:image:width" content="<?= $ogimage->width() ?>"/>
			<meta property="og:image:height" content="<?= $ogimage->height() ?>"/>
		<?php endif ?>
	<?php else: ?>
		<?php if(!$site->ogimage()->empty()): ?>
			<?php $ogimage = $site->ogimage()->toFile()->width(1200) ?>
			<meta property="og:image" content="<?= $ogimage->url() ?>"/>
			<meta property="og:image:width" content="<?= $ogimage->width() ?>"/>
			<meta property="og:image:height" content="<?= $ogimage->height() ?>"/>
		<?php endif ?>
	<?php endif ?>
	<meta itemprop="description" content="<?= $site->description()->html() ?>">

	<link rel="icon" type="image/gif" href="<?= url('assets/images/favicon-32x32.gif') ?>" sizes="32x32" />
	<link rel="icon" type="image/gif" href="<?= url('assets/images/favicon-16x16.gif') ?>" sizes="16x16" />

	<?php 
	echo css('assets/css/build/build.min.css?=v4');
	echo js('assets/js/vendor/modernizr.min.js');
	?>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?= url('assets/js/vendor/jquery.min.js') ?>">\x3C/script>')</script>

	<?php if(!$site->customcss()->empty()): ?>
		<style type="text/css">
			<?php echo $site->customcss()->html() ?>
		</style>
	<?php endif ?>

</head>

<body class="<?= $bodyClass ?>">

<div id="outdated">
	<div class="inner">
	<p class="browserupgrade">You are using an <strong>outdated</strong> browser.
	<br>Please <a href="http://outdatedbrowser.com" target="_blank">upgrade your browser</a> to improve your experience.</p>
	</div>
</div>

<div class="loader"></div>

<div id="main">

<header>
	<div id="site-title">
		<a href="<?= $site->url() ?>" data-target="projects">
			<h1><?= $site->title()->html() ?></h1>
		</a>
	</div>
	<div id="filters">
		<?php if ($page->intendedTemplate() == "clientpage"): ?>
			<?php

			$linkTo = '<a href="'. site()->url().'/'.$page->autoid().'/'.$page->uid() .'" data-target="projects">';
			$projectHeader = $linkTo.$page->title()->html().'</a>';
			if ($page->projectTitle()->isNotEmpty()){
				$projectHeader .= $linkTo.$page->projectTitle()->html().'</a>';
			}
			if ($page->date()){
				$projectHeader .= $linkTo.$page->date('d M Y').'</a>';
			}

			echo $projectHeader;

			?>
		<?php endif ?>
		<?php if ($page->intendedTemplate() == "project"): ?>
			<?php e($page->projectHeader(), $page->projectHeader()) ?>
		<?php endif ?>
	</div>
	<div id="information">
		<?php if ($page->intendedTemplate() == "clientpage"): ?>
			<a href="<?= $site->url() ?>" data-target>Exit</a>
		<?php elseif ($page->intendedTemplate() == "project"): ?>
			<a href="<?= $site->url() ?>" data-target>Exit</a>
		<?php else: ?>
			<a href="https://www.kittenproduction.com">Exit</a>
		<?php endif ?>
	</div>
</header>

<div id="container">