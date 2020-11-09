<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

use function call_user_func;
use function date;
use function file_put_contents;
use function is_dir;
use function microtime;
use function mkdir;
use function random_int;
use function sprintf;

/**
 * FileMailer is a mock mailer that save email messages in files instead of sending them.
 */
class FileMailer extends BaseMailer
{
    /**
     * The path where message files located.
     * @var string $path
     */
    private string $path;

    /**
     * Returns path.
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets path.
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @param MessageFactoryInterface $messageFactory
     * @param Composer $composer
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface $logger
     * @param string $path
     */
    public function __construct(
        MessageFactoryInterface $messageFactory,
        Composer $composer,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger,
        string $path
    ) {
        parent::__construct($messageFactory, $composer, $eventDispatcher, $logger);
        $this->path = $path;
    }

    /**
     * @var callable|null a PHP callback that return a file name which will be used to
     * save the email message.
     * If not set, the file name will be generated based on the current timestamp.
     *
     * The signature of the callback is:
     *
     * ```php
     * function ($mailer, $message)
     * ```
     */
    private $filenameCallback;

    /**
     * Sets filename callback.
     * @param callable $callback
     */
    public function setFilenameCallback(callable $callback): void
    {
        $this->filenameCallback = $callback;
    }

    /**
     * @return string the filename for saving the message.
     * @throws Exception
     */
    protected function generateMessageFilename(): string
    {
        $time = (int) microtime(true);

        return date('Ymd-His-', $time) . sprintf('%04d', (int) (($time - (int) $time) * 10000)) . '-' . sprintf('%04d', random_int(0, 10000)) . '.eml';
    }

    protected function sendMessage(MessageInterface $message): void
    {
        if ($this->filenameCallback !== null) {
            /** @var string filename */
            $filename = call_user_func($this->filenameCallback, $this, $message);
        } else {
            $filename = $this->generateMessageFilename();
        }
        $filename = $this->path . DIRECTORY_SEPARATOR . $filename;
        $filepath = dirname($filename);
        if (!is_dir($filepath) && !mkdir($filepath, 0777, true) && !is_dir($filepath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $filepath));
        }
        file_put_contents($filename, $message->toString());
    }
}
