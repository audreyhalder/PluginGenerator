<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Tests;

use {{NAMESPACE}}\{{PLUGIN_NAME}};
use PHPUnit\Framework\TestCase;

class {{PLUGIN_NAME}}Test extends TestCase
{
    public function testPluginClassExists(): void
    {
        self::assertTrue(class_exists({{PLUGIN_NAME}}::class));
    }
}
