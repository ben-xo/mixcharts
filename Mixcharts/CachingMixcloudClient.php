<?php

namespace Mixcharts;

class CachingMixcloudClient extends MixcloudClient {
    private $cachefile;
    
    function __construct($cachefile, $access_token) {
        parent::__construct($access_token);
        $this->cachefile = $cachefile;
    }

    function getUrl($url) {
        $data = $this->getFromCache($url);
        if(!$data) {
            $data = parent::getUrl($url);
            $this->cache($url, $data);
        }
        return $data;
    }

    function getFromCache($path) {
        $fn = $this->getFilename($path);
        if(file_exists($fn)) {
            $this->debug("Got $path from cache");
            return json_decode(file_get_contents($fn));
        }
    }

    function cache($path, $data) {
        $this->debug("Caching $path");
        file_put_contents($this->getFilename($path), json_encode($data));
    }

    function uncache($path) {
        $this->debug("Uncaching $path");
        unlink($this->getFilename($path));
    }

    function getFilename($path) {
        return $this->cachefile . '/' . $this->safeName($path).'.json';
    }

    function safeName($name) {
        return preg_replace('/[^a-z0-9_-]/i', '__', $name);
    }
}
