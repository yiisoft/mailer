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

    public function testWithAddedFrom(): void
    {
        $sourceMessage = new Message(from: 'test@example.com');

        $message = $sourceMessage->withAddedFrom('mark@example.com');

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame(['test@example.com', 'mark@example.com'], $message->getFrom());
    }

    public function testWithAddedFromToMessageWithoutFrom(): void
    {
        $sourceMessage = new Message();

        $message = $sourceMessage->withAddedFrom('mark@example.com');

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame('mark@example.com', $message->getFrom());
    }

    public function testWithTo(): void
    {
        $message = (new Message())->withTo('test@example.com');
        $this->assertSame('test@example.com', $message->getTo());
    }

    public function testWithAddedTo(): void
    {
        $sourceMessage = new Message(to: 'test@example.com');

        $message = $sourceMessage->withAddedTo('mark@example.com');

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame(['test@example.com', 'mark@example.com'], $message->getTo());
    }

    public function testWithReplyTo(): void
    {
        $message = (new Message())->withReplyTo('test@example.com');
        $this->assertSame('test@example.com', $message->getReplyTo());
    }

    public function testWithAddedReplyTo(): void
    {
        $sourceMessage = new Message(replyTo: 'test@example.com');

        $message = $sourceMessage->withAddedReplyTo('mark@example.com');

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame(['test@example.com', 'mark@example.com'], $message->getReplyTo());
    }

    public function testWithCc(): void
    {
        $message = (new Message())->withCc('test@example.com');
        $this->assertSame('test@example.com', $message->getCc());
    }

    public function testWithAddedCc(): void
    {
        $sourceMessage = new Message(cc: 'test@example.com');

        $message = $sourceMessage->withAddedCc('mark@example.com');

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame(['test@example.com', 'mark@example.com'], $message->getCc());
    }

    public function testWithBcc(): void
    {
        $message = (new Message())->withBcc('test@example.com');
        $this->assertSame('test@example.com', $message->getBcc());
    }

    public function testWithAddedBcc(): void
    {
        $sourceMessage = new Message(bcc: 'test@example.com');

        $message = $sourceMessage->withAddedBcc('mark@example.com');

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame(['test@example.com', 'mark@example.com'], $message->getBcc());
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
            'null' => [null],
        ];
    }

    #[DataProvider('dataDate')]
    public function testWithDate(?DateTimeInterface $date): void
    {
        $sourceMessage = new Message(date: new DateTimeImmutable());

        $message = $sourceMessage->withDate($date);

        $this->assertNotSame($message, $sourceMessage);
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

    public function testWithoutAttachments(): void
    {
        $sourceMessage = new Message(attachments: [File::fromContent('hello')]);

        $message = $sourceMessage->withoutAttachments();

        $this->assertNotSame($message, $sourceMessage);
        $this->assertNull($message->getAttachments());
    }

    public function testWithoutEmbeddings(): void
    {
        $sourceMessage = new Message(embeddings: [File::fromContent('hello')]);

        $message = $sourceMessage->withoutEmbeddings();

        $this->assertNotSame($message, $sourceMessage);
        $this->assertNull($message->getEmbeddings());
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

    public function testWithHeaders(): void
    {
        $sourceMessage = new Message(headers: ['X-Test' => '1']);

        $message = $sourceMessage->withHeaders([
            'X-Origin' => ['0', '1'],
            'X-Pass' => 'pass',
        ]);

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame(
            [
                'X-Origin' => ['0', '1'],
                'X-Pass' => ['pass'],
            ],
            $message->getHeaders(),
        );
    }

    public function testWithHtmlBody(): void
    {
        $sourceMessage = new Message(htmlBody: '<b>Hello</b>');

        $message = $sourceMessage->withHtmlBody('<i>Test</i>');

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame('<i>Test</i>', $message->getHtmlBody());
    }

    public function testWithTextBody(): void
    {
        $sourceMessage = new Message(textBody: 'Hello');

        $message = $sourceMessage->withTextBody('Test');

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame('Test', $message->getTextBody());
    }

    public function testWithAddedAttachments(): void
    {
        $file1 = File::fromContent('1');
        $file2 = File::fromContent('2');
        $file3 = File::fromContent('3');
        $sourceMessage = new Message(attachments: [$file1]);

        $message = $sourceMessage->withAddedAttachments($file2, $file3);

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame([$file1, $file2, $file3], $message->getAttachments());
    }

    public function testWithAddedEmbeddings(): void
    {
        $file1 = File::fromContent('1');
        $file2 = File::fromContent('2');
        $file3 = File::fromContent('3');
        $sourceMessage = new Message(embeddings: [$file1]);

        $message = $sourceMessage->withAddedEmbeddings($file2, $file3);

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame([$file1, $file2, $file3], $message->getEmbeddings());
    }

    public static function dataAddedHeader(): iterable
    {
        yield [
            ['X-Test' => ['0', '1']],
            'X-Test',
            '1',
        ];
        yield [
            ['X-Test' => ['0'], 'X-Origin' => 'on'],
            'X-Origin',
            'on',
        ];
    }

    #[DataProvider('dataAddedHeader')]
    public function testWithAddedHeader(array $expected, string $headerName, string $headerValue): void
    {
        $sourceMessage = new Message(headers: ['X-Test' => '0']);

        $message = $sourceMessage->withAddedHeader($headerName, $headerValue);

        $this->assertNotSame($message, $sourceMessage);
        $this->assertSame($expected, $message->getHeaders());
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
        $this->assertNotSame($message, $message->withEmbeddings(File::fromContent('')));
        $this->assertNotSame($message, $message->withHeader('X-Test', '0'));
    }
}
