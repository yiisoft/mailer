<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Yiisoft\Mailer\File;
use Yiisoft\Mailer\Message;

final class MessageTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaultValues(): void
    {
        $message = new Message();
        $this->assertSame('utf-8', $message->getCharset());
        $this->assertSame([], $message->getFrom());
        $this->assertSame([], $message->getTo());
        $this->assertSame([], $message->getReplyTo());
        $this->assertSame([], $message->getCc());
        $this->assertSame([], $message->getBcc());
        $this->assertSame('', $message->getSubject());
        $this->assertNull($message->getDate());
        $this->assertSame(3, $message->getPriority());
        $this->assertSame('', $message->getReturnPath());
        $this->assertSame('', $message->getSender());
        $this->assertSame('', $message->getTextBody());
        $this->assertSame('', $message->getHtmlBody());
        $this->assertSame([], $message->getAttachments());
        $this->assertSame([], $message->getEmbeddings());
        $this->assertSame([], $message->getHeaders());
        $this->assertNull($message->getError());
    }

    public function testCharset(): void
    {
        $message = (new Message())->withCharset('windows-1251');
        $this->assertSame('windows-1251', $message->getCharset());
    }

    public function testFrom(): void
    {
        $message = (new Message())->withFrom('test@example.com');
        $this->assertSame('test@example.com', $message->getFrom());
    }

    public function testTo(): void
    {
        $message = (new Message())->withTo('test@example.com');
        $this->assertSame('test@example.com', $message->getTo());
    }

    public function testReplyTo(): void
    {
        $message = (new Message())->withReplyTo('test@example.com');
        $this->assertSame('test@example.com', $message->getReplyTo());
    }

    public function testCc(): void
    {
        $message = (new Message())->withCc('test@example.com');
        $this->assertSame('test@example.com', $message->getCc());
    }

    public function testBcc(): void
    {
        $message = (new Message())->withBcc('test@example.com');
        $this->assertSame('test@example.com', $message->getBcc());
    }

    public function testSubject(): void
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

    /**
     * @dataProvider dataDate
     */
    public function testDate(DateTimeInterface $date): void
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
        $this->assertNotSame($message, $message->withPriority(1));
        $this->assertNotSame($message, $message->withAttached(File::fromContent('')));
        $this->assertNotSame($message, $message->withEmbedded(File::fromContent('')));
        $this->assertNotSame($message, $message->withAddedHeader('X-Test', '0'));
        $this->assertNotSame($message, $message->withHeader('X-Test', '0'));
        $this->assertNotSame($message, $message->withHeaders([]));
    }
}
