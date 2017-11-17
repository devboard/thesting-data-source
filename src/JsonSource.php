<?php

declare(strict_types=1);

namespace DevboardLib\Thesting\Source;

use Symfony\Component\Finder\SplFileInfo;

/**
 * @see JsonSourceSpec
 * @see JsonSourceTest
 */
class JsonSource
{
    /** @var JsonReader */
    private $reader;

    /** @var array */
    private $supportedRepoNames;

    public function __construct(JsonReader $jsonReader, array $supportedRepoNames)
    {
        $this->reader             = $jsonReader;
        $this->supportedRepoNames = $supportedRepoNames;
    }

    public static function create()
    {
        $reader = new JsonReader(__DIR__.'/../data/Json/');
        $repos  = [
            'devboard/devboard',
            'devboard/devboard-core',
            'devboard/github-api-facade',
            'devboard/github-api-facade-bundle',
            'devboard/github-core',
            'devboard/github-lib',
            'devboard/github-object-api-facade',
            'devboard/github-object-api-facade-bundle',
            'msvrtan/SkeletonBundle',
            'msvrtan/generator',
            'msvrtan/github-lib',
            'msvrtan/starter-edition',
            'msvrtan/broadway',
            'msvrtan/github-api-facade',
            'msvrtan/github-object-api-facade',
            'msvrtan/user-edition',
            'msvrtan/devboard',
            'msvrtan/github-api-facade-bundle',
            'msvrtan/github-object-api-facade-bundle',
            'msvrtan/devboard-core',
            'msvrtan/github-core',
            'msvrtan/skeleton-sandbox',
            'nulldevelopmenthr/SkeletonBundle',
            'nulldevelopmenthr/generator',
            'nulldevelopmenthr/starter-edition',
            'nulldevelopmenthr/user-edition',
            'octocat/Hello-World',
            'octocat/Spoon-Knife',
            'octocat/linguist',
            'octocat/octocat.github.io',
            'octocat/git-consortium',
            'octocat/test-repo1',
            'symfony/symfony',
            'symfony/symfony-standard',
            'symfony/symfony-docs',
        ];

        return new self($reader, $repos);
    }

    public function getSupportedRepoNames()
    {
        return $this->supportedRepoNames;
    }

    public function getRepo(string $repo): array
    {
        return $this->decode($this->reader->loadRepoContent($repo));
    }

    public function getRepos(): array
    {
        $results = [];

        foreach ($this->supportedRepoNames as $repo) {
            $results[$repo] = $this->decode($this->reader->loadRepoContent($repo));
        }

        return $results;
    }

    public function getBranches(string $repo): array
    {
        $results = [];

        foreach ($this->reader->getBranchFiles($repo) as $item) {
            $results[] = $this->decode($this->reader->loadBranchContent($repo, $this->extractName($item)));
        }

        return $results;
    }

    public function getBranch(string $repo, string $branch): array
    {
        return $this->decode($this->reader->loadBranchContent($repo, $branch));
    }

    public function getTags(string $repo): array
    {
        $results = [];

        foreach ($this->reader->getTagFiles($repo) as $item) {
            $results[] = $this->decode($this->reader->loadTagContent($repo, $this->extractName($item)));
        }

        return $results;
    }

    public function getTag(string $repo, string $tag): array
    {
        return $this->decode($this->reader->loadTagContent($repo, $tag));
    }

    public function getCommits(string $repo): array
    {
        $results = [];

        foreach ($this->reader->getCommitFiles($repo) as $item) {
            $results[] = $this->decode($this->reader->loadCommitContent($repo, $this->extractName($item)));
        }

        return $results;
    }

    public function getCommit(string $repo, string $commitSha): array
    {
        return $this->decode($this->reader->loadCommitContent($repo, $commitSha));
    }

    public function getCommitsStatuses(string $repo): array
    {
        $results = [];

        foreach ($this->reader->getCommitStatusFiles($repo) as $item) {
            $results[] = $this->decode($this->reader->loadCommitStatusContent($repo, $this->extractName($item)));
        }

        return $results;
    }

    public function getCommitStatus(string $repo, string $commitSha): array
    {
        return $this->decode($this->reader->loadCommitStatusContent($repo, $commitSha));
    }

    public function getGitHubPushEventData(): array
    {
        $results = [];

        foreach ($this->supportedRepoNames as $repo) {
            foreach ($this->reader->getPushEventFiles($repo) as $file) {
                $results[] = $this->decode(file_get_contents($file->getPathname()));
            }
        }

        return $results;
    }

    public function getGitHubPushEventBranchesData(): array
    {
        $results = [];

        foreach ($this->supportedRepoNames as $repo) {
            foreach ($this->reader->getPushEventBranchFiles($repo) as $file) {
                $results[] = $this->decode(file_get_contents($file->getPathname()));
            }
        }

        return $results;
    }

    public function getGitHubPushEventTagsData(): array
    {
        $results = [];

        foreach ($this->supportedRepoNames as $repo) {
            foreach ($this->reader->getPushEventTagFiles($repo) as $file) {
                $results[] = $this->decode(file_get_contents($file->getPathname()));
            }
        }

        return $results;
    }

    private function decode(string $json): array
    {
        return json_decode($json, true);
    }

    private function extractName(SplFileInfo $item): string
    {
        return str_replace('.json', '', $item->getRelativePathname());
    }
}
