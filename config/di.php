<?php

declare(strict_types=1);

use Yiisoft\Aliases\Aliases;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Mailer\FileMailer;

/** @var array $params */

return [
    FileMailer::class => [
        '__construct()' => [
            'path' => DynamicReference::to(
                static function (?Aliases $aliases = null) use ($params): string {
                    if ($aliases !== null) {
                        return $aliases->get(
                            $params['yiisoft/mailer']['fileMailer']['path'],
                        );
                    }
                    if (str_starts_with($params['yiisoft/mailer']['fileMailer']['path'], '@')) {
                        throw new LogicException(
                            sprintf(
                                'Aliases dependency are required to resolve path "%s".',
                                $params['yiisoft/mailer']['fileMailer']['path']
                            )
                        );
                    }
                    return $params['yiisoft/mailer']['fileMailer']['path'];
                }
            ),
        ],
    ],
];
