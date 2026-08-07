<?php

declare(strict_types=1);

namespace Symfinit\Installer\Runner\Step;

/**
 * Define a post-runner step.
 *
 * @author Victor Dittiere <victor.dittiere@icloud.com>
 */
interface PostStepInterface
{
    /**
     * Whether this step should run for the current installation.
     */
    public function supports(): bool;

    /**
     * Apply the step to the given project directory.
     */
    public function apply(string $projectPath): void;
}
