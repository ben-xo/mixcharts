<?php

namespace Mixcharts;

spl_autoload_register( function($class) { include str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php'; } );
function debug($s) { echo "$s\n"; }

$options = getopt('', ['token:', 'user:']);

if(!isset($options['token']) || !isset($options['user'])) {
	echo "Requires --token and --user\n";
	exit -1;
}

define('ACCESS_TOKEN', $options['token']);

$chart = new TrackChart();
$f = new MixcloudFeed($options['user']);
$f->addAllMixesToChart($chart);

echo $chart;