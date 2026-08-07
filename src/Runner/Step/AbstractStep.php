<?php

declare(strict_types=1);

namespace Symfinit\Installer\Runner\Step;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Abstraction class PostInstallStep.
 *
 * @author Victor Dittiere <victor.dittiere@icloud.com
 */
abstract class AbstractStep
{
    public function __construct(protected SymfonyStyle $io)
    {
    }
}
