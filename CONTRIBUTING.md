# Contributing

First of all, thank you for taking the time to contribute to Symfinit! 🎉

This document explains how to report issues, set up a development environment
and submit changes.

## Reporting bugs and requesting features

Before opening a new issue, please search the [existing issues](https://github.com/Fuzip/symfinit/issues)
to avoid duplicates.

- **Bugs**: use the *🐛 Bug Report* template. Please include the Symfinit
  version, the build you are using (`linux-x86_64`, `macos-aarch64`, Composer…),
  the command you ran and the full output.
- **Features**: use the *Feature Request* template and describe the problem you
  are trying to solve, not only the solution you have in mind.

## Development setup

### Requirements

- PHP **8.5** or higher
- [Composer](https://getcomposer.org/) 2
- [Docker](https://docs.docker.com/get-docker/) with Docker Compose (to test the generated projects)
- `make` (optional, but recommended)
- [static-php-cli (`spc`)](https://static-php.dev/) (only if you want to build the static binary)

### Getting the code

Fork the repository, then clone your fork:

```sh
git clone git@github.com:<your-username>/symfinit.git
cd symfinit
make install # or: composer install
```

Run `make` (or `make help`) to list all the available targets.

### Running Symfinit locally

The entry point is `bin/symfinit`. You can run it directly from the sources:

```sh
bin/symfinit my-app --path=/tmp
```

Useful options:

| Option              | Description                                                    |
|---------------------|----------------------------------------------------------------|
| `--symfony-version` | The Symfony version to install (e.g. `8` or `8.4`)             |
| `--path`            | The directory where the project is created (defaults to `cwd`) |
| `--no-git`          | Remove the `.git` directory from the generated project         |

> [!TIP]
> Use `--path` to generate test projects outside of the repository. Once you are
> done, stop the stack with `docker compose down -v` from the generated project.

## Coding standards

### Code style

The code follows the [Symfony coding standards](https://symfony.com/doc/current/contributing/code/standards.html),
enforced by [PHP-CS-Fixer](https://cs.symfony.com/) (see `.php-cs-fixer.dist.php`).

```sh
make csfixer # fixes the code style automatically
```

Also make sure your editor respects the `.editorconfig` file.

### Static analysis

[PHPStan](https://phpstan.org/) runs at **level 8** on the `src/` directory
(see `phpstan.dist.neon`):

```sh
make phpstan
```

### Guidelines

- Always add `declare(strict_types=1);` to PHP files.
- Type every property, parameter and return value.
- Keep classes under the `Symfinit\Installer\` namespace (`src/` directory, PSR-4).
- Prefer small, focused classes. New behaviours should fit in the existing
  structure (e.g. `src/Runner/`) rather than growing `InstallerCommand`.

## Continuous integration

Every pull request runs the following GitHub Actions workflows:

| Workflow  | What it checks                                                                                    |
|-----------|---------------------------------------------------------------------------------------------------|
| **CI**    | `php-cs-fixer check` and PHPStan analysis on PHP 8.5 and 8.6                                      |
| **E2E**   | Scaffolds a project with `bin/symfinit`, boots its Docker stack and checks that it answers on HTTPS |

Please run the checks locally before pushing:

```sh
make csfixer phpstan
```

To reproduce the E2E check locally:

```sh
bin/symfinit e2e-app --path=/tmp
curl -kI https://localhost # a 404 is expected on a fresh skeleton
cd /tmp/e2e-app && docker compose down -v
```

## Building the static binary

The released binaries are built with [static-php-cli](https://static-php.dev/)
using the `craft.yml` definition:

```sh
make build # outputs dist/symfinit
make clean # removes the dist/ directory
```

The build first packages the sources into a PHAR (`scripts/build-phar.php`),
then combines it with a static `micro` PHP runtime. Build logs are written to
the `log/` directory.

## Submitting changes

1. Create a branch from `main` with a descriptive name, prefixed with the kind
   of change (e.g. `feat/add-no-git-option`, `fix/path-resolution`).
2. Make your changes, keeping each pull request focused on a single topic.
3. Make sure the code style and static analysis checks pass.
4. Update the `README.md` if you change the behaviour or the options of the CLI.
5. Push your branch and open a pull request against `main`, explaining **what**
   you changed and **why**. Link the related issue if there is one
   (e.g. `Closes #42`).

### Commit messages

Commits follow the [Conventional Commits](https://www.conventionalcommits.org/)
specification:

```
<type>: <short summary in the imperative mood>
```

Common types used in this repository:

| Type       | Usage                                                     |
|------------|-----------------------------------------------------------|
| `feat`     | A new feature                                             |
| `fix`      | A bug fix                                                 |
| `docs`     | Documentation only changes                                |
| `refactor` | A code change that neither fixes a bug nor adds a feature |
| `ci`       | Changes to the GitHub Actions workflows                   |
| `build`    | Changes to the build system or dependencies               |
| `chore`    | Other changes (tooling, configuration…)                   |

Examples:

```
feat: add --no-git option
fix: resolve relative paths passed to --path
ci: test CI workflows on PHP 8.5 and 8.6
```

## License

By contributing to Symfinit, you agree that your contributions will be licensed
under the [MIT License](LICENCE).
