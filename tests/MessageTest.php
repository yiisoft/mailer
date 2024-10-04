<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Yiisoft\Mailer\File;
use Yiisoft\Mailer\Message;
use Yiisoft\Mailer\Priority;

final class MessageTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaultValues(): void
    {
        $message = new Message();
        $this->assertNull($message->getCharset());
        $this->assertNull($message->getFrom());
        $this->assertNull($message->getTo());
        $this->assertNull($message->getReplyTo());
        $this->assertNull($message->getCc());
        $this->assertNull($message->getBcc());
        $this->assertNull($message->getSubject());
        $this->assertNull($message->getDate());
        $this->assertNull($message->getPriority());
        $this->assertNull($message->getReturnPath());
        $this->assertNull($message->getSender());
        $this->assertNull($message->getTextBody());
        $this->assertNull($message->getHtmlBody());
        $this->assertNull($message->getAttachments());
        $this->assertNull($message->getEmbeddings());
        $this->assertNull($message->getHeaders());
    }

    public function testWithCharset(): void
    {
        $message = (new Message())->withCharset('windows-1251');
        $this->assertSame('windows-1251', $message->getCharset());
    }

    public function testWithFrom(): void
    {
        $message = (new Message())->withFrom('test@example.com');
        $this->assertSame('test@example.com', $message->getFrom());
    }

    public function testWithTo(): void
    {
        $message = (new Message())->withTo('test@example.com');
        $this->assertSame('test@example.com', $message->getTo());
    }

    public function testWithReplyTo(): void
    {
        $message = (new Message())->withReplyTo('test@example.com');
        $this->assertSame('test@example.com', $message->getReplyTo());
    }

    public function testWithCc(): void
    {
        $message = (new Message())->withCc('test@example.com');
        $this->assertSame('test@example.com', $message->getCc());
    }

    public function testWithBcc(): void
    {
        $message = (new Message())->withBcc('test@example.com');
        $this->assertSame('test@example.com', $message->getBcc());
    }

    public function testWithSubject(): void
    {
        $message = (new Message())->withSubject('test');
        $this->assertSame('test', $message->getSubject());
    }

    public static function dataDate(): array
    {
        return [
            'immutable' => [new DateTimeImmutable()],
            'mutable' => [new DateTime()],
        ];
    }

    #[DataProvider('dataDate')]
    public function testWithDate(DateTimeInterface $date): void
    {
        $message = (new Message())->withDate($date);
        $this->assertEquals($date, $message->getDate());
    }

    public function testGetHeader(): void
    {
        $message = new Message(
            headers: [
                'X-Test' => ['a', 'b'],
            ],
        );

        $this->assertSame([], $message->getHeader('X-Not-Exists'));
        $this->assertSame(['a', 'b'], $message->getHeader('X-Test'));
    }

    public function testToString(): void
    {
        $message = new Message(
            textBody: 'Hello, World!',
            headers: [
                'X-Test' => ['a', 'b'],
            ],
        );

        $this->assertSame(
            <<<BODY
            X-Test: a
            X-Test: b
            Hello, World!
            BODY,
            (string) $message
        );
    }

    public function testImmutability(): void
    {
        $message = new Message();
        $this->assertNotSame($message, $message->withCharset('utf-8'));
        $this->assertNotSame($message, $message->withFrom([]));
        $this->assertNotSame($message, $message->withTo([]));
        $this->assertNotSame($message, $message->withReplyTo([]));
        $this->assertNotSame($message, $message->withCc([]));
        $this->assertNotSame($message, $message->withBcc([]));
        $this->assertNotSame($message, $message->withSubject(''));
        $this->assertNotSame($message, $message->withDate(new DateTimeImmutable()));
        $this->assertNotSame($message, $message->withReturnPath(''));
        $this->assertNotSame($message, $message->withSender(''));
        $this->assertNotSame($message, $message->withPriority(Priority::HIGHEST));
        $this->assertNotSame($message, $message->withAttachments(File::fromContent('')));
        $this->assertNotSame($message, $message->withAddedAttachments());
        $this->assertNotSame($message, $message->withEmbeddings(File::fromContent('')));
        $this->assertNotSame($message, $message->withAddedEmbeddings());
        $this->assertNotSame($message, $message->withAddedHeader('X-Test', '0'));
        $this->assertNotSame($message, $message->withHeader('X-Test', '0'));
        $this->assertNotSame($message, $message->withHeaders([]));
    }
}
