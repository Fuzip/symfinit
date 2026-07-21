# Symfinit

A CLI installer that scaffolds a new Symfony project pre-configured with Docker, based on the [dunglas/symfony-docker](https://github.com/dunglas/symfony-docker) template.

## Requirements

- PHP 8.5+
- Composer
- Docker & Docker Compose

## Installation

### Static binary (recommended)

Download the latest release for your platform from the
[Releases page](https://github.com/Fuzip/symfinit/symfinit/releases) and
move the binary somewhere on your `$PATH`:

```sh
curl -L https://github.com/Fuzip/symfinit/symfinit/releases/latest/download/symfinit-linux-x86_64 -o /usr/local/bin/symfinit
chmod +x /usr/local/bin/symfinit
```

### Composer

If you already have PHP and Composer installed:

```sh
composer global require fuzip/symfinit
```

The `symfinit` binary will be available in `~/.composer/vendor/bin`.

## Usage

```bash
symfinit [project-name]
```

You can choose the Symfony version that will be installed.

```bash
symfinit --symfony-version=7.4
```

## License

MIT — see [LICENCE](LICENCE).

## Credits

Created by [Fuzip](https://github.com/Fuzip).
