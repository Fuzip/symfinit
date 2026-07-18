# Symfinit

> **Work in progress** — this tool is not yet ready for production use.

A CLI installer that scaffolds a new Symfony project pre-configured with Docker, based on the [dunglas/symfony-docker](https://github.com/dunglas/symfony-docker) template.

## What it does

- Clones and configures the `dunglas/symfony-docker` template
- Lets you choose your **PHP version**
- Lets you choose your **Symfony version**
- Lets you select the **Composer packages** you want to install

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

If no project name is provided, the installer will prompt you for one.

## License

MIT — see [LICENCE](LICENCE).
