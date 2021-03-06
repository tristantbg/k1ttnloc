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
		'pattern' => '(:any)/(:any)',
		'action'  => function($autoid,$clientUid) {
			if ($autoid != "locations") {
				$clientPage = page(site()->index()->filterBy('autoid', $autoid)->first());

				// End Date
				$endDate = $clientPage->date();
				if($clientPage->dateEnd()->isNotEmpty()) $endDate = $clientPage->date(null, 'dateEnd');

				if (!site()->user() && $endDate && strtotime('00:00:00') > $endDate){
					// Event is in the past
					go(site()->homePage());
				}
				else if ($clientPage && $clientPage->isVisible() && $clientPage->uid() == $clientUid) {
					return $clientPage;
				}
				else {
					go(site()->homePage());
				}
			} elseif(site()->user()) {
				return page($autoid."/".$clientUid);
			} else {
				go(site()->homePage());
			}
		}
	),
	array(
		'pattern' => 'locations/(:any)',
		'action'  => function($uid) {
			$location = page("locations/".$uid);
			if (!site()->user()) {
				go(site()->homePage());
			} else if($location) {
				return $location;
			} else {
				go(site()->homePage());
			}
		}
	),
	array(
		'pattern' => 'clients/(:any)',
		'action'  => function($uid) {
			$clientPage = page("clients/".$uid);
			if (!site()->user()) {
				go(site()->homePage());
			} else if($clientPage) {
				return $clientPage;
			} else {
				go(site()->homePage());
			}
		}
	),
	array(
		'pattern' => '(:any)/(:any)/(:any)',
		'action'  => function($autoid,$clientUid,$uid) {
			$clientPage = page(site()->index()->filterBy('autoid', $autoid)->first());
			// Load page
			$location = page('locations/'.$uid);
			$linkTo = '<a href="'. site()->url().'/'.$clientPage->autoid().'/'.$clientPage->uid() .'" data-target="projects">';
			$location->projectHeader = $linkTo.$clientPage->title()->html().'</a>';
			if ($clientPage->projectTitle()->isNotEmpty()){
				$location->projectHeader .= $linkTo.$clientPage->projectTitle()->html().'</a>';
			}
			if ($clientPage->date()){
				$location->projectHeader .= $linkTo.$clientPage->date('d M Y');
				if ($clientPage->dateEnd()->isNotEmpty()) $location->projectHeader .= ' - '.$clientPage->date('d M Y', 'dateEnd');
				$location->projectHeader .= '</a>';
			}
			$hasAccess = in_array($location->autoid(), $clientPage->visibleLocations()->split());

			// End Date
			$endDate = $clientPage->date();
			if($clientPage->dateEnd()->isNotEmpty()) $endDate = $clientPage->date(null, 'dateEnd');

			if (!site()->user() && $endDate && strtotime('00:00:00') > $endDate){
				// Event is in the past
				go(site()->homePage());
			}
			elseif ($clientPage && $clientPage->isVisible() && $clientPage->uid() == $clientUid && $location && $hasAccess) {
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