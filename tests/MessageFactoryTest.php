<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use Yiisoft\Mailer\MessageFactory;
use Yiisoft\Mailer\MessageInterface;

class MessageFactoryTest extends TestCase
{
    public function testSetup(): void
    {
        $factory = new MessageFactory(TestMessage::class);
        $this->assertInstanceOf(TestMessage::class, $factory->create($this->getMailer()));
    }

    public function testInvalidClass(): void
    {
        $className = self::class;
        $this->expectExceptionObject(new \Exception('Class ' . $className . ' does not implement ' . MessageInterface::class));
        new MessageFactory($className);
    }
}
