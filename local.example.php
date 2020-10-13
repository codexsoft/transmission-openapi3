<?php

use CodexSoft\ComposerLocalPackageUpdater\Updater;

require __DIR__.'/vendor/autoload.php';

(new Updater())
    ->setComposerOptions('--no-plugins --no-scripts --no-cache -vvv')
    ->add('codexsoft/db-first', 'dev-release/2.2.x', '/home/user/code/github/codexsoft/db-first')
    ->setMergeConfig([
        "prefer-stable" => true,
        "minimum-stability" => "dev",
        'repositories' => [
            [
                'packagist.org' => false,
            ]
        ],
    ])
    ->run(false);
