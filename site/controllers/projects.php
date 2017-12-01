<?php

return function ($site, $pages, $page) {

	$projects = new Collection();
	if($site->user()) $projects = $page->children()->visible();

	return array(
		'bodyClass' => 'projects',
		'pageTitle' => $page->title()->html(),
		'projects' => $projects
	);
}

?>
