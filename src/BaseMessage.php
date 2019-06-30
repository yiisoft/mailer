<?php
namespace Yiisoft\Mailer;

/**
 * BaseMessage serves as a base class that implements the [[send()]] method required by [[MessageInterface]].
 *
 * By default, [[send()]] will use the "mail" application component to send the current message.
 * The "mail" application component should be a mailer instance implementing [[MailerInterface]].
 *
 * @see BaseMailer
 */
abstract class BaseMessage implements MessageInterface
{
    /**
     * @var MailerInterface the mailer instance that created this message.
     * For independently created messages this is `null`.
     */
    private $mailer;

    /**
     * {@inheritdoc}
     */
    public function setMailer(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Sends this email message.
     * @return bool whether this message is sent successfully.
     */
    public function send(): bool
    {
        return $this->mailer->send($this);
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
        } catch (\Throwable $e) {
            $message  = "Exception '" . get_class($e) . "' with message '{$e->getMessage()}' \n\nin "
                . $e->getFile() . ':' . $e->getLine() . "\n\n"
                . "Stack trace:\n" . $e->getTraceAsString();

            trigger_error($message, E_USER_ERROR);
            return '';
        }
    }
}
