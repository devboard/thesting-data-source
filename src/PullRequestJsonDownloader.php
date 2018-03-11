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

    $prFolder            = $baseDir.$repo.'/pr/';

    if (!is_dir($prFolder)) {
        mkdir($prFolder, 0777, true);
    }

    //Download PR list
    $baseUrl         = 'https://api.github.com/repos/'.$repo.'/pulls?state=all';
    $response        = $client->request('GET', $baseUrl, $options);
    $pullRequestList =json_decode($response->getBody()->getContents(), true);

    foreach ($pullRequestList as $item) {
        $number = $item['number'];

        file_put_contents($prFolder.$number.'.json', json_encode($item));
    }
}
