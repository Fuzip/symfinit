<?php

declare(strict_types=1);

namespace Symfinit\Installer\Runner;

use Symfinit\Installer\Runner\Step\PostStepInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Abstraction class of Runner.
 *
 * @author Victor Dittiere <victor.dittiere@icloud.com>
 */
abstract class AbstractRunner implements RunnerInterface
{
    /**
     * @param iterable<PostStepInterface> $postSteps
     */
    public function __construct(
        protected SymfonyStyle $io,
        protected string $projectPath,
        private readonly iterable $postSteps = [],
    ) {
    }

    public function exec(): void
    {
        $this->run();
        $this->runPostStep();
    }

    /**
     * Run the process.
     */
    abstract protected function run(): void;

    private function runPostStep(): void
    {
        foreach ($this->postSteps as $step) {
            if ($step->supports()) {
                $step->apply($this->projectPath);
            }
        }
    }
}
