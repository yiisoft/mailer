<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\Event;

use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Tests\TestCase;

final class AfterSendTest extends TestCase
{
    public function testSetup(): void
    {
        $message = $this->createMessage();
        $event = new AfterSend($message);
        $this->assertSame($message, $event->getMessage());
    }
}
