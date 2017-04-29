<?php

declare(strict_types=1);

namespace tests\Devboard\Thesting\Source;

use Devboard\Thesting\Source\JsonSource;

/**
 * @covers \Devboard\Thesting\Source\JsonSource
 * @group  unit
 */
class JsonSourceTest extends \PHPUnit_Framework_TestCase
{
    /** @var JsonSource */
    private $sut;

    public function setUp()
    {
        $this->sut = JsonSource::create();
    }

    public function testGetSupportedRepoNames()
    {
        $this->assertCount(9, $this->sut->getSupportedRepoNames());
    }

    public function testGetRepos()
    {
        $this->assertCount(9, $this->sut->getRepos());
    }

    /** @dataProvider provideReposAndBranchCount */
    public function testGetBranches(string $repo, int $expectedCount)
    {
        $this->assertCount($expectedCount, $this->sut->getBranches($repo));
    }

    /** @dataProvider provideReposAndTagCount */
    public function testGetTags(string $repo, int $expectedCount)
    {
        $this->assertCount($expectedCount, $this->sut->getTags($repo));
    }

    public function provideReposAndBranchCount(): array
    {
        return [
            ['octocat/Hello-World', 2],
            ['octocat/Spoon-Knife', 3],
            ['octocat/linguist', 7],
            ['octocat/octocat.github.io', 2],
            ['octocat/git-consortium', 1],
            ['octocat/test-repo1', 1],
            ['symfony/symfony', 13],
            ['symfony/symfony-standard', 15],
            ['symfony/symfony-docs', 18],
        ];
    }

    /**
     * @todo this are actually branches :)
     */
    public function provideReposAndTagCount(): array
    {
        return [
            ['octocat/Hello-World', 2],
            ['octocat/Spoon-Knife', 3],
            ['octocat/linguist', 7],
            ['octocat/octocat.github.io', 2],
            ['octocat/git-consortium', 1],
            ['octocat/test-repo1', 1],
            ['symfony/symfony', 13],
            ['symfony/symfony-standard', 15],
            ['symfony/symfony-docs', 18],
        ];
    }
}
