<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;
use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Event\BeforeSend;

/**
 * Mailer serves as a base class that implements the basic functions required by {@see MailerInterface}.
 *
 * Concrete child classes may focus on implementing the {@see Mailer::sendMessage()} method.
 */
abstract class Mailer implements MailerInterface
{
    public function __construct(private MessageFactoryInterface $messageFactory, private MessageBodyRenderer $messageBodyRenderer, private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * Returns a new instance with the specified message body template.
     *
     * @param MessageBodyTemplate $template The message body template instance.
     *
     * @return self The new instance.
     */
    public function withTemplate(MessageBodyTemplate $template): self
    {
        $new = clone $this;
        $new->messageBodyRenderer = $new->messageBodyRenderer->withTemplate($template);
        return $new;
    }

    /**
     * Returns a new instance with specified locale code.
     *
     * @param string $locale The locale code.
     *
     * @return self
     */
    public function withLocale(string $locale): self
    {
        $new = clone $this;
        $new->messageBodyRenderer = $new->messageBodyRenderer->withLocale($locale);
        return $new;
    }

    /**
     * Creates a new message instance and optionally composes its body content via view rendering.
     *
     * @param array<string, string>|string|null $view The view to be used for rendering the message body.
     * This can be:
     * - a string, which represents the view name for rendering the HTML body of the email.
     *   In this case, the text body will be generated by applying `strip_tags()` to the HTML body.
     * - an array with 'html' and/or 'text' elements. The 'html' element refers to the view name
     *   for rendering the HTML body, while 'text' element is for rendering the text body. For example,
     *   `['html' => 'contact-html', 'text' => 'contact-text']`.
     * - null, meaning the message instance will be returned without body content.
     *
     * The view to be rendered can be specified in one of the following formats:
     * - a relative view name (e.g. "contact") located under {@see MessageBodyRenderer::$viewPath}.
     * @param array $viewParameters The parameters (name-value pairs)
     * that will be extracted and available in the view file.
     * @param array $layoutParameters The parameters (name-value pairs)
     * that will be extracted and available in the layout file.
     *
     * @throws Throwable If an error occurred during rendering.
     *
     * @return MessageInterface The message instance.
     */
    public function compose($view = null, array $viewParameters = [], array $layoutParameters = []): MessageInterface
    {
        $message = $this->createMessage();

        if ($view === null) {
            return $message;
        }

        return $this->messageBodyRenderer->addToMessage($message, $view, $viewParameters, $layoutParameters);
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
        if (!$this->beforeSend($message)) {
            return;
        }

        $this->sendMessage($message);
        $this->afterSend($message);
    }

    /**
     * Sends multiple messages at once.
     *
     * The default implementation simply calls {@see Mailer::send()} multiple times.
     * Child classes may override this method to implement more efficient way of
     * sending multiple messages.
     *
     * @param MessageInterface[] $messages List of email messages, which should be sent.
     *
     * @return MessageInterface[] List of fails messages, the corresponding
     * error can be retrieved by {@see MessageInterface::getError()}.
     */
    public function sendMultiple(array $messages): array
    {
        $failed = [];

        foreach ($messages as $message) {
            try {
                $this->send($message);
            } catch (Throwable $e) {
                $failed[] = $message->withError($e);
            }
        }

        return $failed;
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
     * Creates a new message instance.
     *
     * @return MessageInterface The message instance.
     */
    protected function createMessage(): MessageInterface
    {
        return $this->messageFactory->create();
    }

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
        $event = $this->eventDispatcher->dispatch(new BeforeSend($message));
        return !$event->isPropagationStopped();
    }

    /**
     * This method is invoked right after mail was send.
     *
     * You may override this method to do some postprocessing or logging based on mail send status.
     * If you override this method, please make sure you call the parent implementation first.
     *
     * @param MessageInterface $message
     */
    protected function afterSend(MessageInterface $message): void
    {
        $this->eventDispatcher->dispatch(new AfterSend($message));
    }
}
