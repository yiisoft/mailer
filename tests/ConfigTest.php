<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use LogicException;
use PHPUnit\Framework\TestCase;
use Throwable;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Di\BuildingException;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Files\FileHelper;
use Yiisoft\Mailer\FileMailer;
use Yiisoft\Mailer\Message;

use function dirname;

final class ConfigTest extends TestCase
{
    public function testFileMailer(): void
    {
        $runtimePath = __DIR__ . '/runtime/ConfigRuntime';
        FileHelper::removeDirectory($runtimePath);

        $container = new Container(
            ContainerConfig::create()
                ->withDefinitions(
                    $this->getDiConfig() + [
                        Aliases::class => [
                            '__construct()' => [
                                [
                                    'runtime' => $runtimePath,
                                ],
                            ],
                        ],
                    ]
                )
        );

        $fileMailer = $container->get(FileMailer::class);

        $message = new Message(subject: 'Test', textBody: 'Hello');
        $fileMailer->send($message);
        $files = glob($runtimePath . '/mail/*.eml');

        $this->assertInstanceOf(FileMailer::class, $fileMailer);
        $this->assertCount(1, $files);
        $this->assertTrue(is_file($files[0]));
        $this->assertSame((string) $message, file_get_contents($files[0]));
    }

    public function testFileMailerWithoutAliases(): void
    {
        $runtimePath = __DIR__ . '/runtime/ConfigRuntime';
        FileHelper::removeDirectory($runtimePath);

        $container = new Container(
            ContainerConfig::create()
                ->withDefinitions($this->getDiConfig())
                ->withStrictMode(true)
        );

        $exception = null;
        try {
            $container->get(FileMailer::class);
        } catch (Throwable $exception) {
        }

        $this->assertInstanceOf(BuildingException::class, $exception);
        $this->assertInstanceOf(LogicException::class, $exception->getPrevious());
        $this->assertSame('Aliases dependency are required to resolve path "@runtime/mail".', $exception->getPrevious()->getMessage());
    }

    public function testFileMailerWithoutAliasesWithAbsolutePath(): void
    {
        $path = __DIR__ . '/runtime/ConfigFileMailerAbsolute';
        FileHelper::removeDirectory($path);
        $params = [
            'yiisoft/mailer' => [
                'fileMailer' => [
                    'path' => $path,
                ],
            ],
        ];
        $container = new Container(
            ContainerConfig::create()
                ->withDefinitions($this->getDiConfig($params))
                ->withStrictMode(true)
        );

        $fileMailer = $container->get(FileMailer::class);

        $message = new Message(subject: 'Test', textBody: 'Hello');
        $fileMailer->send($message);
        $files = glob($path . '/*.eml');

        $this->assertInstanceOf(FileMailer::class, $fileMailer);
        $this->assertCount(1, $files);
        $this->assertTrue(is_file($files[0]));
        $this->assertSame((string) $message, file_get_contents($files[0]));
    }

    private function getDiConfig(?array $params = null): array
    {
        $params ??= $this->getParams();
        return require dirname(__DIR__) . '/config/di.php';
    }

    private function getParams(): array
    {
        return require dirname(__DIR__) . '/config/params.php';
    }
}
