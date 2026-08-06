<?php

declare(strict_types=1);

namespace Symfinit\Installer\Runner;

use Symfinit\Installer\Runner\Step\PostStepInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * @author Victor Dittiere <victor.dittiere@icloud.com>
 */
final class GithubRunner extends AbstractRunner
{
    private const string BASE_URL = 'https://github.com/';

    /**
     * @param iterable<PostStepInterface> $postSteps
     */
    public function __construct(
        SymfonyStyle $io,
        string $projectPath,
        private readonly string $repo,
        iterable $postSteps = [],
    ) {
        parent::__construct($io, $projectPath, $postSteps);
    }

    protected function run(): void
    {
        $this->clone();

        $this->io->text(sprintf('Project cloned into <info>%s</info>.', $this->projectPath));
    }

    /**
     * Clone a GitHub repository into the given directory.
     *
     * @throws ProcessFailedException
     */
    private function clone(): void
    {
        $process = new Process(['git', 'clone', self::BASE_URL.$this->repo.'.git', $this->projectPath]);
        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
