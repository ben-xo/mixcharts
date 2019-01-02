<?php

namespace Mixcharts;

class CloudcastPage {
	private $user;
	private $url;
	private $next;
	private $client;
	function __construct($user, MixcloudClient $client, $url='') {
		$this->user = $user;
		$this->client = $client;
		if ($url == '') {
			$this->url = $this->getFirstPageURL();
		} else {
			$this->url = $url;
		}
	}

	function getCloudcasts() {
		$data = $this->client->getUrl( $this->url );
		
		if (isset ( $data->paging->next )) {
			$this->next = $data->paging->next;
		}
		
		$cloudcasts = array ();
		
		foreach ( $data->data as $mix ) {
			$cloudcasts [] = "https://api.mixcloud.com{$mix->key}";
		}
		
		return $cloudcasts;
	}

	function getFirstPageURL() {
		return "https://api.mixcloud.com/{$this->user}/cloudcasts/";
	}

	function getNextPage() {
		if ($this->next) {
			return new CloudcastPage ( $this->user, $this->client, $this->next);
		}
	}
}