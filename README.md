# Symfinit

A CLI installer that scaffolds a new Symfony project pre-configured with Docker, based on the [dunglas/symfony-docker](https://github.com/dunglas/symfony-docker) template.

## Requirements

- Docker

## Installation

### Homebrew (recommended)

Be sure to have Homebrew installed then run:

```sh
brew install fuzip/symfinit
```

Or you can tap the repository and install the formula:

```sh
brew tap fuzip/symfinit
brew install symfinit
```

### Static binary

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

When the generated project includes a devcontainer configuration, `symfinit` offers to open it in a VS Code
devcontainer. Use the `--open` option to open it directly without being asked.

```bash
symfinit my-app --open
```

## Contributing

Contributions are welcome! Please read the [CONTRIBUTING.md](CONTRIBUTING.md) file for more information.

## License

MIT — see [LICENCE](LICENCE).

## Credits

Created by [Fuzip](https://github.com/Fuzip).
