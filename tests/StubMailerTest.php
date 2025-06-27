<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Mailer\Message;
use Yiisoft\Mailer\StubMailer;

final class StubMailerTest extends TestCase
{
    public function testSend(): void
    {
        $mailer = new StubMailer();
        $message = new Message();

        $mailer->send($message);

        $this->assertSame([$message], $mailer->getMessages());
    }

    public function testSendMultiple(): void
    {
        $mailer = new StubMailer();
        $message1 = new Message();
        $message2 = new Message();

        $result = $mailer->sendMultiple([$message1, 'test' => $message2]);

        $this->assertSame([$message1, $message2], $mailer->getMessages());
        $this->assertSame([$message1, $message2], $result->successMessages);
        $this->assertSame([], $result->failMessages);
    }
}
