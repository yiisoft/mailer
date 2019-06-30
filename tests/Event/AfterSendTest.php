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
        $this->assertSame($message, $event->getMessage());
        $this->assertSame($successful, $event->isSuccessful());
    }

    public function successfulProvider()
    {
        return [
            [(new TestMessage())->setSubject('foo'), false],
            [(new TestMessage())->setSubject('bar'), true],
        ];
    }
}
