<?php

return function ($site, $pages, $page) {

	$projects = new Collection();
	$paginate = 12;

	foreach ($page->visibleLocations()->split() as $key => $id) {
		$projects->data[] = page($site->index()->filterBy('autoid', $id)->first());
	}

	return array(
		'bodyClass' => 'projects',
		'pageTitle' => $page->title()->html(),
		'projects' => $projects
	);
}

?>
