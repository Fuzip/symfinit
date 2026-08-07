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
final class DockerRunner extends AbstractRunner
{
    /**
     * @param iterable<PostStepInterface> $postSteps
     */
    public function __construct(
        SymfonyStyle $io,
        string $projectPath,
        private readonly string $symfonyVersion,
        iterable $postSteps = [],
    ) {
        parent::__construct($io, $projectPath, $postSteps);
    }

    /**
     * Builds and starts the docker project with Docker compose.
     *
     * @throws ProcessFailedException
     */
    protected function run(): void
    {
        $this->executeCommand(['docker', 'compose', 'build', '--pull', '--no-cache']);
        $this->executeCommand(['docker', 'compose', 'up', '--wait']);
    }

    /**
     * Executes a given command as a separate process.
     *
     * When a TTY is available, docker is attached directly to it, so its
     * output (colors, progress bars, ...) is identical to running the
     * commands by hand; $output is then ignored. Otherwise, the output is
     * relayed through $output as it is produced.
     *
     * @param list<string> $command the command to execute, represented as an array of strings
     */
    private function executeCommand(array $command): void
    {
        $process = new Process($command, $this->projectPath, ['SYMFONY_VERSION' => $this->symfonyVersion.'.*']);
        $process->setTimeout(null);
        $process->setTty(Process::isTtySupported());
        $process->run(function (string $type, string $buffer): void {
            $this->io->write($buffer);
        });

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
