<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Yiisoft\View\ViewContextInterface;

/**
 * Stores the path to the view file directory and the layout view names.
 */
final class MessageBodyTemplate implements ViewContextInterface
{
    /**
     * @param string $viewPath The directory containing view files for composing mail messages.
     * @param string $htmlLayout The HTML layout view name. It is the layout used to render HTML mail body.
     * @param string $textLayout The TEXT layout view name. This is the layout used to render TEXT mail body.
     */
    public function __construct(private string $viewPath, private string $htmlLayout = 'layouts/html', private string $textLayout = 'layouts/text')
    {
    }

    /**
     * Returns the directory containing view files for composing mail messages.
     *
     * @return string The directory containing view files for composing mail messages.
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    /**
     * Returns the HTML layout view name.
     *
     * @return string The HTML layout view name.
     */
    public function getHtmlLayout(): string
    {
        return $this->htmlLayout;
    }

    /**
     * Returns the TEXT layout view name.
     *
     * @return string The TEXT layout view name.
     */
    public function getTextLayout(): string
    {
        return $this->textLayout;
    }
}
