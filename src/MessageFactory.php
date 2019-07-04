<?php
namespace Yiisoft\Mailer;

/**
 * Class MessageFactory that implements MessageFactoryInterface
 */
class MessageFactory implements MessageFactoryInterface
{
    /**
     * @var string $class the message class name. 
     */
    private $class;

    /**
     * @param string $class message class name.
     */
    public function __construct(string $class)
    {
        if (!is_subclass_of($class, MessageInterface::class)) {
            throw new \Exception('Class ' . $class . ' does not implement ' . MessageInterface::class);
        }

        $this->class = $class;    
    }

    /**
     * {@inheritdoc}
     */
    public function create(MailerInterface $mailer): MessageInterface
    {
        /** @var MessageInterface $message */
        $message = new $this->class;
        return $message->setMailer($mailer);
    }
}
