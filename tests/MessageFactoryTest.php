<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use Yiisoft\Mailer\Message;
use Yiisoft\Mailer\MessageFactory;

final class MessageFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new MessageFactory(Message::class);
        $this->assertInstanceOf(Message::class, $factory->create());
    }

    public function testConstructorThrowExceptionForInvalidMessageClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MessageFactory(self::class);
    }

    public function testWithFrom(): void
    {
        $factory = new MessageFactory(
            Message::class,
            from: 'test@example.com',
        );

        $message = $factory->create();

        $this->assertSame('test@example.com', $message->getFrom());
    }
}
