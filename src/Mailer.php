<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;
use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Event\BeforeSend;

/**
 * `Mailer` serves as a base class that implements the basic functions required by {@see MailerInterface}.
 *
 * Concrete child classes may focus on implementing the {@see Mailer::sendMessage()} method.
 */
abstract class Mailer implements MailerInterface
{
    public function __construct(
        private ?MessageSettings $defaultMessageSettings = null,
        private ?EventDispatcherInterface $eventDispatcher = null,
    ) {
    }

    /**
     * Sends the given email message.
     * This method will log a message about the email being sent.
     * Child classes should implement [[sendMessage()]] with the actual email sending logic.
     *
     * @param MessageInterface $message email message instance to be sent
     *
     * @throws Throwable If sending failed.
     */
    public function send(MessageInterface $message): void
    {
        $message = $this->defaultMessageSettings?->applyTo($message) ?? $message;

        if (!$this->beforeSend($message)) {
            return;
        }

        $this->sendMessage($message);
        $this->afterSend($message);
    }

    /**
     * Sends multiple messages at once.
     *
     * This method may be implemented by some mailers which support more efficient way of
     * sending multiple messages in the same batch.
     *
     * @param MessageInterface[] $messages List of email messages, which should be sent.
     *
     * @return SendResults The result object that contains all messages and errors for failed sent messages.
     */
    public function sendMultiple(array $messages): SendResults
    {
        $successMessages = [];
        $failMessages = [];

        foreach ($messages as $message) {
            try {
                $this->send($message);
            } catch (Throwable $e) {
                $failMessages[] = ['message' => $message, 'error' => $e];
                continue;
            }
            $successMessages[] = $message;
        }

        return new SendResults($successMessages, $failMessages);
    }

    /**
     * Sends the specified message.
     *
     * This method should be implemented by child classes with the actual email sending logic.
     *
     * @param MessageInterface $message the message to be sent
     *
     * @throws Throwable If sending failed.
     */
    abstract protected function sendMessage(MessageInterface $message): void;

    /**
     * This method is invoked right before mail send.
     *
     * You may override this method to do last-minute preparation for the message.
     * If you override this method, please make sure you call the parent implementation first.
     *
     * @param MessageInterface $message The message instance.
     *
     * @return bool Whether to continue sending an email.
     */
    protected function beforeSend(MessageInterface $message): bool
    {
        /** @var BeforeSend $event */
        $event = $this->eventDispatcher?->dispatch(new BeforeSend($message));
        return !$event->isPropagationStopped();
    }

    /**
     * This method is invoked right after mail was send.
     *
     * You may override this method to do some postprocessing or logging based on mail send status.
     * If you override this method, please make sure you call the parent implementation first.
     */
    protected function afterSend(MessageInterface $message): void
    {
        $this->eventDispatcher?->dispatch(new AfterSend($message));
    }
}
