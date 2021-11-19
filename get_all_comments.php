<?php
namespace Mixcharts;

spl_autoload_register(function ($class) {
    include str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
});

$options = getopt('', [
    'token:',
    'user:',
]);

if (! isset($options['token']) || ! isset($options['user'])) {
    echo "Requires --token and --user\n";
    exit(-1);
}


$client = new MixcloudClient($options['token']);
$f = new MixcloudFeed($options['user'], $client);
$f->dumpAllComments();

echo "Done!\n";
