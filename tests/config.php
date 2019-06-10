<?php
use Psr\EventDispatcher\{EventDispatcherInterface, ListenerProviderInterface};
use Psr\Log\LoggerInterface;
use Yiisoft\EventDispatcher\{Dispatcher, Provider\Provider};
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Log\Logger;
use Yiisoft\Mailer\{Composer, MailerInterface, MessageFactory, MessageFactoryInterface};
use Yiisoft\Mailer\Tests\{TestMailer, TestMessage};
use Yiisoft\View\{Theme, View};

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
            'basePath'=> sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'views',
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
            'viewPath' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'views'
        ],
    ],
    MailerInterface::class => [
        '__class' => TestMailer::class,
        '__construct()' => [
            'messageFactory'=> Reference::to(MessageFactoryInterface::class),
            'composer'=> Reference::to(Composer::class),
            'eventDispatcher' => Reference::to(EventDispatcherInterface::class),
            'logger'=> Reference::to(LoggerInterface::class)
        ],
    ],
];
