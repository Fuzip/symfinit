<?php

declare(strict_types=1);

namespace SymfonyScaffold\Installer;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Victor Dittiere <victor.dittiere@camif.fr>
 */
#[AsCommand(name: 'symfony-scaffold', description: 'Scaffold a new Symfony docker project')]
class InstallerCommand extends Command
{
    public const string VERSION = '@package_version@';
    private const string NAME_PATTERN = '/^[a-zA-Z0-9][a-zA-Z0-9._-]*$/';

    public static function version(): string
    {
        return str_starts_with(self::VERSION, '@') ? 'dev' : self::VERSION;
    }

    public static function validateName(string $name): string
    {
        if (!preg_match(self::NAME_PATTERN, $name)) {
            throw new \InvalidArgumentException('Project name must start with a letter or digit and contain only letters, digits, hyphens, dots, or underscores.');
        }

        return $name;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Symfony scaffold installer');

        try {
            $name = $this->resolveProjectName($io, $input);
        } catch (\InvalidArgumentException $e) {
            $io->error($e->getMessage());

            return Command::INVALID;
        }

        $projectDir = (getcwd() ?: '.').\DIRECTORY_SEPARATOR.$name;
        if (file_exists($projectDir)) {
            $io->error(sprintf('Directory %s already exists.', $projectDir));

            return Command::INVALID;
        }

        $io->section(sprintf('Creating project "%s"', $name));

        return Command::SUCCESS;
    }

    private function resolveProjectName(SymfonyStyle $io, InputInterface $input): string
    {
        $name = $input->getArgument('name');
        if (\is_string($name) && '' !== $name) {
            return self::validateName($name);
        }

        $question = new Question('Project name', 'my-app');
        $question->setValidator(static fn ($v): string => self::validateName((string) $v));
        $question->setMaxAttempts(3);

        return (string) $io->askQuestion($question);
    }
}
