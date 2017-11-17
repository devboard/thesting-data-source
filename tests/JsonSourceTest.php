<?php

declare(strict_types=1);

namespace tests\DevboardLib\Thesting\Source;

use DevboardLib\Thesting\Source\JsonSource;

/**
 * @covers \DevboardLib\Thesting\Source\JsonSource
 * @group  integration
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
        $this->assertCount(19, $this->sut->getSupportedRepoNames());
    }

    public function testGetRepos()
    {
        $this->assertCount(19, $this->sut->getRepos());
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

    public function testGetGitHubPushEventData()
    {
        $this->assertCount(6, $this->sut->getGitHubPushEventData());
    }

    public function testGetGitHubPushEventBranchesData()
    {
        $this->assertCount(6, $this->sut->getGitHubPushEventBranchesData());
    }

    public function testGetGitHubPushEventTagsData()
    {
        $this->assertCount(0, $this->sut->getGitHubPushEventTagsData());
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
            ['symfony/symfony', 15],
            ['symfony/symfony-standard', 17],
            ['symfony/symfony-docs', 28],
        ];
    }

    public function provideReposAndTagCount(): array
    {
        return [
            ['octocat/Hello-World', 0],
            ['octocat/Spoon-Knife', 0],
            ['octocat/linguist', 30],
            ['octocat/octocat.github.io', 0],
            ['octocat/git-consortium', 0],
            ['octocat/test-repo1', 0],
            ['symfony/symfony', 30],
            ['symfony/symfony-standard', 30],
            ['symfony/symfony-docs', 30],
        ];
    }
}
