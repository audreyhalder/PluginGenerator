<?php

declare(strict_types=1);

namespace PluginGenerator\Command;

use PluginGenerator\Generator\PluginGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'create',
    description: 'Scaffold a new Shopware 6 plugin skeleton'
)]
final class ScaffoldCommand extends Command
{
    public function __construct(private readonly ?PluginGenerator $generator = null)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Plugin name in PascalCase, e.g. MyCustomPlugin')
            ->addOption('vendor', null, InputOption::VALUE_REQUIRED, 'Composer vendor namespace', 'acme')
            ->addOption('author', null, InputOption::VALUE_REQUIRED, 'Plugin author', 'Your Name')
            ->addOption('with-storefront', null, InputOption::VALUE_NONE, 'Also scaffold a storefront SCSS stub')
            ->addOption('target-dir', null, InputOption::VALUE_REQUIRED, 'Directory to generate the plugin in', '.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string $pluginName */
        $pluginName = $input->getArgument('name');

        if (!preg_match('/^[A-Z][A-Za-z0-9]*$/', $pluginName)) {
            $io->error('Plugin name must be in PascalCase, e.g. "MyCustomPlugin".');

            return Command::FAILURE;
        }

        $generator = $this->generator ?? new PluginGenerator();

        try {
            $targetPath = $generator->generate(
                pluginName: $pluginName,
                targetDir: rtrim((string) $input->getOption('target-dir'), '/'),
                vendor: (string) $input->getOption('vendor'),
                author: (string) $input->getOption('author'),
                withStorefront: (bool) $input->getOption('with-storefront'),
            );
        } catch (\RuntimeException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $io->success(sprintf('Plugin "%s" scaffolded at %s', $pluginName, $targetPath));

        return Command::SUCCESS;
    }
}
