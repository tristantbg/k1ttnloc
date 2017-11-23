<?php

/*

---------------------------------------
License Setup
---------------------------------------

Please add your license key, which you've received
via email after purchasing Kirby on http://getkirby.com/buy

It is not permitted to run a public website without a
valid license key. Please read the End User License Agreement
for more information: http://getkirby.com/license

*/

c::set('license', 'put your license key here');

/*

---------------------------------------
Kirby Configuration
---------------------------------------

By default you don't have to configure anything to
make Kirby work. For more fine-grained configuration
of the system, please check out http://getkirby.com/docs/advanced/options

*/

c::set('debug', true);
c::set('home', 'locations');
c::set('plugin.embed.video.lazyload', true);
c::set('plugin.embed.video.lazyload.btn', 'assets/images/play.png');
c::set('kirbytext.image.figure', false);
c::set('textarea.autocomplete', true);
c::set('autoid.type', 'hash');
//Typo
c::set('typography', true);
c::set('typography.ordinal.suffix', false);
c::set('typography.fractions', false);
c::set('typography.dashes.spacing', false);
c::set('typography.hyphenation', true);
//c::set('typography.hyphenation.language', 'fr');
//c::set('typography.hyphenation.minlength', 5);
c::set('typography.hyphenation.headings', false);
c::set('typography.hyphenation.allcaps', false);
c::set('typography.hyphenation.titlecase', false);
c::set('typography.hyphenation.exceptions', ["advertising", "kitten-production"]);
//Settings
c::set('sitemap.exclude', array('error'));
c::set('sitemap.important', array('contact'));
c::set('thumb.quality', 100);
//c::set('thumbs.driver', 'im');
c::set('routes', array(
	array(
		'pattern' => '(:any)',
		'action'  => function($autoid) {
			$clientPage = page(site()->index()->filterBy('autoid', $autoid)->first());
			// if (!site()->user() && $page) {
			// 	$user = site()->user('client');
			// 	$user->login('h@sACce$$');
			// }
			if ($clientPage && $clientPage->isVisible()) {
				return $clientPage;
			}
			else {
				go(site()->homePage());
			}
		}
	),
	array(
		'pattern' => 'locations/(:any)',
		'action'  => function($uid) {
			if (!site()->user()) {
				go(site()->homePage());
			} else {
				return page("locations/".$uid);
			}
		}
	),
	array(
		'pattern' => '(:any)/(:any)',
		'action'  => function($autoid,$uid) {
			$clientPage = page(site()->index()->filterBy('autoid', $autoid)->first());
			// if ($page) {
			// 	$user = site()->user('client');
			// 	$user->login('h@sACce$$');
			// }
			$location = page('locations/'.$uid);
			$hasAccess = in_array($location->autoid(), $clientPage->visibleLocations()->split());
			if ($clientPage && $clientPage->isVisible() && $location && $hasAccess) {
				return $location;
			} else {
				go(site()->homePage());
			}
		}
	),
	array(
		'pattern' => 'robots.txt',
		'action' => function () {
			return new Response(
				'User-agent: *
				Disallow: /');
		}
	)
));
kirby()->hook(['panel.page.create'], function($page) {
	if ($page->intendedTemplate() == "project") {
		$page->update(array(
			'featuredvideo' => 'no'
		));
	}
});