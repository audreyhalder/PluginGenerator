<?php

declare(strict_types=1);

namespace Acme\AnothernewPlugin\Tests;

use Acme\AnothernewPlugin\AnothernewPlugin;
use PHPUnit\Framework\TestCase;

class AnothernewPluginTest extends TestCase
{
    public function testPluginClassExists(): void
    {
        self::assertTrue(class_exists(AnothernewPlugin::class));
    }
}
