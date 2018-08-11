<?php

namespace Mixcharts;

spl_autoload_register( function($class) { include str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php'; } );

$options = getopt('', ['token:', 'user:', 'cutoff:']);

if(!isset($options['token']) || !isset($options['user'])) {
	echo "Requires --token and --user\n";
	exit -1;
}

$chart = new TrackChart();

if($options['cutoff']) {
	$chart->setCutoff($options['cutoff']);
}

$client = new CachingMixcloudClient("cache", $options['token']);
$f = new MixcloudFeed($options['user'], $client);
$f->addAllMixesToChart($chart);

$chart->sort();
echo $chart->toTSV();