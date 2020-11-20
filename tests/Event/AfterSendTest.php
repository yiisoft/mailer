<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\Event;

use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Mailer\Tests\TestCase;

class AfterSendTest extends TestCase
{
    /**
     * @dataProvider successfulProvider
     */
    public function testIsSuccessful(MessageInterface $message): void
    {
        $event = new AfterSend($message);
        $this->assertSame($message, $event->getMessage());
    }

    public function successfulProvider(): array
    {
        return [
            [$this->createMessage('foo')],
            [$this->createMessage('bar')],
        ];
    }
}
