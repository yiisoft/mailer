<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use stdClass;
use Yiisoft\Files\FileHelper;
use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\FileMailer;
use Yiisoft\Mailer\Message;
use Yiisoft\Mailer\MessageInterface;

use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;

use function file_get_contents;
use function glob;
use function is_file;
use function microtime;

final class FileMailerTest extends \PHPUnit\Framework\TestCase
{
    public function testSend(): void
    {
        $directory = $this->prepareAndGetTempDirectory();
        $eventDispatcher = new SimpleEventDispatcher();
        $mailer = new FileMailer(
            $directory,
            eventDispatcher: $eventDispatcher,
        );

        $message = new Message(
            from: 'from@example.com',
            to: 'to@example.com',
            subject: 'test subject',
            textBody: 'text body',
        );

        $mailer->send($message);
        $files = glob($directory . '/*.eml');

        $this->assertCount(1, $files);
        $this->assertTrue(is_file($files[0]));
        $this->assertSame((string) $message, file_get_contents($files[0]));
        $this->assertSame(
            [BeforeSend::class, AfterSend::class],
            $eventDispatcher->getEventClasses(),
        );
    }

    public static function dataSendWithFilenameCallback(): array
    {
        $time = microtime(true);
        return [
            'without-message' => [
                static fn() => "test-file-{$time}.txt",
                "test-file-{$time}.txt",
            ],
            'with-message' => [
                static fn (MessageInterface $message) => "{$message->getFrom()}-{$time}.txt",
                "from@example.com-{$time}.txt",
            ],
        ];
    }

    #[DataProvider('dataSendWithFilenameCallback')]
    public function testSendWithFilenameCallback(callable $filenameCallback, string $filenameExpected): void
    {
        $directory = $this->prepareAndGetTempDirectory();
        $mailer = new FileMailer($directory, $filenameCallback);

        $message = new Message(
            from: 'from@example.com',
            to: 'to@example.com',
            subject: 'test subject',
            textBody: 'text body',
        );

        $mailer->send($message);
        $files = glob($directory . '/' . $filenameExpected);

        $this->assertCount(1, $files);
        $this->assertTrue(is_file($files[0]));
        $this->assertSame((string) $message, file_get_contents($files[0]));
    }

    public static function dataInvalidFilenameCallbackProvider(): array
    {
        return [
            'int' => ['int', static fn (): int => 1],
            'float' => ['float', static fn (): float => 1,1],
            'bool' => ['bool', static fn (): bool => true],
            'array' => ['array', static fn (): array => []],
            'object' => ['stdClass', static fn (): stdClass => new stdClass()],
            'callable' => ['Closure', static fn (): Closure => static fn () => 'string'],
        ];
    }

    #[DataProvider('dataInvalidFilenameCallbackProvider')]
    public function testInvalidFilenameCallbackProvider($type, callable $filenameCallback): void
    {
        $directory = $this->prepareAndGetTempDirectory();
        $mailer = new FileMailer($directory, $filenameCallback);
        $message = new Message(subject: 'test');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Filename must be a string. "' . $type . '" received.');
        $mailer->send($message);
    }

    private function prepareAndGetTempDirectory(): string
    {
        $directory = __DIR__ . '/runtime/FileMailer';
        FileHelper::removeDirectory($directory);
        return $directory;
    }
}
