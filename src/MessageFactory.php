<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use InvalidArgumentException;

use function is_subclass_of;

/**
 * Class MessageFactory that implements MessageFactoryInterface
 */
class MessageFactory implements MessageFactoryInterface
{
    /**
     * @var string the message class name.
     */
    private string $class;

    /**
     * @param string $class message class name.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $class)
    {
        if (!is_subclass_of($class, MessageInterface::class)) {
            throw new InvalidArgumentException('Class ' . $class . ' does not implement ' . MessageInterface::class);
        }

        $this->class = $class;
    }

    public function create(MailerInterface $mailer): MessageInterface
    {
        /** @var MessageInterface $message */
        $message = new $this->class();
        return $message->setMailer($mailer);
    }
}
