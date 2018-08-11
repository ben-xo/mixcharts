<?php

namespace Mixcharts;

class MixcloudClient {
	private $access_token;
	
	function __construct($access_token) {
		$this->access_token = $access_token;	
	}
	
	function debug($s) { echo "$s\n"; }
	
	function getUrl($url) {
		if(strstr($url, '?')) {
			$token = "&access_token={$this->access_token}";
		} else {
			$token = "?access_token={$this->access_token}";
		}
		$url = "{$url}{$token}";
		$this->debug("Fetching $url");
		$json = file_get_contents($url);
		return json_decode($json);		
	}
}
