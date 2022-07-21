<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\File;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageBodyRenderer;
use Yiisoft\Mailer\MessageBodyTemplate;
use Yiisoft\Mailer\MessageFactoryInterface;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Mailer\Tests\TestAsset\DummyMailer;
use Yiisoft\Mailer\Tests\TestAsset\DummyMessage;

use function basename;
use function strip_tags;

final class MailerTest extends TestCase
{
    public function testWithTemplate(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $template = new MessageBodyTemplate($this->getTestFilePath(), '', '');

        $oldMessageBodyRenderer = $this->getInaccessibleProperty($mailer, 'messageBodyRenderer');
        $newMailer = $mailer->withTemplate($template);
        $newMessageBodyRenderer = $this->getInaccessibleProperty($newMailer, 'messageBodyRenderer');

        $this->assertNotSame($mailer, $newMailer);
        $this->assertNotSame($oldMessageBodyRenderer, $newMessageBodyRenderer);

        $this->assertNotSame($template, $this->getInaccessibleProperty($oldMessageBodyRenderer, 'template'));
        $this->assertSame($template, $this->getInaccessibleProperty($newMessageBodyRenderer, 'template'));
    }

    public function testCompose(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $message = $mailer->compose();
        $this->assertInstanceOf(MessageInterface::class, $message);
    }

    public function testComposeWithView(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $viewPath = $this->getTestFilePath();

        $htmlViewName = 'test-html-view';
        $htmlViewFileName = $viewPath . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $htmlViewFileContent = 'HTML <b>view file</b> content.';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $textViewName = 'test-text-view';
        $textViewFileName = $viewPath . DIRECTORY_SEPARATOR . $textViewName . '.php';
        $textViewFileContent = 'Plain text view file content.';
        $this->saveFile($textViewFileName, $textViewFileContent);

        $message = $mailer->compose([
            'html' => $htmlViewName,
            'text' => $textViewName,
        ]);
        $this->assertSame($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render HTML!');
        $this->assertSame($textViewFileContent, $message->getTextBody(), 'Unable to render text!');

        $message = $mailer->compose($htmlViewName);
        $this->assertSame($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render HTML by direct view!');
        $this->assertSame(
            strip_tags($htmlViewFileContent),
            $message->getTextBody(),
            'Unable to render text by direct view!'
        );
    }

    public function testComposeWithLocale(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $viewPath = $this->getTestFilePath();

        $htmlViewName = 'test-html-view';
        $this->saveFile(
            $viewPath . DIRECTORY_SEPARATOR . $htmlViewName . '.php',
            'HTML <b>view file</b> content.'
        );

        $textViewName = 'test-text-view';
        $this->saveFile(
            $viewPath . DIRECTORY_SEPARATOR . $textViewName . '.php',
            'Plain text view file content.'
        );

        $htmlViewFileName = $viewPath . DIRECTORY_SEPARATOR . 'de_DE' . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $htmlViewFileContent = 'de_DE HTML <b>view file</b> content.';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $textViewFileName = $viewPath . DIRECTORY_SEPARATOR . 'de_DE' . DIRECTORY_SEPARATOR . $textViewName . '.php';
        $textViewFileContent = 'de_DE plain text view file content.';
        $this->saveFile($textViewFileName, $textViewFileContent);

        $message = $mailer
            ->withLocale('de_DE')
            ->compose([
                'html' => $htmlViewName,
                'text' => $textViewName,
            ]);
        $this->assertSame($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render HTML!');
        $this->assertSame($textViewFileContent, $message->getTextBody(), 'Unable to render text!');

        $message = $mailer
            ->withLocale('de_DE')
            ->compose($htmlViewName);
        $this->assertSame($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render HTML by direct view!');
        $this->assertSame(
            strip_tags($htmlViewFileContent),
            $message->getTextBody(),
            'Unable to render text by direct view!'
        );
    }

    public function testComposeWithViewAndWithEmbedFile(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $viewPath = $this->getTestFilePath();

        $textViewName = 'test-text-view';
        $textViewFileName = $viewPath . DIRECTORY_SEPARATOR . $textViewName . '.php';
        $file = File::fromPath(__FILE__, basename($textViewFileName), 'application/x-php');
        $textViewFileContent = "Embed image: {$file->cid()}";
        $this->saveFile($textViewFileName, $textViewFileContent);

        $message = $mailer
            ->compose(['text' => $textViewName])
            ->withEmbedded($file);

        $this->assertSame($textViewFileContent, $message->getTextBody(), 'Unable to render text!');
    }

    /**
     * @dataProvider messagesProvider
     *
     * @param array $messages
     */
    public function testSendMultiple(array $messages): void
    {
        $mailer = $this->get(MailerInterface::class);
        $this->assertCount(0, $mailer->sendMultiple($messages));
        $this->assertSame($messages, $mailer->sentMessages);
    }

    public function messagesProvider(): array
    {
        return [
            [[]],
            [[$this->createMessage()]],
            [[$this->createMessage('bar'), $this->createMessage('baz')]],
        ];
    }

    public function testSendMultipleExceptions(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $messages = [$this->createMessage(''), $this->createMessage(), $this->createMessage('')];
        $failed = $mailer->sendMultiple($messages);

        $this->assertCount(2, $failed);
        $this->assertInstanceOf(InvalidArgumentException::class, $failed[0]->getError());
        $this->assertInstanceOf(InvalidArgumentException::class, $failed[1]->getError());
        $this->assertSame("Message's subject is required.", $failed[0]->getError()->getMessage());
        $this->assertSame("Message's subject is required.", $failed[1]->getError()->getMessage());
    }

    public function testBeforeSend(): void
    {
        $message = new DummyMessage();
        $event = new BeforeSend($message);
        $messageFactory = $this->get(MessageFactoryInterface::class);
        $messageBodyRenderer = $this->get(MessageBodyRenderer::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher
            ->method('dispatch')
            ->willReturn($event);
        $mailer = new DummyMailer($messageFactory, $messageBodyRenderer, $eventDispatcher);

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
        $message = $mailer->createMessage();
        $mailer->afterSend($message);

        $this->assertSame([AfterSend::class],
            $this
                ->get(EventDispatcherInterface::class)
                ->getEventClasses());
    }
}
