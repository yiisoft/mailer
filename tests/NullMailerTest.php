<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Mailer\Message;
use Yiisoft\Mailer\NullMailer;

final class NullMailerTest extends TestCase
{
    public function testSend(): void
    {
        $mailer = new NullMailer();

        $this->expectNotToPerformAssertions();
        $mailer->send(new Message());
    }

    public function testSendMultiple(): void
    {
        $mailer = new NullMailer();
        $message1 = new Message();
        $message2 = new Message();

        $result = $mailer->sendMultiple([$message1, 'test' => $message2]);

        $this->assertSame([$message1, $message2], $result->successMessages);
        $this->assertSame([], $result->failMessages);
    }
}
