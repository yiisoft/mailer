<?php
namespace Yiisoft\Mailer\Tests\Event;

use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Tests\{TestCase, TestMessage};

class AfterSendTest extends TestCase
{
    /**
     * @dataProvider successfulProvider
     */
    public function testIsSuccessful($message, $successful)
    {
        $event = new AfterSend($message, $successful);
        $this->assertEquals($message, $event->message);
        $this->assertEquals($successful, $event->isSuccessful());
    }

    public function successfulProvider()
    {
        return [
            [new TestMessage(), false],
            [new TestMessage(), true],
        ];
    }
}
