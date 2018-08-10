<?php

namespace Mixcharts;

class MixcloudFeed {
	private $user;
	
	function __construct($user) {
		$this->user = $user;
	}
	
	function addAllMixesToChart(TrackChart $chart) {
		$feedPage = $this->getCloudcastPage();
		do {
			$this->addMixesToChart($feedPage->getCloudcasts(), $chart);
		} while($feedPage = $feedPage->getNextPage());
	}

	function getCloudcastPage() {
		return new CloudcastPage($this->user);
	}
	
	function addMixesToChart($mixes, TrackChart $chart) {
		foreach($mixes as $mix) {
			$this->addTracksToChart($mix, $chart);
		}
	}
	
	function addTracksToChart($mixslug, TrackChart $chart) {
		$url = "https://api.mixcloud.com{$mixslug}?access_token=" . ACCESS_TOKEN;
		debug("Fetching $url");
		$json = file_get_contents($url);
		$data = json_decode($json);
		foreach($data->sections as $s) {
			if($s->section_type == 'track') {
				$track = new Track($s->track->artist->name, $s->track->name);
				$chart->addTrack($track);
			}
		}
	}
}