<?php

$composerFile = 'composer.json';

$composer = json_decode(file_get_contents($composerFile), true);

if (!isset($composer['autoload']['files'])) {
    $composer['autoload']['files'] = ['app/Dependencies/DI/functions.php'];

    file_put_contents($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    echo "Added 'files' autoload to composer.json.\n";
} else {
    echo "'files' autoload is already set.\n";
}
