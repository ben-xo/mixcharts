<?php
namespace Mixcharts;

spl_autoload_register(function ($class) {
    include str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
});

$options = getopt('', [
    'term:',
]);

if (! isset($options['term'])) {
    echo "Requires --term\n";
    exit(-1);
}

$db = new MixcloudDB('mixcharts.db');
if(!$db->connect()) {
    echo "Couldn't open mixcharts.db\n";
    exit(-2);
}

var_dump($db->getMixesLike($options['term']));
var_dump($db->getMixesWithTracksLike($options['term']));

