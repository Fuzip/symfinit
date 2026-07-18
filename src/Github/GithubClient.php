<?php

declare(strict_types=1);

namespace Symfinit\Installer\Github;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * @author Victor Dittiere <victor.dittiere@camif.fr>
 */
final class GithubClient
{
    private const string BASE_URL = 'https://github.com/';

    /**
     * Clone a GitHub repository into the given directory.
     *
     * @throws ProcessFailedException
     */
    public function clone(string $repository, string $directory): void
    {
        $process = new Process(['git', 'clone', self::BASE_URL.$repository.'.git', $directory]);
        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
