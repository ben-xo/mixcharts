<?php

namespace Mixcharts;

class TrackChart {
	private $trackChartEntries = array();
	private $cutoff = 0;
	
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
	
	public function setCutoff($count) {
		$this->cutoff = $count;
	}
	
	public function toTSV() {
		echo "count\tartist\ttitle\n";
		foreach($this->trackChartEntries as $tce) {
			if($tce->getCount() < $this->cutoff) break;
			echo $tce->getCount() . "\t" . $tce->getTrack()->getArtist() . "\t" . $tce->getTrack()->getTitle() . "\n";
		}
	}
	
	public function __toString() {
		$this->sort();
		$out = "Chart[\n";
		$out .= implode(",\n", $this->trackChartEntries);
		return $out . "\n]";
	}
}
