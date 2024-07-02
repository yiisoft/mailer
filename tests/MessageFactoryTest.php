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

        $message = $factory->create();

        $this->assertInstanceOf(DummyMessage::class, $message);
        $this->assertSame('', $message->getFrom());
    }

    public function testConstructorThrowExceptionForInvalidMessageClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MessageFactory(self::class);
    }

    public function testWithFrom(): void
    {
        $factory = new MessageFactory(
            DummyMessage::class,
            from: 'test@example.com',
        );

        $message = $factory->create();

        $this->assertSame('test@example.com', $message->getFrom());
    }
}
