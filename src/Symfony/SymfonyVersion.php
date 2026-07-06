<?php

declare(strict_types=1);

namespace SymfonyScaffold\Installer\Symfony;

final readonly class SymfonyVersion
{
    public function __construct(
        public string $version,
        public bool $isLts,
    ) {
    }
}
