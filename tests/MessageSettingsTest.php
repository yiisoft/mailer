<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use DateTimeImmutable;
use Yiisoft\Mailer\File;
use Yiisoft\Mailer\Message;
use Yiisoft\Mailer\MessageSettings;
use Yiisoft\Mailer\Priority;

final class MessageSettingsTest extends TestCase
{
    public function testApplyToEmptyMessage(): void
    {
        $sourceMessage = new Message();

        $date = new DateTimeImmutable();
        $file1 = File::fromContent('1');
        $file2 = File::fromContent('2');
        $file3 = File::fromContent('3');
        $file4 = File::fromContent('4');
        $settings = new MessageSettings(
            charset: 'utf-8',
            from: 'from@example.com',
            addFrom: 'from-add@example.com',
            to: 'to@example.com',
            addTo: 'to-add@example.com',
            replyTo: 'reply-to@example.com',
            addReplyTo: 'reply-to-add@example.com',
            cc: 'cc@example.com',
            addCc: 'cc-add@example.com',
            bcc: 'bcc@example.com',
            addBcc: 'bcc-add@example.com',
            subject: 'Test',
            date: $date,
            priority: Priority::HIGH,
            returnPath: 'return@example.com',
            sender: 'SENDER',
            textBody: 'my-text',
            htmlBody: '<b>Hello</b>',
            attachments: [$file1],
            addAttachments: [$file2],
            embeddings: [$file3],
            addEmbeddings: [$file4],
            headers: ['X-Test' => 'on'],
            overwriteHeaders: ['X-Pass' => 'true'],
        );

        $message = $settings->applyTo($sourceMessage);

        $this->assertSame('utf-8', $message->getCharset());
        $this->assertSame(['from@example.com', 'from-add@example.com'], $message->getFrom());
        $this->assertSame(['to@example.com', 'to-add@example.com'], $message->getTo());
        $this->assertSame(['reply-to@example.com', 'reply-to-add@example.com'], $message->getReplyTo());
        $this->assertSame(['cc@example.com', 'cc-add@example.com'], $message->getCc());
        $this->assertSame(['bcc@example.com', 'bcc-add@example.com'], $message->getBcc());
        $this->assertSame('Test', $message->getSubject());
        $this->assertSame($date, $message->getDate());
        $this->assertSame(Priority::HIGH, $message->getPriority());
        $this->assertSame('return@example.com', $message->getReturnPath());
        $this->assertSame('SENDER', $message->getSender());
        $this->assertSame('my-text', $message->getTextBody());
        $this->assertSame('<b>Hello</b>', $message->getHtmlBody());
        $this->assertSame([$file1, $file2], $message->getAttachments());
        $this->assertSame([$file3, $file4], $message->getEmbeddings());
        $this->assertSame(['X-Test' => ['on'], 'X-Pass' => ['true']], $message->getHeaders());
    }

    public function testApplyToFilledMessage(): void
    {
        $sourceDate = new DateTimeImmutable();
        $sourceFile1 = File::fromContent('1');
        $sourceFile2 = File::fromContent('2');
        $sourceMessage = new Message(
            charset: 'windows-1251',
            from: 'from@yiiframework.com',
            to: 'to@yiiframework.com',
            replyTo: 'reply-to@yiiframework.com',
            cc: 'cc@yiiframework.com',
            bcc: 'bcc@yiiframework.com',
            subject: 'Source Test',
            date: $sourceDate,
            priority: Priority::LOW,
            returnPath: 'return@yiiframework.com',
            sender: 'SOURCE_SENDER',
            textBody: 'source-text',
            htmlBody: '<b>Hello, World!</b>',
            attachments: [$sourceFile1],
            embeddings: [$sourceFile2],
            headers: ['X-Spam' => 'no', 'X-Pass' => 'false'],
        );

        $date = new DateTimeImmutable();
        $file1 = File::fromContent('1');
        $file2 = File::fromContent('2');
        $file3 = File::fromContent('3');
        $file4 = File::fromContent('4');
        $settings = new MessageSettings(
            charset: 'utf-8',
            from: 'from@example.com',
            addFrom: 'from-add@example.com',
            to: 'to@example.com',
            addTo: 'to-add@example.com',
            replyTo: 'reply-to@example.com',
            addReplyTo: 'reply-to-add@example.com',
            cc: 'cc@example.com',
            addCc: 'cc-add@example.com',
            bcc: 'bcc@example.com',
            addBcc: 'bcc-add@example.com',
            subject: 'Test',
            date: $date,
            priority: Priority::HIGH,
            returnPath: 'return@example.com',
            sender: 'SENDER',
            textBody: 'my-text',
            htmlBody: '<b>Hello</b>',
            attachments: [$file1],
            addAttachments: [$file2],
            embeddings: [$file3],
            addEmbeddings: [$file4],
            headers: ['X-Test' => 'on'],
            overwriteHeaders: ['X-Pass' => 'true'],
        );

        $message = $settings->applyTo($sourceMessage);

        $this->assertSame('windows-1251', $message->getCharset());
        $this->assertSame(['from@yiiframework.com', 'from-add@example.com'], $message->getFrom());
        $this->assertSame(['to@yiiframework.com', 'to-add@example.com'], $message->getTo());
        $this->assertSame(['reply-to@yiiframework.com', 'reply-to-add@example.com'], $message->getReplyTo());
        $this->assertSame(['cc@yiiframework.com', 'cc-add@example.com'], $message->getCc());
        $this->assertSame(['bcc@yiiframework.com', 'bcc-add@example.com'], $message->getBcc());
        $this->assertSame('Source Test', $message->getSubject());
        $this->assertSame($sourceDate, $message->getDate());
        $this->assertSame(Priority::LOW, $message->getPriority());
        $this->assertSame('return@yiiframework.com', $message->getReturnPath());
        $this->assertSame('SOURCE_SENDER', $message->getSender());
        $this->assertSame('source-text', $message->getTextBody());
        $this->assertSame('<b>Hello, World!</b>', $message->getHtmlBody());
        $this->assertSame([$sourceFile1, $file2], $message->getAttachments());
        $this->assertSame([$sourceFile2, $file4], $message->getEmbeddings());
        $this->assertSame(['X-Test' => ['on'], 'X-Spam' => ['no'], 'X-Pass' => ['true']], $message->getHeaders());
    }

    public function testHeaders(): void
    {
        $settings = new MessageSettings(
            headers: ['X-Test' => ['0', '1']],
            overwriteHeaders: ['X-Pass' => 'true'],
        );
        $this->assertSame(['X-Test' => ['0', '1']], $settings->headers);
        $this->assertSame(['X-Pass' => ['true']], $settings->overwriteHeaders);
    }

    public function testHeadersDoNotOverride(): void
    {
        $sourceMessage = new Message(headers: ['X-Test' => 'a']);
        $settings = new MessageSettings(headers: ['X-Test' => 'b', 'X-Origin' => 'on']);

        $message = $settings->applyTo($sourceMessage);

        $this->assertSame(
            [
                'X-Test' => ['a'],
                'X-Origin' => ['on'],
            ],
            $message->getHeaders(),
        );
    }
}
