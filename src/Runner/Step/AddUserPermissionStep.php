<?php

declare(strict_types=1);

namespace Symfinit\Installer\Runner\Step;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Assigns ownership of the generated project to the current host user.
 *
 * Root typically owns files created by Docker, so this step is the
 * equivalent of running `chown -R user:user my-app`.
 *
 * @author Victor Dittiere <victor.dittiere@camif.fr>
 */
class AddUserPermissionStep extends AbstractStep implements PostStepInterface
{
    public function supports(): bool
    {
        return 'Linux' === \PHP_OS_FAMILY;
    }

    public function apply(string $projectPath): void
    {
        try {
            $uid = $this->currentUserId();
            $gid = $this->currentGroupId();

            $process = new Process(['chown', '-R', "$uid:$gid", $projectPath]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->io->info("Set ownership of $projectPath to $uid:$gid");
        } catch (\Throwable $e) {
            $this->io->error('Failed to set project ownership: '.$e->getMessage());
        }
    }

    /**
     * Return the current user ID.
     */
    private function currentUserId(): int
    {
        if (\function_exists('posix_getuid')) {
            return posix_getuid();
        }

        return (int) trim(new Process(['id', '-u'])->mustRun()->getOutput());
    }

    /**
     * Return the current group ID.
     */
    private function currentGroupId(): int
    {
        if (\function_exists('posix_getgid')) {
            return posix_getgid();
        }

        return (int) trim(new Process(['id', '-g'])->mustRun()->getOutput());
    }
}
