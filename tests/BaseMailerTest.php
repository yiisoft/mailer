<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\Message;
use Yiisoft\Mailer\Tests\TestAsset\DummyMailer;

final class BaseMailerTest extends TestCase
{
    #[DataProvider('messagesProvider')]
    public function testSendMultiple(array $messages): void
    {
        $mailer = $this->get(MailerInterface::class);

        $result = $mailer->sendMultiple($messages);

        $this->assertSame($messages, $result->successMessages);
        $this->assertSame([], $result->failMessages);
        $this->assertSame($messages, $mailer->sentMessages);
    }

    public static function messagesProvider(): array
    {
        return [
            [[]],
            [[self::createMessage()]],
            [[self::createMessage('bar'), self::createMessage('baz')]],
        ];
    }

    public function testSendMultipleExceptions(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $message1 = self::createMessage('');
        $message2 = self::createMessage();
        $message3 = self::createMessage('');
        $result = $mailer->sendMultiple([
            $message1,
            $message2,
            $message3,
        ]);

        $this->assertSame([$message2], $result->successMessages);
        $this->assertCount(2, $result->failMessages);
        $this->assertInstanceOf(InvalidArgumentException::class, $result->failMessages[0]['error']);
        $this->assertInstanceOf(InvalidArgumentException::class, $result->failMessages[1]['error']);
        $this->assertSame("Message's subject is required.", $result->failMessages[0]['error']->getMessage());
        $this->assertSame("Message's subject is required.", $result->failMessages[1]['error']->getMessage());
        $this->assertSame($message1, $result->failMessages[0]['message']);
        $this->assertSame($message3, $result->failMessages[1]['message']);
    }

    public function testBeforeSend(): void
    {
        $message = new Message();
        $event = new BeforeSend($message);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher
            ->method('dispatch')
            ->willReturn($event);
        $mailer = new DummyMailer(eventDispatcher: $eventDispatcher);

        $this->assertTrue($mailer->beforeSend($message));
        $event->stopPropagation();
        $this->assertFalse($mailer->beforeSend($message));

        $this->assertSame([], $mailer->sentMessages);
        $mailer->send($message);
        $this->assertSame([], $mailer->sentMessages);
    }

    public function testAfterSend(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $message = new Message();
        $mailer->afterSend($message);

        $this->assertSame(
            [AfterSend::class],
            $this
                ->get(EventDispatcherInterface::class)
                ->getEventClasses()
        );
    }
}
