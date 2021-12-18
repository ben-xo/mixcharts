<?php
namespace Mixcharts;

spl_autoload_register(function ($class) {
    include str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
});

$options = getopt('', [
    'metric:',
    'cutoff:'
]);

if (! isset($options['metric'])) {
    echo "Requires --metric [play, listener, favorite, repost, comment]\n";
    exit(-1);
}

$db = new MixcloudDB('mixcharts.db');
if(!$db->connect()) {
    echo "Couldn't open mixcharts.db\n";
    exit(-2);
}

var_dump($db->getTopMixesBy($options['metric'], $options['cutoff']));

