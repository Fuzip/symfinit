<?php

declare(strict_types=1);

namespace Symfinit\Installer\Symfony;

final readonly class SymfonyVersion
{
    public function __construct(
        public string $version,
        public bool $isLts,
    ) {
    }
}
