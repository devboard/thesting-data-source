<?php

declare(strict_types=1);

namespace Devboard\Thesting\Source;

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
        return file_get_contents($this->getBasePath().$repo.'/branches/'.$tagName.'.json');
    }

    public function getBranchFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'branches');
    }

    /**
     * @todo this are actually branches :)
     */
    public function getTagFiles(string $repo): array
    {
        return $this->getFilesIn($repo, 'branches');
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
