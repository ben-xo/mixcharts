<?php

namespace Mixcharts;

spl_autoload_register(function ($class) {
    include str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
});

$fail=false;
$term = $_GET['term'];
$mixes_like = [];
$mixes_tracks_like = [];
if($term != '') {
    try {
        $db = new MixcloudDB('mixcharts.db');
        if(!$db->connect()) {
            $fail=true;
        } else {
            $mixes_like = $db->getMixesLike($term);
            $mixes_tracks_like = $db->getMixesWithNestedTracks($term);
        }
    } catch (Exception $e) {
        $fail=true;
    }
}

?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
    </head>
    <body>
    <?php if($fail): ?>
    <h2>Sorry, this page isn't working today.</h2>
    <?php endif; ?>

    <?php if($term): ?>
    <ul>
        <?php if($mixes_like || $mixes_tracks_like): ?>
        <ul>
            <?php foreach ($mixes_like as $mix): ?>
                <li><a href="<?php echo htmlspecialchars(str_replace('api.', 'www.', $mix['slug'])); ?>"><?php echo htmlspecialchars($mix['name']); ?></a></li>
            <?php endforeach; ?>
            <?php foreach ($mixes_tracks_like as $name => $mix): ?>
                <li><a href="<?php echo htmlspecialchars(str_replace('api.', 'www.', $mix['slug'])); ?>"><?php echo htmlspecialchars($name); ?></a>
                    <small><ul>
                      <?php foreach ($mix['tracks'] as $track): ?>
                        <li><?php echo htmlspecialchars($track['artist'] . ' - ' . $track['title']); ?></li>
                      <?php endforeach; ?>
                    </ul></small><br>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
            <li>Sorry, none found that match.</li>
        <?php endif; ?>
    </ul>
    <?php endif; ?>
    </body>
</html>
