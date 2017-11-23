<?php

return function ($site, $pages, $page, $args) {

	$title = $page->title()->html();
	$artist = $page->artist()->html();
	$pageTitle = $title;
	if($page->artist()->isNotEmpty()) $pageTitle .= ' × '.$artist;
	$description = null;
	if ($page->text()->isNotEmpty()) $description = $page->text()->kt();
	$additionalText = null;
	if ($page->additionalText()->isNotEmpty()) $additionalText = $page->additionalText()->kt();

	return array(
	'bodyClass' => $page->category().' project', 
	'pageTitle' => $pageTitle, 
	'title' => $title, 
	'artist' => $artist, 
	'description' => $description, 
	'additionalText' => $additionalText, 
	'category' => $page->category(), 
	'images' => $page->medias()->toStructure(),
	);
}

?>
