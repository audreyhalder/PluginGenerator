<?php

declare(strict_types=1);

namespace AudreyHalder\NewPlugin\Tests;

use AudreyHalder\NewPlugin\NewPlugin;
use PHPUnit\Framework\TestCase;

class NewPluginTest extends TestCase
{
    public function testPluginClassExists(): void
    {
        self::assertTrue(class_exists(NewPlugin::class));
    }
}
