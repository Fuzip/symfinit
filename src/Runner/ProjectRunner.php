<?php

declare(strict_types=1);

namespace SymfonyScaffold\Installer\Runner;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * @author Victor Dittiere <victor.dittiere@icloud.com>
 */
final class ProjectRunner
{
    /**
     * Builds and starts the dunglas/symfony-docker project, installing the
     * given Symfony version.
     *
     * When a TTY is available, docker is attached directly to it so its
     * output (colors, progress bars, ...) is identical to running the
     * commands by hand; $onOutput is then ignored. Otherwise, the output is
     * relayed through $onOutput as it is produced.
     *
     * @param ?callable(string, string): void $onOutput called with the process output as it is produced, e.g. `fn (string $type, string $buffer) => ...`
     *
     * @see https://github.com/dunglas/symfony-docker/blob/main/docs/options.md
     *
     * @throws ProcessFailedException
     */
    public function start(string $directory, string $symfonyVersion, ?callable $onOutput = null): void
    {
        $env = ['SYMFONY_VERSION' => $symfonyVersion.'.*'];

        $this->run(['docker', 'compose', 'build', '--pull', '--no-cache'], $directory, $env, $onOutput);
        $this->run(['docker', 'compose', 'up', '--wait'], $directory, $env, $onOutput);
    }

    /**
     * @param list<string>                    $command
     * @param array<string, string>           $env
     * @param ?callable(string, string): void $onOutput
     */
    private function run(array $command, string $directory, array $env, ?callable $onOutput): void
    {
        $process = new Process($command, $directory, $env);
        $process->setTimeout(null);
        $process->setTty(Process::isTtySupported());
        $process->run($onOutput);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
