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

        $replacements = $this->buildReplacements($pluginName, $vendor, $author);
        $this->makeDir($pluginPath . '/src');
        $this->renderTemplate('composer.json.tpl', $pluginPath . '/composer.json', $replacements);
        $this->renderTemplate('Plugin.php.tpl', $pluginPath . '/src/' . $pluginName . '.php', $replacements);
        $this->makeDir($pluginPath . '/src/Resources/config');
        $this->renderTemplate('services.xml.tpl', $pluginPath . '/src/Resources/config/services.xml', $replacements);
        $this->makeDir($pluginPath . '/tests');
        $this->renderTemplate('PluginTest.php.tpl', $pluginPath . '/tests/' . $pluginName . 'Test.php', $replacements);
        return $pluginPath;
    }

    private function buildReplacements(string $pluginName, string $vendor, string $author): array
    {
        $namespace = $this->toPascalCase($vendor) . '\\' . $pluginName;

        return [
            '{{PLUGIN_NAME}}' => $pluginName,
            '{{PLUGIN_LABEL}}' => $this->toLabel($pluginName),
            '{{PLUGIN_NAME_KEBAB}}' => $this->toKebabCase($pluginName),
            '{{NAMESPACE}}' => $namespace,
            '{{NAMESPACE_JSON}}' => str_replace('\\', '\\\\', $namespace),
            '{{VENDOR}}' => strtolower($vendor),
            '{{AUTHOR}}' => $author,
            '{{YEAR}}' => date('Y'),
        ];
    }

    private function renderTemplate(string $templateName, string $destination, array $replacements): void
    {
        $templatePath = $this->templateDir . '/' . $templateName;

        if (!is_file($templatePath)) {
            throw new \RuntimeException(sprintf('Template "%s" not found in "%s".', $templateName, $this->templateDir));
        }

        $content = file_get_contents($templatePath);
        file_put_contents($destination, strtr($content, $replacements));
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
