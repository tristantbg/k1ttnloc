<?php

return function ($site, $pages, $page) {

	$menuPages = $pages->visible()->filterBy('intendedTemplate', 'not in', ['project', 'projects']);
	return array(
		'bodyClass' => 'page',
		'pageTitle' => $page->title()->html(),
		'menuPages' => $menuPages
	);
}

?>
