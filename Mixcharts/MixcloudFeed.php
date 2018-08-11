<?php

namespace Mixcharts;

class MixcloudFeed {
	private $user;
	private $client;
	
	function __construct($user, MixcloudClient $client) {
		$this->user = $user;
		$this->client = $client;
	}
	
	function addAllMixesToChart(TrackChart $chart) {
		$feedPage = $this->getCloudcastPage();
		do {
			$this->addMixesToChart($feedPage->getCloudcasts(), $chart);
		} while($feedPage = $feedPage->getNextPage());
	}

	function getCloudcastPage() {
		return new CloudcastPage($this->user, $this->client);
	}
	
	function addMixesToChart($mixes, TrackChart $chart) {
		foreach($mixes as $mix) {
			$this->addTracksToChart($mix, $chart);
		}
	}
	
	function addTracksToChart($mixslug, TrackChart $chart) {
		$data = $this->client->getUrl($mixslug);
		foreach($data->sections as $s) {
			if($s->section_type == 'track') {
				$track = new Track($s->track->artist->name, $s->track->name);
				$chart->addTrack($track);
			}
		}
	}
}