<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use ReflectionClass;
use Yiisoft\Files\FileHelper;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageBodyRenderer;
use Yiisoft\Mailer\MessageBodyTemplate;
use Yiisoft\Mailer\MessageFactory;
use Yiisoft\Mailer\MessageFactoryInterface;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Mailer\Tests\TestAsset\DummyMailer;
use Yiisoft\Mailer\Tests\TestAsset\DummyMessage;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;
use Yiisoft\View\View;

use function basename;
use function dirname;
use function file_put_contents;
use function is_dir;
use function mkdir;
use function str_replace;
use function sys_get_temp_dir;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private ?ContainerInterface $container = null;

    protected function setUp(): void
    {
        FileHelper::ensureDirectory($this->getTestFilePath());
        $this->getContainer();
    }

    protected function tearDown(): void
    {
        $this->container = null;
        FileHelper::removeDirectory($this->getTestFilePath());
    }

    protected function get(string $id)
    {
        return $this
            ->getContainer()
            ->get($id);
    }

    protected function createMessage(
        string $subject = 'foo',
        string $from = 'from@example.com',
        string $to = 'to@example.com'
    ): MessageInterface {
        return (new DummyMessage())
            ->withSubject($subject)
            ->withFrom($from)
            ->withTo($to);
    }

    /**
     * Asserting two strings equality ignoring line endings.
     */
    protected function assertEqualsWithoutLE(string $expected, string $actual, string $message = ''): void
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);
        $this->assertEquals($expected, $actual, $message);
    }

    protected function getTestFilePath(): string
    {
        return sys_get_temp_dir()
            . DIRECTORY_SEPARATOR
            . basename(str_replace('\\', '_', static::class))
            ;
    }

    protected function saveFile(string $filename, string $data): void
    {
        $path = dirname($filename);

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($filename, $data);
    }

    /**
     * Gets an inaccessible object property.
     *
     * @return mixed
     */
    protected function getInaccessibleProperty(object $object, string $propertyName)
    {
        $class = new ReflectionClass($object);

        while (!$class->hasProperty($propertyName)) {
            $class = $class->getParentClass();
        }

        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);
        $result = $property->getValue($object);
        $property->setAccessible(false);

        return $result;
    }

    private function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $tempDir = $this->getTestFilePath();
            $eventDispatcher = new SimpleEventDispatcher();
            $view = new View($tempDir, $eventDispatcher);
            $messageBodyTemplate = new MessageBodyTemplate($tempDir, '', '');
            $messageBodyRenderer = new MessageBodyRenderer($view, $messageBodyTemplate);
            $messageFactory = new MessageFactory(DummyMessage::class);

            $this->container = new SimpleContainer([
                EventDispatcherInterface::class => $eventDispatcher,
                MailerInterface::class => new DummyMailer($messageFactory, $messageBodyRenderer, $eventDispatcher),
                MessageBodyRenderer::class => new MessageBodyRenderer($view, $messageBodyTemplate),
                MessageBodyTemplate::class => $messageBodyTemplate,
                MessageFactoryInterface::class => $messageFactory,
                View::class => $view,
            ]);
        }

        return $this->container;
    }
}
