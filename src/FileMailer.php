<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Exception;
use LogicException;
use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;

use function date;
use function dirname;
use function file_put_contents;
use function gettype;
use function is_dir;
use function is_string;
use function microtime;
use function mkdir;
use function random_int;
use function sprintf;

/**
 * `FileMailer` is a mock mailer that save email messages in files instead of sending them.
 */
final class FileMailer extends BaseMailer
{
    /**
     * @var callable|null A PHP callback that return a file name which will be used to save the email message.
     *
     * If not set, the file name will be generated based on the current
     * timestamp {@see FileMailer::generateMessageFilename()}.
     *
     * The signature of the callback is:
     *
     * ```php
     * function (MessageInterface $message): string;
     * ```
     */
    private $filenameCallback;

    /**
     * @param string $path The path where message files located.
     * @param callable|null $filenameCallback A PHP callback that return a file name which will be used to save
     * the email message.
     * @param MessageSettings|null $messageSettings The default and extra message settings.
     * @param EventDispatcherInterface|null $eventDispatcher The event dispatcher instance.
     */
    public function __construct(
        private string $path,
        callable $filenameCallback = null,
        ?MessageSettings $messageSettings = null,
        ?EventDispatcherInterface $eventDispatcher = null,
    ) {
        parent::__construct($messageSettings, $eventDispatcher);
        $this->filenameCallback = $filenameCallback;
    }

    protected function sendMessage(MessageInterface $message): void
    {
        $filename = $this->path . DIRECTORY_SEPARATOR . $this->generateMessageFilename($message);
        $filepath = dirname($filename);

        if (!is_dir($filepath) && !mkdir($filepath, 0777, true) && !is_dir($filepath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created.', $filepath));
        }

        file_put_contents($filename, $message->__toString());
    }

    /**
     * Generates a filename based on the current timestamp.
     *
     * @param MessageInterface $message The message instance.
     *
     * @throws Exception {@see https://www.php.net/manual/en/function.random-int.php}
     * @throws LogicException If {@see FileMailer::$filenameCallback} does not return a string.
     *
     * @return string The filename for saving the message.
     */
    private function generateMessageFilename(MessageInterface $message): string
    {
        if ($this->filenameCallback === null) {
            $time = (int) microtime(true);
            return date('Ymd-His-', $time) . sprintf('%04d', $time) . '-' . sprintf('%04d', random_int(0, 10000)) . '.eml';
        }

        $filename = ($this->filenameCallback)($message);

        if (!is_string($filename)) {
            throw new LogicException(sprintf('Filename must be a string. "%s" received.', get_debug_type($filename)));
        }

        return $filename;
    }
}
