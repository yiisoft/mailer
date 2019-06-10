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
     * @param MailerInterface $mailer the mailer that should be used to send this message.
     * If no mailer is given it will first check if [[mailer]] is set and if not,
     * the "mail" application component will be used instead.
     * @return bool whether this message is sent successfully.
     */
    public function send(MailerInterface $mailer = null): bool
    {
        $mailer = $mailer ?: $this->mailer;

        return $mailer->send($this);
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
