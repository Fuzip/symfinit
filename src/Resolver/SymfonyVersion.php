<?php

declare(strict_types=1);

namespace Symfinit\Installer\Resolver;

final readonly class SymfonyVersion
{
    public function __construct(
        public string $version,
        public bool $isLts,
    ) {
    }
}
