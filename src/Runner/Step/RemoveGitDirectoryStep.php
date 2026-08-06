<?php

declare(strict_types=1);

namespace Symfinit\Installer\Runner\Step;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Removes the .git directory so the generated project starts free of its upstream history.
 *
 * @author Victor Dittiere <victor.dittiere@icloud.com>
 */
final class RemoveGitDirectoryStep extends AbstractStep implements PostStepInterface
{
    public function __construct(
        SymfonyStyle $io,
        private readonly bool $enabled,
        private readonly Filesystem $filesystem = new Filesystem(),
    ) {
        parent::__construct($io);
    }

    public function supports(): bool
    {
        return $this->enabled;
    }

    public function apply(string $projectPath): void
    {
        $gitPath = $projectPath.\DIRECTORY_SEPARATOR.'.git';
        try {
            $this->filesystem->remove($gitPath);
            $this->io->info("Removed $gitPath directory");
        } catch (\Throwable $e) {
            $this->io->error("Failed to remove $gitPath directory: ".$e->getMessage());
        }
    }
}
