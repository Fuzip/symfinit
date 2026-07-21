<?php

declare(strict_types=1);

// Build a self-contained Phar of the installer for static-php-cli's
// micro:combine step.

$root = \dirname(__DIR__);
$target = $argv[1] ?? $root.'/dist/symfinit.phar';

@mkdir(\dirname($target), recursive: true);
@unlink($target);

$phar = new Phar($target);
$phar->startBuffering();

// Not just .php files: some dependencies (e.g. symfony/console's shell
// completion command) read non-PHP resource files from vendor/ at runtime.
$phar->buildFromDirectory($root, '#/(src|vendor)/#');

$phar->addFile($root.'/bin/symfinit', 'bin/symfinit');

$phar->setStub(<<<'PHP'
    #!/usr/bin/env php
    <?php
    Phar::mapPhar();
    require 'phar://'.__FILE__.'/bin/symfinit';
    __HALT_COMPILER();
    PHP);

$phar->stopBuffering();
$phar->compressFiles(Phar::GZ);

chmod($target, 0o755);

echo "Built $target".PHP_EOL;
