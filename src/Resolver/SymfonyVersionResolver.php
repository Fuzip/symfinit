<?php

declare(strict_types=1);

namespace Symfinit\Installer\Resolver;

use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Resolve a Symfony version.
 *
 * @author Victor Dittiere <victor.dittiere@icloud.com>
 */
final class SymfonyVersionResolver
{
    private const string RELEASE_URL = 'https://symfony.com/releases/%s.json';
    private const string RELEASES_URL = 'https://symfony.com/releases.json';

    public function __construct(private readonly HttpClientInterface $httpClient = new NativeHttpClient())
    {
    }

    /**
     * Resolves a user-provided Symfony version (e.g. "8" or "8.4") against
     * the official releases feed.
     *
     * A major-only version is resolved to its LTS minor, since every
     * Symfony major release line ends with an ".4" LTS release.
     *
     * @throws \InvalidArgumentException if the format is invalid or the version does not exist
     * @throws \RuntimeException         if the releases feed cannot be reached
     */
    public function resolve(string $version): SymfonyVersion
    {
        if (preg_match('/^\d+$/', $version)) {
            $version .= '.4';
        } elseif (!preg_match('/^\d+\.\d+$/', $version)) {
            throw new \InvalidArgumentException(sprintf('Invalid Symfony version "%s". Expected a major version (e.g. "8") or a major.minor version (e.g. "8.4").', $version));
        }

        $data = $this->fetchJson(sprintf(self::RELEASE_URL, $version));

        if (isset($data['error_message']) && is_string($data['error_message'])) {
            throw new \InvalidArgumentException($data['error_message']);
        }

        if (!isset($data['is_lts'])) {
            throw new \InvalidArgumentException(sprintf('Symfony version "%s" does not exist.', $version));
        }

        if (!($data['is_released'] ?? false)) {
            throw new \InvalidArgumentException(sprintf('Symfony version "%s" has not been released yet.', $version));
        }

        return new SymfonyVersion($version, (bool) $data['is_lts']);
    }

    /**
     * Resolves the current latest Symfony LTS version.
     *
     * @throws \RuntimeException if the releases feed cannot be reached or parsed
     */
    public function resolveLatestLts(): SymfonyVersion
    {
        $data = $this->fetchJson(self::RELEASES_URL);
        $ltsPatchVersion = $data['symfony_versions']['lts'] ?? null;

        if (!is_string($ltsPatchVersion) || !preg_match('/^(\d+\.\d+)/', $ltsPatchVersion, $matches)) {
            throw new \RuntimeException('Unable to determine the latest Symfony LTS version.');
        }

        return new SymfonyVersion($matches[1], true);
    }

    /**
     * @return array<string, mixed>
     */
    private function fetchJson(string $url): array
    {
        try {
            return $this->httpClient->request('GET', $url, ['timeout' => 10])->toArray(false);
        } catch (ExceptionInterface $e) {
            throw new \RuntimeException('Unable to reach symfony.com to verify the Symfony version.', previous: $e);
        }
    }
}
