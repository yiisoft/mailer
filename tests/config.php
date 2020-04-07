<?php

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Log\Logger;
use Yiisoft\Mailer\Composer;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageFactory;
use Yiisoft\Mailer\MessageFactoryInterface;
use Yiisoft\Mailer\Tests\TestMailer;
use Yiisoft\Mailer\Tests\TestMessage;
use Yiisoft\View\Theme;
use Yiisoft\View\View;

$tempDir = sys_get_temp_dir();

return [
    ListenerProviderInterface::class => function () {
        return new class () implements ListenerProviderInterface {
            private $listeners = [];

            public function getListenersForEvent(object $event): iterable
            {
                $eventName = get_class($event);
                return isset($this->listeners[$eventName]) ? $this->listeners[$eventName] : [];
            }

            public function attach(callable $callback, string $eventName): void
            {
                $this->listeners[$eventName][] = $callback;
            }
        };
    },
    EventDispatcherInterface::class => Dispatcher::class,
    LoggerInterface::class => [
        '__class' => Logger::class,
        '__construct()' => [
            'targets' => [],
        ],
    ],
    Theme::class => [
        '__class' => Theme::class,
    ],
    View::class => [
        '__class' => View::class,
        '__construct()' => [
            'basePath' => $tempDir . DIRECTORY_SEPARATOR . 'views',
            'theme' => Reference::to(Theme::class),
            'eventDispatcher' => Reference::to(EventDispatcherInterface::class),
            'logger' => Reference::to(LoggerInterface::class)
        ],
    ],
    MessageFactoryInterface::class => [
        '__class' => MessageFactory::class,
        '__construct()' => [
            'class' => TestMessage::class,
        ],
    ],
    Composer::class => [
        '__class' => Composer::class,
        '__construct()' => [
            'view' => Reference::to(View::class),
            'viewPath' => $tempDir . DIRECTORY_SEPARATOR . 'views'
        ],
    ],
    MailerInterface::class => [
        '__class' => TestMailer::class,
        '__construct()' => [
            'messageFactory' => Reference::to(MessageFactoryInterface::class),
            'composer' => Reference::to(Composer::class),
            'eventDispatcher' => Reference::to(EventDispatcherInterface::class),
            'logger' => Reference::to(LoggerInterface::class),
            'path' => $tempDir . DIRECTORY_SEPARATOR . 'mails',
        ],
    ],
];
