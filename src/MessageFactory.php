<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use InvalidArgumentException;

use function is_subclass_of;
use function sprintf;

/**
 * `MessageFactory` creates an instance of the mail message.
 *
 * @psalm-import-type FromType from MessageInterface
 */
final class MessageFactory implements MessageFactoryInterface
{
    /**
     * @var string The message class name.
     *
     * @psalm-var class-string<MessageInterface>
     */
    private string $class;

    /**
     * @param string $class The message class name.
     * @param string|string[]|null $from The sender email address.
     *
     * @throws InvalidArgumentException If the class does not implement `MessageInterface`.
     *
     * @psalm-param FromType|null $from
     */
    public function __construct(
        string $class,
        private array|string|null $from = null,
    ) {
        if (!is_subclass_of($class, MessageInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'Class "%s" does not implement "%s".',
                $class,
                MessageInterface::class,
            ));
        }

        $this->class = $class;
    }

    public function create(): MessageInterface
    {
        $message = new $this->class();
        if ($this->from !== null) {
            $message = $message->withFrom($this->from);
        }
        return $message;
    }
}
