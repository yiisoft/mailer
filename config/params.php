<?php

declare(strict_types=1);

use Yiisoft\Mailer\Debug\MailerCollector;
use Yiisoft\Mailer\Debug\MailerInterfaceProxy;
use Yiisoft\Mailer\MailerInterface;

return [
    'yiisoft/yii-debug' => [
        'collectors' => [
            MailerCollector::class,
        ],
        'trackedServices' => [
            MailerInterface::class => [MailerInterfaceProxy::class, MailerCollector::class],
        ],
    ],
];
