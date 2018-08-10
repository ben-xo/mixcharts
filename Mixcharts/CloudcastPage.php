<?php

namespace Mixcharts;

class CloudcastPage {
	private $user;
	private $url;
	private $next;

	function __construct($user, $url='') {
		$this->user = $user;
		if(empty($url)) {
			$this->url = "https://api.mixcloud.com/{$this->user}/cloudcasts/";
		} else {
			$this->url = $url;
		}
	}

	function getCloudcasts() {
		debug("Fetching {$this->url}");
		$json = file_get_contents($this->url);
		$data = json_decode($json);

		if(isset($data->paging->next)) {
			$this->next = $data->paging->next;
		}

		$cloudcasts = array();

		foreach($data->data as $mix) {
			$cloudcasts[] = $mix->key;
		}

		return $cloudcasts;
	}

	function getNextPage() {
		if($this->next) {
			return new CloudcastPage($this->user, $this->next);
		}
	}
}