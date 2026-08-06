<?php

declare(strict_types=1);

namespace Symfinit\Installer\Runner;

use Symfinit\Installer\Runner\Step\RemoveGitDirectoryStep;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Builds the runners used by the installer.
 *
 * @author Victor Dittiere <victor.dittiere@icloud.com>
 */
final readonly class RunnerFactory
{
    public function __construct(
        private SymfonyStyle $io,
        private string $projectPath,
    ) {
    }

    public function createGithubRunner(string $repo, bool $noGit): GithubRunner
    {
        return new GithubRunner(
            $this->io,
            $this->projectPath,
            $repo,
            [new RemoveGitDirectoryStep($this->io, $noGit)],
        );
    }

    public function createDockerRunner(string $symfonyVersion): DockerRunner
    {
        return new DockerRunner(
            $this->io,
            $this->projectPath,
            $symfonyVersion,
        );
    }
}
