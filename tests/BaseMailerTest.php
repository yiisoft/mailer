<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\Message;
use Yiisoft\Mailer\Tests\Support\DummyMailer;
use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;

final class BaseMailerTest extends TestCase
{
    public static function dataSendMultiple(): iterable
    {
        yield [[]];
        yield [[new Message(subject: 'test')]];
        yield [[new Message(subject: 'foo'), new Message(subject: 'bar')]];
    }

    #[DataProvider('dataSendMultiple')]
    public function testSendMultiple(array $messages): void
    {
        $mailer = new DummyMailer();

        $result = $mailer->sendMultiple($messages);

        $this->assertSame($messages, $result->successMessages);
        $this->assertSame([], $result->failMessages);
        $this->assertSame($messages, $mailer->sentMessages);
    }

    public function testSendMultipleExceptions(): void
    {
        $mailer = new DummyMailer();
        $message1 = new Message();
        $message2 = new Message(subject: 'test');
        $message3 = new Message();

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

    public function testBeforeSendWithPreventSendingMessage(): void
    {
        $eventDispatcher = new SimpleEventDispatcher(
            static function (object $event): void {
                if ($event instanceof BeforeSend) {
                    $event->preventSendingMessage = true;
                }
            },
        );
        $mailer = new DummyMailer(eventDispatcher: $eventDispatcher);

        $mailer->send(new Message());

        $this->assertSame([BeforeSend::class], $eventDispatcher->getEventClasses());
        $this->assertEmpty($mailer->sentMessages);
    }

    public function testBeforeSend(): void
    {
        $eventDispatcher = new SimpleEventDispatcher();
        $mailer = new DummyMailer(eventDispatcher: $eventDispatcher);
        $message = new Message(subject: 'Hello!');

        $mailer->send($message);

        $this->assertSame([BeforeSend::class, AfterSend::class], $eventDispatcher->getEventClasses());
        $this->assertSame([$message], $mailer->sentMessages);
    }
}
