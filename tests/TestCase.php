<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionClass;
use Yiisoft\Di\Container;
use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\Factory\Definition\Reference;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageBodyRenderer;
use Yiisoft\Mailer\MessageBodyTemplate;
use Yiisoft\Mailer\MessageFactory;
use Yiisoft\Mailer\MessageFactoryInterface;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Mailer\Tests\TestAsset\DummyMailer;
use Yiisoft\Mailer\Tests\TestAsset\DummyMessage;
use Yiisoft\View\Theme;
use Yiisoft\View\View;

use function basename;
use function dirname;
use function file_put_contents;
use function getmypid;
use function is_dir;
use function mkdir;
use function str_replace;
use function sys_get_temp_dir;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private ?ContainerInterface $container = null;

    protected function setUp(): void
    {
        $this->getContainer();
    }

    protected function tearDown(): void
    {
        $this->container = null;
    }

    protected function get(string $id)
    {
        return $this->getContainer()->get($id);
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
     *
     * @param string $expected
     * @param string $actual
     * @param string $message
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
            . '_'
            . getmypid()
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
     * @param object $object
     * @param string $propertyName
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

            $this->container = new Container([
                Theme::class => [
                    'class' => Theme::class,
                ],

                View::class => [
                    'class' => View::class,
                    '__construct()' => [
                        'basePath' => $tempDir,
                    ],
                ],

                MessageFactoryInterface::class => [
                    'class' => MessageFactory::class,
                    '__construct()' => [
                        'class' => DummyMessage::class,
                    ],
                ],

                MessageBodyRenderer::class => [
                    'class' => MessageBodyRenderer::class,
                    '__construct()' => [
                        'view' => Reference::to(View::class),
                        'template' => Reference::to(MessageBodyTemplate::class),
                    ],
                ],

                MessageBodyTemplate::class => [
                    'class' => MessageBodyTemplate::class,
                    '__construct()' => [
                        'viewPath' => $tempDir,
                        'htmlLayout' => '',
                        'textLayout' => '',
                    ],
                ],

                LoggerInterface::class => NullLogger::class,
                MailerInterface::class => DummyMailer::class,
                EventDispatcherInterface::class => Dispatcher::class,
                ListenerProviderInterface::class => Provider::class,
            ]);
        }

        return $this->container;
    }
}
