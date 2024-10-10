<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\Event;

use PHPUnit\Framework\TestCase;
use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\Message;

final class BeforeSendTest extends TestCase
{
    public function testSetup(): void
    {
        $message = new Message();

        $event = new BeforeSend($message);

        $this->assertSame($message, $event->message);
        $this->assertFalse($event->isPropagationStopped());
    }

    public function testStopPropagation(): void
    {
        $event = new BeforeSend(new Message());
        $event->stopPropagation();

        $this->assertTrue($event->isPropagationStopped());
    }
}
