<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use Yiisoft\Mailer\MessageFactory;
use Yiisoft\Mailer\Tests\TestAsset\DummyMessage;

final class MessageFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new MessageFactory(DummyMessage::class);
        $this->assertInstanceOf(DummyMessage::class, $factory->create($this->getMailer()));
    }

    public function testConstructorThrowExceptionForInvalidMessageClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MessageFactory(self::class);
    }
}
