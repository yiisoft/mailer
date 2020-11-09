<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Throwable;

use function get_class;
use function trigger_error;

/**
 * BaseMessage serves as a base class that implements the [[send()]] method required by [[MessageInterface]].
 *
 * @see BaseMailer
 */
abstract class BaseMessage implements MessageInterface
{
    /**
     * @var MailerInterface the mailer instance that created this message.
     * For independently created messages this is `null`.
     */
    private MailerInterface $mailer;

    public function setMailer(MailerInterface $mailer): MessageInterface
    {
        $this->mailer = $mailer;
        return $this;
    }

    /**
     * Sends this email message.
     * @throws Throwable throws an exception on send fails.
     */
    public function send(): void
    {
        $this->mailer->send($this);
    }

    /**
     * @var Throwable $error the error represents why send fails.
     */
    private Throwable $error;

    public function getError(): Throwable
    {
        return $this->error;
    }

    public function setError(Throwable $e): void
    {
        $this->error = $e;
    }

    /**
     * PHP magic method that returns the string representation of this object.
     * @return string the string representation of this object.
     */
    public function __toString()
    {
        // __toString cannot throw exception
        // use trigger_error to bypass this limitation
        try {
            return $this->toString();
        } catch (Throwable $e) {
            $message  = "Exception '" . get_class($e) . "' with message '{$e->getMessage()}' \n\nin "
                . $e->getFile() . ':' . $e->getLine() . "\n\n"
                . "Stack trace:\n" . $e->getTraceAsString();

            trigger_error($message, E_USER_ERROR);
            return '';
        }
    }
}
