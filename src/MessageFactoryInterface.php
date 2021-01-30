<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

/**
 * A factory that creates a mail message instance.
 */
interface MessageFactoryInterface
{
    /**
     * Creates a new message instance.
     *
     * @return MessageInterface The message instance.
     */
    public function create(): MessageInterface;
}
