# Security Policy

## Supported versions

Only the latest release of Symfinit receives security fixes. Please make sure
you are running the most recent version before reporting a vulnerability.

| Version        | Supported          |
|----------------|--------------------|
| Latest release | :white_check_mark: |
| Older releases | :x:                |

To upgrade:

```sh
brew upgrade symfinit                 # Homebrew
composer global update fuzip/symfinit # Composer
```

Static binaries can be downloaded again from the
[Releases page](https://github.com/Fuzip/symfinit/releases).

## Reporting a vulnerability

> [!IMPORTANT]
> Please **do not** report security vulnerabilities through public GitHub
> issues, discussions or pull requests.

Report them privately using one of the following channels:

- **GitHub** (preferred): open a [private security advisory](https://github.com/Fuzip/symfinit/security/advisories/new).
- **Email**: send a message to [victor.dittiere@icloud.com](mailto:victor.dittiere@icloud.com)
  with `[Symfinit Security]` in the subject.

Please include as much of the following information as possible:

- The Symfinit version and the build you are using (`linux-x86_64`,
  `macos-aarch64`, Composer…).
- The type of issue (e.g. command injection, path traversal, insecure download…).
- The affected source file(s) or component(s), if known.
- Step-by-step instructions to reproduce the issue.
- A proof of concept or exploit code, if possible.
- The potential impact of the issue, including how an attacker could exploit it.

## Disclosure process

1. You will receive an acknowledgement soon as possible.
2. The report is investigated and, if confirmed, its severity is assessed.
   You will be kept informed of the progress.
3. A fix is prepared privately and released as a new version.
4. A security advisory is published on GitHub once the fix is available.
   Unless you prefer to remain anonymous, you will be credited for the discovery.

Please give us a reasonable amount of time to fix the issue before disclosing
it publicly.

## Scope

This policy covers the Symfinit CLI itself: its source code, the released
static binaries, the PHAR and the Homebrew formula.

Vulnerabilities in the generated projects that come from upstream components
should be reported to the corresponding projects:

- [dunglas/symfony-docker](https://github.com/dunglas/symfony-docker/security)
- [Symfony](https://symfony.com/doc/current/contributing/code/security.html)
- [FrankenPHP](https://github.com/php/frankenphp/security)
