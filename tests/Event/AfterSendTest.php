<?php
namespace Yiisoft\Mailer\Tests\Event;

use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Tests\{TestCase, TestMessage};

class AfterSendTest extends TestCase
{
    /**
     * @dataProvider successfulProvider
     */
    public function testIsSuccessful($message)
    {
        $event = new AfterSend($message);
        $this->assertSame($message, $event->getMessage());
    }

    public function successfulProvider()
    {
        return [
            [$this->createMessage('foo')],
            [$this->createMessage('bar')],
        ];
    }
}
