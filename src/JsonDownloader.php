<?php

declare(strict_types=1);

namespace DevboardLib\Thesting\Source;

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

    $branchFolder            = $baseDir.$repo.'/branches/';
    $tagFolder               = $baseDir.$repo.'/tags/';
    $commitFolder            = $baseDir.$repo.'/commits/';
    $commitStatusesFolder    = $baseDir.$repo.'/commit-statuses/';

    $commits = [];

    if (!is_dir($branchFolder)) {
        mkdir($branchFolder, 0777, true);
    }
    if (!is_dir($tagFolder)) {
        mkdir($tagFolder, 0777, true);
    }
    if (!is_dir($commitFolder)) {
        mkdir($commitFolder, 0777, true);
    }
    if (!is_dir($commitStatusesFolder)) {
        mkdir($commitStatusesFolder, 0777, true);
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
        $commits[] = $branchItem['commit']['sha'];
    }

    //Handle tags.json
    $response = $client->request('GET', 'https://api.github.com/repos/'.$repo.'/tags', $options);
    $tagsData = $response->getBody()->getContents();

    file_put_contents($baseDir.$repo.'/tags.json', $tagsData);

    $tags = json_decode($tagsData, true);

    foreach ($tags as $tagItem) {
        $tag      = urlencode($tagItem['name']);
        $response = $client->request('GET', 'https://api.github.com/repos/'.$repo.'/git/refs/tags/'.$tag, $options);
        file_put_contents($tagFolder.$tag.'.json', $response->getBody());
        $commits[] = $tagItem['commit']['sha'];
    }

    foreach ($commits as $commit) {
        $response = $client->request('GET', 'https://api.github.com/repos/'.$repo.'/git/commits/'.$commit, $options);
        file_put_contents($commitFolder.$commit.'.json', $response->getBody());
    }

    foreach ($commits as $commit) {
        $response = $client->request('GET', 'https://api.github.com/repos/'.$repo.'/statuses/'.$commit, $options);
        file_put_contents($commitStatusesFolder.$commit.'.json', $response->getBody());
    }
}
