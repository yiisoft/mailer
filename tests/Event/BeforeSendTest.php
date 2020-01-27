<?php

namespace Yiisoft\Mailer\Unit\Event;

use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\Tests\TestCase;

class BeforeSendTest extends TestCase
{
    public function testSetup(): void
    {
        $message = $this->createMessage();
        $event = new BeforeSend($message);
        $this->assertEquals($message, $event->getMessage());
        $this->assertFalse($event->isPropagationStopped());
    }

    public function testStopPropagation(): void
    {
        $event = new BeforeSend($this->createMessage());
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}
