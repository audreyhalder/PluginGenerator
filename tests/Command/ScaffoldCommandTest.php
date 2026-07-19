<?php

declare(strict_types=1);

namespace PluginGenerator\Tests\Command;

use PHPUnit\Framework\TestCase;
use PluginGenerator\Command\ScaffoldCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class ScaffoldCommandTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . '/scaffold-cmd-test-' . uniqid('', true);
        mkdir($this->tmpDir, 0777, true);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->tmpDir);
    }

    public function testCommandCreatesPluginSuccessfully(): void
    {
        $tester = $this->makeCommandTester();

        $tester->execute([
            'name' => 'DemoPlugin',
            '--target-dir' => $this->tmpDir,
        ]);

        $tester->assertCommandIsSuccessful();
        self::assertStringContainsString('scaffolded', $tester->getDisplay());
        self::assertDirectoryExists($this->tmpDir . '/DemoPlugin');
    }

    public function testRejectsInvalidPluginName(): void
    {
        $tester = $this->makeCommandTester();

        $exitCode = $tester->execute([
            'name' => 'not-pascal-case',
            '--target-dir' => $this->tmpDir,
        ]);

        self::assertSame(Command::FAILURE, $exitCode);
        self::assertStringContainsString('PascalCase', $tester->getDisplay());
    }

    public function testWithStorefrontFlagIsPassedThrough(): void
    {
        $tester = $this->makeCommandTester();

        $tester->execute([
            'name' => 'StorefrontDemo',
            '--target-dir' => $this->tmpDir,
            '--with-storefront' => true,
        ]);

        $tester->assertCommandIsSuccessful();
        self::assertFileExists(
            $this->tmpDir . '/StorefrontDemo/src/Resources/app/storefront/src/scss/base.scss'
        );
    }

    private function makeCommandTester(): CommandTester
    {
        $application = new Application();
        $application->add(new ScaffoldCommand());

        $command = $application->find('create');

        return new CommandTester($command);
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        /** @var list<string> $items */
        $items = array_diff((array) scandir($dir), ['.', '..']);

        foreach ($items as $item) {
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->removeDir($path) : unlink($path);
        }

        rmdir($dir);
    }
}
