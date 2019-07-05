<?php
namespace Yiisoft\Mailer\Unit\Event;

use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\Tests\{TestCase, TestMessage};

class BeforeSendTest extends TestCase
{
    public function testSetup()
    {
        $message = $this->createMessage();
        $event = new BeforeSend($message);
        $this->assertEquals($message, $event->getMessage());
        $this->assertFalse($event->isPropagationStopped());
    }

    public function testStopPropagation()
    {
        $event = new BeforeSend($this->createMessage());
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}
