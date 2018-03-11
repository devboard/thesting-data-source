<?php

declare(strict_types=1);

namespace DevboardLib\Thesting\Source;

use Symfony\Component\Finder\Finder;

/**
 * @see JsonReaderSpec
 * @see JsonReaderTest
 */
class JsonReader
{
    /** @var string */
    private $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    public function loadRepoContent(string $repo): string
    {
        return file_get_contents($this->getBasePath().$repo.'/repo.json');
    }

    public function loadBranchContent(string $repo, string $branchName): string
    {
        return file_get_contents($this->getBasePath().$repo.'/branches/'.$branchName.'.json');
    }

    public function loadTagContent(string $repo, string $tagName): string
    {
        return file_get_contents($this->getBasePath().$repo.'/tags/'.$tagName.'.json');
    }

    public function loadCommitContent(string $repo, string $commitSha): string
    {
        return file_get_contents($this->getBasePath().$repo.'/commits/'.$commitSha.'.json');
    }

    public function loadCommitStatusContent(string $repo, string $commitSha): string
    {
        return file_get_contents($this->getBasePath().$repo.'/commit-statuses/'.$commitSha.'.json');
    }

    public function loadPullRequestContent(string $repo, string $prNumber): string
    {
        return file_get_contents($this->getBasePath().$repo.'/pr/'.$prNumber.'.json');
    }

    public function getBranchFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'branches');
    }

    public function getTagFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'tags');
    }

    public function getCommitFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'commits');
    }

    public function getCommitStatusFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'commit-statuses');
    }

    public function getPushEventFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'push');
    }

    public function getPushEventBranchFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'push/branch');
    }

    public function getPushEventTagFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'push/tag');
    }

    public function getPullRequestFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'pr');
    }

    private function getFilesIn(string $repo, string $folderName): array
    {
        $path   = sprintf('%s/%s/%s/', $this->getBasePath(), $repo, $folderName);
        $finder = new Finder();

        if (false === is_dir($path)) {
            return [];
        }

        $data = [];
        foreach ($finder->files()->in($path)->getIterator() as $item) {
            $data[] = $item;
        }

        return $data;
    }

    private function getBasePath(): string
    {
        return $this->basePath;
    }
}
