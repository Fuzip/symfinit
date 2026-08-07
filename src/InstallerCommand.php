<?php

declare(strict_types=1);

namespace Symfinit\Installer;

use Symfinit\Installer\Resolver\SymfonyVersion;
use Symfinit\Installer\Resolver\SymfonyVersionResolver;
use Symfinit\Installer\Runner\RunnerFactory;
use Symfinit\Installer\Runner\RunnerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Victor Dittiere <victor.dittiere@icloud.com>
 */
#[AsCommand(name: 'symfinit', description: 'Scaffold a new Symfony docker project')]
class InstallerCommand extends Command
{
    public const string VERSION = '@package_version@';
    private const string NAME_PATTERN = '/^[a-zA-Z0-9][a-zA-Z0-9._-]*$/';
    private const string SYMFONY_DOCKER_REPOSITORY = 'dunglas/symfony-docker';

    private SymfonyStyle $io;
    private string $projectName;
    private string $projectPath;
    private bool $noGit;
    private SymfonyVersion $symfonyVersion;
    /** @var array<RunnerInterface> */
    private array $runners = [];

    public function __construct(
        private readonly SymfonyVersionResolver $symfonyVersionResolver = new SymfonyVersionResolver(),
    ) {
        parent::__construct();
    }

    /**
     * Return the command version.
     */
    public static function version(): string
    {
        return str_starts_with(self::VERSION, '@') ? 'dev' : self::VERSION;
    }

    /**
     * Validate the given project name.
     */
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
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'The name of the project'
            )
            ->addOption(
                'symfony-version',
                null,
                InputOption::VALUE_REQUIRED,
                'The Symfony version to use (e.g. "8" or "8.4")'
            )
            ->addOption(
                'path',
                null,
                InputOption::VALUE_REQUIRED,
                'The directory where the project will be created',
                getcwd() ?: '.'
            )
            ->addOption(
                'no-git',
                null,
                InputOption::VALUE_NONE,
                'Remove the .git directory from the generated project'
            )
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // Configure SymfonyStyle object.
        $this->io = new SymfonyStyle($input, $output);

        // Resolve command arguments.
        $name = $input->getArgument('name');
        if (is_string($name) && !empty($name)) {
            $this->projectName = self::validateName($name);
        }

        // Resolve command options.
        $this->projectPath = rtrim((string) $input->getOption('path'), DIRECTORY_SEPARATOR);
        $this->symfonyVersion = $this->symfonyVersionResolver->resolve($input->getOption('symfony-version'));
        $this->noGit = (bool) $input->getOption('no-git');
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (!isset($this->projectName)) {
            $question = new Question('Project name', 'my-app');
            $question->setValidator(static fn ($v): string => self::validateName((string) $v));
            $question->setMaxAttempts(3);

            $answer = (string) $this->io->askQuestion($question);

            $this->projectName = $answer;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Symfinit installer');
        $this->io->info("Symfony version {$this->symfonyVersion->version}.");

        if (!$this->symfonyVersion->isLts) {
            $this->io->warning("Symfony {$this->symfonyVersion->version} is not an LTS version.");
        }

        // Check project path
        $this->projectPath .= DIRECTORY_SEPARATOR.$this->projectName;

        if (file_exists($this->projectPath)) {
            $this->io->error(sprintf('Directory %s already exists.', $this->projectPath));

            return Command::FAILURE;
        }

        // Initialize runners
        $runnerFactory = new RunnerFactory($this->io, $this->projectPath);

        $this->runners = [
            $runnerFactory->createGithubRunner(self::SYMFONY_DOCKER_REPOSITORY, $this->noGit),
            $runnerFactory->createDockerRunner($this->symfonyVersion->version),
        ];

        // Execute runners
        foreach ($this->runners as $runner) {
            try {
                $runner->exec();
            } catch (\Throwable $e) {
                $this->io->error($e->getMessage());

                return Command::FAILURE;
            }
        }

        $this->io->success(sprintf('Project %s is ready : %s', $this->projectName, $this->projectPath));

        $this->io->info('Open https://localhost in your browser to access to the app.');

        return Command::SUCCESS;
    }
}
