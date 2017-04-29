<?php

declare(strict_types=1);

namespace Devboard\Thesting\Source;

use GuzzleHttp\Client;

require __DIR__.'/../vendor/autoload.php';

$client = new Client();

$testDataProvider = JsonSource::create();

$token = getenv('GITHUB_API_TOKEN');

$options = [
    'headers' => [
        'Authorization' => 'token '.$token,
        'Accept'        => 'application/vnd.github.v3+json',
    ],
];
$baseDir = __DIR__.'/../data/Json/';

foreach ($testDataProvider->getSupportedRepoNames() as $repo) {
    echo $repo.PHP_EOL;

    $branchFolder = $baseDir.$repo.'/branches/';
    $tagFolder    = $baseDir.$repo.'/tags/';

    if (!is_dir($branchFolder)) {
        mkdir($branchFolder, 0777, true);
    }
    if (!is_dir($tagFolder)) {
        mkdir($tagFolder, 0777, true);
    }

    //Handle repo.json
    $response = $client->request('GET', 'https://api.github.com/repos/'.$repo, $options);
    $repoData = $response->getBody()->getContents();

    file_put_contents($baseDir.$repo.'/repo.json', $repoData);

    //Handle branches.json
    $response     = $client->request('GET', 'https://api.github.com/repos/'.$repo.'/branches', $options);
    $branchesData = $response->getBody()->getContents();

    file_put_contents($baseDir.$repo.'/branches.json', $branchesData);

    $branches = json_decode($branchesData, true);

    foreach ($branches as $branchItem) {
        $branch   = urlencode($branchItem['name']);
        $response = $client->request('GET', 'https://api.github.com/repos/'.$repo.'/branches/'.$branch, $options);
        file_put_contents($branchFolder.$branch.'.json', $response->getBody());
    }

    //Handle tags.json
    $response = $client->request('GET', 'https://api.github.com/repos/'.$repo.'/tags', $options);
    $tagsData = $response->getBody()->getContents();

    file_put_contents($baseDir.$repo.'/tags.json', $tagsData);
}
