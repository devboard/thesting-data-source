<?php

declare(strict_types=1);

namespace spec\DevboardLib\Thesting\Source;

use DevboardLib\Thesting\Source\JsonReader;
use DevboardLib\Thesting\Source\JsonSource;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Finder\SplFileInfo;

class JsonSourceSpec extends ObjectBehavior
{
    public function let(JsonReader $jsonReader)
    {
        $this->beConstructedWith($jsonReader, ['owner/repo']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(JsonSource::class);
    }

    public function it_returns_all_supported_repos_as_array_data(JsonReader $jsonReader)
    {
        $jsonReader->loadRepoContent('owner/repo')
            ->shouldBeCalled()
            ->willReturn('["repo-data"]');

        $this->getRepos()->shouldReturn(['owner/repo' => ['repo-data']]);
    }

    public function it_returns_branch_data_for_given_repo(JsonReader $jsonReader, SplFileInfo $file)
    {
        $jsonReader->getBranchFiles('owner/repo')
            ->shouldBeCalled()
            ->willReturn([$file]);

        $file->getRelativePathname()
            ->shouldBeCalled()
            ->willReturn('master.json');

        $jsonReader->loadBranchContent('owner/repo', 'master')
            ->shouldBeCalled()
            ->willReturn('["branch-data"]');

        $this->getBranches('owner/repo')
            ->shouldReturn(
                [
                    ['branch-data'],
                ]
            );
    }

    public function it_returns_tags_data_for_given_repo(JsonReader $jsonReader, SplFileInfo $file)
    {
        $jsonReader->getTagFiles('owner/repo')
            ->shouldBeCalled()
            ->willReturn([$file]);

        $file->getRelativePathname()
            ->shouldBeCalled()
            ->willReturn('v0.1.json');

        $jsonReader->loadTagContent('owner/repo', 'v0.1')
            ->shouldBeCalled()
            ->willReturn('["tag-data"]');

        $this->getTags('owner/repo')
            ->shouldReturn(
                [
                    ['tag-data'],
                ]
            );
    }
}
