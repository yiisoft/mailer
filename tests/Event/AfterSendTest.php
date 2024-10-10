<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\Event;

use PHPUnit\Framework\TestCase;
use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Message;

final class AfterSendTest extends TestCase
{
    public function testSetup(): void
    {
        $message = new Message();

        $event = new AfterSend($message);

        $this->assertSame($message, $event->message);
    }
}
