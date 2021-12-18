<?php

namespace Mixcharts;

class MixcloudFeed {
	private $user;
	private $client;
	
	function __construct($user, MixcloudClient $client) {
		$this->user = $user;
		$this->client = $client;
	}
	
	function debug($s) { echo "$s\n"; }
	
	function addAllMixesToChart(TrackChart $chart) {
		$feedPage = $this->getCloudcastPage();
		do {
			$this->addMixesToChart($feedPage->getCloudcasts(), $chart);
		} while($feedPage = $feedPage->getNextPage());
	}

	function dumpAllComments() {
		$feedPage = $this->getCloudcastPage();
		do {
			$this->dumpMixPageComments($feedPage->getCloudcasts());
		} while($feedPage = $feedPage->getNextPage());
	}

	function dumpMixPageComments($mixes) {
		foreach($mixes as $mix) {
			$this->dumpMixComments($mix);
		}
	}

	function dumpMixComments($mixslug) {
		$data = $this->client->getUrl("${mixslug}comments/");
		foreach($data->data as $c) {
			echo "$mixslug\n";
			echo "{$c->user->name}\n";
			echo "{$c->submit_date}\n";
			echo "{$c->comment}\n\n";
		}
	}

	function addAllMixesToDB(MixcloudDB $db) {
	    $feedPage = $this->getCloudcastPage();
	    do {
	        $this->addMixesToDB($feedPage->getCloudcasts(), $db);
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
	
	function addMixesToDB($mixes, MixcloudDB $db) {
	    $total_tracks_added = 0;
	    $total_mixes_added = 0;
	    foreach($mixes as $mix) {
	        $total_tracks_added += $this->addMixToDB($mix, $db);
	        $total_mixes_added++;
	    }
	    $this->debug("Added $total_mixes_added mixes and $total_tracks_added tracks to DB (some or all may already have been present)");
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
	
	function addMixToDB($mixslug, MixcloudDB $db) {
	    if($mix_data = $db->getMix($mixslug)) {
	        return 0;
	    }
	    $mix_data = $this->client->getUrl($mixslug);
	    $mix = Mix::fromJson($slug, $mix_data);
	    $db->addMix($mix);
	    return $this->addTracksToDB($mixslug, $db, $mix_data);
	}
	
	function addTracksToDB($mixslug, MixcloudDB $db, $mix_data) {
	    if(!$mix_data) {
	        $mix_data = $this->client->getUrl($mixslug);
	    }
	    $track_count = 0;
	    foreach($mix_data->sections as $s) {
	        if($s->section_type == 'track') {
	            $track = new Track($s->track->artist->name, $s->track->name);
	            $db->addTrackToMix($mixslug, $track);
	            $track_count++;
	        }
	    }
	    return $track_count;
	}
}