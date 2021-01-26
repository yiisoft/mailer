<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\Event;

use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\Tests\TestCase;

final class BeforeSendTest extends TestCase
{
    public function testSetup(): void
    {
        $message = $this->createMessage();
        $event = new BeforeSend($message);
        $this->assertSame($message, $event->getMessage());
        $this->assertFalse($event->isPropagationStopped());
    }

    public function testStopPropagation(): void
    {
        $event = new BeforeSend($this->createMessage());
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}
