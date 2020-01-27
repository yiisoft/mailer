<?php

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\EventDispatcher\Dispatcher;
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
    ListenerProviderInterface::class => [
        '__class' => Provider::class,
    ],
    EventDispatcherInterface::class => [
        '__class' => Dispatcher::class,
        '__construct()' => [
           'listenerProvider' => Reference::to(ListenerProviderInterface::class)
        ],
    ],
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
            'basePath'=> $tempDir . DIRECTORY_SEPARATOR . 'views',
            'theme'=> Reference::to(Theme::class),
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
            'messageFactory'=> Reference::to(MessageFactoryInterface::class),
            'composer'=> Reference::to(Composer::class),
            'eventDispatcher' => Reference::to(EventDispatcherInterface::class),
            'logger'=> Reference::to(LoggerInterface::class),
            'path' => $tempDir . DIRECTORY_SEPARATOR . 'mails',
        ],
    ],
];
