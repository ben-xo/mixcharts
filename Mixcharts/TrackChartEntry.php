<?php

namespace Mixcharts;

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