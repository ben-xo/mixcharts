<?php
namespace Mixcharts;

spl_autoload_register(function ($class) {
    include str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
});

$options = getopt('', [
    'token:',
    'user:',
    'cutoff:'
]);

if (! isset($options['token']) || ! isset($options['user'])) {
    echo "Requires --token and --user\n";
    exit(-1);
}

$chart = new TrackChart();
$db = new MixcloudDB('mixcharts.db');
if(!$db->connect()) {
    echo "Couldn't open mixcharts.db\n";
    exit(-2);
}

if (isset($options['cutoff'])) {
    $chart->setCutoff($options['cutoff']);
}

$client = new CachingMixcloudClient("cache", $options['token']);
$f = new MixcloudFeed($options['user'], $client);
$client->uncache($f->getCloudcastPage()->getFirstPageURL()); // otherwise it won't pick up new mixes.
$f->addAllMixesToChart($chart);

$chart->sort();
echo $chart->toTSV();