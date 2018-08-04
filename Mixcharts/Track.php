<?php

namespace Mixcharts;

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
