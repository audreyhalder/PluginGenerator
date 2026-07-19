<?php

declare(strict_types=1);

namespace PluginGenerator\Generator;

final class PluginGenerator
{
    private readonly string $templateDir;

    public function __construct(?string $templateDir = null)
    {
        $this->templateDir = $templateDir ?? \dirname(__DIR__, 2) . '/templates';
    }

    public function generate(
        string $pluginName,
        string $targetDir,
        string $vendor,
        string $author,
        bool $withStorefront
    ): string {
        $pluginPath = rtrim($targetDir, '/') . '/' . $pluginName;

        if (is_dir($pluginPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" already exists.', $pluginPath));
        }

        $this->makeDir($pluginPath . '/src');

        return $pluginPath;
    }

    private function makeDir(string $path): void
    {
        if (!is_dir($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new \RuntimeException(sprintf('Could not create directory "%s".', $path));
        }
    }

    private function toLabel(string $pascalCase): string
    {
        return trim((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $pascalCase));
    }

    private function toKebabCase(string $pascalCase): string
    {
        return strtolower((string) preg_replace('/(?<!^)[A-Z]/', '-$0', $pascalCase));
    }

    private function toPascalCase(string $value): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value)));
    }
}