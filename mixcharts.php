<?php

namespace Mixcharts;

define('ACCESS_TOKEN', $argv[1]);

spl_autoload_register( function($class) { include str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php'; } );

$chart = new TrackChart();
$f = new MixcloudFeed();
$f->addTracksToChart('ben-xo-xpansion-pack-2018-07-31', $chart);
$f->addTracksToChart('ben-xo-beats-records-2018-07-24', $chart);
$f->addTracksToChart('ben-xo-higher-purchase-2018-07-17', $chart);

echo $chart;