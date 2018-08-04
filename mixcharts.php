<?php

namespace Mixcharts;

spl_autoload_register( function($class) { include str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php'; } );

$options = getopt('', ['token:', 'user:']);

if(!isset($options['token']) || !isset($options['user'])) {
	echo "Requires --token and --user\n";
	exit -1;
}

define('ACCESS_TOKEN', $options['token']);

$chart = new TrackChart();
$f = new MixcloudFeed($options['user']);
$f->addTracksToChart('ben-xo-xpansion-pack-2018-07-31', $chart);
$f->addTracksToChart('ben-xo-beats-records-2018-07-24', $chart);
$f->addTracksToChart('ben-xo-higher-purchase-2018-07-17', $chart);

echo $chart;