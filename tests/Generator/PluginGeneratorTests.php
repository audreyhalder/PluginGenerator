<?php

declare(strict_types=1);

namespace PluginGenerator\tests\Generator;

use PHPUnit\Framework\TestCase;
use PluginGenerator\Generator\PluginGenerator;

final class PluginGeneratorTests extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . '/scaffold-test-' . uniqid('', true);
        mkdir($this->tmpDir, 0777, true);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->tmpDir);
    }

    public function testCreatesPluginDirectory(): void
    {
        $generator = new PluginGenerator();

        $pluginPath = $generator->generate(
            pluginName: 'MyTestPlugin',
            targetDir: $this->tmpDir,
            vendor: 'Audrey Halder',
            author: 'Audrey Halder',
            withStorefront: true
        );

        self::assertDirectoryExists($pluginPath);
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = array_diff((array) scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->removeDir($path) : unlink($path);
        }
        rmdir($dir);
    }
}