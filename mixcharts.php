<?php

class MixcloudFeed {
	private $user;
	
	function addMixesToChart($mixes, TrackChart $chart) {
		foreach($mixes as $mix) {
			$this->addTracksToChart($mix, $chart);
		}
	}
	
	function addTracksToChart($mixslug, TrackChart $chart) {
		$url = "https://api.mixcloud.com/benxo/$mixslug/?access_token=" . ACCESS_TOKEN;
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

class Track {
	private $artist;
	private $title;
	
	public function __construct($artist, $title) {
		$this->artist = $artist;
		$this->title = $title;
	}
	
	public function getArtist() {
		return $this->artist;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getKey() {
		return "{$this->artist}\t{$this->title}";
	}
	
	public function __toString() {
		return "Track[{$this->artist} - {$this->title}]";
	}	
}

class TrackChartEntry {
	private $track;
	private $count = 1;
	
	public function __construct(Track $track) {
		$this->track = $track;
	}
	
	public function incr() {
		$this->count++;
	}
	
	public function getTrack() {
		return $this->track;
	}
	
	public function getCount() {
		return $this->count;
	}
	
	public function __toString() {
		return "ChartEntry[{$this->track}, {$this->count}]";
	}
}

class TrackChart {
	private $trackChartEntries = array();

	public function addTrack(Track $track) {
		$key = $track->getKey();
		if($this->trackChartEntries[$key]) {
			$this->trackChartEntries[$key]->incr();
		} else {
			$this->trackChartEntries[$key] = new TrackChartEntry($track);
		}
	}
	
	public function sort() {
		usort($this->trackChartEntries, function(TrackChartEntry $a, TrackChartEntry $b){
			if($a->getCount() > $b->getCount()) return -1;
			if($a->getCount() < $b->getCount()) return 1;
			return strcasecmp($a->getTrack()->getKey(), $b->getTrack()->getKey());
		});
	}

	public function __toString() {
		$this->sort();
		$out = "Chart[\n";
		$out .= implode(",\n", $this->trackChartEntries);
		return $out . "\n]";
	}
}

define('ACCESS_TOKEN', $argv[1]);

$chart = new TrackChart();
$f = new MixcloudFeed();
$f->addTracksToChart('ben-xo-xpansion-pack-2018-07-31', $chart);
$f->addTracksToChart('ben-xo-beats-records-2018-07-24', $chart);
$f->addTracksToChart('ben-xo-higher-purchase-2018-07-17', $chart);

echo $chart;