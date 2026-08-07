<?php

declare(strict_types=1);

namespace Symfinit\Installer\Runner;

/**
 * @author Victor Dittiere <victor.dittiere@icloud.com>
 */
interface RunnerInterface
{
    /**
     * Execute the runner.
     */
    public function exec(): void;
}
