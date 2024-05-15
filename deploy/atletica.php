<?php

namespace Deployer;

set('release_name', function () {
    return (string) run('date +"%Y%m%d%H%M%S"');
});

// Override del metodo standard in attesa di rilascio versione che risolve il problema con windows
desc('Prepares release');
task('deploy:release', function () {
    cd('{{deploy_path}}');

    // Clean up if there is unfinished release.
    if (test('[ -h release ]')) {
        run('rm release'); // Delete symlink.
    }

    // We need to get releases_list at same point as release_name,
    // as standard release_name's implementation depends on it and,
    // if user overrides it, we need to get releases_list manually.
    $releasesList = get('releases_list');
    $releaseName = get('release_name');
    $releasePath = "releases/$releaseName";

    // Check what there is no such release path.
    if (test("[ -d $releasePath ]")) {
        $freeReleaseName = '...';
        // Check what $releaseName is integer.
        if (ctype_digit($releaseName)) {
            $freeReleaseName = intval($releaseName);
            // Find free release name.
            while (test("[ -d releases/$freeReleaseName ]")) {
                $freeReleaseName++;
            }
        }
        throw new Exception("Release name \"$releaseName\" already exists.\nRelease name can be overridden via:\n dep deploy -o release_name=$freeReleaseName");
    }

    // Save release_name.
    if (is_numeric($releaseName) && is_integer(intval($releaseName))) {
        run("echo $releaseName > .dep/latest_release");
    }

    // Metainfo.
    $timestamp = timestamp();
    $metainfo = [
        'created_at' => $timestamp,
        'release_name' => $releaseName,
        'user' => get('user'),
        'target' => get('target'),
    ];

    // Save metainfo about release.
    if (PHP_OS_FAMILY === "Windows") {
        $json = addcslashes(json_encode($metainfo), '\\"');
    } else {
        $json = escapeshellarg(json_encode($metainfo));
    }
    run("echo $json >> .dep/releases_log");

    // Make new release.
    run("mkdir -p $releasePath");
    run("{{bin/symlink}} $releasePath {{deploy_path}}/release");

    // Add to releases list.
    array_unshift($releasesList, $releaseName);
    set('releases_list', $releasesList);

    // Set previous_release.
    if (isset($releasesList[1])) {
        set('previous_release', "{{deploy_path}}/releases/{$releasesList[1]}");
    }
});

//desc('Runs the tenants database migrations');
//task('artisan:tenants:migrate', artisan('tenants:migrate'));
