<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Throwable;

/**
 * Result of {@see MailerInterface::sendMultiple()}.
 */
final class SendResults
{
    /**
     * @param MessageInterface[] $successMessages List of successfully sent messages.
     * @param array[] $failMessages List of failed sent messages. Each element of array is array in format:
     * ```php
     * [
     *     'message' => MessageInterface,
     *     'error' => Throwable,
     * ]
     * ```
     *
     * @psalm-param list<MessageInterface> $successMessages
     * @psalm-param list<array{message: MessageInterface, error: Throwable}> $failMessages
     */
    public function __construct(
        public readonly array $successMessages,
        public readonly array $failMessages,
    ) {
    }
}
