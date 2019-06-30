<?php
namespace Yiisoft\Mailer\Unit\Event;

use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\Tests\{TestCase, TestMessage};

class BeforeSendTest extends TestCase
{
    public function testSetup()
    {
        $message = new TestMessage();
        $event = new BeforeSend($message);
        $this->assertEquals($message, $event->getMessage());
        $this->assertFalse($event->isPropagationStopped());
    }

    public function testStopPropagation()
    {
        $event = new BeforeSend(new TestMessage());
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}
