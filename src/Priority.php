<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

/**
 * Priority indicates importance of message.
 *
 * @api
 */
enum Priority: int
{
    case HIGHEST = 1;
    case HIGH = 2;
    case NORMAL = 3;
    case LOW = 4;
    case LOWEST = 5;
}
