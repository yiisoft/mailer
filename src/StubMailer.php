<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use function array_values;

/**
 * This mailer keep messages in memory and does not send them.
 */
final class StubMailer implements MailerInterface
{
    /**
     * @var MessageInterface[]
     * @psalm-var list<MessageInterface>
     */
    private array $messages = [];

    /**
     * @return MessageInterface[]
     * @psalm-return list<MessageInterface>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function send(MessageInterface $message): void
    {
        $this->messages[] = $message;
    }

    public function sendMultiple(array $messages): SendResults
    {
        foreach ($messages as $message) {
            $this->send($message);
        }
        return new SendResults(array_values($messages), []);
    }
}
